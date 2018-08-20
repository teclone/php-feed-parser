<?php
/**
 * The parser module
 *
 * PHP Version 7.1
 *
 *@author Harrison ifeanyichukwu <harrisonifeanyichukwu@gmail.com>
*/
declare(strict_types = 1);

namespace Forensic\FeedParser;

use Exception;
use Forensic\FeedParser\Exceptions\InvalidURLException;
use Forensic\FeedParser\Exceptions\ResourceNotFoundException;
use Forensic\FeedParser\Exceptions\FileNotFoundException;

/**
 * Class Parser
*/
class Parser
{
    private $_default_lang = '';

    public function __construct(string $default_lang = 'en')
    {
        $this->setDefaultLanguage($default_lang);
    }

    /**
     * set default language
    */
    public function setDefaultLanguage(string $default_lang)
    {
        $this->_default_lang = $default_lang;
    }

    /**
     * return default language
    */
    public function getDefaultLanguage()
    {
        return $this->_default_lang;
    }

    /**
     * creates a feed parser, and xml document, feeds the document to the parser, and returns
     * the result from the parser
    */
    private function parse(string $xml)
    {
        return null;
    }

    /**
     * Fetches feed from a given url and parses it.
     *
     *@param string $url - the resource url
    */
    public function parseFromURL(string $url)
    {
        if (!filter_var($url, FILTER_VALIDATE_URL))
            throw new InvalidURLException($url . ' is not a valid resource url');

        try
        {
            $xml = file_get_contents($url);
            if ($xml === false)
                throw new Exception('xml resource not found');
        }
        catch(Exception $ex)
        {
            throw new ResourceNotFoundException($ex->getMessage());
        }

        return $this->parse($xml);
    }

    /**
     * Fetches feed from a given file and parses it.
     *
     *@param string $filename - the file path
    */
    public function parseFromFile(string $filename)
    {
        if (!file_exists($filename))
            throw new FileNotFoundException($filename . ' does not exist');

        $xml = file_get_contents($filename);

        return $this->parse($xml);
    }

    /**
     * Parses feed from the given xml string
     *
     *@param string $xml - the xml string
    */
    public function parseFromString(string $xml)
    {
        return $this->parse($xml);
    }
}