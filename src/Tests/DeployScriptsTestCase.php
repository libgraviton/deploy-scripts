<?php
/**
 * Base test case defining generally available methods
 */

namespace Graviton\Deployment;

use Graviton\Deployment\Command\AbstractCommand;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * @author   List of contributors <https://github.com/libgraviton/deploy-scripts/graphs/contributors>
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     http://swisscom.ch
 */
class DeployScriptsTestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * Provides a predefined configuration set.
     *
     * @return array
     */
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
        $configuration['cf']['services']['mongodb'] = 'mongotype';

        return $configuration;
    }

    /**
     * Determines, if app/config/config.yml exists.
     *
     * @return void
     */
    public function configYamlExists()
    {
        if (!file_exists(__DIR__ .'/../../app/config/config.yml')) {
            $this->markTestSkipped('Configuration file (app/config/config.yml) missing.');
        }
    }

    /**
     * Provides a double of the \Symfony\Component\Config\Definition\Processor
     *
     * @param array $methods List of methods to be stubbed
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|\Symfony\Component\Config\Definition\Processor
     */
    public function getConfigurationProcessorDouble(array $methods = array())
    {
        return $this->getMockBuilder('\Symfony\Component\Config\Definition\Processor')
            ->disableOriginalConstructor()
            ->setMethods($methods)
            ->getMock();
    }


    /**
     * @param AbstractCommand $command
     *
     * @return Application
     */
    public function getSetUpApplication(AbstractCommand $command)
    {
        $this->configYamlExists();
        $application = new Application();
        $application->add($command);

        return $application;
    }

    /**
     * @param AbstractCommand $command
     *
     * @return string
     */
    public function getOutputFromCommand(AbstractCommand $command)
    {
        $commandTester = new CommandTester($command);

        // prevent  command from writing to stdout
        ob_start();
        $commandTester->execute(array('command' => $command->getName()));

        return ob_get_clean();
    }
}
