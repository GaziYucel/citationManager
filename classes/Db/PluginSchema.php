<?php
/**
 * @file classes/Db/PluginSchema.php
 *
 * @copyright (c) 2021+ TIB Hannover
 * @copyright (c) 2021+ Gazi Yücel
 * @license Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * @class PluginSchema
 * @brief Plugin Schema
 */

namespace APP\plugins\generic\citationManager\classes\Db;

use APP\plugins\generic\citationManager\CitationManagerPlugin;

class PluginSchema
{
    /**
     * This method adds properties to the schema of a publication.
     *
     * @param string $hookName
     * @param array $args
     * @return bool
     */
    public function addToSchemaPublication(string $hookName, array $args): bool
    {
        $schema = &$args[0];

        $schema->properties->{CitationManagerPlugin::CITATION_MANAGER_CITATIONS_STRUCTURED} = (object)[
            'type' => 'string',
            'multilingual' => false,
            'apiSummary' => true,
            'validation' => ['nullable']
        ];

        $schema->properties->{CitationManagerPlugin::CITATION_MANAGER_METADATA_PUBLICATION} = (object)[
            'type' => 'string',
            'multilingual' => false,
            'apiSummary' => true,
            'validation' => ['nullable']
        ];

        return false;
    }

    /**
     * This method adds properties to the schema of a journal / context.
     *
     * @param string $hookName
     * @param array $args
     * @return bool
     */
    public function addToSchemaContext(string $hookName, array $args): bool
    {
        $schema = &$args[0];

        $schema->properties->{CitationManagerPlugin::CITATION_MANAGER_METADATA_JOURNAL} = (object)[
            'type' => 'string',
            'multilingual' => false,
            'apiSummary' => true,
            'validation' => ['nullable']
        ];

        return false;
    }

    /**
     * This method adds properties to the schema of an author.
     *
     * @param string $hookName
     * @param array $args
     * @return bool
     */
    public function addToSchemaAuthor(string $hookName, array $args): bool
    {
        $schema = &$args[0];

        $schema->properties->{CitationManagerPlugin::CITATION_MANAGER_METADATA_AUTHOR} = (object)[
            'type' => 'string',
            'multilingual' => false,
            'apiSummary' => true,
            'validation' => ['nullable']
        ];

        return false;
    }
}
