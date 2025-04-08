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

        options: {
            sourceMap: true
        },

        files: {
            "out/src/js/backend.min.js": [
                //"build/vendor/gridster/js/jquery.gridster.js",
                "build/vendor/lodash/js/lodash.js",
                "build/vendor/gridstack/gridstack.js",
                "build/vendor/gridstack/gridstack.jQueryUI.js",
                "build/vendor/selectize/js/selectize.js",
                "build/vendor/minicolors/js/jquery.minicolors.js",
                "build/vendor/clipboardjs/js/clipboard.js",
                "build/js/plugins/visualeditor.js",
                "build/js/backend.js"
            ],
            "out/src/js/ace.min.js": [
                "build/vendor/ace/js/ace.js",
                "build/vendor/ace/js/mode-less.js",
                "build/vendor/ace/js/theme-xcode.js"
            ],
            "out/src/js/scripts.min.js": [
                "build/js/frontend.js"
            ],
            "out/src/js/jquery.gray.min.js": [
                "build/vendor/gray/js/jquery.gray.js"
            ],
            "out/src/js/photoswipe.min.js": [
                "build/vendor/photoswipe/js/photoswipe.js",
                "build/vendor/photoswipe/js/photoswipe-ui-default.js"
            ]
        }
    },
    
    ddbase: {

        options: {
            sourceMap: true
        },
        
        files: {

            "out/src/js/admin.min.js": [
                "build/vendor/summernote/dist/summernote.js",
                "build/vendor/summernote/dist/lang/summernote-de-DE.js",
                "build/vendor/summernote/plugin/ddmedia.summernote.js",
                "build/vendor/summernote/plugin/smarty.summernote.js",
                "build/js/plugins/summernote-video-responsive/summernote-video-responsive.js",
                "build/js/plugins/summernote-lang-extends/summernote-de-DE.js",
                "build/js/plugins/summernote-lang-extends/summernote-en-US.js",
                "build/vendor/dropzone/js/dropzone.js",
                "build/js/base/helper.js",
                "build/js/base/medialibrary.js",
                "build/js/base/admin_ui.js"
            ]

        }
        
    },
    
    bootstrap: {
        files: {

            // Frontend
            "out/src/js/bootstrap-custom.min.js": [
                "build/vendor/bootstrap/js/bootstrap.js"
            ],

            // Backend
            "out/src/js/bootstrap.min.js": [
                "build/vendor/bootstrap/js/bootstrap.js"
            ]
        }
    },

    jquery: {
        files: {
            "out/src/js/jquery-backend.min.js": [
                "build/vendor/jquery/js/jquery-1.12.0.js"
            ],
            "out/src/js/jquery.min.js": [
                "build/vendor/jquery/js/jquery-3.7.1.js"
            ]
        }
    },

    jqueryui: {
        files: {
            "out/src/js/jquery-ui-backend.min.js": [
                "build/vendor/jquery-ui/js/jquery-ui-1.11.4.js"
            ],
            "out/src/js/jquery-ui.min.js": [
                "build/vendor/jquery-ui/js/jquery-ui-1.14.1.js"
            ]
        }
    }

};
