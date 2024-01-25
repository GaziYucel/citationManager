<?php
/**
 * @file plugins/generic/optimetaCitations/classes/ScheduledTasks/DepositorTask.php
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

use APP\plugins\generic\optimetaCitations\classes\Handler\DepositorHandler;
use PKP\scheduledTask\ScheduledTaskHelper;

class DepositorTask extends ScheduledTask
{
    /**
     * @var OptimetaCitationsPlugin
     */
    var OptimetaCitationsPlugin $plugin;

    function __construct($args)
    {
        $plugin = PluginRegistry::getPlugin('generic', 'optimetacitationsplugin');

        /** @var OptimetaCitationsPlugin $plugin */
        $this->plugin = $plugin;

        parent::__construct($args);
    }

    /**
     * @copydoc ScheduledTask::executeActions()
     */
    public function executeActions(): bool
    {
        return false; // todo: disabled, do something useful

        $plugin = $this->plugin;
        if (!$plugin->getEnabled()) {
            $this->addExecutionLogEntry(
                'APP\plugins\generic\optimetaCitations\classes\ScheduledTasks\DepositorTask\executeActions>pluginEnabled=false' .
                ' [' . date('Y-m-d H:i:s') . ']',
                ScheduledTaskHelper::SCHEDULED_TASK_MESSAGE_TYPE_WARNING);
            return false;
        }

        $result = false;

        $depositor = new DepositorHandler($this->plugin);
        $result = $depositor->batchDeposit();

        if (!$result) {
            $this->addExecutionLogEntry(
                'APP\plugins\generic\optimetaCitations\classes\ScheduledTasks\DepositorTask\executeActions>result=false' .
                ' [' . date('Y-m-d H:i:s') . ']',
                ScheduledTaskHelper::SCHEDULED_TASK_MESSAGE_TYPE_ERROR);
        }

        $this->addExecutionLogEntry(
            'APP\plugins\generic\optimetaCitations\classes\ScheduledTasks\DepositorTask\executeActions>result=' . $result,
            ScheduledTaskHelper::SCHEDULED_TASK_MESSAGE_TYPE_COMPLETED);

        return $result;
    }
}