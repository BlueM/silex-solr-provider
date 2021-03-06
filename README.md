[![Build Status](https://travis-ci.org/BlueM/silex-solr-provider.svg)](https://travis-ci.org/BlueM/silex-solr-provider)

**As Silex is discontinued, so is this project. It will not receive udpates or fixes.**

Overview
========

What is it?
--------------
This repository contains a [Silex](https://github.com/silexphp/Silex) service provider which provides [Apache Solr](http://lucene.apache.org/solr/) version 4 or 5 connectivity for Silex 1.2+, via the [PHP Solr extension](http://php.net/solr) v2.

Installation
-------------
The preferred way to install this library is through [Composer](https://getcomposer.org). For this, add `"bluem/silex-solr-provider": "~1.0"` (for Silex 1.2+) or `"bluem/silex-solr-provider": "~2.0@dev"` (for Silex2@dev) respectively to the requirements in your `composer.json` file. As this library uses [semantic versioning](http://semver.org), you will get fixes and feature additions when running composer update, but not changes which break the API.

Alternatively, you can clone the repository using git or download a tagged release. As with the Composer-based installation, you have to pay attention to the Silex version: when using Silex 1.2+, you will need a 1.* release of the service provider, and for Silex 2, you will need dev-master.


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

You can use all options which the `SolrClient` class constructor accepts. For a list, see http://php.net/manual/en/solrclient.construct.php. Please note that `SolrClient` does not accept an empty options array, wich means you have to provide at least 1 option, otherwise you will get an exception.

Another (IMHO less recommendable way) is to pass the options as 2nd argument to `Silex\Application` when registering the provider:

```php
$app->register(
    new BlueM\Silex\Provider\SolrServiceProvider(),
    ['solr.hostname' => '127.0.0.1']
);
```

In either case, you can use a different string (instead of “solr”) as configuration key prefix by passing this string as argument to the provider’s constructor. This comes also in handy when multiple cores are needed:

```php
// In app.php:
$app->register(new BlueM\Silex\Provider\SolrServiceProvider('solr-core0'));
$app->register(new BlueM\Silex\Provider\SolrServiceProvider('solr-core1'));

// In config/prod.php:
$app['solr-core0.hostname'] = '127.0.0.1';
$app['solr-core0.path']     = 'solr/core0';
$app['solr-core1.hostname'] = '127.0.0.1';
$app['solr-core1.path']     = 'solr/core1';

// In your project code:
$app['solr-core0']->doSomethingWithCore0();
$app['solr-core1']->doSomethingWithCore1();
```


Version History
=================

* 1.0.1: Improve Readme, fix a doc comment
* 1.0: Works


Author & License
=================
This code was written by Carsten Blüm (www.bluem.net) and licensed under the BSD 2-Clause license.
