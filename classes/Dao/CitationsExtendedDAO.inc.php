<?php
namespace Optimeta\Citations\Dao;

import('lib.pkp.classes.site.VersionCheck');

use VersionCheck;

if (strstr(VersionCheck::getCurrentCodeVersion()->getVersionString(false), '3.2.1')) {
    require_once(OPTIMETA_CITATIONS_PLUGIN_PATH . '/classes/VersionSpecific/CitationsExtendedDAO_ojs_v321.inc.php');
    return;
}

import('plugins.generic.optimetaCitations.classes.Dao.CitationsExtendedDAOBase');

class CitationsExtendedDAO extends CitationsExtendedDAOBase
{

}
