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

namespace Sauls\Component\Helper\Operation\StringOperation;

class ExplodeWithMultiDelimiters implements ExplodeWithMultiDelimitersInterface
{
    public function execute(array $delimiters = [], string $value): array
    {
        return \explode($delimiters[0], \str_replace($delimiters, $delimiters[0], $value));
    }
}
