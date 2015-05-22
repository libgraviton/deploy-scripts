<?php
/**
 * Step to log in to a Cloud Foundry instance.
 */

namespace Graviton\Deployment\Steps;

/**
 * @author   List of contributors <https://github.com/libgraviton/graviton/graphs/contributors>
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     http://swisscom.ch
 */
final class StepLogin extends AbstractStep
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
            'login',
            '-u', $_SERVER['SYMFONY__DEPLOYMENT__CF_LOGIN_USERNAME'],
            '-p' , $_SERVER['SYMFONY__DEPLOYMENT__CF_LOGIN_PASSWORD'] ,
            '-o' , $_SERVER['SYMFONY__DEPLOYMENT__CF_ORGANISATION'] ,
            '-s' , $_SERVER['SYMFONY__DEPLOYMENT__CF_SPACE'] ,
            '-a' , $_SERVER['SYMFONY__DEPLOYMENT__CF_API_ENDPOINT'],
        );
    }
}
