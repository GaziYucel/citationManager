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
        $this->plugin = PluginRegistry::getPlugin('generic', 'optimetacitationsplugin');

        parent::__construct($args);}

    /**
     * @copydoc ScheduledTask::executeActions()
     */
    public function executeActions(): bool
    {
        if(is_null($this->plugin)) return false;
        if(!$this->plugin->getEnabled()) return false;

        $result = false;

        $depositor = new Depositor();
        $result = $depositor->batchDeposit();

        error_log(
            'Optimeta\Citations\ScheduledTasks\DepositorTask\executeActions: ' .
            date('Y-m-d H:i:s'));

        return $result;
    }
}