<?php
/**
 * Test suite to validate the Configuration class
 */

namespace Graviton\Deployment;

/**
 * @author   List of contributors <https://github.com/libgraviton/deploy-scripts/graphs/contributors>
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     http://swisscom.ch
 */
class ConfigurationTest extends DeployScriptsTestCase
{
    /**
     * @return void
     */
    public function testLoadExpectingException()
    {
        $this->configYamlExists();

        $processorDouble = $this->getConfigurationProcessorDouble(array('processConfiguration'));
        $processorDouble
            ->expects($this->once())
            ->method('processConfiguration')
            ->willReturn(array());

        $configuration = new Configuration($processorDouble);

        $this->setExpectedException('\Symfony\Component\Config\Definition\Exception\InvalidConfigurationException');

        $configuration->load();
    }
}
