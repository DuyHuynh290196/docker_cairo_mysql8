<?php
/*
 * Test removing article from all shops.
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
    ),
    'setup'    => array(
        'articles2shop' => array(
            '_testArticle1' => array(2, 4),
        ),
    ),
    'actions'  => array(
        'remove_from_all_shops' => array(
            '_testArticle1',
        ),
    ),
    'expected' => array(
        'article_in_shop'     => array(
            '_testArticle1' => array(),
        ),
        'article_not_in_shop' => array(
            '_testArticle1' => array(1, 2, 3, 4),
        ),
    ),
);
