<?php
/**
 * @file plugins/generic/optimetaCitations/classes/ScheduledTasks/DepositorTask.inc.php
 *
 * Copyright (c) 2021+ TIB Hannover
 * Copyright (c) 2021+ Gazi Yucel
 * Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * @class DepositorTask
 * @ingroup plugins_generic_optimetacitations
 *
 * @brief Main DepositorTask class
 */

import('lib.pkp.classes.scheduledTask.ScheduledTask');
import('plugins.generic.optimetaCitations.OptimetaCitationsPlugin');

use Optimeta\Citations\Deposit\Depositor;

class DepositorTask extends ScheduledTask
{
    /**
     * @var $plugin OptimetaCitationsPlugin
     */
    var $plugin;

    /**
     * Constructor.
     * @param $args array
     */
    function __construct($args)
    {
        $plugin = \PluginRegistry::getPlugin('generic', 'optimetacitationsplugin');
        $this->plugin = $plugin;

        parent::__construct($args);
    }

    /**
     * @copydoc ScheduledTask::executeActions()
     */
    public function executeActions()
    {
        $plugin = $this->plugin;
        if (!$plugin->getEnabled()) {
            $this->addExecutionLogEntry(
                'Optimeta\Citations\ScheduledTasks\DepositorTask\executeActions>pluginEnabled=false' .
                ' [' . date('Y-m-d H:i:s') . ']',
                SCHEDULED_TASK_MESSAGE_TYPE_WARNING);
            return false;
        }

        $result = false;

        $depositor = new Depositor();
        $result = $depositor->batchDeposit();

        if (!$result) {
            $this->addExecutionLogEntry(
                'Optimeta\Citations\ScheduledTasks\DepositorTask\executeActions>result=false' .
                ' [' . date('Y-m-d H:i:s') . ']',
                SCHEDULED_TASK_MESSAGE_TYPE_ERROR);
        }

        $this->addExecutionLogEntry(
            'Optimeta\Citations\ScheduledTasks\DepositorTask\executeActions>result=' . $result,
            SCHEDULED_TASK_MESSAGE_TYPE_COMPLETED);

        return $result;
    }
}