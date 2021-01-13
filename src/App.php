<?php
declare(strict_types=1);

namespace DepCheck;


use DepCheck\Input\InputServiceInterface;
use DepCheck\LayerAssignmentService\AnnotationsService;

final class App
{
    private InputServiceInterface $input;
    private AnnotationsService $assignment;

    public function __construct(InputServiceInterface $input, AnnotationsService $assignment)
    {
        $this->input = $input;
        $this->assignment = $assignment;
    }

    public function run(): Report
    {
        $nodes = $this->input->getNodes();
        $elements = $this->assignment->assign($nodes);
        $report = new DependencyChecker\Service();
        // get inputs - nodes without layers, but with relations and props
        // convert to elements
        // check

    }
}
