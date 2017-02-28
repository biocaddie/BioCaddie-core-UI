<?php
//require_once dirname(__FILE__) . '/Parser.php';
/**
 * Created by PhpStorm.
 * For analyzing Boolean logic
 * User: rliu1
 * Date: 2/3/16
 * Time: 1:19 PM
 */
class Context
{
    protected $_parser =null;  //
    protected $_children_contexts = array();
    protected $_raw_content = array();
    protected $_operations = array();

    const T_OPERATOR=1;
    const T_SCOPE_OPEN=2;
    const T_SCOPE_CLOSE=3;
    const T_word=4;

    public function set_parser(Parser $parser){
        $this->_parser = $parser;
    }

    public function add_operation($operation){
        $this->_operations[] = $operation;
    }

    public function get_operations()
    {
        return $this->_operations;
    }

    /**
     * handle the next token from the tokenized list. Example actions on a token would be to:
     *  - add it to the current context expression list;
     *  - push a new context on the context stack;
     * - or pop a context off the stack.
     */
    public function handle_token($token)
    {
        $type = null;

        if ($token === ')') $type = self::T_SCOPE_CLOSE;
        if ($token === '(') $type = self::T_SCOPE_OPEN;
        if (in_array($token, array('AND', 'OR', 'NOT'))) $type = self::T_OPERATOR;

        if (is_null($type)) {
            $type = self::T_word;
        }

        switch ($type){
            case self::T_OPERATOR:
            case self::T_word:
                $this->_operations[] = $token;
                break;
            case self::T_SCOPE_OPEN:
                $this->_parser->push_context(new Context());
                break;
            case self::T_SCOPE_CLOSE:

                $context_operation = $this->_parser->pop_context()->_operations;
                $new_context =  $this->_parser->get_context();

                if(is_null($context_operation) || (!$new_context)){
                    // this means there are more right parentheses than left parentheses
                    var_dump("There are more right parentheses than left parentheses");
                }
                $new_context->add_operation($context_operation);
                break;
        }
    }
}