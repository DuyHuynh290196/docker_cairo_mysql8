SET @@session.sql_mode = '';
# for frontendMultidimensionalVariantsOnDetailsPage
#Articles demodata
REPLACE INTO `oxarticles` (`OXID`,  `OXMAPID`, `OXSHOPID`, `OXPARENTID`, `OXACTIVE`, `OXARTNUM`, `OXTITLE`,                     `OXSHORTDESC`,                   `OXPRICE`, `OXPRICEA`, `OXPRICEB`, `OXPRICEC`, `OXTPRICE`, `OXUNITNAME`, `OXUNITQUANTITY`, `OXVAT`, `OXWEIGHT`, `OXSTOCK`, `OXSTOCKFLAG`, `OXSTOCKTEXT`,   `OXNOSTOCKTEXT`,     `OXDELIVERY`, `OXINSERT`,   `OXTIMESTAMP`,        `OXLENGTH`, `OXWIDTH`, `OXHEIGHT`, `OXSEARCHKEYS`, `OXISSEARCH`, `OXVARNAME`,             `OXVARSTOCK`, `OXVARCOUNT`, `OXVARSELECT`, `OXVARMINPRICE`, `OXVARMAXPRICE`, `OXVARNAME_1`,             `OXVARSELECT_1`,   `OXTITLE_1`,                 `OXSHORTDESC_1`,                        `OXSEARCHKEYS_1`, `OXSUBCLASS`, `OXSTOCKTEXT_1`,       `OXNOSTOCKTEXT_1`,        `OXSORT`, `OXVENDORID`,      `OXMANUFACTURERID`, `OXVPE`, `OXMINDELTIME`, `OXMAXDELTIME`, `OXDELTIMEUNIT`) VALUES
                         ('10014',  111,       1,           '',            1,         '10014',    '13 DE product šÄßüл',         '14 DE description',              1.6,       0,          0,          0,          0,         '',            0,                NULL,    0,          0,         1,            '',              '',                  '0000-00-00', '2008-04-03', '2008-04-03 12:50:20', 0,          0,         0,         '',              1,          'size[DE] | color | type', 0,            12,          '',             15,               25,               'size[EN] | color | type', '',                '14 EN product šÄßüл',       '13 EN description šÄßüл',              '',               'oxarticle',  '',                    '',                        0,       '',                '',                  1,       0,              0,             '');

