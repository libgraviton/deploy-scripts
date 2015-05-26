<?php

/**
 * Test suite for the create mongo step.
 */

namespace Graviton\Deployment\Tests\Steps\CloudFoundry;

use Graviton\Deployment\DeployScriptsTestCase;
use Graviton\Deployment\Steps\CloudFoundry\StepCreateService;

/**
 * @author   List of contributors <https://github.com/libgraviton/graviton/graphs/contributors>
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     http://swisscom.ch
 */
class StepCreateServiceTest extends DeployScriptsTestCase
{

    /**
     * Validate getCommand
     *
     * @return void
     */
    public function testGetCommand()
    {
        $configuration = $this->getConfigurationSet();
        $step = new StepCreateService($configuration, 'my_application', 'mongodb');

        $this->assertEquals(
            array('/usr/bin/cf', 'cs', 'mongodb', 'mongotype', 'my_application-mongodb'),
            $step->getCommand()
        );
    }
}
