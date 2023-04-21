<?php
/**
 * @file plugins/generic/optimetaCitations/vendor/tibhannover/optimeta/src/WikiData/Model/Item.php
 *
 * Copyright (c) 2021+ TIB Hannover
 * Copyright (c) 2021+ Gazi Yucel
 * Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * @class Item
 * @ingroup plugins_generic_optimetacitations
 *
 * @brief Item on WikiData.
 */

namespace Optimeta\Shared\WikiData\Model;

class Item
{
    /**
     * ID of the item
     * @var string
     */
    public string $id;

    /**
     * Title of the item
     * @var string
     */
    public string $title;

    /**
     * Type of the item
     * @var string
     */
    public string $type = 'item';

    function __construct()
    {

    }
}
