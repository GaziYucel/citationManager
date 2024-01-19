<?php
/**
 * @file plugins/generic/optimetaCitations/classes/Db/PluginMigration.php
 *
 * Copyright (c) 2021+ TIB Hannover
 * Copyright (c) 2021+ Gazi Yucel
 * Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * @class PluginMigration
 * @ingroup plugins_generic_optimetacitations
 *
 * @brief PluginMigration
 */

namespace APP\plugins\generic\optimetaCitations\classes\Db;

use APP\core\Application;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Capsule\Manager;
use APP\plugins\generic\optimetaCitations\OptimetaCitationsPlugin;

class PluginMigration extends Migration
{
    private OptimetaCitationsPlugin $plugin;

    public function __construct(OptimetaCitationsPlugin $plugin)
    {
        $this->plugin = $plugin;
    }

    /**
     * Run the migrations
     *
     * @return void
     */
    public function up(): void
    {
        $this->createCitationsExtended();
    }

    /**
     * Create citations_extended table if not exists
     *
     * @return void
     */
    public function createCitationsExtendedIfNotExists(): void
    {
        if (!Schema::hasTable('citations_extended')) {
            $this->createCitationsExtended();
        }
    }

    /**
     * Create citations_extended table
     *
     * @return void
     */
    private function createCitationsExtended(): void
    {
        Schema::create('citations_extended', function (Blueprint $table) {
            $table->bigInteger('citations_extended_id')->nullable(0)->autoIncrement();
            $table->bigInteger('publication_id')->nullable(1);
            $table->longText('parsed_citations')->nullable(1);

            $table->index('publication_id');
        });
    }

    /**
     * Returns MySQL create table script as a string
     *
     * @return string
     */
    private function getMySQLCreateTableSql(): string
    {
        return "CREATE TABLE `citations_extended` (
         `citations_extended_id` bigint(20) NOT NULL AUTO_INCREMENT,
         `publication_id` bigint(20) DEFAULT NULL,
         `parsed_citations` longtext DEFAULT NULL,
         PRIMARY KEY (`citations_extended_id`),
         KEY `citations_extended_publication_id_index` (`publication_id`)
        ) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;";
    }
}