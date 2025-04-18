<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

/**
 * Smarty modifier
 * -------------------------------------------------------------
 * Name:     oxenclose<br>
 * Purpose:  {$var|oxenclose:"'"}
 * -------------------------------------------------------------
 *
 * @param string $sString   string to enclose
 * @param string $sEncloser enclose with
 * @deprecated will be moved to the separate smarty component
 * @return string
 */
function smarty_modifier_oxenclose($sString, $sEncloser = "'")
{
    return $sEncloser . $sString . $sEncloser;
}
