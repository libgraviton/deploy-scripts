<?php
/**
 * test suite to validate cloud foundry deployment utils.
 */
namespace Graviton\Deployment\Tests\Services\CloudFoundry;

use Graviton\Deployment\DeployScriptsTestCase;
use Graviton\Deployment\Services\CloudFoundry\DeploymentUtils;

/**
 * @author   List of contributors <https://github.com/libgraviton/deploy-scripts/graphs/contributors>
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     http://swisscom.ch
 */
class DeploymentUtilsTest extends DeployScriptsTestCase
{
    /**
     * validates creation of mandatory services
     *
     * @return void
     */
    public function testCreateServices()
    {
        $processDouble = $this->getMock('\Symfony\Component\Process\ProcessBuilder');

        DeploymentUtils::createServices(
            $this->getDeploymentDouble($processDouble, array('registerSteps' => 2, 'deploy' => 1)),
            $this->getOutputDouble(),
            $this->getConfigurationSet(),
            'graviton-develop'
        );
    }

    /**
     * validates determinations for blue/green deployment.
     *
     * @return void
     */
    public function testDetermineDeploymentSlice()
    {
        $processDouble = $this->getMock('\Symfony\Component\Process\ProcessBuilder');

        DeploymentUtils::determineDeploymentSlice(
            $this->getDeploymentDouble($processDouble, array('registerSteps' => 2, 'deploy' => 2)),
            $this->getOutputDouble(),
            $this->getConfigurationSet(),
            'graviton-develop'
        );
    }

    /**
     * validates a login attempt.
     *
     * @return void
     */
    public function testLogin()
    {
        $processDouble = $this->getMock('\Symfony\Component\Process\ProcessBuilder');

        DeploymentUtils::login(
            $this->getDeploymentDouble($processDouble, array('registerSteps' => 1, 'deploy' => 1)),
            $this->getOutputDouble(),
            $this->getConfigurationSet(),
            'graviton-develop'
        );
    }

    /**
     * validates a login attempt.
     *
     * @return void
     */
    public function testLogout()
    {
        $processDouble = $this->getMock('\Symfony\Component\Process\ProcessBuilder');

        DeploymentUtils::logout(
            $this->getDeploymentDouble($processDouble, array('registerSteps' => 1, 'deploy' => 1)),
            $this->getOutputDouble(),
            $this->getConfigurationSet(),
            'graviton-develop'
        );
    }

    /**
     * validates a login attempt.
     *
     * @return void
     */
    public function testCleanUp()
    {
        $processDouble = $this->getMock('\Symfony\Component\Process\ProcessBuilder');

        DeploymentUtils::cleanUp(
            $this->getDeploymentDouble($processDouble, array('registerSteps' => 1, 'deploy' => 1)),
            $this->getOutputDouble(),
            $this->getConfigurationSet(),
            'graviton-develop',
            'blue'
        );
    }

    /**
     * validates a login attempt.
     *
     * @return void
     */
    public function testDeploy()
    {
        $processDouble = $this->getMock('\Symfony\Component\Process\ProcessBuilder');

        DeploymentUtils::deploy(
            $this->getDeploymentDouble($processDouble, array('registerSteps' => 1, 'deploy' => 1)),
            $this->getOutputDouble(),
            $this->getConfigurationSet(),
            'graviton-develop',
            'green'
        );
    }

    /**
     * Provides an instance of the Deployment class.
     *
     * @param \Symfony\Component\Process\ProcessBuilder $processDouble Stub to be able to test.
     * @param array                                     $methodCounts  What methods shall be called how often?
     *
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function getDeploymentDouble($processDouble, array $methodCounts)
    {
        $deployDouble = $this->getMockBuilder('\Graviton\Deployment\Deployment')
            ->setConstructorArgs(array($processDouble))
            ->setMethods(array('registerSteps', 'deploy'))
            ->getMock();
        $deployDouble
            ->expects($this->exactly($methodCounts['registerSteps']))
            ->method('registerSteps')
            ->with($this->isType('array'))
            ->willReturn($deployDouble);
        $deployDouble
            ->expects($this->exactly($methodCounts['deploy']))
            ->method('deploy');

        return $deployDouble;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function getOutputDouble()
    {
        $outputDouble = $this->getMockBuilder('\Symfony\Component\Console\Output\OutputInterface')
            ->setMethods(array('write', 'writeln'))
            ->getMockForAbstractClass();

        return $outputDouble;
    }
}
