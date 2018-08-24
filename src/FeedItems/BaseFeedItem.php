<?php
declare(strict_types = 1);

namespace Forensic\FeedParser\FeedItems;

use Forensic\FeedParser\Enums\FeedItemTypes;
use Forensic\FeedParser\XPath;
use DOMElement;
use Forensic\FeedParser\Traits\Parser;
use Forensic\FeedParser\ParameterBag;

class BaseFeedItem
{
    use Parser;

    /**
     * feed item type
    */
    protected $_type = null;

    /**
     * feed item id
    */
    protected $_id = '';

    /**
     * feed item title
    */
    protected $_title = '';

    /**
     * url link to feed item's homepage
    */
    protected $_link = '';

    /**
     * feed item content
    */
    protected $_content = '';

    /**
     * image associated with the feed item
    */
    protected $_image = [
        'src' => '', //image src link
        'link' => '', //url that this image links to, likely the feed item's homepage
        'title' => '', //image title, will likely correspond to feed item's homepage title
    ];

    /**
     * media type associated with this item
    */
    protected $_enclosure = [

        'type' => '', //enclose media type

        'url' => '', //enclosure media url location

        'length' => '' // enclosure media length in bytes
    ];

    /**
     * time string describing when this feed item was last updated
    */
    protected $_lastUpdated = '';

    /**
     * what category does this feed item cover
    */
    protected $_category = '';

    /**
     * item's source
    */
    protected $_source = '';

    /**
     * who is the author of this item?
    */
    protected $_author = '';

    /**
     *
     *@param FeedItemTypes $feed_type - the feed item type
     *@param DOMElement $item - the feed item node
     *@param XPath $xpath - the xpath instance for the feed
     *@param array $property_selectors - array of property selector maps
    */
    public function __construct(FeedItemTypes $feed_item_type, DOMElement $item, XPath $xpath,
        array $property_selectors, array $parser_options)
    {
        $this->_type = $feed_item_type;

        $xpath->setContextNode($item);

        $this->parse($xpath, $property_selectors, $parser_options);
        $this->parseImage();
    }

    /**
     *
     *@param string $property - the property to retrieve
     *@return string|null
    */
    public function __get(string $property)
    {
        $this_property = '_' . $property;
        if (property_exists($this, $this_property))
        {
            $value = $this->{$this_property};
            if (is_array($value))
                return new ParameterBag($value);
            else
                return $value;
        }

        return null;
    }
}