<?php
declare(strict_types=1);

namespace DepCheck\Model\DependencyChecker\Result;


use DepCheck\Model\DependencyChecker\Element;

final class UnknownElement extends AbstractReportRecord
{
    public Element $element;

    public function __construct(Element $element)
    {
        $this->element = $element;
    }
}
