<?php
namespace Optimeta\Citations\Dao;

if (strstr(OPTIMETA_OJS_VERSION, OPTIMETA_COMPATIBLE_OJS_VERSION["V321"])) {
    class CitationsExtendedDAO extends \Optimeta\Citations\VersionSpecific\V321\Dao\CitationsExtendedDAO {}
}
else if (strstr(OPTIMETA_OJS_VERSION, OPTIMETA_COMPATIBLE_OJS_VERSION["V330"])) {
    class CitationsExtendedDAO extends \Optimeta\Citations\VersionSpecific\V330\Dao\CitationsExtendedDAO {}
}
else {
    class CitationsExtendedDAO extends \Optimeta\Citations\VersionSpecific\Main\Dao\CitationsExtendedDAO {}
}
