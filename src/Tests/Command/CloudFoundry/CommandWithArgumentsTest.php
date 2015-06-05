<?php
/**
 * Validates command to authorize to a Cloud Foundry.
 */

namespace Graviton\Deployment\Tests\Command\CloudFoundry;

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
     * @param string $cmd                Command to be tested.
     * @param array  $commandArgs        List of argumenst to instantiate the command.
     * @param string $commandName        Name of the command.
     * @param string $commandDescription Input arguments for the command.
     *
     *
     * @return void
     */
    public function testConfigure($cmd, array $commandArgs, $commandName, $commandDescription)
    {
        $command = new $cmd($commandArgs[0]);

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
            'check application command' => array(
                '\Graviton\Deployment\Command\CloudFoundry\CheckApplicationCommand',
                array($configuration),
                'graviton:deployment:cf:checkApplication',
                'Determines, if a special CF application is alive.'
            ),
            'deploy command' => array(
                '\Graviton\Deployment\Command\CloudFoundry\DeployCommand',
                array($configuration),
                'graviton:deployment:cf:deploy',
                'Deploys an application to a CF instance.'
            ),
        );
    }

    /**
     * @dataProvider executeCommandProvider
     *
     * @param string $cmd         Command to be tested.
     * @param array  $commandArgs List of argumenst to instantiate the command.
     * @param string $commandName Name of the command.
     * @param array  $inputArgs   Input arguments for the command.
     * @param string $expected    Console output.
     *
     * @return void
     */
    public function testExecute($cmd, array $commandArgs, $commandName, array $inputArgs, $expected)
    {
        $this->markTestSkipped('This shall be reactivated, if a system user is available!');

        $this->configYamlExists();

        $application = $this->getSetUpApplication(new $cmd($commandArgs[0]));
        $command = $application->find($commandName);

        $this->assertContains($expected, $this->getOutputFromCommand($command, $inputArgs));
    }

    /**
     * @return array
     */
    public function executeCommandProvider()
    {
        $locator = new FileLocator(__DIR__ . '/../../Resources/config');
        $configuration = new Configuration(new Processor(), $locator);

        return array(
            'check application command' => array(
                '\Graviton\Deployment\Command\CloudFoundry\CheckApplicationCommand',
                array($configuration),
                'graviton:deployment:cf:checkApplication',
                array(
                    'applicationName' => 'graviton-develop',
                    'slice' => 'blue'
                ),
                'Application health check. Stated messages:'
            ),
            'deploy command' => array(
                '\Graviton\Deployment\Command\CloudFoundry\DeployCommand',
                array($configuration),
                'graviton:deployment:cf:deploy',
                'Deploys an application to a CF instance.'
            ),
        );
    }
}
