<?php
declare(strict_types = 1);
namespace Forensic\FeedParser\Enums;

class FeedItemTypes extends BaseEnum
{
    const __default = 1;

    const RSS_FEED_ITEM = 1;
    const ATOM_FEED_ITEM = 2;
    const RDF_FEED_ITEM = 3;

    public function __construct($value = null, bool $strict = true)
    {
        parent::__construct($value, $strict);
    }
}