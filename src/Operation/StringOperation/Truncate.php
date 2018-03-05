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


class Truncate implements TruncateInterface
{
    private $encoding = 'utf-8';

    public function execute(string $value, int $length, string $suffix = '...'): string
    {
        $value = \strip_tags($value);

        if (\mb_strlen($value, $this->encoding) > $length) {
            return rtrim(mb_substr($value, 0, $length, $this->encoding)) . $suffix;
        }
        return $value;
    }
}
