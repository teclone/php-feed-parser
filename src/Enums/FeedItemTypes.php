<?php
declare(strict_types = 1);

namespace Forensic\FeedParser\Enums;

class FeedItemTypes extends \SplEnum
{
    const __default = self::RSS_FEED_ITEM;

    const RSS_FEED_ITEM = 1;
    const ATOM_FEED_ITEM = 2;
    const RDF_FEED_ITEM = 3;
}