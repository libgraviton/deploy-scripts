<?php
/**
 * Loads configuration information for the lib.
 */
namespace Graviton\Deployment;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Yaml\Yaml;

/**
 * @author   List of contributors <https://github.com/libgraviton/deploy-scripts/graphs/contributors>
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     http://swisscom.ch
 */
class Configuration implements ConfigurationInterface
{
    /**
     * Loads the current configuration.
     *
     * @return array|string
     */
    public function load()
    {
        $locator = new FileLocator(__DIR__ . '/../app/config');
        $yamlFiles = $locator->locate('config.yml', null, false);
        $config = Yaml::parse(file_get_contents($yamlFiles[0]));

        $this->validateParsedConfiguration(
            $config,
            'Unable to parse the provided configuration file (' . $yamlFiles[0] . ').'
        );

        $processor = new Processor();

        $configuration = $processor->processConfiguration($this, $config);

        $this->validateParsedConfiguration(
            $configuration,
            'Parsing the provided configuration file (' . $yamlFiles[0] . ') did not convert into an array.' .
            PHP_EOL .
            'Please check the setup of »\Symfony\Component\Config\Definition\BaseNode::$finalValidationClosures«.'
        );

        return $configuration;
    }

    /**
     * Generates the configuration tree builder.
     *
     * @return \Symfony\Component\Config\Definition\Builder\TreeBuilder The tree builder
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('deploy-scripts');

        $rootNode
            ->children()
            ->arrayNode('cf')
            ->children()
            ->scalarNode('command')->cannotBeEmpty()->isRequired()->end()
            ->arrayNode('credentials')
            ->children()
            ->scalarNode('username')->cannotBeEmpty()->isRequired()->end()
            ->scalarNode('password')->cannotBeEmpty()->isRequired()->end()
            ->scalarNode('org')->cannotBeEmpty()->isRequired()->end()
            ->scalarNode('space')->cannotBeEmpty()->isRequired()->end()
            ->scalarNode('api_url')->cannotBeEmpty()->isRequired()->end()
            ->scalarNode('domain')->cannotBeEmpty()->isRequired()->end()
            ->end()
            ->end()
            ->arrayNode('services')
            ->children()
            ->scalarNode('mongodb')->end()
            ->scalarNode('atmoss3')->end()
            ->end()
            ->end()
            ->end()
            ->end()
            ->end();

        return $treeBuilder;
    }

    /**
     * Determines the parsed or processed configuration is valid.
     *
     * @param array  $configuration Configuration to be validated
     * @param string $message       Error message to be passed to thrown exception.
     *
     * @throws \Symfony\Component\Config\Definition\Exception\InvalidConfigurationException
     *
     * @return void
     */
    protected function validateParsedConfiguration(array $configuration, $message = '')
    {
        if (!is_array($configuration) || empty($configuration)) {
            throw new InvalidConfigurationException($message);
        }
    }
}
