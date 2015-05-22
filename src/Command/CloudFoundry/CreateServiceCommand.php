<?php
/**
 * Command to create a Cloud Foundry service.
 */

namespace Graviton\Deployment\Command\CloudFoundry;

use Graviton\Deployment\Deployment;
use Graviton\Deployment\Steps\CloudFoundry\StepCreateService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\ProcessBuilder;

/**
 * @author   List of contributors <https://github.com/libgraviton/graviton/graphs/contributors>
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     http://swisscom.ch
 */
final class CreateServiceCommand extends Command
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
            ->setDescription('Create a CF service. Use environment variable: '.
                '"SYMFONY__DEPLOYMENT__CF_{{Service}}_TYPE"'.
                'to make the service type available to the command.')
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
        $applicationname = $input->getArgument('applicationname');
        $servicename = $input->getArgument('servicename');
        
        $output->writeln('Creating ' . $servicename . ' service ...');

        $deployment = new Deployment(new ProcessBuilder());
        $deployment
            ->add(new StepCreateService($applicationname, $servicename))
            ->deploy();

        $output->writeln('... done');
    }
}