[![Build Status](https://travis-ci.org/BlueM/silex-solr-provider.svg)](https://travis-ci.org/BlueM/silex-solr-provider)

Overview
========

What is it?
--------------
This repository contais a [Silex](https://github.com/silexphp/Silex) service provider which provides [Apache Solr](http://lucene.apache.org/solr/) 4.* connectivity for Silex 1.2+, via the [PHP Solr extension](http://php.net/solr) v2.

Installation
-------------
The preferred way to install Tree is through [Composer](https://getcomposer.org). For this, add `"bluem/silex-solr-provider": "~1.0"` to the requirements in your composer.json file. As this library uses [semantic versioning](http://semver.org), you will get fixes and feature additions when running composer update, but not changes which break the API.

Alternatively, you can clone the repository using git or download a tagged release.


Usage
======

Registering the provider
------------------------
To make Silex aware of the provider, add this to your `app.php`:

```php
$app->register(new BlueM\Silex\Provider\SolrServiceProvider());
```


Configuration
-------------
You can define Solr settings such as the hostname in the usual Silex way in `config/prod.php` and `config/dev.php`.

Example:

```php
$app['solr.hostname'] = '127.0.0.1';
$app['solr.path']     = 'solr/core0';
```

You can use all options that the `SolrClient` class constructor accepts. For a list, see http://php.net/manual/en/solrclient.construct.php. Please note that `SolrClient` does not accept an empty options array, wich means you have to provide at least 1 option, otherwise you will get an exception.

Another (IMHO less recommendable way) is to pass the options as 2nd argument to `Silex\Application` when registering the provider:

```php
$app->register(
    new BlueM\Silex\Provider\SolrServiceProvider(),
    ['solr.hostname' => '127.0.0.1']
);
```

In either case, you can use a different string (instead of “solr”) as configuration key prefix by passing this string as argument to the provider’s constructor:

```php
// In app.php:
$app->register(new BlueM\Silex\Provider\SolrServiceProvider('mysolr'));

// In config/prod.php:
$app['mysolr.hostname'] = '127.0.0.1';

// In your project code:
$app['mysolr']->getOptions();
```


Version History
=================

* 1.0: Works


Author & License
=================
This code was written by Carsten Blüm (www.bluem.net) and licensed under the BSD 2-Clause license.
