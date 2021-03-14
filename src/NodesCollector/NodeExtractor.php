<?php

declare(strict_types=1);

namespace DepCheck\NodesCollector;


use PhpParser\Lexer;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitor\NameResolver;
use PhpParser\NodeVisitor\ParentConnectingVisitor;
use PhpParser\ParserFactory;
use DepCheck\Model\Input\Node;
use DepCheck\Model\Input\SourceFile;

final class NodeExtractor
{
    /**
     * @param SourceFile $file
     * @return Node[]
     */
    public function extract(SourceFile $file): array
    {
        $lexer = new Lexer(['usedAttributes'=>['comments', 'startLine', 'endLine', 'startTokenPos', 'endTokenPos']]);
        $parser = (new ParserFactory())->create(ParserFactory::PREFER_PHP7, $lexer);

        $nodeTraverser = new NodeTraverser();

        $nameResolver = new NameResolver();
        $nodeTraverser->addVisitor($nameResolver);

        $parentResolver = new ParentConnectingVisitor();
        $nodeTraverser->addVisitor($parentResolver);

        $collectorVisitor = new Visitor($nameResolver);
        $nodeTraverser->addVisitor($collectorVisitor);

        $ast = $parser->parse($file->content);


        $collectorVisitor->setTokens($lexer->getTokens());

        $nodeTraverser->traverse($ast);
        return $collectorVisitor->getNodes();
    }


}
