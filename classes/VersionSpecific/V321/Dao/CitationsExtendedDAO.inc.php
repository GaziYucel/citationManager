<?php
namespace Optimeta\Citations\VersionSpecific\V321\Dao;

import('lib.pkp.classes.db.DAO');
import('lib.pkp.classes.site.VersionCheck');

use DAO;
use DAOResultFactory;
use VersionCheck;

class CitationsExtendedDao extends \Optimeta\Citations\VersionSpecific\Main\Dao\CitationsExtendedDAO
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
