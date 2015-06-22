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
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

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
            )
            ->addArgument(
                'versionName',
                InputArgument::OPTIONAL,
                'Which application shall be deployed?',
                'unstable'
            )
            ->addOption(
                'no-logout',
                null,
                InputOption::VALUE_NONE,
                'Will keep the CF session open after deployment. ' .
                '<fg=yellow;options=bold>Keep in mind to close it by yourself!</fg=yellow;options=bold>'
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
        // read options
        $noLogout = $input->getOption('no-logout');

        // read arguments
        // e.g. graviton-develop
        $applicationName = $input->getArgument('applicationName') . '-' . $input->getArgument('versionName');
        if ($input->getArgument('versionName') == 'master') {
            $applicationRoute = $input->getArgument('applicationName');
        } else {
            $applicationRoute = $applicationName;
        }

        $output->writeln('Deploying application (' . $applicationName . ') to a Cloud Foundry instance.');

        DeploymentUtils::login($this->deployHandler, $output, $this->configuration);
        DeploymentUtils::createServices($this->deployHandler, $output, $this->configuration, $applicationName);
        list($slice, $oldSlice) = DeploymentUtils::determineDeploymentSlice(
            $this->deployHandler,
            $output,
            $this->configuration,
            $applicationName
        );
        DeploymentUtils::setEnvironmentVariables(
            $this->deployHandler,
            $output,
            $this->configuration,
            $applicationName . '-' . $slice
        );
        DeploymentUtils::deploy(
            $this->deployHandler,
            $output,
            $this->configuration,
            $applicationName,
            $applicationRoute,
            $slice
        );
        if (!DeploymentUtils::isInitialDeploy()) {
            DeploymentUtils::cleanUp(
                $this->deployHandler,
                $output,
                $this->configuration,
                $applicationName,
                $applicationRoute,
                $oldSlice
            );
        }

        if (true == $noLogout) {
            $output->writeln(
                '<bg=yellow;fg=black;options=bold>' .
                '                                                                           ' . PHP_EOL .
                '  Cloud Foundry session will not be closed (»no-logout« option was set) !  ' .
                PHP_EOL . '                                                                           ' .
                '</bg=yellow;fg=black;options=bold>'
            );

            return;
        }

        DeploymentUtils::logout($this->deployHandler, $output, $this->configuration);
    }
}
