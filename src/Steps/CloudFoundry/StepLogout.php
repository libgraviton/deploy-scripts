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
final class StepLogout extends AbstractStep
{

    /**
     * returns the command
     *
     * @return string
     */
    public function getCommand()
    {
        return array(
            $this->cfCommand(),
            'logout'
        );
    }
}
