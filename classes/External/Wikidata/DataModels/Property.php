<?php
/**
 * @file classes/External/Wikidata/DataModels/Property.php
 *
 * @copyright (c) 2021+ TIB Hannover
 * @copyright (c) 2021+ Gazi Yücel
 * @license Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * @class Property
 * @brief Properties on Wikidata.
 * @see https://hay.toolforge.org/propbrowse
 */

namespace APP\plugins\generic\citationManager\classes\External\Wikidata\DataModels;

class Property
{
    public array $instanceOfScientificJournal = ['id' => 'P31', 'default' => 'Q5633421'];
    public array $instanceOfScientificArticle = ['id' => 'P31', 'default' => 'Q13442814'];
    public array $instanceOfHuman = ['id' => 'P31', 'default' => 'Q5'];
    public array $title = ['id' => 'P1476', 'default' => ''];
    public array $author = ['id' => 'P50', 'default' => ''];
    public array $publicationDate = ['id' => 'P577', 'default' => ''];
    public array $publishedIn = ['id' => 'P1433', 'default' => ''];
    public array $volume = ['id' => 'P478', 'default' => ''];
    public array $citesWork = ['id' => 'P2860', 'default' => ''];
    public array $doi = ['id' => 'P356', 'default' => ''];
    public array $orcidId = ['id' => 'P496', 'default' => ''];
    public array $issnL = ['id' => 'P7363', 'default' => ''];
}
