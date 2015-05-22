<?php
/**
 * Command to authorize to a Cloud Foundry.
 */

namespace Graviton\Deployment\Command\CloudFoundry;

use Graviton\Deployment\Deployment;
use Graviton\Deployment\Steps\StepAuth;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\ProcessBuilder;

/**
 * @author   List of contributors <https://github.com/libgraviton/graviton/graphs/contributors>
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     http://swisscom.ch
 */
class AuthCommand extends Command
{
    /**
     * Configures the current command.
     *
     * @return void
     */
    protected function configure()
    {
        $this
            ->setName('graviton:deployment:cf:auth')
            ->setDescription(
                'Authorises a user to a CF instance. Use environment variables: ' .
                '"SYMFONY__DEPLOYMENT__CF_LOGIN_USERNAME", "SYMFONY__DEPLOYMENT__CF_LOGIN_PASSWORD" ' .
                'to make your credentials available to the command.'
            );
    }

    /**
     * Executes the current command.
     *
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Authorising user. Stated messages:');

        $deployment = new Deployment(new ProcessBuilder());
        $deployment
            ->add(new StepAuth())
            ->deploy();

        $output->writeln('... done');
    }
}
