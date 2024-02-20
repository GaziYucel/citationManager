<?php
/**
 * @file classes/External/GitHub/DataModels/Issue.php
 *
 * @copyright (c) 2021+ TIB Hannover
 * @copyright (c) 2021+ Gazi Yücel
 * @license Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * @class Issue
 * @brief GitHub Issue
 */

namespace APP\plugins\generic\citationManager\classes\External\GitHub\DataModels;

class Issue
{
    /** @var string Title of the issue */
    public string $title = '';

    /** @var string Body of the issue */
    public string $body = '';

    /** @var array Labels of the issue */
    public array $labels = [];
}