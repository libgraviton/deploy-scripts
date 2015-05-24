<?php
/**
 * Test suite for the Logout step.
 */

namespace Graviton\Deployment\Tests\Steps\CloudFoundry;

use Graviton\Deployment\Steps\CloudFoundry\StepLogout;

/**
 * @author   List of contributors <https://github.com/libgraviton/graviton/graphs/contributors>
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     http://swisscom.ch
 */
class StepLogoutTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Validate getCommand
     *
     * @return void
     */
    public function testGetCommand()
    {
        $configuration = array();
        $configuration['cf']['command'] = '/usr/bin/cf';
        $step = new StepLogout($configuration);

        $this->assertEquals(
            array('/usr/bin/cf', 'logout'),
            $step->getCommand()
        );
    }
}
