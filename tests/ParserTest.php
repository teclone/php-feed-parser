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
        $parser = new Parser('fr', false, false);
        $this->assertSame('fr', $parser->getDefaultLanguage());
        $this->assertFalse($parser->removeStyles());
        $this->assertFalse($parser->removeScripts());
    }

    public function testInvalidUrl()
    {
        $this->expectException(InvalidURLException::class);
        $this->_parser->parseFromUrl('www.google.com');
    }

    public function testUnExistingUrl()
    {
        $this->expectException(ResourceNotFoundException::class);
        $this->_parser->parseFromUrl('http://feed.fjsfoundations.com');
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
}
