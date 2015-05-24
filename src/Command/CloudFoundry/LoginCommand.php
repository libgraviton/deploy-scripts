<?php
/**
 * Command to log in to a Cloud Foundry.
 */

namespace Graviton\Deployment\Command\CloudFoundry;

use Graviton\Deployment\Command\AbstractCommand;
use Graviton\Deployment\Deployment;
use Graviton\Deployment\Steps\CloudFoundry\StepLogin;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\ProcessBuilder;

/**
 * @author   List of contributors <https://github.com/libgraviton/graviton/graphs/contributors>
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     http://swisscom.ch
 */
final class LoginCommand extends AbstractCommand
{
    /**
     * Configures the current command.
     *
     * @return void
     */
    protected function configure()
    {
        $this
            ->setName('graviton:deployment:cf:login')
            ->setDescription(
                'Authorises a user to a CF instance. Use environment variables: ' .
                '"SYMFONY__DEPLOYMENT__CF_LOGIN_USERNAME", "SYMFONY__DEPLOYMENT__CF_LOGIN_PASSWORD" ' .
                '"SYMFONY__DEPLOYMENT__CF_ORGANISATION", "SYMFONY__DEPLOYMENT__CF_SPACE" ' .
                'and "SYMFONY__DEPLOYMENT__CF_API_ENDPOINT"' .
                'to make your credentials available to the command.'
            );
    }

    /**
     * Executes the current command.
     *
     * @param InputInterface  $input  User input on console
     * @param OutputInterface $output Output of the command
     *
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Authorising user. Stated messages:');

        $deployment = new Deployment(new ProcessBuilder());
        $deployment
            ->add(new StepLogin($this->configuration))
            ->deploy();

        $output->writeln('... done');
    }
}
