# SpiffyConnect

SpiffyConnect is a connector implementing OAuth1 and OAuth2 to make communicating with service providers as easy as possible.

## Project Status
[![Master Branch Build Status](https://secure.travis-ci.org/spiffyjr/spiffy-connect.png?branch=master)](http://travis-ci.org/spiffyjr/spiffy-connect)
[![Dependency Status](https://www.versioneye.com/user/projects/51adf3da33f9dd0002007e85/badge.png)](https://www.versioneye.com/user/projects/51adf3da33f9dd0002007e85)

## Documentation

Please check the [`doc` dir](https://github.com/spiffyjr/spiffy-connect/tree/master/doc)
for more detailed documentation on the features provided by this module.

## Requirements

* PHP 5.3.3
* Composer (and dependencies listed in [`composer.json`](https://github.com/spiffyjr/spiffy-connect/tree/master/composer.json)

## Installation

Installation of SpiffyConnect uses composer. For composer documentation, please refer to
[getcomposer.org](http://getcomposer.org/).

```sh
php composer.phar require spiffy/spiffy-connect:dev-master
```

Then add `SpiffyConnect` to your `config/application.config.php`

Installation without composer is not officially supported, and requires you to install and autoload
the dependencies specified in the `composer.json`.
