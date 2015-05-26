<?php
/**
 * Command to kill the current session in a Cloud Foundry.
 */

namespace Graviton\Deployment\Command\CloudFoundry;

use Graviton\Deployment\Command\AbstractSingleStepCommand;
use Graviton\Deployment\Steps\CloudFoundry\StepLogout;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author   List of contributors <https://github.com/libgraviton/graviton/graphs/contributors>
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     http://swisscom.ch
 */
final class LogoutCommand extends AbstractSingleStepCommand
{
    /**
     * Configures the current command.
     *
     * @return void
     */
    protected function configure()
    {
        parent::configure('graviton:deployment:cf:logout', 'Closes a user session to a CF instance');
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
        $step = new StepLogout($this->configuration);
        $message = 'Closing user session. Stated messages:';
        parent::execute($step, $message, $output);
    }
}
