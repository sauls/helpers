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

namespace Sauls\Component\Helper\Operation\TypeOperation\Collection;

use Sauls\Component\Helper\Exception\InvalidTypeConverterException;
use Sauls\Component\Helper\Operation\TypeOperation\Converter\ConverterInterface;

class ConverterCollection implements ConverterCollectionInterface
{
    private $converters = [];

    private static $typeAliases = [
        'boolean' => 'bool',
        'integer' => 'int',
        'long' => 'int',
        'double' => 'float',
        'real' => 'float',
        '[]' => 'array',
    ];

    public function __construct($converters = [])
    {
        if ($converters instanceof \Traversable) {
            $converters = iterator_to_array($converters);
        }

        $this->add($converters);
    }

    public function add(array $converters): void
    {
        foreach ($converters as $ensurer) {
            $this->set($ensurer);
        }
    }

    public function set(ConverterInterface $converter): void
    {
        $type = $this->resolveType($converter->getType());
        $this->converters[$type][] = $converter;
        $this->removeDuplicateConverters($type);
        $this->sort($type);
    }

    private function removeDuplicateConverters($type): void
    {
        $this->converters[$type] = array_unique($this->converters[$type], SORT_REGULAR);
    }

    private function sort(string $type): void
    {
        uasort(
            $this->converters[$this->resolveType($type)],
            function (ConverterInterface $ensurer1, ConverterInterface $ensurer2) {

                $priority1 = $ensurer1->getPriority();
                $priority2 = $ensurer2->getPriority();

                if ($priority1 === $priority2) {
                    return 0;
                }

                return ($priority1 > $priority2) ? -1 : 1;
            }
        );
    }

    /**
     * @return array|ConverterInterface[]
     *
     * @throws \Sauls\Component\Helper\Exception\InvalidTypeConverterException
     */
    public function get(string $type): array
    {
        try {
            return $this->converters[$this->resolveType($type)];
        } catch (\Throwable $t) {
            throw new InvalidTypeConverterException(
                sprintf('Invalid converter type `%s`.', $type)
            );
        }
    }

    /**
     * @param string $type
     *
     * @return mixed
     */
    private function resolveType(string $type)
    {
        return \strtr($type, self::$typeAliases);
    }
}
