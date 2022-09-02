<?php
namespace Optimeta\Citations\Dao;

import('lib.pkp.classes.site.VersionCheck');

use VersionCheck;

if (strstr(VersionCheck::getCurrentCodeVersion()->getVersionString(false), '3.2.1')) {
    import('plugins.generic.optimetaCitations.classes.VersionSpecific.v321.CitationsExtendedDAOv321');
    class CitationsExtendedDAO extends CitationsExtendedDAOv321  {}
}
else {
    import('plugins.generic.optimetaCitations.classes.Dao.CitationsExtendedDAOBase');
    class CitationsExtendedDAO extends CitationsExtendedDAOBase {}
}
