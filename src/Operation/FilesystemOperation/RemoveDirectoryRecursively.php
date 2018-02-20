<?php
/**
 * This file is part of the sauls/helpers package.
 *
 * @author    Saulius Vaičeliūnas <vaiceliunas@inbox.lt>
 * @link      http://saulius.vaiceliunas.lt
 * @copyright 2018
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sauls\Component\Helper\Operation\FilesystemOperation;


class RemoveDirectoryRecursively implements RemoveDirectoryRecursivelyInterface
{
    public function execute(string $directory): bool
    {
        if (\is_dir($directory)) {
            $objects = \scandir($directory, SCANDIR_SORT_NONE);
            foreach ($objects as $object) {
                $this->removeSubDirectoriesAndFiles($object, $directory);
            }

            \reset($objects);

            return \rmdir($directory);
        }

        return false;
    }

    private function removeSubDirectoriesAndFiles($object, string $directory): void
    {
        if ($object !== '.' && $object !== '..') {

            $currentDirectory = $this->createDirectoryPath([$directory, $object]);

            if (filetype($currentDirectory) === 'dir') {
                $this->execute($currentDirectory);
            } else {
                \unlink($currentDirectory);
            }
        }
    }

    private function createDirectoryPath(array $directories): string
    {
        return implode(DIRECTORY_SEPARATOR, $directories);
    }
}
