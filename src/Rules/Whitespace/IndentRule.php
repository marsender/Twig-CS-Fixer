<?php

declare(strict_types=1);

namespace TwigCsFixer\Rules\Whitespace;

use TwigCsFixer\Rules\AbstractFixableRule;
use TwigCsFixer\Rules\ConfigurableRuleInterface;
use TwigCsFixer\Token\Token;

/**
 * Ensures that files are not indented with tabs.
 */
final class IndentRule extends AbstractFixableRule implements ConfigurableRuleInterface
{
    public function __construct(private int $spaceRatio = 4)
    {
    }

    public function getConfiguration(): array
    {
        return [
            'spaceRatio' => $this->spaceRatio,
        ];
    }

    protected function process(int $tokenPosition, array $tokens): void
    {
        $token = $tokens[$tokenPosition];
        if (!$this->isTokenMatching($token, Token::TAB_TOKENS)) {
            return;
        }

        $fixer = $this->addFixableError('A file must not be indented with tabs.', $token);
        if (null === $fixer) {
            return;
        }

        $fixer->replaceToken(
            $tokenPosition,
            str_replace("\t", str_repeat(' ', $this->spaceRatio), $token->getValue())
        );
    }
}