#demodata for multidimensional variants
REPLACE INTO `oxarticles` (`OXID`,   `OXMAPID`, `OXSHOPID`, `OXPARENTID`, `OXACTIVE`, `OXARTNUM`, `OXPRICE`, `OXVAT`, `OXSTOCK`, `OXSTOCKFLAG`, `OXINSERT`,   `OXTIMESTAMP`,         `OXVARSELECT`,               `OXVARSELECT_1`,        `OXSUBCLASS`, `OXSORT`) VALUES
                         ('1001432', 114,       1,           '10014',       1,         '10014-3-2', 15,        NULL,    3,         1,            '2008-04-03', '2008-04-03 12:50:20', 'M | black | material [DE]', 'L | black | material', 'oxarticle',   3002),
                         ('1001424', 115,       1,           '10014',       1,         '10014-2-4', 15,        NULL,    0,         1,            '2008-04-03', '2008-04-03 12:50:20', 'M | red [DE]',              'M | red',              'oxarticle',   2004),
                         ('1001422', 116,       1,           '10014',       1,         '10014-2-2', 15,        NULL,    0,         3,            '2008-04-03', '2008-04-03 12:50:20', 'M | black | material [DE]', 'M | black | material', 'oxarticle',   2002),
                         ('1001421', 117,       1,           '10014',       1,         '10014-2-1', 25,        NULL,    0,         2,            '2008-04-03', '2008-04-03 12:50:20', 'M | black | lether [DE]',   'M | black | lether',   'oxarticle',   2001),
                         ('1001411', 118,       1,           '10014',       1,         '10014-1-1', 25,        NULL,    3,         1,            '2008-04-03', '2008-04-03 12:50:20', 'S | black | lether [DE]',   'S | black | lether',   'oxarticle',   1001),
                         ('1001413', 119,       1,           '10014',       1,         '10014-1-3', 15,        NULL,    3,         1,            '2008-04-03', '2008-04-03 12:50:20', 'S | white [DE]',            'S | white',            'oxarticle',   1003),
                         ('1001412', 120,       1,           '10014',       1,         '10014-1-2', 15,        NULL,    3,         1,            '2008-04-03', '2008-04-03 12:50:20', 'S | black | material [DE]', 'S | black | material', 'oxarticle',   1002),
                         ('1001434', 121,       1,           '10014',       1,         '10014-3-4', 15,        NULL,    3,         1,            '2008-04-03', '2008-04-03 12:50:20', 'L | red [DE]',              'L | red',              'oxarticle',   3004),
                         ('1001423', 122,       1,           '10014',       1,         '10014-2-3', 15,        NULL,    0,         1,            '2008-04-03', '2008-04-03 12:50:20', 'M | white [DE]',            'M | white',            'oxarticle',   2003),
                         ('1001414', 123,       1,           '10014',       1,         '10014-1-4', 15,        NULL,    3,         1,            '2008-04-03', '2008-04-03 12:50:20', 'S | red [DE]',              'S | red',              'oxarticle',   1004),
                         ('1001431', 124,       1,           '10014',       1,         '10014-3-1', 15,        NULL,    3,         1,            '2008-04-03', '2008-04-03 12:50:20', 'L | black | lether [DE]',   'L | black | lether',   'oxarticle',   3001),
                         ('1001433', 125,       1,           '10014',       1,         '10014-3-3', 15,        NULL,    3,         1,            '2008-04-03', '2008-04-03 12:50:20', 'L | white [DE]',            'L | white',            'oxarticle',   3003);

REPLACE INTO `oxarticles2shop` (`OXSHOPID`, `OXMAPOBJECTID`) VALUES
  (1, 111), (1, 114), (1, 115), (1, 116), (1, 117), (1, 118), (1, 119), (1, 120), (1, 121), (1, 122),
  (1, 123), (1, 124), (1, 125);

#Articles long desc
REPLACE INTO `oxartextends` (`OXID`,   `OXLONGDESC`, `OXLONGDESC_1`) VALUES
('10014',  '',                                            '');

