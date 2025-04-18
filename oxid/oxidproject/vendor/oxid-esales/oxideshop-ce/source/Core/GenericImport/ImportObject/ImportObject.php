<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopCommunity\Core\GenericImport\ImportObject;

use Exception;
use OxidEsales\Eshop\Core\GenericImport\GenericImport;

/**
 * Main import object - includes basic implementations of methods.
 */
abstract class ImportObject
{
    /** @var string Database table name. */
    protected $tableName = null;

    /** @var array List of database fields, to which data should be imported. */
    protected $fieldList = null;

    /** @var array List of database key fields (i.e. oxid). */
    protected $keyFieldList = null;

    /** @var string Shop object name. */
    protected $shopObjectName = null;

    /**
     * Getter for _sTableName
     *
     * @return string
     */
    public function getBaseTableName()
    {
        return $this->tableName;
    }

    /**
     * setter for field list
     *
     * @param array $aFieldList fields to set
     */
    public function setFieldList($aFieldList)
    {
        $this->fieldList = $aFieldList;
    }

    /**
     * Basic access check for writing data, checks for same shopId, should be overridden if field oxshopid does not
     * exist.
     *
     * @param \OxidEsales\Eshop\Core\Model\BaseModel $shopObject Loaded shop object.
     * @param array                                  $data       Fields to be written, null for default.
     *
     * @throws Exception on now access
     */
    public function checkWriteAccess($shopObject, $data = null)
    {
        if ($shopObject->isDerived()) {
            throw new Exception(GenericImport::ERROR_USER_NO_RIGHTS);
        }
    }

    /**
     * Basic access check for creating new objects
     *
     * @param array $data fields to be written
     *
     * @throws Exception on now access
     */
    public function checkCreateAccess($data)
    {
    }

    /**
     * Insert or Update a Row into database.
     *
     * @param array $data Assoc. array with field names, values what should be stored in this table.
     *
     * @return string|false
     */
    public function import($data)
    {
        return $this->saveObject($data, false);
    }

    /**
     * Used for the RR implementation, right now not really used.
     *
     * @return array
     */
    public function getRightFields()
    {
        $accessRightFields = [];
        if (!$this->fieldList) {
            $this->getFieldList();
        }

        foreach ($this->fieldList as $field) {
            $accessRightFields[] = strtolower($this->tableName . '__' . $field);
        }

        return $accessRightFields;
    }

    /**
     * Returns the predefined field list.
     *
     * @return array
     */
    public function getFieldList()
    {
        $objectName = $this->getShopObjectName();

        if ($objectName) {
            $shopObject = oxNew($objectName);
        } else {
            $shopObject = oxNew(\OxidEsales\Eshop\Core\Model\BaseModel::class);
        }

        $shopObject->init($this->getTableName());

        if ($shopObject instanceof \OxidEsales\Eshop\Core\Model\MultiLanguageModel) {
            $shopObject->setLanguage(0);
            $shopObject->setEnableMultilang(false);
        }

        $viewName = $shopObject->getViewName();
        $fields = str_ireplace('`' . $viewName . "`.", "", strtoupper($shopObject->getSelectFields()));
        $fields = str_ireplace([" ", "`"], ["", ""], $fields);
        $this->fieldList = explode(",", $fields);

        return $this->fieldList;
    }

    /**
     * Returns the keylist array.
     *
     * @return array
     */
    public function getKeyFields()
    {
        return $this->keyFieldList;
    }

    /**
     * Getter for _sShopObjectName.
     *
     * @return string
     */
    protected function getShopObjectName()
    {
        return $this->shopObjectName;
    }

    /**
     * Returns table or View name.
     *
     * @return string
     */
    protected function getTableName()
    {
        $shopId = \OxidEsales\Eshop\Core\Registry::getConfig()->getShopId();

        return getViewName($this->tableName, -1, $shopId);
    }

    /**
     * Issued before saving an object. can modify aData for saving.
     *
     * @param \OxidEsales\Eshop\Core\Model\BaseModel $shopObject        shop object
     * @param array                                  $data              data to prepare
     * @param bool                                   $allowCustomShopId if allow custom shop id
     *
     * @return array
     */
    protected function preAssignObject($shopObject, $data, $allowCustomShopId)
    {
        if (isset($data['OXSHOPID'])) {
            $data['OXSHOPID'] = \OxidEsales\Eshop\Core\Registry::getConfig()->getShopId();
        }

        if (!isset($data['OXID'])) {
            $data['OXID'] = $this->getOxidFromKeyFields($data);
        }

        // null values support
        foreach ($data as $key => $val) {
            if (!strlen((string) $val)) {
                // oxBase will quote it as string if db does not support null for this field
                $data[$key] = null;
            }
        }

        return $data;
    }

