<?php
/**
 * Command to deploy an application to a Cloud Foundry.
 */

namespace Graviton\Deployment\Command\CloudFoundry;

use Graviton\Deployment\Command\AbstractCommand;
use Graviton\Deployment\Configuration;
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
    private $deployHandler;

    /**
     * @param Deployment    $deploymentHandler Managing the actual deployment
     * @param Configuration $configuration     Set of configuration options to influence the current command.
     * @param string|null   $name              Name of the command
     */
    public function __construct(Deployment $deploymentHandler, Configuration $configuration, $name = null)
    {
        parent::__construct($configuration, $name);
        $this->deployHandler = $deploymentHandler;
    }

    /**
     * Configures the current command.
     *
     * @return void
     */
    protected function configure()
    {
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

        DeploymentUtils::login($this->deployHandler, $output, $this->configuration);
        DeploymentUtils::createServices($this->deployHandler, $output, $this->configuration, $applicationName);
        list($slice, $oldSlice) = DeploymentUtils::determineDeploymentSlice(
            $this->deployHandler,
            $output,
            $this->configuration,
            $applicationName
        );
        DeploymentUtils::deploy($this->deployHandler, $output, $this->configuration, $applicationName, $slice);
        DeploymentUtils::cleanUp($this->deployHandler, $output, $this->configuration, $applicationName, $oldSlice);
        DeploymentUtils::logout($this->deployHandler, $output, $this->configuration);
    }
}
