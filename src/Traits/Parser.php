<?php
declare(strict_types = 1);

namespace Forensic\FeedParser\Traits;

use Forensic\FeedParser\XPath;


trait Parser
{

    private function filterPropertyValue(string $property_name, string $value)
    {
        if ($property_name === 'lastUpdated')
        {
            //process date
            $value = $value;
        }
        return $value;
    }

    /**
     * resolves a given property value, checking if it of type text, html or xhtml
     *
     *@param XPath $xpath - the xpath instance
     *@param string $property_name - the property_name
     *@param string $property_selectors - the alternate property selectors
     *@param bool $remove_styles - boolean indicating if style attributes and style elements
     * should be removed if any
     *@param bool $remove_scripts - boolean indicating if on* event handlers attributes and
     * script elements should be remvoed if any
     *@return string
    */
    private function resolveProperty(XPath $xpath, string $property_name,
        string $property_selectors, bool $remove_styles, bool $remove_scripts)
    {
        $node = $xpath->selectAltNode($property_selectors);
        if (is_null($node))
            return '';

        $type = 'text';
        if ($xpath->selectNode('@type', $node))
            $type = $xpath->selectNode('@type', $node)->nodeValue;

        if ($type === 'text' || $type === 'html')
            return $this->filterPropertyValue($property_name, $node->nodeValue);

        //dealing with text construct of type xhtml
        $div = null;
        for ($i = 0, $len = $node->childNodes->length; $i < $len; $i++)
        {
            $current = $node->childNodes->item($i);
            if ($current->nodeType === XML_ELEMENT_NODE)
            {
                $div = $current;
                break;
            }
        }

        $serialized_content = trim($xpath->get()->document->saveXML($div));

        //replace all forms of xml namespace prefixes
        $prefix = '([-\w]:)';
        $filtered_content = preg_replace([
                '/^<' . $prefix . '?div[^>]*>/', //remove the parent div start tag
                '/<\s*\/' . $prefix . '?div\s*>$/', //remove the parent div end tag
                '/<' . $prefix . '/im', //remove all namespace prefixes
                '/<\/' . $prefix . '/im', //remove all namespace prefixes
            ], [
                '',
                '',
                '<',
                '</'
            ],
            $serialized_content
        );

        if ($remove_styles) {
            //remove style attributes
            $filtered_content = preg_replace_callback(
                '/(<[^>]+)\s+style=("[^"]*"|\'[^\']*\')/im',
                function ($matches) {
                    return $matches[1];
                },
                $filtered_content
            );

            //remove style elements
            $filtered_content = preg_replace(
                '/<(style)[^>]*>[^<]*<\/\\1\s*>/im',
                '',
                $filtered_content
            );
        }

        if ($remove_scripts)
        {
            //remove all on* event attribute handlers
            $filtered_content = preg_replace_callback(
                '/(<[^>]+)\s+on[a-z]+=("[^"]*"|\'[^\']*\')/im',
                function ($matches) {
                    return $matches[1]; //@codeCoverageIgnore
                },
                $filtered_content
            );

            //remove script elements
            $filtered_content = preg_replace(
                '/<(script)[^>]*>[^<]*<\/\\1\s*>/im',
                '',
                $filtered_content
            );
        }

        return $this->filterPropertyValue($property_name, $filtered_content);
    }

    /**
     * parses array property
     *
     *@param XPath $xpath - the xpath instance
     *@param array &$store - the array to store values in
     *@param array $property_maps - array of property => selectors map
     *@param bool $remove_styles - boolean indicating if style attributes and style elements
     * should be removed if any
     *@param bool $remove_scripts - boolean indicating if on* event handlers attributes and
     * script elements should be remvoed if any
    */
    private function parseArrayProperty(XPath $xpath, array &$store,
        array $property_maps, bool $remove_styles, bool $remove_scripts)
    {
        foreach($property_maps as $property_name => $property_selectors)
        {
            $store[$property_name] = $this->resolveProperty(
                $xpath,
                $property_name,
                $property_selectors,
                $remove_styles,
                $remove_scripts
            );
        }
    }

    /**
     * entry pass point
     *
     *@param XPath $xpath - the xpath instance
     *@param array $property_maps - array of property => selectors map
     *@param bool $remove_styles - boolean indicating if style attributes and style elements
     * should be removed if any
     *@param bool $remove_scripts - boolean indicating if on* event handlers attributes and
     * script elements should be remvoed if any
    */
    protected function parse(XPath $xpath, array $property_maps,
        bool $remove_styles, bool $remove_scripts)
    {
        foreach($property_maps as $property_name => $property_selectors)
        {
            $this_property_name = '_' . $property_name;
            if (is_array($property_selectors))
                $this->parseArrayProperty(
                    $xpath,
                    $this->{$this_property_name},
                    $property_selectors,
                    $remove_styles,
                    $remove_scripts
                );
            else
                $this->{$this_property_name} = $this->resolveProperty(
                    $xpath,
                    $property_name,
                    $property_selectors,
                    $remove_styles,
                    $remove_scripts
                );
        }
    }
}