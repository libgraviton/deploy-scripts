<?php

/**
 * Step to bind a service to a instance
 */

namespace Graviton\Deployment\Steps\CloudFoundry;

/**
 * @author   List of contributors <https://github.com/libgraviton/deploy-scripts/graphs/contributors>
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     http://swisscom.ch
 */
final class StepBindService extends AbstractStep
{
    /** @var string  */
    private $serviceName;

    /** @var string  */
    private $applicationName;

    /**
     * @var string
     **/
    private $slice;

    /**
     * @param array  $configuration   Current application configuration.
     * @param string $applicationName Name of the CF-application to be checked
     * @param string $slice           deployment location in blue/green deployment.
     * @param string $serviceName     Name of the CF service to create
     */
    public function __construct(array $configuration, $applicationName, $slice, $serviceName)
    {
        parent::__construct($configuration);

        $this->applicationName = $applicationName;
        $this->slice = $slice;
        $this->serviceName = $serviceName;
    }

    /**
     * returns the command
     *
     * @return array
     */
    public function getCommand()
    {
        return array(
            $this->configuration['cf_bin'],
            'bind-service',
            $this->applicationName . '-' . $this->slice,
            $this->applicationName . '-' . $this->serviceName,
        );
    }
}
