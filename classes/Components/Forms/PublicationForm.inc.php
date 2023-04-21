<?php
/**
 * @file plugins/generic/optimetaCitations/classes/components/forms/PublicationForm.php
 *
 * Copyright (c) 2021+ TIB Hannover
 * Copyright (c) 2021+ Gazi Yucel
 * Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * @class PublicationForm
 * @ingroup plugins_generic_optimetacitations
 *
 * @brief A preset form for setting a publication's parsed citations
 */

namespace Optimeta\Citations\Components\Forms;

use Optimeta\Citations\Dao\PluginDAO;
use \PKP\components\forms\FormComponent;
use \PKP\components\forms\FieldText;
use Publication;

class PublicationForm extends FormComponent
{
    /** @copydoc FormComponent::$id */
    public $id = OPTIMETA_CITATIONS_FORM_NAME;

    /** @copydoc FormComponent::$method */
    public $method = 'PUT';

    /** @copydoc FormComponent::$action */
    public $action = '';

    /** @copydoc FormComponent::$successMessage */
    public $successMessage = '';

    /**
     * Constructor
     *
     * @param $action string URL to submit the form to
     * @param $publication Publication The publication to change settings for
     */
    public function __construct($action, $publication, $successMessage)
    {
        $this->action = $action;
        $this->successMessage = $successMessage;

        $pluginDAO = new PluginDAO();

        $this->addField(new FieldText(
            OPTIMETA_CITATIONS_FORM_FIELD_PARSED, [
            'label' => '',
            'description' => '',
            'isMultilingual' => false,
            'value' => $pluginDAO->getCitations($publication)
        ]));

        $this->addField(new FieldText(
            OPTIMETA_CITATIONS_PUBLICATION_WORK, [
            'label' => '',
            'description' => '',
            'isMultilingual' => false,
            'value' => $publication->getData(OPTIMETA_CITATIONS_PUBLICATION_WORK)
        ]));
    }
}