# for frontendMultidimensionalVariantsOnDetailsPage, createBasketUserAccountWithoutRegistration
#Articles demodata
REPLACE INTO `oxarticles` (`OXID`,  `OXMAPID`, `OXSHOPID`, `OXPARENTID`, `OXACTIVE`, `OXARTNUM`, `OXTITLE`,                     `OXSHORTDESC`,                   `OXPRICE`, `OXPRICEA`, `OXPRICEB`, `OXPRICEC`, `OXTPRICE`, `OXUNITNAME`, `OXUNITQUANTITY`, `OXVAT`, `OXWEIGHT`, `OXSTOCK`, `OXSTOCKFLAG`, `OXSTOCKTEXT`,   `OXNOSTOCKTEXT`,     `OXDELIVERY`, `OXINSERT`,   `OXTIMESTAMP`,        `OXLENGTH`, `OXWIDTH`, `OXHEIGHT`, `OXSEARCHKEYS`, `OXISSEARCH`, `OXVARNAME`,             `OXVARSTOCK`, `OXVARCOUNT`, `OXVARSELECT`, `OXVARMINPRICE`, `OXVARMAXPRICE`, `OXVARNAME_1`,             `OXVARSELECT_1`,   `OXTITLE_1`,                 `OXSHORTDESC_1`,                        `OXSEARCHKEYS_1`, `OXSUBCLASS`, `OXSTOCKTEXT_1`,       `OXNOSTOCKTEXT_1`,        `OXSORT`, `OXVENDORID`,      `OXMANUFACTURERID`, `OXVPE`, `OXMINDELTIME`, `OXMAXDELTIME`, `OXDELTIMEUNIT`) VALUES
                         ('1000',   101,       1,           '',            1,         '1000',     '[DE 4] Test product 0 šÄßüл', 'Test product 0 short desc [DE]', 50,        35,         45,         55,         0,         'kg',          2,                NULL,    2,          15,        1,            'In stock [DE]', 'Out of stock [DE]', '0000-00-00', '2008-02-04', '2008-02-04 17:07:29', 1,          2,         2,         'search1000',    1,           '',                       0,            0,           '',             50,                0,                '',                        '',                'Test product 0 [EN] šÄßüл', 'Test product 0 short desc [EN] šÄßüл', 'šÄßüл1000',      'oxarticle',  'In stock [EN] šÄßüл', 'Out of stock [EN] šÄßüл', 0,       'testdistributor', 'testmanufacturer',  1,       1,              1,             'DAY'),
                         ('1001',   102,       1,           '',            1,         '1001',     '[DE 1] Test product 1 šÄßüл', 'Test product 1 short desc [DE]', 100,       0,          0,          0,          150,       '',            0,                10,      0,          0,         1,            '',              '',                  '2030-01-01', '2008-02-04', '2008-02-04 17:35:43', 0,          0,         0,         'search1001',    1,          '',                        0,            0,           '',             100,               0,                '',                        '',                'Test product 1 [EN] šÄßüл', 'Test product 1 short desc [EN] šÄßüл', 'šÄßüл1001',      'oxarticle',  '',                    '',                        0,       'testdistributor', 'testmanufacturer',  1,       0,              1,             'WEEK'),
                         ('1002-1', 105,       1,           '1002',        1,         '1002-1',   '',                            '',                               55,        45,         0,          0,          0,         '',            0,                NULL,    0,          5,         1,            'In stock [DE]', 'Out of stock [DE]', '0000-00-00', '2008-02-04', '2008-02-04 17:36:53', 0,          0,         0,         '',              1,          '',                        0,            0,           'var1 [DE]',    0,                 0,                '',                        'var1 [EN] šÄßüл', '',                          '',                                     '',               'oxarticle',  'In stock [EN] šÄßüл', 'Out of stock [EN] šÄßüл', 1,       '',                '',                  1,       0,              0,             ''),
                         ('1002-2', 106,       1,           '1002',        1,         '1002-2',   '',                            '',                               67,        47,         0,          0,          0,         '',            0,                NULL,    0,          5,         1,            'In stock [DE]', 'Out of stock [DE]', '0000-00-00', '2008-02-04', '2008-02-04 17:38:11', 0,          0,         0,         '',              1,          '',                        0,            0,           'var2 [DE]',    0,                 0,                '',                        'var2 [EN] šÄßüл', '',                          '',                                     '',               'oxarticle',  'In stock [EN] šÄßüл', 'Out of stock [EN] šÄßüл', 2,       '',                '',                  1,       0,              0,             ''),
                         ('1002',   103,       1,           '',            1,         '1002',     '[DE 2] Test product 2 šÄßüл', 'Test product 2 short desc [DE]', 55,        0,          0,          0,          0,         '',            0,                NULL,    0,          0,         1,            'In stock [DE]', 'Out of stock [DE]', '0000-00-00', '2008-02-04', '2008-02-04 17:27:47', 0,          0,         0,         'search1002',    1,          'variants [DE]',           10,           2,           '',             55,                67,               'variants [EN] šÄßüл',     '',                'Test product 2 [EN] šÄßüл', 'Test product 2 short desc [EN] šÄßüл', 'šÄßüл1002',      'oxarticle',  'In stock [EN] šÄßüл', 'Out of stock [EN] šÄßüл', 0,       'testdistributor', 'testmanufacturer',  1,       1,              1,             'MONTH');

