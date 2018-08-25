<?php
declare(strict_types = 1);

namespace Forensic\FeedParser\Test;

use Forensic\FeedParser\Parser;
use PHPUnit\Framework\TestCase;
use Forensic\FeedParser\Exceptions\InvalidURLException;
use Forensic\FeedParser\Exceptions\ResourceNotFoundException;
use Forensic\FeedParser\Exceptions\FileNotFoundException;
use Forensic\FeedParser\Exceptions\MalformedFeedException;
use Forensic\FeedParser\Exceptions\FeedTypeNotSupportedException;
use Forensic\FeedParser\Feeds\ATOMFeed;
use Forensic\FeedParser\Feeds\RSSFeed;
use Forensic\FeedParser\Feeds\RDFFeed;
use Forensic\FeedParser\ParameterBag;

class ParserTest extends TestCase
{
    private $_parser = null;

    public function setup()
    {
        $this->_parser = new Parser();
    }

    public function testDefaultLanguage()
    {
        $this->assertSame('en', $this->_parser->getDefaultLanguage());
    }

    public function testDefaultDateTemplate()
    {
        $this->assertEquals('jS F, Y, g:i A', $this->_parser->getDateTemplate());
    }

    public function testDefaultRemoveStyles()
    {
        $this->assertTrue($this->_parser->removeStyles());
    }

    public function testDefaultRemoveScripts()
    {
        $this->assertTrue($this->_parser->removeScripts());
    }

    public function testUpdateDefaultLanguage()
    {
        $this->_parser->setDefaultLanguage('fr');
        $this->assertSame('fr', $this->_parser->getDefaultLanguage());
    }

    public function testUpdateDateTemplate()
    {
        $this->_parser->setDateTemplate('jS f, Y, g:i A');
        $this->assertSame('jS f, Y, g:i A', $this->_parser->getDateTemplate());
    }

    public function testUpdateRemoveStyles()
    {
        $this->_parser->removeStyles(false);
        $this->assertFalse($this->_parser->removeStyles());
    }

    public function testUpdateRemoveScripts()
    {
        $this->_parser->removeScripts(false);
        $this->assertFalse($this->_parser->removeScripts());
    }

    public function testConstructSettings()
    {
        $parser = new Parser('fr', '', false, false);
        $this->assertSame('fr', $parser->getDefaultLanguage());
        $this->assertFalse($parser->removeStyles());
        $this->assertFalse($parser->removeScripts());
    }

    public function testInvalidURL()
    {
        $this->expectException(InvalidURLException::class);
        $this->_parser->parseFromURL('www.google.com');
    }

    public function testUnExistingURL()
    {
        $this->expectException(ResourceNotFoundException::class);
        $this->_parser->parseFromURL('http://feed.fjsfoundations.com');
    }

    public function testUnExistingFile()
    {
        $this->expectException(FileNotFoundException::class);
        $this->_parser->parseFromFile('./somefile.xml');
    }

    public function testMalformedFeed()
    {
        $this->expectException(MalformedFeedException::class);
        $this->_parser->parseFromFile(__DIR__ . '/../package.json');
    }

    public function testNonSupportedFeedType()
    {
        $this->expectException(FeedTypeNotSupportedException::class);
        $this->_parser->parseFromFile(__DIR__ . '/../phpunit.xml');
    }

    public function testATOMFeedParseResult()
    {
        $feed = $this->_parser->parseFromFile(__DIR__ . '/Helpers/Feeds/atom.xml');
        $this->assertInstanceOf(ATOMFeed::class, $feed);
    }

    public function testRSSFeedParseResult()
    {
        $feed = $this->_parser->parseFromFile(__DIR__ . '/Helpers/Feeds/rss.xml');
        $this->assertInstanceOf(RSSFeed::class, $feed);
    }

    public function testRDFFeedParseResult()
    {
        $feed = $this->_parser->parseFromFile(__DIR__ . '/Helpers/Feeds/rdf.xml');
        $this->assertInstanceOf(RDFFeed::class, $feed);
    }

    public function testFromExistingURL()
    {
        $feed = $this->_parser->parseFromURL('https://www.yahoo.com/news/rss/mostviewed');
        $this->assertInstanceOf(RSSFeed::class, $feed);
    }

    public function testFromString()
    {
        $parser = new Parser('en', '', false, false);
        $feed = $parser->parseFromString(
            file_get_contents(__DIR__ . '/Helpers/Feeds/atom.xml')
        );
        $this->assertInstanceOf(ATOMFeed::class, $feed);
    }

    /**
     * test if it returns string when accessing existing property
    */
    public function testFeedExistingPropertyAccessibility()
    {
        $parser = new Parser('en', '', false, false);
        $feed = $parser->parseFromString(
            file_get_contents(__DIR__ . '/Helpers/Feeds/atom.xml')
        );
        $this->assertTrue(is_string($feed->id));
    }

    /**
     * test if it returns null when accessing non existing property
    */
    public function testFeedNonExistingPropertyAccessibility()
    {
        $parser = new Parser('en', '', false, false);
        $feed = $parser->parseFromString(
            file_get_contents(__DIR__ . '/Helpers/Feeds/atom.xml')
        );
        $this->assertNull($feed->random);
    }

    /**
     * test if it returns string when accessing existing property
    */
    public function testFeedItemExistingPropertyAccessibility()
    {
        $parser = new Parser('en', '', false, false);
        $feed = $parser->parseFromString(
            file_get_contents(__DIR__ . '/Helpers/Feeds/atom.xml')
        );
        $this->assertTrue(is_string($feed->items[0]->id));
    }

    /**
     * test if it returns null when accessing non existing property
    */
    public function testFeedItemNonExistingPropertyAccessibility()
    {
        $parser = new Parser('en', '', false, false);
        $feed = $parser->parseFromString(
            file_get_contents(__DIR__ . '/Helpers/Feeds/atom.xml')
        );
        $this->assertNull($feed->items[0]->random);
    }

    /**
     * test if feed returns paramater bag for array properties such as image
    */
    public function testIfFeedReturnsParameterBag()
    {
        $feed = $this->_parser->parseFromFile(__DIR__ . '/Helpers/Feeds/rss.xml');
        $this->assertInstanceOf(ParameterBag::class, $feed->image);
    }

    /**
     * test if feed item returns paramater bag for array properties such as image
     * and enclosure
    */
    public function testIfFeedItemReturnsParameterBag()
    {
        $feed = $this->_parser->parseFromFile(__DIR__ . '/Helpers/Feeds/rss.xml');
        $this->assertInstanceOf(ParameterBag::class, $feed->items[0]->enclosure);
    }

    public function testFeedToArrayMethod()
    {
        $feed = $this->_parser->parseFromFile(__DIR__ . '/Helpers/Feeds/rss.xml');
        $this->assertTrue(is_array($feed->toArray()));
    }

    public function testFeedItemToArrayMethod()
    {
        $feed = $this->_parser->parseFromFile(__DIR__ . '/Helpers/Feeds/rss.xml');
        $this->assertTrue(is_array($feed->items[0]->toArray()));
    }

    public function testFeedToJSONMethod()
    {
        $feed = $this->_parser->parseFromFile(__DIR__ . '/Helpers/Feeds/rss.xml');
        $this->assertTrue(is_string($feed->toJSON()));
    }

    public function testFeedItemToJSONMethod()
    {
        $feed = $this->_parser->parseFromFile(__DIR__ . '/Helpers/Feeds/rss.xml');
        $this->assertTrue(is_string($feed->items[0]->toJSON()));
    }
}
