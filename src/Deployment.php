<?php
/**
 * Class to deploy graviton
 */

namespace Graviton\Deployment;

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
     * Constructor with ProcessFactory param
     *
     * @param ProcessFactory $processFactory factory to create processes
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
    
    /**
     * deploys the steps
     *
     * @return void
     */
    public function deploy()
    {
        foreach ($this->steps as $step) {
            $command = $step->getCommand();
            $process = $this->processFactory->create($command);
            $process->run();
        }
    }
}
