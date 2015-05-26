<?php
/**
 * Validate deploy class
 */

namespace Graviton\Deployment;

use Graviton\Deployment\Steps\StepInterface;

/**
 * @author  List of contributors <https://github.com/libgraviton/deploy-scripts/graphs/contributors>
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
        $deployment = new Deployment($this->getProcessBuilderDouble());
        $step = $this->getStepDouble();

        $this->assertInstanceOf('Graviton\Deployment\Deployment', $deployment->add($step));
        $this->assertAttributeCount(1, 'steps', $deployment);
    }

    /**
     * testDeployWithOneStep
     *
     * @return void
     */
    public function testDeployWithOneStep()
    {
        $command = array('helloWorldCmd');

        $step = $this->getStepDouble();
        $step
            ->expects($this->once())
            ->method('getCommand')
            ->willReturn($command);

        $process = $this->getProcessDouble();
        $process
            ->expects($this->once())
            ->method('mustRun');

        $processBuilder = $this->getProcessBuilderDouble();
        $processBuilder
            ->expects($this->once())
            ->method('setArguments')
            ->with($this->equalTo($command))
            ->willReturn($processBuilder);
        $processBuilder
            ->expects($this->once())
            ->method('getProcess')
            ->willReturn($process);

        $deployment = new Deployment($processBuilder);
        $deployment
            ->add($step)
            ->deploy();
    }

    /**
     * testDeployWithManySteps
     *
     * @return void
     */
    public function testDeployWithManySteps()
    {
        $command = array('helloWorldCmd');

        $step = $this->getStepDouble();
        $step
            ->expects($this->exactly(2))
            ->method('getCommand')
            ->willReturn($command);

        $process = $this->getProcessDouble();
        $process
            ->expects($this->exactly(2))
            ->method('mustRun');

        $processBuilder = $this->getProcessBuilderDouble();
        $processBuilder
            ->expects($this->exactly(2))
            ->method('setArguments')
            ->with($this->equalTo($command))
            ->willReturn($processBuilder);
        $processBuilder
            ->expects($this->exactly(2))
            ->method('getProcess')
            ->willReturn($process);

        $deployment = new Deployment($processBuilder);
        $deployment
            ->add($step)
            ->add($step)
            ->deploy();
    }

    /**
     * Validates registerSteps
     *
     * @return void
     */
    public function testRegisterSteps()
    {
        $deployment = new Deployment($this->getProcessBuilderDouble());
        $steps = array($this->getStepDouble(), $this->getStepDouble());

        $this->assertInstanceOf('Graviton\Deployment\Deployment', $deployment->registerSteps($steps));
        $this->assertAttributeCount(2, 'steps', $deployment);
    }


    /**
     * Provides an instance of the \Symfony\Component\Process\Process
     *
     * @param array $methods Set of methods to be stubbed.
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|\Symfony\Component\Process\Process
     */
    protected function getProcessDouble(array $methods = array())
    {
        $process = $this->getMockBuilder('\Symfony\Component\Process\Process')
            ->disableOriginalConstructor()
            ->setMethods($methods)
            ->getMock();

        return $process;
    }

    /**
     * get a step double
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|StepInterface
     */
    private function getStepDouble()
    {
        return $this->getMock('Graviton\Deployment\Steps\StepInterface');
    }

    /**
     * get a process factory double
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|\Symfony\Component\Process\ProcessBuilder
     */
    private function getProcessBuilderDouble()
    {
        return $this->getMock('\Symfony\Component\Process\ProcessBuilder');
    }
}
