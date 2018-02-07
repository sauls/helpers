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

namespace Sauls\Component\Helper;

function rrmdir(string $directory): bool
{
    if (is_dir($directory)) {

        $objects = scandir($directory, SCANDIR_SORT_NONE);

        foreach ($objects as $object) {
            remove_directories_and_files_in_directory($object, $directory);
        }

        reset($objects);

        return rmdir($directory);
    }

    return false;
}

function remove_directories_and_files_in_directory($object, string $directory): void
{
    if ($object !== '.' && $object !== '..') {

        $currentDirectory = create_directory_path([$directory, $object]);

        if (filetype($currentDirectory) === 'dir') {
            rrmdir($currentDirectory);
        } else {
            unlink($currentDirectory);
        }
    }
}

function create_directory_path(array $directories): string
{
    return implode(DIRECTORY_SEPARATOR, $directories);
}
