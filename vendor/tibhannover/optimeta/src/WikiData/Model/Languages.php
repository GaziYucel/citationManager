<?php

namespace Optimeta\Shared\WikiData\Model;

class Languages
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
    public string $languagesJsonFile = 'Languages.json';

    /**
     * Language mapping OJS <> Wikidata
     * @var array
     */
    public array $languages = [];

    function __construct()
    {
        $this->languages = json_decode(
            file_get_contents(
                realpath(dirname(__FILE__)) . '\\' . $this->languagesJsonFile),
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
