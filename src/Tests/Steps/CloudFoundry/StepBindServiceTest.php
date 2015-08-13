<?php
/**
 * Test suite for the bind service step.
 */

namespace Graviton\Deployment\Tests\Steps\CloudFoundry;

use Graviton\Deployment\DeployScriptsTestCase;
use Graviton\Deployment\Steps\CloudFoundry\StepBindService;

/**
 * @author   List of contributors <https://github.com/libgraviton/deploy-scripts/graphs/contributors>
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     http://swisscom.ch
 */
class StepBindServiceTest extends DeployScriptsTestCase
{
    /**
     * Validate getCommand
     *
     * @return void
     */
    public function testGetCommand()
    {
        $configuration = $this->getConfigurationSet();
        $step = new StepBindService(
            $configuration,
            'my_personal_application-unstable',
            'run-1234',
            'mongodb'

        );

        $this->assertEquals(
            array(
                '/usr/bin/cf',
                'bind-service',
                'my_personal_application-unstable-run-1234',
                'my_personal_application-unstable-mongodb',
            ),
            $step->getCommand()
        );
    }
}
