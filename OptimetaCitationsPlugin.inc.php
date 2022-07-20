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

import('lib.pkp.classes.site.VersionCheck');

if (strstr(VersionCheck::getCurrentCodeVersion()->getVersionString(false), '3.2.1')) {
    require_once(__DIR__ . '/classes/VersionSpecific/v321/OptimetaCitationsPlugin.inc.php');
    return;
}

import('plugins.generic.optimetaCitations.OptimetaCitationsPluginBase');

class OptimetaCitationsPlugin extends OptimetaCitationsPluginBase
{

}
