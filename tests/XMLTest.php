<?php
declare(strict_types = 1);

namespace Forensic\FeedParser\Test;

use Forensic\FeedParser\XML;
use PHPUnit\Framework\TestCase;

class XMLTest extends TestCase
{
    private $_xml = null;

    public function setup()
    {
        $this->_xml = new XML();
    }

    public function testInitialState()
    {
        $this->assertSame(false, $this->_xml->status());
        $this->assertNull($this->_xml->document());
    }

    public function testErronousDocumentParse()
    {
        $xml = <<<'XML'
            <?xml version='1.0' standalone='yes'?>
            <movies>
                <movie>
                    <titles>PHP: Behind the Parser</title>
                </movie>
            </movies>
XML;
        $this->_xml->parse($xml);
        $this->assertGreaterThan(0, count($this->_xml->errors()));
        $this->assertFalse($this->_xml->status());
        $this->assertNull($this->_xml->document());
    }

    public function testCorrectDocumentParse()
    {
        $xml = <<<'XML'
            <?xml version='1.0' standalone='yes'?>
            <movies>
                <movie>
                    <titles>PHP: Behind the Parser</titles>
                </movie>
            </movies>
XML;
        $this->_xml->parse($xml);

        $this->assertEquals(0, count($this->_xml->errors()));
        $this->assertTrue($this->_xml->status());
        $this->assertInstanceOf('DOMDocument', $this->_xml->document());
    }
}
