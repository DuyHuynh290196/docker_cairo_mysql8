<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Core;

// e.g. for accessing openldap
/*var $aLDAPParams = array(
                            "HOST"          => "ldaps://xxx.xxx.xxx.xxx",
                            "PORT"          => 636,
                            "BASEDN"        => "ou=people,dc=uni,dc=de",
                            "USERQUERY"     => "uid=@@USERNAME@@,ou=people,dc=uni,dc=de",
                            "FILTER"        => "(uid=@@USERNAME@@)",
                            "DATAMAP"       => array(   "givenname"         =>  "OXFNAME",
                                                        "sn"                =>  "OXLNAME",
                                                        "rufanrede"         =>  "OXSAL",
                                                        // you can specify more mappings for one field it takes the first
                                                        "rufexternemail"    =>  "OXUSERNAME",
                                                        "rufpreferredmail"  =>  "OXUSERNAME",
                                                    ),
                        );


// ------------------------------------------------------------------------------------------------------------
// windows 2003 Small Business Server default settings
// works with Active Directory Services
var $aLDAPParams = array(
                            "HOST"          => "oxserver.oxid-esales.local",
                            "PORT"          => 389,
                            "BASEDN"        => "ou=MyBusiness,DC=oxid-esales,DC=local",
                            "USERQUERY"     => "@@USERNAME@@",
                            "FILTER"        => "(&(|(objectClass=user)(objectClass=contact))(objectCategory=person)(cn=@@USERNAME@@))",

                            "DATAMAP"       => array(   "givenname"         =>  "OXFNAME",
                                                        "sn"                =>  "OXLNAME",
                                                        "l"                 =>  "OXCITY",
                                                        "postalcode"        =>  "OXZIP",
                                                        "telephonenumber"   =>  "OXFON",
                                                        "co"                =>  "OXCOUNTRY",
                                                        "streetaddress"     =>  "OXSTREET",
                                                        "mail"              =>  "OXUSERNAME",
                                                    ),
                        );
// ------------------------------------------------------------------------------------------------------------
*/


/**
 * Class LDAP simplifies the usage of LDAP.
 * Hint: oxid LDAP is specialized for usage in shops (authentification of
 * users to and loading users data from external LDAP server).
 *
 * For more information see the official LDAP documentation.
 * RFC1777, 1778, 1823, 1959, 1960, 2251, 2252, 2253, 2254, 2255, 2256
 * http://www.ietf.org/rfc
 * For information about using LDAP with PHP look at php.net.
 * http://www.php.net/manual/en/ref.ldap.php
 * Change ldap definitions in config.inc.php if you want to connect to another LDAP-Server.
 *
 * @deprecated v5.3 (2016-10-06); LDAP will be moved to own module.
 */
class LDAP
{
    /**
     * For debugging only.
     * Outputs ldap status information. Enable to see which problems
     * occurred when connecting to the server.
     *
     * @var boolean Turns debugging on/off
     */
    protected $_blVerbose = false;


    /**
     * Contains formatted error message from the server.
     *
     * @var string Error message
     */
    protected $_sErrorMsg = null;

    /**
     * Contains connection handle from the server.
     *
     * @var object Connection resource
     */
    protected $_oLDAPDS = null;

    /**
     * Contains search result from the server using the dn defined in aLDAPParams[USERQUERY].
     *
     * @var array Mapped search results
     */
    protected $_aData = null;

    /**
     * Class constructor. The constructor is defined in order to be possible to call parent::__construct() in modules.
     *
     * @param string $host host
     * @param int $port port
     *
     * @return null;
     */
    public function __construct($host, $port)
    {
        $this->connect($host, $port);
    }

