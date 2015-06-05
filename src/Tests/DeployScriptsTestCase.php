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
        $configuration['cf_bin'] = '/usr/bin/cf';
        $configuration['cf_username'] = 'Jon';
        $configuration['cf_password'] = 'mySecret';
        $configuration['cf_org'] = 'ORG';
        $configuration['cf_space'] = 'DEV';
        $configuration['cf_api_url'] = 'API_URL';
        $configuration['cf_domain'] = 'DOMAIN';
        $configuration['cf_services']['mongodb'] = 'mongotype';

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
     * @return \PHPUnit_Framework_MockObject_MockObject
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
        ob_end_clean();

        return $commandTester->getDisplay();
    }

    /**
     * suppress output to stdout while test run.
     *
     * @return void
     */
    public function suppressOutput()
    {
        $callback = function () {
            // intentionally left blank.
        };
        $this->setOutputCallback($callback);
    }
}
