<?php

declare(strict_types=1);

namespace OpsWay\Doctrine\ORM\Query\AST\Functions;

use Doctrine\ORM\Query\AST\Functions\FunctionNode;
use Doctrine\ORM\Query\AST\Node;
use Doctrine\ORM\Query\Parser;
use Doctrine\ORM\Query\SqlWalker;
use Doctrine\ORM\Query\TokenType;

use function sprintf;

class ToTsquery extends FunctionNode
{
    /** @var Node|null */
    /** @psalm-suppress all */
    private $config;

    /** @var Node */
    /** @psalm-suppress all */
    private $expr1;

    public function parse(Parser $parser) : void
    {
        $parser->match(TokenType::T_IDENTIFIER);
        $parser->match(TokenType::T_OPEN_PARENTHESIS);
        $this->expr1 = $parser->StringPrimary();

        if ($parser->getLexer()->isNextToken(TokenType::T_COMMA)) {
            $parser->match(TokenType::T_COMMA);
            $this->config = $this->expr1;
            $this->expr1  = $parser->StringPrimary();
        }

        $parser->match(TokenType::T_CLOSE_PARENTHESIS);
    }

    /** @psalm-suppress all */
    public function getSql(SqlWalker $sqlWalker) : string
    {
        if (null === $this->config) {
            return sprintf(
                'to_tsquery(%s)',
                $this->expr1->dispatch($sqlWalker)
            );
        }

        return sprintf(
            'to_tsquery(%s, %s)',
            $this->config->dispatch($sqlWalker),
            $this->expr1->dispatch($sqlWalker)
        );
    }
}
