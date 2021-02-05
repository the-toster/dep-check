<?php
declare(strict_types=1);

namespace DepCheck\Model\Input;


final class Node
{
    public string $id;
    /** @var NodeDependency[] $depends */
    public array $depends;
    public Properties $props;

    /**
     * @param string $id
     * @param NodeDependency[] $depends
     * @param Properties $props
     */
    public function __construct(string $id, array $depends, Properties $props)
    {
        $this->id = $id;
        $this->depends = $depends;
        $this->props = $props;
    }

    public function addDependency(NodeDependency $retDep)
    {
        $this->depends[] = $retDep;
    }

}
