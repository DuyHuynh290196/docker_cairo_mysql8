# Change Log for OXID eShop Professional Edition Core Component

All notable changes to this project will be documented in this file.
The format is based on [Keep a Changelog](http://keepachangelog.com/)
and this project adheres to [Semantic Versioning](http://semver.org/).

## [6.5.3] - 2023-02-14

### Fixed
- Fix for the PHP 8 support

## [6.5.2] - 2022-05-17

### Changed
- Remove OXIDforge links

## [6.5.1] - 2021-07-27

### Changed
- Remove unnecessary deprecations

## [6.5.0] - 2021-04-12

### Added
- Support PHP 8.0

### Removed
- Support PHP 7.1 and 7.2

## [6.4.1] - 2020-07-14

### Added
- PHPCodeSniffer and PSR12 Coding guidelines

### Deprecated
- Methods starting with underscore have been deprecated, these methods will be renamed
- News feature:
    - Classes:
        - `Application/Controller/Admin/NewsMainAjax`
        - `Application/Model/News`

## [6.4.0] - 2019-11-07

### Added
- `OxidEsales\Eshop\Internal\Framework\Database\QueryBuilderFactoryInterface`

### Changed
- Most of INSERT queries do use prepared statements
- Most of UPDATE queries do use prepared statements

### Deprecated
- `OxidEsales\EshopProfessional\Core\DatabaseProvider`
- `OxidEsales\EshopProfessional\Core\Database\Adapter\Doctrine\Database`

### Fixed
- Fix issue with fetch_mode_changing. [Bug 6892](https://bugs.oxid-esales.com/view.php?id=6892)

## [6.3.0] - 2019-07-30

### Deprecated
- `\OxidEsales\EshopProfessional\Core\Module\ModuleInstaller`: Use service `OxidEsales\EshopCommunity\Internal\Framework\Module\Setup\Bridge\ModuleActivationBridgeInterface` instead.
- `\OxidEsales\EshopProfessional\Core\Module\ModuleCache`: ModuleCache moved to Internal\Framework\Module package.

### Removed
- Empty classes that have also been removed from EE
  - `\OxidEsales\EshopProfessional\Application\Controller\Admin\ShopSeo`
  - `\OxidEsales\EshopEnterprise\Application\Controller\AccountNoticeListController`
  - `\OxidEsales\EshopEnterprise\Application\Controller\ClearCookiesController`
  - `\OxidEsales\EshopEnterprise\Core\ViewHelper\JavaScriptRenderer`
  - `\OxidEsales\EshopEnterprise\Core\ViewHelper\StyleRenderer`

## [6.2.3] - 2020-03-16

### Fixed
- Fix not working actions and promotions [#0005526](https://bugs.oxid-esales.com/view.php?id=5526)
- Fix links in license conditions during setup are wrong [#0005524](https://bugs.oxid-esales.com/view.php?id=5524)

## [6.2.2] - 2019-01-22

### Changed
- Adapted test to use the new onShopEnd method

## [6.2.1] - 2018-10-16

### Fixed 
- Improve testing

## [6.2.0] - 2018-07-18

### Added
- Added isDeprecated flag to UnifiedNameSpaceClassMap.php

## [6.1.2] - 2019-02-21

### Fixed 
- Improved tests

## [6.1.1] - 2018-07-18

### Fixed
- PHP 7.1 compatibility

### Changed
- Application/views/admin/tpl/shop_license.tpl

## [6.1.0] - 2018-03-27

### Deprecated
- OxidEsales\EshopProfessional\Application\Controller\Admin\AdminView

## [6.0.0] - 2017-11-17

## [6.0.0-rc.3] - 2017-11-02

## [6.0.0-rc.2] - 2017-08-15

## [6.0.0-rc.1] - 2017-04-07

### Removed
- Removed classes and methods:
  - OxidEsales\EshopProfessional\Core\SystemRequirements::checkZendOptimizer()
  - OxidEsales\EshopProfessional\Core\SystemRequirements::getRequiredModules()

## [6.0-beta.3] - 2017-03-14

## [6.0-beta.2] - 2017-12-13

## [6.0-beta.1] - 2016-11-30

[6.5.3]: https://github.com/OXID-eSales/oxideshop_pe/compare/v6.5.2...v6.5.3
[6.5.2]: https://github.com/OXID-eSales/oxideshop_pe/compare/v6.5.1...v6.5.2
[6.5.1]: https://github.com/OXID-eSales/oxideshop_pe/compare/v6.5.0...v6.5.1
[6.5.0]: https://github.com/OXID-eSales/oxideshop_pe/compare/v6.4.1...v6.5.0
[6.4.1]: https://github.com/OXID-eSales/oxideshop_pe/compare/v6.4.0...v6.4.1
[6.4.0]: https://github.com/OXID-eSales/oxideshop_pe/compare/v6.3.0...v6.4.0
[6.3.0]: https://github.com/OXID-eSales/oxideshop_pe/compare/v6.2.3...v6.3.0
[6.2.3]: https://github.com/OXID-eSales/oxideshop_pe/compare/v6.2.2...v6.2.3
[6.2.2]: https://github.com/OXID-eSales/oxideshop_pe/compare/v6.2.1...v6.2.2
[6.2.1]: https://github.com/OXID-eSales/oxideshop_pe/compare/v6.2.0...v6.2.1
[6.2.0]: https://github.com/OXID-eSales/oxideshop_pe/compare/v6.1.1...v6.2.0
[6.1.2]: https://github.com/OXID-eSales/oxideshop_pe/compare/v6.1.1...v6.1.2
[6.1.1]: https://github.com/OXID-eSales/oxideshop_pe/compare/v6.1.0...v6.1.1
[6.1.0]: https://github.com/OXID-eSales/oxideshop_pe/compare/v6.0.0...v6.1.0
[6.0.0]: https://github.com/OXID-eSales/oxideshop_pe/compare/v6.0.0-rc.3...v6.0.0
[6.0.0-rc.3]: https://github.com/OXID-eSales/oxideshop_pe/compare/v6.0.0-rc.2...v6.0.0-rc.3
[6.0.0-rc.2]: https://github.com/OXID-eSales/oxideshop_pe/compare/v6.0.0-rc.1...v6.0.0-rc.2
[6.0.0-rc.1]: https://github.com/OXID-eSales/oxideshop_pe/compare/v6.0-beta.3...v6.0.0-rc.1
[6.0-beta.3]: https://github.com/OXID-eSales/oxideshop_pe/compare/v6.0-beta.2...v6.0-beta.3
[6.0-beta.2]: https://github.com/OXID-eSales/oxideshop_pe/compare/v6.0-beta.1...v6.0-beta.2
[6.0-beta.1]: https://github.com/OXID-eSales/oxideshop_pe/compare/v6.0-beta.1...v6.0-beta.2
