<?php
/**
 * @file plugins/generic/optimetaCitations/classes/Helpers/LogHelper.php
 *
 * Copyright (c) 2021+ TIB Hannover
 * Copyright (c) 2021+ Gazi Yucel
 * Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * @class LogHelper
 * @ingroup plugins_generic_optimetacitations
 *
 * @brief Logging helper class
 */

namespace APP\plugins\generic\optimetaCitations\classes\Helpers;

use PKP\config\Config;

class LogHelper
{
    /**
     * Log notice
     *
     * @param string $message
     * @return bool
     */
    public static function logInfo(string $message): bool
    {
        return self::writeToFile($message, 'INF');
    }

    /**
     * Log error
     *
     * @param string $message
     * @return bool
     */
    public static function logError(string $message): bool
    {
        return self::writeToFile($message, 'ERR');
    }

    /**
     * Write to file
     *
     * @param string $message
     * @param string $level
     * @return bool
     */
    private static function writeToFile(string $message, string $level): bool
    {
        $fineStamp =
            date('Y-m-d H:i:s') .
            substr(microtime(), 1, 4);

        $file = Config::getVar('files', 'files_dir') . '/' .
            strtolower(
                str_replace(
                    ['\\classes\\Helpers', '\\'],
                    ['', '_'],
                    __NAMESPACE__ . '.log'
                )
            );

        return error_log(
            $fineStamp . ' ' . $level . ' ' . str_replace(array("\r", "\n"), ' ', $message) . "\n",
            3,
            $file);
    }
}
