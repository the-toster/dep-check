<?php
declare(strict_types=1);

namespace DepCheck;


use DepCheck\Model\Input\InputServiceInterface;
use DepCheck\Model\LayerAssignmentService\AnnotationsService;
use DepCheck\Model\Report\Report;

final class App
{
    private InputServiceInterface $input;
    private AnnotationsService $layerAssignmentService;

    public function __construct(InputServiceInterface $input, AnnotationsService $assignment)
    {
        $this->input = $input;
        $this->layerAssignmentService = $assignment;
    }

    public function run(Rules $rules): Report
    {
        // get inputs - nodes without layers, but with relations and props
        $nodes = $this->input->getNodes();
        // convert to elements
        $elements = $this->layerAssignmentService->assign($nodes);
        // check
        $report = new DepCheck\Model\DependencyChecker\Service($rules);
        return new Report();
    }
}
