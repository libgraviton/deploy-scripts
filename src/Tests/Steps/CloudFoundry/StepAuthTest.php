<?php
/**
 * Test suite for the Auth step.
 */

namespace Graviton\Deployment\Tests\Steps\CloudFoundry;

use Graviton\Deployment\Steps\CloudFoundry\StepAuth;

/**
 * @author   List of contributors <https://github.com/libgraviton/graviton/graphs/contributors>
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     http://swisscom.ch
 */
class StepAuthTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Validate getCommand
     *
     * @return void
     */
    public function testGetCommand()
    {
        $configuration['cf']['command'] = '/usr/bin/cf';
        $configuration['cf']['credentials']['username'] = 'Jon';
        $configuration['cf']['credentials']['password'] = 'mySecret';

        $step = new StepAuth($configuration);

        $this->assertEquals(
            array('/usr/bin/cf' , 'auth' , 'Jon', 'mySecret'),
            $step->getCommand()
        );
    }
}
