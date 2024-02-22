<?php
/**
 * @file classes/Helpers/ArrayHelper.php
 *
 * @copyright (c) 2021+ TIB Hannover
 * @copyright (c) 2021+ Gazi Yücel
 * @license Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * @class ArrayHelper
 * @brief Provides static helper methods for manipulating arrays.
 */

namespace APP\plugins\generic\citationManager\classes\Helpers;

class ArrayHelper
{
    /**
     * Sets the value of an array from an array containing the path to the keys leading to the value.
     * @param array  &$array The array to manipulate.
     * @param array  $path   An array containing the path to the value, e.g., ['person', 'name', 'value'].
     * @param string $value  The value to be assigned to the specified path.
     */
    public static function setValue(array &$array, array $path, string $value): void
    {
        $key = array_shift($path);
        if (empty($path)) {
            $array[$key] = $value;
        } else {
            self::setValue($array[$key], $path, $value);
        }
    }

    /**
     * Gets an element of an array from an array containing the path to the keys for each dimension.
     * @param array $array The array to retrieve the value from.
     * @param array $path  An array containing the path to the value, e.g., ['person', 'name', 'value'].
     * @return string The value retrieved from the specified path. If the path does not exist, an empty string is returned.
     */
    public static function getValue(array $array, array $path): string
    {
        $key = array_shift($path);

        if (!isset($array[$key])) return '';

        if (empty($path)) {
            return $array[$key];
        } else {
            return self::getValue($array[$key], $path);
        }
    }
}
