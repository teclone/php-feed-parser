<?php
declare(strict_types = 1);

namespace Forensic\FeedParser\Test;

use Forensic\FeedParser\XPath;
use PHPUnit\Framework\TestCase;
use DOMDocument;

class XPathTest extends TestCase
{
    private $_xpath = null;
    private $_namespaces = null;

    public function setup()
    {
        $xml = <<<'XML'
        <?xml version='1.0' standalone='yes'?>
        <documents>
            <!--default document-->
            <html>
                <body>
                    <div>this is default document</div>
                </body>
            </html>
            <!--xhtml document-->
            <html xmlns="http://w3.org/ns/xhml">
                <body>
                    <div>this is xhtml document</div>
                </body>
            </html>

            <!--svg document-->
            <html xmlns="http://w3.org/ns/svg">
                <body>
                    <div>this is svg document</div>
                </body>
            </html>

        </documents>
XML;
        $document = new DOMDocument('1.0', 'UTF-8');
        $document->loadXML(trim($xml));

        $this->_xpath = new XPath($document);
        $this->_namespaces = [
            'xhtml' => 'http://w3.org/ns/xhml',
            'svg' => 'http://w3.org/ns/svg'
        ];
    }

    /**
     * provides xpath test expressions. the first expression matches a node,
     * the second matches none.
     *
     *@return array
    */
    public function expressionProvider()
    {
        return [
            'xhtml expression pair' => [
                'xhtml:html/xhtml:body/xhtml:div',
                'xhtml:html/xhtml:body/xhtml:p'
            ],
            'svg expression pair' => [
                'svg:html/svg:body/svg:div',
                'svg:html/svg:body/svg:p'
            ],
        ];
    }

    public function testGet()
    {
        $this->assertInstanceOf('DOMXPath', $this->_xpath->get());
    }

    public function testInitialContextNode()
    {
        $this->assertNull($this->_xpath->getContextNode());
    }

    public function testSetContextNode()
    {
        $context_node = $this->_xpath->get()->document->documentElement;
        $this->_xpath->setContextNode($context_node);

        $this->assertSame($context_node, $this->_xpath->getContextNode());
    }

    /**
     *@dataProvider expressionProvider
    */
    public function testRegisterNamespace(string $existing, string $non_existing)
    {
        $dom_xpath = $this->_xpath->get();
        $this->assertFalse($dom_xpath->evaluate($existing));

        foreach($this->_namespaces as $prefix => $namespace_uri)
            $this->assertTrue($this->_xpath->registerNamespace($prefix, $namespace_uri));

        $this->assertInstanceOf('DOMNodeList', $dom_xpath->evaluate($existing));
    }

    /**
     *@dataProvider expressionProvider
    */
    public function testRegisterNamespaces(string $existing, string $non_existing)
    {
        $dom_xpath = $this->_xpath->get();

        $this->assertFalse($dom_xpath->evaluate($existing));

        $this->_xpath->registerNamespaces($this->_namespaces);
        $this->assertInstanceOf('DOMNodeList', $dom_xpath->evaluate($existing));
    }

    /**
     *@dataProvider expressionProvider
    */
    public function testSelectExistingNode(string $existing, string $non_existing)
    {
        $this->_xpath->registerNamespaces($this->_namespaces);
        $this->assertInstanceOf('DOMElement', $this->_xpath->selectNode($existing));
    }

    /**
     *@dataProvider expressionProvider
    */
    public function testSelectNonExistingNode(string $existing, string $non_existing)
    {
        $this->_xpath->registerNamespaces($this->_namespaces);
        $this->assertNull($this->_xpath->selectNode($non_existing));
    }

    /**
     *@dataProvider expressionProvider
    */
    public function testSelectExistingNodes(string $existing, string $non_existing)
    {
        $this->_xpath->registerNamespaces($this->_namespaces);
        $result = $this->_xpath->selectNodes($existing);

        $this->assertInstanceOf('DOMNodeList', $result);
        $this->assertCount(1, $result);
    }

    /**
     *@dataProvider expressionProvider
    */
    public function testSelectNonExistingNodes(string $existing, string $non_existing)
    {
        $this->_xpath->registerNamespaces($this->_namespaces);

        $this->assertNull($this->_xpath->selectNodes($non_existing));
    }

    /**
     *@dataProvider expressionProvider
    */
    public function testSelectAltNode(string $existing, string $non_existing)
    {
        $this->_xpath->registerNamespaces($this->_namespaces);

        $expressions = [$existing, $non_existing];
        $this->assertInstanceOf(
            'DOMElement',
            $this->_xpath->selectAltNode(implode(' || ', $expressions))
        );
        $this->assertInstanceOf(
            'DOMElement',
            $this->_xpath->selectAltNode(implode(' || ', array_reverse($expressions)))
        );
    }

    /**
     *@dataProvider expressionProvider
    */
    public function testSelectAltNodes(string $existing, string $non_existing)
    {
        $this->_xpath->registerNamespaces($this->_namespaces);

        $expressions = [$existing, $non_existing];

        $result = $this->_xpath->selectAltNodes(implode(' || ', $expressions));

        $this->assertInstanceOf('DOMNodeList', $result);
        $this->assertCount(1, $result);

        $result = $this->_xpath->selectAltNodes(implode(' || ', array_reverse($expressions)));

        $this->assertInstanceOf('DOMNodeList', $result);
        $this->assertCount(1, $result);

        $result = $this->_xpath->selectAltNodes(implode(' || ', [$non_existing, $non_existing]));
        $this->assertNull($result);
    }
}
