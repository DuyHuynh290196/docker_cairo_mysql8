<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

$sMetadataVersion = '2.1';
$aModule = [
    'id' => 'chainGeneration/legacy',
    'title' => 'Legacy module',
    'description' => 'Module without namespaces',
    'thumbnail' => 'picture.png',
    'version' => '1.0',
    'author' => 'OXID eSales AG',
    'extend' => [
        'oxarticle' => 'chainGeneration/legacy/model/module_article',
    ],
];
