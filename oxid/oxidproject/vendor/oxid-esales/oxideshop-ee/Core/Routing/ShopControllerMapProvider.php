<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Core\Routing;

/**
 * @inheritdoc
 *
 * @internal Do not make a module extension for this class.
 */
class ShopControllerMapProvider extends \OxidEsales\EshopProfessional\Core\Routing\ShopControllerMapProvider
{
    private $controllerMap = [
        'account_noticelist'                  => \OxidEsales\Eshop\Application\Controller\AccountNoticeListController::class,
        'admin_beroles'                       => \OxidEsales\Eshop\Application\Controller\Admin\AdminBackEndRoles::class,
        'admin_feroles'                       => \OxidEsales\Eshop\Application\Controller\Admin\AdminFrontEndRoles::class,
        'admin_mall'                          => \OxidEsales\Eshop\Application\Controller\Admin\AdminMall::class,
        'adminlinks_mall'                     => \OxidEsales\Eshop\Application\Controller\Admin\AdminLinksMall::class,
        'article_mall'                        => \OxidEsales\Eshop\Application\Controller\Admin\ArticleMall::class,
        'article_rights_visible_ajax'         => \OxidEsales\Eshop\Application\Controller\Admin\ArticleRightsVisibleAjax::class,
        'article_rights_buyable_ajax'         => \OxidEsales\Eshop\Application\Controller\Admin\ArticleRightsBuyableAjax::class,
        'article_rights'                      => \OxidEsales\Eshop\Application\Controller\Admin\ArticleRights::class,
        'attribute_mall'                      => \OxidEsales\Eshop\Application\Controller\Admin\AttributeMall::class,
        'discount_mall'                       => \OxidEsales\Eshop\Application\Controller\Admin\DiscountMall::class,
        'delivery_mall'                       => \OxidEsales\Eshop\Application\Controller\Admin\DeliveryMall::class,
        'deliveryset_mall'                    => \OxidEsales\Eshop\Application\Controller\Admin\DeliverySetMall::class,
        'category_mall'                       => \OxidEsales\Eshop\Application\Controller\Admin\CategoryMall::class,
        'category_rights'                     => \OxidEsales\Eshop\Application\Controller\Admin\CategoryRights::class,
        'category_rights_buyable_ajax'        => \OxidEsales\Eshop\Application\Controller\Admin\CategoryRightsBuyableAjax::class,
        'category_rights_visible_ajax'        => \OxidEsales\Eshop\Application\Controller\Admin\CategoryRightsVisibleAjax::class,
        'news_mall'                           => \OxidEsales\Eshop\Application\Controller\Admin\NewsMall::class,
        'manufacturer_mall'                   => \OxidEsales\Eshop\Application\Controller\Admin\ManufacturerMall::class,
        'roles_femain'                        => \OxidEsales\Eshop\Application\Controller\Admin\RolesFrontendMain::class,
        'roles_feuser'                        => \OxidEsales\Eshop\Application\Controller\Admin\RolesFrontendUser::class,
        'selectlist_mall'                     => \OxidEsales\Eshop\Application\Controller\Admin\SelectListMall::class,
        'shop_cache'                          => \OxidEsales\Eshop\Application\Controller\Admin\ShopCache::class,
        'roles_begroups_ajax'                 => \OxidEsales\Eshop\Application\Controller\Admin\RolesBackendGroupsAjax::class,
        'roles_belist'                        => \OxidEsales\Eshop\Application\Controller\Admin\RolesBackendList::class,
        'roles_bemain'                        => \OxidEsales\Eshop\Application\Controller\Admin\RolesBackendMain::class,
        'roles_beobject'                      => \OxidEsales\Eshop\Application\Controller\Admin\RolesBackendObject::class,
        'roles_beuser'                        => \OxidEsales\Eshop\Application\Controller\Admin\RolesBackendUser::class,
        'roles_beuser_ajax'                   => \OxidEsales\Eshop\Application\Controller\Admin\RolesBackendUserAjax::class,
        'roles_fegroups_ajax'                 => \OxidEsales\Eshop\Application\Controller\Admin\RolesFrontendGroupsAjax::class,
        'roles_felist'                        => \OxidEsales\Eshop\Application\Controller\Admin\RolesFrontendList::class,
        'shop_mall'                           => \OxidEsales\Eshop\Application\Controller\Admin\ShopMall::class,
        'vendor_mall'                         => \OxidEsales\Eshop\Application\Controller\Admin\VendorMall::class,
        'voucherserie_mall'                   => \OxidEsales\Eshop\Application\Controller\Admin\VoucherSerieMall::class,
        'wrapping_mall'                       => \OxidEsales\Eshop\Application\Controller\Admin\WrappingMall::class,
        'mallstart'                           => \OxidEsales\Eshop\Application\Controller\MallStartController::class,
    ];

    /**
     * @inheritdoc
     */
    public function getControllerMap()
    {
        return array_merge(parent::getControllerMap(), $this->controllerMap);
    }
}
