<?php
declare(strict_types=1);

namespace DepCheck\IO;


use DepCheck\Model\Input\FileCollectorInterface;
use DepCheck\Model\Input\SourceFile;

final class FileCollector implements FileCollectorInterface
{
    /**
     * @param string $path
     * @return SourceFile[]
     */
    public function collect(string $path): array
    {
        $filePath = glob($path.'/**.php');
        $r = [];

        foreach ($filePath as $path) {
            $r[] = new SourceFile($path, file_get_contents($path));
        }

        return $r;
    }
}
