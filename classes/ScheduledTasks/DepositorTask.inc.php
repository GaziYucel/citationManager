<?php
/**
 * @file plugins/generic/optimetaCitations/classes/ScheduledTasks/DepositorTask.inc.php
 *
 * Copyright (c) 2021+ TIB Hannover
 * Copyright (c) 2021+ Gazi Yucel
 * Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * @class DepositorTask
 * @ingroup
 *
 * @brief Main DepositorTask class
 *
 */
namespace Optimeta\Citations\ScheduledTasks;

import('lib.pkp.classes.scheduledTask.ScheduledTask');

use OptimetaCitationsPlugin;
use PluginRegistry;
use ScheduledTask;

class DepositorTask extends ScheduledTask
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
        $this->plugin = PluginRegistry::getPlugin('generic', 'optimetacitationsplugin');

        parent::__construct($args);

        error_log('Optimeta\Citations\Scheduler\TaskScheduler->plugin->getEnabled: ' . $this->plugin->getEnabled());
        if ($this->plugin->getEnabled()) {
            // todo: do something useful
        }
    }

    /**
     * @copydoc ScheduledTask::executeActions()
     */
    public function executeActions(): bool
    {
        if (!$this->plugin->getEnabled()) return false;

        error_log('task executed at ' . date('Y-m-d H:i:s'));

        return true;
    }
}