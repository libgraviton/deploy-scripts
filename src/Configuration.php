<?php
/**
 * Loads configuration information for the lib.
 */
namespace Graviton\Deployment;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Yaml\Yaml;

/**
 * @author   List of contributors <https://github.com/libgraviton/graviton/graphs/contributors>
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     http://swisscom.ch
 */
class Configuration implements ConfigurationInterface
{
    /**
     * Loads the current configuration.
     *
     * @return array
     */
    public function load()
    {
        $locator = new FileLocator(__DIR__ . '/../app/config');
        $yamlFiles = $locator->locate('config.yml', null, false);
        $config = Yaml::parse(file_get_contents($yamlFiles[0]));

        $processor = new Processor();

        return $processor->processConfiguration($this, $config);
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
}
