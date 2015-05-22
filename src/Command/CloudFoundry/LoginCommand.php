<?php

namespace Graviton\Deployment\Command\CloudFoundry;

use Graviton\Deployment\Deployment;
use Graviton\Deployment\Steps\StepLogin;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\ProcessBuilder;

/**
 * @author   List of contributors <https://github.com/libgraviton/graviton/graphs/contributors>
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     http://swisscom.ch
 */
class LoginCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('graviton:deployment:cf:login')
            ->setDescription(
                'Authorises a user to a CF instance. Use environment variables: '.
                '"SYMFONY__DEPLOYMENT__CF_LOGIN_USERNAME", "SYMFONY__DEPLOYMENT__CF_LOGIN_PASSWORD" '.
                '"SYMFONY__DEPLOYMENT__CF_ORGANISATION", "SYMFONY__DEPLOYMENT__CF_SPACE" '.
                'and "SYMFONY__DEPLOYMENT__CF_API_ENDPOINT"'.
                'to make your credentials available to the command.'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Authorising user. Stated messages:');

        $deployment = new Deployment(new ProcessBuilder());
        $deployment
            ->add(new StepLogin())
            ->deploy();

        $output->writeln('... done');
    }
}
