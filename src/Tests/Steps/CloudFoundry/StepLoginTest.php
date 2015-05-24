<?php
/**
 * Test suite for the Login step.
 */

namespace Graviton\Deployment\Tests\Steps\CloudFoundry;

use Graviton\Deployment\Steps\CloudFoundry\StepLogin;

/**
 * @author   List of contributors <https://github.com/libgraviton/graviton/graphs/contributors>
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     http://swisscom.ch
 */
class StepLoginTest extends \PHPUnit_Framework_TestCase
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
        $configuration['cf']['credentials']['username'] = 'Jon';
        $configuration['cf']['credentials']['password'] = 'mySecret';
        $configuration['cf']['credentials']['org'] = 'ORG';
        $configuration['cf']['credentials']['space'] = 'DEV';
        $configuration['cf']['credentials']['api_url'] = 'API_URL';

        $step = new StepLogin($configuration);

        $this->assertEquals(
            array(
                '/usr/bin/cf',
                'login',
                '-u',
                'Jon',
                '-p', 'mySecret',
                '-o', 'ORG',
                '-s', 'DEV',
                '-a', 'API_URL',
            ),
            $step->getCommand()
        );
    }
}
