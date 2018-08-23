<?php
declare(strict_types = 1);

namespace Forensic\FeedParser\FeedItems;

use Forensic\FeedParser\Enums\FeedItemTypes;
use Forensic\FeedParser\XPath;
use DOMElement;

class ATOMFeedItem extends BaseFeedItem
{
    public function __construct(DOMElement $item, XPath $xpath,
        bool $remove_styles, bool $remove_scripts)
    {
        $property_selectors = [
            'id' => 'atom:id || atom:source/atom:id',

            // a text construct
            'title' => 'atom:title || atom:source/atom:title',

            'link' => 'atom:link[@rel="alternate"]/@href || ' .
                'atom:source/atom:link[@rel="alternate"]/@href || ' .
                'atom:link/@href || atom:source/atom:link/@href',

            // a text construct
            'content' => 'atom:content || atom:source/atom:content || atom:summary || ' .
                'atom:source/atom:content',

            'enclosure' => [
                'type' => 'atom:link[@rel="enclosure"]/@type',
                'url' => 'atom:link[@rel="enclosure"]/@href',
                'length' => 'atom:link[@rel="enclosure"]/@length'
            ],

            'source' => 'atom:source/atom:title || atom:source/atom:subtitle',

            //'image' => { 'src' => '', 'link' => '', 'title' => '' }, // to be parsed specially

            //date construct
            'lastUpdated' => 'atom:updated || atom:source/atom:updated || atom:published || ' .
                'atom:source/atom:published',

            'author' => 'atom:author/atom:name || atom:source/atom:author/atom:name || ' .
                    'parent::atom:feed/atom:author/atom:name',

            //defaults to the parent category
            'category' => 'atom:category/@term || atom:source/atom:category/@term || ' .
                'parent::atom:feed/atom:category'
        ];

        parent::__construct(
            new FeedItemTypes(FeedItemTypes::ATOM_FEED_ITEM),
            $item,
            $xpath,
            $property_selectors,
            $remove_styles,
            $remove_scripts
        );
    }
}