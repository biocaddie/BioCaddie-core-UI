<?php
require_once dirname(__FILE__) . '/Context.php';
/**
 * Created by PhpStorm.
 * For analyzing Boolean logic
 * User: rliu1
 * Date: 2/3/16
 * Time: 12:53 PM
 */
class Parser
{
    protected $_content=null;
    protected $_context_stack=array();
    protected $_tree=null;
    protected $_tokens = array();

    public function __construct($content=null)
    {
        if($content){
            $this->set_Content($content);
        }
    }

    /**
     * this function does some simple syntax cleaning:
     * - remove all spaces
     * - then it runs a regex to split the contents into tokens.
     * the set of possible tokens contains words, logic operator and parentheses.
     */
    public function tokenize(){

        $this->_content = str_replace(array("\n","\r","\t"),'',$this->_content);
        $pattern = '/(AND|OR|NOT|\(|\))/';
        $this->_tokens = preg_split( $pattern, $this->_content,-1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);
        // strip whitespace from an array
        $this->_tokens = array_filter(array_map('trim', $this->_tokens));

        return $this;
    }


    /**
     *  this is the loop that transforms the tokens array into a tree structure
     */
    public function parse(){
        // this is the global scope which will contain the entire tree
        $this->push_context(new Context());

        foreach($this->_tokens as $token){
            // get the last context model from the context stack,
            // and have it handle the next token
            $this->get_context()->handle_token($token);
        }
        $this->_tree=$this->pop_context();

        return $this->_tree->get_operations();
    }

    /***  accessors and mutators ***/
    public function get_Tree()
    {
        return $this->_tree;
    }

    public function set_Content($content=null)
    {
        $this->_content = $content;
        return $this;
    }

    public function get_tokens(){
        return $this->_tokens;
    }

    public function get_context_stack(){
        return $this->_context_stack;
    }

    /*******************************************************
     * the context stack function.
     * push, pop and get the current element from the stack
     *******************************************************/
    public function push_context(Context $context){
        array_push($this->_context_stack, $context);
        $this->get_context()->set_parser($this);
    }

    public function pop_context(){
        return array_pop($this->_context_stack);
    }

    public function get_context(){
        return end($this->_context_stack);
    }
}