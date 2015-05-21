<?php
/**
 * Test suite for the CheckApp step.
 */

namespace Graviton\Deployment\Tests\Steps;

use Graviton\Deployment\Steps\StepCheckApp;

/**
 * @author   List of contributors <https://github.com/libgraviton/graviton/graphs/contributors>
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     http://swisscom.ch
 */
class StepCheckAppTest extends \PHPUnit_Framework_TestCase
{
    public static function setUpBeforeClass()
    {
        $_ENV['SYMFONY__DEPLOYMENT__CF_COMMAND'] = '/usr/bin/cf';
    }


    public function testGetCommand()
    {
        $step = new StepCheckApp('my_application', 'blue');

        $this->assertEquals('/usr/bin/cf app "my_application-blue"', $step->getCommand());
    }
}
