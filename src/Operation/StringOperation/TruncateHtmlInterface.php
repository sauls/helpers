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

use Sauls\Component\Helper\Operation\Operation;

interface TruncateHtmlInterface extends Operation
{
    public const TRUNCATE_HTML_LETTER = 'truncate';
    public const TRUNCATE_HTML_WORD = 'truncateWords';
    public const TRUNCATE_HTML_SENTENCE = 'truncateSentences';

    public function execute(string $value, int $length, string $suffix = '...'): string;
    public function setTruncateOperationMethod(string $truncateOperationMethod): void;
}
