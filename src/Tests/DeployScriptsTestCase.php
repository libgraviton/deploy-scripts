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
    public static function getConfigurationSet()
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
        if (!file_exists(__DIR__ . '/../../app/config/config.yml')) {
            $this->markTestSkipped('Configuration file (app/config/config.yml) missing.');
        }
    }

    /**
     * Provides a double of the \Symfony\Component\Config\Definition\Processor
     *
     * @param array $methods List of methods to be stubbed
     *
     * @return \Symfony\Component\Config\Definition\Processor
     */
    public function getConfigurationProcessorDouble(array $methods = array())
    {
        return $this->getMockBuilder('\Symfony\Component\Config\Definition\Processor')
            ->disableOriginalConstructor()
            ->setMethods($methods)
            ->getMock();
    }

    /**
     * Provides a symfony application with the provided command setup
     *
     * @param AbstractCommand $command Instance of a command
     *
     * @return Application
     */
    public function getSetUpApplication(AbstractCommand $command)
    {
        $application = new Application();
        $application->add($command);

        return $application;
    }

    /**
     * Provides the output sent to stderr|stdout of a specific command.
     *
     * @param AbstractCommand $command   Instance of a command
     * @param array           $inputArgs Arguments to be passed tot he command via $input.
     *
     * @return string
     */
    public function getOutputFromCommand(AbstractCommand $command, array $inputArgs = array())
    {
        $commandTester = new CommandTester($command);

        $input = array_merge(array('command' => $command->getName()), $inputArgs);

        // prevent  command from writing to stdout
        ob_start();
        $commandTester->execute($input);

        return ob_get_clean();
    }
}