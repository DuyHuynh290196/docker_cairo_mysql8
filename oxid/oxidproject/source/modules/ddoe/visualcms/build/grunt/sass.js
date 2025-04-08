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

module.exports = {

    options: {
        sourcemap: 'none'
    },

    gridstack: {

        files: {
            "build/vendor/gridstack/css/gridstack.css": "build/vendor/gridstack/src/scss/gridstack.scss",
            "build/vendor/gridstack/css/gridstack-extra.css": "build/vendor/gridstack/src/scss/gridstack-extra.scss"
        }

    }

};