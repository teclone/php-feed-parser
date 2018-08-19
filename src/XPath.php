<?php
declare(strict_types = 1);

namespace Forensic\FeedParser;

use DOMDocument;
use DOMXPath;
use DOMNode;

class XPath
{
    private $_dom_xpath = null;

    public function __construct(DOMDocument $doc)
    {
        $this->_dom_xpath = new DOMXPath($doc);
    }

    /**
     * registers namespace
     *
     *@return boolean
    */
    public function registerNamespace(string $prefix, string $namespacePrefix)
    {
        return $this->_dom_xpath->registerNamespace($prefix, $namespacePrefix);
    }

    /**
     * registers namespaces
    */
    public function registerNamespaces(array $namespaces)
    {
        foreach($namespaces as $prefix => $namespacePrefix)
            if (is_string($prefix) && is_string($namespacePrefix))
                $this->registerNamespace($prefix, $namespacePrefix);
    }

    /**
     * queries the document and returns a single result item or null
     *
     *@return DOMNode|null
    */
    public function selectNode(string $expression, DOMNode $context_node = null)
    {
        $result = $this->_dom_xpath->query($expression, $context_node);
        if ($result === false || $result->length === 0)
            return null;

        return $result->item(0);
    }

    /**
     * queries the document and returns a a DOMNodeList or null
     *
     *@return DOMNodeList|null
    */
    public function selectNodes(string $expression, DOMNode $context_node = null)
    {
        $result = $this->_dom_xpath->query($expression, $context_node);
        if ($result === false)
            return null;

        return $result;
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