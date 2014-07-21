# How to contribute

Everybody should be able to help. Here's how you can make this project more
awesome:

1. [Fork it](https://github.com/pyrech/PyrechCronBundle/fork_select)
2. improve it
3. submit a [pull request](https://help.github.com/articles/creating-a-pull-request)

Your work will then be reviewed as soon as possible (suggestions about some
changes, improvements or alternatives may be given).

Here's some tips to make you the best contributor ever:

* [Green tests](#green-tests)
* [Standard code](#standard-code)
* [Branch naming](#branch-naming)
* [Keeping your fork up-to-date](#keeping-your-fork-up-to-date)

## Green tests

Use [PHPUnit](http://phpunit.de/) the run the tests suite:

    phpunit

## Standard code

Use [PHP CS fixer](http://cs.sensiolabs.org/) to make your code compliant with
PyrechCronBundle's coding standards:

    php-cs-fixer fix .

## Branch naming

PyrechCronBundle uses the following prefixes for its branch names:

* __user/*__ for User Stories
* __tech/*__ for Tech Stories
* __doc/*__ for documentation
* __fix/*__ for bug fixes

The second part is an helpful micro title.

## Keeping your fork up-to-date

Once the repository is forked, you should track the upstream (original) one
using the following command:

    git remote add upstream https://github.com/pyrech/PyrechCronBundle.git

Then you should create your own branch, following the
[branch naming policy](VERSIONING.md#branch-naming):

    git checkout -b <prefix>/<micro-title>-<issue-number>

Once your changes are done (`git commit -am '<descriptive-message>'`), get the
upstream changes:

    git checkout master
    git pull --rebase origin master
    git pull --rebase upstream master
    git checkout <your-branch>
    git rebase master

Finally, publish your changes:

    git push -f origin <your-branch>

You should be now ready to make a pull request.
