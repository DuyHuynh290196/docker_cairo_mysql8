<?php
/*
 * Test remove items by type from sub shop(-s) that were inherited from parent shop.
 */
return array(
    'shops'    => array(
        array(
            'oxname' => '_testShop2',
        ),
        array(
            'oxname' => '_testShop3',
        ),
        array(
            'oxname' => '_testShop4',
        ),
    ),
    'articles' => array(
        array(
            'oxid'     => '_testArticle1',
            'oxshopid' => 1,
        ),
        array(
            'oxid'     => '_testArticle2',
            'oxshopid' => 1,
        ),
    ),
    'setup'    => array(
        'articles2shop' => array(
            '_testArticle1' => array(2, 3),
            '_testArticle2' => array(3, 4),
        ),
    ),
    'actions'  => array(
        'remove_inherited_from_shop' => array(
            3 => 1,
        ),
    ),
    'expected' => array(
        'article_in_shop'     => array(
            '_testArticle1' => array(1, 2),
            '_testArticle2' => array(1, 4),
        ),
        'article_not_in_shop' => array(
            '_testArticle1' => array(3, 4),
            '_testArticle2' => array(2, 3),
        ),
    ),
);
