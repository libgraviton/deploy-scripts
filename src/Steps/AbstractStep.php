<?php
/**
 *  base class to gather basic information for a step.
 */

namespace Graviton\Deployment\Steps;

/**
 * @author   List of contributors <https://github.com/libgraviton/graviton/graphs/contributors>
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     http://swisscom.ch
 */
abstract class AbstractStep implements StepInterface
{
    /**
     * @return string
     */
    public function cfCommand()
    {
        return $_ENV['SYMFONY__DEPLOYMENT__CF_COMMAND'];
    }
}
