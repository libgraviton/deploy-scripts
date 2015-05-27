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
        $configuration = new Configuration(new Processor());

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

    public function executeCommandProvider()
    {
        $configuration = new Configuration(new Processor());

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
