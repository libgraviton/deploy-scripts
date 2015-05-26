<?php
/**
 * Base test case defining generally available methods
 */

namespace Graviton\Deployment;

/**
 * @author   List of contributors <https://github.com/libgraviton/graviton/graphs/contributors>
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     http://swisscom.ch
 */
class DeployScriptsTestCase extends \PHPUnit_Framework_TestCase
{
    public function getConfigurationSet()
    {
        $configuration = [];
        $configuration['cf']['command'] = '/usr/bin/cf';
        $configuration['cf']['credentials']['username'] = 'Jon';
        $configuration['cf']['credentials']['password'] = 'mySecret';
        $configuration['cf']['credentials']['org'] = 'ORG';
        $configuration['cf']['credentials']['space'] = 'DEV';
        $configuration['cf']['credentials']['api_url'] = 'API_URL';
        $configuration['cf']['credentials']['domain'] = 'DOMAIN';
        $configuration['cf']['services']['mongodb']['type'] = 'mongotype';

        return $configuration;
    }
}
