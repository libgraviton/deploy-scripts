<?php
/**
 * Test suite for the Auth step.
 */

namespace Graviton\Deployment\Tests\Steps;

use Graviton\Deployment\Steps\StepAuth;

/**
 * @author   List of contributors <https://github.com/libgraviton/graviton/graphs/contributors>
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     http://swisscom.ch
 */
class StepAuthTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Will be called before the SUT is instantiated
     *
     * @return void
     */
    public static function setUpBeforeClass()
    {
        $_SERVER['SYMFONY__DEPLOYMENT__CF_COMMAND'] = '/usr/bin/cf';
        $_SERVER['SYMFONY__DEPLOYMENT__CF_LOGIN_USERNAME'] = 'Jon';
        $_SERVER['SYMFONY__DEPLOYMENT__CF_LOGIN_PASSWORD'] = 'mySecret';
    }

    /**
     * Validate getCommand
     *
     * @return void
     */
    public function testGetCommand()
    {
        $step = new StepAuth();

        $this->assertEquals(
            array('/usr/bin/cf' , 'auth' , 'Jon', 'mySecret'),
            $step->getCommand()
        );
    }
}
