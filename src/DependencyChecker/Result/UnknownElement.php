<?php
declare(strict_types=1);

namespace DepCheck\DependencyChecker\Result;


use DepCheck\DependencyChecker\Element;

final class UnknownElement extends AbstractReportRecord
{
    public Element $element;

    public function __construct(Element $element)
    {
        $this->setStatus(self::UNKNOWN);
        $this->element = $element;
    }
}
