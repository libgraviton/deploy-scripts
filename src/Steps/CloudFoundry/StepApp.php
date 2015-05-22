<?php
/**
 * Step to verify the existence of an application in a Cloud Foundry instance.
 */

namespace Graviton\Deployment\Steps\CloudFoundry;

/**
 * @author   List of contributors <https://github.com/libgraviton/graviton/graphs/contributors>
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     http://swisscom.ch
 */
final class StepApp extends AbstractStep
{
    /** @var string  */
    private $slice;

    /** @var string  */
    private $applicationName;

    /**
     * @param string $applicationName Name of the CF-application to be checked
     * @param string $slice           deployment location in blue/green deployment.
     *
     * @link http://martinfowler.com/bliki/BlueGreenDeployment.html
     */
    public function __construct($applicationName, $slice)
    {
        $this->slice = $slice;
        $this->applicationName = $applicationName;
    }

    /**
     * returns the command
     *
     * @return string
     */
    public function getCommand()
    {
        return array(
            $this->cfCommand(),
            'app',
            $this->applicationName . '-' . $this->slice
        );
    }
}
