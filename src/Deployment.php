<?php
/**
 * Class to deploy graviton
 */

namespace Graviton\Deployment;

use Graviton\Deployment\Steps\StepInterface;
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
     * @return string
     */
    public function deploy()
    {
        if (empty($this->steps)) {
            return 'No steps registered! Aborting.';
        }

        $output = '';

        foreach ($this->steps as $step) {
            $command = $step->getCommand();
            $process = $this->processBuilder
                ->setArguments($command)
                ->getProcess();
            $process->mustRun();
            $output = $process->getOutput();
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
