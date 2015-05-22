<?php
/**
 * Step to log off a Cloud Foundry instance.
 */

namespace Graviton\Deployment\Steps\CloudFoundry;

/**
 * @author   List of contributors <https://github.com/libgraviton/graviton/graphs/contributors>
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     http://swisscom.ch
 */
final class StepRoute extends AbstractStep
{
    /** @var string  */
    private $target;

    /** @var string  */
    private $map;

    /**
     * @param string $targetName Name of the target (deploy or old)
     * @param string $map        Art of the map (map or unmap)
     */
    public function __construct($targetName, $map)
    {
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
            $this->cfCommand(),
            $this->map . '-route',
            $this->target,
            $_SERVER['SYMFONY__DEPLOYMENT__CF_DOMAIN'],
            '-n',
            $_SERVER['SYMFONY__DEPLOYMENT__APP_ROUTE']

        );
    }
}
