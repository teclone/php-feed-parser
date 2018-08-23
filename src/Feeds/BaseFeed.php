<?php
declare(strict_types = 1);

namespace Forensic\FeedParser\Feeds;

use Forensic\FeedParser\Enums\FeedTypes;
use Forensic\FeedParser\XPath;

class BaseFeed
{
    /**
     * feed type
    */
    protected $_type = null;

    /**
     * feed id
    */
    protected $_id = '';

    /**
     * feed title
    */
    protected $_title = '';

    /**
     * url link to feed's homepage
    */
    protected $_link = '';

    /**
     * short description for the feed
    */
    protected $_description = '';

    /**
     * image associated with the feed
    */
    protected $_image = [
        'src' => '', //image src link
        'link' => '', //url that this image links to, likely the feed's homepage
        'title' => '', //image title, will likely correspond to feed's homepage title
    ];

    /**
     * copyright notice associated with the use of this feed
    */
    protected $_copyright = '';

    /**
     * time string describing when this feed was last updated
    */
    protected $_lastUpdated = '';

    /**
     * software used in generating the feed
    */
    protected $_generator = '';

    /**
     * feed's language
    */
    protected $_language = '';

    /**
     * what category does this feed cover
    */
    protected $_category = '';

    /**
     * feed items
    */
    protected $_items = [];

    /**
     *
     *@param FeedTypes $feed_type - the feed type
     *@param string $default_lang - the default feed language
     *@param XPath $xpath - the feed document associated xpath
     *@param array $namespaces - the namespaces to be used in processing the feed
     *@param array $property_selectors - array of alternate property xpath selectors
     *@param string $item_selector - xpath expression that selects feed items,
     *@param bool $remove_styles - boolean indicating if style elements and attributes should
     * be stripped out
     *@param bool $remove_scripts - boolean indicating if script elements and on*
     * event handlers should be removed
    */
    public function __construct(FeedTypes $feed_type, string $default_lang, XPath $xpath,
        array $namespaces, array $property_selectors, string $item_selector,
        bool $remove_styles, bool $remove_scripts)
    {
        $this->_type = $feed_type;
        $this->_language = $default_lang;

        //register namespaces and parse the feed
        $xpath->registerNamespaces($namespaces);
    }
}