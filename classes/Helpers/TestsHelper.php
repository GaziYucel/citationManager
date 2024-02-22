<?php
/**
 * @file classes/Helpers/TestsHelper.php
 *
 * @copyright (c) 2021+ TIB Hannover
 * @copyright (c) 2021+ Gazi Yücel
 * @license Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * @class TestsHelper
 * @brief Provides static helper methods for tests.
 */

namespace APP\plugins\generic\citationManager\classes\Helpers;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class TestsHelper
{
    public static function overrideClasses(): void
    {
        $classes = [];
        $pluginPath = str_replace(
            DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR . 'Helpers',
            '',
            __DIR__);
        $testDir = $pluginPath . '/tests/classes';
        $prodDir = $pluginPath . '/classes';

        // get files in ./tests/classes dir recursively
        $testFiles = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($testDir),
            RecursiveIteratorIterator::SELF_FIRST);

        // add files to array
        foreach ($testFiles as $path => $object)
            if (str_contains($path, '.php')) {
                $key = str_replace($testDir . DIRECTORY_SEPARATOR, '', $path);
                $classes[$key] = $path;
            }

        // get files in ./classes dir recursively
        $prodFiles = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($prodDir),
            RecursiveIteratorIterator::SELF_FIRST);

        // add files to array if not exists
        foreach ($prodFiles as $path => $object)
            if (str_contains($path, '.php')) {
                $key = str_replace($prodDir . DIRECTORY_SEPARATOR, '', $path);
                if (!array_key_exists($key, $classes)) $classes[$key] = $path;
            }

        // require once all files
        foreach ($classes as $key => $path) {
            require_once($path);

            LogHelper::logInfo(['isTestMode', true]);
        }
    }
}
