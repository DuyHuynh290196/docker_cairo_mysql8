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

namespace OxidEsales\VisualCmsModule\Application\Model;

use OxidEsales\Eshop\Core\Config;
use OxidEsales\Eshop\Core\DatabaseProvider;
use OxidEsales\Eshop\Core\Exception\DatabaseConnectionException;
use OxidEsales\Eshop\Core\Exception\DatabaseErrorException;
use OxidEsales\Eshop\Core\Registry;
use OxidEsales\EshopCommunity\Application\Model\ContentList;
use Symfony\Component\DependencyInjection\Compiler\CheckExceptionOnInvalidReferenceBehaviorPass;

/**
 * Class Content
 *
 * @mixin \OxidEsales\Eshop\Application\Model\Content
 */
class Content extends Content_parent
{
    /** @var array $_aRootNodeLists */
    private $_aRootNodeLists = array();

    /** @var array $_aTreeviewNodes */
    private $_aTreeviewNodes = array();

    /** @var ContentList $_oSubConts */
    private $_oSubConts = null;

    /**
     * Returns whether the content is active or not
     *
     * @return string
     */
    public function isActive()
    {
        $iCurrentTS = strtotime( date( 'Y-m-d' ) );

        $sActiveFrom  = substr( $this->oxcontents__ddactivefrom->value, 0, 10 );
        $sActiveUntil = substr( $this->oxcontents__ddactiveuntil->value, 0, 10 );

        if( $sActiveFrom && $sActiveFrom != '0000-00-00' )
        {
            $iActiveFromTS = strtotime( $sActiveFrom );

            if( $iActiveFromTS > $iCurrentTS )
            {
                return false;
            }
        }

        if( $sActiveUntil && $sActiveUntil != '0000-00-00' )
        {
            $iActiveUntilTS = strtotime( $sActiveUntil );

            if( $iActiveUntilTS < $iCurrentTS )
            {
                return false;
            }
        }

        return $this->oxcontents__oxactive->value;
    }


    /**
     * Returns a List of sub contents
     *
     * @return ContentList
     *
     * @throws DatabaseConnectionException
     * @throws DatabaseErrorException
     */
    public function getSubConts()
    {
        if( $this->_oSubConts === null )
        {
            /** @var Config $oConfig */
            $oConfig = Registry::getConfig();
            $oDb = DatabaseProvider::getDb( DatabaseProvider::FETCH_MODE_ASSOC );
            $sContentsView = getViewName( 'oxcontents', $this->getLanguage() );

            $aSubConts = $oDb->getAll(
                "SELECT *
                        FROM `" . $sContentsView . "`
                        WHERE ( `OXSHOPID` = ? OR `OXSHOPID` = '' )
                            AND `OXACTIVE` = 1 AND `DDPARENTCONTENT` = ?",
                array(
                    $oConfig->getShopId(),
                    $this->getId(),
                )
            );

            if( count( $aSubConts ) )
            {
                /** @var ContentList $oSubConts */
                $oSubConts = oxNew( ContentList::class );
                $oSubConts->assignArray( $aSubConts );

                $this->_oSubConts = $oSubConts;
            }
            else
            {
                $this->_oSubConts = false;
            }
        }

        return $this->_oSubConts;
    }


