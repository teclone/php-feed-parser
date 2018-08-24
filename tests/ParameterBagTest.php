<?php
declare(strict_types = 1);

namespace Forensic\FeedParser\Test;

use Forensic\FeedParser\ParameterBag;
use PHPUnit\Framework\TestCase;

class ParameterBagTest extends TestCase
{
    private $_bag = null;

    public function setup()
    {
        $this->_bag = new ParameterBag([
            'title' => 'Parameter Bag Test',
            "version" => 2.0
        ]);
    }

    public function testExistingPropertyAccessibility()
    {
        $this->assertEquals('Parameter Bag Test', $this->_bag->title);
    }

    public function testNonExistingPropertyAccessibility()
    {
        $this->assertNull($this->_bag->age);
    }
}
