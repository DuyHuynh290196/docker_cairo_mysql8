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

    ddvisualeditor: {
        files: {
            "out/src/css/backend.min.css": [
                //"build/vendor/gridster/css/jquery.gridster.css",
                "build/vendor/gridstack/gridstack.css",
                "build/vendor/gridstack/gridstack-extra.css",
                "build/vendor/minicolors/css/jquery.minicolors.css",
                "out/src/css/backend.min.css"
            ],
            "out/src/css/gray.min.css": [
                "build/vendor/gray/css/gray.css"
            ],
            "out/src/css/photoswipe.min.css": [
                "build/vendor/photoswipe/css/photoswipe.css",
                "build/vendor/photoswipe/css/default-skin.css"
            ]
        }
    },

    ddbase: {
        files: {
            "out/src/css/admin.min.css": [
                "build/vendor/summernote/dist/summernote.css",
                "out/src/css/medialibrary.min.css",
                "out/src/css/admin_ui.min.css"
            ]
        }
    }
};
