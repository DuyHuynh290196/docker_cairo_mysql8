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

use OxidEsales\VisualCmsModule\Application\Model\VisualEditorShortcode;

use OxidEsales\Eshop\Core\Registry;
use OxidEsales\Eshop\Application\Model\Category;

class category_shortcode extends VisualEditorShortcode
{

    protected $_sTitle = 'DD_VISUAL_EDITOR_SHORTCODE_CATEGORY';

    protected $_sBackgroundColor = '#40d47e';

    protected $_sIcon = 'fa-newspaper-o';

    public function install()
    {
        $this->setShortCode( basename( __FILE__, '.php' ) );
    }

    public function setInterfaceOptions()
    {
        $oLang = Registry::getLang();

        $this->setOptions(
            array(
                'id'   => array(
                    'type'        => 'select',
                    'label'       => $oLang->translateString( 'DD_VISUAL_EDITOR_WIDGET_CATEGORY' ),
                    'placeholder' => $oLang->translateString( 'DD_VISUAL_EDITOR_WIDGET_CHOOSE_CATEGORY' ),
                    'values'      => $this->getCategories( $oLang->getEditLanguage() ),
                    'dataFields'  => array(
                        'name' => 'label'
                    )

                ),
                'name' => array(
                    'type'    => 'hidden',
                    'preview' => true
                )
            )
        );
    }

    public function parse( $sContent = '', $aParams = array() )
    {
        /** @var Category $oCat */
        $oCat = oxNew( Category::class );
        $oCat->load( $aParams[ 'id' ] );

        $oViewConf = Registry::get( 'oxViewConfig' );
        $oConfig = \OxidEsales\Eshop\Core\Registry::getConfig();

        if( $oViewConf->isAzureTheme() )
        {
            $sOutput = '<div class="dd-shortcode-' . $this->getShortCode() . ' box subcatList' . ( $aParams[ 'class' ] ? ' ' . $aParams[ 'class' ] : '' ) . '">
                        <h3>
                            <a href="' . $oCat->getLink() . '">
                                ' . $oCat->oxcategories__oxtitle->value . ( $this->showCategoryArticlesCount() && $oCat->getNrOfArticles() ? ' (' . $oCat->getNrOfArticles() . ')' : '' ) . '
                            </a>
                        </h3>';

            if( $oCat->getIconUrl() )
            {
                $sOutput .= '<div class="content catPicOnly">
                                 <div class="subcatPic">
                                     <a href="' . $oCat->getLink() . '">
                                         <img '. ( $oConfig->getConfigParam("blEnableLazyLoading") ? 'data-' : '' ) .'src="' . $oCat->getIconUrl() . '" alt="' . $oCat->oxcategories__oxtitle->value . '">
                                     </a>
                                 </div>
                             </div>';
            }

            $sOutput .= '</div>';
        }
        else
        {
            $sOutput = '<div class="dd-shortcode-' . $this->getShortCode() . ' panel panel-default' . ( $aParams[ 'class' ] ? ' ' . $aParams[ 'class' ] : '' ) . '">
                            <div class="panel-heading">
                                <a href="' . $oCat->getLink() . '">' . $oCat->oxcategories__oxtitle->value . '</a>' . ( $this->showCategoryArticlesCount() && $oCat->getNrOfArticles() ? ' (' . $oCat->getNrOfArticles() . ')' : '' ) . '
                            </div>';

            if( $oCat->getIconUrl() )
            {
                $sOutput .= '<div class="panel-body">
                                 <div class="text-center">
                                     <a href="' . $oCat->getLink() . '">
                                         <img '. ( $oConfig->getConfigParam("blEnableLazyLoading") ? 'data-' : '' ) .'src="' . $oCat->getIconUrl() . '" alt="' . $oCat->oxcategories__oxtitle->value . '">
                                     </a>
                                 </div>
                             </div>';
            }

            $sOutput .= '</div>';
        }


        return $sOutput;

    }

}