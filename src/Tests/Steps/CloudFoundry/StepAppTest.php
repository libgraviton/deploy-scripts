<?php
/**
 * Test suite for the CheckApp step.
 */

namespace Graviton\Deployment\Tests\Steps\CloudFoundry;

use Graviton\Deployment\Steps\CloudFoundry\StepApp;

/**
 * @author   List of contributors <https://github.com/libgraviton/graviton/graphs/contributors>
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     http://swisscom.ch
 */
class StepAppTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Validate getCommand
     *
     * @return void
     */
    public function testGetCommand()
    {
        $configuration['cf']['command'] = '/usr/bin/cf';
        $step = new StepApp($configuration, 'my_application', 'blue');

        $this->assertEquals(
            array('/usr/bin/cf' , 'app' , 'my_application-blue'),
            $step->getCommand()
        );
    }
}
