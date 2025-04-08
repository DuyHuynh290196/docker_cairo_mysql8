<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\EshopProfessional\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
use OxidEsales\Eshop\Core\Config;
use OxidEsales\Eshop\Core\ConfigFile;
use OxidEsales\Facts\Facts;

final class Version20230104155434 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        $configKey = $this->getConfigKey();
        $varName = 'sTagList';
        $varType = 'str';
        $rawValue = time();

        $query = "INSERT INTO `oxconfig` 
                  (
                      `OXID`, 
                      `OXSHOPID`, 
                      `OXVARNAME`, 
                      `OXVARTYPE`, 
                      `OXVARVALUE`
                  )
                  SELECT  
                      REPLACE(UUID( ) , '-', '' ), 
                      `OXID`,
                      ?,
                      ?, 
                      ENCODE(?, ?)
                  FROM `oxshops`                  
                  WHERE NOT EXISTS (
                      SELECT `OXVARNAME` 
                      FROM `oxconfig`
                      WHERE `OXVARNAME` = ? 
                      AND `oxconfig`.OXSHOPID = `oxshops`.OXID 
                  )";
        $this->addSql(
            $query,
            [$varName, $varType, $rawValue, $configKey, $varName]
        );
    }

    public function down(Schema $schema) : void
    {
    }

    public function isTransactional(): bool
    {
        return false;
    }

    private function getConfigKey()
    {
        $configFile = new ConfigFile((new Facts())->getSourcePath() . '/config.inc.php');

        return $configFile->getVar('sConfigKey') ?? Config::DEFAULT_CONFIG_KEY;
    }
}
