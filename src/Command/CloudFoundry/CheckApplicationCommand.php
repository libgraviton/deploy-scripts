<?php
/**
 * Command to verify the existence of an a application in a Cloud Foundry.
 */

namespace Graviton\Deployment\Command\CloudFoundry;

use Graviton\Deployment\Command\AbstractCommand;
use Graviton\Deployment\Steps\CloudFoundry\StepApp;
use Graviton\Deployment\Steps\CloudFoundry\StepLogin;
use Graviton\Deployment\Steps\CloudFoundry\StepLogout;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author   List of contributors <https://github.com/libgraviton/deploy-scripts/graphs/contributors>
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     http://swisscom.ch
 */
final class CheckApplicationCommand extends AbstractCommand
{
    /**
     * Configures the current command.
     *
     * @return void
     */
    protected function configure()
    {
        $this
            ->setName('graviton:deployment:cf:checkApplication')
            ->setDescription('Determines, if a special CF application is alive.')
            ->addArgument(
                'applicationName',
                InputArgument::REQUIRED,
                'Which application shall be checked?'
            )
            ->addArgument(
                'slice',
                InputArgument::REQUIRED,
                'Which deployed slice (green or blue) do you want to check?'
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
        $applicationName = $input->getArgument('applicationName');
        $slice = $input->getArgument('slice');

        $this->addStep(new StepLogin($this->configuration))
            ->addStep(new StepApp($this->configuration, $applicationName, $slice))
            ->addStep(new StepLogout($this->configuration));
        $this->setStartMessage('Application health check. Stated messages:');
        parent::execute($input, $output);
    }
}
