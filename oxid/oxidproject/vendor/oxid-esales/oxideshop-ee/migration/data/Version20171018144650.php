<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Migrations;

use Doctrine\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

class Version20171018144650 extends AbstractMigration
{
    /**
     * All tables should have the same default character set and collation
     *
     * @param Schema $schema
     */
    public function up(Schema $schema): void
    {
        $this->addSql(
            "ALTER TABLE `oxarticles2shop`
                  COLLATE=utf8_general_ci, COMMENT='Mapping table for element subshop assignments';"
        );

        $this->addSql(
            "ALTER TABLE `oxattribute2shop`
                  COLLATE=utf8_general_ci, COMMENT='Mapping table for element subshop assignments';"
        );

        $this->addSql(
            "ALTER TABLE `oxcategories2shop`
                  COLLATE=utf8_general_ci, COMMENT='Mapping table for element subshop assignments';"
        );

        $this->addSql(
            "ALTER TABLE `oxdelivery2shop`
                  COLLATE=utf8_general_ci, COMMENT='Mapping table for element subshop assignments';"
        );

        $this->addSql(
            "ALTER TABLE `oxdeliveryset2shop`
                  COLLATE=utf8_general_ci, COMMENT='Mapping table for element subshop assignments';"
        );

        $this->addSql(
            "ALTER TABLE `oxdiscount2shop`
                  COLLATE=utf8_general_ci, COMMENT='Mapping table for element subshop assignments';"
        );

        $this->addSql(
            "ALTER TABLE `oxlinks2shop`
                  COLLATE=utf8_general_ci, COMMENT='Mapping table for element subshop assignments';"
        );

        $this->addSql(
            "ALTER TABLE `oxmanufacturers2shop`
                  COLLATE=utf8_general_ci, COMMENT='Mapping table for element subshop assignments';"
        );

        $this->addSql(
            "ALTER TABLE `oxnews2shop`
                  COLLATE=utf8_general_ci, COMMENT='Mapping table for element subshop assignments';"
        );

        $this->addSql(
            "ALTER TABLE `oxobject2action`
                  COLLATE=utf8_general_ci, COMMENT='Shows many-to-many relationship between actions (oxactions) and objects (table set by oxclass)';"
        );

        $this->addSql(
            "ALTER TABLE `oxselectlist2shop`
                  COLLATE=utf8_general_ci, COMMENT='Mapping table for element subshop assignments';"
        );

        $this->addSql(
            "ALTER TABLE `oxvendor2shop`
                  COLLATE=utf8_general_ci, COMMENT='Mapping table for element subshop assignments';"
        );

        $this->addSql(
            "ALTER TABLE `oxvoucherseries2shop`
                  COLLATE=utf8_general_ci, COMMENT='Mapping table for element subshop assignments';"
        );

        $this->addSql(
            "ALTER TABLE `oxwrapping2shop`
                  COLLATE=utf8_general_ci, COMMENT='Mapping table for element subshop assignments';"
        );
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema): void
    {
    }

    public function isTransactional(): bool
    {
        return false;
    }
}
