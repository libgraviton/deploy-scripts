<?php
/**
 * Validates command to check vitality of an application in a Cloud Foundry.
 */

namespace Graviton\Deployment\Tests\Command\CloudFoundry;

use Graviton\Deployment\Command\CloudFoundry\CheckApplicationCommand;
use Graviton\Deployment\Configuration;
use Graviton\Deployment\DeployScriptsTestCase;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Config\FileLocator;

/**
 * @author   List of contributors <https://github.com/libgraviton/deploy-scripts/graphs/contributors>
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     http://swisscom.ch
 */
class CheckApplicationCommandTest extends DeployScriptsTestCase
{
    /** @var \Graviton\Deployment\Configuration */
    private static $configuration;

    /**
     * @return void
     */
    public static function setUpBeforeClass()
    {
        $locator = new FileLocator(__DIR__ . '/../../Resources/config');
        self::$configuration = new Configuration(new Processor(), $locator);
    }

    /**
     * @return void
     */
    public function testConfigure()
    {
        $this->configYamlExists();
        $cmd = new CheckApplicationCommand(self::$configuration);

        $this->assertAttributeEquals('graviton:deployment:cf:checkApplication', 'name', $cmd);
        $this->assertAttributeEquals('Determines, if a special CF application is alive.', 'description', $cmd);
    }

    /**
     * @return void
     */
    public function testExecute()
    {
        $this->configYamlExists();
        $application = $this->getSetUpApplication(new CheckApplicationCommand(self::$configuration));
        $command = $application->find('graviton:deployment:cf:checkApplication');

        $inputArgs = array(
            'applicationName' => 'graviton-develop',
            'slice' => 'blue'
        );
        $output = $this->getOutputFromCommand($command, $inputArgs);

        $this->assertContains("Authenticating...\nOK", $output);
        $this->assertContains("Showing health and status for app graviton-develop-blue", $output);
        $this->assertContains("Logging out...\nOK", $output);
    }
}
