<?php
/**
 * Step to start an application in a Cloud Foundry instance.
 */

namespace Graviton\Deployment\Steps\CloudFoundry;

/**
 * @author   List of contributors <https://github.com/libgraviton/deploy-scripts/graphs/contributors>
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     http://swisscom.ch
 */
final class StepStart extends AbstractCommonStep
{
    /**
     * @var string Name of the step to be registered.
     */
    protected static $stepName = 'start';
}