    /**
     * Prepares object for saving in shop.
     * Returns true if save can proceed further.
     *
     * @param \OxidEsales\Eshop\Core\Model\BaseModel $shopObject Shop object.
     * @param array                                  $data       Data for importing.
     *
     * @return boolean
     */
    protected function preSaveObject($shopObject, $data)
    {
        return true;
    }

    /**
     * Saves object data.
     *
     * @param array $data              data for saving
     * @param bool  $allowCustomShopId allow custom shop id
     *
     * @return string|false
     */
    protected function saveObject($data, $allowCustomShopId)
    {
        $shopObject = $this->createShopObject();

        foreach ($data as $key => $value) {
            // change case to UPPER
            $uppercaseKey = strtoupper($key);
            if (!isset($data[$uppercaseKey])) {
                unset($data[$key]);
                $data[$uppercaseKey] = $value;
            }
        }

        if (method_exists($shopObject, 'setForceCoreTableUsage')) {
            $shopObject->setForceCoreTableUsage(true);
        }

        $isLoaded = false;
        if ($data['OXID']) {
            $isLoaded = $shopObject->load($data['OXID']);
        }

        $data = $this->preAssignObject($shopObject, $data, $allowCustomShopId);

        if ($isLoaded) {
            $this->checkWriteAccess($shopObject, $data);
        } else {
            $this->checkCreateAccess($data);
        }

        $shopObject->assign($data);

        if ($allowCustomShopId) {
            $shopObject->setIsDerived(false);
        }

        if ($this->preSaveObject($shopObject, $data)) {
            // store
            if ($shopObject->save()) {
                return $this->postSaveObject($shopObject, $data);
            }
        }

        return false;
    }

    /**
     * Post saving hook. can finish transactions if needed or adjust related data.
     *
     * @param \OxidEsales\Eshop\Core\Model\BaseModel $shopObject Shop object.
     * @param array                                  $data       Data to save.
     *
     * @return mixed data to return
     */
    protected function postSaveObject($shopObject, $data)
    {
        // returning ID on success
        return $shopObject->getId();
    }

    /**
     * Returns oxid of this data type from key fields.
     *
     * @param array $data Data for object.
     *
     * @return string
     */
    protected function getOxidFromKeyFields($data)
    {
        if (!is_array($this->getKeyFields())) {
            return null;
        }

        $database = \OxidEsales\Eshop\Core\DatabaseProvider::getDb();

        $queryWherePart = [];
        $allKeysExists = true;
        foreach ($this->getKeyFields() as $key) {
            if (array_key_exists($key, $data)) {
                $queryWherePart[] = $key . '=' . $database->quote($data[$key]);
            } else {
                $allKeysExists = false;
            }
        }

        if ($allKeysExists) {
            $query = 'SELECT OXID FROM ' . $this->getTableName() . ' WHERE ' . implode(' AND ', $queryWherePart);

            return $database->getOne($query);
        }

        return null;
    }

    /**
     * Checks if user is allowed to edit in this shop.
     *
     * @param int $shopId shop id
     *
     * @return bool
     */
    protected function isAllowedToEdit($shopId)
    {
        $user = oxNew(\OxidEsales\Eshop\Application\Model\User::class);
        $user->loadAdminUser();

        if ($user->oxuser__oxrights->value == "malladmin" || $user->oxuser__oxrights->value == (int) $shopId) {
            return true;
        }

        return false;
    }

    /**
     * Checks if id field is valid.
     *
     * @param string $id field check id
     *
     * @throws Exception
     */
    protected function checkIdField($id)
    {
        if (!isset($id) || !$id) {
            throw new Exception("ERROR: Articlenumber/ID missing!");
        } elseif (strlen($id) > 32) {
            throw new Exception("ERROR: Articlenumber/ID longer then allowed (32 chars max.)!");
        }
    }

    /**
     * Creates shop object.
     *
     * @return \OxidEsales\Eshop\Core\Model\BaseModel
     */
    protected function createShopObject()
    {
        $objectName = $this->getShopObjectName();
        if ($objectName) {
            $shopObject = oxNew($objectName, 'core');
            if ($shopObject instanceof \OxidEsales\Eshop\Core\Model\MultiLanguageModel) {
                $shopObject->setLanguage(0);
                $shopObject->setEnableMultilang(false);
            }
        } else {
            $shopObject = oxNew('oxBase', 'core');
            $shopObject->init($this->getBaseTableName());
        }

        return $shopObject;
    }
}
