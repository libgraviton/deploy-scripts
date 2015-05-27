<?php
/**
 * Validates command to create a new service in a Cloud Foundry application.
 */

namespace Graviton\Deployment\Tests\Command\CloudFoundry;

use Graviton\Deployment\Command\CloudFoundry\CreateServiceCommand;
use Graviton\Deployment\Configuration;
use Graviton\Deployment\DeployScriptsTestCase;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * @author   List of contributors <https://github.com/libgraviton/deploy-scripts/graphs/contributors>
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     http://swisscom.ch
 */
class CreateServiceCommandTest extends DeployScriptsTestCase
{
    /** @var \Graviton\Deployment\Configuration */
    private static $configuration;

    /**
     * @return void
     */
    public static function setUpBeforeClass()
    {
        self::$configuration = new Configuration(new Processor());
    }

    /**
     * @return void
     */
    public function testConfigure()
    {
        $this->configYamlExists();
        $cmd = new CreateServiceCommand(self::$configuration);

        $this->assertAttributeEquals('graviton:deployment:cf:createService', 'name', $cmd);
        $this->assertAttributeEquals('Create a Cloud Foundry service.', 'description', $cmd);
    }

    /**
     * @return void
     */
    public function testExecute()
    {
        $this->markTestSkipped('This shall be reactivated, if a system user is available!');

        $this->configYamlExists();
        $application = new Application();
        $application->add(new CreateServiceCommand(self::$configuration));
        $command = $application->find('graviton:deployment:cf:createService');
        $inputArgs = array(
                'applicationname' => 'graviton-develop',
                'servicename' => 'mongodb'
        );

        $this->assertContains("Creating mongodb service ...", $this->getOutputFromCommand($command, $inputArgs));
    }
}
