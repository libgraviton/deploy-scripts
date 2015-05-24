<?php
/**
 *  base class to gather basic information for a step.
 */

namespace Graviton\Deployment\Steps\CloudFoundry;

use Graviton\Deployment\Steps\StepInterface;

/**
 * @author   List of contributors <https://github.com/libgraviton/graviton/graphs/contributors>
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     http://swisscom.ch
 */
abstract class AbstractStep implements StepInterface
{
    /** @var array Current application configuration.*/
    protected $configuration;


    /**
     * @param array $configuration Current application configuration.
     */
    public function __construct(array $configuration)
    {
        $this->configuration = $configuration;
    }
}
