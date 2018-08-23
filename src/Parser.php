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
use Forensic\FeedParser\Exceptions\MalformedFeedException;
use Forensic\FeedParser\Exceptions\FeedTypeNotSupportedException;
use Forensic\FeedParser\Feeds\ATOMFeed;
use Forensic\FeedParser\Feeds\RSSFeed;
use Forensic\FeedParser\Feeds\RDFFeed;

/**
 * Class Parser
*/
class Parser
{
    private $_default_lang = '';
    private $_remove_styles = null;
    private $_remove_scripts = null;

    /**
     *@param string $default_lang - fallback feed default language.
     *@param bool $remove_styles - boolean value indicating if inline style attributes and
     * style elements should be removed
     *@param bool $remove_scripts - boolean value indicating if inline on* event script
     * attributes event listeners and script elements should be removed
    */
    public function __construct(string $default_lang = 'en', bool $remove_styles = true,
        bool $remove_scripts = true)
    {
        $this->setDefaultLanguage($default_lang);
        $this->removeStyles($remove_styles);
        $this->removeScripts($remove_scripts);
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
     * sets or returns the remove styles parse attributes
     *
     *@param bool [$remove_styles] - the remove styles attributes
     *@return bool|this
    */
    public function removeStyles(bool $remove_styles = null)
    {
        if (is_null($remove_styles))
            return $this->_remove_styles;

        $this->_remove_styles = $remove_styles;
        return $this;
    }

    /**
     * sets or returns the remove scripts parse attributes
     *
     *@param bool [$remove_scripts] - the remove scripts attributes
     *@return bool|this
    */
    public function removeScripts(bool $remove_scripts = null)
    {
        if (is_null($remove_scripts))
            return $this->_remove_scripts;

        $this->_remove_scripts = $remove_scripts;
        return $this;
    }

    /**
     * creates a feed parser, and xml document, feeds the document to the parser, and returns
     * the result from the parser
    */
    private function parse(string $xml)
    {
        $xml_instance = new XML($xml);
        if (!$xml_instance->status())
            throw new MalformedFeedException(implode("\n", $xml_instance->errors()));

        $doc = $xml_instance->document();
        $xpath = new XPath($doc);

        //inspect feed type
        $feed_name = $doc->documentElement->localName;
        $model = null;
        switch(strtolower($feed_name))
        {
            case 'feed':
                $model = ATOMFeed::class;
                break;
            case 'rss':
                $model = RSSFeed::class;
                break;
            case 'rdf':
                $model = RDFFeed::class;
                break;
            default:
                throw new FeedTypeNotSupportedException(
                    $feed_name . ' feed type is currently not support'
                );
                break;
        }

        return new $model(
            $xpath,
            $this->getDefaultLanguage(),
            $this->removeStyles(),
            $this->removeScripts()
        );
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