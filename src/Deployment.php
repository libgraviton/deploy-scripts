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
     * Factory for process
     *
     * @var ProcessFactory
     */
    private $processFactory;
    
    /**
     * 
     * @param ProcessFactory $processFactory
     */
    public function __construct(ProcessFactory $processFactory)
    {
        $this->processFactory = $processFactory;
    }

    /**
     * Add new step
     *
     * @param StepInterface $step step to add
     * @return Deployment
     */
    public function add(StepInterface $step)
    {
        $this->steps[] = $step;
        return $this;
    }
    
    public function deploy()
    {
        foreach ($this->steps as $step)
        {
            $command = $step->getCommand();
            $process = $this->processFactory->create($command);
        }
    }
}
