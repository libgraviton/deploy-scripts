<?php

/**
 * Test suite for the create mongo step.
 */

namespace Graviton\Deployment\Tests\Steps\CloudFoundry;

use Graviton\Deployment\Steps\CloudFoundry\StepCreateService;

/**
 * @author   List of contributors <https://github.com/libgraviton/graviton/graphs/contributors>
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     http://swisscom.ch
 */
class StepCreateMongoTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Will be called before the SUT is instantiated
     *
     * @return void
     */
    public static function setUpBeforeClass()
    {
        $_SERVER['SYMFONY__DEPLOYMENT__CF_COMMAND'] = '/usr/bin/cf';
        $_SERVER['SYMFONY__DEPLOYMENT__CF_MONGODB_TYPE'] = 'mongotype';
    }

    /**
     * Validate getCommand
     *
     * @return void
     */
    public function testGetCommand()
    {
        $step = new StepCreateService('my_application', 'mongodb');

        $this->assertEquals(
            array('/usr/bin/cf', 'cs', 'mongodb', 'mongotype', 'my_application-mongodb'),
            $step->getCommand()
        );
    }
}
