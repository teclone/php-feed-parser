<?php
declare(strict_types = 1);

namespace Forensic\FeedParser\Test\Enums;

use PHPUnit\Framework\TestCase;
use Forensic\FeedParser\Enums\BaseEnum;
use Forensic\FeedParser\Exceptions\UnexpectedValueException;

class EnumWithDefault extends BaseEnum
{
    const __default = 1;

    const FIRST = 1;
    const SECOND = 2;

    public function __construct($value = null, bool $strict = true)
    {
        parent::__construct($value, $strict);
    }
}

class EnumWithoutDefault extends BaseEnum
{
    const FIRST = 1;
    const SECOND = 2;

    public function __construct($value = null, bool $strict = true)
    {
        parent::__construct($value, $strict);
    }
}

class BaseEnumTest extends TestCase
{
    public function setup()
    {

    }

    /**
     * test that everything goes well creating an instance with valid constant value
    */
    public function testValidConstValue()
    {
        $enum = new EnumWithDefault(EnumWithDefault::SECOND);
        $this->assertSame(EnumWithDefault::SECOND, $enum->value());
    }

    /**
     * test that it throws UnexpectedValueException if passed in value is not a valid constant
     * value
    */
    public function testInvalidConstValue()
    {
        $this->expectException(UnexpectedValueException::class);
        $enum = new EnumWithDefault(3);
    }

    /**
     * test that instance can be created using the __default const value by not passing in any
     * parameter
    */
    public function testDefaultConstValue()
    {
        $enum = new EnumWithDefault();
        $this->assertSame(EnumWithDefault::__default, $enum->value());
    }

    /**
     * test that instance cannot be created by leaving the value empty for an enum with no
     * __default value
    */
    public function testNoDefaultConstValue()
    {
        $this->expectException(UnexpectedValueException::class);
        $enum = new EnumWithoutDefault();
    }

    /**
     * test the return type of getConstantList()
    */
    public function testGetConstantList()
    {
        $enum = new EnumWithDefault();

        $list_without_default = $enum->getConstList();
        $this->assertEquals([
            'FIRST' => 1,
            'SECOND' => 2
        ], $list_without_default);

        $list_with_default = $enum->getConstList(true);
        $this->assertEquals([
            '__default' => 1,
            'FIRST' => 1,
            'SECOND' => 2
        ], $list_with_default);
    }

    /**
     * test the class magic __toString() value
    */
    public function testToString()
    {
        $enum = new EnumWithDefault();

        $this->expectOutputString('1');
        echo $enum;
    }
}