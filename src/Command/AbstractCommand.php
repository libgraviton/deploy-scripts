<?php
/**
 * base command
 */
namespace Graviton\Deployment\Command;

use Graviton\Deployment\Configuration;
use Symfony\Component\Console\Command\Command;

/**
 * @author   List of contributors <https://github.com/libgraviton/graviton/graphs/contributors>
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     http://swisscom.ch
 */
abstract class AbstractCommand extends Command
{
    /**
     * @var array configuration settings for the application.
     */
    protected $configuration;

    /**
     * Constructor.
     *
     * @param Configuration $configuration          current application configuration loader.
     * @param string|null   $name                   The name of the command;
     *                                              passing null means it must be set in configure()
     *
     */
    public function __construct(Configuration $configuration, $name = null)
    {
        parent::__construct($name);

        $this->configuration = $configuration->load();
    }
}
