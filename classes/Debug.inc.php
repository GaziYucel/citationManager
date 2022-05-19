<?php
namespace Optimeta\Citations;

use Exception;

class Debug
{
    /**
     * Path to debug file
     *
     * @var string
     */
    private $file = __DIR__ . '/' . '__debug.txt';

    /**
     * Add to debug file
     *
     * @param $text
     * @return void
     */
    function Add($text = null): void
    {
        $textToWrite = $text;
        if (is_object($text) || is_array($text)) $textToWrite = var_export($text, true);
        if ($text == null) $textToWrite = 'null';

        $fp = fopen($this->file, 'a');
        try {
            $date = new \DateTime();
            fwrite($fp, $date->format('Y-m-d H:i:s') . ' ' . $textToWrite . "\n");
        }
        catch (\Exception $ex) {
        }
        finally {
            if ($fp) fclose($fp);
        }
    }

    /**
     * Get and return contents debug file
     *
     * @return string
     */
    function Get(): string
    {
        if (file_exists($this->file)) {
            return file_get_contents($this->file);
        }
        return '';
    }

    /**
     * Clear contents debug file
     *
     * @return void
     */
    function Clear(): void
    {
        $fp = fopen($this->file, 'w');
        try {
            fwrite($fp, '');
        }
        catch (\Exception $ex) {
        }
        finally {
            if ($fp) fclose($fp);
        }
    }
}
