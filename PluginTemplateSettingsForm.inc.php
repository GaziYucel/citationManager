<?php
import('lib.pkp.classes.form.Form');
class PluginTemplateSettingsForm extends Form {

	/** @var PluginTemplatePlugin  */
	public $plugin;

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
	 * Load settings already saved in the database
	 *
	 * Settings are stored by context, so that each journal or press
	 * can have different settings.
	 */
	public function initData() {
		$context = Application::get()->getRequest()->getContext();
		$contextId = $context ? $context->getId() : CONTEXT_SITE;
		$this->setData('publicationStatement', $this->plugin->getSetting($contextId, 'publicationStatement'));
		parent::initData();
	}

	/**
	 * Load data that was submitted with the form
	 */
	public function readInputData() {
		$this->readUserVars(['publicationStatement']);
		parent::readInputData();
	}

	/**
	 * Fetch any additional data needed for your form.
	 *
	 * Data assigned to the form using $this->setData() during the
	 * initData() or readInputData() methods will be passed to the
	 * template.
	 *
	 * @return string
	 */
	public function fetch($request, $template = null, $display = false) {

		// Pass the plugin name to the template so that it can be
		// used in the URL that the form is submitted to
		$templateMgr = TemplateManager::getManager($request);
		$templateMgr->assign('pluginName', $this->plugin->getName());

		return parent::fetch($request, $template, $display);
	}

	/**
	 * Save the settings
	 *
	 * @return null|mixed
	 */
	public function execute() {
		$context = Application::get()->getRequest()->getContext();
		$contextId = $context ? $context->getId() : CONTEXT_SITE;
		$this->plugin->updateSetting($contextId, 'publicationStatement', $this->getData('publicationStatement'));

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
