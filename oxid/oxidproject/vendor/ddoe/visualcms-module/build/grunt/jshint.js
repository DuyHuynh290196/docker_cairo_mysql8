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
        jshintrc: 'build/js/.jshintrc',
    },

    ddvisualeditor: {
        src: [
            "build/js/*.js"
        ]
    },

    tests: {
        options: {
            jshintrc: 'test/js/.jshintrc',
        },

        src: [
            "test/js/*.js"
        ]
    }

};