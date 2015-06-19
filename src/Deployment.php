<?php
/**
 * Class to deploy graviton
 */

namespace Graviton\Deployment;

use Graviton\Deployment\Steps\StepInterface;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\ProcessBuilder;

/**
 * @author   List of contributors <https://github.com/libgraviton/deploy-scripts/graphs/contributors>
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
     * Adds a whole bunch of steps at once.
     *
     * @param array $steps List of steps to be added to the deployment.
     *
     * @return $this
     */
    public function registerSteps(array $steps)
    {
        foreach ($steps as $step) {
            if (!$step instanceof StepInterface) {
                throw new \InvalidArgumentException(
                    'Provided step is not an instance of \Graviton\Deployment\Steps\StepInterface.'
                );
            }
            $this->steps[] = $step;
        }

        return $this;
    }

    /**
     * deploys the steps
     *
     * @param bool $immediateOutput Forces the Process to dump every output immediately.
     *
     * @return string
     */
    public function deploy($immediateOutput = false)
    {
        $callback = null;
        $output = '';

        if (empty($this->steps)) {
            return 'No steps registered! Aborting.';
        }

        /**
         * @link http://symfony.com/doc/current/components/process.html#getting-real-time-process-output
         */
        if (true === $immediateOutput) {
            $callback = function ($type, $buffer) {
                if (Process::ERR === $type) {
                    echo 'ERR > '.$buffer;
                } else {
                    echo 'OUT > '.$buffer;
                }
            };
        }

        foreach ($this->steps as $step) {
            $command = $step->getCommand();
            $process = $this->processBuilder
                ->setArguments($command)
                ->getProcess();
            $process->mustRun($callback);

            // do not add the already printed output to the consolidated output to be printed later.
            if (false === $immediateOutput) {
                $output .= $process->getOutput();
            }
        }

        return $output;
    }

    /**
     * Rests this the current instance.
     *
     * @return $this
     */
    public function resetSteps()
    {
        $this->steps = array();

        return $this;
    }
}
