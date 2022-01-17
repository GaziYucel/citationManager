<?php
/**
 * @file plugins/generic/optimetaCitations/classes/components/forms/PublicationOptimetaCitationsForm.inc.php
 *
 * Copyright (c) 2021+ TIB Hannover
 * Copyright (c) 2021+ Gazi Yucel
 * Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * @class PublicationOptimetaCitationsForm
 * @ingroup plugins_generic_optimetacitations
 *
 * @brief A preset form for setting a publication's parsed citations
 */

include_once($_SERVER["DOCUMENT_ROOT"] . '/plugins/generic/optimetaCitations/params.inc.php');

use \PKP\components\forms\FormComponent;
use \PKP\components\forms\FieldText;

define("OPTIMETA_CITATIONS_PUBLICATION_FORM", OptimetaCitationsPublicationFormName);

class PublicationOptimetaCitationsForm extends FormComponent {
	/** @copydoc FormComponent::$id */
	public $id = OPTIMETA_CITATIONS_PUBLICATION_FORM;

	/** @copydoc FormComponent::$method */
	public $method = 'PUT';

	/** @copydoc FormComponent::$action */
	public $action = '';

	/** @copydoc FormComponent::$successMessage */
	public $successMessage = '';

    private $citationsKeyDb   = OptimetaCitationsParsedKeyDb;
    private $citationsKeyForm = OptimetaCitationsParsedKeyDb;

	/**
	 * Constructor
	 *
     * @param $action string URL to submit the form to
     * @param $publication Publication The publication to change settings for
	 */
	public function __construct($action, $publication, $successMessage) {
        $this->action = $action;
        $this->successMessage = $successMessage;

        $value = $publication->getData($this->citationsKeyDb);

        $this->addField(new FieldText(
            $this->citationsKeyForm, [
            'label' => '',
            'description' => '',
            'isMultilingual' => false,
            'value' => $value ]
        ));

	}
}
