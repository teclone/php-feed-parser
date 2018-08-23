<?php
declare(strict_types = 1);
namespace Forensic\FeedParser\Enums;

use ReflectionClass;
use Forensic\FeedParser\Exceptions\UnexpectedValueException;

class BaseEnum
{
    const __default = null;

    protected $_value = null;

    /**
     *@param mixed [$value] - the instance value
     *@param boolean [$strict=true] - boolean indicating if value comparison should be strict
    */
    public function __construct($value = null, bool $strict = true)
    {
        $resolved_value = null;
        $const_list = $this->getConstList(true);
        if (is_null($value) && array_key_exists('__default', $const_list))
        {
            $resolved_value = $const_list['__default'];
        }
        else if (!is_null($value))
        {
            foreach($const_list as $const_key => $const_value)
            {
                if (($strict && $const_value === $value) || (!$strict && $const_value == $value))
                {
                    $resolved_value = $const_value;
                    break;
                }
            }
        }

        if (is_null($resolved_value))
            throw new UnexpectedValueException('unknown enum value');
        else
            $this->_value = $resolved_value;
    }

    /**
     * returns array of enum constants
     *
     *@param boolean [$include_default=false] - boolean value indicating if it should include
     * the class default enum constant
     *@return array
    */
    public function getConstList(bool $include_default = false)
    {
        $reflector = new ReflectionClass(get_class($this));
        $const_list = $reflector->getConstants();

        if (!$include_default && array_key_exists('__default', $const_list))
            unset($const_list['__default']);

        return $const_list;
    }

    /**
     *@return mixed
    */
    public function value()
    {
        return $this->_value;
    }

    /**
     *@return string
    */
    public function __toString()
    {
        return strval($this->_value);
    }
}