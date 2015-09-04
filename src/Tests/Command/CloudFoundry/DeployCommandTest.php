<?php
/**
 * Validates command to deploy to a Cloud Foundry.
 */

namespace Graviton\Deployment\Tests\Command\CloudFoundry;

use Graviton\Deployment\Command\CloudFoundry\DeployCommand;
use Graviton\Deployment\Configuration;
use Graviton\Deployment\Deployment;
use Graviton\Deployment\DeployScriptsTestCase;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Process\ProcessBuilder;

/**
 * @author   List of contributors <https://github.com/libgraviton/deploy-scripts/graphs/contributors>
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     http://swisscom.ch
 */
class DeployCommandTest extends DeployScriptsTestCase
{
    /**
     * @dataProvider deployData
     *
     * @param string $appName    name of app
     * @param string $appVersion version to deploy
     * @param string $expected   output to expect from cf
     *
     * @return void
     */
    public function testDeploy($appName, $appVersion, $expected)
    {
        $this->configYamlExists();
        $this->suppressOutput();

        $locator = new FileLocator(__DIR__.'/../../Resources/config');
        $configuration = new Configuration(new Processor(), $locator);
        $deploymentHandler = new Deployment(new ProcessBuilder());

        $application = $this->getSetUpApplication(new DeployCommand($deploymentHandler, $configuration));
        $command = $application->find('graviton:deployment:cf:deploy');

        $this->assertEquals(
            $expected,
            $this->getOutputFromCommand($command, ['applicationName' => $appName, 'versionName' => $appVersion])
        );
    }

    /**
     * @return array[]
     */
    public function deployData()
    {
        $expectedUnstable = <<<"EOD"
Deploying application (graviton-unstable) to a Cloud Foundry instance.
Trying to login... done
Determining which application slice to be deployed
... done
Trying to find deployment slice (blue)... found. Using slice graviton-unstable-blue as deployment target.
Will deploy application: graviton-unstable-green.
Pushing graviton-unstable-green to Cloud Foundry.
... done

Creating services... done
cs mongodb free graviton-unstable-mongodb
bind-service graviton-unstable-green graviton-unstable-mongodb
cs mongodb free graviton-unstable-test
bind-service graviton-unstable-green graviton-unstable-test

Defining environment variables... done
set-env graviton-unstable-green ERRBIT_API_KEY some_secret_key

Adding route (graviton-unstable) to application (graviton-unstable).
... done

Starting application (graviton-unstable).
... done

Removing graviton-unstable-blue from Cloud Foundry.... done
unmap-route graviton-unstable-blue DOMAIN -n graviton-unstable
stop graviton-unstable-blue
delete graviton-unstable-blue -f

Logging out... bye

EOD;
        $expectedMaster = <<<"EOD"
Deploying application (graviton-master) to a Cloud Foundry instance.
Trying to login... done
Determining which application slice to be deployed
... done
Trying to find deployment slice (blue)... found. Using slice graviton-master-blue as deployment target.
Will deploy application: graviton-master-green.
Pushing graviton-master-green to Cloud Foundry.
... done

Creating services... done
cs mongodb free graviton-master-mongodb
bind-service graviton-master-green graviton-master-mongodb
cs mongodb free graviton-master-test
bind-service graviton-master-green graviton-master-test

Defining environment variables... done
set-env graviton-master-green ERRBIT_API_KEY some_secret_key

Adding route (graviton) to application (graviton-master).
... done

Starting application (graviton-master).
... done

Removing graviton-master-blue from Cloud Foundry.... done
unmap-route graviton-master-blue DOMAIN -n graviton
stop graviton-master-blue
delete graviton-master-blue -f

Logging out... bye

EOD;

        return [
            'unstable deploy' => [
                'graviton',
                'unstable',
                $expectedUnstable,
            ],
            'master deploy' => [
                'graviton',
                'master',
                $expectedMaster,
            ],
        ];
    }
}
