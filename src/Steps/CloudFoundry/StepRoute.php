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
     *
     * @param array  $configuration Current application configuration.
     * @param string $targetName    Name of the target (deploy or old)
     * @param string $map           Art of the map (map or unmap)
     */
    public function __construct(array $configuration, $targetName, $map)
    {
        parent::__construct($configuration);

        $this->target = $targetName;
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
            $this->configuration['cf']['command'],
            $this->map . '-route',
            $this->target,
            $this->configuration['cf']['credentials']['domain'],
            '-n',
            $this->configuration['cf']['credentials']['api_url'],

        );
    }
}
