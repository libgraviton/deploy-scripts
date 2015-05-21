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
     * testDoIt
     *
     * @return void
     */
    public function testDeploy()
    {
        $step = $this->getStepDouble();
        $step->method('getCommand')
            ->willReturn('helloWorldCmd');
        $step->expects($this->once())
            ->method('getCommand');
        
        
        
        $processFactory = $this->getProcessFactoryDouble();
        $processFactory->method('create')
            ->willReturn($this->getMock('Symfony\Component\Process\Process'));
        $processFactory->expects($this->once())
            ->method('create');
        
        $deployment = new Deployment($processFactory);
        
        $deployment->add($step);
        
        $deployment->deploy();
    }
    
    private function getStepDouble()
    {
        return $this->getMock('Graviton\Deployment\StepInterface');
    }
    
    private function getProcessFactoryDouble()
        {
        return $this->getMock('Graviton\Deployment\ProcessFactory');
    }
}
