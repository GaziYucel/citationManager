<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit205e7fc175dbc92c2ca8148adbc3ad81
{
    public static $prefixLengthsPsr4 = array (
        'O' => 
        array (
            'Optimeta\\Shared\\' => 16,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Optimeta\\Shared\\' => 
        array (
            0 => __DIR__ . '/..' . '/tibhannover/optimeta/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
        'DepositorTask' => __DIR__ . '/../..' . '/classes/ScheduledTasks/DepositorTask.inc.php',
        'Optimeta\\Citations\\Components\\Forms\\PublicationForm' => __DIR__ . '/../..' . '/classes/Components/Forms/PublicationForm.inc.php',
        'Optimeta\\Citations\\Components\\Forms\\SettingsForm' => __DIR__ . '/../..' . '/classes/Components/Forms/SettingsForm.inc.php',
        'Optimeta\\Citations\\Dao\\CitationsExtended' => __DIR__ . '/../..' . '/classes/Dao/CitationsExtended.inc.php',
        'Optimeta\\Citations\\Dao\\CitationsExtendedDAO' => __DIR__ . '/../..' . '/classes/Dao/CitationsExtendedDao.inc.php',
        'Optimeta\\Citations\\Dao\\PluginDAO' => __DIR__ . '/../..' . '/classes/Dao/PluginDao.inc.php',
        'Optimeta\\Citations\\Debug' => __DIR__ . '/../..' . '/classes/Debug.inc.php',
        'Optimeta\\Citations\\Deposit\\Depositor' => __DIR__ . '/../..' . '/classes/Deposit/Depositor.inc.php',
        'Optimeta\\Citations\\Deposit\\OpenCitations' => __DIR__ . '/../..' . '/classes/Deposit/OpenCitations.inc.php',
        'Optimeta\\Citations\\Deposit\\WikiData' => __DIR__ . '/../..' . '/classes/Deposit/WikiData.inc.php',
        'Optimeta\\Citations\\Enrich\\Enricher' => __DIR__ . '/../..' . '/classes/Enrich/Enricher.inc.php',
        'Optimeta\\Citations\\Enrich\\OpenAlex' => __DIR__ . '/../..' . '/classes/Enrich/OpenAlex.inc.php',
        'Optimeta\\Citations\\Enrich\\Orcid' => __DIR__ . '/../..' . '/classes/Enrich/Orcid.php',
        'Optimeta\\Citations\\Enrich\\WikiData' => __DIR__ . '/../..' . '/classes/Enrich/WikiData.inc.php',
        'Optimeta\\Citations\\Handler\\PluginAPIHandler' => __DIR__ . '/../..' . '/classes/Handler/PluginAPIHandler.inc.php',
        'Optimeta\\Citations\\Install\\OptimetaCitationsMigration' => __DIR__ . '/../..' . '/classes/Install/OptimetaCitationsMigration.inc.php',
        'Optimeta\\Citations\\Model\\AuthorModel' => __DIR__ . '/../..' . '/classes/Model/AuthorModel.inc.php',
        'Optimeta\\Citations\\Model\\CitationModel' => __DIR__ . '/../..' . '/classes/Model/CitationModel.inc.php',
        'Optimeta\\Citations\\Model\\WorkModel' => __DIR__ . '/../..' . '/classes/Model/WorkModel.inc.php',
        'Optimeta\\Citations\\Parse\\Parser' => __DIR__ . '/../..' . '/classes/Parse/Parser.inc.php',
        'Optimeta\\Shared\\CrossRef\\CrossRefBase' => __DIR__ . '/..' . '/tibhannover/optimeta/src/CrossRef/CrossRefBase.php',
        'Optimeta\\Shared\\GitHub\\GitHubBase' => __DIR__ . '/..' . '/tibhannover/optimeta/src/GitHub/GitHubBase.php',
        'Optimeta\\Shared\\OpenAlex\\Model\\Author' => __DIR__ . '/..' . '/tibhannover/optimeta/src/OpenAlex/Model/Author.php',
        'Optimeta\\Shared\\OpenAlex\\Model\\Venue' => __DIR__ . '/..' . '/tibhannover/optimeta/src/OpenAlex/Model/Venue.php',
        'Optimeta\\Shared\\OpenAlex\\Model\\Work' => __DIR__ . '/..' . '/tibhannover/optimeta/src/OpenAlex/Model/Work.php',
        'Optimeta\\Shared\\OpenAlex\\OpenAlexBase' => __DIR__ . '/..' . '/tibhannover/optimeta/src/OpenAlex/OpenAlexBase.php',
        'Optimeta\\Shared\\OpenCitations\\Model\\WorkCitation' => __DIR__ . '/..' . '/tibhannover/optimeta/src/OpenCitations/Model/WorkCitation.php',
        'Optimeta\\Shared\\OpenCitations\\Model\\WorkMetaData' => __DIR__ . '/..' . '/tibhannover/optimeta/src/OpenCitations/Model/WorkMetaData.php',
        'Optimeta\\Shared\\OpenCitations\\OpenCitationsBase' => __DIR__ . '/..' . '/tibhannover/optimeta/src/OpenCitations/OpenCitationsBase.php',
        'Optimeta\\Shared\\Orcid\\Model\\Author' => __DIR__ . '/..' . '/tibhannover/optimeta/src/Orcid/Model/Author.php',
        'Optimeta\\Shared\\Orcid\\OrcidBase' => __DIR__ . '/..' . '/tibhannover/optimeta/src/Orcid/OrcidBase.php',
        'Optimeta\\Shared\\Pid\\Arxiv' => __DIR__ . '/..' . '/tibhannover/optimeta/src/Pid/Arxiv.php',
        'Optimeta\\Shared\\Pid\\Doi' => __DIR__ . '/..' . '/tibhannover/optimeta/src/Pid/Doi.php',
        'Optimeta\\Shared\\Pid\\Handle' => __DIR__ . '/..' . '/tibhannover/optimeta/src/Pid/Handle.php',
        'Optimeta\\Shared\\Pid\\Orcid' => __DIR__ . '/..' . '/tibhannover/optimeta/src/Pid/Orcid.php',
        'Optimeta\\Shared\\Pid\\Ror' => __DIR__ . '/..' . '/tibhannover/optimeta/src/Pid/Ror.php',
        'Optimeta\\Shared\\Pid\\Url' => __DIR__ . '/..' . '/tibhannover/optimeta/src/Pid/Url.php',
        'Optimeta\\Shared\\Pid\\Urn' => __DIR__ . '/..' . '/tibhannover/optimeta/src/Pid/Urn.php',
        'Optimeta\\Shared\\WikiData\\Api' => __DIR__ . '/..' . '/tibhannover/optimeta/src/WikiData/Api.php',
        'Optimeta\\Shared\\WikiData\\Model\\Article' => __DIR__ . '/..' . '/tibhannover/optimeta/src/WikiData/Model/Article.php',
        'Optimeta\\Shared\\WikiData\\Model\\Author' => __DIR__ . '/..' . '/tibhannover/optimeta/src/WikiData/Model/Author.php',
        'Optimeta\\Shared\\WikiData\\Model\\Item' => __DIR__ . '/..' . '/tibhannover/optimeta/src/WikiData/Model/Item.php',
        'Optimeta\\Shared\\WikiData\\Model\\Languages' => __DIR__ . '/..' . '/tibhannover/optimeta/src/WikiData/Model/Languages.php',
        'Optimeta\\Shared\\WikiData\\Model\\Property' => __DIR__ . '/..' . '/tibhannover/optimeta/src/WikiData/Model/Property.php',
        'Optimeta\\Shared\\WikiData\\WikiDataBase' => __DIR__ . '/..' . '/tibhannover/optimeta/src/WikiData/WikiDataBase.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit205e7fc175dbc92c2ca8148adbc3ad81::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit205e7fc175dbc92c2ca8148adbc3ad81::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit205e7fc175dbc92c2ca8148adbc3ad81::$classMap;

        }, null, ClassLoader::class);
    }
}
