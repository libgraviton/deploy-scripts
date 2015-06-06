<?php
/**
 * Step to set a routing in a Cloud Foundry instance.
 */

namespace Graviton\Deployment\Tests\Steps\CloudFoundry;

use Graviton\Deployment\DeployScriptsTestCase;

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
     * @param string $cmd      Name of the step.
     * @param array  $args     List of argumetns to be used to initialize the step.
     * @param array  $expected Expected data set to be passed
     *                         to a ProcessBuilder.
     *
     * @return void
     */
    public function testGetCommand($cmd, array $args, $expected)
    {
        $reflector = new \ReflectionClass($cmd);
        $step = $reflector->newInstanceArgs($args);
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
                '\Graviton\Deployment\Steps\CloudFoundry\StepRoute',
                array($configuration, 'APP_NAME', 'blue', 'target', 'map'),
                array('/usr/bin/cf', 'map-route', 'target', 'DOMAIN', '-n', 'APP_NAME')
            ),
            'step ' => array(
                '\Graviton\Deployment\Steps\CloudFoundry\StepCreateService',
                array($configuration, 'my_application', 'mongodb'),
                array('/usr/bin/cf', 'cs', 'mongodb', 'mongotype', 'my_application-mongodb')
            ),
        );
    }
}
