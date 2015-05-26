<?php
/**
 * Command to authorize to a Cloud Foundry.
 */

namespace Graviton\Deployment\Command\CloudFoundry;

use Graviton\Deployment\Command\AbstractCommand;
use Graviton\Deployment\Deployment;
use Graviton\Deployment\Steps\CloudFoundry\StepPush;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\ProcessBuilder;

/**
 * @author   List of contributors <https://github.com/libgraviton/deploy-scripts/graphs/contributors>
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     http://swisscom.ch
 */
final class PushCommand extends AbstractCommand
{
    /**
     * Configures the current command.
     *
     * @return void
     */
    protected function configure()
    {
        $this
            ->setName('graviton:deployment:cf:push')
            ->setDescription('Pushes an application to a CF instance.')
            ->addArgument(
                'name',
                InputArgument::REQUIRED,
                'Which application shall be pushed?'
            )
            ->addArgument(
                'slice',
                InputArgument::REQUIRED,
                'Which slice (green or blue deployment) do you want to push?'
            );
    }

    /**
     * Executes the current command.
     *
     * @param InputInterface  $input  User input on console
     * @param OutputInterface $output Output of the command
     *
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $applicationName = $input->getArgument('name');
        $slice = $input->getArgument('slice');

        $this->addStep(new StepPush($this->configuration, $applicationName, $slice));
        $this->setStartMessage('Pushing application to a Cloud Foundry instance. Stated messages:');
        parent::execute($input, $output);
    }
}
