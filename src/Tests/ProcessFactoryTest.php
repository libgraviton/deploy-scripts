<?php

namespace Graviton\Deployment;

/**
 * @author  List of contributors <https://github.com/libgraviton/graviton/graphs/contributors>
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link    http://swisscom.ch
 */
class ProcessFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testCreate()
    {
        $processFactory = new ProcessFactory();
        
        $command = 'helloWorldCommand';
        $process = $processFactory->create($command);
        
        $this->assertInstanceOf('Symfony\Component\Process\Process', $process);
    }
}
