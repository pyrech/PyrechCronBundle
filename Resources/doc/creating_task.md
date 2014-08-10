# Creating your task

In order to schedule your Tasks, you first have to create them! :)

*PyrechCronBundle* currently provides you two ways to create your scheduled
Tasks:

* [From your Command](#from-your-command)
* [From the config](#from-the-config)

## From your Command

The first goal of this bundle is to allow you to easily make your console
commands schedulable.

First, it requires that your commands are usable in your console application.
It should probably already be done. As a reminder, it means that Symfony can
register your `Command` automatically, which is the case either if you respect
convention (commands placed in the `Command` directory of your bundle, class
name suffixed by `Command` and extending the base `Command` class from the
*Console* component) or [configured as a service](http://symfony.com/doc/current/cookbook/console/commands_as_services.html)
tagged with `console.command`.

Then, to transform your `Command` into a scheduled `Task`, it should implement
`Pyrech\CronBundle\Scheduling\SchedulableInterface` and its unique method
`configTask`:

    use Pyrech\CronBundle\Scheduling\SchedulableInterface;
    use Pyrech\CronBundle\Scheduling\TaskBuilderInterface;
    ...

    class MyCommand extends Command implements SchedulableInterface
    {
        protected function configure() {...}
        protected function execute(InputInterface $input, OutputInterface $output) {...}

        public function configTask(TaskBuilderInterface $builder)
        {
            $builder
                ->setCommand($this)
                ->setWeekly()
            ;
        }
    }

If you look at the `TaskBuilderInterface`, you will see a bunch of methods to
configure the task as you want (job, frequency or custom time, etc). The
`setCommand` method is where the magic takes place. It transforms the `Command`
into a job:

* find the php binary
* find the bootstrap console (based on the kernel root dir, it looks for
`console` or `../bin/console`)
* use the command name

For example, `acme:test` command would probably be converted into the following
job:

    /usr/bin/php /var/www/my_project/app/console acme:test

**Note**: If your console is not in default locations, you should specified it
using the `pyrech_cron.console_paths` parameter.

    // app/config/config.yml
    ...

    pyrech_cron:
        console_paths: "../bin/mycustomconsole"


## From the config

Tasks in *PyrechCronBundle* can also be configured in bundle config when they
are not related to *your* console command.

Under the pyrech_cron entry, you can configure tasks this way (task's name is
not yet used inside the bundle):

    // app/config/config.yml
    ...

    pyrech_cron:
        tasks:
            my_first_task:
                job: "echo 'Im your father!' | mail -s 'You have to know...' luke@jedi.org"
                frequency: "monthly"

            my_second_task:
                job: "@phpbin /path/to/your/script"
                when: # Every two hours on saturday
                    minute: 0
                    hour: "*/2"
                    day_of_the_week: 6

You can specify any job that is runnable in your shell. You can use the special
syntax `@phpbin` that will be replaced by the php bin path (f.e `/usr/bin/php`
on Ubuntu).

You have the choice to configure either frequency (i.e `hourly`, `daily`,
`weekly`, `monthly`, `yearly`) or a completely custom setup by setting
`minute`, `hour`, `day`, `month`, `day_of_the_week` in the `when` section
