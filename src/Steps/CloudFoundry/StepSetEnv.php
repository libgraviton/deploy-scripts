<?php
/**
 * Step to log off a Cloud Foundry instance.
 */

namespace Graviton\Deployment\Steps\CloudFoundry;

/**
 * @author   List of contributors <https://github.com/libgraviton/deploy-scripts/graphs/contributors>
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     http://swisscom.ch
 */
final class StepSetEnv extends AbstractStep
{
    /** @var string  */
    private $envVarName;

    /** @var string  */
    private $envVarValue;

    /** @var string  */
    private $application;

    /**
     * @param array  $configuration Current application configuration.
     * @param string $application   Name of the CF-application (e.g. graviton-unstable-blue)
     * @param string $envVarName    Name of the environment variable to be set.
     * @param string $envVarValue   Content of the environment variable to be set.
     */
    public function __construct(array $configuration, $application, $envVarName, $envVarValue)
    {
        parent::__construct($configuration);

        $this->envVarName = $envVarName;
        $this->envVarValue = $envVarValue;
        $this->application = $application;
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
            'set-env',
            $this->application,
            $this->envVarName,
            $this->envVarValue
        );
    }
}
