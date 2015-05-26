# graviton/deploy-scripts
[![Build Status](https://travis-ci.org/libgraviton/deploy-scripts.png?branch=develop)](https://travis-ci.org/libgraviton/deploy-scripts) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/libgraviton/deploy-scripts/badges/quality-score.png?b=develop)](https://scrutinizer-ci.com/g/libgraviton/deploy-scripts/?branch=develop) [![Code Coverage](https://scrutinizer-ci.com/g/libgraviton/deploy-scripts/badges/coverage.png?b=develop)](https://scrutinizer-ci.com/g/libgraviton/deploy-scripts/?branch=develop) [![Latest Stable Version](https://poser.pugx.org/graviton/deploy-scripts/v/stable.svg)](https://packagist.org/packages/graviton/deploy-scripts) [![Total Downloads](https://poser.pugx.org/graviton/deploy-scripts/downloads.svg)](https://packagist.org/packages/graviton/deploy-scripts) [![License](https://poser.pugx.org/graviton/deploy-scripts/license.svg)](https://packagist.org/packages/graviton/deploy-scripts)

These are currently mia.

I intend this to contain the low level parts of all the ``deploy.sh`` and ``cf-multipush.sh`` things
we have been writing. It should only depend on ``symfony/console`` and ``symfony/process`` and have
heaps of test coverage.

I intend for this to support both cf and docker cases. When it's done we'll wrap it up in a simple
``GravitonDeployBundle`` and the grey beards of early will look upon our continuous deploy rig with
awe and wonder whilst glancing at the door nervously.

## Installation
Install it using [composer](https://getcomposer.org/).

```bash
composer require graviton/deploy-scripts
```

## Configuration
The configuration is to be done in app/config/paramerters.yml and app/config/config.yml.
If you installed the incenteev/composer-parameter-handler you will be asked to setup the 
application by answering simple questions.

## Usage
### Console command
To get an overview about every available command run:

```bash
$>./bin/deploy
```

### In code
see tests ;) 

## Development
We welcome contributions as a pull request on the develop branch.

## Available Commands
### Cloud Foundry specific
- **AuthCommand**
  Authorises a user to a CF instance.
- **CheckApplicationCommand**
  Determines, if a special CF application is alive.
- **LoginCommand**
  Authorises a user to a CF instance.
- **LogoutCommand**
  Closes a Cloud Foundry user session 
