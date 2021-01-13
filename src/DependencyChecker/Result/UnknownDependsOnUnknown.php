<?php
declare(strict_types=1);

namespace DepCheck\DependencyChecker\Result;


use DepCheck\DependencyChecker\Element;

final class UnknownDependsOnUnknown extends AbstractReportRecord
{
    public Element $from;
    public Element $to;

    public function __construct(Element $from, Element $to)
    {
        $this->from = $from;
        $this->to = $to;
    }

}
