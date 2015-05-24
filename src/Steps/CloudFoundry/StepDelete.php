<?php
/**
 * Step to deletes an application from a Cloud Foundry instance.
 */

namespace Graviton\Deployment\Steps\CloudFoundry;

/**
 * @author   List of contributors <https://github.com/libgraviton/graviton/graphs/contributors>
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     http://swisscom.ch
 */
final class StepDelete extends AbstractStep
{
    /** @var string deployment location in blue/green deployment. */
    private $slice;

    /** @var string Name of the CF-application to be deleted */
    private $applicationName;

    /** @var bool Flag to force a deletion or not. */
    private $force;

    /**
     *
     * @param array  $configuration      Current application configuration.
     * @param string $applicationName    Name of the CF-application to be deleted.
     * @param string $slice              deployment location in blue/green deployment.
     * @param bool   $force              Flag to force a deletion or not.
     * @param bool   $deleteMappedRoutes Flag to delete mapped routes as well or not.
     *
     * @link http://martinfowler.com/bliki/BlueGreenDeployment.html
     */
    public function __construct(
        array $configuration,
        $applicationName,
        $slice,
        $force = false,
        $deleteMappedRoutes = false
    ) {
        parent::__construct($configuration);

        $this->slice = $slice;
        $this->applicationName = $applicationName;
        $this->force = ($force !== false);
        $this->delMappedRoutes = ($deleteMappedRoutes !== false);
    }

    /**
     * returns the command
     *
     * @return array
     */
    public function getCommand()
    {
        $command = array(
            $this->configuration['cf']['command'],
            'delete',
            $this->applicationName . '-' . $this->slice
        );

        if ($this->force === true) {
            $command[] = '-f';
        }

        if ($this->delMappedRoutes === true) {
            $command[] = '-r';
        }

        return $command;
    }
}
