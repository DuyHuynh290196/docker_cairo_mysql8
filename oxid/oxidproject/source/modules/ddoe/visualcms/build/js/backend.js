/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

+ function ($) {
    'use strict';

    // BACKEND UI METHODS
    // ==================

    // Main Variables
    // --------------
    var VE = {};
    var blSourceMode = false;
    var blPlainMode = false;
    var $lang = null;
    var $loader = null;
    var fields_definition = {};

    // Basic Getter Methods
    // --------------------
    var getLang = function () {
        if ($lang == null) {
            $lang = $('#elm_lang');
        }
        return $lang.val();
    };

    var getLoader = function () {
        if ($loader == null) {
            $loader = $('.dd-ajax-loader.dd-content-loader');
        }
        return $loader;
    };

    // Source Mode Methods
    // -------------------
    var setSourceTextarea = function () {
        if (blSourceMode) {
            var iHeight = $('.dd-veditor-wrapper', VE.$container).height();
            $('textarea', VE.$source).css('height', iHeight + 'px');
        }
    };

    var setWidgetMode = function (source, callback) {
        if (typeof source === 'function') {
            callback = source;
        }
        var blForce = false;
        if (typeof source !== 'string') {
            source = $('textarea', VE.$source).val();
        } else {
            blForce = true;
        }
        if (blPlainMode) {
            blPlainMode = false;
            $('.dd-veditor-css-action, .dd-veditor-template-action, .dd-veditor-source-action, .dd-veditor-delete-all-action').removeClass('disabled');
            $('#elm_edit_hide_title, #elm_edit_islanding, #elm_edit_fullwidth, #elm_edit_cssclass').prop('disabled', false);
        }
        if (blSourceMode || blForce) {
            getLoader().show();
            blSourceMode = false;
            $('.dd-veditor-widget-action, .dd-veditor-media-action, .dd-veditor-column-action').removeClass('disabled');
            $('.dd-veditor-source-action').attr('data-original-title', ddh.translate('DD_VISUAL_EDITOR_SHOW_SOURCE')).html('<i class="fa fa-code"></i>').tooltip('hide');
            VE.clear();
            VE.$grid.show();
            VE.$source.hide();
            if (source) {
                var sLang = getLang();
                var data = '&editlanguage=' + sLang + '&source=' + encodeURIComponent(source);
                $.post(VE.options.actionLink + '&fnc=getWidgets', data, function (data) {
                    $.each(data, function () {
                        VE.add(this.widget, this.widget_params, this);
                    });
                    getLoader().hide();
                    typeof callback == 'function' && callback(source, VE);
                });
            } else {
                getLoader().hide();
                typeof callback == 'function' && callback(source, VE);
            }
        } else {
            typeof callback == 'function' && callback(source, VE);
        }
    };

    var setSourceMode = function (callback) {
        if (blPlainMode) {
            blPlainMode = false;
            $('.dd-veditor-css-action, .dd-veditor-template-action, .dd-veditor-source-action, .dd-veditor-delete-all-action').removeClass('disabled');
            $('#elm_edit_hide_title, #elm_edit_islanding, #elm_edit_fullwidth, #elm_edit_cssclass').prop('disabled', false);
        }
        if (!blSourceMode) {
            getLoader().show();
            blSourceMode = true;
            var sLang = getLang();
            var data = '&editlanguage=' + sLang;
            $('.dd-veditor-widget-action, .dd-veditor-media-action, .dd-veditor-column-action').addClass('disabled');
            $('.dd-veditor-source-action').attr('data-original-title', ddh.translate('DD_VISUAL_EDITOR_SHOW_WIDGETS')).html('<i class="fa fa-puzzle-piece"></i>').tooltip('hide');
            $.each(VE.serialize(), function () {
                data += '&widget[' + this.row + '][' + this.col + ']=' + encodeURIComponent(JSON.stringify(this));
            });
            VE.$grid.hide();
            VE.$source.show().find('textarea').val('');
            if (data.match(/widget/)) {
                $.post(VE.options.actionLink + '&fnc=getSource', data, function (data) {
                    VE.$source.show().find('textarea').val(data);
                    setSourceTextarea();
                    getLoader().hide();
                    typeof callback == 'function' && callback(data, VE);
                });
            } else {
                setSourceTextarea();
                getLoader().hide();
                typeof callback == 'function' && callback('', VE);
            }
        } else {
            typeof callback == 'function' && callback($('textarea', VE.$source).val(), VE);
        }
    };

    var toggleMode = function (callback) {
        if (blSourceMode) {
            setWidgetMode(callback);
        } else {
            setSourceMode(callback);
        }
    };

    var setPlainMode = function (callback) {
        if (!blSourceMode) {
            setSourceMode();
        }
        blPlainMode = true;
        $('.dd-veditor-css-action, .dd-veditor-template-action, .dd-veditor-source-action, .dd-veditor-delete-all-action').addClass('disabled');
        $('#elm_edit_hide_title, #elm_edit_islanding, #elm_edit_fullwidth, #elm_edit_cssclass').prop('disabled', true);
        typeof callback == 'function' && callback($('textarea', VE.$source).val(), VE);
    };

    // Settings Form Methods
    // ---------------------
    var setTabContentHeight = function () {
        var $settings = $('#settings');
        var iHeight = $settings.outerHeight() - $('.tab-content', $settings).position().top - $('.dd-veditor-form-actions', $settings).outerHeight() - 1;
        $('.tab-content', $settings).height(iHeight);
    };

    // Load Content Method
    // -------------------
    var loadContent = function (data) {
        var sLang = getLang();
        $('input[name=selectedlanguage]').val(sLang);
        VE.clear();
        $('textarea', VE.$source).val('');
        var _setContent = function () {
            var selectizeLoadad = typeof $().selectize == 'function';
            if (data && typeof data === 'object') {
                getLoader().show();
                $.each(data, function (index, value) {
                    if (index.indexOf('$') !== -1) {
                        return;
                    }/*ensure fields definition includes defaults*/
                    if (fields_definition[index] == null) {
                        fields_definition[index] = {id: index};
                    } else {
                        if (fields_definition[index].id == null) {
                            fields_definition[index].id = index;
                        }
                    }
                    var fd = fields_definition[index];
                    var id = "#elm_edit_" + fd.id;
                    if (fd.type === 'date') {
                        value = value === '0000-00-00' ? '' : value;
                    }
                    var $id = $(id);
                    if ($id.length >= 1) {
                        if (fd.type === 'checkbox') {
                            $id.prop('checked', value === '1');
                        } else if (fd.type === 'select' && selectizeLoadad) {
                            $id[0].selectize.setValue(value);
                        } else {
                            $id.val(value);
                        }
                    } else {
                        var known_manuel_handled_fields = {
                            'desc': '',
                            'fullurl': '',
                            'type': '',
                            'fullwidth': '',
                            'newinlang': '',
                            'fromlang': '',
                            'seo': '',
                            'url': '',
                            'isactive': ''
                        };
                        if (!(index in known_manuel_handled_fields)) {
                            console.log("element " + id + " (" + index + ") not found please check template and field definition/data");
                        }
                    }
                });
                if ((data.active_from && data.active_from != '0000-00-00') || (data.active_until && data.active_until != '0000-00-00')) {
                    $('.dd-veditor-timespan').show();
                    $('.dd-veditor-timespan-toggle').html(ddh.translate('DD_VISUAL_EDITOR_ACTIVE_TIMESPAN_HIDE'));
                }
                $('input[name="editval[oxcontents__oxtype]"][value="' + data.type + '"]').prop('checked', true);
                if (typeof data.seo == 'object') {
                    $('input#elm_seo_fixed').prop('checked', (data.seo.fixed == '1'));
                    $('input#elm_seo_url').val(data.seo.url);
                    $('textarea#elm_seo_keywords').val(data.seo.keywords);
                    $('textarea#elm_seo_description').val(data.seo.description);
                }
                if (!data.type || data.type == '0') {
                    $('.dd-cms-url, .dd-cms-cat').hide();
                } else {
                    if (data.type == '2') {
                        $('.dd-cms-cat').show();
                    } else {
                        $('.dd-cms-cat').hide();
                    }
                    $('.dd-cms-url').show();
                    if (data.newinlang) {
                        $('.dd-cms-url .form-control-static').html('<code>-</code><br><br><small class="text-info">' + ddh.translate('DD_VISUAL_EDITOR_NO_LANG_INFO').replace('%s', data.fromlang) + '</small>');
                    } else {
                        $('.dd-cms-url .form-control-static').html('<a href="' + data.fullurl + '" target="_blank"><code>' + data.url + '</code></a>');
                    }
                }
                $('.dd-cms-snippet').show();
                $('#elm_snippet').val('[{oxcontent ident="' + data.ident + '"}]');
                if (!blPlainMode) {
                    $.get(VE.options.actionLink + '&fnc=getWidgets&editlanguage=' + sLang + '&id=' + data.id, function (widgets) {
                        $.each(widgets, function () {
                            VE.add(this.widget, this.widget_params, this);
                        });
                        getLoader().hide();
                    });
                } else {
                    $.get(VE.options.actionLink + '&fnc=getSource&editlanguage=' + sLang + '&id=' + data.id, function (data) {
                        VE.$source.show().find('textarea').val(data);
                        setSourceTextarea();
                        getLoader().hide();
                    });
                }
                if (data && data.id) {
                    $('.dd-delete-action').removeAttr('disabled');
                } else {
                    $('.dd-delete-action').attr('disabled', true);
                }
            } else {
                $.each(fields_definition, function (index, fd) {
                    var id = fd.id ? fd.id : index;
                    id = "#elm_edit_" + id;
                    var default_value = fd.defaulValue == null ? fd.defaulValue : '';
                    var $id = $(id);
                    if (fd.type === 'checkbox') {
                        $id.prop('checked', false);
                    } else if (fd.type === 'select' && selectizeLoadad) {
                        $id[0].selectize.setValue(default_value);
                    } else {
                        $id.val(default_value);
                    }
                });
                $('.dd-cms-snippet #elm_snippet').val('');
                $('.dd-cms-url .form-control-static').html('<code>-</code>');
                $('.dd-cms-url').hide();
                $('.dd-veditor-timespan').hide();
                $('.dd-veditor-timespan-toggle').html(ddh.translate('DD_VISUAL_EDITOR_ACTIVE_TIMESPAN_SELECT'));
                $('input[name="editval[oxcontents__oxtype]"][value="0"]').prop('checked', true);
                $('input#elm_seo_fixed').prop('checked', false);
                $('input#elm_seo_url').val('');
                $('textarea#elm_seo_keywords').val('');
                $('textarea#elm_seo_description').val('');
                $('.dd-veditor-grid ul').css('height', 'auto');
                $('.dd-delete-action').attr('disabled', true);
            }
            setTabContentHeight();
        };
        if (data && data.plaintext === '1') {
            setPlainMode(_setContent);
        } else {
            setWidgetMode(_setContent);
        }
    };

    // Preload Content Picker
    // ------------------------
    var preloadContentDropdown = function (activeValue) {
        if ($('#elm_edit_cms').length) {
            var $picker = $('#elm_edit_cms');
            $picker[0].selectize.clearOptions();
            $picker[0].selectize.load(function (callback) {
                var sLang = $('#elm_lang').val();
                $.ajax({
                    url: VE.options.actionLink + '&fnc=searchContents&editlanguage=' + sLang + '&all=1&definition=1',
                    type: 'GET',
                    error: function () {
                        callback();
                    },
                    success: function (res) {
                        if (res.fields_definition) {
                            fields_definition = res.fields_definition;
                            delete res.fields_definition;
                            res = Object.keys(res).map(function (key) {
                                return res[key];
                            });
                        }
                        callback(res);
                        if (activeValue) {
                            $picker[0].selectize.setValue(activeValue);
                        } else {
                            $picker[0].selectize.setValue('');
                        }
                    }
                });
            });
        }
    };

    // Load Demo Content Method
    // ------------------------
    var loadDemoContent = function () {
        ddh.confirm(ddh.translate('DD_VISUAL_EDITOR_LOAD_DEMODATA_INFO'), function () {
            $.get(VE.options.actionLink + '&fnc=searchContents&editlanguage=' + getLang() + '&block=0&ident=oxdemopage', function (data) {
                if (data && data.length) {
                    preloadContentDropdown(data[0].id);
                }
            });
        });
    };

    // Window Events
    // -------------
    $(window).on('load', function () {
        if (window.preloadid) {
            window.history.replaceState({}, document.title, location.href.replace(/\&preloadid=[a-z0-9\.\-\_]*/, ''));
        }/* Preload content picker*/
        preloadContentDropdown(window.preloadid);/* Remove main loader*/
        $('.dd-main-loader').remove();
        setTabContentHeight();
    }).on('resize', function () {
        setSourceTextarea();
        setTabContentHeight();
    });

    // Document Ready
    // --------------
    $(function () {
        if (!window.shortcodes) {
            return;
        }
        if (typeof window.debug != 'undefined' && window.debug === true) {
            VisualEditor.DEBUG = true;
        }
        // MediaLibrary resource URLs
        if (typeof MediaLibrary == 'object') {
            window.options = window.options || {};
            window.options.actionLink = MediaLibrary._actionLink ? MediaLibrary._actionLink + '&cl=ddoevisualcmsadmin' : location.href;
            window.options.resourceLink = MediaLibrary._resourceLink ? MediaLibrary._resourceLink : null;
        }

        // Initialize Visual Editor
        VE = new VisualEditor(window.shortcodes, window.options);

        // Add source mode element
        VE.$source = $('.dd-veditor-source', VE.$container);

        // Make Visual Editor Instance global
        window.VisualEditorInstance = VE;

        // Initialize tooltips
        $('*[data-toggle=tooltip]').tooltip();

        // Initialize CMS Picker
        if (typeof $().selectize == 'function') {
            $('.dd-cms-picker').each(function () {
                $(this).selectize({
                    valueField: 'id',
                    labelField: 'title',
                    searchField: ['title', 'desc'],
                    create: false,
                    render: {
                        option: function (item, escape) {
                            return '<div class="media">' + '<div class="media-left"><i class="fa fa-circle' + (item.isactive ? ' active' : '') + '"></i></div>' + '<div class="media-body">' + '<strong class="media-heading name">' + escape(item.title) + '</strong><br/>' + '<small>' + item.desc + '</small>' + '</div>' + '</div>';
                        }
                    },
                    load: function (query, callback) {
                        if (!query.length) {
                            return callback();
                        }
                        var sLang = getLang();
                        var blBlock = this.$input.is('#elm_tmpl_timer_cms') ? 1 : 0;
                        $.ajax({
                            url: VE.options.actionLink + '&fnc=searchContents&editlanguage=' + sLang + '&block=' + blBlock + '&search=' + encodeURIComponent(query),
                            type: 'GET',
                            error: function () {
                                callback();
                            },
                            success: function (res) {
                                callback(res);
                            }
                        });
                    },
                    onChange: function (value) {
                        if (this.$input.is('#elm_edit_cms')) {
                            loadContent.call(this, (value && this.options[value] ? this.options[value] : null));
                        }
                    }
                });
            });
            var $objectPicker = $('.dd-article-picker, .dd-category-picker, .dd-manufacturer-picker');
            if ($objectPicker.length) {
                $objectPicker.selectize({
                    valueField: 'id',
                    labelField: 'title',
                    searchField: ['title', 'description'],
                    create: false,
                    render: {
                        option: function (item, escape) {
                            return '<div class="media">' + '<div class="media-body">' + '<strong class="media-heading name">' + escape(item.title) + '</strong>' + (item.description ? '<br/><small>' + escape(item.description) + '</small>' : '') + '</div>' + '</div>';
                        }
                    },
                    load: function (query, callback) {
                        if (!query.length) {
                            return callback();
                        }
                        var sLang = $('#elm_lang').val();
                        var sType = $('#elm_edit_object_type').val();
                        $.ajax({
                            url: VE.options.actionLink + '&fnc=searchObjects&type=' + sType + '&editlanguage=' + sLang + '&search=' + encodeURIComponent(query),
                            type: 'GET',
                            error: function () {
                                callback();
                            },
                            success: function (res) {
                                callback(res);
                            }
                        });
                    },
                    onChange: function (value) {
                        if (value) {
                            var sBlock = $('#elm_edit_block').val();
                            var sType = $('#elm_edit_object_type').val();
                            var sLang = $('#elm_lang').val();
                            $.get(VE.options.actionLink + '&fnc=searchBlock&editlanguage=' + sLang + '&block=' + sBlock + '&objecttype=' + sType + '&objectid=' + value, function (data) {
                                loadContent.call(this, data);
                            });
                        } else {
                            loadContent.call(this, null);
                        }
                    }
                });
            }
            $('.dd-form-picker').each(function () {
                $(this).selectize({
                    render: {
                        option: function (data, escape) {
                            return '<div class="option"' + (data.color ? ' style="color: ' + data.color + '"' : '') + '>' + escape(data[this.settings.labelField]) + '</div>';
                        }
                    }
                });
            });
        }

        // Set events
        $('input#elm_edit_new').change(function () {
            var $ident = $('input#elm_edit_ident');
            if ($(this).is(':checked')) {
                $('.dd-cms-search, .dd-cms-type-infos').hide();
                $ident.data('old-ident', $ident.val());
                $ident.val('');
            } else {
                $('.dd-cms-search, .dd-cms-type-infos').show();
                if ($ident.val() === '' && $ident.data('old-ident')) {
                    $ident.val($ident.data('old-ident'));
                }
            }
        });
        $('select#elm_lang').change(function () {
            var $picker = $('#elm_edit_cms');
            var id = $picker.val();
            var sLang = $(this).val();/* clear cms-selectize input for template timer*/
            var $timerCmspicker = $('#elm_tmpl_timer_cms');
            if ($timerCmspicker.length && typeof $timerCmspicker[0].selectize == 'object') {
                $timerCmspicker[0].selectize.clearOptions();
            }
            if (id) {
                var self = this;
                var sLangName = $('option[value="' + sLang + '"]', this).text();
                $.ajax({
                    url: VE.options.actionLink + '&fnc=searchContents&editlanguage=' + sLang + '&id=' + id,
                    type: 'GET',
                    success: function (res) {
                        var newinlang = (res.length && res[0].newinlang);
                        var buttons = [{
                            html: '<button type="button" class="btn btn-default" data-dismiss="modal">' + ddh.translate('DD_VISUAL_EDITOR_LANG_CHANGE_CANCEL') + '</button>',
                            action: function ($modal) {
                                $modal.modal('hide');
                                $(self).val($('input[name=selectedlanguage]').val());
                            }
                        }, {
                            html: '<button type="button" class="btn btn-warning">' + ddh.translate('DD_VISUAL_EDITOR_LANG_CHANGE_OVERWRITE') + '</button>',
                            action: function ($modal) {
                                var $editForm = $('#myedit');
                                $editForm.one('saved.ve.content', function (e) {
                                    e.preventDefault();
                                    location.href = VE.options.actionLink + '&editlanguage=' + sLang + '&preloadid=' + id;
                                });
                                if (!newinlang) {
                                    ddh.confirm(ddh.translate('DD_VISUAL_EDITOR_LANG_CHANGE_OVERWRITE_CONFIRM').replace('%s', sLangName), function () {
                                        $modal.modal('hide');
                                        $editForm[0].fnc.value = 'save';
                                        $editForm.submit();
                                    });
                                } else {
                                    $modal.modal('hide');
                                    $editForm[0].fnc.value = 'save';
                                    $editForm.submit();
                                }
                            }
                        }, {html: '<a href="' + VE.options.actionLink + '&editlanguage=' + sLang + '&preloadid=' + id + '" class="btn btn-primary">' + ddh.translate('DD_VISUAL_EDITOR_LANG_CHANGE_LOAD') + '</a>'}];
                        var title = ddh.translate('DD_VISUAL_EDITOR_LANG_CHANGE_TITLE');
                        var msg = ddh.translate('DD_VISUAL_EDITOR_LANG_CHANGE_MSG');
                        ddh._dialog(msg, title, buttons, 'md');
                    }
                });
            } else {
                location.href = VE.options.actionLink + '&editlanguage=' + sLang;
            }
        });

        // Toogle source mode
        $('.dd-veditor-source-action').on('click', function () {
            if (!$(this).hasClass('disabled')) {
                toggleMode();
            }
        });

        // Toggle plain mode
        $('#elm_edit_plaintext').on('change', function () {
            if ($(this).prop('checked')) {
                setPlainMode();
            } else {
                setWidgetMode();
            }
        });

        // Save content
        $('#myedit')
            .on('submit', function (e) {
                e.preventDefault();
                $('button.btn-success', this).attr('disabled', true);
                getLoader().show();
                var self = this;
                var data = $(this).serialize();
                if (blSourceMode) {
                    data += '&source=' + encodeURIComponent($('.dd-veditor-source textarea').val());
                } else {
                    $.each(VE.serialize(), function () {
                        data += '&widget[' + this.row + '][' + this.col + ']=' + encodeURIComponent(JSON.stringify(this));
                    });
                }
                var sLang = getLang();
                data += '&editlanguage=' + sLang;
                $.post($(this).attr('action'), data, function (data) {
                    var savedEvent = $.Event('saved.ve.content');
                    $(self).trigger(savedEvent, [data]);
                    $('button.btn-success', self).removeAttr('disabled');
                    if (savedEvent.isDefaultPrevented()) {
                        return;
                    }
                    if (data.error) {
                        ddh.alert(data.msg);
                        getLoader().hide();
                        return;
                    }
                    if (data.url) {
                        getLoader().hide();
                        if (window.ddcontentpreview && !window.ddcontentpreview.closed) {
                            window.ddcontentpreview.location.href = data.url;
                            window.ddcontentpreview.focus();
                        } else {
                            window.ddcontentpreview = window.open(data.url, '_blank', 'scrollbars=1, width=' + (screen.width * 0.8) + ', height=' + (screen.height * 0.8));
                        }
                    } else {
                        if (!data.id) {
                            preloadContentDropdown();
                            getLoader().hide();
                            return;
                        }
                        var _setActiveContent = function () {
                            if (data['new']) { /* make jshint happy*/
                                $('input#elm_edit_new').prop('checked', false).change();
                            }
                            preloadContentDropdown(data.id);
                            $('.dd-delete-action').removeAttr('disabled');
                        };
                        if (blPlainMode) {
                            setPlainMode(_setActiveContent);
                        } else {
                            setWidgetMode(_setActiveContent);
                        }
                    }
                });
            });

        // Clipboard Action
        if (typeof Clipboard !== 'undefined') {
            var copyAction = new Clipboard('.dd-clipboard-action');
            copyAction.on('success', function (e) {
                var $parent = $(e.trigger).closest('.form-group');
                $parent.addClass('has-feedback');
                window.setTimeout(function () {
                    $parent.removeClass('has-feedback');
                }, 2000);
            });
        }

        // Block assignments
        $('.dd-block-picker').change(function () {
            if ($(this).val() !== '') {
                $('.dd-cms-object-type').show();
            } else {
                $('.dd-cms-object-type').hide();
            }
            var sObjectType = $('.dd-block-object-picker').val();
            if (sObjectType) {
                if (sObjectType !== 'empty') {
                    var $activeObjectElement = $('.dd-cms-object-id:visible select');
                    if ($activeObjectElement.length && $activeObjectElement[0].selectize) {
                        $activeObjectElement[0].selectize.setValue($activeObjectElement.val());
                    }
                } else {
                    $('.dd-block-object-picker').trigger('change');
                }
            }
        });
        $('.dd-block-object-picker').change(function () {
            if ($(this).val() === 'empty') {
                var sLang = $('#elm_lang').val();
                var sBlock = $('#elm_edit_block').val();
                $.get(VE.options.actionLink + '&fnc=searchBlock&editlanguage=' + sLang + '&block=' + sBlock + '&objecttype=empty', function (data) {
                    if (data && data.id) {
                        $('.dd-delete-block-action').removeAttr('disabled');
                    } else {
                        $('.dd-delete-block-action').attr('disabled', true);
                    }
                    loadContent.call(this, data);
                });
            } else {
                if ($(this).val() !== '') {
                    $('.dd-cms-object-id select').each(function () {
                        this.selectize.setValue('');
                    });
                } else {
                    loadContent();
                }
            }
        });
        $('.dd-delete-action').on('click', function (e) {
            e.preventDefault();
            ddh.confirm(ddh.translate('DD_VISUAL_EDITOR_DELETE_CONFIRM'), function () {
                $('#myedit').submit();
            });
        });

        // Templates
        $('.dd-veditor-template-action').on('click', function () {
            if (!$(this).hasClass('disabled')) {
                $('.dd-templates-modal').modal({backdrop: false, keyboard: false, show: true});
            }
        });
        $('.dd-template-action-save').on('click', function (e) {
            e.preventDefault();
            $('.dd-template-form input[name=source]').val('');
            $('.dd-template-form input[name=oxid]').val('');
            $('.dd-template-form input[name=fnc]').val('save');
            $('.dd-template-form').submit();
        });

        // Template Timer
        var $timerModal = $('.dd-template-timer-modal');
        $timerModal.modal({backdrop: false, keyboard: false, show: false});
        $('.dd-templates-modal').on('click', '.dd-template-action-preview', function (e) {
            e.preventDefault();
            var oxid = $(this).closest('li').data('oxid');
            $('.dd-template-form input[name=oxid]').val(oxid);
            $('.dd-template-form input[name=fnc]').val('savePreview');
            $.get(VE.options.actionLink + '&fnc=getTemplate&id=' + oxid, function (data) {
                $('.dd-template-form input[name=source]').val(data);
                $('.dd-template-form').submit();
            });
        }).on('click', '.dd-template-action-delete', function (e) {
            e.preventDefault();
            var $li = $(this).closest('li');
            var oxid = $li.data('oxid');
            if (oxid) {
                ddh.confirm(ddh.translate('DD_VISUAL_EDITOR_DELETE_TEMPLATE_CONFIRM'), function () {
                    $.get(VE.options.actionLink + '&fnc=delete&oxid=' + oxid, function () {
                        $li.remove();
                    });
                });
            }
        }).on('click', '.dd-template-action-apply', function (e) {
            e.preventDefault();
            var $li = $(this).closest('li');
            var oxid = $li.data('oxid');
            if (oxid) {
                ddh.confirm(ddh.translate('DD_VISUAL_EDITOR_APPLY_TEMPLATE_CONFIRM'), function () {
                    $.get(VE.options.actionLink + '&fnc=getTemplate&id=' + oxid, function (data) {
                        setWidgetMode(data, function () {
                            $('.dd-templates-modal').modal('hide');
                        });
                    });
                });
            }
        }).on('click', '.dd-template-action-timer', function (e) {
            e.preventDefault();
            var $li = $(this).closest('li');
            var oxid = $li.data('oxid');
            var $_templateLoader = $('.dd-ajax-loader.dd-template-loader');
            $_templateLoader.show();
            var $_loader = $('.dd-ajax-loader.dd-template-timer-loader');
            $_loader.show();
            var sLang = getLang();
            $.get(VE.options.actionLink + '&fnc=getTemplateData&editlanguage=' + sLang + '&id=' + oxid, function (data) {
                $_templateLoader.hide();
                if (data) {
                    $('#elm_tmpl_timer_oxid').val(data.id);
                    $('#elm_tmpl_timer_name').text(data.title);
                    $('#elm_tmpl_timer_date').val((data.targetdate && data.targetdate.substr(0, 10) != '0000-00-00' ? data.targetdate : ''));
                    preloadContentDropdown((data.targetid ? data.targetid : ''));
                    $_loader.hide();
                } else {
                    $_loader.hide();
                }
                $timerModal.modal('show');
            });
        });
        $('.dd-template-form .form-control').on('keypress', function (e) {
            if (e.keyCode == 13) {
                $('.dd-template-form input[name=source]').val('');
                $('.dd-template-form input[name=oxid]').val('');
                $('.dd-template-form input[name=fnc]').val('save');
            }
        });
        $('.dd-template-timer-form').on('submit', function (e) {
            e.preventDefault();
            var $form = $(this);
            var data = $form.serialize();
            var $_loader = $('.dd-ajax-loader.dd-template-timer-loader');
            $_loader.show();
            $.post($form.attr('action'), data, function (data) {
                if (!data.error) {
                    if (data.tmpl) {
                        var $item = $('.dd-templates-modal .list-group-item[data-oxid="' + data.tmpl.id + '"]');
                        var $info = $item.find('.dd-target-active-info');
                        if (data.tmpl.targetid && data.tmpl.targetdate != '0000-00-00 00:00:00') {
                            if (!$info.length) {
                                $info = $('<small class="text-muted dd-target-active-info"></small>');
                                var $inner = $item.find('.clearfix > .pull-left');
                                $inner.append('<br />');
                                $inner.append($info);
                            }
                            var text = ddh.translate('DD_VISUAL_EDITOR_TEMPLATE_TIMER_ACTIVE_INFO').replace("%s", (data.tmpl.targettitle ? data.tmpl.targettitle : data.tmpl.targetident)).replace("%s", data.tmpl.targetdate.substr(0, 10)).replace("%s", data.tmpl.targetdate.substr(11, 5));
                            $info.html('<i class="fa fa-check"></i> ' + text);
                        } else {
                            if ($info.length) {
                                $info.prev().remove();
                                $info.remove();
                            }
                        }
                    }
                    $('.dd-template-timer-modal').modal('hide');
                }
            });
        });
        $('.dd-template-form').on('submit', function (e) {
            e.preventDefault();
            var $form = $(this);
            var data = $form.serialize();
            if ($('input[name="fnc"]', $form).val() == 'save') {
                if ($('input[name="editval[oxcontents__oxtitle]"]', $form).val() === '') {
                    return;
                }
                $.each(VE.serialize(), function () {
                    data += '&widget[' + this.row + '][' + this.col + ']=' + encodeURIComponent(JSON.stringify(this));
                });
            }
            var $_loader = $('.dd-ajax-loader.dd-template-loader');
            $_loader.show();
            var sLang = getLang();
            data += '&editlanguage=' + sLang;
            $.post($form.attr('action'), data, function (data) {
                if (!data.error) {
                    if (data.url) {
                        if (window.ddcontentpreview && !window.ddcontentpreview.closed) {
                            window.ddcontentpreview.location.href = data.url;
                            window.ddcontentpreview.focus();
                        } else {
                            window.ddcontentpreview = window.open(data.url, '_blank', 'scrollbars=1, width=' + (screen.width * 0.8) + ', height=' + (screen.height * 0.8));
                        }
                    } else {
                        if (data.id) {
                            var $list = $('.dd-templates-modal .dd-veditor-templates');
                            if ($('.no-data', $list).length) {
                                $('.no-data', $list).remove();
                            }
                            var $item = $('<li class="list-group-item" />').attr('data-oxid', data.id);
                            var $itemInner = $('<div class="clearfix" />').appendTo($item);
                            var $titleContainer = $('<div class="pull-left" />').appendTo($itemInner);
                            $titleContainer.append('<span>' + data.title + '</span>');
                            var $actions = $('<div class="pull-right" />').appendTo($itemInner);
                            $actions.append('<button type="button" class="btn btn-xs btn-link dd-template-action-apply"><i class="fa fa-plus"></i></button>');
                            $actions.append('<button type="button" class="btn btn-xs btn-link dd-template-action-timer"><i class="fa fa-clock-o"></i></button>');
                            $actions.append('<button type="button" class="btn btn-xs btn-link dd-template-action-preview"><i class="fa fa-external-link"></i></button>');
                            $actions.append('<button type="button" class="btn btn-xs btn-link dd-template-action-delete"><i class="fa fa-trash"></i></button>');
                            $list.prepend($item);
                            $('input[name="editval[oxcontents__oxtitle]"]', $form).val('');
                        }
                    }
                } else {
                    ddh.alert(data.msg);
                }
                $_loader.hide();
            });
        });

        // Timespan activation
        $('.dd-veditor-timespan-toggle').on('click', function () {
            var $timespan = $('.dd-veditor-timespan');
            if ($timespan.is(':visible')) {
                $timespan.hide();
                $(this).html(ddh.translate('DD_VISUAL_EDITOR_ACTIVE_TIMESPAN_SELECT'));
            } else {
                $timespan.show();
                $(this).html(ddh.translate('DD_VISUAL_EDITOR_ACTIVE_TIMESPAN_HIDE'));
            }
            setTabContentHeight();
        });

        // Demodata action
        $('.dd-demodata-action').on('click', function () {
            var $action = $(this);
            if ($action.hasClass('hasDemodata')) {
                loadDemoContent();
            } else {
                $action.html('<i class="fa fa-cog fa-spin"></i>');
                $action.prop('disabled', true);
                $.get(VE.options.actionLink + '&fnc=installDemodata&editlanguage=' + getLang(), function (res) {
                    $action.prop('disabled', false);
                    if (res && res.success) {
                        $action.addClass('hasDemodata');
                        $action.html(ddh.translate('DD_VISUAL_EDITOR_LOAD_DEMODATA'));
                        loadDemoContent();
                    }
                });
            }
        });

        // ACE Less Editor
        var aceEditor = ace.edit($('.dd-less-editor')[0]);
        aceEditor.setTheme('ace/theme/xcode');
        aceEditor.getSession().setMode('ace/mode/less');
        var $cssModal = $('.dd-css-modal');
        $cssModal.modal({backdrop: false, keyboard: false, show: false}).on('show.bs.modal', function () {
            aceEditor.setValue($('#elm_edit_css').val());
        });
        $('.dd-css-form').on('submit', function (e) {
            e.preventDefault();
            $('#elm_edit_css').val(aceEditor.getValue());
            $cssModal.modal('hide');
        });
        $('.dd-veditor-css-action').on('click', function () {
            if (!$(this).hasClass('disabled')) {
                $cssModal.modal('show');
            }
        });

        // Treeview
        var $treeviewModal = $('.dd-treeview-modal'), $treeviewForm = $('.dd-treeview-form'),
            $treeviewMain = $('#dd-treeview-main-inner'), $treeviewSnippets = $('#dd-treeview-snippets-inner'),
            $treeviewMainSearch = $('.dd-treeview-search-main'),
            $treeviewSnippetsSearch = $('.dd-treeview-search-snippets'),
            $treeviewAjaxLoaderMain = $('.dd-treeview-main-loader'),
            $treeviewAjaxLoaderSnippets = $('.dd-treeview-snippets-loader'), treeviewMainSearchTO = false,
            treeviewSnippetsSearchTO = false;
        $treeviewModal.modal({backdrop: false, keyboard: false, show: false}).on('hidden.bs.modal', function () {
            $treeviewForm.trigger("reset");
            $treeviewMain.jstree(true).clear_search();
            $treeviewMain.jstree(true).close_all();
            $treeviewSnippets.jstree(true).clear_search();
            $treeviewSnippets.jstree(true).close_all();
            $('#btn_tab_treeview_main').tab('show');
            $treeviewAjaxLoaderMain.hide();
            $treeviewAjaxLoaderSnippets.hide();
        });
        $treeviewForm.on('submit', function (e) {
            e.preventDefault();
            var value = $('#elm_edit_treeview_contentid').val();
            $treeviewModal.modal('hide');
            var $picker = $('#elm_edit_cms');
            if ($picker.length && typeof $picker[0].selectize == 'object') {
                $picker[0].selectize.setValue(value);
            }
        });
        $('.dd-veditor-treeview-action').on('click', function () {
            if (!$(this).hasClass('disabled')) {
                $treeviewMain.jstree(true).refresh();
                $treeviewSnippets.jstree(true).refresh();
                $treeviewModal.modal('show');
            }
        });
        $('#btn_clear_treeview_search_main').on('click', function () {
            $treeviewMainSearch.val('');
            $treeviewMain.jstree(true).show_all();
            $treeviewMain.jstree(true).clear_search();
            $treeviewMain.jstree(true).close_all();
            $('#btn_clear_treeview_search_main').hide();
            $treeviewAjaxLoaderMain.hide();
        });
        $('#btn_clear_treeview_search_snippets').on('click', function () {
            $treeviewSnippetsSearch.val('');
            $treeviewSnippets.jstree(true).show_all();
            $treeviewSnippets.jstree(true).clear_search();
            $treeviewSnippets.jstree(true).close_all();
            $('#btn_clear_treeview_search_snippets').hide();
            $treeviewAjaxLoaderSnippets.hide();
        });
        $treeviewMainSearch.on('input', function () {
            if ($treeviewMainSearch.val() === '') {
                $('#btn_clear_treeview_search_main').hide();
                $treeviewMain.jstree(true).clear_search();
            } else {
                $('#btn_clear_treeview_search_main').show();
            }
        });
        $treeviewSnippetsSearch.on('input', function () {
            if ($treeviewSnippetsSearch.val() === '') {
                $('#btn_clear_treeview_search_snippets').hide();
                $treeviewSnippets.jstree(true).clear_search();
            } else {
                $('#btn_clear_treeview_search_snippets').show();
            }
        });
        $treeviewMain.on('move_node.jstree', function (e, data) {
            $.get(VE.options.actionLink + '&fnc=saveTreeviewNode&id=' + data.node.id + '&parentid=' + data.parent + '&pos=' + data.position + '&oldparentid=' + data.old_parent + '&oldpos=' + data.old_position);
        }).on('changed.jstree', function (e, data) {
            $('#elm_edit_treeview_contentid').val('' + data.instance.get_node(data.selected[0]).id);
        }).on('dnd_start.vakata.jstree', function () {
            $('#jstree-marker').appendTo('.dd-treeview-modal');
        }).on('search.jstree', function (nodes, str) {
            $treeviewAjaxLoaderMain.show();
            if (str.nodes.length === 0) {
                $treeviewMain.jstree(true).hide_all();
                $('.dd-treeview-main-no-results').show();
            }
            $treeviewAjaxLoaderMain.hide();
        }).on('clear_search.jstree', function () {
            $treeviewAjaxLoaderMain.hide();
        }).on('show_all.jstree', function () {
            $('.dd-treeview-main-no-results').hide();
        }).jstree({
            "core": {
                "animation": 250,
                "force_text": true,
                "check_callback": true,
                "themes": {"name": "default", "stripes": true, "dots": true, "responsive": true},
                'data': {
                    'url': VE.options.actionLink + '&fnc=getTreeviewNodes&type=main', 'data': function (node) {
                        return {'id': node.id};
                    }
                }
            },
            "dnd": {"copy": false, "inside_pos": "last", "large_drop_target": true},
            "search": {
                "show_only_matches": true,
                "show_only_matches_children": true,
                "ajax": {'url': VE.options.actionLink + '&fnc=searchTreeviewNodes&type=main'}
            },
            "plugins": ["dnd", "search"]
        });
        $treeviewSnippets.on('changed.jstree', function (e, data) {
            $('#elm_edit_treeview_contentid').val(data.instance.get_node(data.selected[0]).id);
        }).on('search.jstree', function (nodes, str) {
            $treeviewAjaxLoaderSnippets.show();
            if (str.nodes.length === 0) {
                $treeviewSnippets.jstree(true).hide_all();
                $('.dd-treeview-snippets-no-results').show();
            }
            $treeviewAjaxLoaderSnippets.hide();
        }).on('clear_search.jstree', function () {
            $treeviewAjaxLoaderSnippets.hide();
        }).on('show_all.jstree', function () {
            $('.dd-treeview-snippets-no-results').hide();
        }).jstree({
            "core": {
                "animation": 250,
                "check_callback": true,
                "force_text": true,
                "themes": {"name": "default", "stripes": true, "dots": true, "responsive": true},
                'data': {
                    'url': VE.options.actionLink + '&fnc=getTreeviewNodes&type=snippets', 'data': function (node) {
                        return {'id': node.id};
                    }
                }
            },
            "search": {
                "show_only_matches": true,
                "show_only_matches_children": true,
                "ajax": {'url': VE.options.actionLink + '&fnc=searchTreeviewNodes&type=snippets'}
            },
            "plugins": ["search"]
        });
        $treeviewMainSearch.keyup(function () {
            if (treeviewMainSearchTO) {
                clearTimeout(treeviewMainSearchTO);
            }
            treeviewMainSearchTO = setTimeout(function () {
                $treeviewMain.jstree(true).show_all();
                $treeviewAjaxLoaderMain.show();
                $treeviewMain.jstree(true).search($treeviewMainSearch.val());
            }, 250);
        });
        $treeviewSnippetsSearch.keyup(function () {
            if (treeviewSnippetsSearchTO) {
                clearTimeout(treeviewSnippetsSearchTO);
            }
            treeviewSnippetsSearchTO = setTimeout(function () {
                $treeviewSnippets.jstree(true).show_all();
                $treeviewAjaxLoaderSnippets.show();
                $treeviewSnippets.jstree(true).search($treeviewSnippetsSearch.val());
            }, 250);
        });

        // Predefined CSS Classes
        if (typeof $().selectize == 'function') {
            var cssClassOptions = [];
            if (window.cssclasses && window.cssclasses.length) {
                for (var i in window.cssclasses) {
                    cssClassOptions.push({
                        label: window.cssclasses[i],
                        value: window.cssclasses[i]
                    })
                }
            }
            $('.dd-widget-class').selectize({
                delimiter: ' ',
                persist: false,
                maxItems: null,
                valueField: 'value',
                labelField: 'label',
                searchField: ['label'],
                options: cssClassOptions,
                create: function (input) {
                    return {label: input, value: input}
                }
            });
        }
    });
}(jQuery);
