<?php
declare(strict_types = 1);

namespace Forensic\FeedParser;

class ParameterBag
{
    private $_parameters = null;

    public function __construct(array $parameters = [])
    {
        $this->_parameters = $parameters;
    }

    /**
     * return the parameter value if found else, return null
    */
    public function __get(string $name)
    {
        if (array_key_exists($name, $this->_parameters))
            return $this->_parameters[$name];
        else
            return null;
    }
}