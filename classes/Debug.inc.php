<?php
namespace Optimeta\Citations;

class Debug
{
    /**
     * @desc Path to debug file
     * @var string
     */
    private $file = __DIR__ . '/' . '__debug.txt';

    /**
     * @desc Add to debug file
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
     * @desc Get and return contents debug file
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
     * @desc Clear contents debug file
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

    /**
     * @desc Get a list of all the hooks called
     * @return string[]
     */
    function getListHooks()
    {
        return [
            'AccessKeyDAO::_returnAccessKeyFromRow',
            'AcronPlugin::parseCronTab',
            'Announcement::add',
            'Announcement::delete::before',
            'Announcement::delete',
            'Announcement::edit',
            'Announcement::getMany::queryBuilder',
            'Announcement::getMany::queryObject',
            'Announcement::getProperties',
            'Announcement::validate',
            'API::_submissions::params',
            'API::contexts::params',
            'API::emailTemplates::params',
            'API::issues::params',
            'API::stats::editorial::averages::params',
            'API::stats::editorial::params',
            'API::stats::publication::abstract::params',
            'API::stats::publication::galley::params',
            'API::stats::publication::params',
            'API::stats::publications::abstract::params',
            'API::stats::publications::galley::params',
            'API::stats::publications::params',
            'API::stats::users::params',
            'API::submissions::files::params',
            'API::submissions::params',
            'API::submissions::params',
            'API::uploadPublicFile::permissions',
            'API::users::params',
            'API::users::reviewers::params',
            'API::users::user::report::params',
            'API::vocabs::getMany',
            'APIHandler::endpoints',
            'ArticleGalleyDAO::getLocalizedGalleysByArticle',
            'ArticleHandler::download',
            'ArticleHandler::view::galley',
            'ArticleHandler::view',
            'ArticleSearch::getSimilarityTerms',
            'ArticleTombstoneManager::insertArticleTombstone',
            'Author::add',
            'Author::delete::before',
            'Author::delete',
            'Author::edit',
            'Author::getMany::queryBuilder',
            'Author::getMany::queryObject',
            'Author::getProperties::values',
            'Author::validate',
            'CategoryDAO::_fromRow',
            'CitationDAO::afterImportCitations',
            'CitationStyleLanguage::citation',
            'CitationStyleLanguage::citationDownloadDefaults',
            'CitationStyleLanguage::citationStyleDefaults',
            'Common::UserDetails::AdditionalItems',
            'Context::add',
            'Context::defaults::localeParams',
            'Context::delete::before',
            'Context::delete',
            'Context::edit',
            'Context::getContexts::queryObject',
            'Context::getMany::queryBuilder',
            'Context::getProperties',
            'Context::restoreLocaleDefaults::localeParams',
            'Context::validate',
            'crossrefexportplugin::deposited',
            'Dc11SchemaArticleAdapter::extractMetadataFromDataObject',
            'Dispatcher::dispatch',
            'EditorAction::addReviewer',
            'EditorAction::clearReview',
            'EditorAction::modifyDecisionOptions',
            'EditorAction::recordDecision',
            'EditorAction::reinstateReview',
            'EditorAction::setDueDates',
            'EditorialStats::averages',
            'EditorialStats::overview',
            'EmailLogDAO::build',
            'EmailTemplate::add',
            'EmailTemplate::delete::before',
            'EmailTemplate::delete',
            'EmailTemplate::edit',
            'EmailTemplate::getMany::queryBuilder',
            'EmailTemplate::getMany::queryObject::custom',
            'EmailTemplate::getMany::queryObject::default',
            'EmailTemplate::getProperties',
            'EmailTemplate::restoreDefaults',
            'EmailTemplate::validate',
            'EventLogDAO::build',
            'File::adapter',
            'File::download',
            'File::formatFilename',
            'FileManager::deleteFile',
            'FileManager::downloadFile',
            'FileManager::downloadFileFinished',
            'FileManager::downloadFileFinished',
            'Form::config::after',
            'Form::config::before',
            'Galley::add',
            'Galley::delete::before',
            'Galley::delete',
            'Galley::edit',
            'Galley::getMany::queryBuilder',
            'Galley::getMany::queryObject',
            'Galley::getProperties::values',
            'Galley::validate',
            'GenreDAO::_fromRow',
            'GoogleScholarPlugin::references',
            'HtmlArticleGalleyPlugin::articleDownload',
            'HtmlArticleGalleyPlugin::articleDownloadFinished',
            'IndividualSubscriptionDAO::_fromRow',
            'Installer::destroy',
            'Installer::executeInstaller',
            'Installer::Installer',
            'Installer::parseInstaller',
            'Installer::postInstall',
            'Installer::preInstall',
            'Installer::updateVersion',
            'InstitutionalSubscriptionDAO::_fromRow',
            'Issue::getMany::queryBuilder',
            'Issue::getMany::queryObject',
            'Issue::getProperties::fullProperties',
            'Issue::getProperties::summaryProperties',
            'Issue::getProperties::values',
            'IssueAccessForm::execute',
            'IssueAction::subscribedDomain',
            'IssueAction::subscribedUser',
            'IssueAction::subscriptionRequired',
            'IssueDAO::_fromRow',
            'IssueDAO::_returnIssueFromRow',
            'IssueFileDAO::_returnIssueFileFromRow',
            'IssueFileManager::fromTemporaryFile',
            'issueform::execute',
            'IssueGalleyDAO::_fromRow',
            'IssueGalleyDAO::deleteById',
            'IssueGalleyDAO::getById',
            'IssueGalleyDAO::getByPubId',
            'IssueGalleyDAO::getGalleysByIssue',
            'IssueGalleyDAO::insertObject',
            'IssueGridHandler::publishIssue',
            'IssueGridHandler::unpublishIssue',
            'IssueHandler::download',
            'IssueHandler::view::galley',
            'JournalOAI::identifiers',
            'JournalOAI::records',
            'JournalOAI::sets',
            'LensGalleyPlugin::articleDownload',
            'LensGalleyPlugin::articleDownloadFinished',
            'LibraryFileDAO::_fromRow',
            'LinkAction::construct',
            'LoadComponentHandler',
            'LoadHandler',
            'Mail::send',
            'NavigationMenus::displaySettings',
            'NavigationMenus::itemCustomTemplates',
            'NavigationMenus::itemTypes',
            'NoteDAO::_fromRow',
            'NotificationDAO::_fromRow',
            'NotificationManager::getNotificationMessage',
            'OAI::metadataFormats',
            'OAIDAO::_returnIdentifierFromRow',
            'OAIDAO::_returnRecordFromRow',
            'OAIDAO::getJournalSets',
            'PageHandler::compileLess',
            'PageHandler::displayCss',
            'PKPLocale::installLocale',
            'PKPLocale::registerLocaleFile::isValidLocaleFile',
            'PKPLocale::registerLocaleFile',
            'PKPLocale::translate',
            'PluginRegistry::categoryLoaded::themes',
            'PluginRegistry::getCategories',
            'PluginRegistry::loadCategory',
            'Publication::add',
            'Publication::delete::before',
            'Publication::delete',
            'Publication::edit',
            'Publication::getMany::queryBuilder',
            'Publication::getMany::queryObject',
            'Publication::getProperties',
            'Publication::publish::before',
            'Publication::publish',
            'Publication::unpublish::before',
            'Publication::unpublish',
            'Publication::validate',
            'Publication::validatePublish',
            'Publication::version',
            'QueryDAO::_fromRow',
            'Request::getBasePath',
            'Request::getBaseUrl',
            'Request::getCompleteUrl',
            'Request::getIndexUrl',
            'Request::getProtocol',
            'Request::getQueryString',
            'Request::getRemoteAddr',
            'Request::getRemoteDomain',
            'Request::getRequestedJournalPath',
            'Request::getRequestPath',
            'Request::getRequestUrl',
            'Request::getServerHost',
            'Request::getUserAgent',
            'Request::redirect',
            'ReviewerAction::confirmReview',
            'ReviewerSubmissionDAO::_fromRow',
            'ReviewFormDAO::_fromRow',
            'ReviewFormElementDAO::_fromRow',
            'ReviewFormResponseDAO::_returnReviewFormResponseFromRow',
            'Router::getIndexUrl',
            'Router::getRequestedContextPaths',
            'Schema::get::author',
            'Schema::get::publication',
            'Schema::get::submission',
            'Section::getProperties::fullProperties',
            'Section::getProperties::summaryProperties',
            'Section::getProperties::values',
            'SectionDAO::_fromRow',
            'Site::edit',
            'Site::getProperties',
            'Site::validate',
            'SitemapHandler::createJournalSitemap',
            'Stats::editorial::queryBuilder',
            'Stats::editorial::queryObject',
            'Stats::getOrderedObjects::queryBuilder',
            'Stats::getRecords::queryBuilder',
            'Stats::getTimeline::queryBuilder',
            'Stats::queryBuilder',
            'Stats::queryObject',
            'Submission::add',
            'Submission::delete::before',
            'Submission::delete',
            'Submission::edit',
            'Submission::getBackendListProperties::properties',
            'Submission::getMany::queryBuilder',
            'Submission::getMany::queryObject',
            'Submission::getProperties::values',
            'Submission::updateStatus',
            'Submission::validate',
            'SubmissionCommentDAO::_fromRow',
            'SubmissionFile::add',
            'SubmissionFile::assignedFileStages',
            'SubmissionFile::delete::before',
            'SubmissionFile::delete',
            'SubmissionFile::edit',
            'SubmissionFile::fileStages',
            'SubmissionFile::getMany::queryBuilder',
            'SubmissionFile::getMany::queryObject',
            'SubmissionFile::getProperties',
            'SubmissionFile::supportsDependentFiles',
            'SubmissionFile::validate',
            'SubmissionHandler::saveSubmit',
            'SubscriptionDAO::_fromRow',
            'SubscriptionTypeDAO::_fromRow',
            'Template::Announcements',
            'Template::Layout::Backend::HeaderActions',
            'Template::Settings::access',
            'Template::Settings::admin::appearance',
            'Template::Settings::admin::contextSettings::plugins',
            'Template::Settings::admin::contextSettings::setup',
            'Template::Settings::admin::contextSettings',
            'Template::Settings::admin::setup',
            'Template::Settings::admin',
            'Template::Settings::distribution::archiving',
            'Template::Settings::distribution',
            'Template::Settings::website::appearance',
            'Template::Settings::website::plugins',
            'Template::Settings::website::setup',
            'Template::Settings::website',
            'Template::Settings::workflow::emails',
            'Template::Settings::workflow::review',
            'Template::Settings::workflow::submission',
            'Template::Settings::workflow',
            'Template::Workflow::Publication',
            'Template::Workflow::Publication',
            'Template::Workflow',
            'Template::Workflow',
            'TemplateManager::display',
            'TemplateManager::fetch',
            'TemplateManager::setupBackendPage',
            'TemplateResource::getFilename',
            'Templates::Admin::Index::AdminFunctions',
            'Templates::Admin::Index::SiteManagement',
            'Templates::Article::Details::Reference',
            'Templates::Article::Details',
            'Templates::Article::Footer::PageFooter',
            'Templates::Article::Main',
            'Templates::Common::Footer::PageFooter',
            'Templates::Common::Footer::PageFooter',
            'Templates::Common::Footer::PageFooter',
            'Templates::Common::Sidebar',
            'Templates::Controllers::Tab::PubIds::Form::PublicIdentifiersForm',
            'Templates::Editor::Issues::IssueData::AdditionalMetadata',
            'Templates::Index::journal',
            'Templates::Issue::Issue::Article',
            'Templates::Management::Settings::tools',
            'Templates::Manager::Sections::SectionForm::AdditionalMetadata',
            'Templates::Search::SearchResults::AdditionalFilters',
            'Templates::Search::SearchResults::PreResults',
            'Templates::Submission::SubmissionMetadataForm::AdditionalMetadata',
            'TemporaryFileDAO::_returnTemporaryFileFromRow',
            'ThankReviewerForm::thankReviewer',
            'UsageEventPlugin::getUsageEvent',
            'User::getMany::queryBuilder',
            'User::getMany::queryObject',
            'User::getProperties::fullProperties',
            'User::getProperties::reviewerSummaryProperties',
            'User::getProperties::summaryProperties',
            'User::getProperties::values',
            'User::getReport',
            'User::getReviewers::queryBuilder',
            'User::PublicProfile::AdditionalItems',
            'UserAction::mergeUsers',
            'UserDAO::_returnUserFromRow',
            'UserDAO::_returnUserFromRowWithData',
            'UserDAO::_returnUserFromRowWithReviewerStats',
            'UserGroupDAO::_returnFromRow',
            'VersionDAO::_returnVersionFromRow'
        ];
    }

    /**
     * @desc Export a list of hooks called where this method is called
     * @return void
     */
    function calledHooks()
    {
        $hooksList = $this->getListHooks();
        foreach($hooksList as $key){
            \HookRegistry::register($key, function($key){
                $this->Add($key);
            });
        }
    }
}
