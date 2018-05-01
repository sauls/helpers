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

use Sauls\Component\Helper\Operation\Factory\OperationFactory;
use Sauls\Component\Helper\Operation\StringOperation;

/**
 * @throws \Exception
 */
function string_camelize(string $value): string
{
    return OperationFactory::create(StringOperation\Camelize::class)->execute($value);
}

/**
 * @throws \Exception
 */
function string_snakeify(string $value): string
{
    return OperationFactory::create(StringOperation\Snakeify::class)->execute($value);
}

/**
 * @throws \Exception
 */
function explode_using_multi_delimiters(array $delimiters = ['.'], string $value): array
{
    return OperationFactory::create(StringOperation\ExplodeWithMultiDelimiters::class)->execute($delimiters, $value);
}

/**
 * @throws \Exception
 */
function base64_url_encode(string $value): string
{
    return OperationFactory::create(StringOperation\Base64UrlEncode::class)->execute($value);
}

/**
 * @throws \Exception
 */
function base64_url_decode(string $value): string
{
    return OperationFactory::create(StringOperation\Base64Decode::class)->execute($value);
}

function count_words(string $value): int
{
    return OperationFactory::create(StringOperation\CountWords::class)->execute($value);
}

function count_sentences(string $value): int
{
    return OperationFactory::create(StringOperation\CountSentences::class)->execute($value);
}

function truncate(string $value, int $length, string $suffix = '...'): string
{
    return OperationFactory::create(StringOperation\Truncate::class)->execute($value, $length, $suffix);
}

function truncate_words(string $value, int $count, string $suffix = '...'): string
{
    return OperationFactory::create(StringOperation\TruncateWords::class)->execute($value, $count, $suffix);
}

function truncate_sentences(string $value, int $count, string $suffix = '...'): string
{
    return OperationFactory::create(StringOperation\TruncateSentences::class)->execute($value, $count, $suffix);
}

function truncate_html(string $value, int $length, string $suffix): string
{
    return OperationFactory::create(StringOperation\TruncateHtml::class)->execute($value, $length, $suffix);
}

function truncate_html_worlds(string $value, int $count, string $suffix = '...'): string
{
    $truncateOperation = OperationFactory::create(StringOperation\TruncateHtml::class);
    $truncateOperation->setTruncateOperationMethod(StringOperation\TruncateHtmlInterface::TRUNCATE_HTML_WORD);

    return $truncateOperation->execute($value, $count, $suffix);
}

function truncate_html_sentences(string $value, int $count, string $suffix = '...'): string
{
    $truncateOperation = OperationFactory::create(StringOperation\TruncateHtml::class);
    $truncateOperation->setTruncateOperationMethod(StringOperation\TruncateHtmlInterface::TRUNCATE_HTML_SENTENCE);

    return $truncateOperation->execute($value, $count, $suffix);
}

function string_in(string $value, array $values): bool
{
    return OperationFactory::create(StringOperation\StringIn::class)->execute($value, $values);
}

