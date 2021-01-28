<?php
declare(strict_types=1);

namespace DepCheck\Model\Input;


final class SourceFile
{
    public string $path;
    public string $content;

    public function __construct(string $path, string $content)
    {
        $this->path = $path;
        $this->content = $content;
    }

}
