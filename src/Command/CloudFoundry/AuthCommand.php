<?php
/**
 * Command to authorize to a Cloud Foundry.
 */

namespace Graviton\Deployment\Command\CloudFoundry;

use Graviton\Deployment\Command\AbstractCommand;
use Graviton\Deployment\Command\AbstractSingleStepCommand;
use Graviton\Deployment\Deployment;
use Graviton\Deployment\Steps\CloudFoundry\StepAuth;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\ProcessBuilder;

/**
 * @author   List of contributors <https://github.com/libgraviton/graviton/graphs/contributors>
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     http://swisscom.ch
 */
final class AuthCommand extends AbstractSingleStepCommand
{
    /**
     * Configures the current command.
     *
     * @return void
     */
    protected function configure()
    {
        parent::configure('graviton:deployment:cf:auth', 'Authorises a user to a CF instance.');
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
        $step = new StepAuth($this->configuration);
        $message = 'Authorising user. Stated messages:';
        parent::execute($step, $message, $input, $output);
    }
}
