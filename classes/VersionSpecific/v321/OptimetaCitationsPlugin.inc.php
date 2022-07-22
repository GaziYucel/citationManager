<?php
/**
 * @file plugins/generic/optimetaCitations/OptimetaCitationsPlugin.inc.php
 *
 * Copyright (c) 2021+ TIB Hannover
 * Copyright (c) 2021+ Gazi Yucel
 * Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * @class OptimetaCitationsPlugin
 * @ingroup plugins_generic_optimetacitations
 *
 * @brief Plugin for parsing Citations and submitting to Open Access websites.
 */

import('plugins.generic.optimetaCitations.OptimetaCitationsPluginBase');

class OptimetaCitationsPlugin extends OptimetaCitationsPluginBase
{
    protected $versionSpecificNameState = 'workflowData';

    /**
     * @desc This method is called after the plugin is activated
     * @return void
     */
    public function pluginActivationActions()
    {
        $this->callbackParseCronTabWorkAround();

        // create / alter table required by plugin
        import('classes.install.Upgrade');
        $installer = new Upgrade(array());
        $conn = DBConnection::getInstance();
        $installer->dbconn = $conn->getDBConn();
        $this->updateSchema('Installer::postInstall', [$installer]);
    }

    function getInstallSchemaFile()
    {
        return $this->getPluginPath() . '/classes/VersionSpecific/v321/schema.xml';
    }
}
