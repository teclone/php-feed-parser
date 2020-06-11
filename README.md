# PHP Feed Parser

[![Build Status](https://travis-ci.org/teclone/php-feed-parser.svg?branch=master)](https://travis-ci.org/teclone/php-feed-parser)
[![Coverage Status](https://coveralls.io/repos/github/teclone/php-feed-parser/badge.svg?branch=master)](https://coveralls.io/github/teclone/php-feed-parser?branch=master)
[![semantic-release](https://img.shields.io/badge/%20%20%F0%9F%93%A6%F0%9F%9A%80-semantic--release-e10079.svg)](https://github.com/semantic-release/semantic-release)
![Packagist](https://img.shields.io/packagist/dt/forensic/feed-parser.svg)

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
    echo $item->textContent; // the same as content, but all html tags are stripped out
    echo $item->createdAt;
    echo $item->lastUpdated;
    echo $item->category;
    echo $item->image->src;
    echo $item->image->title;
    .....
}
```

## Export Feed as JSON

You can also export the parsed feed as json, as shown below. This can also help you view all properties that are accessible in the parsed feed.

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
    bool $remove_styles = true,
    bool $remove_scripts = true
);
```

- **default_lang**:

  This option sets the default feed language property to use should there be no language entry found in the xml document.

  ```php
  $parser = new Parser();
  $parser->setDefaultLanguage('fr');
  ```

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
git clone https://github.com/teclone/php-feed-parser && php-feed-parser
```

**Install dependencies**:

```bash
composer install && npm install
```

**Run test**:

```bash
vendor/bin/phpunit
```
