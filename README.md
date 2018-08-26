# PHP Feed Parser

[![Build Status](https://travis-ci.org/harrison-ifeanyichukwu/php-feed-parser.svg?branch=master)](https://travis-ci.org/harrison-ifeanyichukwu/php-feed-parser)

PHP Feed Parser is a fully integrated web syndication feed parser. It can successfully parse all the three major syndication feeds which include [RSS](http://cyber.harvard.edu/rss/rss.html), [ATOM](https://tools.ietf.org/html/rfc4287) & [RDF](http://web.resource.org/rss/1.0/spec) feeds.

It produces clean, unified parse accross all the three supported feed types. It is configurable, lightweight, fully tested and allows one to parse feeds from three different sources that includes **parsing from url, from file or from string**.

## Getting Started

**Install via composer**:

```bash
composer require forensic/feed-parser
```

Create an instance as shown below:

```php
//include composer autoload.php
require 'vendor/autoload.php';

//use the project's Parser module
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

## Export Feed as JSON

You can also export the parsed feed as json, as shown below. This can also help you view all properties that accessible in the parsed feed.

```php
//create an instance
$parser = new Parser();

//parse yahoo rss news feed
$feed = $parser->parseFromURL('https://www.yahoo.com/news/rss/mostviewed');

header('Content-Type: application/json');

//export whole feed
echo $feed->toJSON();

//export a single item
echo $feed->items[0]->toJSON();
```

## Parser Options

The following configuration options can be passed in when creating an instance or set through the designated public methods:

```php
//constructor signature
new Parser(
    string $default_lang = 'en',
    string $date_template = '',
    bool $remove_styles = true,
    bool $remove_scripts = true
);
```

- **default_lang**:

    This option sets the default feed language property to use should there be no language entry found in the xml document.

    ```php
    $parser = new Parser();
    $parser->setDefaultLanguage('fr');
    ````

- **date_template**:

    This option sets the date formatter template used when parsing feed date properties such as `lastUpdated`. the default format used is `'jS F, Y, g:i A'`. The formatter template should be a valid php date formatter argument. see [date](http://php.net/manual/en/function.date.php) for details.

    ```php
    $parser = new Parser();
    $parser->setDateTemplate('jS F, y, g:i a');
    ````

- **remove_styles**:

    This option states if stylings found in any feed item's content, should be stripped off. The stylings include html `style` element and attribute. Defaults to true.

    ```php
    $parser = new Parser();
    $parser->removeStyles(true);
    ```

- **remove_scripts**:

    This option states if any scripting found in any feed item's content, should be stripped off. Scripting includes html `script` element and event handler `on-prefixed` element attributes such as `onclick`. Defaults to true.

    ```php
    $parser = new Parser();
    $parser->removeScripts(true);
    ```

## Testing FeedParser

To locally test or contribute to this project, You should have at least php 7.1, xDebug, composer and npm installed, then ollow the steps below:

**Clone this repo**:

```bash
git clone https://github.com/harrison-ifeanyichukwu/php-feed-parser && php-feed-parser
```

**Install dependencies**:

```bash
composer install && npm install
```

**Run test**:

```bash
vendor/bin/phpunit
```

## About Maintainers

This project is maintained by [harrison ifeanyichukwu](mailto:harrisonifeanyichukwu@gmail.com),
a young, full stack web developer, PHP, JavaScript and Database Engineer focusing on LAMP & MERN tech stacks with 5+ years experience working with PHP, Symfony, Laravel, JavaScript, CSS & HTML5 technologies. 3+ years experience with database administration, Node.js tech stack, Unit Testing, web asset management (webpack, rollup.js, sass, less, babel) and continuous integration.

Harrison is the maintainer of w3c [xml-serializer](https://www.npmjs.com/package/@harrison-ifeanyichukwu/xml-serializer) project, node.js [R-Server](https://github.com/harrison-ifeanyichukwu/r-server), [Rollup-all](https://www.npmjs.com/package/rollup-all) one-time [Rollup.js](https://rollupjs.org/guide/en) build tool, [Forensic JS](https://www.npmjs.com/package/forensic-js) JavaScript library and other amazing projects.

He is available for hire, in search for new amazing challenge, and looks forward to hearing from you soon!!!