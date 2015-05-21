<?php
/**
 * Validate deploy class
 */

namespace Graviton\Deployment;

/**
 * @author  List of contributors <https://github.com/libgraviton/graviton/graphs/contributors>
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link    http://swisscom.ch
 */
class DeploymentTest extends \PHPUnit_Framework_TestCase
{

    /**
     * testAdd
     *
     * @return void
     */
    public function testAdd()
    {
        $deployment = new Deployment($this->getProcessFactoryDouble());
        $step = $this->getStepDouble();

        $this->assertInstanceOf('Graviton\Deployment\Deployment', $deployment->add($step));
        $this->assertAttributeCount(1, 'steps', $deployment);
    }

    /**
     * testDeployWithZeroSteps
     *
     * @return void
     */
    public function testDeployWithZeroSteps()
    {
        
    }

    /**
     * testDeployWithOneStep
     *
     * @return void
     */
    public function testDeployWithOneStep()
    {
        $command = 'helloWorldCmd';

        $step = $this->getStepDouble();
        $step->method('getCommand')
            ->willReturn($command);
        $step->expects($this->once())
            ->method('getCommand');

        $processMock = $this->getMockBuilder('Symfony\Component\Process\Process')
            ->setConstructorArgs(array($command))
            ->getMock();
        $processMock->expects($this->once())
            ->method('run');

        $processFactory = $this->getProcessFactoryDouble();

        $processFactory->method('create')
            ->willReturn($processMock);

        $processFactory->expects($this->once())
            ->method('create');

        $deployment = new Deployment($processFactory);

        $deployment->add($step);

        $deployment->deploy();
    }

    /**
     * testDeployWithManySteps
     *
     * @return void
     */
    public function testDeployWithManySteps()
    {
        
    }

    /**
     * get a step double
     *
     * @return StepInterface
     */
    private function getStepDouble()
    {
        return $this->getMock('Graviton\Deployment\StepInterface');
    }

    /**
     * get a process factory double
     *
     * @return ProcessFactory
     */
    private function getProcessFactoryDouble()
    {
        return $this->getMock('Graviton\Deployment\ProcessFactory');
    }
}
