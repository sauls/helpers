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

namespace Sauls\Component\Helper\Operation\ArrayOperation;

class DeepSearch implements DeepSearchInterface
{
    /**
     * @var FlattenInterface
     */
    private $arrayFlattenOperation;
    /**
     * @var MergeInterface
     */
    private $arrayMergeOperation;

    public function __construct(FlattenInterface $arrayFlattenOperation, MergeInterface $arrayMergeOperation)
    {

        $this->arrayFlattenOperation = $arrayFlattenOperation;
        $this->arrayMergeOperation = $arrayMergeOperation;
    }

    public function execute(array $array, $searchValue): array
    {
        $foundedValueArrayPath = [];
        foreach ($array as $key => $value) {
            if ($path = $this->deepSearchValuePath([$key, $value], $searchValue)) {
                $foundedValueArrayPath[] = $path;
            }
        }

        return $foundedValueArrayPath;
    }

    private function deepSearchValuePath(array $parameters, $searchValue, $path = [])
    {
        [$key, $value] = $parameters;

        if (\is_array($value) && $subPath = $this->execute($value, $searchValue)) {
            return $this->arrayFlattenOperation->execute(
                $this->arrayMergeOperation->execute($path, [$key], $subPath)
            );
        }

        if ($value === $searchValue) {
            return [$key];
        }

        return [];
    }
}
