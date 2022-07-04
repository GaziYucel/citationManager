<?php
/**
 * @file plugins/generic/optimetaCitations/classes/SettingsForm.inc.php
 *
 * Copyright (c) 2021+ TIB Hannover
 * Copyright (c) 2021+ Gazi Yucel
 * Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * @class SettingsForm
 * @ingroup plugins_generic_optimetacitations
 *
 * @brief Form for journal managers to setup the OptimetaCitations plugin
 */

namespace Optimeta\Citations;

import('lib.pkp.classes.form.Form');

use FormValidator;
use FormValidatorPost;
use FormValidatorCSRF;
use Application;
use TemplateManager;
use NotificationManager;

class SettingsForm extends \Form
{
    /**
     * @var $plugin OptimetaCitationsPlugin
     */
    public $plugin;

    /**
     * @desc Array of variables saved in the database
     * @var string[]
     */
    private $settings = [
        'optimetaCitations_wikidata_username',
        'optimetaCitations_wikidata_password',
        'optimetaCitations_wikidata_api_url'
    ];

    /**
     * @copydoc Form::__construct()
     */
    public function __construct($plugin) {

        // Define the settings template and store a copy of the plugin object
        parent::__construct($plugin->getTemplateResource('settings.tpl'));
        $this->plugin = $plugin;

        // Always add POST and CSRF validation to secure your form.
        $this->addCheck(new FormValidatorPost($this));
        $this->addCheck(new FormValidatorCSRF($this));
    }

    /**
     * @copydoc Form::initData()
     * Load settings already saved in the database
     * Settings are stored by context, so that each journal or press
     * can have different settings.
     */
    public function initData() {
        $context = Application::get()->getRequest()->getContext();
        $contextId = $context ? $context->getId() : CONTEXT_SITE;
        foreach($this->settings as $key){
            $this->setData($key, $this->plugin->getSetting($contextId, $key));
        }

        parent::initData();
    }

    /**
     * @copydoc Form::readInputData()
     * Load data that was submitted with the form
     */
    public function readInputData() {
        foreach($this->settings as $key){
            $this->readUserVars([$key]);
        }
        parent::readInputData();
    }

    /**
     * @copydoc Form::fetch()
     * Fetch any additional data needed for your form.
     * Data assigned to the form using $this->setData() during the
     * initData() or readInputData() methods will be passed to the
     * template.
     */
    public function fetch($request, $template = null, $display = false) {
        // Pass the plugin name to the template so that it can be
        // used in the URL that the form is submitted to
        $templateMgr = TemplateManager::getManager($request);
        $templateMgr->assign('pluginName', $this->plugin->getName());

        return parent::fetch($request, $template, $display);
    }

    /**
     * @copydoc Form::execute()
     * Save the settings
     *
     * @return null|mixed
     */
    public function execute(...$functionArgs) {
        $context = Application::get()->getRequest()->getContext();
        $contextId = $context ? $context->getId() : CONTEXT_SITE;

        foreach($this->settings as $key){
            $this->plugin->updateSetting( $contextId, $key, $this->getData($key));
        }

        // Tell the user that the save was successful.
        import('classes.notification.NotificationManager');
        $notificationMgr = new NotificationManager();
        $notificationMgr->createTrivialNotification(
            Application::get()->getRequest()->getUser()->getId(),
            NOTIFICATION_TYPE_SUCCESS,
            ['contents' => __('common.changesSaved')]
        );

        return parent::execute();
    }
}