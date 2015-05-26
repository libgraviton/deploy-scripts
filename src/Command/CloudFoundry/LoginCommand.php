<?php
/**
 * Command to log in to a Cloud Foundry.
 */

namespace Graviton\Deployment\Command\CloudFoundry;

use Graviton\Deployment\Command\AbstractSingleStepCommand;
use Graviton\Deployment\Steps\CloudFoundry\StepLogin;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author   List of contributors <https://github.com/libgraviton/graviton/graphs/contributors>
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     http://swisscom.ch
 */
final class LoginCommand extends AbstractSingleStepCommand
{
    /**
     * Configures the current command.
     *
     * @return void
     */
    protected function configure()
    {
        parent::configure('graviton:deployment:cf:login', 'Authorises a user to a CF instance.');
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
        $step = new StepLogin($this->configuration);
        $message = 'Authorising user. Stated messages:';
        parent::execute($step, $message, $output);
    }
}
