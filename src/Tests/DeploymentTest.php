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
        $command = 'helloWorldCmd';

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
            ->method('add')
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
        $command = 'helloWorldCmd';

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
            ->method('add')
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
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getProcessDouble()
    {
        $process = $this->getMockBuilder('\Symfony\Component\Process\Process')
            ->disableOriginalConstructor()
            ->setMethods(array('mustRun'))
            ->getMock();

        return $process;
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
     * @return \Symfony\Component\Process\ProcessBuilder
     */
    private function getProcessBuilderDouble()
    {
        return $this->getMock('\Symfony\Component\Process\ProcessBuilder');
    }
}
