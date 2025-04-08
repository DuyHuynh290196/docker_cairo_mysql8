<?php
/**
 * This Software is the property of OXID eSales and is protected
 * by copyright law.
 *
 * Any unauthorized use of this software will be prosecuted by
 * civil and criminal law.
 *
 * @link      http://www.oxid-esales.com
 * @copyright (C) OXID eSales AG 2003-2017
 * @version   OXID eSales Visual CMS
 */

namespace OxidEsales\VisualCmsModule\Application\Component;

/**
 * Class BasketComponent
 * Adds oxcid and oxloadid to redirect parameter
 *
 * @mixin   \OxidEsales\Eshop\Application\Component\BasketComponent
 */
class BasketComponent extends BasketComponent_parent
{

    public function init()
    {
        $this->aRedirectParams[] = 'oxcid';
        $this->aRedirectParams[] = 'oxloadid';

        parent::init();
    }

}