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
     * @return void
     */
    public function testDeploy()
    {
        $this->configYamlExists();
        $this->suppressOutput();

        $expected = <<<"EOD"
Deploying application (graviton-unstable) to a Cloud Foundry instance.
Trying to login... done
Creating mandatory services... done
cs mongodb free graviton-unstable-mongodb

Determining which application slice to be deployed
... done
Trying to find deployment slice (blue)... found. Using slice graviton-unstable-blue as deployment target.
Will deploy application: graviton-unstable-green.
Pushing graviton-unstable-green to Cloud Foundry.
... done

Removing graviton-unstable-blue from Cloud Foundry.... done
unmap-route graviton-unstable-blue DOMAIN -n graviton
stop graviton-unstable-blue
delete graviton-unstable-blue -f

Logging out... bye

EOD;

        $locator = new FileLocator(__DIR__ . '/../../Resources/config');
        $configuration = new Configuration(new Processor(), $locator);
        $deploymentHandler = new Deployment(new ProcessBuilder());

        $application = $this->getSetUpApplication(new DeployCommand($deploymentHandler, $configuration));
        $command = $application->find('graviton:deployment:cf:deploy');

        $this->assertEquals(
            $expected,
            $this->getOutputFromCommand($command, ['applicationName' => 'graviton'])
        );
    }
}