REPLACE INTO `oxarticles2shop` (`OXSHOPID`, `OXMAPOBJECTID`) VALUES
  (1, 101), (1, 102), (1, 103), (1, 105), (1, 106);

#Articles long desc
REPLACE INTO `oxartextends` (`OXID`,   `OXLONGDESC`,                                  `OXLONGDESC_1`) VALUES
                           ('1001',   '<p>Test product 1 long description [DE]</p>', '<p>Test product 1 long description [EN] šÄßüл</p>'),
                           ('1002',   '<p>Test product 2 long description [DE]</p>', '<p>Test product 2 long description [EN] šÄßüл</p>'),
                           ('1002-1', '',                                            ''),
                           ('1002-2', '',                                            ''),
                           ('1000',   '<p>Test product 0 long description [DE]</p>', '<p>Test product 0 long description [EN] šÄßüл</p>');

#Categories demodata
REPLACE INTO `oxcategories` (`OXID`,         `OXMAPID`, `OXPARENTID`,   `OXLEFT`, `OXRIGHT`, `OXROOTID`,     `OXSORT`, `OXACTIVE`, `OXHIDDEN`, `OXSHOPID`, `OXTITLE`,                    `OXDESC`,                    `OXLONGDESC`,                `OXDEFSORT`, `OXDEFSORTMODE`, `OXPRICEFROM`, `OXPRICETO`, `OXACTIVE_1`, `OXTITLE_1`,                  `OXDESC_1`,                        `OXLONGDESC_1`,                   `OXVAT`, `OXSKIPDISCOUNTS`, `OXSHOWSUFFIX`) VALUES
                           ('testcategory0', 101,        'oxrootid',      1,        4,        'testcategory0', 1,        1,          0,          1,           'Test category 0 [DE] šÄßüл', 'Test category 0 desc [DE]', 'Category 0 long desc [DE]', 'oxartnum',   0,               0,             0,           1,           'Test category 0 [EN] šÄßüл', 'Test category 0 desc [EN] šÄßüл', 'Category 0 long desc [EN] šÄßüл', 5,       0,                 1),
                           ('testcategory1', 102,        'testcategory0', 2,        3,        'testcategory0', 2,        1,          0,          1,           'Test category 1 [DE] šÄßüл', 'Test category 1 desc [DE]', 'Category 1 long desc [DE]', 'oxartnum',   1,               0,             0,           1,           'Test category 1 [EN] šÄßüл', 'Test category 1 desc [EN] šÄßüл', 'Category 1 long desc [EN] šÄßüл', NULL,    0,                 1);
REPLACE INTO `oxcategories2shop` (`OXSHOPID`, `OXMAPOBJECTID`) VALUES
  (1, 101), (1, 102);

#article2category
REPLACE INTO `oxobject2category` (`OXID`, `OXSHOPID`, `OXOBJECTID`, `OXCATNID`,     `OXPOS`, `OXTIME`) VALUES
                                ('96047a71f4d4e34d9.76958590',       1,           '1000',       'testcategory0', 0,       1202134861),
                                ('testobject2category',              1,           '1001',       'testcategory0', 0,       1202134867);

