<?php
declare(strict_types = 1);

namespace Forensic\FeedParser\Feeds;

use Forensic\FeedParser\Enums\FeedTypes;
use Forensic\FeedParser\XPath;

class ATOMFeed extends BaseFeed
{
    public function __construct(XPath $xpath, string $default_lang, array $parser_options)
    {
        $namespaces = [
            'atom' => 'http://www.w3.org/2005/Atom',
            'xml' => 'http://www.w3.org/XML/1998/namespace'
        ];

        $property_selectors = [
            'id' => 'atom:id',
            'title' => 'atom:title', // text construct
            'link' => 'atom:link[@rel="alternate"]/@href || atom:link/@href',
            'description' => 'atom:subtitle', // text construct
            'image' => [
                'src' => 'atom:logo',
                'link' => 'atom:link',
                'title' => 'atom:title'
            ],
            'copyright' => 'atom:rights',
            'publisher' => 'atom:contributor || atom:title',
            'lastUpdated' => 'atom:updated',
            'creator' => 'atom:generator',
            'language' => '@xml:lang',
            'category' => 'atom:category'
        ];

        $items_selector = 'atom:entry';

        parent::__construct(
            new FeedTypes(FeedTypes::ATOM_FEED),
            $default_lang,
            $xpath,
            $namespaces,
            $property_selectors,
            $items_selector,
            $parser_options
        );
    }
}