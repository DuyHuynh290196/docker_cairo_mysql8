<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Application\Controller;

use OxidEsales\Eshop\Core\Registry;

/**
 * Starting mall window with list of active shops.
 * Arranges list of running shops in current mall. Shops are sorted by categories
 * (if available). This class is only executed if current license enables shopping
 * mall ability.
 */
class MallStartController extends \OxidEsales\Eshop\Application\Controller\FrontendController
{
    /**
     * Current class template name.
     *
     * @var string
     */
    protected $_sThisTemplate = 'page/shop/mallstart.tpl';

    /**
     * Shop links
     *
     * @var array
     */
    protected $_aShopLinks = null;

    /**
     * Shop default langs
     *
     * @var array
     */
    protected $_aShopDefaultLangs = null;

    /**
     * Shoplist
     *
     * @var object
     */
    protected $_oShopList = null;

    /**
     * Sets id of current session shop and returns "start".
     *
     * @return string
     */
    public function chshp()
    {
        //racalculate basket if changing shops
        $sActShop = \OxidEsales\Eshop\Core\Registry::getConfig()->getRequestParameter('shp');
        if ($sActShop && $sActShop != \OxidEsales\Eshop\Core\Registry::getSession()->getVariable('actshop')) {
            //performs actions required on shop change
            $this->getConfig()->onShopChange();
        }

        \OxidEsales\Eshop\Core\Registry::getSession()->setVariable('actshop', $sActShop);

        return "start";
    }

    /**
     * Template variable getter. Returns shop links
     *
     * @return array
     */
    public function getShopLinks()
    {
        if ($this->_aShopLinks === null) {
            $this->_aShopLinks = false;
            $aShopLinks = array();
            $oShoplist = $this->getShopList();
            foreach ($oShoplist as $sId => $oShop) {
                $aShopLinks[$sId] = $this->getConfig()->getShopConfVar('sMallShopURL', $sId);
            }
            $this->_aShopLinks = $aShopLinks;
        }

        return $this->_aShopLinks;
    }

    /**
     * Template variable getter. Returns shop links
     *
     * @return array
     */
    public function getShopDefaultLangs()
    {
        if ($this->_aShopDefaultLangs === null) {
            $this->_aShopDefaultLangs = false;
            $aShopDefaultLangs = array();
            $oShoplist = $this->getShopList();
            foreach ($oShoplist as $sId => $oShop) {
                $aShopDefaultLangs[$sId] = $this->getConfig()->getShopConfVar('sDefaultLang', $sId);
            }
            $this->_aShopDefaultLangs = $aShopDefaultLangs;
        }

        return $this->_aShopDefaultLangs;
    }

    /**
     * Template variable getter. Returns shoplist
     *
     * @return object
     */
    public function getShopList()
    {
        if ($this->_oShopList === null) {
            $this->_oShopList = false;
            $oShoplist = oxNew(\OxidEsales\Eshop\Application\Model\ShopList::class);
            $oShoplist->getList();
            $this->_oShopList = $oShoplist;
        }

        return $this->_oShopList;
    }
}
