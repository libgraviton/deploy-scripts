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
    /** @var Processor  */
    private $processor;

    /** @var FileLocator  */
    private $fileLocator;

    /**
     * @param Processor   $processor   Configuration processor.
     * @param FileLocator $fileLocator Helper class to find files.
     */
    public function __construct(Processor $processor, FileLocator $fileLocator)
    {
        $this->processor = $processor;
        $this->fileLocator = $fileLocator;
    }

    /**
     * Loads the current configuration.
     *
     * @return array
     */
    public function load()
    {
        $yamlFiles = $this->fileLocator->locate('deploy.yml', null, false);
        $config = Yaml::parse(file_get_contents($yamlFiles[0]));

        $this->validateParsedConfiguration(
            $config,
            'Unable to parse the provided configuration file (' . $yamlFiles[0] . ').'
        );

        $configuration = $this->processor->processConfiguration($this, $config);

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
              ->scalarNode('cf_bin')->cannotBeEmpty()->isRequired()->end()
              ->scalarNode('cf_api_url')->cannotBeEmpty()->isRequired()->end()
              ->scalarNode('cf_username')->cannotBeEmpty()->isRequired()->end()
              ->scalarNode('cf_password')->cannotBeEmpty()->isRequired()->end()
              ->scalarNode('cf_org')->cannotBeEmpty()->isRequired()->end()
              ->scalarNode('cf_space')->cannotBeEmpty()->isRequired()->end()
              ->scalarNode('cf_domain')->cannotBeEmpty()->isRequired()->end()
              ->arrayNode('cf_services')
                ->useAttributeAsKey('name')
                ->prototype('scalar')
                ->end()
              ->end()
              ->arrayNode('cf_environment_vars')
                ->useAttributeAsKey('name')
                ->prototype('scalar')
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
