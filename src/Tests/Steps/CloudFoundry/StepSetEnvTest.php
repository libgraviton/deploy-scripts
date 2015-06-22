<?php
/**
 * Test suite for the setEnv step.
 */

namespace Graviton\Deployment\Tests\Steps\CloudFoundry;

use Graviton\Deployment\DeployScriptsTestCase;
use Graviton\Deployment\Steps\CloudFoundry\StepSetEnv;

/**
 * @author   List of contributors <https://github.com/libgraviton/deploy-scripts/graphs/contributors>
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     http://swisscom.ch
 */
class StepSetEnvTest extends DeployScriptsTestCase
{
    /**
     * Validate getCommand
     *
     * @return void
     */
    public function testGetCommand()
    {
        $configuration = $this->getConfigurationSet();
        $step = new StepSetEnv(
            $configuration,
            'my_personal_application-unstable-blue',
            'SOME_ENV_VAR',
            'SOME_ENV_VAR_VALUE'
        );

        $this->assertEquals(
            array(
                '/usr/bin/cf',
                'set-env',
                'my_personal_application-unstable-blue',
                'SOME_ENV_VAR',
                'SOME_ENV_VAR_VALUE'
            ),
            $step->getCommand()
        );
    }
}
