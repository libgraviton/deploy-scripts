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
     * validates a deploy command.
     *
     * @dataProvider methodsAndCountsProvider
     *
     * @param string $command      Command to be run
     * @param array  $methodCounts amount of calls by method
     *
     * @return void
     */
    public function testDeploySteps($command, array $methodCounts)
    {
        $processDouble = $this->getMock('\Symfony\Component\Process\ProcessBuilder');

        DeploymentUtils::$command(
            $this->getDeploymentDouble($processDouble, $methodCounts),
            $this->getOutputDouble(),
            $this->getConfigurationSet(),
            'graviton-develop',
            'blue'
        );
    }

    /**
     * @return array
     */
    public function methodsAndCountsProvider()
    {
        return array(
            'login' => array('login', array('registerSteps' => 1, 'deploy' => 1)),
            'logout' => array('logout', array('registerSteps' => 1, 'deploy' => 1)),
            'cleanUp' => array('cleanUp', array('registerSteps' => 1, 'deploy' => 1)),
            'deploy' => array('deploy', array('registerSteps' => 1, 'deploy' => 1)),
            'determineDeploymentSlice' => array('determineDeploymentSlice', array('registerSteps' => 2, 'deploy' => 2)),
            'createServices' => array('createServices', array('registerSteps' => 1, 'deploy' => 1)),
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