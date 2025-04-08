# Change Log for OXID eShop Enterprise Edition Core Component

All notable changes to this project will be documented in this file.
The format is based on [Keep a Changelog](http://keepachangelog.com/)
and this project adheres to [Semantic Versioning](http://semver.org/).

## [6.8.1] - 2023-02-14

### Changed
- Configuration disallowForceSessionIdInRequest to disable processing of force_sid URL parameter

### Fixed
- Error in Chrome accessing navigation admin frame in JavaScript via `top.navigation`
- Session start outside of session class
- Category cache generation

## [6.8.0] - 2022-07-20

### Added
- Support Symfony v5

## [6.7.1] - 2022-05-17

### Changed
- Remove OXIDforge links

## [6.7.0] - 2021-12-02

### Fixed
- Query for one item only in oxobjectrights table check [PR-34](https://github.com/OXID-eSales/oxideshop_ee/pull/34)
- Admin link to "Shop's start page" with multiple shops [#0007202](https://bugs.oxid-esales.com/view.php?id=7202)

### Removed
- Support for PHP 7.3

### Deprecated
- `Core\Model\BaseModel::getRecordByQuery()`

## [6.6.2] - 2022-05-17

### Changed
- Remove OXIDforge links

## [6.6.1] - 2021-07-27

### Fixed
- Deprecation messages

## [6.6.0] - 2021-04-12

### Changed
- Backend Cache: Always read from database in admin area. [#0005659](https://bugs.oxid-esales.com/view.php?id=5659)

## [6.5.5] - 2021-03-25

### Fixed
- Improved the ContentCache add query stability. Changed from `REPLACE INTO` to `INSERT INTO ON DUPLICATE KEY UPDATE`
- Updating Category Products' sorting in subshops [#0007155](https://bugs.oxid-esales.com/view.php?id=7155)

## [6.5.4] - 2020-11-10

### Deprecated
- `OxidEsales\EshopEnterprise\Setup\Database::setIfDynamicPagesShouldBeUsed()`

## [6.5.3] - 2020-07-14

### Deprecated
- Methods starting with underscore have been deprecated, these methods will be renamed
- News feature:
    - Classes:
        - `Application/Controller/Admin/NewsMainAjax`
        - `Application/Controller/Admin/NewsMall`
        - `Application/Model/News`
    - Config options:
        - `bl_perfLoadNewsOnlyStart`
        - `bl_perfLoadNews`
        - `sCntOfNewsLoaded`

### Renamed 
- Renamed price alert to wished price

### Fixed
- Fix Cannot edit product description in subshop [#0006987](https://bugs.oxid-esales.com/view.php?id=6987)

## [6.5.2] - 2020-04-21

### Added
- PHPCodeSniffer and PSR12 Coding guidelines

### Deprecated
- Suggest (Recommend Product) feature

## [6.5.1] - 2020-03-16

### Fixed
- Issue with session ID regeneration on user registration

## [6.5.0] - 2020-02-25

### Added
- Add support for PHP 7.3 and 7.4

### Fixed
- Correct third party link in license [#0005524](https://bugs.oxid-esales.com/view.php?id=5524)
- Fix error on DB/PHP timezone discrepancy

## [6.4.0] - 2019-11-07

### Added
- `OxidEsales\Eshop\Internal\Framework\Database\QueryBuilderFactoryInterface`

### Changed
- Most of DELETE queries do use prepared statements
- Most of INSERT queries do use prepared statements
- Most of UPDATE queries do use prepared statements

### Deprecated
- `OxidEsales\EshopEnterprise\Core\DatabaseProvider`
- `OxidEsales\EshopEnterprise\Core\Database\Adapter\Doctrine\Database`

### Fixed
- Fix issue with fetch_mode_changing. [Bug 6892](https://bugs.oxid-esales.com/view.php?id=6892)
- Removed hardcoded "http://" in oxexturl field edit [#0006993](https://bugs.oxid-esales.com/view.php?id=6993)

## [6.3.0] - 2019-07-30

### Added
- `\OxidEsales\EshopEnterprise\Core\ShopControler::isLayout`
- `\OxidEsales\EshopEnterprise\Core\ShopControler::hasErrors`

### Changed
- category_main form layout improvements based on CE PR-585
- Use header_remove to remove Pragma header [PR-19](https://github.com/OXID-eSales/oxideshop_ee/pull/19) 
- Wrap product count in moving navigation template to badge class span  

### Deprecated
- Methods
    - `\OxidEsales\EshopEnterprise\Core\Module\ModuleInstaller`: Use service `OxidEsales\EshopCommunity\Internal\Framework\Module\Setup\Bridge\ModuleActivationBridgeInterface` instead.
    - `\OxidEsales\EshopEnterprise\Application\Model\Actions::executeDependencyEvent`
    - `\OxidEsales\EshopEnterprise\Application\Model\Attribute::executeDependencyEvent`
    - `\OxidEsales\EshopEnterprise\Application\Model\Country::executeDependencyEvent`
    - `\OxidEsales\EshopEnterprise\Application\Model\Discount::executeDependencyEvent`
    - `\OxidEsales\EshopEnterprise\Application\Model\Links::executeDependencyEvent`
    - `\OxidEsales\EshopEnterprise\Application\Model\Manufacturer::executeDependencyEvent`
    - `\OxidEsales\EshopEnterprise\Application\Model\MediaUrl::executeDependencyEvent`
    - `\OxidEsales\EshopEnterprise\Application\Model\News::executeDependencyEvent`
    - `\OxidEsales\EshopEnterprise\Application\Model\Rating::executeDependencyEvent`
    - `\OxidEsales\EshopEnterprise\Application\Model\RecommendationList::executeDependencyEvent`
    - `\OxidEsales\EshopEnterprise\Application\Model\Review::executeDependencyEvent`
    - `\OxidEsales\EshopEnterprise\Application\Model\SelectList::executeDependencyEvent`
    - `\OxidEsales\EshopEnterprise\Application\Model\Shop::executeDependencyEvent`
    - `\OxidEsales\EshopEnterprise\Core\Module\ModuleCache::executeDependencyEvent`
    - `\OxidEsales\EshopEnterprise\Application\Controller\Admin\ModuleConfiguration::executeDependencyEvent`
    - `\OxidEsales\EshopEnterprise\Application\Controller\Admin\ModuleSortList::executeDependencyEvent`
    - `\OxidEsales\EshopEnterprise\Core\Config::executeDependencyEvent`
    - `\OxidEsales\EshopEnterprise\Core\Config::_effectsAllPages`
    - `\OxidEsales\EshopEnterprise\Core\Config::_effectsAllPages`
    - `\OxidEsales\EshopEnterprise\Core\Config::_efectsAllDetails`
    - `\OxidEsales\EshopEnterprise\Core\Config::_effectsAllList`
    - `\OxidEsales\EshopEnterprise\Core\Config::_effectsStartPage`
    - `\OxidEsales\EshopEnterprise\Core\Config::_getUrlGeneratorMethod`
- Classes
    - `\OxidEsales\EshopProfessional\Core\Module\ModuleCache`: ModuleCache moved to Internal\Framework\Module package.
    - `\OxidEsales\EshopEnterprise\Core\Module\ModuleInstaller`: Use service `OxidEsales\EshopCommunity\Internal\Framework\Module\Setup\Bridge\ModuleActivationBridgeInterface` instead.

### Removed
- Old unused config  option "blAutoSearchOnCat" removed. [PR-21](https://github.com/OXID-eSales/oxideshop_ee/pull/21)
- ReverseProxy functionality extracted to module:
  - `\OxidEsales\Eshop\Core\Cache\ReverseProxy\ReverseProxyBackend`
  - `\OxidEsales\Eshop\Core\Cache\ReverseProxy\ReverseProxyHeader`
  - `\OxidEsales\Eshop\Core\Cache\ReverseProxy\ReverseProxyUrlGenerator`
  - `\OxidEsales\Eshop\Core\Cache\ReverseProxy\ReverseProxyUrlPartsToFlush`
  - `\OxidEsales\Eshop\Core\Cache\Generic\Connector\ReverseProxyCacheConnector`
- Classes that would be empty in EE after reverse proxy extraction to module
  - `\OxidEsales\EshopEnterprise\Application\Controller\Admin\ShopSeo`
  - `\OxidEsales\EshopEnterprise\Application\Controller\AccountNoticeListController`
  - `\OxidEsales\EshopEnterprise\Application\Controller\ClearCookiesController`
  - `\OxidEsales\EshopEnterprise\Core\ViewHelper\JavaScriptRenderer`
  - `\OxidEsales\EshopEnterprise\Core\ViewHelper\StyleRenderer`
- Methods that would contain only call to parent method in EE after reverse proxy extraction to module
  - `\OxidEsales\EshopEnterprise\Application\Controller\Admin\ListComponentAjax::processRequest`
  - `\OxidEsales\EshopEnterprise\Core\ControllerBaseController::onexecuteNewAction`
  - `\OxidEsales\EshopEnterprise\Application\Component\Widget\ArticleBox::updateDynamicParameters`
  - `\OxidEsales\EshopEnterprise\Core\Session::needToSetHeaders`
  - `\OxidEsales\EshopEnterprise\Core\Session::sidToUrlEvent`
  - `\OxidEsales\EshopEnterprise\Core\ShopControl::isLayout`
  - `\OxidEsales\EshopEnterprise\Core\ShopControl::doesAllowEnvKeySetting`
  - `\OxidEsales\EshopEnterprise\Core\ShopControl::setReverseProxyParameters`
  - `\OxidEsales\EshopEnterprise\Core\ShopControl::sendAdditionalHeaders`

- Methods that were moved to reverse proxy module  
  - `\OxidEsales\EshopEnterprise\Application|Controller\Admin\ShopCache::flushReverseProxyBackend`
  - `\OxidEsales\EshopEnterprise\CoreDebugInfo::formatReverseProxyActive`
  
- Smarty plugin `function.oxid_include_widget`, use parent one instead.

### Fixed
- added some migration for fixing Upgrade CE/PE to EE leads to empty article list in backend bug [#0006827](https://bugs.oxid-esales.com/view.php?id=6827)
- Ensure array in Shop::getMultiShopTables method [PR-23](https://github.com/OXID-eSales/oxideshop_ee/pull/23)
- Do not lowercase cache classname if its namespaced [PR-26](https://github.com/OXID-eSales/oxideshop_ee/pull/26)
- Fixed admin_demo group OXRRID in tests/test demodata to be unique (15 -> 16)
- Load article data from db if cache-data is falsy [PR-27](https://github.com/OXID-eSales/oxideshop_ee/pull/27)

## [6.2.4] - 2020-03-16

### Fixed
- Issue with session ID regeneration on user registration
- Fix not working actions and promotions [#0005526](https://bugs.oxid-esales.com/view.php?id=5526)
- Fix links in license conditions during setup are wrong [#0005524](https://bugs.oxid-esales.com/view.php?id=5524)

## [6.2.3] - 2019-07-16

### Fixed
- Added some migration for fixing Upgrade CE/PE to EE leads to empty article list in backend bug [#0006827](https://bugs.oxid-esales.com/view.php?id=6827)

## [6.2.2] - 2019-04-11

### Fixed
- Add missing `admin_category_main_assign_articles` block in category_main_form template [PR-25](https://github.com/OXID-eSales/oxideshop_ee/pull/25)

## [6.2.1] - 2019-01-22

### Fixed
- Improved tests and templates
- Fix issue with shop roles readonly. [Bug 6851](https://bugs.oxid-esales.com/view.php?id=6851)

## [6.2.0] - 2018-07-18

### Added
- Added isDeprecated flag to UnifiedNameSpaceClassMap.php

## [6.1.3] - 2019-02-19

### Fixed
- Fix issue with shop roles readonly. [Bug 6851](https://bugs.oxid-esales.com/view.php?id=6851)

## [6.1.2] - 2018-07-18

### Fixed
- Enhanced interoperability of tests 

## [6.1.1] - 2018-03-27

### Changed
- Checking functionality - if class loaded via `widget.php` is a widget, moved to CE code.

## [6.1.0] - 2018-01-23

### Added
- The wysiwyg editor gets disabled for textarea fields which are not allowed to be edited. Currently limited to models supporting it.

### Changed
- Loading for non widget classes via `widget.php` entry point have been declined. To allow a class to be loaded
via `widget.php` it must extend `\OxidEsales\Eshop\Application\Component\Widget\WidgetController`.
- Application/views/admin/tpl/shop_license.tpl
- admin template `article_main.tpl`

### Removed
- `\OxidEsales\EshopEnterprise\Application\Controller\Admin\NavigationTree::_getMenuFiles()`

### Fixed
- [User role 'admin' don't  have rights to save banner-promotion](https://bugs.oxid-esales.com/view.php?id=5735)

## [6.0.0] - 2017-11-17

## [6.0.0-rc.3] - 2017-11-02

## [6.0.0-rc.2] - 2017-08-15

### Added

- Set amount of items in basket to the ``ox_basket_items_count`` cookie.
  This cookie might be used by JavaScript in frontend.

### Fixed

- Shop might show empty basket even if product was added to basket with Varnish because on not unset environment key [#0006343](https://bugs.oxid-esales.com/view.php?id=6343)

## [6.0.0-rc.1] - 2017-04-07

### Removed 
- Removed methods
  - OxidEsales\EshopEnterprise\Core\SystemRequirements::checkZendPlatformOrServer()
  - OxidEsales\EshopEnterprise\Core\SystemRequirements::_getMinimumMemoryLimit()
  - OxidEsales\EshopEnterprise\Core\SystemRequirements::_getRecommendMemoryLimit()
  - OxidEsales\EshopEnterprise\Core\SystemRequirements::getRequiredModules()
  - OxidEsales\EshopEnterprise\Core\SystemRequirements::checkMysqlVersion()


## [6.0-beta.3] - 2017-03-14

## [6.0-beta.2] - 2017-12-13

## [6.0-beta.1] - 2016-11-30

[6.8.1]: https://github.com/OXID-eSales/oxideshop_ee/compare/v6.8.0...v6.8.1
[6.8.0]: https://github.com/OXID-eSales/oxideshop_ee/compare/v6.7.1...v6.8.0
[6.7.1]: https://github.com/OXID-eSales/oxideshop_ee/compare/v6.7.0...v6.7.1
[6.7.0]: https://github.com/OXID-eSales/oxideshop_ee/compare/v6.6.2...v6.7.0
[6.6.2]: https://github.com/OXID-eSales/oxideshop_ee/compare/v6.6.1...v6.6.2
[6.6.1]: https://github.com/OXID-eSales/oxideshop_ee/compare/v6.6.0...v6.6.1
[6.6.0]: https://github.com/OXID-eSales/oxideshop_ee/compare/v6.5.5...v6.6.0
[6.5.5]: https://github.com/OXID-eSales/oxideshop_ee/compare/v6.5.4...v6.5.5
[6.5.4]: https://github.com/OXID-eSales/oxideshop_ee/compare/v6.5.3...v6.5.4
[6.5.3]: https://github.com/OXID-eSales/oxideshop_ee/compare/v6.5.2...v6.5.3
[6.5.2]: https://github.com/OXID-eSales/oxideshop_ee/compare/v6.5.1...v6.5.2
[6.5.1]: https://github.com/OXID-eSales/oxideshop_ee/compare/v6.5.0...v6.5.1
[6.5.0]: https://github.com/OXID-eSales/oxideshop_ee/compare/v6.4.0...v6.5.0
[6.4.0]: https://github.com/OXID-eSales/oxideshop_ee/compare/v6.3.0...v6.4.0
[6.3.0]: https://github.com/OXID-eSales/oxideshop_ee/compare/v6.2.2...v6.3.0
[6.2.4]: https://github.com/OXID-eSales/oxideshop_ee/compare/v6.2.3...v6.2.4
[6.2.3]: https://github.com/OXID-eSales/oxideshop_ee/compare/v6.2.2...v6.2.3
[6.2.2]: https://github.com/OXID-eSales/oxideshop_ee/compare/v6.2.1...v6.2.2
[6.2.1]: https://github.com/OXID-eSales/oxideshop_ee/compare/v6.2.0...v6.2.1
[6.2.0]: https://github.com/OXID-eSales/oxideshop_ee/compare/v6.1.2...v6.2.0
[6.1.3]: https://github.com/OXID-eSales/oxideshop_ee/compare/v6.1.2...v6.1.3
[6.1.2]: https://github.com/OXID-eSales/oxideshop_ee/compare/v6.1.1...v6.1.2
[6.1.1]: https://github.com/OXID-eSales/oxideshop_ee/compare/v6.1.0...v6.1.1
[6.1.0]: https://github.com/OXID-eSales/oxideshop_ee/compare/v6.0.0...v6.1.0
[6.0.0]: https://github.com/OXID-eSales/oxideshop_ee/compare/v6.0.0-rc.3...v6.0.0
[6.0.0-rc.3]: https://github.com/OXID-eSales/oxideshop_ee/compare/v6.0.0-rc.2...v6.0.0-rc.3
[6.0.0-rc.2]: https://github.com/OXID-eSales/oxideshop_ee/compare/v6.0.0-rc.1...v6.0.0-rc.2
[6.0.0-rc.1]: https://github.com/OXID-eSales/oxideshop_ee/compare/v6.0-beta.3...v6.0.0-rc.1
[6.0-beta.3]: https://github.com/OXID-eSales/oxideshop_ee/compare/v6.0-beta.2...v6.0-beta.3
[6.0-beta.2]: https://github.com/OXID-eSales/oxideshop_ee/compare/v6.0-beta.1...v6.0-beta.2
[6.0-beta.1]: https://github.com/OXID-eSales/oxideshop_ee/compare/v6.0-beta.1...v6.0-beta.2
