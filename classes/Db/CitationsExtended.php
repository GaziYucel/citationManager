<?php
/**
 * @file plugins/generic/optimetaCitations/Dao/CitationsExtended.php
 *
 * Copyright (c) 2021+ TIB Hannover
 * Copyright (c) 2021+ Gazi Yucel
 * Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * @class CitationsExtended
 * @ingroup plugins_generic_optimetacitations
 *
 * @brief DataObject  for get/set PublicationId and ParsedCitations
 */

namespace APP\plugins\generic\optimetaCitations\classes\Db;

use PKP\core\DataObject;

class CitationsExtended extends DataObject
{
    /**
     * Get Publication ID
     *
     * @return int
     */
    function getPublicationId(): int
    {
        return $this->getData('publicationId');
    }

    /**
     * Set Publication ID
     *
     * @param int $publicationId
     * @return void
     */
    function setPublicationId(int $publicationId): void
    {
        $this->setData('publicationId', $publicationId);
    }

    /**
     * Get ParsedCitations
     *
     * @return ?string
     */
    function getParsedCitations(): ?string
    {
        return $this->getData('parsedCitations');
    }

    /**
     * Set ParsedCitations
     *
     * @param string $parsedCitations
     * @return void
     */
    function setParsedCitations(string $parsedCitations): void
    {
        $this->setData('parsedCitations', $parsedCitations);
    }
}