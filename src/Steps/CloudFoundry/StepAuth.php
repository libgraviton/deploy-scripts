<?php
/**
 * Step to log in to a Cloud Foundry instance.
 */

namespace Graviton\Deployment\Steps\CloudFoundry;

/**
 * @author   List of contributors <https://github.com/libgraviton/graviton/graphs/contributors>
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     http://swisscom.ch
 */
final class StepAuth extends AbstractStep
{

    /**
     * returns the command
     *
     * @return array
     */
    public function getCommand()
    {
        return array(
            $this->cfCommand(),
            'auth',
            $_SERVER['SYMFONY__DEPLOYMENT__CF_LOGIN_USERNAME'],
            $_SERVER['SYMFONY__DEPLOYMENT__CF_LOGIN_PASSWORD'],
        );
    }
}
