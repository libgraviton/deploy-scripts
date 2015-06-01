<?php
/**
 * Utils for a successful CF deployment.
 */

namespace Graviton\Deployment\Services\CloudFoundry;

use Graviton\Deployment\Deployment;
use Graviton\Deployment\Steps\CloudFoundry\StepApp;
use Graviton\Deployment\Steps\CloudFoundry\StepCreateService;
use Graviton\Deployment\Steps\CloudFoundry\StepDelete;
use Graviton\Deployment\Steps\CloudFoundry\StepLogin;
use Graviton\Deployment\Steps\CloudFoundry\StepLogout;
use Graviton\Deployment\Steps\CloudFoundry\StepPush;
use Graviton\Deployment\Steps\CloudFoundry\StepRoute;
use Graviton\Deployment\Steps\CloudFoundry\StepStop;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Exception\ProcessFailedException;

/**
 * @author   List of contributors <https://github.com/libgraviton/deploy-scripts/graphs/contributors>
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     http://swisscom.ch
 */
final class DeploymentUtils
{
    /**
     * Creates mandatory services on CF.
     *
     * @param Deployment      $deploy          Command handler.
     * @param OutputInterface $output          Output of the command
     * @param array           $configuration   Application configuration (read from config.yml)
     * @param string          $applicationName Application to be used
     *
     * @return void
     */
    public static function createServices(
        Deployment $deploy,
        OutputInterface $output,
        array $configuration,
        $applicationName
    ) {
        $services = ['mongodb', 'atmoss3'];

        $output->write('Creating mandatory services');
        $deploy->resetSteps();

        foreach ($services as $service) {
            $deploy->add(new StepCreateService($configuration, $applicationName, $service));
        }
        $deploy->deploy();
        $output->writeln('... done');
    }

    /**
     * Determines what slice to be used on blue/green deployment.
     *
     * @param Deployment      $deploy          Command handler.
     * @param OutputInterface $output          Output of the command
     * @param array           $configuration   Application configuration (read from config.yml).
     * @param string          $applicationName Application to be cleaned up
     *
     * @return array
     */
    public static function determineDeploymentSlice(
        Deployment $deploy,
        OutputInterface $output,
        array $configuration,
        $applicationName
    ) {
        // blue/green deployment
        $output->write('Determining which application slice to be deployed');
        $deploy->resetSteps();
        $slices = ['blue', 'green'];

        try {
            $deploy
                ->add(new StepApp($configuration, $applicationName, $slices[0]))// check for 'blue' first
                ->deploy();
            $slice = $slices[0];
            $oldSlice = $slices[1];
        } catch (ProcessFailedException $e) {
            $oldSlice = $slices[0];
            $slice = $slices[1];
        }

        // check, if there is an »old« application as well
        $deploy->resetSteps();
        $deploy
            ->add(new StepApp($configuration, $applicationName, $oldSlice))
            ->deploy();

        $output->writeln('... done');

        return array($slice, $oldSlice);
    }

    /**
     * Logs in to CF.
     *
     * @param Deployment      $deploy        Command handler.
     * @param OutputInterface $output        Output of the command
     * @param array           $configuration Application configuration (read from config.yml).
     *
     * @return void
     */
    public static function login(Deployment $deploy, OutputInterface $output, array $configuration)
    {
        $output->write('Trying to login');
        $deploy->resetSteps();
        $deploy
            ->add(new StepLogin($configuration))
            ->deploy();
        $output->writeln('... done');
    }

    /**
     * Logs off from CF.
     *
     * @param Deployment      $deploy        Command handler.
     * @param OutputInterface $output        Output of the command
     * @param array           $configuration Application configuration (read from config.yml).
     *
     * @return void
     */
    public static function logout(Deployment $deploy, OutputInterface $output, array $configuration)
    {
        $output->write('Logging out');
        $deploy->resetSteps();
        $deploy
            ->add(new StepLogout($configuration))
            ->deploy();
        $output->writeln('... bye.');
    }

    /**
     * Removes instances of the application not needed anymore.
     *
     * @param Deployment      $deploy          Command handler.
     * @param OutputInterface $output          Output of the command
     * @param array           $configuration   Application configuration (read from config.yml).
     * @param string          $applicationName Application to be cleaned up
     * @param string          $oldSlice        Slice to be removed.
     *
     * @return void
     */
    public static function cleanUp(
        Deployment $deploy,
        OutputInterface $output,
        array $configuration,
        $applicationName,
        $oldSlice
    ) {
        $oldTarget = $applicationName . '-' . $oldSlice;
        $output->write('Removing ' . $oldTarget . ' from Cloud Foundry.');

        $deploy->resetSteps();
        $deploy
            ->add(new StepRoute($configuration, $oldTarget, 'unmap'))
            ->add(new StepStop($configuration, $applicationName, $oldSlice))
            ->add(new StepDelete($configuration, $applicationName, $oldSlice, true))
            ->deploy();
        $output->writeln('... done');
    }

    /**
     * Removes instances of the application not needed anymore.
     *
     * @param Deployment      $deploy          Command handler.
     * @param OutputInterface $output          Output of the command
     * @param array           $configuration   Application configuration (read from config.yml).
     * @param string          $applicationName Application to be cleaned up
     * @param string          $slice           Slice to be deployed.
     *
     * @return void
     */
    public static function deploy(
        Deployment $deploy,
        OutputInterface $output,
        array $configuration,
        $applicationName,
        $slice
    ) {
        $target = $applicationName . '-' . $slice;
        $output->writeln('Will deploy application: ' . $target);
        $output->write('Pushing ' . $target . ' to Cloud Foundry.');

        $deploy->resetSteps();
        $deploy
            ->add(new StepPush($configuration, $applicationName, $slice))
            ->add(new StepRoute($configuration, $target, 'map'))
            ->deploy();
        $output->writeln('... done');
    }
}
