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

use Config;

class LogHelper
{
    public static $regexes = [
        '/(?<=password":")(.*?)(?=\")/',
        '/(?<=token":")(.*?)(?=\")/',
        '/(?<=token )(.*?)(?=\")/'
    ];

    /**
     * Log an informational message.
     *
     * @param mixed $message The message to be logged.
     * @return bool Returns true on success, false on failure.
     */
    public static function logInfo(mixed $message): bool
    {
        if (empty($message)) $message = '-empty-';

        return self::writeToFile($message, 'INF');
    }

    /**
     * Log an error message.
     *
     * @param mixed $message The error message to be logged.
     * @return bool Returns true on success, false on failure.
     */
    public static function logError(mixed $message): bool
    {
        if (empty($message)) $message = '-empty-';

        return self::writeToFile($message, 'ERR');
    }

    /**
     * Write a message to a log file.
     *
     * @param mixed $message The message to be logged.
     * @param string $level The log level (e.g., 'INF' for informational, 'ERR' for error).
     * @return bool Returns true on success, false on failure.
     */
    private static function writeToFile(mixed $message, string $level): bool
    {
        // path of log file
        $path = Config::getVar('files', 'files_dir');
        $file = strtolower(str_replace(['\\classes\\Helpers', '\\'], ['', '_'], __NAMESPACE__ . '.log'));
        $filePath = $path . DIRECTORY_SEPARATOR . $file;

        // convert message to string
        if (is_object($message) || is_array($message)) {
            $message = json_encode($message, JSON_UNESCAPED_SLASHES);
            if (json_last_error() !== JSON_ERROR_NONE) $message = var_export($message, true);
        }
        $message = str_replace(array("\r", "\n"), ' ', $message);
        $message = preg_replace('!\s+!', ' ', $message);

        // get debug backtrace
        $backTrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);

        // debug information if debug mode enabled
        $debugMessage = '';
        if (\CitationManagerPlugin::isDebugMode) {
            // $debugMessage = '                            ' . json_encode($backTrace, JSON_UNESCAPED_SLASHES) . "\n";
        }

        // message to write
        $message =
            date('Y-m-d H:i:s') . substr(microtime(), 1, 4) . ' ' . $level . ' ' .
            $backTrace[2]['class'] . '\\' . $backTrace[2]['function'] . ': ' . $message . "\n" .
            $debugMessage;

        $message = self::removeSensitive($message);

        return error_log($message, 3, $filePath);
    }

    /**
     * Try to remove sensitive information such as passwords
     * @param string $message
     * @return string
     */
    private static function removeSensitive(string $message): string
    {
        foreach (self::$regexes as $index => $regex) {
            $matches = [];
            preg_match($regex, $message, $matches);
            if (!empty($matches[0]))
                $message = str_replace($matches[0], '*****', $message);
        }

        return $message;
    }
}

