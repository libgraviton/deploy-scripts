<?php
/**
 * Validate deploy class
 */

namespace Graviton\Deploy;

/**
 * @author  List of contributors <https://github.com/libgraviton/graviton/graphs/contributors>
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link    http://swisscom.ch
 */
class DeployTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @return void
     */
    public function testInstanceClass()
    {
        $deploy = new Deploy();
        $this->assertInstanceOf('\Graviton\Deploy\Deploy', $deploy);
    }
}
