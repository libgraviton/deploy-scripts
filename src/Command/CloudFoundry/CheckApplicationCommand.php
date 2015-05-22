<?php

namespace Graviton\Deployment\Command\CloudFoundry;

use Graviton\Deployment\Deployment;
use Graviton\Deployment\Steps\StepCheckApp;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\ProcessBuilder;

/**
 * @author   List of contributors <https://github.com/libgraviton/graviton/graphs/contributors>
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     http://swisscom.ch
 */
class CheckApplicationCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('graviton:deployment:cf:checkApplication')
            ->setDescription('Determines, if a special CF application is alive.')
            ->addArgument(
                'name',
                InputArgument::REQUIRED,
                'Which application shall be checked?'
            )
            ->addArgument(
               'slice',
               InputArgument::REQUIRED,
               'Which deployed slice (green or blue) do you want to check?'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $name = $input->getArgument('name');
        $slice = $input->getArgument('slice');

        $output->writeln('Deployment running. Stated messages:');

        $deployment = new Deployment(new ProcessBuilder());
        $deployment
            ->add(new StepCheckApp($name, $slice))
            ->deploy();

        $output->writeln('... done');
    }
}
