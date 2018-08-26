# PHP Feed Parser

PHP Feed Parser is a fully integrated syndication feed parser, with support for all major feed types including [RSS](http://cyber.harvard.edu/rss/rss.html), [ATOM](https://tools.ietf.org/html/rfc4287) & [RDF](http://web.resource.org/rss/1.0/spec) feeds.

It produces clean, unified parse accross all the three supported feed types. It is configurable, lightweight and fully tested.

It allows you to parse feeds from three different sources:

1. From File using the `parseFromFile($file_abs_path)` method
2. From URL using the `parseFromURL($url)` method
3. From String using the `parseFromString($xml_string)` method

## Install via Composer

The project is available as a composer package.

```bash
composer require forensic/feed-parser
```

setup your project to require composer's `autoload.php` file.

## Getting Started

```php
//include composer autoload.php
require 'vendor/autoload.php';

//use the projects Parser module
use Forensic\FeedParser\Parser;

//create an instance
$parser = new Parser();

//parse yahoo rss news feed
$feed = $parser->parseFromURL('https://www.yahoo.com/news/rss/mostviewed');

//access feed properties
echo $feed->type; //1 for RSS, 2 for ATOM, 3 for RDF
echo $feed->title;
echo $feed->link;
echo $feed->lastUpdated;
echo $feed->generator;
echo $feed->description;
echo $feed->image->src;
....

//access items
$items = $feed->items;

foreach ($items as $item)
{
    //access feed item properties
    echo $item->link;
    echo $item->content;
    echo $item->lastUpdated;
    echo $item->category;
    echo $item->image->src;
    echo $item->image->title;
    .....
}
```

## Setting Parser Options

You can pass in some configuration options when creating a Parser instance or even set them later.

```php
new Parser(
    string $default_lang = 'en',
    string $date_template = '',
    bool $remove_styles = true,
    bool $remove_scripts = true
);
```

## Modifying options later

You can change options through the appropriate exposed method too.

```php
$parser = new Parser();

//set language
$parser->setLanguage($lang);

//set date Template
$parser->setDateTemplate($date_template);

//remove styles
$parser->removeStyles(true);

//remove scripts
$parser->removeScripts(true);
```

## Parser Options Explained

- **default_lang**: This option sets the default feed language property to use should there be no language entry found in the xml document.

- **date_template**: This option sets the date formatter template used when parsing feed date properties such as `lastUpdated`. the default format used is `'jS F, Y, g:i A'`. The formatter template should be a valid php date formatter argument. see [date](http://php.net/manual/en/function.date.php) for details.

- **remove_styles**: This option states if stylings found in any feed item's content, should be stripped off. The stylings include html `style` element and attribute. Defaults to true.

- **remove_scripts**: This option states if any scripting found in any feed item's content, should be stripped off. Scripting includes html `script` element and event handler `on-prefixed` element attributes such as `onclick`. Defaults to true.

### Exporting feed to Array & JSON

You can export parsed feed to array or json. This can also help you view all properties that are accessible parsed feed.

```php
//include composer autoload.php
require 'vendor/autoload.php';

//use the projects Parser module
use Forensic\FeedParser\Parser;

//create an instance
$parser = new Parser();

//parse yahoo rss news feed
$feed = $parser->parseFromURL('https://www.yahoo.com/news/rss/mostviewed');

$feed_array = $feed->toArray();

//send to front end
header('Content-Type: application/json');
echo $feed->toJSON();
```