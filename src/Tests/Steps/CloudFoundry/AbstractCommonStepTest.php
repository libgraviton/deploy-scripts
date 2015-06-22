<?php
/**
 * Test suite for the CheckApp step.
 */

namespace Graviton\Deployment\Tests\Steps\CloudFoundry;

use Graviton\Deployment\DeployScriptsTestCase;
use Graviton\Deployment\Steps\CloudFoundry\StepApp;

/**
 * @author   List of contributors <https://github.com/libgraviton/deploy-scripts/graphs/contributors>
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     http://swisscom.ch
 */
class AbstractCommonStepTest extends DeployScriptsTestCase
{
    /**
     * Validate getCommand
     *
     * @return void
     */
    public function testGetCommand()
    {
        $configuration = $this->getConfigurationSet();

        $step = $this->getMockBuilder('\Graviton\Deployment\Steps\CloudFoundry\AbstractCommonStep')
            ->setConstructorArgs(array($configuration, 'my_application', 'blue'))
            ->getMockForAbstractClass();

        $this->assertEquals(
            array('/usr/bin/cf' , null , 'my_application-blue'),
            $step->getCommand()
        );
    }
}
