<?php

/**
 * Step to create mongo instance on CloudFoundry.
 */

namespace Graviton\Deployment\Steps\CloudFoundry;

/**
 * @author   List of contributors <https://github.com/libgraviton/deploy-scripts/graphs/contributors>
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     http://swisscom.ch
 */
final class StepCreateService extends AbstractStep
{
    /** @var string  */
    private $serviceName;

    /** @var string  */
    private $applicationName;

    /**
     *
     * @param array  $configuration   Current application configuration.
     * @param string $applicationName Name of the CF-application to be checked
     * @param string $serviceName     Name of the CF service to create
     */
    public function __construct(array $configuration, $applicationName, $serviceName)
    {
        parent::__construct($configuration);

        $this->applicationName = $applicationName;
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
            $this->configuration['cf']['command'],
            'cs',
            $this->serviceName,
            $this->configuration['cf']['services'][$this->serviceName]['type'],
            $this->applicationName . '-' . $this->serviceName
        );
    }
}
