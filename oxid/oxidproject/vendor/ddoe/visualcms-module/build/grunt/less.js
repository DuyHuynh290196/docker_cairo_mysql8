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
        compress: true,
        plugins: [
            new ( require( 'less-plugin-autoprefix') )( { browsers: [ "last 2 versions" ] } )
        ]
    },

    ddvisualeditor: {
        files: {
            "out/src/css/style.min.css": "build/less/frontend.less",

            "out/src/css/backend.min.css": [
                "build/vendor/selectize/less/selectize.bootstrap3.less",
                "build/less/backend.less"
            ]
        }
    },

    ddbase: {
        files: {
            "out/src/css/medialibrary.min.css": [
                "build/less/base/medialibrary.less",
            ],
            "out/src/css/admin_ui.min.css": [
                "build/less/base/admin_ui.less"
            ]
        }
    },

    bootstrap: {
        files: {
            
            // Frontend
            "out/src/css/bootstrap-custom.min.css": "build/vendor/bootstrap/less/bootstrap-frontend.less",

            // Backend
            "out/src/css/bootstrap.min.css": "build/vendor/bootstrap/less/bootstrap.less"
        }
    },

    fontawesome: {
        files: {
            "out/src/css/font-awesome.min.css": "build/vendor/font-awesome/less/font-awesome.less"
        }
    }

};