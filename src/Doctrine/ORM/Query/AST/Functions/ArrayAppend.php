<?php

namespace Opsway\Doctrine\ORM\Query\AST\Functions;

use Doctrine\ORM\Query\AST\Functions\FunctionNode,
    Doctrine\ORM\Query\Lexer;
use Doctrine\ORM\Query\Parser;
use Doctrine\ORM\Query\SqlWalker;

class ArrayAppend extends FunctionNode
{
    private $expr1;
    private $expr2;

    public function parse(Parser $parser)
    {
        $parser->match(Lexer::T_IDENTIFIER);
        $parser->match(Lexer::T_OPEN_PARENTHESIS);
        $this->expr1 = $parser->StringPrimary();
        $parser->match(Lexer::T_COMMA);
        $this->expr2 = $parser->InputParameter();
        $parser->match(Lexer::T_CLOSE_PARENTHESIS);
    }

    public function getSql(SqlWalker $sqlWalker)
    {
        return sprintf(
            'array_append(%s, %s)',
            $this->expr1->dispatch($sqlWalker),
            $sqlWalker->walkInputParameter($this->expr2)
        );
    }
}
