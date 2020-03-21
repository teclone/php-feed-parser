<?php
declare(strict_types = 1);

namespace Forensic\FeedParser\Feeds;

use Forensic\FeedParser\Enums\FeedTypes;
use Forensic\FeedParser\XPath;

class RSSFeed extends BaseFeed
{
    public function __construct(XPath $xpath, string $default_lang, array $parser_options)
    {
        $namespaces = [
            'def' => 'http://purl.org/rss/1.0/',
            'rdf' => 'http://www.w3.org/1999/02/22-rdf-syntax-ns#',
            'dc' => 'http://purl.org/dc/elements/1.1/',
            'sy' => 'http://purl.org/rss/1.0/modules/syndication',
            'enc' => 'http://purl.oclc.org/net/rss_2.0/enc#',
            'content' => 'http://purl.org/rss/1.0/modules/content/',
            'taxo' => 'http://purl.org/rss/1.0/modules/taxonomy/'
        ];

        $property_selectors = [
            'id' => 'channel/title',
            'title' => 'channel/title',
            'link' => 'channel/link',
            'description' => 'channel/description',
            'image' => [
                'src' => 'channel/image/url',
                'link' => 'channel/image/link',
                'title' => 'channel/image/title'
            ],
            'copyright' => 'channel/copyright',
            'publisher' => 'channel/managingEditor || channel/webMaster',
            'lastUpdated' => 'channel/lastBuildDate || channel/pubDate',
            'generator' => 'channel/generator',
            'language' => 'channel/language',
            'category' => 'channel/category'
        ];

        $items_selector = 'channel/item';

        parent::__construct(
            new FeedTypes(FeedTypes::RSS_FEED),
            $default_lang,
            $xpath,
            $namespaces,
            $property_selectors,
            $items_selector,
            $parser_options
        );
    }
}