<?php

/**
 * Class to deploy graviton
 */

namespace Graviton\Deployment;

use Symfony\Component\Process\Process;

/**
 * @author   List of contributors <https://github.com/libgraviton/graviton/graphs/contributors>
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     http://swisscom.ch
 */
class Deployment
{
    /**
     * Deploy steps
     *
     * @var StepInterface[]
     */
    private $steps = array();
    
    /**
     * Add new step
     *
     * @param StepInterface $step
     */
    public function add(StepInterface $step)
    {
        array_push($this->steps, $step);
        return $this;
    }
}
