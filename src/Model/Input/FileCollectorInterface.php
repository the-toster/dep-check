<?php
declare(strict_types=1);

namespace DepCheck\Model\Input;


interface FileCollectorInterface
{

    /**
     * @param string $path
     * @return SourceFile[]
     */
    public function collect(string $path): array;

}
