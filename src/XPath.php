<?php
declare(strict_types = 1);

namespace Forensic\FeedParser;

use DOMDocument;
use DOMXPath;
use DOMNode;

class XPath
{
    private $_dom_xpath = null;
    private $_context_node = null;

    public function __construct(DOMDocument $doc)
    {
        $this->_dom_xpath = new DOMXPath($doc);
    }

    public function getContextNode()
    {
        return $this->_context_node;
    }

    public function setContextNode(DOMNode $context_node)
    {
        $this->_context_node = $context_node;
    }

    protected function resolveContextNode(DOMNode $context_node = null)
    {
        if (!is_null($context_node))
            return $context_node;
        else
            return $this->_context_node;
    }

    /**
     * registers namespace
     *
     *@param string $prefix - the namespace prefix
     *@param string $namespace_prefix - the namespace uri
     *@return boolean
    */
    public function registerNamespace(string $prefix, string $namespace_prefix)
    {
        return $this->_dom_xpath->registerNamespace($prefix, $namespace_prefix);
    }

    /**
     * registers namespaces
     *
     *@param array $namespaces - array of namespace prefix=>uri key value pairs
    */
    public function registerNamespaces(array $namespaces)
    {
        foreach($namespaces as $prefix => $namespace_prefix)
            if (is_string($prefix) && is_string($namespace_prefix))
                $this->registerNamespace($prefix, $namespace_prefix);
    }

    /**
     * queries the document and returns a single result item or null
     *
     *@param string $expressions - the xpath selector expression
     *@param DOMNode $context_node - the selector context
     *@return DOMNode|null
    */
    public function selectNode(string $expression, DOMNode $context_node = null)
    {
        $result = $this->_dom_xpath->query($expression, $this->resolveContextNode($context_node));
        if ($result === false || $result->length === 0)
            return null;

        return $result->item(0);
    }

    /**
     * queries the document and returns a DOMNodeList of results or null
     * if no result is found
     *
     *@param string $expressions - the xpath selector expression
     *@param DOMNode $context_node - the selector context
     *@return DOMNodeList|null
    */
    public function selectNodes(string $expression, DOMNode $context_node = null)
    {
        $result = $this->_dom_xpath->query($expression, $this->resolveContextNode($context_node));
        if ($result === false || $result->length === 0)
            return null;

        return $result;
    }

    /**
     * queries the document and returns a single result item or null
     *
     *@param string $expressions - the alternate xpath selector expressions
     *@param DOMNode $context_node - the selector context
     *@return DOMNode|null
    */
    public function selectAltNode(string $expressions, DOMNode $context_node = null)
    {
        foreach(preg_split('/\s*\|\|\s*/', $expressions) as $expression) {
            $result = $this->selectNode($expression, $context_node);
            if ($result)
                return $result;
        }

        return null;
    }

    /**
     * queries the document and returns a DOMNodeList or null using alternate xpath
     * selector xpressions
     *
     *@param string $expressions - the alternate xpath selector expressions
     *@param DOMNode $context_node - the selector context
     *@return DOMNodeList|null
    */
    public function selectAltNodes(string $expressions, DOMNode $context_node = null)
    {
        foreach(preg_split('/\s*\|\|\s*/', $expressions) as $expression) {
            $result = $this->selectNodes($expression, $context_node);
            if ($result && $result->length > 0)
                return $result;
        }

        return null;
    }

    /**
     * returns the dom xpath
     *
     *@return DOMXPath
    */
    public function get()
    {
        return $this->_dom_xpath;
    }
}