<?php

namespace Graviton\Deployment;

use Symfony\Component\Process\Process;

/**
 * @author   List of contributors <https://github.com/libgraviton/graviton/graphs/contributors>
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     http://swisscom.ch
 */
class ProcessFactory
{
    public function create($command)
    {
        return new Process($command);
    }
}
