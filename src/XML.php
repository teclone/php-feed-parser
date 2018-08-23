<?php
declare(strict_types = 1);

namespace Forensic\FeedParser;

use DOMDocument;

class XML
{
    private $_doc = null;
    private $_errors = [];

    public function __construct(string $xml = null)
    {
        $this->parse($xml);
    }

    /**
     * parses the xml string into a document.
     *
     *@return boolean
    */
    public function parse(string $xml = null)
    {
        $this->_doc = null;
        $this->_errors = [];

        //if no string was provided, bail out
        if (is_null($xml))
            return false;

        $this->_doc = new DOMDocument();
        libxml_use_internal_errors(true);

        //if document was loaded successfully, bail out
        if ($this->_doc->loadXML(trim($xml)))
            return true;

        foreach (libxml_get_errors() as $error)
        {
            switch ($error->level)
            {
                //only take error and fatal level errors, ignore warnings
                case LIBXML_ERR_ERROR:
                case LIBXML_ERR_FATAL:
                    $this->_errors[] = $error->message . ' on line ' . $error->line;
                    break;
            }
        }
        libxml_clear_errors();

        //reset doc to null if error exists
        if (count($this->_errors) > 0)
            $this->_doc = null;

        return $this->status();
    }

    /**
     * returns the xml document or null if not parsed successfully.
     *
     *@return DOMDocument|null
    */
    public function document()
    {
        return $this->_doc;
    }

    /**
     * returns the parser detected errors
     *
     *@return array
    */
    public function errors()
    {
        return $this->_errors;
    }

    /**
     * returns the document parse status
     *
     *@return boolean
    */
    public function status()
    {
        return !is_null($this->_doc);
    }
}
