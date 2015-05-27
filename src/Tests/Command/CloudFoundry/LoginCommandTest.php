<?php
/**
 * Validates command to authorize to a Cloud Foundry.
 */

namespace Graviton\Deployment\Tests\Command\CloudFoundry;

use Graviton\Deployment\Command\CloudFoundry\LoginCommand;
use Graviton\Deployment\Configuration;
use Graviton\Deployment\DeployScriptsTestCase;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Config\FileLocator;

/**
 * @author   List of contributors <https://github.com/libgraviton/deploy-scripts/graphs/contributors>
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     http://swisscom.ch
 */
class LoginCommandTest extends DeployScriptsTestCase
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
        $cmd = new LoginCommand(self::$configuration);

        $this->assertAttributeEquals('graviton:deployment:cf:login', 'name', $cmd);
        $this->assertAttributeEquals('Authorises a user to a CF instance.', 'description', $cmd);
    }

    /**
     * @return void
     */
    public function testExecute()
    {
        $this->configYamlExists();
        $application = $this->getSetUpApplication(new LoginCommand(self::$configuration));
        $command = $application->find('graviton:deployment:cf:login');

        $this->assertContains("Authenticating...\nOK", $this->getOutputFromCommand($command));
    }
}
