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
//namespace Optimeta\Citations\ScheduledTasks;

import('lib.pkp.classes.scheduledTask.ScheduledTask');
import('plugins.generic.optimetaCitations.OptimetaCitationsPlugin');

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
        $this->plugin = PluginRegistry::getPlugin('generic', 'optimetacitationsplugin');

        parent::__construct($args);

        error_log(
            'Optimeta\Citations\ScheduledTasks\DepositorTask\__construct: ' . date('Y-m-d H:i:s'));
    }

    /**
     * @copydoc ScheduledTask::executeActions()
     */
    public function executeActions(): bool
    {
        if (!$this->plugin->getEnabled()) return false;

        error_log(
            'Optimeta\Citations\ScheduledTasks\DepositorTask\executeActions: ' . date('Y-m-d H:i:s'));
        return true;
    }
}