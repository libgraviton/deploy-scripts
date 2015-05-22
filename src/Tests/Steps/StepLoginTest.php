<?php
/**
 * Test suite for the Login step.
 */

namespace Graviton\Deployment\Tests\Steps;

use Graviton\Deployment\Steps\StepLogin;

/**
 * @author   List of contributors <https://github.com/libgraviton/graviton/graphs/contributors>
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     http://swisscom.ch
 */
class StepLoginTest extends \PHPUnit_Framework_TestCase
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
        $_SERVER['SYMFONY__DEPLOYMENT__CF_ORGANISATION'] = 'ORG';
        $_SERVER['SYMFONY__DEPLOYMENT__CF_SPACE'] = 'SPACE';
        $_SERVER['SYMFONY__DEPLOYMENT__CF_API_ENDPOINT'] = 'API_URL';
    }

    /**
     * Validate getCommand
     *
     * @return void
     */
    public function testGetCommand()
    {
        $step = new StepLogin();

        $this->assertEquals(
            array(
                '/usr/bin/cf',
                'login',
                '-u',
                'Jon',
                '-p', 'mySecret',
                '-o', 'ORG',
                '-s', 'SPACE',
                '-a', 'API_URL',
            ),
            $step->getCommand()
        );
    }
}
