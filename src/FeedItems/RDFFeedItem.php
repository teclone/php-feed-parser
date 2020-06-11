<?php

declare(strict_types=1);

namespace Forensic\FeedParser\FeedItems;

use Forensic\FeedParser\Enums\FeedItemTypes;
use Forensic\FeedParser\XPath;
use DOMElement;

class RDFFeedItem extends BaseFeedItem
{
    public function __construct(DOMElement $item, XPath $xpath, array $parser_options)
    {
        $property_selectors = [
            'id' => '@rdf:about',
            'title' => 'def:title || dc:title',
            'link' => 'def:link',
            'content' => 'content:encoded || dc:description || def:description',
            'textContent' => 'content:encoded || dc:description || def:description',
            'enclosure' => [
                'type' => 'enc:enclosure/@enc:type',
                'url' => 'enc:enclosure/@rdf:resource',
                'length' => 'enc:enclosure/enc:length'
            ],
            'source' => 'dc:source',
            //'image' => { 'src' => '', 'link' => '', 'title' => '' }, // to be parsed specially
            'createdAt' => 'dc:date', // a date construct
            'lastUpdated' => 'dc:date', // a date construct

            'author' => 'dc:creator || dc:contributor',
            'category' => 'dc:coverage || dc:subject/taxo:topic/@rdf:value || dc:subject || ' .
                'parent::rdf:RDF/def:channel/dc:coverage || ' .
                'parent::rdf:RDF/def:channel/dc:subject/taxo:topic/@rdf:value || ' .
                'parent::rdf:RDF/def:channel/dc:subject' // defaults to the parent category
        ];

        parent::__construct(
            new FeedItemTypes(FeedItemTypes::RDF_FEED_ITEM),
            $item,
            $xpath,
            $property_selectors,
            $parser_options
        );
    }
}
