<?php
/**
 * Created by PhpStorm.
 * User: vagrant
 * Date: 07.08.15
 * Time: 14:37
 */

namespace Graviton\Deployment\Command\CloudFoundry;

use Graviton\Deployment\Command\AbstractCommand;
use Graviton\Deployment\Configuration;
use Graviton\Deployment\Deployment;

final class RunCommand extends AbstractCommand
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
            ->setName('graviton:deployment:cf:run')
            ->setDescription('Run a command as a one-off')
            ->addArgument(
                'Command',
                InputArgument::REQUIRED,
                'Command passed as a string example: "console:command -d"'
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
        DeploymentUtils::login($this->deployHandler, $output, $this->configuration);

        DeploymentUtils::logout($this->deployHandler, $output, $this->configuration);
    }
}