<?php
/*
 * Test adding article to one shop.
 */
return array(
    'shops'           => array(
        array(
            'oxid'          => 2,
            'oxname'        => 'Subshop 2',
            'oxparentid'    => 0,
            'oxisinherited' => 0,
        ),
        array(
            'oxid'          => 3,
            'oxname'        => 'Subshop 3',
            'oxparentid'    => 2,
            'oxisinherited' => 1,
        ),
        array(
            'oxid'          => 4,
            'oxname'        => 'Subshop 4',
            'oxparentid'    => 1,
            'oxisinherited' => 1,
        ),
        array(
            'oxid'          => 5,
            'oxname'        => 'Subshop 5',
            'oxparentid'    => 2,
            'oxisinherited' => 0,
        ),
    ),
    'articles'        => array(
        array(
            'oxid'               => '_testArticle1',
            'oxshopid'           => 1,
            'oxinheritedshopids' => array(2),
        ),
        array(
            'oxid'               => '_testVariant1',
            'oxparentid'         => '_testArticle1',
            'oxshopid'           => 1,
            'oxinheritedshopids' => array(2),
        ),
    ),
    'categories'      => array(
        array(
            'oxid'       => '_testCat1',
            'oxrootid'   => '_testCat1',
            'oxparentid' => 'oxrootid',
            'oxleft'     => 1,
            'oxright'    => 10,
            'oxshopid'   => 1
        ),
        array(
            'oxid'       => '_testCat2',
            'oxrootid'   => '_testCat1',
            'oxparentid' => '_testCat1',
            'oxleft'     => 2,
            'oxright'    => 7,
            'oxshopid'   => 1
        ),
        array(
            'oxid'       => '_testCat3',
            'oxrootid'   => '_testCat1',
            'oxparentid' => '_testCat1',
            'oxleft'     => 8,
            'oxright'    => 9,
            'oxshopid'   => 1
        ),
        array(
            'oxid'       => '_testCat4',
            'oxrootid'   => '_testCat1',
            'oxparentid' => '_testCat2',
            'oxleft'     => 3,
            'oxright'    => 4,
            'oxshopid'   => 1
        ),
        array(
            'oxid'       => '_testCat5',
            'oxrootid'   => '_testCat1',
            'oxparentid' => '_testCat2',
            'oxleft'     => 5,
            'oxright'    => 6,
            'oxshopid'   => 1
        ),
        array(
            'oxid'       => '_testCat6',
            'oxrootid'   => '_testCat6',
            'oxparentid' => 'oxrootid',
            'oxleft'     => 1,
            'oxright'    => 2,
            'oxshopid'   => 1
        ),
    ),
    'object2category' => array(
        array(
            'oxid'       => 1,
            'oxshopid'   => 1,
            'oxobjectid' => '_testArticle1',
            'oxcatnid'   => '_testCat1',
        ),
        array(
            'oxid'       => 2,
            'oxshopid'   => 1,
            'oxobjectid' => '_testArticle1',
            'oxcatnid'   => '_testCat2',
        ),
        array(
            'oxid'       => 3,
            'oxshopid'   => 1,
            'oxobjectid' => '_testArticle1',
            'oxcatnid'   => '_testCat4',
        ),

    ),
);
