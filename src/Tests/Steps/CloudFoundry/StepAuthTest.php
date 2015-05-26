<?php
/**
 * Test suite for the Auth step.
 */

namespace Graviton\Deployment\Tests\Steps\CloudFoundry;

use Graviton\Deployment\DeployScriptsTestCase;
use Graviton\Deployment\Steps\CloudFoundry\StepAuth;

/**
 * @author   List of contributors <https://github.com/libgraviton/graviton/graphs/contributors>
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     http://swisscom.ch
 */
class StepAuthTest extends DeployScriptsTestCase
{
    /**
     * Validate getCommand
     *
     * @return void
     */
    public function testGetCommand()
    {
        $configuration = $this->getConfigurationSet();
        $step = new StepAuth($configuration);

        $this->assertEquals(
            array('/usr/bin/cf' , 'auth' , 'Jon', 'mySecret'),
            $step->getCommand()
        );
    }
}
