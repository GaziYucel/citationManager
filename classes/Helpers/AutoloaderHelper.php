<?php
/**
 * @file classes/Helpers/AutoloaderHelper.php
 *
 * @copyright (c) 2021+ TIB Hannover
 * @copyright (c) 2021+ Gazi Yücel
 * @license Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * @class AutoloaderHelper
 * @brief Provides static helper methods for autoload of classes.
 */

namespace APP\plugins\generic\citationManager\classes\Helpers;

class AutoloaderHelper
{
    public static function register()
    {
        spl_autoload_register(function ($class) {
            echo "<!-- Autoloader::register::spl_autoload_register::class: $class -->";

            $file = str_replace('\\', DIRECTORY_SEPARATOR, $class) . '.php';

            echo "<!-- Autoloader::register::spl_autoload_register::file: $file -->";

            if (file_exists(__DIR__ . '/' . $file)) {
                require $file;
                return true;
            }
            return false;
        });
    }
}

AutoloaderHelper::register();