#User demodata
REPLACE INTO `oxuser` (`OXID`,     `OXACTIVE`, `OXRIGHTS`, `OXSHOPID`, `OXUSERNAME`,         `OXPASSWORD`,                       `OXPASSSALT`,        `OXCUSTNR`, `OXCOMPANY`,          `OXFNAME`,        `OXLNAME`,           `OXSTREET`,        `OXSTREETNR`, `OXADDINFO`,                   `OXCITY`,            `OXCOUNTRYID`,                `OXZIP`,  `OXFON`,       `OXFAX`,       `OXSAL`, `OXBONI`, `OXCREATE`,            `OXREGISTER`,          `OXPRIVFON`,   `OXMOBFON`,    `OXBIRTHDATE`) VALUES
                     ('testuser',  1,         'user',      1,         'example_test@oxid-esales.dev', 'c9dadd994241c9e5fa6469547009328a', '7573657275736572',   8,         'UserCompany šÄßüл',  'UserNamešÄßüл',  'UserSurnamešÄßüл',  'Musterstr.šÄßüл', '1',          'User additional info šÄßüл',  'Musterstadt šÄßüл', 'a7c40f631fc920687.20179984', '79098',  '0800 111111', '0800 111112', 'Mr',     500,     '2008-02-05 14:42:42', '2008-02-05 14:42:42', '0800 111113', '0800 111114', '1980-01-01');

#object2Group
REPLACE INTO `oxobject2group` (`OXID`,                       `OXSHOPID`,   `OXOBJECTID`,   `OXGROUPSID`) VALUES
                             ('15947a85a7ce23451.42160470', 1, 'testuser',     'oxidnewcustomer');

# createBasketUserAccountWithoutRegistration
#adding states for germany
REPLACE INTO `oxstates` (`OXID`, `OXCOUNTRYID`, `OXTITLE`, `OXISOALPHA2`, `OXTITLE_1`, `OXTITLE_2`, `OXTITLE_3`) VALUES
('BB', 'a7c40f631fc920687.20179984', 'Brandenburg', 'BB', 'Brandenburg', '', ''),
('BE', 'a7c40f631fc920687.20179984', 'Berlin', 'BE', 'Berlin', '', ''),
('BW', 'a7c40f631fc920687.20179984', 'Baden-Württemberg', 'BW', 'Baden-Wurttemberg', '', ''),
('BY', 'a7c40f631fc920687.20179984', 'Bayern', 'BY', 'Bavaria', '', ''),
('HB', 'a7c40f631fc920687.20179984', 'Bremen', 'HB', 'Bremen', '', ''),
('HE', 'a7c40f631fc920687.20179984', 'Hessen', 'HE', 'Hesse', '', ''),
('HH', 'a7c40f631fc920687.20179984', 'Hamburg', 'HH', 'Hamburg', '', ''),
('MV', 'a7c40f631fc920687.20179984', 'Mecklenburg-Vorpommern', 'MV', 'Mecklenburg-Western Pomerania', '', ''),
('NI', 'a7c40f631fc920687.20179984', 'Niedersachsen', 'NI', 'Lower Saxony', '', ''),
('NW', 'a7c40f631fc920687.20179984', 'Nordrhein-Westfalen', 'NW', 'North Rhine-Westphalia', '', ''),
('RP', 'a7c40f631fc920687.20179984', 'Rheinland-Pfalz', 'RP', 'Rhineland-Palatinate', '', ''),
('SH', 'a7c40f631fc920687.20179984', 'Schleswig-Holstein', 'SH', 'Schleswig-Holstein', '', ''),
('SL', 'a7c40f631fc920687.20179984', 'Saarland', 'SL', 'Saarland', '', ''),
('SN', 'a7c40f631fc920687.20179984', 'Sachsen', 'SN', 'Saxony', '', ''),
('ST', 'a7c40f631fc920687.20179984', 'Sachsen-Anhalt', 'ST', 'Saxony-Anhalt', '', ''),
('TH', 'a7c40f631fc920687.20179984', 'Thüringen', 'TH', 'Thuringia', '', '');


# createBasketUserAccountWithoutRegistration
UPDATE `oxconfig` SET `OXVARVALUE` = 0xde         WHERE `OXVARNAME` = 'iNewBasketItemMessage';
REPLACE INTO `oxconfig` (`OXID`, `OXSHOPID`, `OXMODULE`,   `OXVARNAME`,                     `OXVARTYPE`, `OXVARVALUE`) VALUES
                       ('4742', 1, '', 'blPerfNoBasketSaving',          'bool',       0x93ea1218),
                       ('8563fba1965a219c9.51133344', 1, '', 'blUseStock',          'bool',       0x93ea1218);

