<?php
/**
 * Test suite for the Logout step.
 */

namespace Graviton\Deployment\Tests\Steps;

use Graviton\Deployment\Steps\StepLogout;

/**
 * @author   List of contributors <https://github.com/libgraviton/graviton/graphs/contributors>
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     http://swisscom.ch
 */
class StepLogoutTest extends \PHPUnit_Framework_TestCase
{
    public static function setUpBeforeClass()
    {
        $_SERVER['SYMFONY__DEPLOYMENT__CF_COMMAND'] = '/usr/bin/cf';
    }


    public function testGetCommand()
    {
        $step = new StepLogout();

        $this->assertEquals(
            array('/usr/bin/cf', 'logout'),
            $step->getCommand()
        );
    }
}
