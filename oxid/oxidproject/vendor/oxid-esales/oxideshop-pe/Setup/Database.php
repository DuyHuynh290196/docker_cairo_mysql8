<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopProfessional\Setup;

use Conf;
use OxidEsales\Eshop\Setup\Utilities;
use OxidEsales\Eshop\Core\Serial;

/**
 * @inheritdoc
 */
class Database extends \OxidEsales\EshopCommunity\Setup\Database
{
    /**
     * Writes serial info to db
     *
     * @param Serial $serial       serial check object
     * @param string $serialNumber serial key
     */
    public function writeSerial($serial, $serialNumber)
    {
        /** @var Utilities $utils */
        $utils = $this->getInstance("Utilities");
        $configKey = new Conf();
        $shopId = $this->getInstance("Setup")->getShopId();

        $pdo = $this->getConnection();

        $pdo->exec("update oxshops set oxserial = '$serialNumber' where oxid = '$shopId'");

        $insert = $pdo->prepare("insert into oxconfig (oxid, oxshopid, oxvarname, oxvartype, oxvarvalue)
                                values (:oxid, :shopId, :name, :type, ENCODE(:value, :key))");

        $pdo->exec("delete from oxconfig where oxvarname = 'aSerials'");
        $insert->execute(
            array(
                'oxid' => $utils->generateUid(),
                'shopId' => $shopId,
                'name' => 'aSerials',
                'type' => 'arr',
                'value' => serialize(array($serialNumber)),
                'key' => $configKey->sConfigKey
            )
        );

        $pdo->exec("delete from oxconfig where oxvarname = 'sTagList'");
        $insert->execute(
            array(
                'oxid' => $utils->generateUid(),
                'shopId' => $shopId,
                'name' => 'sTagList',
                'type' => 'str',
                'value' => time(),
                'key' => $configKey->sConfigKey
            )
        );

        $pdo->exec("delete from oxconfig where oxvarname = 'IMD'");
        $insert->execute(
            array(
                'oxid' => $utils->generateUid(),
                'shopId' => $shopId,
                'name' => 'IMD',
                'type' => 'str',
                'value' => $serial->getMaxDays($serialNumber),
                'key' => $configKey->sConfigKey
            )
        );

        $pdo->exec("delete from oxconfig where oxvarname = 'IMA'");
        $insert->execute(
            array(
                'oxid' => $utils->generateUid(),
                'shopId' => $shopId,
                'name' => 'IMA',
                'type' => 'str',
                'value' => $serial->getMaxArticles($serialNumber),
                'key' => $configKey->sConfigKey
            )
        );

        $pdo->exec("delete from oxconfig where oxvarname = 'IMS'");
        $insert->execute(
            array(
                'oxid' => $utils->generateUid(),
                'shopId' => $shopId,
                'name' => 'IMS',
                'type' => 'str',
                'value' => $serial->getMaxShops($serialNumber),
                'key' => $configKey->sConfigKey
            )
        );
    }

    /**
     * @inheritdoc
     */
    protected function addConfigValueIfShopInfoShouldBeSent($utilities, $baseShopId, $parameters, $configKey, $session)
    {
    }
}
