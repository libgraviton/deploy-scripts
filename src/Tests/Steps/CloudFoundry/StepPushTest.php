<?php
/**
 * Test suite for the push step.
 */

namespace Graviton\Deployment\Tests\Steps\CloudFoundry;

use Graviton\Deployment\DeployScriptsTestCase;
use Graviton\Deployment\Steps\CloudFoundry\StepPush;

/**
 * @author   List of contributors <https://github.com/libgraviton/deploy-scripts/graphs/contributors>
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     http://swisscom.ch
 */
class StepPushTest extends DeployScriptsTestCase
{
    /**
     * Validate getCommand
     *
     * @return void
     */
    public function testGetCommand()
    {
        $step = new StepPush($this->getConfigurationSet(), 'my_application', 'blue');

        $this->assertEquals(
            array('/usr/bin/cf' , 'push' , 'my_application-blue'),
            $step->getCommand()
        );
    }
}
