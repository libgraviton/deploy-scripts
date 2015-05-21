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
        $deployment = new Deployment();
        $step = $this->getMock('Graviton\Deployment\StepInterface');
        
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
        $deployment = new Deployment();
        $step = $this->getMockBuilder('Graviton\Deployment\StepInterface')
            ->getMock();
        $step->method('getCommand')
            ->willReturn('helloWorldCmd');
        $step->expects($this->once())
            ->method('getCommand');
        
        $deployment->add($step);
        
        $deployment->deploy();
    }
}
