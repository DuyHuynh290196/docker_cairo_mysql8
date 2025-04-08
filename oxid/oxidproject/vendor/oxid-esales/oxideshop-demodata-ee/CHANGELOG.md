# Change Log for OXID eShop Enterprise Edition Demo Data Component

All notable changes to this project will be documented in this file.
The format is based on [Keep a Changelog](http://keepachangelog.com/)
and this project adheres to [Semantic Versioning](http://semver.org/).

## [6.0.5] - 2022-12-05

### Changed
- Update credits page content
- 
## [6.0.4] - 2020-04-21

### Removed
- Removed date values from titles and descriptions

## [6.0.3] - 2019-04-26

### Changed
- Removed the `out/pictures/generated` directory from package
    - this directory is filled dynamically by shop.

### Fixed
- Fixed wording in changelog file.

## [6.0.2] - 2019-04-18

### Removed
- `blFooterShowGuestbook` config option [#0006696](https://bugs.oxid-esales.com/view.php?id=6696)

### Fixed
- `OXRRID` is now unique (15->16) for admin_demo group in groups table [#0006925](https://bugs.oxid-esales.com/view.php?id=6925)
- `oxrightofwithdrawal` content page content fixed
    - getBaseDir now called on oViewConf object
    - `ddmedia` directory added in pictures, in place of earlier removed `wysiwyg` directory
        - new OXID Visual CMS module uses this directory for file upload by default

## [6.0.1] - 2017-12-21

[6.0.5]: https://github.com/OXID-eSales/oxideshop_demodata_ee/compare/v6.0.4...HEAD
[6.0.4]: https://github.com/OXID-eSales/oxideshop_demodata_ee/compare/v6.0.3...v6.0.4
[6.0.3]: https://github.com/OXID-eSales/oxideshop_demodata_ee/compare/v6.0.2...v6.0.3
[6.0.2]: https://github.com/OXID-eSales/oxideshop_demodata_ee/compare/v6.0.1...v6.0.2
[6.0.1]: https://github.com/OXID-eSales/oxideshop_demodata_ee/compare/v6.0.0...v6.0.1
