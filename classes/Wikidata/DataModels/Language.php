<?php
/**
 * @file plugins/generic/optimetaCitations/Wikidata/Model/Language.php
 *
 * Copyright (c) 2021+ TIB Hannover
 * Copyright (c) 2021+ Gazi Yucel
 * Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * @class Language
 * @ingroup plugins_generic_optimetacitations
 *
 * @brief Language on Wikidata.
 */

namespace APP\plugins\generic\optimetaCitations\classes\Wikidata\DataModels;

class Language
{
    /**
     * Default language if none found
     * @var string
     */
    public string $defaultLanguage = 'en';

    /**
     * Language mapping file
     * @var string
     */
    public string $languagesJsonFile = 'Language.json';

    /**
     * Language mapping OJS <> Wikidata
     * @var array
     */
    public array $languages = [];

    function __construct()
    {
        $this->languages = json_decode(
            file_get_contents(
                realpath(dirname(__FILE__)) . DIRECTORY_SEPARATOR . $this->languagesJsonFile),
            true);
    }

    public function getLanguageCode(string $locale): string
    {
        if (!empty($locale) &&
            array_key_exists(strtolower($locale), $this->languages))
            return $this->languages[strtolower($locale)];

        return $this->defaultLanguage;
    }
}
