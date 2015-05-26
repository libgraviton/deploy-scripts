<?php
/**
 * Command to kill the current session in a Cloud Foundry.
 */

namespace Graviton\Deployment\Command\CloudFoundry;

use Graviton\Deployment\Command\AbstractCommand;
use Graviton\Deployment\Steps\CloudFoundry\StepLogout;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author   List of contributors <https://github.com/libgraviton/deploy-scripts/graphs/contributors>
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     http://swisscom.ch
 */
final class LogoutCommand extends AbstractCommand
{
    /**
     * Configures the current command.
     *
     * @return void
     */
    protected function configure()
    {
        parent::configure();
        $this->setName('graviton:deployment:cf:logout');
        $this->setDescription('Closes a user session to a CF instance.');
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
        $this->addStep(new StepLogout($this->configuration));
        $this->setStartMessage('Closing user session. Stated messages:');
        parent::execute($input, $output);
    }
}
