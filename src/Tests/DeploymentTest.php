<?php
/**
 * Validate deploy class
 */

namespace Graviton\Deployment;

use Symfony\Component\Process\ProcessBuilder;

/**
 * @author  List of contributors <https://github.com/libgraviton/deploy-scripts/graphs/contributors>
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link    http://swisscom.ch
 */
class DeploymentTest extends \PHPUnit_Framework_TestCase
{
    /**
     * testDeployWithOneStep
     *
     * @return void
     */
    public function testDeployWithOneStep()
    {
        $command = array('helloWorldCmd');

        $step = $this->getMock('Graviton\Deployment\Steps\StepInterface');
        $step
            ->expects($this->once())
            ->method('getCommand')
            ->willReturn($command);

        $process = $this->getProcessDouble();
        $process
            ->expects($this->once())
            ->method('mustRun');

        $processBuilder = $this->getMock('\Symfony\Component\Process\ProcessBuilder');
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
            ->registerSteps([$step])
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

        $step = $this->getMock('Graviton\Deployment\Steps\StepInterface');
        $step
            ->expects($this->exactly(2))
            ->method('getCommand')
            ->willReturn($command);

        $process = $this->getProcessDouble();
        $process
            ->expects($this->exactly(2))
            ->method('mustRun');

        $processBuilder = $this->getMock('\Symfony\Component\Process\ProcessBuilder');
        $processBuilder
            ->expects($this->exactly(2))
            ->method('setArguments')
            ->with($this->equalTo($command))
            ->willReturn($processBuilder);
        $processBuilder
            ->expects($this->exactly(2))
            ->method('getProcess')
            ->willReturn($process);

        $deployment = $this->getDeploymentObject($processBuilder, [$step, $step]);
        $deployment->deploy();
    }

    /**
     * Validates registerSteps
     *
     * @return void
     */
    public function testNoStepsRegistered()
    {
        $processBuilder = new \Symfony\Component\Process\ProcessBuilder;

        $deployment = new Deployment($processBuilder);
        $deployment->registerSteps(array());

        $output = $deployment->deploy();

        $this->assertSame('No steps registered! Aborting.', $output);
        $this->assertAttributeCount(0, 'steps', $deployment);
    }

    /**
     * @return void
     */
    public function testResetSteps()
    {
        $deployment = $this->getDeploymentObject(
            $this->getMock('\Symfony\Component\Process\ProcessBuilder'),
            [$this->getMock('Graviton\Deployment\Steps\StepInterface')]
        );

        $this->assertAttributeCount(1, 'steps', $deployment);
        $this->assertInstanceOf(
            'Graviton\Deployment\Deployment',
            $deployment->resetSteps()
        );
        $this->assertAttributeCount(0, 'steps', $deployment);
    }

    /**
     * @return void
     */
    public function testRegisterInvalidTest()
    {
        $deployment = new Deployment($this->getMock('\Symfony\Component\Process\ProcessBuilder'));

        $this->setExpectedException('\InvalidArgumentException');
        $deployment->registerSteps(['invalid step type']);
    }

    /**
     * Provides an instance of the \Symfony\Component\Process\Process
     *
     * @param array $methods Set of methods to be stubbed.
     *
     * @return \PHPUnit_Framework_MockObject_MockObject
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
     * @param ProcessBuilder $processBuilder Test double of the SF2 ProcessBuilder
     * @param array          $steps          List of steps to be registered.
     *
     * @return Deployment
     */
    private function getDeploymentObject(ProcessBuilder $processBuilder, array $steps = array())
    {
        $deployment = new Deployment($processBuilder);
        $deployment->registerSteps($steps);

        return $deployment;
    }
}
