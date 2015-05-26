<?php
/**
 * Test suite for the delete step.
 */

namespace Graviton\Deployment\Tests\Steps\CloudFoundry;

use Graviton\Deployment\Steps\CloudFoundry\StepDelete;

/**
 * @author   List of contributors <https://github.com/libgraviton/deploy-scripts/graphs/contributors>
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     http://swisscom.ch
 */
class StepDeleteTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Validate getCommand
     *
     * @dataProvider provide
     *
     * @param bool  $force     Flag to force a deletion or not.
     * @param bool  $delMapped Flag to force a deletion of routes mapped by the or not.
     * @param array $flags     Flags representing the two parameters for the cf delete command.
     *
     * @return void
     */
    public function testGetCommand($force, $delMapped, array $flags)
    {
        $configuration['cf']['command'] = '/usr/bin/cf';
        $step = new StepDelete($configuration, 'my_application', 'blue', $force, $delMapped);

        $this->assertEquals(
            array_merge(array('/usr/bin/cf', 'delete', 'my_application-blue'), $flags),
            $step->getCommand()
        );
    }

    /**
     * @return array
     */
    public function provide()
    {
        return array(
            'deletemapped routes' => array(false, true, array('-r')),
            'force delete' => array(true, false, array('-f')),
            'no force delete' => array(false, false, array()),
            'force delete and delete all mapped routes' => array(true, true, array('-f', '-r')),
        );
    }
}
