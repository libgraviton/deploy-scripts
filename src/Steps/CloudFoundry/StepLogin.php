<?php
/**
 * Step to log in to a Cloud Foundry instance.
 */

namespace Graviton\Deployment\Steps\CloudFoundry;

/**
 * @author   List of contributors <https://github.com/libgraviton/deploy-scripts/graphs/contributors>
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     http://swisscom.ch
 */
final class StepLogin extends AbstractStep
{
    /**
     * returns the command
     *
     * @return array
     */
    public function getCommand()
    {
        return array(
            $this->configuration['cf_bin'],
            'login',
            '-u',  $this->configuration['cf_username'],
            '-p' , $this->configuration['cf_password'],
            '-o' , $this->configuration['cf_org'],
            '-s' , $this->configuration['cf_space'],
            '-a' , $this->configuration['cf_api_url']
        );
    }
}
