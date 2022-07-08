<?php
/**
 * @file plugins/generic/optimetaCitations/classes/TaskScheduler/TaskScheduler.inc.php
 *
 * Copyright (c) 2021+ TIB Hannover
 * Copyright (c) 2021+ Gazi Yucel
 * Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * @class TaskScheduler
 * @ingroup
 *
 * @brief Main TaskScheduler class
 *
 */
namespace Optimeta\Citations\Scheduler;

import('lib.pkp.classes.scheduledTask.ScheduledTask');

use ScheduledTask;

class TaskScheduler extends ScheduledTask
{
    /**
     * @var $plugin OptimetaCitationsPlugin
     */
    var $plugin;

    /**
     * Constructor.
     * @param $args array task arguments
     */
    function __construct($args)
    {
        parent::__construct($args);
    }

    /**
     * @copydoc ScheduledTask::executeActions()
     */
    public function executeActions()
    {
        if (!$this->plugin) return false;

        error_log('task executed at ' . date('Y-m-d H:i:s'));

        return true;
    }
}