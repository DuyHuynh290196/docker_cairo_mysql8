<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

/**
 * Smarty function
 * -------------------------------------------------------------
 * Purpose: set params and render widget
 * Use [{oxid_include_dynamic file="..."}] instead of include
 * -------------------------------------------------------------
 *
 * @param array  $params  Params
 * @param Smarty $oSmarty Clever simulation of a method
 * @deprecated will be moved to the separate smarty component
 * @return string
 */
function smarty_function_oxid_include_widget($params, &$oSmarty)
{
    $class = isset($params['cl']) ? strtolower($params['cl']) : '';
    unset($params['cl']);

    $parentViews = null;
    if (!empty($params["_parent"])) {
        $parentViews = explode("|", $params["_parent"]);
        unset($params["_parent"]);
    }

    $widgetControl = oxNew(\OxidEsales\Eshop\Core\WidgetControl::class);
    return $widgetControl->start($class, null, $params, $parentViews);
}
