<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Migrations;

use Doctrine\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

class Version20160919103142 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema): void
    {
        /**
         * Structure changes
         */
        $this->addSql("SET @@session.sql_mode = ''");

        $this->addSql("ALTER TABLE `oxaddress`
            CHANGE `OXCOUNTRYID` `OXCOUNTRYID` char( 32 ) character set latin1 collate latin1_general_ci NOT NULL default '' COMMENT 'Country id (oxcountry)';");

        $this->addSql("ALTER TABLE `oxadminlog`
            ADD COLUMN `OXSHOPID` int(11) NOT NULL default '1' COMMENT 'Shop id (oxshops)',
            ADD COLUMN `OXSESSID` char(40) character set latin1 collate latin1_general_ci NOT NULL COMMENT 'Session id',
            ADD COLUMN `OXCLASS` varchar(50) NOT NULL COMMENT 'Logged class name',
            ADD COLUMN `OXFNC` varchar(30)NOT NULL COMMENT 'Logged function name',
            ADD COLUMN `OXITMID` char(32) character set latin1 collate latin1_general_ci NOT NULL COMMENT 'Item id',
            ADD COLUMN `OXPARAM` text NOT NULL COMMENT 'Logged parameters',
            ADD KEY `OXITMID` (`OXITMID`),
            ADD KEY `OXUSERID` (`OXUSERID`);
        ");

        $this->addSql("ALTER TABLE `oxarticles`
            ADD COLUMN `OXMAPID` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'Integer mapping identifier' AFTER `OXID`,
            ADD COLUMN `OXVPE` int(11) NOT NULL default '1' COMMENT 'Packing unit' AFTER OXSKIPDISCOUNTS,
            ADD COLUMN `OXPIXIEXPORTED` timestamp NOT NULL default '0000-00-00' COMMENT 'Field for 3rd party modules export' AFTER OXSKIPDISCOUNTS,
            ADD COLUMN `OXPIXIEXPORT` tinyint(1) NOT NULL default '0' COMMENT 'Field for 3rd party modules export' AFTER OXSKIPDISCOUNTS,
            ADD COLUMN `OXORDERINFO` varchar(255) NOT NULL COMMENT 'Additional info in order confirmation mail' AFTER OXSKIPDISCOUNTS,
            ADD KEY `OXPARENTID` (`OXPARENTID`),
            ADD KEY `OXMAPID` (`OXMAPID`);
        ");

        $this->addSql("ALTER TABLE `oxattribute`
            ADD COLUMN `OXMAPID` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'Integer mapping identifier' AFTER `OXID`,
            ADD KEY `OXSHOPID` (`OXSHOPID`),
            ADD KEY `OXMAPID` (`OXMAPID`);
        ");

        $this->addSql("DROP TABLE IF EXISTS `oxcache`");
        $this->addSql("CREATE TABLE `oxcache` (
            `OXID` char(32) character set latin1 collate latin1_general_ci NOT NULL COMMENT 'Cache id',
            `OXSHOPID` int(11) NOT NULL default '1' COMMENT 'Shop id (oxshops)',
            `OXEXPIRE` int(11) unsigned NOT NULL default '0' COMMENT 'Expiration time',
            `OXRESETON` char(255) character set latin1 collate latin1_general_ci NOT NULL default '' COMMENT 'Reset id (e.g. View Reset Id)',
            `OXSIZE` int(11) unsigned NOT NULL default '0' COMMENT 'The length of content to be added',
            `OXHITS` int(11) unsigned NOT NULL default '0' COMMENT 'Increases when a certain id is retrieved from cache',
            `OXTIMESTAMP` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP COMMENT 'Timestamp',
            PRIMARY KEY  (`OXID`),
            KEY(`OXEXPIRE`)
        ) ENGINE=InnoDB COMMENT 'Shop cache';");

        $this->addSql("ALTER TABLE `oxcategories`
            ADD COLUMN `OXMAPID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Integer mapping identifier' AFTER `OXID`,
            ADD KEY `OXMAPID` (`OXMAPID`);
        ");

        $this->addSql("ALTER TABLE `oxcontents`
            DROP KEY `OXLOADID`,
            ADD UNIQUE KEY `OXLOADID` (`OXLOADID`, `OXSHOPID`);
        ");

        $this->addSql("ALTER TABLE `oxdelivery`
            ADD COLUMN `OXMAPID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Integer mapping identifier' AFTER `OXID`,
            ADD KEY `OXMAPID` (`OXMAPID`);
        ");

        $this->addSql("ALTER TABLE `oxdeliveryset`
            ADD COLUMN `OXMAPID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Integer mapping identifier' AFTER `OXID`,
            CHANGE `OXTITLE` `OXTITLE` char(255) NOT NULL default '' COMMENT 'Title (multilanguage)',
            CHANGE `OXTITLE_1` `OXTITLE_1` char(255) NOT NULL default '',
            CHANGE `OXTITLE_2` `OXTITLE_2` char(255) NOT NULL default '',
            CHANGE `OXTITLE_3` `OXTITLE_3` char(255) NOT NULL default '',
            ADD KEY `OXMAPID` (`OXMAPID`);
        ");

        $this->addSql("ALTER TABLE `oxdiscount`
            ADD COLUMN `OXMAPID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Integer mapping identifier' AFTER `OXID`,
            ADD KEY `OXMAPID` (`OXMAPID`);
        ");

        $this->addSql("DROP TABLE IF EXISTS `oxfield2role`");
        $this->addSql("CREATE TABLE `oxfield2role` (
            `OXFIELDID` char(255) character set latin1 collate latin1_general_ci NOT NULL COMMENT 'Field id',
            `OXTYPE` char(32) character set latin1 collate latin1_general_ci NOT NULL COMMENT 'Field type',
            `OXROLEID` char(32) character set latin1 collate latin1_general_ci NOT NULL COMMENT 'Roles id (oxroles)',
            `OXIDX` int(1) NOT NULL COMMENT 'Role permission: 0 - Deny, 1 - Read, 2 - Full',
            `OXTIMESTAMP` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP COMMENT 'Timestamp',
            PRIMARY KEY  (`OXFIELDID`,`OXTYPE`,`OXROLEID`),
            KEY `OXIDX` (`OXIDX`),
            KEY(`OXROLEID`),
            KEY(`OXTYPE`)
        ) ENGINE=InnoDB COMMENT 'Shows many-to-many relationship between fields and roles';");

        $this->addSql("DROP TABLE IF EXISTS `oxfield2shop`");
        $this->addSql("CREATE TABLE `oxfield2shop` (
            `OXID` char(32) character set latin1 collate latin1_general_ci NOT NULL COMMENT 'Record id',
            `OXARTID` char(32) character set latin1 collate latin1_general_ci NOT NULL COMMENT 'Article id (oxarticles)',
            `OXSHOPID` int(11) NOT NULL default '1' COMMENT 'Shop id (oxshops)',
            `OXPRICE` DOUBLE NOT NULL COMMENT 'Price',
            `OXPRICEA` DOUBLE NOT NULL COMMENT 'Price A',
            `OXPRICEB` DOUBLE NOT NULL COMMENT 'Price B',
            `OXPRICEC` DOUBLE NOT NULL COMMENT 'Price C',
            `OXUPDATEPRICE` DOUBLE NOT NULL default '0' COMMENT 'If not 0, oxprice will be updated to this value on oxupdatepricetime date',
            `OXUPDATEPRICEA` DOUBLE NOT NULL default '0' COMMENT 'If not 0, oxpricea will be updated to this value on oxupdatepricetime date',
            `OXUPDATEPRICEB` DOUBLE NOT NULL default '0' COMMENT 'If not 0, oxpriceb will be updated to this value on oxupdatepricetime date',
            `OXUPDATEPRICEC` DOUBLE NOT NULL default '0' COMMENT 'If not 0, oxpricec will be updated to this value on oxupdatepricetime date',
            `OXUPDATEPRICETIME` TIMESTAMP NOT NULL default '0000-00-00 00:00:00' COMMENT 'Date, when oxprice[a,b,c] should be updated to oxupdateprice[a,b,c] values',
            `OXTIMESTAMP` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP COMMENT 'Timestamp',
            PRIMARY KEY (`OXID`),
            INDEX (`OXARTID` , `OXSHOPID`),
            KEY `OXUPDATEPRICETIME` (`OXUPDATEPRICETIME`),
            KEY `OXPRICE` (`OXPRICE`)
        ) ENGINE=InnoDB COMMENT 'Shows many-to-many relationship between fields and shops (multishops fields)';");

        $this->addSql("ALTER TABLE `oxgroups`
            ADD COLUMN `OXRRID` bigint(21) UNSIGNED NOT NULL COMMENT 'Group numeric index' AFTER `OXID`;
        ");

        $this->addSql("ALTER TABLE `oxlinks`
            ADD COLUMN `OXMAPID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Integer mapping identifier' AFTER `OXID`,
            ADD KEY `OXMAPID` (`OXMAPID`);
        ");

        $this->addSql("ALTER TABLE `oxmanufacturers`
            ADD COLUMN `OXMAPID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Integer mapping identifier' AFTER `OXID`,
            ADD KEY `OXMAPID` (`OXMAPID`),
            ADD KEY `OXSHOPID` (`OXSHOPID`),
            ADD KEY `OXACTIVE` (`OXACTIVE`);
        ");

        $this->addSql("ALTER TABLE `oxnews`
            ADD COLUMN `OXMAPID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Integer mapping identifier' AFTER `OXID`,
            ADD KEY `OXMAPID` (`OXMAPID`);
        ");

        $this->addSql("ALTER TABLE `oxnewssubscribed`
            DROP KEY `OXEMAIL`,
            ADD KEY `OXEMAIL` (`OXEMAIL`);
        ");

        $this->addSql("ALTER TABLE `oxobject2attribute`
            DROP KEY `OXATTRID`,
            ADD KEY `mainidx` (`OXATTRID`,`OXOBJECTID`);
        ");

        $this->addSql("ALTER TABLE `oxobject2category`
            ADD COLUMN `OXSHOPID` int(11) NOT NULL default 1 COMMENT 'Shop id (oxshops)' AFTER `OXID`,
            DROP KEY `OXMAINIDX`,
            ADD UNIQUE KEY `OXMAINIDXU` (`OXCATNID`, `OXOBJECTID`, `OXSHOPID`),
            ADD KEY `OXMAINIDX` (`OXCATNID`, `OXOBJECTID`),
            ADD KEY `OXSHOPID` (`OXSHOPID`);
        ");

        $this->addSql("ALTER TABLE `oxobject2discount`
            DROP KEY `oxobjectid`,
            ADD KEY `mainidx` (`OXOBJECTID`,`OXDISCOUNTID`,`OXTYPE`);
        ");

        $this->addSql("DROP TABLE IF EXISTS `oxobject2role`");
        $this->addSql("CREATE TABLE `oxobject2role` (
            `OXID` char(32) character set latin1 collate latin1_general_ci NOT NULL COMMENT 'Record id',
            `OXOBJECTID` char(32) character set latin1 collate latin1_general_ci NOT NULL COMMENT 'Object id (e.g. oxgroups, oxuser)',
            `OXROLEID` char(32) character set latin1 collate latin1_general_ci NOT NULL COMMENT 'Role id (oxroles)',
            `OXTYPE` char(32) character set latin1 collate latin1_general_ci NOT NULL COMMENT 'Type (t.g. oxgroups, oxuser)',
            `OXTIMESTAMP` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP COMMENT 'Timestamp',
            PRIMARY KEY  (`OXID`),
            KEY (`OXROLEID`),
            KEY (`OXOBJECTID`)
        ) ENGINE=InnoDB COMMENT 'Shows many-to-many relationship between roles and objects (table determined by oxtype)';");

        $this->addSql("DROP TABLE IF EXISTS `oxobjectrights`");
        $this->addSql("CREATE TABLE `oxobjectrights` (
            `OXID` char(32) character set latin1 collate latin1_general_ci NOT NULL COMMENT 'Record id',
            `OXOBJECTID` char(32) character set latin1 collate latin1_general_ci NOT NULL COMMENT 'Object id ()',
            `OXGROUPIDX` bigint(20) unsigned NOT NULL COMMENT 'Group index',
            `OXOFFSET` int(10) unsigned NOT NULL COMMENT  'Group numeric index',
            `OXACTION` int(10) unsigned NOT NULL COMMENT 'Action',
            `OXTIMESTAMP` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP COMMENT 'Timestamp',
            PRIMARY KEY  (`OXOBJECTID`,`OXOFFSET`,`OXACTION`),
            KEY(`OXOBJECTID`),
            KEY(`OXOFFSET`),
            KEY(`OXACTION`)
        ) ENGINE=InnoDB COMMENT 'Object rights';");

        $this->addSql("ALTER TABLE `oxvoucherseries`
            ADD COLUMN `OXMAPID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Integer mapping identifier' AFTER `OXID`,
            ADD INDEX OXMAPID (`OXMAPID` ASC);
        ");

        $this->addSql("ALTER TABLE `oxorder`
            ADD COLUMN `OXBILLUSTIDSTATUS` tinyint(1) NOT NULL default '0' COMMENT 'User VAT id status: 1 - valid, 0 - invalid' AFTER `OXBILLUSTID`,
            ADD COLUMN `OXPIXIEXPORT` tinyint(1) DEFAULT '0' NOT NULL COMMENT 'Field for 3rd party modules export' AFTER `OXDELTYPE`,
            DROP KEY `MAINIDX`,
            ADD KEY `MAINIDX` (`OXSHOPID`, `OXORDERDATE`),
            ADD KEY `OXUSERID` (`OXUSERID`),
            ADD KEY `OXORDERNR` (`OXORDERNR`);
        ");

        $this->addSql("ALTER TABLE `oxorderarticles`
            ADD COLUMN `OXERPSTATUS` text NOT NULL COMMENT 'serialized ERP statuses array' AFTER `OXORDERSHOPID`;
        ");

        $this->addSql("DROP TABLE IF EXISTS `oxroles`");
        $this->addSql("CREATE TABLE `oxroles` (
            `OXID` char(32) character set latin1 collate latin1_general_ci NOT NULL COMMENT 'Role id',
            `OXTITLE` varchar(255) NOT NULL COMMENT 'Role title',
            `OXSHOPID` int(11) NOT NULL default '1' COMMENT 'Shop id (oxshops)',
            `OXACTIVE` int(1) NOT NULL default '0' COMMENT 'Active',
            `OXAREA` int(1) NOT NULL COMMENT 'Which area this role belongs: 0 - Admin, 1 - Shop',
            `OXTIMESTAMP` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP COMMENT 'Timestamp',
            PRIMARY KEY  (`OXID`),
            KEY (`OXACTIVE`),
            KEY(`OXSHOPID`),
            KEY (`OXAREA`)
        ) ENGINE=InnoDB COMMENT 'Shop and Admin Roles';");

        $this->addSql("DROP TABLE IF EXISTS `oxrolefields`");
        $this->addSql("CREATE TABLE `oxrolefields` (
            `OXID` char(32) character set latin1 collate latin1_general_ci NOT NULL COMMENT 'Field id',
            `OXNAME` varchar(255) NOT NULL COMMENT 'Role name',
            `OXPARAM` varchar(255) NOT NULL COMMENT 'Parameters',
            `OXTIMESTAMP` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP COMMENT 'Timestamp',
            PRIMARY KEY  (`OXID`)
        ) ENGINE=InnoDB;");

        $this->addSql("ALTER TABLE `oxselectlist`
            ADD COLUMN `OXMAPID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Integer mapping identifier' AFTER `OXID`,
            ADD KEY OXMAPID (`OXMAPID`),
            ADD KEY `OXSHOPID` (`OXSHOPID`),
            ADD KEY (`OXTITLE`);
        ");

        $this->addSql("ALTER TABLE `oxshops`
            ADD COLUMN `OXPARENTID` int(11) NOT NULL default 0 COMMENT 'Parent id' AFTER `OXID`,
            ADD COLUMN `OXISSUPERSHOP` INT NOT NULL DEFAULT 0 COMMENT 'Shop is supershop (you can assign products to any shop)' AFTER `OXACTIVE`,
            ADD COLUMN `OXISMULTISHOP` INT NOT NULL DEFAULT 0 COMMENT 'Shop is multishop (loads all products from all shops)' AFTER `OXACTIVE`,
            ADD COLUMN `OXISINHERITED` INT NOT NULL DEFAULT 0 COMMENT 'Shop inherits all inheritable items (products, discounts etc) from it`s parent shop' AFTER `OXACTIVE`;
        ");

        $this->addSql("ALTER TABLE `oxtplblocks`
            DROP INDEX `search`,
            ADD INDEX `search` (`OXACTIVE`, `OXSHOPID`, `OXTEMPLATE`, `OXPOS`);
        ");

        $this->addSql("ALTER TABLE `oxuser`
            ADD COLUMN `OXUSTIDSTATUS` tinyint(1) NOT NULL default '0' COMMENT 'User VAT id status: 1 - valid, 0 - invalid' AFTER `OXUSTID`,
            ADD COLUMN `OXWRONGLOGINS` int(11) NOT NULL default '0' COMMENT 'Wrong logins count' AFTER `OXURL`,
            ADD COLUMN `OXLDAPKEY` varchar(128) NOT NULL default '' COMMENT 'LDAP user key' AFTER `OXURL`,
            ADD KEY `OXSHOPID` (`OXSHOPID`,`OXUSERNAME`);
        ");

        $this->addSql("ALTER TABLE `oxvendor`
            ADD COLUMN `OXMAPID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Integer mapping identifier' AFTER `OXID`,
            ADD KEY `OXMAPID` (`OXMAPID`),
            ADD KEY `OXSHOPID` (`OXSHOPID`);
        ");

        $this->addSql("ALTER TABLE `oxwrapping`
            ADD COLUMN `OXMAPID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Integer mapping identifier' AFTER `OXID`,
            ADD KEY `OXMAPID` (`OXMAPID`),
            ADD KEY `OXSHOPID` (`OXSHOPID`);
        ");

        $this->addSql("DROP TABLE IF EXISTS `oxarticles2shop`");
        $this->addSql("CREATE TABLE `oxarticles2shop` (
            `OXSHOPID` int(11) NOT NULL COMMENT 'Mapped shop id',
            `OXMAPOBJECTID` bigint(20) NOT NULL COMMENT 'Mapped object id',
            `OXTIMESTAMP` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Timestamp',
            UNIQUE KEY `OXMAPIDX` (`OXSHOPID`,`OXMAPOBJECTID`),
            KEY `OXMAPOBJECTID` (`OXMAPOBJECTID`),
            KEY `OXSHOPID`   (`OXSHOPID`)
        ) ENGINE=InnoDB DEFAULT CHARSET=latin1 collate latin1_general_ci COMMENT='Mapping table for element subshop assignments';");

        $this->addSql("DROP TABLE IF EXISTS `oxdiscount2shop`");
        $this->addSql("CREATE TABLE `oxdiscount2shop` (
            `OXSHOPID` int(11) NOT NULL COMMENT 'Mapped shop id',
            `OXMAPOBJECTID` int(11) NOT NULL COMMENT 'Mapped object id',
            `OXTIMESTAMP` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Timestamp',
            UNIQUE KEY `OXMAPIDX` (`OXSHOPID`,`OXMAPOBJECTID`),
            KEY `OXMAPOBJECTID` (`OXMAPOBJECTID`),
            KEY `OXSHOPID`   (`OXSHOPID`)
        ) ENGINE=InnoDB DEFAULT CHARSET=latin1 collate latin1_general_ci COMMENT='Mapping table for element subshop assignments';");

        $this->addSql("DROP TABLE IF EXISTS `oxcategories2shop`");
        $this->addSql("CREATE TABLE `oxcategories2shop` (
            `OXSHOPID` int(11) NOT NULL COMMENT 'Mapped shop id',
            `OXMAPOBJECTID` int(11) NOT NULL COMMENT 'Mapped object id',
            `OXTIMESTAMP` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Timestamp',
            UNIQUE KEY `OXMAPIDX` (`OXSHOPID`,`OXMAPOBJECTID`),
            KEY `OXMAPOBJECTID` (`OXMAPOBJECTID`),
            KEY `OXSHOPID`   (`OXSHOPID`)
        ) ENGINE=InnoDB DEFAULT CHARSET=latin1 collate latin1_general_ci COMMENT='Mapping table for element subshop assignments';");

        $this->addSql("DROP TABLE IF EXISTS `oxattribute2shop`");
        $this->addSql("CREATE TABLE `oxattribute2shop` (
            `OXSHOPID` int(11) NOT NULL COMMENT 'Mapped shop id',
            `OXMAPOBJECTID` int(11) NOT NULL COMMENT 'Mapped object id',
            `OXTIMESTAMP` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Timestamp',
            UNIQUE KEY `OXMAPIDX` (`OXSHOPID`,`OXMAPOBJECTID`),
            KEY `OXMAPOBJECTID` (`OXMAPOBJECTID`),
            KEY `OXSHOPID`   (`OXSHOPID`)
        ) ENGINE=InnoDB DEFAULT CHARSET=latin1 collate latin1_general_ci COMMENT='Mapping table for element subshop assignments';");

        $this->addSql("DROP TABLE IF EXISTS `oxlinks2shop`");
        $this->addSql("CREATE TABLE `oxlinks2shop` (
            `OXSHOPID` int(11) NOT NULL COMMENT 'Mapped shop id',
            `OXMAPOBJECTID` int(11) NOT NULL COMMENT 'Mapped object id',
            `OXTIMESTAMP` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Timestamp',
            UNIQUE KEY `OXMAPIDX` (`OXSHOPID`,`OXMAPOBJECTID`),
            KEY `OXMAPOBJECTID` (`OXMAPOBJECTID`),
            KEY `OXSHOPID`   (`OXSHOPID`)
        ) ENGINE=InnoDB DEFAULT CHARSET=latin1 collate latin1_general_ci COMMENT='Mapping table for element subshop assignments';");

        $this->addSql("DROP TABLE IF EXISTS `oxvoucherseries2shop`");
        $this->addSql("CREATE TABLE `oxvoucherseries2shop` (
            `OXSHOPID` int(11) NOT NULL COMMENT 'Mapped shop id',
            `OXMAPOBJECTID` int(11) NOT NULL COMMENT 'Mapped object id',
            `OXTIMESTAMP` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Timestamp',
            UNIQUE KEY `OXMAPIDX` (`OXSHOPID`,`OXMAPOBJECTID`),
            KEY `OXMAPOBJECTID` (`OXMAPOBJECTID`),
            KEY `OXSHOPID`   (`OXSHOPID`)
        ) ENGINE=InnoDB DEFAULT CHARSET=latin1 collate latin1_general_ci COMMENT='Mapping table for element subshop assignments';");

        $this->addSql("DROP TABLE IF EXISTS `oxmanufacturers2shop`");
        $this->addSql("CREATE TABLE `oxmanufacturers2shop` (
            `OXSHOPID` int(11) NOT NULL COMMENT 'Mapped shop id',
            `OXMAPOBJECTID` int(11) NOT NULL COMMENT 'Mapped object id',
            `OXTIMESTAMP` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Timestamp',
            UNIQUE KEY `OXMAPIDX` (`OXSHOPID`,`OXMAPOBJECTID`),
            KEY `OXMAPOBJECTID` (`OXMAPOBJECTID`),
            KEY `OXSHOPID`   (`OXSHOPID`)
        ) ENGINE=InnoDB DEFAULT CHARSET=latin1 collate latin1_general_ci COMMENT='Mapping table for element subshop assignments';");

        $this->addSql("DROP TABLE IF EXISTS `oxnews2shop`");
        $this->addSql("CREATE TABLE `oxnews2shop` (
            `OXSHOPID` int(11) NOT NULL COMMENT 'Mapped shop id',
            `OXMAPOBJECTID` int(11) NOT NULL COMMENT 'Mapped object id',
            `OXTIMESTAMP` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Timestamp',
            UNIQUE KEY `OXMAPIDX` (`OXSHOPID`,`OXMAPOBJECTID`),
            KEY `OXMAPOBJECTID` (`OXMAPOBJECTID`),
            KEY `OXSHOPID`   (`OXSHOPID`)
        ) ENGINE=InnoDB DEFAULT CHARSET=latin1 collate latin1_general_ci COMMENT='Mapping table for element subshop assignments';");

        $this->addSql("DROP TABLE IF EXISTS `oxselectlist2shop`");
        $this->addSql("CREATE TABLE `oxselectlist2shop` (
            `OXSHOPID` int(11) NOT NULL COMMENT 'Mapped shop id',
            `OXMAPOBJECTID` int(11) NOT NULL COMMENT 'Mapped object id',
            `OXTIMESTAMP` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Timestamp',
            UNIQUE KEY `OXMAPIDX` (`OXSHOPID`,`OXMAPOBJECTID`),
            KEY `OXMAPOBJECTID` (`OXMAPOBJECTID`),
            KEY `OXSHOPID`   (`OXSHOPID`)
        ) ENGINE=InnoDB DEFAULT CHARSET=latin1 collate latin1_general_ci COMMENT='Mapping table for element subshop assignments';");

        $this->addSql("DROP TABLE IF EXISTS `oxwrapping2shop`");
        $this->addSql("CREATE TABLE `oxwrapping2shop` (
            `OXSHOPID` int(11) NOT NULL COMMENT 'Mapped shop id',
            `OXMAPOBJECTID` int(11) NOT NULL COMMENT 'Mapped object id',
            `OXTIMESTAMP` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Timestamp',
            UNIQUE KEY `OXMAPIDX` (`OXSHOPID`,`OXMAPOBJECTID`),
            KEY `OXMAPOBJECTID` (`OXMAPOBJECTID`),
            KEY `OXSHOPID`   (`OXSHOPID`)
        ) ENGINE=InnoDB DEFAULT CHARSET=latin1 collate latin1_general_ci COMMENT='Mapping table for element subshop assignments';");

        $this->addSql("DROP TABLE IF EXISTS `oxdeliveryset2shop`");
        $this->addSql("CREATE TABLE `oxdeliveryset2shop` (
            `OXSHOPID` int(11) NOT NULL COMMENT 'Mapped shop id',
            `OXMAPOBJECTID` int(11) NOT NULL COMMENT 'Mapped object id',
            `OXTIMESTAMP` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Timestamp',
            UNIQUE KEY `OXMAPIDX` (`OXSHOPID`,`OXMAPOBJECTID`),
            KEY `OXMAPOBJECTID` (`OXMAPOBJECTID`),
            KEY `OXSHOPID`   (`OXSHOPID`)
        ) ENGINE=InnoDB DEFAULT CHARSET=latin1 collate latin1_general_ci COMMENT='Mapping table for element subshop assignments';");

        $this->addSql("DROP TABLE IF EXISTS `oxdelivery2shop`");
        $this->addSql("CREATE TABLE `oxdelivery2shop` (
            `OXSHOPID` int(11) NOT NULL COMMENT 'Mapped shop id',
            `OXMAPOBJECTID` int(11) NOT NULL COMMENT 'Mapped object id',
            `OXTIMESTAMP` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Timestamp',
            UNIQUE KEY `OXMAPIDX` (`OXSHOPID`,`OXMAPOBJECTID`),
            KEY `OXMAPOBJECTID` (`OXMAPOBJECTID`),
            KEY `OXSHOPID`   (`OXSHOPID`)
        ) ENGINE=InnoDB DEFAULT CHARSET=latin1 collate latin1_general_ci COMMENT='Mapping table for element subshop assignments';");

        $this->addSql("DROP TABLE IF EXISTS `oxvendor2shop`");
        $this->addSql("CREATE TABLE `oxvendor2shop` (
            `OXSHOPID` int(11) NOT NULL COMMENT 'Mapped shop id',
            `OXMAPOBJECTID` int(11) NOT NULL COMMENT 'Mapped object id',
            `OXTIMESTAMP` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Timestamp',
            UNIQUE KEY `OXMAPIDX` (`OXSHOPID`,`OXMAPOBJECTID`),
            KEY `OXMAPOBJECTID` (`OXMAPOBJECTID`),
            KEY `OXSHOPID`   (`OXSHOPID`)
        ) ENGINE=InnoDB DEFAULT CHARSET=latin1 collate latin1_general_ci COMMENT='Mapping table for element subshop assignments';");

        /**
         * Data changes
         */

        $this->addSql("UPDATE `oxconfig` SET `OXVARVALUE`=0x4dba852e754d5636461a94d500fa85e977b4e362efc6891c67a459b290a6f4bdd6e209e22277cecd4f6c4052b78bed9a0d108e2c19712fa655a80dbf45c0c417537711f0c23d9dd7d12767cd78c0582cf18e2d689e312815c1408c2a1f7b6ceed9e1d32094b912d0fc72f6d5ec618ed173cf8a37689d7396 WHERE `OXVARNAME`='aCMSfolder'");
        $this->addSql("UPDATE `oxconfig` SET `OXVARVALUE`='' WHERE `OXVARNAME`='blDisableNavBars'");
        $this->addSql("UPDATE `oxconfig` SET `OXVARVALUE`=0x07aa1b94066827395d66 WHERE `OXVARNAME`='iOlcSuccess'");
        $this->addSql("DELETE FROM `oxconfig` WHERE `OXVARNAME` in ('sGZSLogFile', 'blDisableDublArtOnCopy', 'blAllowNegativeStock')");
        $this->addSql("INSERT INTO `oxconfig` (OXID, OXSHOPID, OXMODULE, OXVARNAME, OXVARTYPE, OXVARVALUE) VALUES
            ('79e417a3726a0a010.44960388', 1, '', 'sBackTag', 'str', ''),
            ('e9244781359d1dd18.54146261', 1, '', 'iMallPriceAddition', 'str', 0xde),
            ('e924478126bb80968.65249125', 1, '', 'blMallPriceAdditionPercent', 'bool', 0x07),
            ('13c44abc1eb0e08c9.55267104', 1, '', 'iCacheLifeTime', 'str', 0xb00fb55d00),
            ('13c44abc1eb0e6841.92235277', 1, '', 'aCachableClasses', 'arr', 0x4dba682873e04af3cad2a8e8163c00a3f12c7c5c9004269540d12483cff3c29cdee114412197adf9b241e1d6c74fe8fc9e1ce815996a5eacb8d09fc83e579c765a959bb2c398ad40c7279ed7f2fc27520aca6f9007df58216811bba3b7),
            ('2e244d9a2f78077d1.36913924', 1, '', 'bl_perfLoadSelectListsInAList', 'bool', ''),
            ('a1544b76735e2d8e8.37455553', 1, '', 'blShowVATForDelivery', 'bool', 0x07),
            ('a1544b76735e421e0.22942938', 1, '', 'blShowVATForPayCharge', 'bool', 0x07),
            ('a1544b76735e48c05.33803554', 1, '', 'blExclNonMaterialFromDelivery', 'bool', ''),
            ('a1544b76735e557a6.57474874', 1, '', 'blAutoSearchOnCat', 'bool', ''),
            ('a1544b76735e63209.62380160', 1, '', 'blShowVATForWrapping', 'bool', ''),
            ('a1544b76735edac06.77267220', 1, '', 'sLocalDateFormat', 'str', 0x7170ae),
            ('a1544b76735ede2e3.94589565', 1, '', 'sLocalTimeFormat', 'str', 0x7170ae),
            ('e7744be1b5fb6ac06.91704848', 1, '', 'blVariantParentBuyable', 'bool', ''),
            ('e7744be1b5fb6e4a9.96876634', 1, '', 'blVariantInheritAmountPrice', 'bool', ''),
            ('e7744be1b5fb6e4a9.96876633', 1, '', 'blShowVariantReviews', 'bool', ''),
            ('e7744be1b5fb8aef2.33450414', 1, '', 'blGBModerate', 'bool', ''),
            ('e7744be1b5fb93c94.74487583', 1, '', 'blUseLDAP', 'bool', ''),
            ('e7744be1b5fbacf84.23562842', 1, '', 'iSessionTimeoutAdmin', 'str', ''),
            ('e7744be1b5fbb4cf1.34834569', 1, '', 'iServerTimeShift', 'str', ''),
            ('e7744be1b5fb9ece3.82840270', 1, '', 'iAdminLogTime', 'str', 0x17c3),
            ('d8d44bbdea56b3ed0.58768595', 1, '', 'blMallCustomPrice', 'bool', 0x07),
            ('51e44d9a1e3bc2571.58800338', 1, '', 'blShopStopped', 'bool', 0x07)");

        $this->addSql("UPDATE `oxdeliveryset` SET `OXMAPID`=901 WHERE OXID='oxidstandard'");

        $this->addSql("UPDATE `oxgroups` SET `OXRRID`=0 WHERE `OXID`='oxidblacklist'");
        $this->addSql("UPDATE `oxgroups` SET `OXRRID`=1 WHERE `OXID`='oxidsmallcust'");
        $this->addSql("UPDATE `oxgroups` SET `OXRRID`=2 WHERE `OXID`='oxidmiddlecust'");
        $this->addSql("UPDATE `oxgroups` SET `OXRRID`=3 WHERE `OXID`='oxidgoodcust'");
        $this->addSql("UPDATE `oxgroups` SET `OXRRID`=4 WHERE `OXID`='oxidforeigncustomer'");
        $this->addSql("UPDATE `oxgroups` SET `OXRRID`=5 WHERE `OXID`='oxidnewcustomer'");
        $this->addSql("UPDATE `oxgroups` SET `OXRRID`=6 WHERE `OXID`='oxidpowershopper'");
        $this->addSql("UPDATE `oxgroups` SET `OXRRID`=7 WHERE `OXID`='oxiddealer'");
        $this->addSql("UPDATE `oxgroups` SET `OXRRID`=8 WHERE `OXID`='oxidnewsletter'");
        $this->addSql("UPDATE `oxgroups` SET `OXRRID`=9 WHERE `OXID`='oxidadmin'");
        $this->addSql("UPDATE `oxgroups` SET `OXRRID`=10 WHERE `OXID`='oxidpriceb'");
        $this->addSql("UPDATE `oxgroups` SET `OXRRID`=11 WHERE `OXID`='oxidpricea'");
        $this->addSql("UPDATE `oxgroups` SET `OXRRID`=12 WHERE `OXID`='oxidpricec'");
        $this->addSql("UPDATE `oxgroups` SET `OXRRID`=13 WHERE `OXID`='oxidblocked'");
        $this->addSql("UPDATE `oxgroups` SET `OXRRID`=14 WHERE `OXID`='oxidcustomer'");
        $this->addSql("UPDATE `oxgroups` SET `OXRRID`=15 WHERE `OXID`='oxidnotyetordered'");

        $this->addSql("UPDATE `oxnewssubscribed` set `OXFNAME`='Shop', `OXLNAME`='Administrator' WHERE `OXID`='0b742e66fd94c88b8.61001136'");

        $this->addSql("INSERT INTO `oxrolefields` (`OXID`, `OXNAME`, `OXPARAM`) VALUES
            ('42b44bc9934bdb406.85935627', 'TOBASKET', 'tobasket;basket'),
            ('42b44bc9941a46fd3.13180499', 'SHOWLONGDESCRIPTION', ''),
            ('42b44bc99488c66b1.94059993', 'SHOWARTICLEPRICE', ''),
            ('42b44bc9950334951.12393781', 'SHOWSHORTDESCRIPTION', '')");

        $this->addSql("UPDATE oxshops SET OXPARENTID=0, OXISINHERITED=0, OXISMULTISHOP=0, OXISSUPERSHOP=1 WHERE OXID=1");
        $this->addSql("UPDATE oxshops SET OXEDITION='EE'");

        $this->addSql("INSERT INTO `oxdeliveryset2shop` (`OXSHOPID`, `OXMAPOBJECTID`) VALUES (1, 901)");
        $this->addSql("INSERT INTO `oxdeliveryset2shop` (OXSHOPID, OXMAPOBJECTID) SELECT OXSHOPID,OXMAPID from oxarticles");
        $this->addSql("INSERT INTO `oxdelivery2shop` (OXSHOPID, OXMAPOBJECTID) SELECT OXSHOPID,OXMAPID from oxarticles");
        $this->addSql("INSERT INTO `oxarticles2shop` (OXSHOPID, OXMAPOBJECTID) SELECT OXSHOPID,OXMAPID from oxarticles");
        $this->addSql("INSERT INTO `oxdiscount2shop` (OXSHOPID, OXMAPOBJECTID) SELECT OXSHOPID,OXMAPID from oxarticles");
        $this->addSql("INSERT INTO `oxcategories2shop` (OXSHOPID, OXMAPOBJECTID) SELECT OXSHOPID,OXMAPID from oxarticles");
        $this->addSql("INSERT INTO `oxattribute2shop` (OXSHOPID, OXMAPOBJECTID) SELECT OXSHOPID,OXMAPID from oxarticles");
        $this->addSql("INSERT INTO `oxlinks2shop` (OXSHOPID, OXMAPOBJECTID) SELECT OXSHOPID,OXMAPID from oxarticles");
        $this->addSql("INSERT INTO `oxvoucherseries2shop` (OXSHOPID, OXMAPOBJECTID) SELECT OXSHOPID,OXMAPID from oxarticles");
        $this->addSql("INSERT INTO `oxmanufacturers2shop` (OXSHOPID, OXMAPOBJECTID) SELECT OXSHOPID,OXMAPID from oxarticles");
        $this->addSql("INSERT INTO `oxnews2shop` (OXSHOPID, OXMAPOBJECTID) SELECT OXSHOPID,OXMAPID from oxarticles");
        $this->addSql("INSERT INTO `oxselectlist2shop` (OXSHOPID, OXMAPOBJECTID) SELECT OXSHOPID,OXMAPID from oxarticles");
        $this->addSql("INSERT INTO `oxwrapping2shop` (OXSHOPID, OXMAPOBJECTID) SELECT OXSHOPID,OXMAPID from oxarticles");
        $this->addSql("INSERT INTO `oxvendor2shop` (OXSHOPID, OXMAPOBJECTID) SELECT OXSHOPID,OXMAPID from oxarticles");

        $this->addSql("DELETE FROM oxtplblocks WHERE OXID='aba2417d4a2846a07c1575a20479c927'");
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
