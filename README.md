# graviton/deploy-scripts
[![Build Status](https://travis-ci.org/libgraviton/deploy-scripts.png?branch=develop)](https://travis-ci.org/libgraviton/deploy-scripts) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/libgraviton/deploy-scripts/badges/quality-score.png?b=develop)](https://scrutinizer-ci.com/g/libgraviton/deploy-scripts/?branch=develop) [![Code Coverage](https://scrutinizer-ci.com/g/libgraviton/deploy-scripts/badges/coverage.png?b=develop)](https://scrutinizer-ci.com/g/libgraviton/deploy-scripts/?branch=develop) [![Latest Stable Version](https://poser.pugx.org/graviton/deploy-scripts/v/stable.svg)](https://packagist.org/packages/graviton/deploy-scripts) [![Total Downloads](https://poser.pugx.org/graviton/deploy-scripts/downloads.svg)](https://packagist.org/packages/graviton/deploy-scripts) [![License](https://poser.pugx.org/graviton/deploy-scripts/license.svg)](https://packagist.org/packages/graviton/deploy-scripts)

## Installation
Install it using [composer](https://getcomposer.org/).

```bash
composer require graviton/deploy-scripts
```

There is also a PHAR file for your convenience, [located here](https://graviton-deploy-scripts.nova.scapp.io/deploy.phar). ;-)

## Configuration
The configuration is to be done in ``app/config/deploy.yml``.
In order to configure this library you have to copy the ``deploy.yml.dist`` to ``deploy.yml`` and modify the latter 
by replacing every value with your personal configuration. 

## PHAR building
In order to build a PHAR file, execute

```bash
composer build
```

The `deploy.phar` will be created in `build/`. This `build/` folder can be pushed to the cloud by using `deploy-scripts` on this repo
(probably using `deploy.phar` itself *g)

## Usage
### Console command
To get an overview about every available command run:

```bash
$>php ./bin/deploy list
```

### In code
see tests ;) 

## Development
We welcome contributions as a pull request on the develop branch.

### Add new Command
Commands need to be registered within bin/deploy
[see here](http://symfony.com/doc/current/components/console/introduction.html)

## Available Commands
### Cloud Foundry specific
- **CheckApplicationCommand**
  Determines, if a special Cloud Foundry application is alive.
- **DeployCommand**
  Deploys an application to a CF instance.
- **RunCommand**
  Runs a shell command on a CF instance. It does not clean up at the moment so you have to do it yourself by running: 
  
   ```bash
   cf a | awk '/-run-/ {print $1}' | xargs -n 1 cf d -
   ```

## Further ideas
- ~~evolve a ``GravitonDeployBundle`` from this library.~~
- provide the possibility to push to multiple Cloud Foundry instances at once by one command.
- ~~add incenteev Parameter handler (https://github.com/Incenteev/ParameterHandler) to be able to use env vars for config.~~
