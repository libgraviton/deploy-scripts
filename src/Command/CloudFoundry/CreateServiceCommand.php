<?php
/**
 * Command to create a Cloud Foundry service.
 */

namespace Graviton\Deployment\Command\CloudFoundry;

use Graviton\Deployment\Command\AbstractCommand;
use Graviton\Deployment\Deployment;
use Graviton\Deployment\Steps\CloudFoundry\StepCreateService;
use Graviton\Deployment\Steps\CloudFoundry\StepLogin;
use Graviton\Deployment\Steps\CloudFoundry\StepLogout;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\ProcessBuilder;

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
            ->setDescription('Create a CF service.')
            ->addArgument(
                'applicationname',
                InputArgument::REQUIRED,
                'Which application shall be checked?'
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

        $output->writeln('Creating ' . $serviceName . ' service ...');

        $deployment = new Deployment(new ProcessBuilder());
        $deployment
            ->add(new StepLogin($this->configuration))
            ->add(new StepCreateService($this->configuration, $applicationName, $serviceName))
            ->add(new StepLogout($this->configuration))
        ->deploy();

        $output->writeln('... done');
    }
}
