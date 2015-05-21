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

    public function testAdd()
    {
        $deployment = new Deployment();
        $step = $this->getMockBuilder('Graviton\Deployment\StepInterface')
            ->getMock();
        $fluentAdd = $deployment->add($step);
        $this->assertAttributeCount(1, 'steps', $deployment);
        $this->assertInstanceOf('Graviton\Deployment\Deployment', $fluentAdd);
    }
}
