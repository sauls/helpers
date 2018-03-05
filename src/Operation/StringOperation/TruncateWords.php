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

class TruncateWords implements TruncateWordsInterface
{
    public function execute(string $value, int $count, string $suffix = '...'): string
    {
        $value = \strip_tags($value);

        $words = \preg_split('/(\s+)/u', \trim($value), null, PREG_SPLIT_DELIM_CAPTURE);
        if (\count($words) / 2 > $count) {
            return \implode('', \array_slice($words, 0, ($count * 2) - 1)) . $suffix;
        }
        return $value;
    }
}
