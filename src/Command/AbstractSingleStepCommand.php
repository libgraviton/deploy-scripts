<?php
/**
 *  Consolidates code for symfony commands only using one step
 */

namespace Graviton\Deployment\Command;

use Graviton\Deployment\Deployment;
use Graviton\Deployment\Steps\StepInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\ProcessBuilder;

/**
 * @author   List of contributors <https://github.com/libgraviton/graviton/graphs/contributors>
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     http://swisscom.ch
 */
class AbstractSingleStepCommand extends AbstractCommand
{
    /**
     * Configures the current command.
     *
     * @return void
     */
    protected function configure($command, $description)
    {
        $this
            ->setName($command)
            ->setDescription($description);
    }

    /**
     * Executes the current command.
     *
     * @param StepInterface   $step    Step to be executed
     * @param OutputInterface $message Start message.
     * @param InputInterface  $input   User input on console
     * @param OutputInterface $output  Output of the command
     *
     * @return void
     */
    protected function execute(StepInterface $step, $message, InputInterface $input, OutputInterface $output)
    {
        $output->writeln($message);

        $deployment = new Deployment(new ProcessBuilder());
        $deployment
            ->add($step)
            ->deploy();

        $output->writeln('... done');
    }
}
