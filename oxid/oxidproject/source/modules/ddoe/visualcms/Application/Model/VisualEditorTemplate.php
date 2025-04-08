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

use OxidEsales\Eshop\Core\Model\BaseModel;
use OxidEsales\Eshop\Core\DatabaseProvider;

/**
 * Class VisualEditorTemplate
 *
 * @mixin \OxidEsales\Eshop\Core\Model\BaseModel
 */
class VisualEditorTemplate extends BaseModel
{
    protected $_aTemplates = null;

    public function getTemplates()
    {
        if( $this->_aTemplates == null )
        {
            $sSelect = $this->_getSQL() . " ORDER BY `c`.`OXTIMESTAMP` DESC";
            $aData = DatabaseProvider::getDb( DatabaseProvider::FETCH_MODE_ASSOC )->getAll( $sSelect );

            $this->_aTemplates = array();

            foreach( $aData as $aRow )
            {
                $this->_aTemplates[ $aRow[ 'OXID' ] ] = $aRow;
            }
        }

        return $this->_aTemplates;

    }

    public function getTemplate( $sID )
    {
        if( $this->_aTemplates == null || !$this->_aTemplates[ $sID ]  )
        {
            $sSelect = $this->_getSQL() . " AND `c`.`OXID` = '" . $sID . "' ";
            $aData = DatabaseProvider::getDb( DatabaseProvider::FETCH_MODE_ASSOC )->getRow( $sSelect );

            if( $aData )
            {
                $this->_aTemplates[ $sID ] = $aData;
            }
        }

        return $this->_aTemplates[ $sID ];
    }


    protected function _getSQL()
    {
        $oContent = new \OxidEsales\Eshop\Application\Model\Content();
        $sContentTable = $oContent->getCoreTableName();

        return "SELECT
                  `c`.`OXID`,
                  `c`.`OXCONTENT`,
                  `c`.`OXTITLE`,
                  `c`.`OXTIMESTAMP`,
                  `c`.`DDTMPLTARGETID`,
                  `c`.`DDTMPLTARGETDATE`,
                  `t`.`OXTITLE` AS 'DDTMPLTARGETTITLE',
                  `t`.`OXLOADID` AS 'DDTMPLTARGETIDENT'
              FROM `" . $sContentTable . "` AS `c`
                  LEFT OUTER JOIN `" . $sContentTable . "` AS `t`
                      ON `t`.`OXID` = `c`.`DDTMPLTARGETID`
              WHERE `c`.`DDISTMPL` = 1 ";

    }

}
