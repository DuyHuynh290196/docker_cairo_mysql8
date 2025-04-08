<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopProfessional\Migrations;

use Doctrine\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

class Version20160919103142 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema): void
    {
        $this->connection->getDatabasePlatform()->registerDoctrineTypeMapping('enum', 'string');
        /**
         * Structure changes
         */
        if (!$schema->getTable('oxshops')->hasColumn('OXSERIAL')) {
            $this->addSql("ALTER TABLE oxshops ADD COLUMN `OXSERIAL` varchar(255) NOT NULL default '' COMMENT 'Shop license number'");
        }


        /**
         * Data changes
         */
        $this->addSql("INSERT INTO oxconfig (OXID, OXSHOPID, OXMODULE, OXVARNAME, OXVARTYPE, OXVARVALUE)
            VALUES ('21798f6956c099662a61067f6b4e6a99', 1, '', 'iOlcSuccess', 'str', 0x07aa1b94066827395d66)
            ON DUPLICATE KEY UPDATE OXSHOPID=1, OXVARNAME='iOlcSuccess', OXVARTYPE='str', OXVARVALUE='0x07aa1b94066827395d66';");
        $this->addSql("UPDATE oxshops SET OXEDITION='PE'");
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
