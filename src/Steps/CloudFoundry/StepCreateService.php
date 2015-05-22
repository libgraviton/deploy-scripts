<?php

/**
 * Step to create mongo instance on CloudFoundry.
 */

namespace Graviton\Deployment\Steps\CloudFoundry;

/**
 * @author   List of contributors <https://github.com/libgraviton/graviton/graphs/contributors>
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
     * @param string $applicationName Name of the CF-application to be checked
     * @param string $serviceName     Name of the CF service to create
     */
    public function __construct($applicationName, $serviceName)
    {
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
            $this->cfCommand(),
            'cs',
            $this->serviceName,
            $_SERVER['SYMFONY__DEPLOYMENT__CF_' . strtoupper($this->serviceName) . '_TYPE'],
            $this->applicationName . '-' . $this->serviceName
        );
    }
}
