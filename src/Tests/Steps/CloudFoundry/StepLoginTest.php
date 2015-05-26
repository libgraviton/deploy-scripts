<?php
/**
 * Test suite for the Login step.
 */

namespace Graviton\Deployment\Tests\Steps\CloudFoundry;

use Graviton\Deployment\DeployScriptsTestCase;
use Graviton\Deployment\Steps\CloudFoundry\StepLogin;

/**
 * @author   List of contributors <https://github.com/libgraviton/graviton/graphs/contributors>
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     http://swisscom.ch
 */
class StepLoginTest extends DeployScriptsTestCase
{
    /**
     * Validate getCommand
     *
     * @return void
     */
    public function testGetCommand()
    {
        $configuration = $this->getConfigurationSet();
        $step = new StepLogin($configuration);

        $this->assertEquals(
            array(
                '/usr/bin/cf',
                'login',
                '-u',
                'Jon',
                '-p', 'mySecret',
                '-o', 'ORG',
                '-s', 'DEV',
                '-a', 'API_URL',
            ),
            $step->getCommand()
        );
    }
}
