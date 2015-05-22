<?php
/**
 * Step to verify the existence of an application in a Cloud Foundry instance.
 */

namespace Graviton\Deployment\Steps;

/**
 * @author   List of contributors <https://github.com/libgraviton/graviton/graphs/contributors>
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     http://swisscom.ch
 */
final class StepCheckApp extends AbstractStep
{
    /** @var string  */
    private $slice;

    /** @var string  */
    private $applicationName;

    /**
     * @param string $applicationName
     * @param string $slice
     */
    public function __construct($applicationName, $slice)
    {
        $this->slice = $slice;
        $this->applicationName = $applicationName;
    }

    /**
     * returns the command
     *
     * @return string
     */
    public function getCommand()
    {
        return array(
            $this->cfCommand(),
            'app',
            $this->applicationName . '-' . $this->slice
        );
    }
}