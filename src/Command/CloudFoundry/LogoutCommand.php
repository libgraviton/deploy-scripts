<?php

namespace Graviton\Deployment\Command\CloudFoundry;

use Graviton\Deployment\Deployment;
use Graviton\Deployment\Steps\StepLogout;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\ProcessBuilder;

/**
 * @author   List of contributors <https://github.com/libgraviton/graviton/graphs/contributors>
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     http://swisscom.ch
 */
class LogoutCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('graviton:deployment:cf:logout')
            ->setDescription('Closes a user session to a CF instance');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Closing user session. Stated messages:');

        $deployment = new Deployment(new ProcessBuilder());
        $deployment
            ->add(new StepLogout())
            ->deploy();

        $output->writeln('... done');
    }
}
