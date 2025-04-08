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

/**
 * Metadata version
 */
$sMetadataVersion = '2.0';

/**
 * Module information
 */
$aModule = array(
    'id'          => 'ddoevisualcms',
    'title'       => 'Visual CMS',
    'description' => array(
        'de' => '<span>Einfache Verwaltung von CMS-Inhalten per Drag & Drop.</span>
                 <br>
                 <ul>
                    <li>Viele M&ouml;glichkeiten durch Verwendung von Widgets</li>
                    <li>Kinderleicht per Drag & Drop bedienbar</li>
                    <li>Schnelleres Arbeiten = gro&szlig;e Zeitersparnis</li>
                    <li>Flow Ready / RoxIVE Ready</li>
                    <li>CMS-Seiten Livesuche</li>
                    <li>Responsive Gridsystem</li>
                 </ul>
                 <dl class="moduleDesc clear">
                    <dt>Doku</dt>
                    <dd><a href="https://docs.oxid-esales.com/modules/vcms/de/latest/" target="_blank">Benutzerhandbuch Modul Visual CMS</a></dd>
                </dl>
                 ',
        'en' => '<span>Easy management of CMS content via drag and drop.</span>
                 <br>
                 <ul>
                    <li>Many scenarios possible through usage of widgets</li>
                    <li>Drag & Drop</li>
                    <li>Fast Results</li>
                    <li>Flow Ready / RoxIVE Ready</li>
                    <li>Live search of CMS pages</li>
                    <li>Responsive grid system</li>
                 </ul>
                 <dl class="moduleDesc clear">
                    <dt>DOK</dt>
                    <dd><a href="https://docs.oxid-esales.com/modules/vcms/en/latest/" target="_blank">Visual CMS Module User Manual</a></dd>
                </dl>
                 ',
    ),
    'thumbnail'   => 'logo.png',
    'version'     => '3.7.0',
    'author'      => 'OXID eSales AG & digidesk - media solutions',
    'url'         => 'http://www.oxid-esales.com',
    'email'       => 'info@oxid-esales.com',
    'extend'      => array(

        // Core
        \OxidEsales\Eshop\Core\ViewConfig::class  => \OxidEsales\VisualCmsModule\Core\ViewConfig::class,
        \OxidEsales\Eshop\Core\Utils::class       => \OxidEsales\VisualCmsModule\Core\Utils::class,
        \OxidEsales\Eshop\Core\UtilsView::class   => \OxidEsales\VisualCmsModule\Core\UtilsView::class,
        \OxidEsales\Eshop\Core\Theme::class       => \OxidEsales\VisualCmsModule\Core\Theme::class,
        \OxidEsales\Eshop\Core\Language::class    => \OxidEsales\VisualCmsModule\Core\Language::class,

        // Controllers
        \OxidEsales\Eshop\Application\Controller\ContentController::class => \OxidEsales\VisualCmsModule\Application\Controller\ContentController::class,
        \OxidEsales\Eshop\Application\Component\BasketComponent::class    => \OxidEsales\VisualCmsModule\Application\Component\BasketComponent::class,

        // Models
        \OxidEsales\Eshop\Application\Model\Content::class => \OxidEsales\VisualCmsModule\Application\Model\Content::class,
        \OxidEsales\Eshop\Application\Model\ContentList::class => \OxidEsales\VisualCmsModule\Application\Model\ContentList::class,
        \OxidEsales\Eshop\Application\Model\SeoEncoderContent::class => \OxidEsales\VisualCmsModule\Application\Model\SeoEncoderContent::class,

    ),
    'controllers' => array(

        // Lang
        'ddoevisualcmslangjs'  => \OxidEsales\VisualCmsModule\Application\Controller\VisualCmsLangJs::class,

        // Admin Controllers
        'ddoevisualcmsadmin'   => \OxidEsales\VisualCmsModule\Application\Controller\Admin\VisualCmsAdmin::class,
        'ddoevisualcmsmedia'   => \OxidEsales\VisualCmsModule\Application\Controller\Admin\VisualCmsMedia::class,

        // Controllers
        'ddoevisualcmspreview' => \OxidEsales\VisualCmsModule\Application\Controller\PreviewController::class,
        'ddoevisualcmscron'    => \OxidEsales\VisualCmsModule\Application\Controller\CronController::class,
    ),
    'templates' => array(

        // Admin Templates
        'ddoevisualcmsadmin.tpl'        => 'ddoe/visualcms/Application/views/admin/tpl/ddoevisualcmsadmin.tpl',
        'ddoevisualcmsadmin_ui.tpl'     => 'ddoe/visualcms/Application/views/admin/tpl/ddoevisualcmsadmin_ui.tpl',
        'dialog/ddoevisualcmsmedia.tpl' => 'ddoe/visualcms/Application/views/admin/tpl/dialog/ddoevisualcmsmedia.tpl',

        // Templates
        'ddoe_azure_content.tpl'        => 'ddoe/visualcms/Application/views/azure/tpl/ddoe_azure_content.tpl',
        'ddoe_azure_content_plain.tpl'  => 'ddoe/visualcms/Application/views/azure/tpl/ddoe_azure_content_plain.tpl',
        'ddoe_roxive_content.tpl'       => 'ddoe/visualcms/Application/views/roxive/tpl/ddoe_roxive_content.tpl',
        'ddoe_roxive_content_plain.tpl' => 'ddoe/visualcms/Application/views/roxive/tpl/ddoe_roxive_content_plain.tpl',
        'ddoevisualcms_photoswipe.tpl'  => 'ddoe/visualcms/Application/views/tpl/ddoevisualcms_photoswipe.tpl',

        // Widgets
        'ddoe_widget_article.tpl'       => 'ddoe/visualcms/Application/views/tpl/ddoe_widget_article.tpl',

    ),
    'blocks' => array(
        array( 'template' => 'layout/base.tpl', 'block' => 'base_style',  'file' => '/Application/views/blocks/base_style.tpl' ),
        array( 'template' => 'layout/base.tpl', 'block' => 'base_js',     'file' => '/Application/views/blocks/base_js.tpl' ),
        array(
            'theme' => 'flow',
            'template' => 'widget/header/categorylist.tpl',
            'block' => 'dd_widget_header_categorylist_navbar_list',
            'file' => '/Application/views/blocks/dd_widget_header_categorylist_navbar_list.tpl'
        ),
        array(
            'theme' => 'wave',
            'template' => 'widget/header/categorylist.tpl',
            'block' => 'dd_widget_header_categorylist_navbar_list',
            'file' => '/Application/views/blocks/dd_widget_header_categorylist_navbar_list_wave.tpl'
        ),
    ),
    'events' => array(
        'onActivate'   => '\OxidEsales\VisualCmsModule\Core\Events::onActivate',
        'onDeactivate' => '\OxidEsales\VisualCmsModule\Core\Events::onDeactivate'
    ),
    'settings' => array(
        array( 'group' => 'frontend', 'name' => 'blCustomGridFramework',      'type' => 'bool', 'value' => false ),        
        array( 'group' => 'frontend', 'name' => 'sGridColPrefix',             'type' => 'str',  'value' => 'col-sm-' ),
        array( 'group' => 'frontend', 'name' => 'sGridOffsetPrefix',          'type' => 'str',  'value' => 'col-sm-offset-' ),
        array( 'group' => 'frontend', 'name' => 'sGridRow',                   'type' => 'str',  'value' => 'row' ),
        array( 'group' => 'frontend', 'name' => 'blGridWordNumbers',          'type' => 'bool', 'value' => false ),
        array( 'group' => 'frontend', 'name' => 'iGridSize',                  'type' => 'str',  'value' => '12' ),
        array( 'group' => 'frontend', 'name' => 'blEnableLazyLoading',        'type' => 'bool', 'value' => false ),
        array( 'group' => 'frontend', 'name' => 'blEnableFontAwesome',        'type' => 'bool', 'value' => true ),

        array( 'group' => 'backend',  'name' => 'iGridsterSize',              'type' => 'str',  'value' => '6' ),
        array( 'group' => 'backend',  'name' => 'aPredefinedCssClasses',      'type' => 'arr',  'value' => array() ),
        array( 'group' => 'backend',  'name' => 'iDefaultWidgetSize',         'type' => 'str',  'value' => '1' ),
        array( 'group' => 'backend',  'name' => 'blEnableVisualEditorBlocks', 'type' => 'bool', 'value' => false ),

        array( 'group' => 'other',    'name' => 'blDisableJQuery',            'type' => 'bool', 'value' => false ),
        array( 'group' => 'other',    'name' => 'blDisableBootstrap',         'type' => 'bool', 'value' => false ),
        array( 'group' => 'other',    'name' => 'blVisualEditorDebug',        'type' => 'bool', 'value' => false ),
        array( 'group' => 'other',    'name' => 'sVisualEditorCronKey',       'type' => 'str',  'value' => '' ),
        [
            'group' => 'other',
            'name' => 'ddoeVisualCmsAlternativeImageDirectory',
            'type' => 'str',
            'value' => '',
        ],
    )
);
