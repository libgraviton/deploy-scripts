<?php
/**
 * Step to push application into a Cloud Foundry instance.
 */

namespace Graviton\Deployment\Steps\CloudFoundry;

/**
 * @author   List of contributors <https://github.com/libgraviton/deploy-scripts/graphs/contributors>
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     http://swisscom.ch
 */
final class StepPush extends AbstractCommonStep
{
    /**
     * indicate if --no-start flag is to be used or not
     *
     * @var boolean
     */
    protected $start;

    /**
     * @var string Name of the step to be registered.
     */
    protected static $stepName = 'push';

    /**
     * @param array   $configuration   Current application configuration.
     * @param string  $applicationName Name of the CF-application to be checked
     * @param string  $slice           deployment location in blue/green deployment.
     * @param boolean $start           start the app on push or use --no-start flag
     */
    public function __construct(array $configuration, $applicationName, $slice, $start = true)
    {
        parent::__construct($configuration, $applicationName, $slice);

        $this->start = $start;
    }

    /**
     * returns the command
     *
     * @return array
     */
    public function getCommand()
    {
        $command = parent::getCommand();

        if (!$this->start) {
            $command[] = '--no-start';
        }
        return $command;
    }
}
