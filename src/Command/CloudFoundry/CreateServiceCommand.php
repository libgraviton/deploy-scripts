<?php
/**
 * Command to create a Cloud Foundry service.
 */

namespace Graviton\Deployment\Command\CloudFoundry;

use Graviton\Deployment\Command\AbstractCommand;
use Graviton\Deployment\Steps\CloudFoundry\StepCreateService;
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
final class CreateServiceCommand extends AbstractCommand
{

    /**
     * Configures the current command.
     *
     * @return void
     */
    protected function configure()
    {
        $this
            ->setName('graviton:deployment:cf:createService')
            ->setDescription('Create a Cloud Foundry service.')
            ->addArgument(
                'applicationname',
                InputArgument::REQUIRED,
                'Which application shall contain the new service?'
            )
            ->addArgument(
                'servicename',
                InputArgument::REQUIRED,
                'Which service shall be created?'
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
        $applicationName = $input->getArgument('applicationname');
        $serviceName = $input->getArgument('servicename');

        $this->addStep(new StepLogin($this->configuration))
            ->addStep(new StepCreateService($this->configuration, $applicationName, $serviceName))
            ->addStep(new StepLogout($this->configuration));
        $this->setStartMessage('Creating ' . $serviceName . ' service ...');
        parent::execute($input, $output);
    }
}
