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

class TruncateSentences implements TruncateSentencesInterface
{
    public function execute(string $value, int $count, string $suffix = '..')
    {
        $value = \strip_tags($value);

        $sentences = \preg_split('/(?<!\w\.\w.)(?<![A-Z][a-z]\.)(?<=\.|\?)\s/um', \trim($value), null, PREG_SPLIT_DELIM_CAPTURE);

        $sentences = array_map(function($value) {
            return trim($value);
        }, $sentences);

        if (\count($sentences) > $count) {
            return \implode(' ', \array_slice($sentences, 0, $count)) . $suffix;
        }
        return $value;
    }
}
