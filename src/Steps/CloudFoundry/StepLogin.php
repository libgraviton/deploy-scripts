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
            $this->configuration['cf']['command'],
            'login',
            '-u',  $this->configuration['cf']['credentials']['username'],
            '-p' , $this->configuration['cf']['credentials']['password'],
            '-o' , $this->configuration['cf']['credentials']['org'],
            '-s' , $this->configuration['cf']['credentials']['space'],
            '-a' , $this->configuration['cf']['api_url']
        );
    }
}
