<?php
declare(strict_types = 1);
namespace Forensic\FeedParser\Enums;

class FeedTypes extends BaseEnum
{
    const __default = 1;

    const RSS_FEED = 1;
    const ATOM_FEED = 2;
    const RDF_FEED = 3;

    public function __construct($value = null, bool $strict = true)
    {
        parent::__construct($value, $strict);
    }
}