<?php
declare(strict_types=1);

namespace DepCheck\Input;


final class StringInputService implements InputServiceInterface
{
    private string $input;

    public function __construct(string $input)
    {
        $this->input = $input;
    }

    /**
     * @inheritDoc
     */
    public function getNodes(): array
    {
        return [];
    }
}
