<?php
namespace Optimeta\Citations\Dao;

import('lib.pkp.classes.site.VersionCheck');

use VersionCheck;

if (strstr(VersionCheck::getCurrentCodeVersion()->getVersionString(false), '3.2.1')) {
    require_once(OPTIMETA_CITATIONS_PLUGIN_PATH . '/classes/VersionSpecific/v321/CitationsExtendedDAO.inc.php');
    return;
}

import('plugins.generic.optimetaCitations.classes.Dao.CitationsExtendedDAOBase');

class CitationsExtendedDAO extends CitationsExtendedDAOBase
{

}
