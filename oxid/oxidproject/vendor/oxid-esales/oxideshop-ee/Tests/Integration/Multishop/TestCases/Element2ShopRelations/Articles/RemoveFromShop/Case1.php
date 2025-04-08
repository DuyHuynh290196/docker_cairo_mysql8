<?php
/*
 * Test removing article from one shop.
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
            '_testArticle1' => array(2),
        ),
    ),
    'actions'  => array(
        'remove_from_shop' => array(
            '_testArticle1' => array(2),
        ),
    ),
    'expected' => array(
        'article_in_shop'     => array(
            '_testArticle1' => array(1),
        ),
        'article_not_in_shop' => array(
            '_testArticle1' => array(2, 3, 4),
        ),
    ),
);
