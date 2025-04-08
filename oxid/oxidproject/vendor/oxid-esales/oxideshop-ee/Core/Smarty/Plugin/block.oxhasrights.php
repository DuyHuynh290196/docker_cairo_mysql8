<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

/**
 * Smarty {oxhasrights}{/oxhasrights} block plugin
 *
 * Type:     block function<br>
 * Name:     oxhasrights<br>
 * Purpose:  checks if user has rights to view block of data
 *
 * @param array  $params  Parameters
 * @param string $content Contents of the block
 * @param Smarty $smarty  Clever simulation of a method
 * @param bool   $repeat  Repeat
 *
 * @return string $content re-formatted
 */
function smarty_block_oxhasrights($params, $content, &$smarty, &$repeat)
{
    $config = \OxidEsales\Eshop\Core\Registry::getConfig();
    $rights = $config->getActiveView()->getRights();

    if (\OxidEsales\Eshop\Core\Registry::getConfig()->isAdmin()) {
        // admin R&R check
        $class = $params['type'];
        $field = isset($params['field']) ? $params['field'] : null;
        $right = isset($params['right']) ? ((int)$params['right']) : null;
        $isReadOnly = false;

        $isDerivedObject = isset($params['object']) && ($object = $params['object'])
                           && ($object instanceof \OxidEsales\EshopCommunity\Core\Model\BaseModel)
                           && $object->isDerived();

        if (isset($params['readonly']) && $params['readonly'] && !$isDerivedObject) {
            $isReadOnly = true;
        }

        if (!$isReadOnly && isset($params['object']) && ($object = $params['object']) && $object instanceof \OxidEsales\EshopCommunity\Core\Model\BaseModel) {
            // testing if passed object has rights:
            if (($right === RIGHT_DELETE && !$object->canDelete()) || ($right === RIGHT_INSERT && !$object->canInsert())) {
                // object has no right for action
                $repeat = false;
                return "";
            } elseif ($right !== null && !$object->canDo($field, $right)) {
                // object has no right for action
                $repeat = false;
                return "";
            } elseif (($field && !$object->canReadField($field)) || !$object->canRead()) {
                // if object or its field is not readable
                $repeat = false;
                return "";
            } elseif (($field && !$object->canUpdateField($field)) || !$object->canUpdate()) {
                // if object or its field is not editable
                $isReadOnly = true;
            }
        } elseif (!$isReadOnly && $rights && $class) {
            // testing is passed object info has special R&R config
            $field = $field ? $field : $class;
            if ($right !== null) {
                // saving object template due to performance.
                $objectCache = $config->getGlobalParameter('aObjectCache');
                if (!isset($objectCache[$class])) {
                    $object = oxNew(\OxidEsales\Eshop\Core\Model\BaseModel::class);
                    $object->init($class);
                    $objectCache[$class] = $object;
                } else {
                    $object = $objectCache[$class];
                }
                if (!$rights->hasRights($right, $object, $field)) {
                    $repeat = false;
                    return "";
                }
            } elseif (($index = $rights->getObjectRightsIndex($class, $field)) != null) {
                if (!$index) {
                    $repeat = false;
                    return "";
                } elseif ($index == RIGHT_VIEW) {
                    $isReadOnly = true;
                }
            }
        }

        if ($isReadOnly === true) {
            $pattern = array(
                '/<textarea/i',
                '/<select/i',
                '/<input ((?:(?!btnShowHelpPanel).)*?)>/is',
                '/<a (.*?)(onclick\s*=\s*([\'"]).*?\3)?(.*?)/i'
            );
            $replacement = array(
                '<textarea readonly disabled',
                '<select readonly disabled',
                '<input readonly disabled \1>',
                '<a onclick="return false" \1 \4'
            );

            return getStr()->preg_replace($pattern, $replacement, $content);
        }
    } elseif ($rights && !isAdmin() && $params['ident']) {
        // front-end R&R check
        if (!$rights->hasViewRights($params['ident'])) {
            $repeat = false;
            return;
        }
    }

    return $content;
}
