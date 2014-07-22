# PyrechCronBundle

*Easily setup your cron*.

This bundle will allow you to:

* configure task to execute periodically
* easily adapt your symfony command into a scheduled task
* dump all the tasks into the format used on your system (only crontab is currently supported)
* eventually register the tasks directly

**Caution**: still under heavy development and not yet usable.

[![Travis CI](https://travis-ci.org/pyrech/PyrechCronBundle.png)](https://travis-ci.org/pyrech/PyrechCronBundle)
[![Code Coverage](https://scrutinizer-ci.com/g/pyrech/PyrechCronBundle/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/pyrech/PyrechCronBundle/?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/pyrech/PyrechCronBundle/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/pyrech/PyrechCronBundle/?branch=master)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/aa318e9e-cb57-449a-8c32-fd4cf54b47ef/small.png)](https://insight.sensiolabs.com/projects/aa318e9e-cb57-449a-8c32-fd4cf54b47ef)

## Getting started

Use [Composer](http://getcomposer.org/) to download and install PyrechCronBundle in
your projects:

    composer require "pyrech/cron-bundle:dev-master"

Finally, enable the bundle in the kernel:

    <?php
    // app/AppKernel.php

    public function registerBundles()
    {
        $bundles = array(
            // ...
            new Pyrech\CronBundle\PyrechCronBundle(),
        );
    }


## Further documentation

You can see the current and past versions using one of the following:

* the `git tag` command
* the [releases page on Github](https://github.com/pyrech/PyrechCronBundle/releases)
* the file listing the [changes between versions](CHANGELOG.md)

You can find more documentation at the following links:

* [copyright and MIT license](Resources/meta/LICENSE)
* [versioning and branching models](VERSIONING.md)
* [contribution instructions](CONTRIBUTING.md)
