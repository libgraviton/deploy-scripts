<?php
/**
 * Validates command to close session in a Cloud Foundry.
 */

namespace Graviton\Deployment\Tests\Command\CloudFoundry;

use Graviton\Deployment\Command\CloudFoundry\LogoutCommand;
use Graviton\Deployment\Configuration;
use Graviton\Deployment\DeployScriptsTestCase;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Config\FileLocator;

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
        $locator = new FileLocator(__DIR__ . '/../../Resources/config');
        self::$configuration = new Configuration(new Processor(), $locator);
    }

    /**
     * @return void
     */
    public function testConfigure()
    {
        $cmd = new LogoutCommand(self::$configuration);

        $this->assertAttributeEquals('graviton:deployment:cf:logout', 'name', $cmd);
        $this->assertAttributeEquals('Closes a user session to a CF instance.', 'description', $cmd);
    }

    /**
     * @return void
     */
    public function testExecute()
    {
        $application = $this->getSetUpApplication(new LogoutCommand(self::$configuration));
        $command = $application->find('graviton:deployment:cf:logout');

        $this->assertContains("Logging out...\nOK", $this->getOutputFromCommand($command));
    }
}
