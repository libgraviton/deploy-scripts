<?php
/**
 * Step to set a routing in a Cloud Foundry instance.
 */

namespace Graviton\Deployment\Tests\Steps\CloudFoundry;

use Graviton\Deployment\DeployScriptsTestCase;
use Graviton\Deployment\Steps\CloudFoundry\StepRoute;

/**
 * @author   List of contributors <https://github.com/libgraviton/deploy-scripts/graphs/contributors>
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     http://swisscom.ch
 */
class StepRouteTest extends DeployScriptsTestCase
{
    /**
     * Validate getCommand
     *
     * @return void
     */
    public function testGetCommand()
    {
        $configuration = $this->getConfigurationSet();
        $step = new StepRoute($configuration, 'target', 'map');

        $this->assertEquals(
            array(
                '/usr/bin/cf',
                'map-route',
                'target',
                'DOMAIN',
                '-n',
                'API_URL',
            ),
            $step->getCommand()
        );
    }
}
