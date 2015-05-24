<?php
/**
 * Command to deploy an application to a Cloud Foundry.
 */

namespace Graviton\Deployment\Command\CloudFoundry;

use Graviton\Deployment\Command\AbstractCommand;
use Graviton\Deployment\Deployment;
use Graviton\Deployment\Steps\CloudFoundry\StepCreateService;
use Graviton\Deployment\Steps\CloudFoundry\StepLogin;
use Graviton\Deployment\Steps\CloudFoundry\StepPush;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\ProcessBuilder;

/**
 * @author   List of contributors <https://github.com/libgraviton/graviton/graphs/contributors>
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
                'name',
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
        $name = $input->getArgument('name');

        $output->writeln('Deploying application (' . $name . ') to a Cloudfounrdy instance.');

        // prepare cf instance
        $output->write('Creating services for MongoDB and AtmosS3')
        $prepare = new Deployment(new ProcessBuilder());
        $prepare
            ->add(new StepLogin($this->configuration))
            ->add(new StepCreateService($this->configuration, $name, 'mongodb'))
            ->add(new StepCreateService($this->configuration, $name, 'atmoss3'))
            ->deploy();
        $output->writeln('... done');

        // determine which application slice to be deployed
        $output->write('Determining which application slice to be deployed')
        $determineSlice = new Deployment(new ProcessBuilder());
        try {
            $determineSlice
                ->add(new StepApp($this->configuration, $this->slices[0]))// blue first
                ->deploy()
            $slice = $this->slices[0];
            $oldSlice = $this->slices[1];
        } catch (Symfony\Component\Process\Exception\ProcessFailedException $e) {
            $oldSlice = $this->slices[0];
            $slice = $this->slices[1];
        }
        // check, if there is an »old« application as well
        $checkSlice = new Deployment(new ProcessBuilder());
        $checkSlice
            ->add(new StepApp($this->configuration, $name, $oldSlice))
            ->deploy()

        $output->writeln('... done');

        $target = $name . '-' . $slice;

        $output->writeln('Will deploy application: ' . $target);

        // deploy application
        $output->write('Pushing ' . $target . ' to Cloud Foundry.');
        $deploy = new Deployment(new ProcessBuilder());
        $deploy
            ->add(new StepPush($this->configuration, $name, $slice))
            ->add(new StepRoute($this->configuration, $target, 'map'))
            ->deploy();
        $output->writeln('... done');

        // cleanup old application
        $output->write('Removing ' . $name . '-' . $oldSlice . ' from Cloud Foundry.');
        $cleanUp = new Deployment(new ProcessBuilder());
        $cleanUp
            ->add(new StepRoute($this->configuration, $name . '-' . $oldSlice, 'unmap'))
            ->add(new StepStop($this->configuration, $name, $oldSlice))
            ->add(new StepDelete($this->configuration, $name, $oldSlice, true))
            ->deploy();
        $output->writeln('... done');
    }
}
