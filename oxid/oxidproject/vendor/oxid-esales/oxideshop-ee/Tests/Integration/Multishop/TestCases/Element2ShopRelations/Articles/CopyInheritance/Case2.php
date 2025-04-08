<?php
/*
 * Test copy inheritance information from one item to another.
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
            '_testArticle2' => array(),
        ),
    ),
    'actions'  => array(
        'copy_inheritance' => array(
            '_testArticle2' => '_testArticle1',
        ),
    ),
    'expected' => array(
        'article_in_shop'     => array(
            '_testArticle1' => array(1, 2),
            '_testArticle2' => array(1, 2),
        ),
        'article_not_in_shop' => array(
            '_testArticle1' => array(3, 4),
            '_testArticle2' => array(3, 4),
        ),
    ),
);
