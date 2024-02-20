<?php
/**
 * @file classes/Helpers/StringHelper.php
 *
 * @copyright (c) 2021+ TIB Hannover
 * @copyright (c) 2021+ Gazi Yücel
 * @license Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * @class StringHelper
 * @brief Provides static helper methods for manipulating strings.
 */

namespace APP\plugins\generic\citationManager\classes\Helpers;

class StringHelper
{
    /**
     * Trim specified characters from the beginning and end of a string.
     * @param string $string      The string to be trimmed.
     * @param string $characters  Characters to be trimmed (default is an empty string).
     * @return string The trimmed string.
     */
    public static function trim(string $string, string $characters = ''): string
    {
        if(empty($string)) return '';

        return trim($string, $characters);
    }

    /**
     * Strip slashes from a string.
     * @param string $string The string from which slashes will be stripped.
     * @return string The string with slashes stripped.
     */
    public static function stripSlashes(string $string): string
    {
        if(empty($string)) return '';

        return stripslashes($string);
    }

    /**
     * Remove number from the beginning of a string.
     * @param string $string The string from which to remove the number prefix.
     * @return string The string with the number prefix removed.
     */
    public static function removeNumberPrefixFromString(string $string): string
    {
        if(empty($string)) return '';

        return preg_replace(
            '/^\s*[\[#]?[0-9]+[.)\]]?\s*/',
            '',
            $string);
    }

    /**
     * Normalize whitespace in a string.
     * @param string $string The string in which to normalize whitespace.
     * @return string The string with normalized whitespace.
     */
    public static function normalizeWhiteSpace(string $string): string
    {
        if(empty($string)) return '';

        return preg_replace(
            '/[\s]+/',
            ' ',
            $string);
    }

    /**
     * Normalize line endings in a string.
     * @param string $string The string in which to normalize line endings.
     * @return string The string with normalized line endings.
     */
    public static function normalizeLineEndings(string $string): string
    {
        if(empty($string)) return '';

        return preg_replace(
            '/[\r\n]+/s',
            "\n",
            $string);
    }
}
