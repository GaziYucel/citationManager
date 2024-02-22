<?php
/**
 * @file plugins/generic/citationManager/classes/DataModels/WorkModelHelper.php
 *
 * Copyright (c) 2021+ TIB Hannover
 * Copyright (c) 2021+ Gazi Yucel
 * Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * @class WorkModelHelper
 * @ingroup plugins_generic_citationmanager
 *
 * @brief WorkModelHelper
 */

namespace APP\plugins\generic\citationManager\classes\DataModels;

class WorkModelHelper
{
    /**
     * Migrates to current WorkModel
     *
     * @param array $work
     * @return WorkModel
     */
    public static function migrateToModel(array $work): WorkModel
    {
        $result = new WorkModel();

        if (empty($work)) return $result;

        foreach ($work as $key => $value) {
            switch ($key) {
                case 'add key here to do custom changes or mappings 24ed57634c5a42b3b830a750795cc586':
                    break;
                default:
                    if(property_exists($result, $key)){
                        $result->$key = $value;
                    }
            }
        }

        return $result;
    }
}