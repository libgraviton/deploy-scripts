# graviton/deploy-scripts
[![Build Status](https://travis-ci.org/libgraviton/deploy-scripts.png?branch=develop)](https://travis-ci.org/libgraviton/deploy-scripts) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/libgraviton/deploy-scripts/badges/quality-score.png?b=develop)](https://scrutinizer-ci.com/g/libgraviton/deploy-scripts/?branch=develop) [![Code Coverage](https://scrutinizer-ci.com/g/libgraviton/deploy-scripts/badges/coverage.png?b=develop)](https://scrutinizer-ci.com/g/libgraviton/deploy-scripts/?branch=develop) [![Latest Stable Version](https://poser.pugx.org/deploy-scripts/deploy-scripts/v/stable.svg)](https://packagist.org/packages/deploy-scripts/deploy-scripts) [![Total Downloads](https://poser.pugx.org/deploy-scripts/deploy-scripts/downloads.svg)](https://packagist.org/packages/deploy-scripts/deploy-scripts) [![License](https://poser.pugx.org/deploy-scripts/deploy-scripts/license.svg)](https://packagist.org/packages/deploy-scripts/deploy-scriptsß)

These are currently mia.

I intend this to contain the low level parts of all the ``deploy.sh`` and ``cf-multipush.sh`` things
we have been writing. It should only depend on ``symfony/console`` and ``symfony/process`` and have
heaps of test coverage.

I intend for this to support both cf and docker cases. When it's done we'll wrap it up in a simple
``GravitonDeployBundle`` and the grey beards of early will look upon our continuous deploy rig with
awe and wonder whilst glancing at the door nervously.
