<?php
/**
 * Command to deploy an application to a Cloud Foundry.
 */

namespace Graviton\Deployment\Command\CloudFoundry;

use Graviton\Deployment\Command\AbstractCommand;
use Graviton\Deployment\Deployment;
use Graviton\Deployment\Steps\CloudFoundry\StepApp;
use Graviton\Deployment\Steps\CloudFoundry\StepCreateService;
use Graviton\Deployment\Steps\CloudFoundry\StepDelete;
use Graviton\Deployment\Steps\CloudFoundry\StepLogin;
use Graviton\Deployment\Steps\CloudFoundry\StepPush;
use Graviton\Deployment\Steps\CloudFoundry\StepRoute;
use Graviton\Deployment\Steps\CloudFoundry\StepStop;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\ProcessBuilder;

/**
 * @author   List of contributors <https://github.com/libgraviton/deploy-scripts/graphs/contributors>
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     http://swisscom.ch
 */
final class DeployCommand extends AbstractCommand
{
    /**
     * @var array
     */
    protected $slices = array('blue', 'green');

    /**
     * Configures the current command.
     *
     * @return void
     */
    protected function configure()
    {
        $this
            ->setName('graviton:deployment:cf:deploy')
            ->setDescription('Deploys an application to a CF instance.')
            ->addArgument(
                'applicationName',
                InputArgument::REQUIRED,
                'Which application shall be deployed?'
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

        $output->writeln('Deploying application (' . $applicationName . ') to a Cloudfounrdy instance.');

        // prepare cf instance
        $output->write('Creating services for MongoDB and AtmosS3');
        $prepare = new Deployment(new ProcessBuilder());
        $prepare
            ->add(new StepLogin($this->configuration))
            ->add(new StepCreateService($this->configuration, $applicationName, 'mongodb'))
            ->add(new StepCreateService($this->configuration, $applicationName, 'atmoss3'))
            ->deploy();
        $output->writeln('... done');

        // determine which application slice to be deployed (blue/green deployment)
        $output->write('Determining which application slice to be deployed');
        $determineSlice = new Deployment(new ProcessBuilder());
        try {
            $determineSlice
                ->add(new StepApp($this->configuration, $applicationName, $this->slices[0])) // check for 'blue' first
                ->deploy();
            $slice = $this->slices[0];
            $oldSlice = $this->slices[1];
        } catch (ProcessFailedException $e) {
            $oldSlice = $this->slices[0];
            $slice = $this->slices[1];
        }

        // check, if there is an »old« application as well
        $checkSlice = new Deployment(new ProcessBuilder());
        $checkSlice
            ->add(new StepApp($this->configuration, $applicationName, $oldSlice))
            ->deploy();

        $output->writeln('... done');

        // deploy application
        $target = $applicationName . '-' . $slice;
        $output->writeln('Will deploy application: ' . $target);
        $output->write('Pushing ' . $target . ' to Cloud Foundry.');
        $deploy = new Deployment(new ProcessBuilder());
        $deploy
            ->add(new StepPush($this->configuration, $applicationName, $slice))
            ->add(new StepRoute($this->configuration, $target, 'map'))
            ->deploy();
        $output->writeln('... done');

        // cleanup old application
        $oldTarget = $applicationName . '-' . $oldSlice;
        $output->write('Removing ' . $oldTarget . ' from Cloud Foundry.');
        $cleanUp = new Deployment(new ProcessBuilder());
        $cleanUp
            ->add(new StepRoute($this->configuration, $oldTarget, 'unmap'))
            ->add(new StepStop($this->configuration, $applicationName, $oldSlice))
            ->add(new StepDelete($this->configuration, $applicationName, $oldSlice, true))
            ->deploy();
        $output->writeln('... done');
    }
}