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

const OPTIMETA_CITATIONS_PLUGIN_PATH               = __DIR__;

const OPTIMETA_CITATIONS_API_ENDPOINT              = 'OptimetaCitations';
const OPTIMETA_CITATIONS_PUBLICATION_WORK          = 'OptimetaCitations_PublicationWork';
const OPTIMETA_CITATIONS_FORM_NAME                 = 'OptimetaCitations_PublicationForm';
const OPTIMETA_CITATIONS_FORM_FIELD_PARSED         = 'OptimetaCitations_CitationsParsed';
const OPTIMETA_CITATIONS_SAVED_IS_ENABLED          = 'OptimetaCitations_IsEnabled';

const OPTIMETA_CITATIONS_WIKIDATA_USERNAME         = 'OptimetaCitations_Wikidata_Username';
const OPTIMETA_CITATIONS_WIKIDATA_PASSWORD         = 'OptimetaCitations_Wikidata_Password';
const OPTIMETA_CITATIONS_WIKIDATA_API_URL          = 'OptimetaCitations_Wikidata_Api_Url';
const OPTIMETA_CITATIONS_OPEN_CITATIONS_OWNER      = 'OptimetaCitations_Open_Citations_Owner';
const OPTIMETA_CITATIONS_OPEN_CITATIONS_REPOSITORY = 'OptimetaCitations_Open_Citations_Repository';
const OPTIMETA_CITATIONS_OPEN_CITATIONS_TOKEN      = 'OptimetaCitations_Open_Citations_Token';

const OPTIMETA_COMPATIBLE_OJS_VERSION = [
    "V321" => "3.2.1",
    "V330" => "3.3.0"
];

import('lib.pkp.classes.site.VersionCheck');
define('OPTIMETA_OJS_VERSION', VersionCheck::getCurrentCodeVersion()->getVersionString(false));

require_once (OPTIMETA_CITATIONS_PLUGIN_PATH . '/vendor/autoload.php');

if (strstr(OPTIMETA_OJS_VERSION, OPTIMETA_COMPATIBLE_OJS_VERSION["V321"])) {
    class OptimetaCitationsPlugin extends \Optimeta\Citations\VersionSpecific\V321\OptimetaCitationsPlugin {}
}
else if (strstr(OPTIMETA_OJS_VERSION, OPTIMETA_COMPATIBLE_OJS_VERSION["V330"])) {
    class OptimetaCitationsPlugin extends \Optimeta\Citations\VersionSpecific\V330\OptimetaCitationsPlugin {}
}
else {
    class OptimetaCitationsPlugin extends \Optimeta\Citations\VersionSpecific\Main\OptimetaCitationsPlugin {}
}
