<?php
/**
 * Test suite for the push step.
 */

namespace Graviton\Deployment\Tests\Steps\CloudFoundry;

use Graviton\Deployment\Steps\CloudFoundry\StepPush;

/**
 * @author   List of contributors <https://github.com/libgraviton/graviton/graphs/contributors>
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     http://swisscom.ch
 */
class StepPushTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Validate getCommand
     *
     * @return void
     */
    public function testGetCommand()
    {
        $configuration['cf']['command'] = '/usr/bin/cf';
        $step = new StepPush($configuration, 'my_application', 'blue');

        $this->assertEquals(
            array('/usr/bin/cf' , 'push' , 'my_application-blue'),
            $step->getCommand()
        );
    }
}
