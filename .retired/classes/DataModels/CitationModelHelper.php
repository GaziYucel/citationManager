<?php
/**
 * @file plugins/generic/citationManager/classes/DataModels/CitationModelHelper.php
 *
 * Copyright (c) 2021+ TIB Hannover
 * Copyright (c) 2021+ Gazi Yucel
 * Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * @class CitationModelHelper
 * @ingroup plugins_generic_citationmanager
 *
 * @brief CitationModelHelper
 */

namespace APP\plugins\generic\citationManager\classes\DataModels;

class CitationModelHelper
{
    public static function migrateToCitationModel(array $citation): CitationModel
    {
        $result = new CitationModel();

        if (empty($citation)) return $result;

        foreach ($citation as $key => $value) {
            switch ($key) {
                case 'add key here to do custom changes or mappings ea7dc2987572474988df31d83dcc97eb':
                    break;
                default:
                    if (property_exists($result, $key)) {
                        $result->$key = $value;
                    }
            }
        }

        return $result;
    }
}
