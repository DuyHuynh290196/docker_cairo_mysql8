<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Application\Controller\Admin;

/**
 * @inheritdoc
 */
class NavigationController extends \OxidEsales\EshopProfessional\Application\Controller\Admin\NavigationController
{
    /**
     * @inheritdoc
     */
    public function chshp()
    {
        parent::chshp();

        if ($rights = $this->getRights()) {
            $navigation = $this->getNavigation();
            $listView = $this->_aViewData['listview'];

            // checking list view
            $rightsIndex = $rights->getViewRightsIndex($navigation->getClassId($listView));
            if ($rightsIndex !== null && $rightsIndex < RIGHT_VIEW) {
                $this->_aViewData['listview'] = null;
                $this->_aViewData['editview'] = null;
                $this->_aViewData['actedit'] = null;
            } else {
                $navigation->markNodeActive($listView);
                // checking edit view
                $rightsIndex = $rights->getViewRightsIndex($navigation->getClassId($this->_aViewData['editview']));
                if ($rightsIndex !== null && $rightsIndex < RIGHT_VIEW) {
                    $this->_aViewData['editview'] = $navigation->getActiveTab($listView, 0);
                    $this->_aViewData['actedit'] = 0;
                }
            }
        }
    }
}
