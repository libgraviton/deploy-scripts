<?php
/**
 * Interface for a step
 */

namespace Graviton\Deployment\Steps;

/**
 * @author   List of contributors <https://github.com/libgraviton/graviton/graphs/contributors>
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     http://swisscom.ch
 */
interface StepInterface
{
    /**
     * returns the command
     *
     * @return string
     */
    public function getCommand();
}
