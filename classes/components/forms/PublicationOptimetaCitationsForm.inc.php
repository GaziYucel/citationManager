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

use \PKP\components\forms\FormComponent;
use \PKP\components\forms\FieldText;

define("FORM_PUBLICATION_OPTIMETA_CITATIONS", "optimetaCitationsForm");

class PublicationOptimetaCitationsForm extends FormComponent {
	/** @copydoc FormComponent::$id */
	public $id = FORM_PUBLICATION_OPTIMETA_CITATIONS;

	/** @copydoc FormComponent::$method */
	public $method = 'PUT';

    private $citationsKeyDb   = 'optimetaCitations__parsedCitations';
    private $citationsKeyForm = 'optimetaCitations__parsedCitations';

	/**
	 * Constructor
	 *
     * @param $action string URL to submit the form to
     * @param $publication Publication The publication to change settings for
	 */
	public function __construct($action, $publication) {
        $this->action = $action;
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
