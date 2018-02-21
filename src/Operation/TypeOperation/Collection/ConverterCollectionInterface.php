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

use Sauls\Component\Helper\Operation\TypeOperation\Converter\ConverterInterface;

interface ConverterCollectionInterface
{
    /**
     * @return array|ConverterInterface[]
     *
     */
    public function get(string $type): array;
    public function set(ConverterInterface $converter): void;
    public function add(array $converters): void;
}
