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


class TruncateHtml implements TruncateHtmlInterface
{
    private $truncateOperation;

    private $truncateOperationMethod = 'truncate';

    private $truncateOperationMethods = [
        self::TRUNCATE_HTML_LETTER, self::TRUNCATE_HTML_WORD, self::TRUNCATE_HTML_SENTENCE
    ];

    private $encoding = 'utf-8';

    private $truncateWordsOperation;

    private $truncateSentencesOperation;
    /**
     * @var CountWordsInterface
     */
    private $countWordsOperation;
    /**
     * @var CountSentencesInterface
     */
    private $countSentencesOperation;

    public function __construct(
        TruncateInterface $truncateOperation,
        TruncateWordsInterface $truncateWordsOperation,
        TruncateSentencesInterface $truncateSentencesOperation,
        CountWordsInterface $countWordsOperation,
        CountSentencesInterface $countSentencesOperation
    ) {
        $this->truncateOperation = $truncateOperation;
        $this->truncateWordsOperation = $truncateWordsOperation;
        $this->truncateSentencesOperation = $truncateSentencesOperation;
        $this->countWordsOperation = $countWordsOperation;
        $this->countSentencesOperation = $countSentencesOperation;
    }

    public function execute(string $value, int $length, string $suffix = '...'): string
    {
        $config = \HTMLPurifier_Config::create(null);

        $lexer = \HTMLPurifier_Lexer::create($config);
        $tokens = $lexer->tokenizeHTML($value, $config, new \HTMLPurifier_Context());
        $openTokens = [];
        $totalCount = 0;
        $depth = 0;
        $truncated = [];
        foreach ($tokens as $token) {
            if ($token instanceof \HTMLPurifier_Token_Start) {
                $openTokens[$depth] = $token->name;
                $truncated[] = $token;
                ++$depth;
            } elseif ($token instanceof \HTMLPurifier_Token_Text && $totalCount <= $length) {

                $currentLength = $this->truncateByMethod($token->data, $length - $totalCount, $suffix);

                $totalCount += $currentLength;
                $truncated[] = $token;
            } elseif ($token instanceof \HTMLPurifier_Token_End) {
                if ($token->name === $openTokens[$depth - 1]) {
                    --$depth;
                    unset($openTokens[$depth]);
                    $truncated[] = $token;
                }
            } elseif ($token instanceof \HTMLPurifier_Token_Empty) {
                $truncated[] = $token;
            }
            if ($totalCount >= $length) {
                if (0 < \count($openTokens)) {
                    \krsort($openTokens);
                    foreach ($openTokens as $name) {
                        $truncated[] = new \HTMLPurifier_Token_End($name);
                    }
                }
                break;
            }
        }
        $context = new \HTMLPurifier_Context();
        $generator = new \HTMLPurifier_Generator($config, $context);

        return $generator->generateFromTokens($truncated).($totalCount >= $length ? $suffix : '');
    }

    private function truncateByMethod(string &$value, int $length, string $suffix): int
    {
        return $this->{$this->truncateOperationMethod}($value, $length, $suffix);
    }

    private function truncate(string &$value, int $length, string $suffix): int
    {
        $value = $this->truncateOperation->execute($value, $length, $suffix);

        return \mb_strlen($value, $this->encoding);
    }

    private function truncateWords(string &$value, int $count, string $suffix): int
    {
        $value = $this->truncateWordsOperation->execute($value, $count, $suffix);

        return $this->countWordsOperation->execute($value);
    }

    private function truncateSentences(string &$value, int $count, string $suffix): int
    {
        $value = $this->truncateSentencesOperation->execute($value, $count, $suffix);
        return $this->countSentencesOperation->execute($value);
    }

    /**
     * @param string $truncateOperationMethod
     *
     * @return TruncateHtml
     */
    public function setTruncateOperationMethod(string $truncateOperationMethod): void
    {
        if (!in_array($truncateOperationMethod, $this->truncateOperationMethods)) {
            throw new \InvalidArgumentException(\sprintf('`%s` truncate operation method is not supported.', $truncateOperationMethod));
        }

        $this->truncateOperationMethod = $truncateOperationMethod;
    }


}
