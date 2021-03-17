<?php

declare(strict_types=1);

namespace DepCheck\NodesCollector\Handlers;

use DepCheck\Model\Input\Node as InputNode;
use DepCheck\Model\Input\NodeDependency;
use DepCheck\Model\Input\NodePosition;
use DepCheck\Model\Input\Properties;
use DepCheck\NodesCollector\DocBlock\DocBlockService;
use DepCheck\NodesCollector\NameResolutionVisitor;
use DepCheck\NodesCollector\ParserService;
use PhpParser\Node as AstNode;
use PhpParser\NodeVisitor\NameResolver;

class AbstractHandler
{

    private ParserService $parser;
    protected DocBlockService $docBlockService;
    private NodeCollection $nodes;
    protected ?NameResolver $nameResolver;

    public function __construct(NodeCollection $nodes, NameResolver $nameResolver = null)
    {
        $this->parser = new ParserService();
        $this->docBlockService = new DocBlockService();
        $this->nodes = $nodes;
        $this->nameResolver = $nameResolver;
    }

    public function getNodes(): array
    {
        return $this->nodes->toArray();
    }

    /**
     * @param string $commentText
     * @param string $tagName
     * @return AstNode\Name[]
     */
    protected function getTypesFromDocblock(string $commentText, string $tagName): array
    {
        $stringNames = $this->docBlockService->getTypesFromDocblock($commentText, $tagName);
        return $this->convertStringsToNames($stringNames);
    }

    /**
     * @param string[] $strings
     * @return AstNode\Name[]
     */
    protected function convertStringsToNames(array $strings): array
    {
        $resolver = $this->nameResolver->getNameContext();
        $names = [];
        foreach ($strings as $relativeName) {
            if(strpos($relativeName, '\\') === 0) {
                $parserName = new AstNode\Name\FullyQualified(ltrim($relativeName, '\\'));
            } else {
                $parserName = new AstNode\Name($relativeName);
            }
            $names[] = $resolver->getResolvedClassName($parserName);
        }

        return $names;
    }

    protected function populateNode(AstNode $node): InputNode
    {
        $id = $this->parser->extractId($node);
        if ($this->nodes->has($id)) {
            return $this->nodes->get($id);
        }

        $newNode = new InputNode($id, [], new Properties(''));
        $this->nodes->set($newNode);

        return $newNode;
    }

    protected function handleTypeOccurrence($type, InputNode $parent, int $depType): void
    {
        foreach ($this->parser->getTypeNames($type) as $name) {
            $parent->addDependency($this->getDependency($name, $depType));
        }
    }

    protected function getDependency(AstNode $on, int $type): NodeDependency
    {
        return new NodeDependency($this->populateNode($on), new NodePosition($on->getLine(), 0, ''), $type);
    }

    protected function findContext(AstNode $node): ?InputNode
    {
        $node = $this->parser->findContextNode($node);
        return $node ? $this->populateNode($node) : null;
    }

    protected function handleRef($node, $depType): void
    {
        $parent = $this->findContext($node);
        if ($parent) {
            $parent->addDependency($this->getDependency($node, $depType));
        }
    }
}
