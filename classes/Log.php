<?php
/**
 * @file plugins/generic/optimetaCitations/classes/Log.php
 *
 * Copyright (c) 2021+ TIB Hannover
 * Copyright (c) 2021+ Gazi Yucel
 * Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * @class Log
 * @ingroup plugins_generic_optimetacitations
 *
 * @brief Logging helper class
 */

namespace APP\plugins\generic\optimetaCitations\classes;

use PKP\config\Config;

class Log
{
    /**
     * Path to file
     *
     * @var string
     */
    private static string $file = OPTIMETA_CITATIONS_PLUGIN_NAME . '.log';

    /**
     * Is the class initialized
     * @var bool
     */
    private static bool $initialized = false;

    /**
     * Private prevents instantiating this class
     */
    private function __construct() { }

    /**
     * Initialize class
     *
     * @return void
     */
    private static function initialize(): void
    {
        if (self::$initialized) return;

        self::$file = Config::getVar('files', 'files_dir') . '/' . self::$file;

        self::$initialized = true;
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
        self::initialize();

        $fineStamp =
            date('Y-m-d H:i:s') .
            substr(microtime(), 1, 4);

        return error_log(
            $fineStamp . ' ' . $level . ' ' . str_replace(array("\r", "\n"), ' ', $message) . "\n",
            3,
            self::$file);
    }

    /**
     * Log notice
     *
     * @param string $message
     * @return bool
     */
    public static function logInfo(string $message): bool
    {
        return self::writeToFile($message, 'INFO');
    }

    /**
     * Log error
     *
     * @param string $message
     * @return bool
     */
    public static function logError(string $message): bool
    {
        return self::writeToFile($message, 'ERROR');
    }
}