# createBasketUserAccountWithoutRegistrationTwice
UPDATE `oxcountry` SET `OXACTIVE` = 1 WHERE `OXTITLE_1` = 'Belgium';

# userCompareList
#Attributes demodata
REPLACE INTO `oxattribute` (`OXID`,          `OXMAPID`, `OXSHOPID`, `OXTITLE`,                     `OXTITLE_1`,                  `OXPOS`) VALUES
                          ('testattribute1', 101,       1,           'Test attribute 1 [DE] šÄßüл', 'Test attribute 1 [EN] šÄßüл', 1),
                          ('testattribute2', 102,       1,           'Test attribute 2 [DE] šÄßüл', 'Test attribute 2 [EN] šÄßüл', 3),
                          ('testattribute3', 103,       1,           'Test attribute 3 [DE] šÄßüл', 'Test attribute 3 [EN] šÄßüл', 2);

REPLACE INTO `oxattribute2shop` (`OXSHOPID`, `OXMAPOBJECTID`) VALUES
  (1, 101), (1, 102), (1, 103);

UPDATE `oxattribute` SET `OXDISPLAYINBASKET` = 0;

#article2attribute
REPLACE INTO `oxobject2attribute` (`OXID`,                       `OXOBJECTID`, `OXATTRID`,       `OXVALUE`,           `OXPOS`, `OXVALUE_1`) VALUES
                                 ('15947a851058b2082.70204859', '1001',       'testattribute1', 'attr value 11 [DE]', 0,      'attr value 11 [EN] šÄßüл'),
                                 ('15947a851058bda82.26304276', '1000',       'testattribute1', 'attr value 1 [DE]',  0,      'attr value 1 [EN] šÄßüл'),
                                 ('15947a8510d0b8da1.03431546', '1001',       'testattribute2', 'attr value 12 [DE]', 0,      'attr value 12 [EN] šÄßüл'),
                                 ('15947a8510d1029d6.13172117', '1000',       'testattribute2', 'attr value 2 [DE]',  0,      'attr value 2 [EN] šÄßüл'),
                                 ('15947a85117af8781.96104704', '1001',       'testattribute3', 'attr value 3 [DE]',  0,      'attr value 3 [EN] šÄßüл'),
                                 ('15947a85117b03a35.88871161', '1000',       'testattribute3', 'attr value 3 [DE]',  0,      'attr value 3 [EN] šÄßüл');

#set country, username, password for default user
UPDATE oxuser
  SET
      oxcountryid = 'a7c40f631fc920687.20179984',
      oxusername = 'admin@myoxideshop.com',
      oxpassword = '6cb4a34e1b66d3445108cd91b67f98b9',
      oxpasssalt = '6631386565336161636139613634663766383538633566623662613036636539'
  WHERE OXUSERNAME='admin';
REPLACE INTO `oxdiscount` (`OXID`,           `OXMAPID`, `OXSHOPID`, `OXACTIVE`, `OXTITLE`,                          `OXTITLE_1`,                       `OXAMOUNT`, `OXAMOUNTTO`, `OXPRICETO`, `OXPRICE`, `OXADDSUMTYPE`, `OXADDSUM`, `OXITMARTID`, `OXITMAMOUNT`, `OXITMMULTIPLE`, `OXSORT`) VALUES
                         ('testcatdiscount', 101,       1,            0,         'discount for category [DE] šÄßüл', 'discount for category [EN] šÄßüл', 1,          999999,       0,           0,        'abs',           5,         '',            0,             0,             100);

REPLACE INTO `oxdiscount2shop` (`OXSHOPID`, `OXMAPOBJECTID`) VALUES
  (1, 101);


