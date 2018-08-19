<?php
declare(strict_types = 1);

namespace Forensic\FeedParser\Enums;

class FeedTypes extends \SplEnum
{
    const __default = self::RSS_FEED;

    const RSS_FEED = 1;
    const ATOM_FEED = 2;
    const RDF_FEED = 3;
}