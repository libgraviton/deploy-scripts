<?php
/**
 * Validates command to authorize to a Cloud Foundry.
 */

namespace Graviton\Deployment\Tests\Command\CloudFoundry;

use Graviton\Deployment\Command\CloudFoundry\LogoutCommand;
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
class LogoutCommandTest extends DeployScriptsTestCase
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
        $cmd = new LogoutCommand(self::$configuration);

        $this->assertAttributeEquals('graviton:deployment:cf:logout', 'name', $cmd);
        $this->assertAttributeEquals('Closes a user session to a CF instance.', 'description', $cmd);
    }

    /**
     * @return void
     */
    public function testExecute()
    {
        $this->configYamlExists();
        $application = new Application();
        $application->add(new LogoutCommand(self::$configuration));

        $command = $application->find('graviton:deployment:cf:logout');

        $commandTester = new CommandTester($command);

        // prevent  command from writing to stdout
        ob_start();
        $commandTester->execute(array('command' => $command->getName()));
        $output = ob_get_clean();

        $this->assertContains("Logging out...\nOK", $output);
    }
}
