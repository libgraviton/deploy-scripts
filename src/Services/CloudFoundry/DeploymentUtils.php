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
    /** @var array slices for "Blue/Green-Deployment" mechanism */
    private static $slices = ['blue', 'green'];

    /** @var bool Indicator to signal an initial deployment */
    private static $isInitial = false;

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
        if (empty($configuration['cf']['services'])) {
            $output->writeln('No services define in configuration. Skipping!');
            return;
        }

        $steps = [];
        foreach ($configuration['cf']['services'] as $service => $type) {
            $steps[] = new StepCreateService($configuration, $applicationName, $service, $type);
        }

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
     * **NOTICE**
     * Behavior on different szenarios
     *
     * trail    | blue  | green | deployed
     * ===================================
     * 1st      | n/a   | n/a   |  blue
     * 2nd      | avail | n/a   | green (blue is dropped)
     * 3rd      | n/a   | avail | blue (green is dropped)
     * manually | avail | avail | green (blue will be dropped)
     * altered  |       |       |
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
        try {
            self::deploySteps(
                $deploy,
                $output,
                array(new StepApp($configuration, $applicationName, self::$slices[0])),
                'Determining which application slice to be deployed',
                '',
                false
            );
            $slice = self::$slices[1];
            $oldSlice = self::$slices[0];
        } catch (ProcessFailedException $e) {
            $slice = self::$slices[0];
            $oldSlice = self::$slices[1];
        }

        $output->writeln('... done.');
        $startMsg = '... found. Using slice »' .
            self::renderTargetName($applicationName, $oldSlice) .
            '« as deployment target.';

        try {
            // check, if there is an »old« application as well
            self::deploySteps(
                $deploy,
                $output,
                array(new StepApp($configuration, $applicationName, $oldSlice)),
                'Trying to find deployment slice (' . $oldSlice . ')',
                $startMsg,
                false
            );
            self::$isInitial = false;
        } catch (ProcessFailedException $e) {
            $slice = self::$slices[0];
            $oldSlice = self::$slices[1];

            $output->writeln(
                '... not found. Using slice »' .
                self::renderTargetName($applicationName, $slice) .
                '« as deployment target.'
            );
            $output->writeln('Initial Deploy, remember to set up the DB');
            self::$isInitial = true;
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
     * @param string          $slice           Slice to be removed.
     *
     * @return void
     */
    public static function cleanUp(
        Deployment $deploy,
        OutputInterface $output,
        array $configuration,
        $applicationName,
        $slice
    ) {
        $target = self::renderTargetName($applicationName, $slice);
        $steps = array(
            new StepRoute($configuration, $applicationName, $target, 'unmap'),
            new StepStop($configuration, $applicationName, $slice),
            new StepDelete($configuration, $applicationName, $slice, true)
        );


        try {
            // remove 'old' deployment
            self::deploySteps($deploy, $output, $steps, 'Removing »' . $target . '« from Cloud Foundry.');
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
        $target = self::renderTargetName($applicationName, $slice);
        $output->writeln('Will deploy application: »' . $target . '«.');
        $steps = array(
            new StepPush($configuration, $applicationName, $slice),
            new StepRoute($configuration, $applicationName, $target, 'map')
        );

        self::deploySteps(
            $deploy,
            $output,
            $steps,
            'Pushing ' . $target . ' to Cloud Foundry.' . PHP_EOL,
            '... done.',
            true,
            true
        );
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
     * @param bool            $forceImmediateOutput Forces the process to send every output to stdout immediately.
     *
     * @return void
     */
    private static function deploySteps(
        Deployment $deploy,
        OutputInterface $output,
        array $steps,
        $startMsg,
        $endMsg = '... done',
        $returnProcessMessage = true,
        $forceImmediateOutput = false
    ) {
        $output->write($startMsg);
        $msg = $deploy->resetSteps()
            ->registerSteps($steps)
            ->deploy($forceImmediateOutput);
        $output->writeln($endMsg);

        if (true === $returnProcessMessage) {
            $output->writeln('<info>' . $msg . '</info>');
        }
    }

    /**
     * Determine if the slice to be deploy
     *
     * @return bool
     */
    public static function isInitialDeploy()
    {
        return self::$isInitial;
    }

    /**
     * Provides the name of the target to used.
     *
     * @param string $application Name of the current application
     * @param string $slice       Slice to be used (from blue/green deployment)
     *
     * @return string
     */
    private static function renderTargetName($application, $slice)
    {
        return sprintf('%s-%s', $application, $slice);
    }
}
