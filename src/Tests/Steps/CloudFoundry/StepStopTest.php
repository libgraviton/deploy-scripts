<?php
/**
 * Test suite for the stop step.
 */

namespace Graviton\Deployment\Tests\Steps\CloudFoundry;

use Graviton\Deployment\DeployScriptsTestCase;
use Graviton\Deployment\Steps\CloudFoundry\StepStop;

/**
 * @author   List of contributors <https://github.com/libgraviton/deploy-scripts/graphs/contributors>
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     http://swisscom.ch
 */
class StepStopTest extends DeployScriptsTestCase
{
    /**
     * Validate getCommand
     *
     * @return void
     */
    public function testGetCommand()
    {
        $step = new StepStop($this->getConfigurationSet(), 'my_application', 'blue');

        $this->assertEquals(
            array('/usr/bin/cf' , 'stop' , 'my_application-blue'),
            $step->getCommand()
        );
    }
}