    /**
     * Returns an array containing treeview node data
     *
     * @param string $sParentOxid
     * @param bool   $bSnippets
     * @param int    $iLang
     * @param bool   $bParent
     *
     * @return array
     * @throws DatabaseConnectionException
     * @throws DatabaseErrorException
     */
    public function getTreeviewNodes( $sParentOxid, $bSnippets, $iLang, $bParent = false )
    {
        $sNodeKey = md5( $sParentOxid . ( int ) $bSnippets . $iLang );

        if( !array_key_exists( $sNodeKey, $this->_aTreeviewNodes ) )
        {
            /** @var Config $oConfig */
            $oConfig = Registry::getConfig();
            $oDb = DatabaseProvider::getDb( DatabaseProvider::FETCH_MODE_ASSOC );
            $sContentsView = getViewName( 'oxcontents', $iLang );
            $aNodes = array();

            $sSql = "SELECT `OXID`, `OXTITLE`, `OXACTIVE`, `DDPARENTCONTENT`, `DDSORTING` FROM `" . $sContentsView . "`";

            $sSql .= ( !$bSnippets ) ? " WHERE `OXTYPE` != 0" : " WHERE `OXTYPE` = 0";

            $sSql .= " AND ( `OXSHOPID` = ? OR `OXSHOPID` = '' )";

            if( $bParent && $sParentOxid != '#' )
            {
                $sSql .= " AND ( `OXID` = " . $oDb->quote( $sParentOxid ) . " )";
            }
            else
            {
                $sSql .= " AND ( `DDPARENTCONTENT` = " . $oDb->quote( ( $sParentOxid != '#' ) ? $sParentOxid : '' ) . " )";
            }

            $sSql .= " AND DDISTMPL = 0 ORDER BY DDSORTING ASC";

            $rsResult = $oDb->getAll(
                $sSql,
                array(
                    $oConfig->getShopId(),
                )
            );

            foreach( $rsResult as $aRow )
            {
                $sIcon = 'fa fa-circle';
                $sIcon .= ( $aRow[ 'OXACTIVE' ] == '1' ) ? ' active' : '';

                $aNewNode = array(
                    'id'      => $aRow[ 'OXID' ],
                    'text'    => $aRow[ 'OXTITLE' ],
                    'icon'    => $sIcon,
                    'parent'  => ( !empty( $aRow[ 'DDPARENTCONTENT' ] ) ) ? $aRow[ 'DDPARENTCONTENT' ] : '#',
                    'sorting' => $aRow[ 'DDSORTING' ],
                    'state'   => array( 'opened' => false ),
                );

                $rsChildCheck = $oDb->getAll(
                    "SELECT `OXID` 
                            FROM `" . $sContentsView . "`
                            WHERE ( `OXSHOPID` = ? OR `OXSHOPID` = '' )
                                AND `DDPARENTCONTENT` = ?",
                    array(
                        $oConfig->getShopId(),
                        $aRow[ 'OXID' ],
                    )
                );
                if( count( $rsChildCheck ) )
                {
                    $aNewNode[ 'children' ] = true;
                }

                $aNodes[] = $aNewNode;
            }

            $this->_aTreeviewNodes[ $sNodeKey ] = $aNodes;
        }


        return $this->_aTreeviewNodes[ $sNodeKey ];
    }


    public function getTreeviewRootNodeList( $sNodeOxid, $iLang )
    {
        if( !array_key_exists( $sNodeOxid, $this->_aRootNodeLists ) )
        {
            /** @var Config $oConfig */
            $oConfig = Registry::getConfig();
            $oDb = DatabaseProvider::getDb( DatabaseProvider::FETCH_MODE_ASSOC );
            $sContentsView = getViewName( 'oxcontents', $iLang );
            $bRootFound = false;
            $sRootOxid = $sNodeOxid;
            $aNodeList = array();

            while( !$bRootFound )
            {
                $aRes = $oDb->getRow(
                    "SELECT `OXID`, `DDPARENTCONTENT`
                        FROM `" . $sContentsView . "`
                        WHERE ( `OXSHOPID` = ? OR `OXSHOPID` = '' )
                            AND `OXID` = ?",
                    array(
                        $oConfig->getShopId(),
                        $sRootOxid,
                    )
                );

                if( !empty( $aRes[ 'DDPARENTCONTENT' ] ) )
                {
                    $sRootOxid = $aRes[ 'DDPARENTCONTENT' ];
                    $aNodeList[] = $sRootOxid;
                }
                else
                {
                    $bRootFound = true;
                }
            }

            $this->_aRootNodeLists[ $sNodeOxid ] = array( 'sRootOxid' => $sRootOxid, 'aNodeList' => $aNodeList );
        }

        return $this->_aRootNodeLists[ $sNodeOxid ];
    }


    /**
     * Returns OXID of the root node CMS-content of a given
     * CMS-content page in a given language
     *
     * @param string $sNodeOxid
     * @param int    $iLang
     *
     * @return string
     */
    public function getTreeviewRootNode( $sNodeOxid, $iLang )
    {
        $aNodeList = $this->getTreeviewRootNodeList( $sNodeOxid, $iLang );
        return $aNodeList[ 'sRootOxid' ];
    }
}
