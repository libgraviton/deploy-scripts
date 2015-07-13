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
    /**
     * @var string
     */
    private $applicationName;

    /**
     * @var string
     */
    private $serviceType;

    /*
     * @var string
     */
    private $servicePlan;

    /**
     * @var string
     */
    private $serviceName;


    /**
     *
     * Note:
     * use »cf m« to find supported services anf types.
     *
     * @param array  $configuration   Current application configuration.
     * @param string $applicationName Name of the CF-application to be checked
     * @param string $serviceType     Type of the CF service to create
     * @param string $servicePlan     Plan of the CF service to create
     * @param string $serviceName     Name of the CF service to create, defaults to $serviceType
     */
    public function __construct(array $configuration, $applicationName, $serviceType, $servicePlan, $serviceName = null)
    {
        parent::__construct($configuration);

        $this->applicationName = $applicationName;
        $this->serviceType = $serviceType;
        $this->servicePlan = $servicePlan;
        $this->serviceName = $serviceName;
        if (is_null($serviceName)) {
            $this->serviceName = $serviceType;
        }
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
            'cs',
            $this->serviceType,
            $this->servicePlan,
            $this->applicationName . '-' . $this->serviceName
        );
    }
}
