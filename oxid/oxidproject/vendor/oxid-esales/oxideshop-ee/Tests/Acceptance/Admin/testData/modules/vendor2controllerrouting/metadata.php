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
    'id'           => 'vendor2ControllerRouting',
    'title'        => 'Test metadata v2 vendor2 controllers feature for EE',
    'description'  => '',
    'thumbnail'    => 'picture.png',
    'version'      => '1.0',
    'author'       => 'OXID eSales AG',
    'controllers'  => [
        'vendor2_controllerrouting_mymodulecontroller' => OxidEsales\EshopEnterprise\Tests\Acceptance\Admin\testData\modules\vendor2controllerrouting\MyModuleController::class
    ],
    'templates' => [
        'vendor2_controller_routing.tpl' => 'vendor2controllerrouting/vendor2_controller_routing.tpl'
    ]
);
