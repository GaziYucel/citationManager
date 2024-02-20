<?php
/**
 * @file classes/Helpers/LogHelper.php
 *
 * @copyright (c) 2021+ TIB Hannover
 * @copyright (c) 2021+ Gazi Yücel
 * @license Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * @class LogHelper
 * @brief Provides static helper methods for logging messages.
 */

namespace APP\plugins\generic\citationManager\classes\Helpers;

use PKP\config\Config;

class LogHelper
{
    /**
     * Log an informational message.
     * @param mixed $message The message to be logged.
     * @return bool Returns true on success, false on failure.
     */
    public static function logInfo(mixed $message): bool
    {
        if (empty($message)) $message = '-empty-';

        return self::writeToFile(self::convertToString($message), 'INF');
    }

    /**
     * Log an error message.
     * @param mixed $message The error message to be logged.
     * @return bool Returns true on success, false on failure.
     */
    public static function logError(mixed $message): bool
    {
        if (empty($message)) $message = '-empty-';

        return self::writeToFile(self::convertToString($message), 'ERR');
    }

    /**
     * Convert an object / array message to a string.
     * @param mixed $message The message to be converted to a string.
     * @return string The converted message as a string.
     */
    private static function convertToString(mixed $message): string
    {
        if (is_object($message) || is_array($message)) {
            $message = json_encode($message, JSON_UNESCAPED_SLASHES);
            if (json_last_error() !== JSON_ERROR_NONE) $message = var_export($message, true);
        }

        $message = str_replace(array("\r", "\n"), ' ', $message);

        return preg_replace('!\s+!', ' ', $message);
    }

    /**
     * Write a message to a log file.
     * @param string $message The message to be logged.
     * @param string $level The log level (e.g., 'INF' for informational, 'ERR' for error).
     * @return bool Returns true on success, false on failure.
     */
    private static function writeToFile(string $message, string $level): bool
    {
        $path = Config::getVar('files', 'files_dir');
        $file = strtolower(str_replace(['\\classes\\Helpers', '\\'], ['', '_'], __NAMESPACE__ . '.log'));

        $backTrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 3);
        $calling = str_replace('classes' . DIRECTORY_SEPARATOR . 'Helpers', '', __DIR__);
        $calling = str_replace([$calling, '.php'], '', $backTrace[1]['file'] . '/' . $backTrace[2]['function']);

        $message = date('Y-m-d H:i:s') . substr(microtime(), 1, 4) . ' ' . $level . ' ' .
            $calling . ': ' . $message . "\n";

        return error_log($message, 3, $path . DIRECTORY_SEPARATOR . $file);
    }
}
