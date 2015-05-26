<?php
/**
 * Command to authorize to a Cloud Foundry.
 */

namespace Graviton\Deployment\Command\CloudFoundry;

use Graviton\Deployment\Command\AbstractCommand;
use Graviton\Deployment\Steps\CloudFoundry\StepAuth;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author   List of contributors <https://github.com/libgraviton/deploy-scripts/graphs/contributors>
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     http://swisscom.ch
 */
final class AuthCommand extends AbstractCommand
{
    /**
     * Configures the current command.
     *
     * @return void
     */
    protected function configure()
    {
        parent::configure();
        $this->setName('graviton:deployment:cf:auth');
        $this->setDescription('Authorises a user to a CF instance.');
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
        $this->addStep(new StepAuth($this->configuration));
        $this->setStartMessage('Authorising user. Stated messages:');
        parent::execute($input, $output);
    }
}
