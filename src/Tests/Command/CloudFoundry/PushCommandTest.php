<?php
/**
 * Validates command to authorize to a Cloud Foundry.
 */

namespace Graviton\Deployment\Tests\Command\CloudFoundry;

use Graviton\Deployment\Command\CloudFoundry\PushCommand;
use Graviton\Deployment\Configuration;
use Graviton\Deployment\DeployScriptsTestCase;
use Symfony\Component\Config\Definition\Processor;

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
        $this->markTestSkipped('This shall be reactivated, if a system user is available!');

        $this->configYamlExists();
        $application = $this->getSetUpApplication(new PushCommand(self::$configuration));
        $command = $application->find('graviton:deployment:cf:push');
        $inputArgs = array(
            'applicationName' => 'graviton-develop',
            'slice' => 'blue'
        );

        $this->assertContains("Pushing application to", $this->getOutputFromCommand($command, $inputArgs));
    }
}
