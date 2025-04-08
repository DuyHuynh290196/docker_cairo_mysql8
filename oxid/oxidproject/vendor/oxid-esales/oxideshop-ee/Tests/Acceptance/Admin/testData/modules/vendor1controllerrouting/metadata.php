<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

/**
 * Metadata version
 */
$sMetadataVersion = '2.0';

/**
 * Module information
 */
$aModule = array(
    'id'           => 'vendor1ControllerRouting',
    'title'        => 'Test metadata v2 vendor1 controllers feature for EE',
    'description'  => '',
    'thumbnail'    => 'picture.png',
    'version'      => '1.0',
    'author'       => 'OXID eSales AG',
    'controllers'  => [
        'vendor1_controllerrouting_mymodulecontroller' => OxidEsales\EshopEnterprise\Tests\Acceptance\Admin\testData\modules\vendor1controllerrouting\MyModuleController::class
    ],
    'templates' => [
        'vendor1_controller_routing.tpl' => 'vendor1controllerrouting/vendor1_controller_routing.tpl'
    ]
);
