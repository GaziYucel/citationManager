<?php
/**
 * @file plugins/generic/optimetaCitations/classes/Helpers/ClassHelper.php
 *
 * Copyright (c) 2021+ TIB Hannover
 * Copyright (c) 2021+ Gazi Yucel
 * Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * @class ClassHelper
 * @ingroup plugins_generic_optimetacitations
 *
 * @brief ClassHelper.
 */

namespace APP\plugins\generic\optimetaCitations\classes\Helpers;

use ReflectionClass;
use ReflectionProperty;

class ClassHelper
{
    /**
     * Get public properties of object
     *
     * @param object $class
     * @return array
     */
    public function getObjectProperties(object $class): array
    {
        $reflect = new ReflectionClass($class);
        $properties = $reflect->getProperties(ReflectionProperty::IS_PUBLIC);

        $objVars = [];

        for ($i = 0; $i < count($properties); $i++)
            $objVars[$properties[$i]->name] = null;

        error_log($reflect->getName() . '(objVars): ' . json_encode($objVars, JSON_UNESCAPED_SLASHES));

        return $objVars;
    }

    /**
     * Assign values to class properties from the given data array and return an instance of the class
     *
     * @param object $class
     * @param array $values
     * @return array
     */
    public function getObjectPropertiesWithValues(object $class, array $values): array
    {
        $reflect = new ReflectionClass($class);
        $properties = (array)$reflect->getProperties(ReflectionProperty::IS_PUBLIC);
        $objVars = [];

        for ($i = 0; $i < count($properties); $i++) {
            if (!empty($values[$properties[$i]['name']])) {
                $objVars[$properties[$i]->name] = $values[$properties[$i]->name];
            } else {
                $objVars[$properties[$i]->name] = null;
            }
        }

        return $objVars;
    }
}