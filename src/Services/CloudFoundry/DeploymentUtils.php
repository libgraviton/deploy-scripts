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
use Graviton\Deployment\Steps\StepInterface;
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
        $steps = [
            new StepCreateService($configuration, $applicationName, 'mongodb'),
            new StepCreateService($configuration, $applicationName, 'atmoss3'),
        ];

        self::deploySteps(
            $deploy,
            $output,
            $steps,
            'Creating mandatory services'
        );
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
        $slices = ['blue', 'green'];

        try {
            self::deploySteps(
                $deploy,
                $output,
                array(new StepApp($configuration, $applicationName, $slices[0])),
                'Determining which application slice to be deployed',
                '... done',
                false
            );
            $oldSlice = $slices[0];
            $slice = $slices[1];
        } catch (ProcessFailedException $e) {
            $slice = $slices[0];
            $oldSlice = $slices[1];
        }

        try {
            // check, if there is an »old« application as well
            self::deploySteps(
                $deploy,
                $output,
                array(new StepApp($configuration, $applicationName, $oldSlice)),
                'Trying to find deployment slice (' . $oldSlice . ')',
                '... done',
                false
            );
        } catch (ProcessFailedException $e) {
            $slice = $slices[0];
            $oldSlice = $slices[1];

            $output->writeln('Initial Deploy, remember to set up the DB');
        }

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
        self::deploySteps($deploy, $output, array(new StepLogin($configuration)), 'Trying to login', '... done', false);
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
        self::deploySteps($deploy, $output, array(new StepLogout($configuration)), 'Logging out', '... bye.', false);
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
        $steps = array(
            new StepRoute($configuration, $applicationName, $oldTarget, 'unmap'),
            new StepStop($configuration, $applicationName, $oldSlice),
            new StepDelete($configuration, $applicationName, $oldSlice, true)
        );


        try {
            // remove 'old' deployment
            self::deploySteps($deploy, $output, $steps, 'Removing ' . $oldTarget . ' from Cloud Foundry.');
        } catch (ProcessFailedException $e) {
            $output->writeln(
                PHP_EOL .
                '<error>Unable to cleanUp old instances: ' . PHP_EOL . $e->getProcess()->getOutput() . '</error>'
            );
        }
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
        $steps = array(
            new StepPush($configuration, $applicationName, $slice),
            new StepRoute($configuration, $applicationName, $target, 'map')
        );

        self::deploySteps($deploy, $output, $steps, 'Pushing ' . $target . ' to Cloud Foundry.');
    }

    /**
     * Initializes a single
     *
     * @param Deployment      $deploy               Command handler.
     * @param OutputInterface $output               Output of the command
     * @param StepInterface[] $steps                Process step to be executed.
     * @param string          $startMsg             Message to  be shown on start.
     * @param string          $endMsg               Message to be shown on end.
     * @param bool            $returnProcessMessage Include message from process in output..
     *
     * @return void
     */
    private static function deploySteps(
        Deployment $deploy,
        OutputInterface $output,
        array $steps,
        $startMsg,
        $endMsg = '... done',
        $returnProcessMessage = true
    ) {
        $output->write($startMsg);
        $msg = $deploy->resetSteps()
            ->registerSteps($steps)
            ->deploy();
        $output->writeln($endMsg);

        if (true === $returnProcessMessage) {
            $output->writeln('<info>' . $msg . '</info>');
        }
    }
}
