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
     * @var string
     **/
    private $customServiceName;

    /**
     * @param array  $configuration   Current application configuration.
     * @param string $applicationName Name of the CF-application to be checked
     * @param string $slice           deployment location in blue/green deployment.
     * @param string $serviceName     Name of the CF service to create
     * @param string boolean          use a custom service name
     */
    public function __construct(array $configuration, $applicationName, $slice, $serviceName, $customServiceName = false)
    {
        parent::__construct($configuration);

        $this->applicationName = $applicationName;
        $this->slice = $slice;
        $this->serviceName = $serviceName;
        $this->customServiceName = $customServiceName;
    }

    /**
     * returns the command
     *
     * @return array
     */
    public function getCommand()
    {
        if($this->customServiceName){
           $command =  array(
               $this->configuration['cf_bin'],
               'bind-service',
               $this->applicationName . '-' . $this->slice,
               $this->serviceName,
           );
        } else {
            $command = array(
                $this->configuration['cf_bin'],
                'bind-service',
                $this->applicationName . '-' . $this->slice,
                $this->applicationName . '-' . $this->serviceName,
        );

        }
        return $command;
    }
}

