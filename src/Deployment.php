<?php
/**
 * Class to deploy graviton
 */

namespace Graviton\Deployment;

use Graviton\Deployment\Steps\StepInterface;
use Symfony\Component\Process\ProcessBuilder;

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
     * Builder for process
     *
     * @var ProcessBuilder
     */
    private $processBuilder;

    /**
     * @param ProcessBuilder $processBuilder factory to create processes
     */
    public function __construct(ProcessBuilder $processBuilder)
    {
        $this->processBuilder = $processBuilder;
    }

    /**
     * Add new step
     *
     * @param StepInterface $step step to add
     *
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
            $process = $this->processBuilder
                ->setArguments($command)
                ->getProcess();
            $process->mustRun();
            print $process->getOutput();
        }
    }
}
