<?php
/**
 * Step to set a routing in a Cloud Foundry instance.
 */

namespace Graviton\Deployment\Tests\Steps\CloudFoundry;

use Graviton\Deployment\DeployScriptsTestCase;
use Graviton\Deployment\Steps\CloudFoundry\StepCreateService;
use Graviton\Deployment\Steps\CloudFoundry\StepRoute;

/**
 * @author   List of contributors <https://github.com/libgraviton/deploy-scripts/graphs/contributors>
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     http://swisscom.ch
 */
class CommonStepTest extends DeployScriptsTestCase
{
    /**
     * Validate getCommand
     *
     * @dataProvider stepCredentialsProvider
     *
     * @param \Graviton\Deployment\Steps\CloudFoundry\AbstractStep $step     Step to be tested
     * @param array                                                $expected Expected data set to be passed
     *                                                                       to a ProcessBuilder.
     *
     * @return void
     */
    public function testGetCommand($step, $expected)
    {
        $this->assertEquals($expected, $step->getCommand());
    }

    /**
     * @return array
     */
    public function stepCredentialsProvider()
    {
        $configuration = DeployScriptsTestCase::getConfigurationSet();

        return array(
            'step route' => array(
                new StepRoute($configuration, 'target', 'map'),
                array('/usr/bin/cf', 'map-route', 'target', 'DOMAIN', '-n', 'API_URL')
            ),
            'step ' => array(
                new StepCreateService($configuration, 'my_application', 'mongodb'),
                array('/usr/bin/cf', 'cs', 'mongodb', 'mongotype', 'my_application-mongodb')
            ),
        );
    }
}
