<?php
declare(strict_types = 1);

namespace Forensic\FeedParser\Test;

use Forensic\FeedParser\Parser;
use PHPUnit\Framework\TestCase;
use Forensic\FeedParser\Exceptions\InvalidURLException;
use Forensic\FeedParser\Exceptions\ResourceNotFoundException;
use Forensic\FeedParser\Exceptions\FileNotFoundException;

class ParserTest extends TestCase
{
    private $_parser = null;

    public function setup()
    {
        $this->_parser = new Parser();
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
}
