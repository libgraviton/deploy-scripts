<?php
/**
 * Test suite for the CheckApp step.
 */

namespace Graviton\Deployment\Tests\Steps;

use Graviton\Deployment\Steps\StepApp;

/**
 * @author   List of contributors <https://github.com/libgraviton/graviton/graphs/contributors>
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     http://swisscom.ch
 */
class StepAppTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Will be called before the SUT is instantiated
     *
     * @return void
     */
    public static function setUpBeforeClass()
    {
        $_SERVER['SYMFONY__DEPLOYMENT__CF_COMMAND'] = '/usr/bin/cf';
    }

    /**
     * Validate getCommand
     *
     * @return void
     */
    public function testGetCommand()
    {
        $step = new StepApp('my_application', 'blue');

        $this->assertEquals(
            array('/usr/bin/cf' , 'app' , 'my_application-blue'),
            $step->getCommand()
        );
    }
}
