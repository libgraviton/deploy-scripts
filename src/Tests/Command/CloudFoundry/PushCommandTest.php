<?php
/**
 * Validates command to authorize to a Cloud Foundry.
 */

namespace Graviton\Deployment\Tests\Command\CloudFoundry;

use Graviton\Deployment\Command\CloudFoundry\PushCommand;
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
class PushCommandTest extends DeployScriptsTestCase
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
        $cmd = new PushCommand(self::$configuration);

        $this->assertAttributeEquals('graviton:deployment:cf:push', 'name', $cmd);
        $this->assertAttributeEquals('Pushes an application to a CF instance.', 'description', $cmd);
    }

    /**
     * @return void
     */
    public function testExecute()
    {
        $this->configYamlExists();
        $application = new Application();
        $application->add(new PushCommand(self::$configuration));

        $command = $application->find('graviton:deployment:cf:push');

        $commandTester = new CommandTester($command);

        // prevent  command from writing to stdout
        ob_start();
        $commandTester->execute(
            array(
                'command' => $command->getName(),
                'name' => 'my_application',
                'slice' => 'blue'
            )
        );
        $output = ob_get_clean();

        $this->assertContains("Pushing application to", $output);
    }
}