#object2discount
REPLACE INTO `oxobject2discount` (`OXID`,                       `OXDISCOUNTID`,    `OXOBJECTID`,                 `OXTYPE`) VALUES
                                ('bde47a823ca721245.91816191', 'testcatdiscount', 'a7c40f631fc920687.20179984', 'oxcountry'),
                                ('bde47a823db7d82f5.99715633', 'testcatdiscount', 'testcategory0',              'oxcategories');
#Coupons demodata
REPLACE INTO `oxvoucherseries` (`OXID`,        `OXMAPID`, `OXSHOPID`, `OXSERIENR`,           `OXSERIEDESCRIPTION`,      `OXDISCOUNT`, `OXDISCOUNTTYPE`, `OXBEGINDATE`,         `OXENDDATE`,          `OXALLOWSAMESERIES`, `OXALLOWOTHERSERIES`, `OXALLOWUSEANOTHER`, `OXMINIMUMVALUE`, `OXCALCULATEONCE`) VALUES
                              ('testvoucher4', 106,       1,           '4 Coupon šÄßüл',      '4 Coupon šÄßüл',           50.00,       'percent',        '2008-01-01 00:00:00', now() + interval 1 day, 0,                   0,                    0,                   45.00,            1);

REPLACE INTO `oxvoucherseries2shop` (`OXSHOPID`, `OXMAPOBJECTID`) VALUES
  (1, 106);


REPLACE INTO `oxvouchers` (`OXDATEUSED`, `OXORDERID`, `OXUSERID`, `OXRESERVED`, `OXVOUCHERNR`, `OXVOUCHERSERIEID`, `OXDISCOUNT`, `OXID`) VALUES
                         ('0000-00-00', '',          '',          0,           '123123',      'testvoucher4',      NULL,        'testcoucher011');

#Gift wrapping demodata
REPLACE INTO `oxwrapping` (`OXID`,        `OXMAPID`, `OXSHOPID`, `OXACTIVE`, `OXACTIVE_1`, `OXTYPE`, `OXNAME`,                        `OXNAME_1`,                      `OXPRICE`) VALUES
                         ('testwrapping', 101,       1,            1,          1,           'WRAP',   'Test wrapping [DE] šÄßüл',      'Test wrapping [EN] šÄßüл',       0.9),
                         ('testcard',     102,       1,            1,          1,           'CARD',   'Test card [DE] šÄßüл',          'Test card [EN] šÄßüл',           0.2);

REPLACE INTO `oxwrapping2shop` (`OXSHOPID`, `OXMAPOBJECTID`) VALUES
  (1, 101), (1, 102);

#Select list demodata
REPLACE INTO `oxselectlist` (`OXID`,       `OXMAPID`,    `OXSHOPID`,   `OXTITLE`,                        `OXIDENT`,              `OXVALDESC`,                                                                      `OXTITLE_1`,                      `OXVALDESC_1`) VALUES
                           ('testsellist',   101,        1, 'test selection list [DE] šÄßüл', 'test sellist šÄßüл',   'selvar1 [DE]!P!1__@@selvar2 [DE]__@@selvar3 [DE]!P!-2__@@selvar4 [DE]!P!2%__@@', 'test selection list [EN] šÄßüл', 'selvar1 [EN] šÄßüл!P!1__@@selvar2 [EN] šÄßüл__@@selvar3 [EN] šÄßüл!P!-2__@@selvar4 [EN] šÄßüл!P!2%__@@');

REPLACE INTO `oxselectlist2shop` (`OXSHOPID`, `OXMAPOBJECTID`) VALUES
  (1, 101);

#Article2SelectList
REPLACE INTO `oxobject2selectlist` (`OXID`,                       `OXOBJECTID`, `OXSELNID`,   `OXSORT`) VALUES
                                  ('testsellist.1001', '1001',       'testsellist', 0);
