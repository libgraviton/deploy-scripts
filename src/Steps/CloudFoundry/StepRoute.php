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
final class StepRoute extends AbstractStep
{
    /** @var string */
    private $target;

    /** @var string */
    private $map;

    /**
     * @var string
     */
    private $applicationName;

    /** @var string  */
    private $hostname;

    /**
     *
     * @param array  $configuration   Current application configuration.
     * @param string $applicationName Name of the CF-application to be checked
     * @param string $targetName      Name of the target (deploy or old)
     * @param string $subdomain       Used a the subdomain for the application route.
     * @param string $map             Art of the map (map or unmap)
     */
    public function __construct(array $configuration, $applicationName, $targetName, $subdomain, $map)
    {
        parent::__construct($configuration);

        $this->applicationName = $applicationName;
        $this->target = $targetName;
        $this->hostname = $subdomain;
        $this->map = $map;
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
            $this->map . '-route',
            $this->target,
            $this->configuration['cf_domain'],
            '-n',
            $this->hostname,
        );
    }
}
