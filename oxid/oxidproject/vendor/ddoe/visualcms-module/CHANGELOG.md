# Change Log for VisualCMS module

All notable changes to this project will be documented in this file.
The format is based on [Keep a Changelog](http://keepachangelog.com/)
and this project adheres to [Semantic Versioning](http://semver.org/).

## [3.7.0] 2025-03-04

### Changed
- Bootstrap updated to 3.3.7
- jQuery updated to 3.7.1
- jQuery-ui updated to 1.14.1

## [3.6.2] 2023-08-29

### Changed
- License updated - now using OXID Module and Component License

### Fixed
- Single quotes in category field causing a javascript error [#0007401](https://bugs.oxid-esales.com/view.php?id=7401)

## [3.6.1] 2022-06-01

### Fixed
- Fix possible broken dom in backend preview [PR-59](https://github.com/OXID-eSales/visual_cms_module/pull/59)

## [3.6.0] 2021-10-21

### Added
- New configuration parameter `ddoeVisualCmsAlternativeImageDirectory` for accessing Media files from alternative location

### Changed
- Output of the following methods depends on value of configuration parameter `ddoeVisualCmsAlternativeImageDirectory`:
  - `Application\Model\Media::getMediaPath`
  - `Application\Model\Media::getMediaUrl`

### Fixed
- Fix edit on languages above 8 in admin [PR-57](https://github.com/OXID-eSales/visual_cms_module/pull/57)
- Update documentation links in module description
- Save content Submit button is clickable during request

## [3.5.3] 2020-11-23

### Fixed
- Fix lazyloading display error in combination with flow theme
- Remove not used setting that was added by accident

## [3.5.2] 2020-11-20

### Fixed
- Fix error preventing gallery and carousel widgets to load in frontend

## [3.5.1] 2020-10-30

### Removed
- Remove BOM from summernote-az-AZ.js file.

## [3.5.0] 2020-10-20

### Added
- Add Option to deactivate Font-Awesome injection in Frontend [PR-48](https://github.com/OXID-eSales/visual_cms_module/pull/48)
- Images in hidden widgets will not be loaded till shown (LazyLoading)
- Help button is added - it links to online documentation. [PR-43](https://github.com/OXID-eSales/visual_cms_module/pull/43)
- Enabled the Superscript button in editor. [PR-46](https://github.com/OXID-eSales/visual_cms_module/pull/46)
- Video added in Summernote are responsive. [PR-53](https://github.com/OXID-eSales/visual_cms_module/pull/53)
- Link dialog in Summernote is extended by cms-ident. [PR-54](https://github.com/OXID-eSales/visual_cms_module/pull/54)

### Changed
- Tabs widget icon changed to Dir [PR-44](https://github.com/OXID-eSales/visual_cms_module/pull/44)
- Needless "Main menu" Cms Types is removed [PR-45](https://github.com/OXID-eSales/visual_cms_module/pull/45)
- Allow drag&drop widgets in nested grids [PR-55](https://github.com/OXID-eSales/visual_cms_module/pull/55)

### Fixed
- Fixed default config values to fit the type [PR-40](https://github.com/OXID-eSales/visual_cms_module/pull/40)
- Single Quotes in title field shown as "&#039" [PR-51](https://github.com/OXID-eSales/visual_cms_module/pull/51)
- Fix loading a snippet from tree view [PR-50](https://github.com/OXID-eSales/visual_cms_module/pull/50)
- Gallery pictures are now centered [PR-52](https://github.com/OXID-eSales/visual_cms_module/pull/52)
- Fixed missing translations warnings in frontend

## [3.4.0] 2020-07-09

### Changed
- Update summernote to 0.8.18

## [3.3.3] 2019-10-16

### Changed
- Upload improvements

### Fixed
- Fixed highlighting in treeview [Bug #6649](https://bugs.oxid-esales.com/view.php?id=6649) [PR-39](https://github.com/OXID-eSales/visual_cms_module/pull/39)
- Fixed deprecated calls

## [3.3.2] 2019-05-21

### Fixed
- Fixed widget translation handling when switching vcms language [Bug #6665](https://bugs.oxid-esales.com/view.php?id=6665) [PR-38](https://github.com/OXID-eSales/visual_cms_module/pull/38)
- Fixed cms content label when content was selected by treewidget
- Fixed title reloading after switching content [PR-37](https://github.com/OXID-eSales/visual_cms_module/pull/37)

## [3.3.1] 2019-04-15

### Changed
- Introduced online documentation
- Changed link to documentation in main module tab in admin [PR-35](https://github.com/OXID-eSales/visual_cms_module/pull/35)
- Improve config variables listing in metadata.php [PR-29](https://github.com/OXID-eSales/visual_cms_module/pull/29)

### Removed
- Documentation files removed as documentation is available online [PR-34](https://github.com/OXID-eSales/visual_cms_module/pull/34)

### Fixed
- Fix for active class in tree widget
- Fix for custom widgets with multiple images [Bug #6764](https://bugs.oxid-esales.com/view.php?id=6764)
- Fixed element assignment in backend.js [Bug #6916](https://bugs.oxid-esales.com/view.php?id=6916)
- Fixed rendering of hero widget [Bug #6604](https://bugs.oxid-esales.com/view.php?id=6604)  
- Fix problems in translation [PR-36](https://github.com/OXID-eSales/visual_cms_module/pull/36)

## [3.3.0] 2019-01-17

### Added
- Added possibility to build a hierachy with CMS pages

### Changed
- Tests updated to phpunit 6 [PR-33](https://github.com/OXID-eSales/visual_cms_module/pull/33)

### Fixed
- Fixed live search [PR-31](https://github.com/OXID-eSales/visual_cms_module/pull/31)

## [3.2.2] 2018-10-11

### Changed
- Make visual editor extensible by using a dynamic field definition [PR-27](https://github.com/OXID-eSales/visual_cms_module/pull/27)

## [3.2.1] 2018-07-12

### Added
- Possibility to clone widgets 

### Removed
- deleteImage() Method was unused and was removed. See [Bug #6833](https://bugs.oxid-esales.com/view.php?id=6833)

### Fixed
- Updated documentation
- Fix for saving more than on picture
- Fix metadata default value
- Removed insecure method in admin [Bug #6833](https://bugs.oxid-esales.com/view.php?id=6833)
- It was not possible to use templates when a non default shop language is selected [Bug #6815](https://bugs.oxid-esales.com/view.php?id=6815)
- In Gallery or Carousel-Widget it was not possible to save multiple pictures [Bug #6746](https://bugs.oxid-esales.com/view.php?id=6746)
- Plain Text elements were treated as normal CMS Pages
- Own grid system did use layout UI settings to generate div classes
- Widgets with edited Layout settings could not be edited after activation of own grid

### Security

## [3.2.0] - 2018-03-26

### Added
- New option to disable widgets and use only text.

### Changed
- "CMS content" dropdown is prefilled when no characters are entered.

### Fixed
- Dangerous button overwrites all content if switching language. [Bug #6742](https://bugs.oxid-esales.com/view.php?id=6742)
- VisualCMS breaks Smarty tags with parameters. [Bug #6514](https://bugs.oxid-esales.com/view.php?id=6514)
- Text-Widget adds pictures with full path incl domain. [Bug #6685](https://bugs.oxid-esales.com/view.php?id=6685)

## [3.1.0] - 2018-01-11

### Removed
- [Removed not neccesary mime type validation](https://github.com/OXID-eSales/visual_cms_module/commit/1e2fb1bce3762a034724602e6e7f97dea4d178c7)

### Fixed
- Fixed chain of class loading: OEVE-94, [Bug #6725](https://bugs.oxid-esales.com/view.php?id=6725)
- Added validation for database columns when activating the module: OEVE-87
- Folder is now displayed in content livesearch: OEVE-55

## [3.0.0] - 2017-11-14 

### Added
- Introduced namespaces
- Added demo data installation button

### Changed
- Usage of metadata 2.0

### Fixed
- Fixed smarty tag problem after saving widgets
- Correct protocol usage for image urls

[3.7.0]: https://github.com/OXID-eSales/visual_cms_module/compare/v3.6.2...v3.7.0
[3.6.2]: https://github.com/OXID-eSales/visual_cms_module/compare/v3.6.1...v3.6.2
[3.6.1]: https://github.com/OXID-eSales/visual_cms_module/compare/v3.6.0...v3.6.1
[3.6.0]: https://github.com/OXID-eSales/visual_cms_module/compare/v3.5.3...v3.6.0
[3.5.3]: https://github.com/OXID-eSales/visual_cms_module/compare/v3.5.2...v3.5.3
[3.5.2]: https://github.com/OXID-eSales/visual_cms_module/compare/v3.5.1...v3.5.2
[3.5.1]: https://github.com/OXID-eSales/visual_cms_module/compare/v3.5.0...v3.5.1
[3.5.0]: https://github.com/OXID-eSales/visual_cms_module/compare/v3.4.0...v3.5.0
[3.4.0]: https://github.com/OXID-eSales/visual_cms_module/compare/v3.3.3...v3.4.0
[3.3.3]: https://github.com/OXID-eSales/visual_cms_module/compare/v3.3.2...v3.3.3
[3.3.2]: https://github.com/OXID-eSales/visual_cms_module/compare/v3.3.1...v3.3.2
[3.3.1]: https://github.com/OXID-eSales/visual_cms_module/compare/v3.3.0...v3.3.1
[3.3.0]: https://github.com/OXID-eSales/visual_cms_module/compare/v3.2.2...v3.3.0
[3.2.2]: https://github.com/OXID-eSales/visual_cms_module/compare/v3.2.1...v3.2.2
[3.2.1]: https://github.com/OXID-eSales/visual_cms_module/compare/v3.2.0...v3.2.1
[3.2.0]: https://github.com/OXID-eSales/visual_cms_module/compare/v3.1.0...v3.2.0
[3.1.0]: https://github.com/OXID-eSales/visual_cms_module/compare/v3.0.0...v3.1.0
[3.0.0]: https://github.com/OXID-eSales/visual_cms_module/tree/v3.0.0
