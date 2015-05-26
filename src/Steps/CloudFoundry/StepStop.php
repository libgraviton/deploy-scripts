<?php
/**
 * Step to stops an application in a Cloud Foundry instance.
 */

namespace Graviton\Deployment\Steps\CloudFoundry;

/**
 * @author   List of contributors <https://github.com/libgraviton/deploy-scripts/graphs/contributors>
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     http://swisscom.ch
 */
final class StepStop extends AbstractStep
{
    /** @var string */
    private $slice;

    /** @var string */
    private $applicationName;

    /**
     *
     * @param array  $configuration   Current application configuration.
     * @param string $applicationName Name of the CF-application to be checked
     * @param string $slice           deployment location in blue/green deployment.
     *
     * @link http://martinfowler.com/bliki/BlueGreenDeployment.html
     */
    public function __construct(array $configuration, $applicationName, $slice)
    {
        parent::__construct($configuration);

        $this->slice = $slice;
        $this->applicationName = $applicationName;
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
            'stop',
            $this->applicationName . '-' . $this->slice
        );
    }
}
