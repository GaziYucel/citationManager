<?php
/**
 * @file plugins/generic/optimetaCitations/classes/DataModels/CitationModel.php
 *
 * Copyright (c) 2021+ TIB Hannover
 * Copyright (c) 2021+ Gazi Yucel
 * Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * @class CitationModel
 * @ingroup plugins_generic_optimetacitations
 *
 * @brief Citations are scholarly documents like journal articles, books, datasets, and theses.
 */

namespace APP\plugins\generic\optimetaCitations\classes\DataModels;

class CitationModel extends WorkModel
{
    /**
     * The unchanged raw citation
     *
     * @var ?string
     */
    public ?string $raw = null;
}
