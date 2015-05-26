<?php
/**
 * base command
 */
namespace Graviton\Deployment\Command;

use Graviton\Deployment\Configuration;
use Graviton\Deployment\Deployment;
use Graviton\Deployment\Steps\StepInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\ProcessBuilder;

/**
 * @author   List of contributors <https://github.com/libgraviton/deploy-scripts/graphs/contributors>
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     http://swisscom.ch
 */
abstract class AbstractCommand extends Command
{
    /**
     * @var array configuration settings for the application.
     */
    protected $configuration;

    /** @var StepInterface[] */
    protected $steps;

    /** @var string */
    protected $message;

    /**
     * Constructor.
     *
     * @param Configuration $configuration current application configuration loader.
     * @param string|null   $name          The name of the command;
     *                                     passing null means it must be set in configure()
     *
     */
    public function __construct(Configuration $configuration, $name = null)
    {
        parent::__construct($name);

        $this->configuration = $configuration->load();
    }

    /**
     * Executes the current command.
     *
     * @param InputInterface  $input   An InputInterface instance
     * @param OutputInterface $output  Output of the command
     *
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln($this->getStartMessage());

        $deployment = new Deployment(new ProcessBuilder());

        foreach ($this->steps as $step) {
            $deployment->add($step);
        }

        $deployment->deploy();
        $output->writeln('... done');
    }

    /**
     * Provides the previous defined message.
     *
     * @return string
     *
     * @throws \LogicException in case no message was set.
     */
    public function getStartMessage()
    {
        if (empty($this->message)) {
            throw new \LogicException('The console message must not be empty!');
        }
        return $this->message;
    }

    /**
     * Set the content of the start message.
     *
     * @param string $message Start message.
     *
     * @return $this
     */
    public function setStartMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Provides the defined
     *
     * @return StepInterface[]
     *
     * @throws \LogicException in case no step was set.
     */
    public function getStep()
    {
        if (empty($this->steps)) {
            throw new \LogicException('The command step must not be empty!');
        }
        return $this->steps;
    }

    /**
     * Adds a step to the set of steps to be executed.
     *
     * @param StepInterface $step Step to be executed
     *
     * @return $this
     */
    public function addStep(StepInterface $step)
    {
        $this->steps[] = $step;

        return $this;
    }
}
