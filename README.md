Webhost Whois
=============

PHP class for determining what web hosting company's servers the current environment is running in.

[![Build Status](https://travis-ci.org/bradleyboy/webhost-whois.png)](https://travis-ci.org/bradleyboy/webhost-whois)

## What? Why?

For developers who distribute applications that run on shared hosting, it can be useful to know what web hosting company is in use.

*But that's what phpinfo() is for!*

Yes, phpinfo() is a great way to test for general compatibility. However, other factors outside of PHP – like Apache setup and restrictions, or other resource caps – are not detectable. Being able to detect the actual hosting company and compare it against known resource limitations or features for said company can be beneficial.

## Goals

1. Be PHP 5.2 compatible, at minimum. Those targeting shared hosts will know all to well why this is important.
2. Keep code within a single file. This will help keep it portable for inclusion in light packages like server tests, or thin installers.
3. Cover as many hosts as possible, no matter how small.
4. Only revert to DNS based detection when no other identifiable information is available in PHP itself. See `$dns` array in WebhostWhois.php for an example.

## How can I help?

If you know a web hosting company's environment well and can contribute a test, fork the project and add the test to the `results` array inside the constructor. Then create a pull request so we can merge it in.

If you don't know how to reliably create a test, but have access to the phpinfo() for a host, add a link to it in an issue. Or just dump a screenshot or export of the phpinfo() output to the issue. Others should be able to create a test based on that.

## Alright. So how do I use it?

Include the class, then create an instance of it.

```php
require 'WebhostWhois.php';
$host = new WebhostWhois;
```

To get the key of the detected hosting company, access the `key` property:

```php
require 'WebhostWhois.php';
$host = new WebhostWhois;
$host->key; // Ex: 'media-temple-grid'
```

You can also call a boolean method based on the hosting company's key:

```php
require 'WebhostWhois.php';
$host = new WebhostWhois;
$host->isMediaTempleGrid(); // true
```

By default, WebhostWhois performs a DNS check if the standard PHP data checks fail. You can disable this by passing options to the contructor:

```php
require 'WebhostWhois.php';
$host = new WebhostWhois;
$host->isMediaTempleDv(); // true

$host = new WebhostWhois(array('useDns' => false));
$host->isMediaTempleDv(); // false
```

## Supported hosts

So far, the following hosts are supported:

Company | Key | Uses DNS? | URL
----|----|----|----
Bluehost | bluehost | No | http://bluehost.com
Dreamhost | dreamhost | No | http://dreamhost.com
GoDaddy | go-daddy | No | http://godaddy.com
InMotion | in-motion | No | http://inmotionhosting.com
Media Temple DV | media-temple-dv | Yes | http://mediatemple.net/dv
Media Temple Grid | media-temple-grid | No | http://mediatemple.net/grid
OVH | ovh | No | http://ovh.co.uk
Rackspace Cloud | rackspace-cloud | No | http://rackspace.com/cloud
Site5 | site5 | No | http://site5.com
Strato | strato | No | http://strato.de/hosting/

