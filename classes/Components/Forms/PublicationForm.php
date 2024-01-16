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

namespace APP\plugins\generic\optimetaCitations\classes\Components\Forms;

use APP\plugins\generic\optimetaCitations\OptimetaCitationsPlugin;
use APP\publication\Publication;
use PKP\components\forms\FormComponent;
use PKP\components\forms\FieldText;

class PublicationForm extends FormComponent
{
    /**
     * @var OptimetaCitationsPlugin
     */
    public OptimetaCitationsPlugin $plugin;

    /** @copydoc FormComponent::$successMessage */
    public string $successMessage = '';

    /**
     * Constructor
     *
     * @param string $id Unique id of this form
     * @param string $action URL to submit the form to
     * @param string $method Method used
     * @param array $locales Locales of this context
     * @param Publication $publication The publication to change settings for
     * @param string $successMessage Message which will be shown if successful
     * @param OptimetaCitationsPlugin $plugin
     */
    public function __construct(string $id, string $method, string $action, array $locales,
                                Publication $publication,
                                string $successMessage,
                                OptimetaCitationsPlugin $plugin)
    {
        parent::__construct($id, $method, $action, $locales);

        $this->successMessage = $successMessage;

        $this->plugin = $plugin;

        $this->addField(new FieldText(
            $this->plugin::OPTIMETA_CITATIONS_FORM_FIELD_PARSED, [
            'label' => '',
            'description' => '',
            'isMultilingual' => false,
            'value' => $this->plugin->pluginDao->getCitations($publication)
        ]));

        $this->addField(new FieldText(
            $this->plugin::OPTIMETA_CITATIONS_PUBLICATION_WORK, [
            'label' => '',
            'description' => '',
            'isMultilingual' => false,
            'value' => $publication->getData($this->plugin::OPTIMETA_CITATIONS_PUBLICATION_WORK)
        ]));
    }
}
