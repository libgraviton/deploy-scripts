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
### Environment variables

- **SYMFONY__DEPLOYMENT__CF_COMMAND**
  Shell command to call Cloud foundry (e.g. ```/usr/bin/cf```).
- **SYMFONY__DEPLOYMENT__CF_LOGIN_USERNAME**
  Cloud Foundry login username.  
- **SYMFONY__DEPLOYMENT__CF_LOGIN_PASSWORD**
  Cloud Foundry login password.
- **SYMFONY__DEPLOYMENT__CF_ORGANISATION**
  Cloud Foundry ORG to be used.
- **SYMFONY__DEPLOYMENT__CF_SPACE**
  Cloud Foundry SPACE to be used.
- **SYMFONY__DEPLOYMENT__CF_API_ENDPOINT**
  Cloud Foundry API-URL to be used.

## Usage
### Console command
To get an overview about every available command run:

```bash
$>./bin/console
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
