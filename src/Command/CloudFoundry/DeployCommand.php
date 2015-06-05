<?php
/**
 * Command to deploy an application to a Cloud Foundry.
 */

namespace Graviton\Deployment\Command\CloudFoundry;

use Graviton\Deployment\Command\AbstractCommand;
use Graviton\Deployment\Deployment;
use Graviton\Deployment\Services\CloudFoundry\DeploymentUtils;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\ProcessBuilder;

/**
 * @author   List of contributors <https://github.com/libgraviton/deploy-scripts/graphs/contributors>
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     http://swisscom.ch
 */
final class DeployCommand extends AbstractCommand
{
    /** @var Deployment */
    private $deployCmd;

    /**
     * Configures the current command.
     *
     * @return void
     */
    protected function configure()
    {
        $this->deployCmd = new Deployment(new ProcessBuilder());
        $this
            ->setName('graviton:deployment:cf:deploy')
            ->setDescription('Deploys an application to a CF instance.')
            ->addArgument(
                'applicationName',
                InputArgument::REQUIRED,
                'Which application shall be deployed?'
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
        $applicationName = $input->getArgument('applicationName');

        $output->writeln('Deploying application (' . $applicationName . ') to a Cloud Foundry instance.');

        DeploymentUtils::login($this->deployCmd, $output, $this->configuration, $applicationName);
        DeploymentUtils::createServices($this->deployCmd, $output, $this->configuration, $applicationName);
        list($slice, $oldSlice) = DeploymentUtils::determineDeploymentSlice(
            $this->deployCmd,
            $output,
            $this->configuration,
            $applicationName
        );
        DeploymentUtils::deploy($this->deployCmd, $output, $this->configuration, $applicationName, $slice);
        DeploymentUtils::cleanUp($this->deployCmd, $output, $this->configuration, $applicationName, $oldSlice);
        DeploymentUtils::logout($this->deployCmd, $output, $this->configuration);
    }
}
