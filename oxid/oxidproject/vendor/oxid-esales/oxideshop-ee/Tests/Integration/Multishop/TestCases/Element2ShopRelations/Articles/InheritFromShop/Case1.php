<?php
/*
 * Test inherit items by type to sub shop(-s) from parent shop.
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
            '_testArticle1' => array(2),
            '_testArticle2' => array(3),
        ),
    ),
    'actions'  => array(
        'inherit_from_shop' => array(
            3 => 2,
        ),
    ),
    'expected' => array(
        'article_in_shop'     => array(
            '_testArticle1' => array(1, 2, 3),
            '_testArticle2' => array(1, 3),
        ),
        'article_not_in_shop' => array(
            '_testArticle1' => array(4),
            '_testArticle2' => array(2, 4),
        ),
    ),
);
