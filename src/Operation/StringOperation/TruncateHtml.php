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
        self::TRUNCATE_HTML_LETTER,
        self::TRUNCATE_HTML_WORD,
        self::TRUNCATE_HTML_SENTENCE,
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

    private $openTokens = [];

    private $totalCount = 0;

    private $depth = 0;

    private $truncated = [];

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
        $this->resetVariables();

        $config = \HTMLPurifier_Config::create(null);

        $lexer = \HTMLPurifier_Lexer::create($config);
        $tokens = $lexer->tokenizeHTML($value, $config, new \HTMLPurifier_Context());

        $this->processTokens($tokens, $length, $suffix);

        $context = new \HTMLPurifier_Context();
        $generator = new \HTMLPurifier_Generator($config, $context);

        return $generator->generateFromTokens($this->truncated).($this->totalCount >= $length ? $suffix : '');
    }

    private function truncateByMethod(string &$value, int $length, string $suffix): int
    {
        return $this->{$this->truncateOperationMethod}($value, $length, $suffix);
    }

    /**
     * @param string $truncateOperationMethod
     *
     * @return TruncateHtml
     */
    public function setTruncateOperationMethod(string $truncateOperationMethod): void
    {
        if (!in_array($truncateOperationMethod, $this->truncateOperationMethods)) {
            throw new \InvalidArgumentException(\sprintf('`%s` truncate operation method is not supported.',
                $truncateOperationMethod));
        }

        $this->truncateOperationMethod = $truncateOperationMethod;
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

    private function resetVariables(): void
    {
        $this->openTokens = [];
        $this->depth = 0;
        $this->totalCount = 0;
        $this->truncated = [];
    }

    private function processTokens(array $tokens, int $length, string $suffix): void
    {
        foreach ($tokens as $token) {

            $this->processTokenStart($token);
            $this->processTokenText($token, $length, $suffix);
            $this->processTokenEnd($token);
            $this->processTokenEmpty($token);

            if ($this->totalCount >= $length) {
                $this->processOpenTokens();
                break;
            }
        }
    }

    private function processTokenStart($token): void
    {
        if ($token instanceof \HTMLPurifier_Token_Start) {
            $this->openTokens[$this->depth] = $token->name;
            $this->truncated[] = $token;
            ++$this->depth;
        }
    }

    private function processTokenText($token, int $length, string $suffix): void
    {
        if ($token instanceof \HTMLPurifier_Token_Text && $this->totalCount <= $length) {
            $currentLength = $this->truncateByMethod($token->data, $length - $this->totalCount, $suffix);
            $this->totalCount += $currentLength;
            $this->truncated[] = $token;
        }
    }

    private function processTokenEnd($token): void
    {
        if ($token instanceof \HTMLPurifier_Token_End) {
            if ($token->name === $this->openTokens[$this->depth - 1]) {
                --$this->depth;
                unset($this->openTokens[$this->depth]);
                $this->truncated[] = $token;
            }
        }
    }

    private function processTokenEmpty($token)
    {
        if ($token instanceof \HTMLPurifier_Token_Empty) {
            $this->truncated[] = $token;
        }
    }

    private function processOpenTokens(): void
    {
        if (0 < \count($this->openTokens)) {
            \krsort($this->openTokens);
            foreach ($this->openTokens as $name) {
                $this->truncated[] = new \HTMLPurifier_Token_End($name);
            }
        }
    }


}
