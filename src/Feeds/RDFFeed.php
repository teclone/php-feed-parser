<?php
declare(strict_types = 1);

namespace Forensic\FeedParser\Feeds;

use Forensic\FeedParser\Enums\FeedTypes;
use Forensic\FeedParser\XPath;


class RDFFeed extends BaseFeed
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
            'id' => 'def:channel/@rdf:about || def:channel/dc:identifier',
            'title' => 'def:channel/def:title || def:channel/dc:title',
            'link' => 'def:channel/def:link',
            'description' => 'def:channel/def:description || def:channel/dc:description',
            'image' => [
                'src' => 'def:image/def:url || def:image/@rdf:about',
                'link' => 'def:image/def:link || def:channel/def:link',
                'title' => 'def:image/def:title || def:image/dc:title || def:channel/def:title ' .
                    '|| def:channel/dc:title'
            ],
            'copyright' => 'def:channel/dc:rights',
            'lastUpdated' => 'def:channel/dc:date',
            'generator' => 'def:channel/dc:publisher || def:channel/dc:creator',
            'language' => 'def:channel/dc:language',
            'category' => 'def:channel/dc:coverage || ' .
                'def:channel/dc:subject/taxo:topic/@rdf:value || dc:subject',
        ];

        $items_selector = 'def:item';

        parent::__construct(
            new FeedTypes(FeedTypes::RDF_FEED),
            $default_lang,
            $xpath,
            $namespaces,
            $property_selectors,
            $items_selector,
            $parser_options
        );
    }
}