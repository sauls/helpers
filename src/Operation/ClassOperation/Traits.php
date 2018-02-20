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

namespace Sauls\Component\Helper\Operation\ClassOperation;

use Sauls\Component\Helper\Operation\ArrayOperation;

class Traits implements TraitsInterface
{
    /**
     * @var ArrayOperation\MergeInterface
     */
    private $mergeOperation;

    public function __construct(ArrayOperation\MergeInterface $mergeOperation)
    {
        $this->mergeOperation = $mergeOperation;
    }

    public function execute(string $class): array
    {
        $classTraits = [];

        do {
            $classTraits = $this->mergeOperation->execute(\class_uses($class), $classTraits);
        } while ($class = \get_parent_class($class));

        return \array_unique($classTraits);
    }
}
