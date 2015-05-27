<?php
/**
 * Validates command to authorize to a Cloud Foundry.
 */

namespace Graviton\Deployment\Tests\Command\CloudFoundry;

use Graviton\Deployment\Command\CloudFoundry\CreateServiceCommand;
use Graviton\Deployment\Command\CloudFoundry\PushCommand;
use Graviton\Deployment\Configuration;
use Graviton\Deployment\DeployScriptsTestCase;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Config\FileLocator;

/**
 * @author   List of contributors <https://github.com/libgraviton/deploy-scripts/graphs/contributors>
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     http://swisscom.ch
 */
class CommandWithArgumentsTest extends DeployScriptsTestCase
{
    /**
     * @dataProvider configureCommandProvider
     *
     *
     * @param \Graviton\Deployment\Command\AbstractCommand $command            Command to be tested.
     * @param string                                       $commandName        Name of the command.
     * @param string                                       $commandDescription Input arguments for the command.
     *
     *
     * @return void
     */
    public function testConfigure($command, $commandName, $commandDescription)
    {
        $this->configYamlExists();

        $this->assertAttributeEquals($commandName, 'name', $command);
        $this->assertAttributeEquals($commandDescription, 'description', $command);
    }

    /**
     * @return array
     */
    public function configureCommandProvider()
    {
        $locator = new FileLocator(__DIR__ . '/../../Resources/config');
        $configuration = new Configuration(new Processor(), $locator);

        return array(
            'push command' => array(
                new PushCommand($configuration),
                'graviton:deployment:cf:push',
                'Pushes an application to a CF instance.'
            ),
            'createService command' => array(
                new CreateServiceCommand($configuration),
                'graviton:deployment:cf:createService',
                'Create a Cloud Foundry service.'
            ),
        );
    }

    /**
     * @dataProvider executeCommandProvider
     *
     * @param \Graviton\Deployment\Command\AbstractCommand $commandObj  Command to be tested.
     * @param string                                       $commandName Name of the command.
     * @param array                                        $inputArgs   Input arguments for the command.
     *
     * @return void
     */
    public function testExecute($commandObj, $commandName, array $inputArgs)
    {
        $this->markTestSkipped('This shall be reactivated, if a system user is available!');

        $this->configYamlExists();
        $application = $this->getSetUpApplication($commandObj);
        $command = $application->find($commandName);

        $this->assertContains("Pushing application to", $this->getOutputFromCommand($command, $inputArgs));
    }

    /**
     * @return array
     */
    public function executeCommandProvider()
    {
        $locator = new FileLocator(__DIR__ . '/../../Resources/config');
        $configuration = new Configuration(new Processor(), $locator);

        return array(
            'push command' => array(
                new PushCommand($configuration),
                'graviton:deployment:cf:push',
                array(
                    'applicationName' => 'graviton-develop',
                    'slice' => 'blue'
                )
            ),
            'createService command' => array(
                new CreateServiceCommand($configuration),
                'graviton:deployment:cf:createService',
                array(
                    'applicationName' => 'graviton-develop',
                    'slice' => 'blue'
                )
            ),
        );
    }
}
