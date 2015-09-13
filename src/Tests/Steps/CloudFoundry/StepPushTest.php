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
        $configuration = $this->getConfigurationSet();
        $step = new StepPush(
            $configuration,
            'my_personal_application-unstable',
            'blue',
            true,
            true,
            "php app/console doctrine:mongodb:fixtures:load unstable"
        );

        $this->assertEquals(
            array(
                '/usr/bin/cf',
                'push',
                'my_personal_application-unstable-blue',
                '--no-route',
                '-c',
                'php app/console doctrine:mongodb:fixtures:load unstable'
            ),
            $step->getCommand()
        );
    }

    /**
     * Validate getCommand with flag --no-start
     *
     * @return void
     */
    public function testGetCommandStartFalse()
    {
        $configuration = $this->getConfigurationSet();
        $step = new StepPush(
            $configuration,
            'my_personal_application-unstable',
            'blue',
            false,
            true,
            "php app/console doctrine:mongodb:fixtures:load unstable"
        );

        $this->assertEquals(
            array(
                '/usr/bin/cf',
                'push',
                'my_personal_application-unstable-blue',
                '--no-start',
                '--no-route',
                '-c',
                'php app/console doctrine:mongodb:fixtures:load unstable'
            ),
            $step->getCommand()
        );
    }
}
