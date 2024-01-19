<?php
/**
 * @file plugins/generic/optimetaCitations/classes/Debug.php
 *
 * Copyright (c) 2021+ TIB Hannover
 * Copyright (c) 2021+ Gazi Yucel
 * Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * @class Debug
 * @ingroup plugins_generic_optimetacitations
 *
 * @brief Debug helper class
 */

namespace APP\plugins\generic\optimetaCitations\classes;

use PKP\config\Config;
use APP\plugins\generic\optimetaCitations\OptimetaCitationsPlugin;

class Log
{
    /**
     * Path to file
     *
     * @var string
     */
    private static string $file;

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
    private static function initialize():void
    {
        if (self::$initialized) return;

        self::$file =
            Config::getVar('files', 'files_dir') . '/' .
            OptimetaCitationsPlugin::OPTIMETA_CITATIONS_PLUGIN_NAME . '.txt';

        self::$initialized = true;
    }

    /**
     * Add to file
     *
     * @param string $text
     * @return bool
     */
    public static function Add(string $text = ''): bool
    {
        self::initialize();

        return file_put_contents(
            self::$file,
            date('Y-m-d H:i:s') . ' ' . str_replace(array("\r", "\n"), ' ', $text) . "\n",
            FILE_APPEND | LOCK_EX);
    }

    /**
     * Get and return contents file
     *
     * @return string
     */
    public static function Get(): string
    {
        self::initialize();

        if (file_exists(self::$file)) {
            return file_get_contents(self::$file);
        }

        return '';
    }

    /**
     * Clear contents file
     *
     * @return bool
     */
    public static function Clear(): bool
    {
        self::initialize();

        return file_put_contents(
            self::$file,
            '',
            LOCK_EX);
    }
}