    /**
     * Connects to the specified ldap-server and sets the needed option.
     *
     * @param string $host ldap host
     * @param int $port connection port
     *
     * @throws oxConnectionExceptions thrown when connection to ldap server impossible
     */
    public function connect($host, $port)
    {
        // try to make connection
        $this->_oLDAPDS = ldap_connect($host, $port);

        ldap_set_option($this->_oLDAPDS, LDAP_OPT_PROTOCOL_VERSION, 3);
        //disable plain text passwords
        ldap_set_option($this->_oLDAPDS, LDAP_OPT_REFERRALS, 0);

        if ($this->_oLDAPDS == false) {
            //in open ldap 2.X this can never happen as ldap_connect does not really connect
            $exception = oxNew(\OxidEsales\Eshop\Core\Exception\ConnectionException::class);
            $exception->setMessage('ERROR_MESSAGE_CONNECTION_NOLDAP');
            $exception->setAdress($host);
            $exception->setConnectionError(ldap_error($this->_oLDAPDS));
            throw $exception;
        }
    }

    /**
     * Tries Login into LDAP Server
     * if successful, fills $this->aData with retrieved Data
     *
     * @param string $user user login name
     * @param string $password user password
     * @param string $userQuery ldap query
     * @param string $baseDirectoryName base DN for the directory
     * @param string $filter search filter
     *
     * @throws oxConnectionException thrown when connection problems to ldap server shows up
     *
     * @return bool
     */
    public function login($user, $password, $userQuery, $baseDirectoryName, $filter)
    {
        $result = false;

        // check and modify user if needed
        $query = str_replace("@@USERNAME@@", $user, $userQuery);


        // bind now
        $bindIsSuccessful = @ldap_bind($this->_oLDAPDS, $query, $password);

        if ($this->_blVerbose && $bindIsSuccessful == false) {
            $exception = oxNew(\OxidEsales\Eshop\Core\Exception\ConnectionException::class);
            $exception->setMessage('ERROR_MESSAGE_CONNECTION_NOLDAPBIND');
            $exception->setConnectionError(ldap_error($this->_oLDAPDS));
            throw $exception;
        }


        if ($bindIsSuccessful == false) {
            $this->setErrorMsg("LDAP Login failed - " . ldap_error($this->_oLDAPDS));
        } else {
            // search entry for this user
            // change ldap_search to ldap_list if you only need the first search match
            $searchResult = @ldap_search($this->_oLDAPDS, $baseDirectoryName, str_replace("@@USERNAME@@", $user, $filter));

            if ($searchResult == false) {
                $this->setErrorMsg("LDAP Login failed - " . ldap_error($this->_oLDAPDS));
                if ($this->_blVerbose) {
                    \OxidEsales\Eshop\Core\Registry::getUtils()->logger("ldap_search failed ({$query}) - " . ldap_error($this->_oLDAPDS));
                }
            } else {
                // load data
                $resultsArray = ldap_get_entries($this->_oLDAPDS, $searchResult);

                if ($this->_blVerbose) {
                    \OxidEsales\Eshop\Core\Registry::getUtils()->logger(var_export($resultsArray, true));
                }

                $result = $this->setResult($resultsArray);
            }
        }

        return $result;
    }

    /**
     * map LDAP data to our internal structure
     *
     * @param array $dataMap data map array
     *
     * @return array
     */
    public function mapData($dataMap)
    {
        $result = array();
        if ($this->_aData) {
            // let's map the data
            foreach ($dataMap as $ldapField => $oxidField) {
                if (isset($this->_aData[$ldapField][0]) && $this->_aData[$ldapField][0] && !isset($result[$oxidField])) {
                    $result[$oxidField] = $this->_aData[$ldapField][0];
                }
            }
        }

        return $result;
    }

    /**
     * Result setter. Returns set state - true/false
     *
     * @param array $result result data
     *
     * @return bool
     */
    public function setResult($result)
    {
        $isSuccessful = false;
        if (isset($result['count']) && $result['count']) {
            // success
            $isSuccessful = true;
            $this->_aData = $result[0];
        }

        return $isSuccessful;
    }

    /**
     * Sets verbose mode.
     *
     * @param bool $verboseLevel sets verbose level
     */
    public function setVerbose($verboseLevel)
    {
        $this->_blVerbose = $verboseLevel;
    }

    /**
     * Returns error message
     *
     * @return string error message
     */
    public function getErrorMsg()
    {
        return $this->_sErrorMsg;
    }

    /**
     * Error message setter
     *
     * @param string $message error message
     */
    public function setErrorMsg($message)
    {
        $this->_sErrorMsg = $message;
    }
}
