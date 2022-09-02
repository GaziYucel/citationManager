<?php
namespace Optimeta\Citations\Dao;

import('lib.pkp.classes.db.DAO');
import('plugins.generic.optimetaCitations.classes.Dao.CitationsExtended');
import('lib.pkp.classes.site.VersionCheck');

import('plugins.generic.optimetaCitations.classes.Dao.CitationsExtendedDAOBase');

use DAO;
use DAOResultFactory;
use VersionCheck;

class CitationsExtendedDAOv321 extends CitationsExtendedDAOBase
{
    /**
     * Get parsed citations by Publication ID
     * @param $publicationId int Publication ID
     * @return array
     */
    function getParsedCitationsByPublicationId($publicationId) {
        $citationsParsed = '';

        $result = $this->retrieve(
            'SELECT * FROM citations_extended WHERE publication_id = ?',
            [(int) $publicationId]
        );

        $returner = null;
        if ($result->RecordCount() != 0) {
            $returner = $this->_fromRow($result->GetRowAssoc(false));
        }
        $result->Close();

        if(!empty($returner)) {
            $citationsParsed = $returner->getParsedCitations(); }

        return $citationsParsed;
    }
}
