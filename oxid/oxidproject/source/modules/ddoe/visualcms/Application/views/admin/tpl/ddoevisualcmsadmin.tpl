[{assign var="oViewConf" value=$oView->getViewConfig()}]
[{assign var="oConf" value=$oView->getConfig()}]

[{oxstyle include=$oViewConf->getModuleUrl('ddoevisualcms','out/src/css/backend.min.css')}]
[{oxstyle include=$oViewConf->getModuleUrl('ddoevisualcms','out/src/js/jstree/themes/default/style.min.css')}]

[{capture append="content"}]

    [{block name="visualcms_shortcodes"}]

        [{foreach from=$veditor->getShortCodes() item="oCode"}]

            [{block name="visualcms_shortcodes_types"}]

                [{if $oCode->getShortCode() != 'column'}]
                    [{capture append="aShortCodeTypes"}]
                        <option data-data='{"area":"dd-type-[{$oCode->getShortCode()}]","label":"<i class=\"fa [{$oCode->getIcon()}]\"></i> [{$oCode->getTitle()}]"}' value="[{$oCode->getShortCode()}]">[{$oCode->getTitle()}]</option>
                    [{/capture}]

                    [{capture append="aShortCodeSelect"}]
                        <button class="btn btn-default dd-widget-select-box" data-widget="[{$oCode->getShortCode()}]" data-color="[{$oCode->getBackgroundColor()}]" title="[{$oCode->getTitle()}]">
                        <span class="dd-widget-select-description">
                            <i class="fa fa-2x [{$oCode->getIcon()}]"></i><br />
                            <span>[{$oCode->getTitle()}]</span>
                        </span>
                        </button>
                    [{/capture}]
                [{else}]
                    [{capture append="aShortCodeTypes"}]
                        <option data-data='{"area":"dd-type-[{$oCode->getShortCode()}]","label":"<i class=\"fa [{$oCode->getIcon()}]\"></i> [{$oCode->getTitle()}]"}' value="[{$oCode->getShortCode()}]">[{$oCode->getTitle()}]</option>
                    [{/capture}]
                [{/if}]

            [{/block}]

            [{block name="visualcms_shortcodes_options"}]

                [{foreach from=$oCode->getOptions() key="sOptionName" item="aOption"}]

                    [{block name="visualcms_shortcodes_options_fields"}]

                        [{if $aOption.editable === false}]
                            [{assign var="sOptionEditable" value='data-editable="false" readonly="true"'}]
                        [{else}]
                            [{assign var="sOptionEditable" value='data-editable="true"'}]
                        [{/if}]

                        [{capture append="aShortCodeFields"}]
                            [{if $aOption.type == 'text' || $aOption.type == 'color'}]
                                <div class="form-group" data-area="dd-type-[{$oCode->getShortCode()}]" data-area-group="dd-widget-type">
                                    <label for="elm_widget_[{$oCode->getShortCode()}]_[{$sOptionName}]" class="col-sm-2 control-label">[{$aOption.label}]</label>
                                    <div class="col-sm-10">
                                        <input type="text" name="[{$oCode->getShortCode()}][[{$sOptionName}]]" class="form-control[{if $aOption.type == 'color'}] dd-color-picker[{/if}]" id="elm_widget_[{$oCode->getShortCode()}]_[{$sOptionName}]" placeholder="[{$aOption.placeholder}]" value="[{$aOption.value}]" data-default-value="[{$aOption.value}]"[{if $aOption.hint}] data-toggle="tooltip" data-trigger="focus" data-container="body" data-placement="right" title="[{$aOption.hint}]"[{/if}][{if $aOption.random}] data-random-value="true"[{/if}] [{$sOptionEditable}] />
                                        [{if $aOption.help}]
                                            <p class="help-block">[{$aOption.help}]</p>
                                        [{/if}]
                                    </div>
                                </div>
                            [{elseif $aOption.type == 'file'}]
                                <div class="form-group" data-area="dd-type-[{$oCode->getShortCode()}]" data-area-group="dd-widget-type">
                                    <label for="elm_widget_[{$oCode->getShortCode()}]_[{$sOptionName}]" class="col-sm-2 control-label">[{$aOption.label}]</label>
                                    <div class="col-sm-6">
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="[{$oCode->getShortCode()}][[{$sOptionName}]]" id="elm_widget_[{$oCode->getShortCode()}]_[{$sOptionName}]" placeholder="[{$aOption.placeholder}]" value="[{$aOption.value}]" data-default-value="[{$aOption.value}]"[{if $aOption.hint}] data-toggle="tooltip" data-trigger="focus" data-container="body" data-placement="right" title="[{$aOption.hint}]"[{/if}] [{$sOptionEditable}] />
                                            <span class="input-group-btn">
                                                <button class="btn btn-info dd-media-action" data-target="#elm_widget_[{$oCode->getShortCode()}]_[{$sOptionName}]" type="button">
                                                    <i class="fa fa-file-image-o"></i>
                                                </button>
                                            </span>
                                        </div>
                                        [{if $aOption.help}]
                                            <p class="help-block">[{$aOption.help}]</p>
                                        [{/if}]
                                    </div>
                                </div>
                            [{elseif $aOption.type == 'image'}]
                                <div class="form-group" data-area="dd-type-[{$oCode->getShortCode()}]" data-area-group="dd-widget-type">
                                    <label for="elm_widget_[{$oCode->getShortCode()}]_[{$sOptionName}]" class="col-sm-2 control-label">[{$aOption.label}]</label>
                                    <div class="col-sm-10">
                                        <div class="row">

                                            <div class="dd-widget-image-list" id="elm_widget_images_[{$oCode->getShortCode()}]_[{$sOptionName}]" data-input-name="[{$oCode->getShortCode()}][[{$sOptionName}]]" data-multi="[{if $aOption.multi}]1[{else}]0[{/if}]" data-max-length="[{if $aOption.maxLength}][{$aOption.maxLength}][{elseif !$aOption.multi}]1[{/if}]">

                                                <div class="dd-widget-image-item dd-widget-image-item-helper">
                                                    <div class="dd-widget-image-item-preview">
                                                        <div class="dd-widget-image-item-centered">
                                                            <img class="dd-widget-image-thumb">
                                                        </div>
                                                    </div>
                                                    <div class="dd-widget-image-delete-item" data-target="#elm_widget_images_[{$oCode->getShortCode()}]_[{$sOptionName}]">
                                                        <i class="fa fa-times"></i> [{oxmultilang ident="DD_VISUAL_EDITOR_WIDGET_DELETE"}]
                                                    </div>
                                                </div>

                                                <div class="col-sm-2">

                                                    <a class="dd-widget-image-item dd-widget-image-add-item" href="javascript:void(null);" data-target="#elm_widget_images_[{$oCode->getShortCode()}]_[{$sOptionName}]">
                                                        <div class="dd-widget-image-item-preview">
                                                            <div class="dd-widget-image-item-centered">
                                                                <span class="dd-widget-image-text">[{oxmultilang ident="DD_VISUAL_EDITOR_ADD_IMAGE"}]</span>
                                                            </div>
                                                        </div>
                                                    </a>

                                                </div>

                                            </div>

                                        </div>

                                        [{if $aOption.help}]
                                            <p class="help-block">[{$aOption.help}]</p>
                                        [{/if}]
                                    </div>
                                </div>
                            [{elseif $aOption.type == 'checkbox'}]
                                <div class="form-group" data-area="dd-type-[{$oCode->getShortCode()}]" data-area-group="dd-widget-type">
                                    <div class="col-sm-10 col-sm-offset-2">
                                        <div class="checkbox">
                                            <label>
                                                <input type="hidden" name="[{$oCode->getShortCode()}][[{$sOptionName}]]" value="0" [{$sOptionEditable}] />
                                                <input type="checkbox" name="[{$oCode->getShortCode()}][[{$sOptionName}]]" id="elm_widget_[{$oCode->getShortCode()}]_[{$sOptionName}]" value="[{if $aOption.value}][{$aOption.value}][{else}]1[{/if}]"[{if $aOption.checked}] checked="checked"[{/if}] [{$sOptionEditable}] /> [{$aOption.label}]
                                            </label>
                                        </div>
                                        [{if $aOption.help}]
                                            <p class="help-block">[{$aOption.help}]</p>
                                        [{/if}]
                                    </div>
                                </div>
                            [{elseif $aOption.type == 'select' || $aOption.type == 'multi'}]
                                <div class="form-group" data-area="dd-type-[{$oCode->getShortCode()}]" data-area-group="dd-widget-type">
                                    <label for="elm_widget_[{$oCode->getShortCode()}]_[{$sOptionName}]" class="col-sm-2 control-label">[{$aOption.label}]</label>
                                    <div class="col-sm-10">
                                        <select name="[{$oCode->getShortCode()}][[{$sOptionName}]][{if $aOption.type == 'multi'}][][{/if}]" class="form-control[{if $aOption.data}] dd-data-picker[{else}] dd-picker[{/if}]" placeholder="[{$aOption.placeholder}]" id="elm_widget_[{$oCode->getShortCode()}]_[{$sOptionName}]"  data-default-value="[{$aOption.value}]"[{if $aOption.data}] data-action="[{$aOption.data}]" data-shortcode="[{$oCode->getShortCode()}]"[{/if}] [{if $aOption.type == 'multi'}]multiple="1"[{/if}] [{$sOptionEditable}]>
                                            [{if !$aOption.data}]
                                                [{foreach from=$aOption.values key="_value" item="_label"}]
                                                    [{if $_label|is_array}]
                                                        <optgroup label="[{$_value}]">
                                                            [{foreach from=$_label key="__value" item="__label"}]
                                                                <option value="[{$__value}]"[{if $__value == $aOption.value}] selected="selected"[{/if}] data-data='{"label":"[{$__label|addslashes|replace:'\\\'':'\''|oxescape}]"}'>[{$__label}]</option>
                                                            [{/foreach}]
                                                        </optgroup>
                                                    [{else}]
                                                        <option value="[{$_value}]"[{if $_value == $aOption.value}] selected="selected"[{/if}] data-data='{"label":"[{$_label|addslashes|replace:'\\\'':'\''|oxescape}]"}'>[{$_label}]</option>
                                                    [{/if}]
                                                [{/foreach}]
                                            [{/if}]
                                        </select>
                                        [{if $aOption.help}]
                                            <p class="help-block">[{$aOption.help}]</p>
                                        [{/if}]
                                    </div>
                                </div>
                            [{elseif $aOption.type == 'textarea' || $aOption.type == 'wysiwyg'}]
                                <div class="form-group" data-area="dd-type-[{$oCode->getShortCode()}]" data-area-group="dd-widget-type">
                                    <label for="elm_widget_[{$oCode->getShortCode()}]_[{$sOptionName}]" class="col-sm-2 control-label">[{$aOption.label}]</label>
                                    <div class="col-sm-10">
                                        <textarea name="[{$oCode->getShortCode()}][[{$sOptionName}]]" class="form-control[{if $aOption.type == 'wysiwyg'}] dd-editor[{/if}]" id="elm_widget_[{$oCode->getShortCode()}]_[{$sOptionName}]" placeholder="[{$aOption.placeholder}]" data-default-value="[{$aOption.value}]"[{if $aOption.hint}] data-toggle="tooltip" data-trigger="focus" data-container="body" data-placement="right" title="[{$aOption.hint}]"[{/if}][{if $aOption.random}] data-random-value="true"[{/if}] [{$sOptionEditable}]>[{$aOption.value}]</textarea>
                                        [{if $aOption.help}]
                                            <p class="help-block">[{$aOption.help}]</p>
                                        [{/if}]
                                    </div>
                                </div>
                            [{elseif $aOption.type == 'hidden'}]
                                <input type="hidden" name="[{$oCode->getShortCode()}][[{$sOptionName}]]" value="[{$aOption.value}]" data-default-value="[{$aOption.value}]"[{if $aOption.random}] data-random-value="true"[{/if}] [{$sOptionEditable}] />
                            [{/if}]
                        [{/capture}]

                    [{/block}]

                    [{block name="visualcms_shortcodes_options_fields_data"}]

                        [{if $aOption.data}]
                            <script type="text/javascript">
                                [{capture append="aCustomScripts"}]
                                $( function()
                                    {
                                        $( '#elm_widget_[{$oCode->getShortCode()}]_[{$sOptionName}]' ).selectize(
                                            {
                                                valueField: 'value',
                                                labelField: 'label',
                                                searchField: [
                                                    'label',
                                                    'description'
                                                ],
                                                create: false,
                                                render: {
                                                    option: function(item, escape) {

                                                        var opt = '<div class="media">';

                                                        if( item.icon )
                                                        {
                                                            opt += '<div class="pull-left" style="width:32px;">' +
                                                            '  <img src="' + item.icon + '" class="media-object" style="max-width: 32px; max-height: 32px; margin-top: 3px;" />' +
                                                            '</div>';
                                                        }

                                                        opt += '  <div class="media-body">' +
                                                        '    <strong class="media-heading name">' + escape(item.label) + '</strong>';

                                                        if( item.description )
                                                        {
                                                            opt += '<br /><small>' + item.description + '</small>';
                                                        }

                                                        opt += '  </div>' +
                                                        '</div>';

                                                        return opt;
                                                    }
                                                },
                                                load: function(query, callback) {
                                                    if (!query.length)
                                                    {
                                                        return callback();
                                                    }

                                                    var $form      = $( 'form#transfer' );
                                                    var formAction = $form.attr( 'action' ) + $form.serialize();

                                                    $.ajax({
                                                        url: formAction + '&fnc=doShortCodeAction&shortcode=[{$oCode->getShortCode()}]&action=[{$aOption.data}]&search=' + encodeURIComponent(query),
                                                        type: 'GET',
                                                        error: function() {
                                                            callback();
                                                        },
                                                        success: function(res) {
                                                            callback(res);
                                                        }
                                                    });
                                                },
                                                onChange: function( value )
                                                {
                                                    [{if $aOption.dataFields}]
                                                    if( value && this.options[ value ] )
                                                    {
                                                        [{foreach from=$aOption.dataFields key="sField" item="sKey"}]
                                                        if( this.options[ value ].[{$sKey}] )
                                                        {
                                                            $( '.dd-widget-form *[name="[{$oCode->getShortCode()}][[{$sField}]]"]' ).val( this.options[ value ].[{$sKey}] );
                                                        }
                                                        [{/foreach}]
                                                    }
                                                    [{/if}]
                                                }
                                            }
                                        );
                                    }
                                );
                                [{/capture}]
                            </script>
                        [{elseif $aOption.dataFields}]

                            <script type="text/javascript">
                                [{capture append="aCustomScripts"}]
                                $( function()
                                    {
                                        $( '#elm_widget_[{$oCode->getShortCode()}]_[{$sOptionName}]' )[ 0 ].selectize.on( 'change', function( value )
                                            {
                                                if( !value )
                                                {
                                                    return;
                                                }

                                                if( typeof value !== 'object' )
                                                {
                                                    value = [ value ];
                                                }

                                                var sel = this;

                                                [{foreach from=$aOption.dataFields key="sField" item="sKey"}]
                                                $( '.dd-widget-form *[name="[{$oCode->getShortCode()}][[{$sField}]]"]' ).val( '' );
                                                [{/foreach}]

                                                $.each( value, function()
                                                        {
                                                            if( this && sel.options[ this ] )
                                                            {
                                                                [{foreach from=$aOption.dataFields key="sField" item="sKey"}]
                                                                if( sel.options[ this ].[{$sKey}] )
                                                                {
                                                                    var $field = $( '.dd-widget-form *[name="[{$oCode->getShortCode()}][[{$sField}]]"]' );
                                                                    $field.val( ( $field.val() != '' ? $field.val() + ', ' : '' ) + sel.options[ this ].[{$sKey}] );
                                                                }
                                                                [{/foreach}]
                                                            }
                                                        }
                                                );

                                            }
                                        );
                                    }
                                );
                                [{/capture}]
                            </script>

                        [{/if}]

                    [{/block}]

                [{/foreach}]

            [{/block}]

            [{block name="visualcms_shortcodes_data"}]

                [{capture append="aShortCodeData"}]
                    <script type="text/javascript">
                        window.shortcodes[ '[{$oCode->getShortCode()}]' ] = {
                            previewParam: '[{$oCode->getPreviewOption()}]',
                            options: [{$oCode->getOptions()|@json_encode}],
                            name: '[{$oCode->getTitle()}]',
                            icon: '[{$oCode->getIcon()}]'
                        };
                    </script>
                [{/capture}]

            [{/block}]

            [{block name="visualcms_shortcodes_styles"}]

                [{capture append="aCustomStyles"}]
                    <style type="text/css">
                        .dd-veditor-widget.dd-widget-type-[{$oCode->getShortCode()}] > .dd-widget-inner {
                            background: [{$oCode->getBackgroundColor()}];
                        }
                    </style>
                [{/capture}]

            [{/block}]

        [{/foreach}]

    [{/block}]

    [{foreach from=$aCustomStyles item="sStyle"}]
        [{$sStyle}]
    [{/foreach}]

    <div class="dd-ajax-loader dd-main-loader">
        <img src="[{$oViewConf->getModuleUrl('ddoevisualcms','out/src/img/loading.svg')}]" />
    </div>

    <div class="dd-visual-editor">

        <form name="transfer" id="transfer" action="[{$oViewConf->getSelfLink()|replace:'editlanguage':'_editlanguage'}]" method="post">
            [{$oViewConf->getHiddenSid()}]
            <input type="hidden" name="cl" value="ddoevisualcmsadmin">
        </form>

        <div class="row" style="height: 100%;">

            <div class="col-md-3 col-sm-4 col-xs-12 hidden-xs" style="height: 100%;">

                <div id="settings">

                    [{block name="visualcms_settings_form"}]

                        <form name="myedit" class="form" role="form" id="myedit" action="[{$oViewConf->getSelfLink()|replace:'editlanguage':'_editlanguage'}]" method="post">

                            [{block name="visualcms_settings_form_hidden"}]
                                [{$oViewConf->getHiddenSid()}]
                                <input type="hidden" name="cl" value="ddoevisualcmsadmin">
                                <input type="hidden" name="fnc" value="">
                                <input type="hidden" id="elm_edit_oxid" name="oxid" value="">

                                <input type="hidden" name="editval[oxcontents__ddcustomcss]" id="elm_edit_css" value="" />
                            [{/block}]

                            <div class="form-group" style="margin-bottom: 0;">

                                <div class="dd-veditor-activestate-box">

                                    [{block name="visualcms_settings_form_activestate"}]

                                        <div class="form-group clearfix">
                                            <div class="checkbox pull-left">
                                                <label>
                                                    <input type="hidden" name="editval[oxcontents__oxactive]" value="0" />
                                                    <input type="checkbox" name="editval[oxcontents__oxactive]" id="elm_edit_active" value="1" /> [{oxmultilang ident="DD_VISUAL_EDITOR_ACTIVE"}]
                                                </label>
                                            </div>
                                            <div class="pull-right">
                                                <a href="javascript:void(null)" class="dd-veditor-timespan-toggle text-muted">[{oxmultilang ident="DD_VISUAL_EDITOR_ACTIVE_TIMESPAN_SELECT"}]</a>
                                            </div>
                                        </div>

                                        <div class="dd-veditor-timespan">

                                            <div class="row">

                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label for="elm_edit_active_from" class="control-label">[{oxmultilang ident="DD_VISUAL_EDITOR_ACTIVE_TIMESPAN_FROM"}] <i class="fa fa-info-circle" title="[{oxmultilang ident="DD_VISUAL_EDITOR_ACTIVE_TIMESPAN_INFO"}]" data-toggle="tooltip" data-container="body"></i></label>
                                                        <input type="text" name="editval[oxcontents__ddactivefrom]" class="form-control" id="elm_edit_active_from" placeholder="YYYY-MM-DD" />
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label for="elm_edit_active_until" class="control-label">[{oxmultilang ident="DD_VISUAL_EDITOR_ACTIVE_TIMESPAN_UNTIL"}] <i class="fa fa-info-circle" title="[{oxmultilang ident="DD_VISUAL_EDITOR_ACTIVE_TIMESPAN_INFO"}]" data-toggle="tooltip" data-container="body"></i></label>
                                                        <input type="text" name="editval[oxcontents__ddactiveuntil]" class="form-control" id="elm_edit_active_until" placeholder="YYYY-MM-DD" />
                                                    </div>
                                                </div>

                                            </div>

                                        </div>

                                    [{/block}]

                                </div>

                            </div>

                            <div class="clearfix"></div>

                            <div class="dd-veditor-form-settings">

                                <div class="dd-tab-form">

                                    <ul class="nav nav-tabs" role="tablist">
                                        [{block name="visualcms_settings_form_tabs"}]
                                            <li role="presentation" class="active"><a href="#tab_settings_main" role="tab" data-toggle="tab">[{oxmultilang ident="DD_VISUAL_EDITOR_SETTINGS_MAIN"}]</a></li>
                                            <li role="presentation"><a href="#tab_settings_advanced" role="tab" data-toggle="tab">[{oxmultilang ident="DD_VISUAL_EDITOR_SETTINGS_ADVANCED"}]</a></li>
                                            <li role="presentation"><a href="#tab_settings_seo" role="tab" data-toggle="tab">[{oxmultilang ident="DD_VISUAL_EDITOR_SETTINGS_SEO"}]</a></li>
                                        [{/block}]
                                    </ul>

                                    <div class="tab-content">

                                        [{block name="visualcms_settings_form_tabs_content"}]

                                            <div role="tabpanel" class="tab-pane active" id="tab_settings_main">

                                                [{block name="visualcms_settings_form_tabs_main"}]

                                                    [{if $blocks}]
                                                        <div class="form-group dd-cms-type">
                                                            <label for="elm_edit_cms" class="control-label">[{oxmultilang ident="DD_VISUAL_EDITOR_TYPE"}]</label>
                                                            <select name="cms_type" class="form-control dd-picker dd-area-select dd-cms-type-select" data-area-group-value="dd-cms-type" data-style="btn-inverse" id="elm_edit_cms_type">
                                                                <option data-data='{"area":"dd-type-cms-default"}' value="default" selected="selected">[{oxmultilang ident="DD_VISUAL_EDITOR_TYPE_STANDARD"}]</option>
                                                                <option data-data='{"area":"dd-type-cms-block"}' value="block">[{oxmultilang ident="DD_VISUAL_EDITOR_TYPE_BLOCK"}]</option>
                                                            </select>
                                                        </div>

                                                        <div data-area="dd-type-cms-block" data-area-group="dd-cms-type" style="display: none;">

                                                            <div class="form-group dd-cms-block">
                                                                <label for="elm_edit_block" class="control-label">[{oxmultilang ident="DD_VISUAL_EDITOR_BLOCK"}]</label>
                                                                <select name="block" class="form-control dd-picker dd-block-picker" placeholder="[{oxmultilang ident="DD_VISUAL_EDITOR_CHOOSE_BLOCK"}]" id="elm_edit_block">
                                                                    <option></option>
                                                                    [{foreach from=$blocks item="block" key="ident"}]
                                                                        <option value="[{$ident}]">[{$block}]</option>
                                                                    [{/foreach}]
                                                                </select>
                                                            </div>

                                                            <div class="form-group dd-cms-object-type" style="display: none;">
                                                                <label for="elm_edit_object_type" class="control-label">[{oxmultilang ident="DD_VISUAL_EDITOR_BLOCK_OBJECT_TYPE"}]</label>
                                                                <select name="editval[oxcontents__ddobjecttype]" class="form-control dd-picker dd-block-object-picker dd-area-select" data-area-group-value="dd-object-type" data-style="btn-inverse" id="elm_edit_object_type" placeholder="[{oxmultilang ident="DD_VISUAL_EDITOR_CHOOSE_BLOCK_OBJECT"}]">
                                                                    <option selected="selected"></option>
                                                                    <option data-data='{"area":"dd-object-type-empty"}' value="empty">[{oxmultilang ident="DD_VISUAL_EDITOR_BLOCK_OBJECT_EMPTY"}]</option>
                                                                    <option data-data='{"area":"dd-object-type-article"}' value="article">[{oxmultilang ident="DD_VISUAL_EDITOR_BLOCK_OBJECT_ARTICLE"}]</option>
                                                                    <option data-data='{"area":"dd-object-type-category"}' value="category">[{oxmultilang ident="DD_VISUAL_EDITOR_BLOCK_OBJECT_CATEGORY"}]</option>
                                                                    <option data-data='{"area":"dd-object-type-manufacturer"}' value="manufacturer">[{oxmultilang ident="DD_VISUAL_EDITOR_BLOCK_OBJECT_MANUFACTURER"}]</option>
                                                                </select>
                                                            </div>

                                                            <div class="form-group dd-cms-object-id" data-area="dd-object-type-article" data-area-group="dd-object-type" style="display: none;">
                                                                <label for="elm_edit_object_article" class="control-label">[{oxmultilang ident="DD_VISUAL_EDITOR_BLOCK_OBJECT_ARTICLE"}]</label>
                                                                <select name="editval[oxcontents__ddobjectid][article]" class="form-control dd-article-picker" id="elm_edit_object_article">
                                                                    <option></option>
                                                                </select>
                                                            </div>

                                                            <div class="form-group dd-cms-object-id" data-area="dd-object-type-category" data-area-group="dd-object-type" style="display: none;">
                                                                <label for="elm_edit_object_category" class="control-label">[{oxmultilang ident="DD_VISUAL_EDITOR_BLOCK_OBJECT_CATEGORY"}]</label>
                                                                <select name="editval[oxcontents__ddobjectid][category]" class="form-control dd-category-picker" id="elm_edit_object_category">
                                                                    <option></option>
                                                                </select>
                                                            </div>

                                                            <div class="form-group dd-cms-object-id" data-area="dd-object-type-manufacturer" data-area-group="dd-object-type" style="display: none;">
                                                                <label for="elm_edit_object_manufacturer" class="control-label">[{oxmultilang ident="DD_VISUAL_EDITOR_BLOCK_OBJECT_MANUFACTURER"}]</label>
                                                                <select name="editval[oxcontents__ddobjectid][manufacturer]" class="form-control dd-manufacturer-picker" id="elm_edit_object_manufacturer">
                                                                    <option></option>
                                                                </select>
                                                            </div>

                                                        </div>
                                                    [{/if}]

                                                    <div data-area="dd-type-cms-default" data-area-group="dd-cms-type">

                                                        <div class="form-group">
                                                            <div class="checkbox">
                                                                <label>
                                                                    <input type="hidden" name="new" value="0" />
                                                                    <input type="checkbox" name="new" id="elm_edit_new" value="1" /> [{oxmultilang ident="DD_VISUAL_EDITOR_NEW"}]
                                                                </label>
                                                            </div>
                                                        </div>

                                                        [{block name="visualcms_settings_form_cmssearch"}]

                                                            <div class="form-group row dd-cms-search">
                                                                <div class="col-sm-12">
                                                                    <label for="elm_edit_cms" class="control-label">[{oxmultilang ident="DD_VISUAL_EDITOR_CMS"}]</label>
                                                                </div>
                                                                <div class="col-sm-9" style="padding-right: 0;">
                                                                    <select name="content" class="form-control dd-cms-picker" placeholder="[{oxmultilang ident="DD_VISUAL_EDITOR_CHOOSE_CMS"}]" id="elm_edit_cms">
                                                                        <option></option>
                                                                    </select>
                                                                </div>
                                                                <div class="col-sm-3 text-right">
                                                                    <a href="javascript:void(null)" class="dd-veditor-treeview-action btn btn-primary btn-block" title="">
                                                                        <i class="fa fa-sitemap"></i>
                                                                    </a>
                                                                </div>
                                                            </div>

                                                        [{/block}]

                                                        <div class="form-group dd-cms-title">
                                                            <label for="elm_edit_title" class="control-label">[{oxmultilang ident="DD_VISUAL_EDITOR_TITLE"}]</label>
                                                            <input type="text" name="editval[oxcontents__oxtitle]" class="form-control" id="elm_edit_title" />
                                                        </div>

                                                        <div class="form-group">
                                                            <label for="elm_edit_ident" class="control-label">[{oxmultilang ident="DD_VISUAL_EDITOR_IDENT"}]</label>
                                                            <input type="text" name="editval[oxcontents__oxloadid]" class="form-control" id="elm_edit_ident" />
                                                            <small class="help-block">[{oxmultilang ident="DD_VISUAL_EDITOR_IDENT_INFO"}]</small>
                                                        </div>

                                                        <div class="form-group">
                                                            <label for="elm_edit_folder" class="control-label">[{oxmultilang ident="DD_VISUAL_EDITOR_FOLDER"}]</label>
                                                            <select name="editval[oxcontents__oxfolder]" class="form-control dd-form-picker" id="elm_edit_folder">

                                                                [{foreach from=$aFolder key="field" item="color"}]
                                                                    <option value="[{$field}]" style="color: [{$color}];" data-data='{"color":"[{$color}]"}'[{if $field|replace:"_RR":""=="CMSFOLDER_NONE"}] selected[{/if}]>[{oxmultilang ident=$field}]</option>
                                                                [{/foreach}]

                                                            </select>
                                                        </div>

                                                        <div class="dd-cms-type-infos">

                                                            <div class="form-group dd-cms-url" style="display: none;">
                                                                <label for="elm_edit_ident" class="control-label">[{oxmultilang ident="DD_VISUAL_EDITOR_URL"}]</label>
                                                                <p class="form-control-static">
                                                                    <code>-</code>
                                                                </p>
                                                            </div>

                                                            <div class="form-group dd-cms-snippet" style="display: none;">
                                                                <label for="elm_snippet" class="control-label">[{oxmultilang ident="DD_VISUAL_EDITOR_SNIPPET"}]</label>
                                                                <div class="input-group">
                                                                    <input type="text" name="snippet" class="form-control" id="elm_snippet" readonly="readonly" />
                                                                    <span class="input-group-btn">
                                                                        <button class="btn btn-default dd-clipboard-action" data-clipboard-target="#elm_snippet" type="button">
                                                                            <i class="fa fa-clipboard"></i>
                                                                        </button>
                                                                    </span>
                                                                </div>
                                                                <span class="form-control-feedback text-success">
                                                                    <i class="fa fa-check"></i> [{oxmultilang ident="DD_VISUAL_EDITOR_SNIPPET_COPIED"}]
                                                                </span>
                                                            </div>

                                                        </div>

                                                    </div>

                                                [{/block}]

                                            </div>

                                            <div role="tabpanel" class="tab-pane" id="tab_settings_advanced">

                                                [{block name="visualcms_settings_form_tabs_advanced"}]

                                                    <div class="form-group">
                                                        <label>[{oxmultilang ident="DD_VISUAL_EDITOR_ADVANCED_BEHAVIOR"}]</label>

                                                        <div class="checkbox">
                                                            <label>
                                                                <input type="hidden" name="editval[oxcontents__ddplaintext]" value="0" />
                                                                <input type="checkbox" name="editval[oxcontents__ddplaintext]" id="elm_edit_plaintext" value="1" /> [{oxmultilang ident="DD_VISUAL_EDITOR_USE_PLAIN_CONTENT"}] <i class="fa fa-info-circle" title="[{oxmultilang ident="DD_VISUAL_EDITOR_USE_PLAIN_CONTENT_INFO"}]" data-toggle="tooltip" data-container="body" data-placement="right"></i>
                                                            </label>
                                                        </div>

                                                    </div>

                                                    <div class="form-group">
                                                        <label>[{oxmultilang ident="DD_VISUAL_EDITOR_ADVANCED_TYPE"}]</label>

                                                        <div class="radio">
                                                            <label>
                                                                <input type="radio" name="editval[oxcontents__oxtype]" id="elm_edit_type_0" value="0" checked /> [{oxmultilang ident="CONTENT_MAIN_SNIPPET"}] <i class="fa fa-info-circle" title="[{"HELP_CONTENT_MAIN_SNIPPET"|oxmultilangassign|@strip_tags}]" data-toggle="tooltip" data-container="body" data-placement="right"></i>
                                                            </label>
                                                        </div>

                                                        <div class="radio">
                                                            <label>
                                                                <input type="radio" name="editval[oxcontents__oxtype]" id="elm_edit_type_2" value="2" /> [{oxmultilang ident="CONTENT_MAIN_CATEGORY"}] <i class="fa fa-info-circle" title="[{"HELP_CONTENT_MAIN_CATEGORY"|oxmultilangassign|@strip_tags}]" data-toggle="tooltip" data-container="body" data-placement="right"></i>
                                                            </label>
                                                        </div>

                                                        <div class="radio">
                                                            <label>
                                                                <input type="radio" name="editval[oxcontents__oxtype]" id="elm_edit_type_3" value="3" /> [{oxmultilang ident="CONTENT_MAIN_MANUAL"}] <i class="fa fa-info-circle" title="[{"HELP_CONTENT_MAIN_MANUAL"|oxmultilangassign|@strip_tags}]" data-toggle="tooltip" data-container="body" data-placement="right"></i>
                                                            </label>
                                                        </div>
                                                    </div>

                                                    <div class="form-group dd-cms-cat" style="display: none;">
                                                        <label for="elm_edit_catid" class="control-label">[{oxmultilang ident="DD_VISUAL_EDITOR_CATEGORY"}]</label>
                                                        <select name="editval[oxcontents__oxcatid]" class="form-control dd-form-picker" id="elm_edit_catid">

                                                            [{foreach from=$aCategories key="sCatId" item="sCatTitle"}]
                                                                <option value="[{$sCatId}]">[{$sCatTitle}]</option>
                                                            [{/foreach}]

                                                        </select>
                                                    </div>

                                                    <div class="form-group">
                                                        <label>[{oxmultilang ident="DD_VISUAL_EDITOR_ADVANCED_LAYOUT"}]</label>

                                                        <div class="checkbox">
                                                            <label>
                                                                <input type="hidden" name="editval[oxcontents__ddhidetitle]" value="0" />
                                                                <input type="checkbox" name="editval[oxcontents__ddhidetitle]" id="elm_edit_hide_title" value="1" /> [{oxmultilang ident="DD_VISUAL_EDITOR_HIDE_TITLE"}] <i class="fa fa-info-circle" title="[{oxmultilang ident="DD_VISUAL_EDITOR_HIDE_TITLE_INFO"}]" data-toggle="tooltip" data-container="body" data-placement="right"></i>
                                                            </label>
                                                        </div>

                                                        [{if !$oViewConf->isRoxiveTheme()}]
                                                            <div class="checkbox">
                                                                <label>
                                                                    <input type="hidden" name="editval[oxcontents__ddhidesidebar]" value="0" />
                                                                    <input type="checkbox" name="editval[oxcontents__ddhidesidebar]" id="elm_edit_hide_sidebar" value="1" /> [{oxmultilang ident="DD_VISUAL_EDITOR_HIDE_SIDEBAR"}] <i class="fa fa-info-circle" title="[{oxmultilang ident="DD_VISUAL_EDITOR_HIDE_SIDEBAR_INFO"}]" data-toggle="tooltip" data-container="body" data-placement="right"></i>
                                                                </label>
                                                            </div>
                                                        [{/if}]

                                                        <div class="checkbox">
                                                            <label>
                                                                <input type="hidden" name="editval[oxcontents__ddislanding]" value="0" />
                                                                <input type="checkbox" name="editval[oxcontents__ddislanding]" id="elm_edit_islanding" value="1" /> [{oxmultilang ident="DD_VISUAL_EDITOR_IS_LANDING"}] <i class="fa fa-info-circle" title="[{oxmultilang ident="DD_VISUAL_EDITOR_IS_LANDING_INFO"}]" data-toggle="tooltip" data-container="body" data-placement="right"></i>
                                                            </label>
                                                        </div>

                                                        [{if $oViewConf->isFlowTheme()}]
                                                            <div class="checkbox">
                                                                <label>
                                                                    <input type="hidden" name="editval[oxcontents__ddfullwidth]" value="0" />
                                                                    <input type="checkbox" name="editval[oxcontents__ddfullwidth]" id="elm_edit_fullwidth" value="1" /> [{oxmultilang ident="DD_VISUAL_EDITOR_FULLWIDTH"}] <i class="fa fa-info-circle" title="[{oxmultilang ident="DD_VISUAL_EDITOR_HIDE_TITLE_INFO"}]" data-toggle="tooltip" data-container="body" data-placement="right"></i>
                                                                </label>
                                                            </div>
                                                        [{/if}]

                                                    </div>

                                                    <div class="form-group">
                                                        <label for="elm_edit_cssclass" class="control-label">[{oxmultilang ident="DD_VISUAL_EDITOR_GLOBAL_CSS_CLASS"}]</label>
                                                        <input type="text" name="editval[oxcontents__ddcssclass]" class="form-control" id="elm_edit_cssclass" />
                                                    </div>

                                                [{/block}]

                                            </div>

                                            <div role="tabpanel" class="tab-pane" id="tab_settings_seo">

                                                [{block name="visualcms_settings_form_tabs_seo"}]

                                                    <div class="form-group">
                                                        <div class="checkbox">
                                                            <label>
                                                                <input type="hidden" name="aSeoData[oxfixed]" value="0" />
                                                                <input type="checkbox" name="aSeoData[oxfixed]" id="elm_seo_fixed" value="1" /> [{oxmultilang ident="GENERAL_SEO_FIXED"}] <i class="fa fa-info-circle" title="[{"HELP_GENERAL_SEO_FIXED"|oxmultilangassign|@strip_tags}]" data-toggle="tooltip" data-container="body" data-placement="right"></i>
                                                            </label>
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="elm_seo_url" class="control-label">[{oxmultilang ident="GENERAL_SEO_URL"}]</label>
                                                        <input type="text" name="aSeoData[oxseourl]" class="form-control" id="elm_seo_url" />
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="elm_seo_keywords" class="control-label">[{oxmultilang ident="GENERAL_SEO_OXKEYWORDS"}] <i class="fa fa-info-circle" title="[{"HELP_GENERAL_SEO_OXKEYWORDS"|oxmultilangassign|@strip_tags}]" data-toggle="tooltip" data-container="body" data-placement="right"></i></label>
                                                        <textarea name="aSeoData[oxkeywords]" class="form-control" id="elm_seo_keywords" style="height: 100px;"></textarea>
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="elm_seo_description" class="control-label">[{oxmultilang ident="GENERAL_SEO_OXDESCRIPTION"}] <i class="fa fa-info-circle" title="[{"HELP_GENERAL_SEO_OXDESCRIPTION"|oxmultilangassign|@strip_tags}]" data-toggle="tooltip" data-container="body" data-placement="right"></i></label>
                                                        <textarea name="aSeoData[oxdescription]" class="form-control" id="elm_seo_description" style="height: 100px;"></textarea>
                                                    </div>

                                                [{/block}]

                                            </div>

                                        [{/block}]

                                    </div>

                                </div>

                            </div>

                            <div class="clearfix"></div>

                            <div class="dd-veditor-form-actions">

                                [{block name="visualcms_settings_form_actions"}]

                                    <div class="form-group">
                                        <ul class="dd-veditor-form-action-buttons">
                                            [{block name="visualcms_settings_form_actions_buttons"}]
                                                [{if !$demo}]
                                                    <li>
                                                        <button type="submit" onclick="document.myedit.fnc.value='save'" class="btn btn-success"><i class="fa fa-save" style="margin-right: 5px;"></i> [{oxmultilang ident="DD_VISUAL_EDITOR_SAVE"}]</button>
                                                    </li>
                                                [{/if}]
                                                <li>
                                                    <button type="submit" onclick="document.myedit.fnc.value='savePreview'" class="btn btn-info" data-toggle="tooltip" title="[{oxmultilang ident="DD_VISUAL_EDITOR_PREVIEW_POPUP_INFO"}]"><i class="fa fa-external-link" style="margin-right: 5px;"></i> [{oxmultilang ident="DD_VISUAL_EDITOR_PREVIEW"}]</button>
                                                </li>
                                                [{if !$demo}]
                                                    <li>
                                                        <button type="submit" onclick="document.myedit.fnc.value='delete'" class="btn btn-danger dd-delete-action" disabled><i class="fa fa-trash" style="margin-right: 5px;"></i> [{oxmultilang ident="DD_VISUAL_EDITOR_DELETE"}]</button>
                                                    </li>
                                                [{/if}]
                                            [{/block}]
                                        </ul>
                                        <div class="clearfix"></div>

                                        [{if $demo}]
                                            <div style="padding: 5px 5px 0;">
                                                <small class="text-muted">Im Demomodus ist das Speichern deaktiviert.</small>
                                            </div>
                                        [{/if}]

                                        <input type="hidden" name="selectedlanguage" value="[{$sActiveLang}]" />
                                    </div>

                                [{/block}]

                            </div>

                        </form>

                    [{/block}]

                </div>

            </div>

            <div class="col-md-9 col-sm-8 col-xs-12" style="height: 100%;">

                <div id="widgets" class="dd-veditor">

                    [{block name="visualcms_editor"}]

                        <div class="dd-ajax-loader dd-content-loader" style="display: none;">
                            <img src="[{$oViewConf->getModuleUrl('ddoevisualcms','out/src/img/loading.svg')}]" />
                        </div>

                        <div class="dd-veditor-actions">

                            [{block name="visualcms_editor_actions"}]

                                <div class="pull-left">
                                    <ul class="nav nav-pills">
                                        <li>
                                            <a href="javascript:void(null)" class="dd-veditor-widget-action" title="[{oxmultilang ident="DD_VISUAL_EDITOR_ADD_WIDGET"}]" data-toggle="tooltip" data-placement="bottom" data-container="body" data-delay='{"show":500,"hide":50}'>
                                                <i class="fa fa-plus"></i>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="javascript:void(null)" class="dd-veditor-media-action" title="[{oxmultilang ident="DD_VISUAL_EDITOR_ADD_IMAGE"}]" data-toggle="tooltip" data-placement="bottom" data-container="body" data-delay='{"show":500,"hide":50}'>
                                                <i class="fa fa-file-image-o"></i>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="javascript:void(null)" class="dd-veditor-column-action" title="[{oxmultilang ident="DD_VISUAL_EDITOR_ADD_COLUMN"}]" data-toggle="tooltip" data-placement="bottom" data-container="body" data-delay='{"show":500,"hide":50}'>
                                                <i class="fa fa-columns"></i>
                                            </a>
                                        </li>
                                    </ul>
                                </div>

                                <div class="pull-right">
                                    <ul class="nav nav-right nav-pills">
                                        <li>
                                            <a href="javascript:void(null)" class="dd-veditor-css-action" title="[{oxmultilang ident="DD_VISUAL_EDITOR_CUSTOM_CSS"}]" data-toggle="tooltip" data-placement="bottom" data-container="body" data-delay='{"show":500,"hide":50}'>
                                                <i class="fa fa-magic"></i>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="javascript:void(null)" class="dd-veditor-template-action" title="[{oxmultilang ident="DD_VISUAL_EDITOR_TEMPLATES"}]" data-toggle="tooltip" data-placement="bottom" data-container="body" data-delay='{"show":500,"hide":50}'>
                                                <i class="fa fa-bookmark"></i>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="javascript:void(null)" class="dd-veditor-source-action" title="[{oxmultilang ident="DD_VISUAL_EDITOR_SHOW_SOURCE"}]" data-toggle="tooltip" data-placement="bottom" data-container="body" data-delay='{"show":500,"hide":50}'>
                                                <i class="fa fa-code"></i>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="javascript:void(null)" class="dd-veditor-delete-all-action text-danger" title="[{oxmultilang ident="DD_VISUAL_EDITOR_DELETE_ALL_WIDGETS"}]" data-toggle="tooltip" data-placement="bottom" data-container="body" data-delay='{"show":500,"hide":50}'>
                                                <i class="fa fa-trash"></i>
                                            </a>
                                        </li>
                                    </ul>
                                </div>

                            [{/block}]

                        </div>

                        <div class="dd-veditor-wrapper">

                            [{block name="visualcms_editor_wrapper"}]

                                <div class="dd-veditor-grid grid-stack grid-stack-[{$oConf->getConfigParam('iGridsterSize')|default:'6'}] grid">

                                    [{block name="visualcms_editor_inner"}]

                                        [{*<div class="grid-stack-item dd-veditor-widget dd-widget-type-col" data-widget="col" data-gs-x="0" data-gs-y="2" data-gs-width="6" data-gs-height="1">
                                            <div class="grid-stack-item-content dd-widget-inner">
                                                <div class="grid-stack grid-stack-[{$oConf->getConfigParam('iGridsterSize')|default:'6'}] grid"></div>
                                            </div>
                                        </div>*}]

                                    [{/block}]

                                </div>

                                <div class="dd-veditor-source" style="display: none;">

                                    [{block name="visualcms_editor_sourcecode"}]

                                        <textarea name="source" class="form-control"></textarea>

                                    [{/block}]

                                </div>

                            [{/block}]

                        </div>

                    [{/block}]

                </div>

            </div>

        </div>

    </div>

    <script type="text/javascript">
        [{block name="visualcms_script_settings"}]
            window.shortcodes = {};
            window.debug = [{$blDebugMode}];
            window.options = { gridSize: [{$oConf->getConfigParam('iGridsterSize')|default:'6'}], gridFrontendSize: [{$oConf->getConfigParam('iGridSize')|default:'12'}], defaultWidgetSize: [{$oConf->getConfigParam('iDefaultWidgetSize')|default:'1'}] };
            window.cssclasses = [{if $oConf->getConfigParam('aPredefinedCssClasses')}][{$oConf->getConfigParam('aPredefinedCssClasses')|@json_encode}][{else}][][{/if}];
            window.preloadid = [{if $sPreloadContentId}]'[{$sPreloadContentId}]'[{else}]null[{/if}];
        [{/block}]
    </script>

    [{foreach from=$aShortCodeData item="sScript"}]
        [{$sScript}]
    [{/foreach}]

[{/capture}]

[{capture append="modal"}]

    <div class="modal fade dd-widget-modal" tabindex="-1" role="dialog" aria-labelledby="widgetModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form method="post" role="form" class="form-horizontal dd-widget-form">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="widgetModalLabel">
                            <img src="[{$oViewConf->getModuleUrl('ddoevisualcms','out/src/img/module_icon_light.svg')}]" /> [{oxmultilang ident="DD_VISUAL_EDITOR_WIDGET"}]
                        </h4>
                    </div>
                    <div class="modal-body">

                        <div class="dd-widget-form-select" style="display: block;">

                            <div class="row">

                                [{block name="visualcms_widget_iconselect"}]

                                    [{foreach from=$aShortCodeSelect item="sSelect"}]
                                        <div class="col-sm-2">
                                            [{$sSelect}]
                                        </div>
                                    [{/foreach}]

                                [{/block}]

                            </div>

                        </div>

                        <div class="dd-widget-form-fields" style="display: none;">


                            <div class="dd-tab-form">

                                <ul class="nav nav-tabs" role="tablist">
                                    [{block name="visualcms_widget_tabs"}]
                                        <li role="presentation" class="active"><a href="#tab_widget_main" role="tab" data-toggle="tab">[{oxmultilang ident="DD_VISUAL_EDITOR_WIDGET_MAIN_SETTINGS"}]</a></li>
                                        <li role="presentation"><a href="#tab_widget_design" role="tab" data-toggle="tab">[{oxmultilang ident="DD_VISUAL_EDITOR_WIDGET_DESIGN_SETTINGS"}]</a></li>
                                    [{/block}]
                                </ul>

                                <div class="tab-content">

                                    [{block name="visualcms_widget_tabs_content"}]

                                        <div role="tabpanel" class="tab-pane active" id="tab_widget_main">

                                            [{block name="visualcms_widget_tabs_main"}]

                                                <div class="form-group dd-widget-type-group">
                                                    <label for="elm_widget_type" class="col-sm-2 control-label">[{oxmultilang ident="DD_VISUAL_EDITOR_WIDGET_TYPE"}]</label>
                                                    <div class="col-sm-10">
                                                        <select name="type" class="form-control dd-picker dd-area-select dd-widget-type-select" data-area-group-value="dd-widget-type" data-style="btn-inverse" id="elm_widget_type">

                                                            [{block name="visualcms_widget_select"}]
                                                                [{foreach from=$aShortCodeTypes item="sType"}][{$sType}][{/foreach}]
                                                            [{/block}]

                                                        </select>
                                                    </div>
                                                </div>

                                                [{block name="visualcms_widget_fields"}]

                                                    [{foreach from=$aShortCodeFields item="sField"}]
                                                        [{$sField}]
                                                    [{/foreach}]

                                                [{/block}]

                                            [{/block}]

                                        </div>
                                        <div role="tabpanel" class="tab-pane" id="tab_widget_design">

                                            [{block name="visualcms_widget_tabs_design"}]

                                                <div class="form-group">
                                                    [{*<div class="col-sm-12">
                                                        <label for="elm_widget_class">[{oxmultilang ident="DD_VISUAL_EDITOR_WIDGET_CLASS"}]</label>
                                                    </div>*}]
                                                    <div class="col-sm-12">
                                                        <input type="text" name="class" class="form-control dd-widget-class" id="elm_widget_class" placeholder="[{oxmultilang ident="DD_VISUAL_EDITOR_WIDGET_CHOOSE_CLASS"}]" />
                                                    </div>
                                                </div>

                                                [{if !$oConf->getConfigParam('blCustomGridFramework')}]

                                                    <div class="clearfix" style="height: 10px;"></div>

                                                    <strong>[{oxmultilang ident="DD_VISUAL_EDITOR_WIDGET_RESPONSIVE_SETTINGS"}]</strong>

                                                    [{assign var="iGridSize" value=$oConf->getConfigParam('iGridSize')|default:12}]

                                                    [{capture assign="sColumnOptions"}]
                                                        [{section name="columns" start=1 loop=$iGridSize+1 step=1}]
                                                            [{assign var="col" value=$smarty.section.columns.index}]
                                                            <option value="[{$col}]">[{$col}] [{if $col > 1}][{oxmultilang ident="DD_VISUAL_EDITOR_RESPONSIVE_COLUMNS"}][{else}][{oxmultilang ident="DD_VISUAL_EDITOR_RESPONSIVE_COLUMN"}][{/if}]</option>
                                                        [{/section}]
                                                    [{/capture}]

                                                    <table class="table table-bordered table-striped dd-widget-responsive-table">

                                                        <thead>

                                                        <th style="width: 80px;">[{oxmultilang ident="DD_VISUAL_EDITOR_DEVICE"}]</th>
                                                        <th style="width: 300px;">[{oxmultilang ident="DD_VISUAL_EDITOR_COLUMN_OFFSET"}]</th>
                                                        <th style="width: 300px;">[{oxmultilang ident="DD_VISUAL_EDITOR_COLUMN_WIDTH"}]</th>
                                                        <th>[{oxmultilang ident="DD_VISUAL_EDITOR_HIDE_DEVICE"}]</th>

                                                        </thead>
                                                        <tbody>

                                                        [{* Smartphone *}]
                                                        <tr>
                                                            <td class="text-center">
                                                                <i class="fa fa-mobile fa-2x" title="[{oxmultilang ident="DD_VISUAL_EDITOR_DEVICE_SMARTPHONE"}]" data-toggle="tooltip"></i>
                                                            </td>
                                                            <td>
                                                                <select name="col_offset[xs]" class="form-control dd-picker" id="elm_widget_col_offset_xs" data-default-value="none">

                                                                    <option value="none">[{oxmultilang ident="DD_VISUAL_EDITOR_NO_COLUMN_OFFSET"}]</option>

                                                                    [{$sColumnOptions}]

                                                                </select>
                                                            </td>
                                                            <td>
                                                                <select name="col_size[xs]" class="form-control dd-picker" id="elm_widget_col_size_xs" data-default-value="12">

                                                                    [{$sColumnOptions}]

                                                                </select>
                                                            </td>
                                                            <td class="text-center">
                                                                <input type="checkbox" value="1" name="hide_device[xs]" id="elm_widget_hide_device_xs" />
                                                            </td>
                                                        </tr>

                                                        [{* Tablet / Default *}]
                                                        <tr>
                                                            <td class="text-center">
                                                                <i class="fa fa-tablet fa-2x" title="[{oxmultilang ident="DD_VISUAL_EDITOR_DEVICE_TABLET_PORTRAIT"}]" data-toggle="tooltip"></i>
                                                            </td>
                                                            <td>
                                                                <span class="text-muted">[{oxmultilang ident="DD_VISUAL_EDITOR_COLUMN_DEFAULT"}]</span>
                                                            </td>
                                                            <td>
                                                                <span class="text-muted">[{oxmultilang ident="DD_VISUAL_EDITOR_COLUMN_DEFAULT"}]</span>
                                                            </td>
                                                            <td class="text-center">
                                                                <input type="checkbox" value="1" name="hide_device[sm]" id="elm_widget_hide_device_sm" />
                                                            </td>
                                                        </tr>

                                                        [{* Notebook *}]
                                                        <tr>
                                                            <td class="text-center">
                                                                <i class="fa fa-laptop fa-2x" title="[{oxmultilang ident="DD_VISUAL_EDITOR_DEVICE_TABLET_LANDSCAPE"}]" data-toggle="tooltip"></i>
                                                            </td>
                                                            <td>
                                                                <select name="col_offset[md]" class="form-control dd-picker" id="elm_widget_col_offset_md">

                                                                    <option value="">[{oxmultilang ident="DD_VISUAL_EDITOR_COLUMN_INHERIT"}]</option>
                                                                    <option value="none">[{oxmultilang ident="DD_VISUAL_EDITOR_NO_COLUMN_OFFSET"}]</option>

                                                                    [{$sColumnOptions}]

                                                                </select>
                                                            </td>
                                                            <td>
                                                                <select name="col_size[md]" class="form-control dd-picker" id="elm_widget_col_size_md">

                                                                    <option value="">[{oxmultilang ident="DD_VISUAL_EDITOR_COLUMN_INHERIT"}]</option>

                                                                    [{$sColumnOptions}]

                                                                </select>
                                                            </td>
                                                            <td class="text-center">
                                                                <input type="checkbox" value="1" name="hide_device[md]" id="elm_widget_hide_device_md" />
                                                            </td>
                                                        </tr>

                                                        [{* Desktop *}]
                                                        <tr>
                                                            <td class="text-center">
                                                                <i class="fa fa-desktop fa-2x" title="[{oxmultilang ident="DD_VISUAL_EDITOR_DEVICE_DESKTOP"}]" data-toggle="tooltip"></i>
                                                            </td>
                                                            <td>
                                                                <select name="col_offset[lg]" class="form-control dd-picker" id="elm_widget_col_offset_lg">

                                                                    <option value="">[{oxmultilang ident="DD_VISUAL_EDITOR_COLUMN_INHERIT"}]</option>
                                                                    <option value="none">[{oxmultilang ident="DD_VISUAL_EDITOR_NO_COLUMN_OFFSET"}]</option>

                                                                    [{$sColumnOptions}]

                                                                </select>
                                                            </td>
                                                            <td>
                                                                <select name="col_size[lg]" class="form-control dd-picker" id="elm_widget_col_size_lg">

                                                                    <option value="">[{oxmultilang ident="DD_VISUAL_EDITOR_COLUMN_INHERIT"}]</option>

                                                                    [{$sColumnOptions}]

                                                                </select>
                                                            </td>
                                                            <td class="text-center">
                                                                <input type="checkbox" value="1" name="hide_device[lg]" id="elm_widget_hide_device_lg" />
                                                            </td>
                                                        </tr>

                                                        </tbody>
                                                    </table>

                                                [{/if}]

                                            [{/block}]

                                        </div>

                                    [{/block}]

                                </div>

                            </div>

                        </div>

                    </div>
                    <div class="modal-footer">
                        [{block name="visualcms_widget_actions"}]
                            <button type="button" class="btn btn-link" data-dismiss="modal">[{oxmultilang ident="DD_VISUAL_EDITOR_ADD_WIDGET_CANCEL"}]</button>
                            <button type="submit" class="btn btn-primary">[{oxmultilang ident="DD_VISUAL_EDITOR_ADD_WIDGET_SAVE"}]</button>
                        [{/block}]
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade dd-css-modal" tabindex="-1" role="dialog" aria-labelledby="cssModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form method="post" role="form" class="form-horizontal dd-css-form">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="cssModalLabel">[{oxmultilang ident="DD_VISUAL_EDITOR_CUSTOM_CSS"}]</h4>
                    </div>
                    <div class="modal-body">

                        [{block name="visualcms_less_editor"}]
                            <div class="dd-less-editor"></div>
                        [{/block}]

                    </div>
                    <div class="modal-footer">
                        [{block name="visualcms_less_editor_actions"}]
                            <button type="button" class="btn btn-link" data-dismiss="modal">[{oxmultilang ident="DD_VISUAL_EDITOR_CANCEL"}]</button>
                            <button type="submit" class="btn btn-primary">[{oxmultilang ident="DD_VISUAL_EDITOR_APPLY"}]</button>
                        [{/block}]
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade dd-templates-modal" tabindex="-1" role="dialog" aria-labelledby="templatesModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="templatesModalLabel">[{oxmultilang ident="DD_VISUAL_EDITOR_TEMPLATES"}]</h4>
                </div>
                <div class="modal-body">

                    <div class="dd-ajax-loader dd-template-loader" style="display: none;">
                        <img src="[{$oViewConf->getModuleUrl('ddoevisualcms','out/src/img/loading.svg')}]" />
                    </div>

                    <form method="post" role="form" class="dd-template-form">

                        [{block name="visualcms_templates_form"}]

                            [{$oViewConf->getHiddenSid()}]
                            <input type="hidden" name="cl" value="ddoevisualcmsadmin">
                            <input type="hidden" name="fnc" value="save">
                            <input type="hidden" name="source" value="">
                            <input type="hidden" name="oxid" value="">
                            <input type="hidden" name="new" value="1" />
                            <input type="hidden" name="editval[oxcontents__oxactive]" value="0" />
                            <input type="hidden" name="editval[oxcontents__ddistmpl]" value="1" />

                            <div class="form-group">
                                <label class="control-label">[{oxmultilang ident="DD_VISUAL_EDITOR_SAVE_CURRENT_TEMPLATE"}]</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" name="editval[oxcontents__oxtitle]" />
                                    <span class="input-group-btn">
                                        <button class="btn btn-primary dd-template-action-save" type="submit">[{oxmultilang ident="DD_VISUAL_EDITOR_ADD_WIDGET_SAVE"}]</button>
                                    </span>
                                </div>
                            </div>

                        [{/block}]

                    </form>

                    <div class="clearfix" style="height: 10px;"></div>

                    <div style="margin-bottom: 5px;">
                        <strong>[{oxmultilang ident="DD_VISUAL_EDITOR_SAVED_TEMPLATES"}]</strong>
                    </div>

                    <ul class="list-group dd-veditor-templates">

                        [{block name="visualcms_templates_list"}]

                            [{if $templates}]

                                [{foreach from=$templates item="aTmpl"}]

                                    <li class="list-group-item" data-oxid="[{$aTmpl.OXID}]">
                                        <div class="clearfix">
                                            <div class="pull-left">
                                                <span>[{$aTmpl.OXTITLE}]</span>
                                                [{if $aTmpl.DDTMPLTARGETID && $aTmpl.DDTMPLTARGETDATE != '0000-00-00 00:00:00'}]
                                                    [{if $aTmpl.DDTMPLTARGETTITLE}]
                                                        [{assign var="sTargetTitle" value=$aTmpl.DDTMPLTARGETTITLE}]
                                                    [{else}]
                                                        [{assign var="sTargetTitle" value=$aTmpl.DDTMPLTARGETIDENT}]
                                                    [{/if}]

                                                    [{assign var="sTargetDate" value=$aTmpl.DDTMPLTARGETDATE|substr:0:10}]
                                                    [{assign var="sTargetClock" value=$aTmpl.DDTMPLTARGETDATE|substr:11:5}]

                                                    [{assign var="sTargetInfoText" value="DD_VISUAL_EDITOR_TEMPLATE_TIMER_ACTIVE_INFO"|oxmultilangassign|sprintf:$sTargetTitle:$sTargetDate:$sTargetClock}]

                                                    <br><small class="text-muted dd-target-active-info"><i class="fa fa-check"></i> [{$sTargetInfoText}]</small>
                                                [{/if}]
                                            </div>
                                            <div class="pull-right">
                                                <button type="button" class="btn btn-xs btn-link dd-template-action-apply">
                                                    <i class="fa fa-plus"></i>
                                                </button>
                                                <button type="button" class="btn btn-xs btn-link dd-template-action-timer">
                                                    <i class="fa fa-clock-o"></i>
                                                </button>
                                                <button type="button" class="btn btn-xs btn-link dd-template-action-preview">
                                                    <i class="fa fa-external-link"></i>
                                                </button>
                                                <button type="button" class="btn btn-xs btn-link dd-template-action-delete">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </li>

                                [{/foreach}]

                            [{else}]
                                <li class="list-group-item no-data">
                                    <span class="text-center" style="font-style: italic;">[{oxmultilang ident="DD_VISUAL_EDITOR_NO_TEMPLATES_FOUND"}]</span>
                                </li>
                            [{/if}]

                        [{/block}]

                    </ul>


                </div>
                <div class="modal-footer">
                    [{block name="visualcms_templates_actions"}]
                        <button type="button" class="btn btn-link" data-dismiss="modal">[{oxmultilang ident="DD_VISUAL_EDITOR_CANCEL"}]</button>
                    [{/block}]
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade dd-template-timer-modal" tabindex="-1" role="dialog" aria-labelledby="timerModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="post" role="form" class="dd-template-timer-form">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="timerModalLabel">[{oxmultilang ident="DD_VISUAL_EDITOR_TEMPLATE_TIMER"}]</h4>
                    </div>
                    <div class="modal-body">

                        <div class="dd-ajax-loader dd-template-timer-loader" style="display: none;">
                            <img src="[{$oViewConf->getModuleUrl('ddoevisualcms','out/src/img/loading.svg')}]" />
                        </div>

                        [{block name="visualcms_timer_form"}]

                            [{$oViewConf->getHiddenSid()}]
                            <input type="hidden" name="cl" value="ddoevisualcmsadmin">
                            <input type="hidden" name="fnc" value="saveTemplateTimer">
                            <input type="hidden" name="oxid" id="elm_tmpl_timer_oxid" value="">

                            <div class="alert alert-info">
                                [{oxmultilang ident="DD_VISUAL_EDITOR_TEMPLATE_TIMER_INFO"}]
                            </div>

                            <div class="form-group">
                                <label class="control-label">[{oxmultilang ident="DD_VISUAL_EDITOR_TEMPLATE"}]</label>
                                <p class="form-control-static" id="elm_tmpl_timer_name">-</p>
                            </div>

                            <div class="form-group">
                                <label for="elm_tmpl_timer_cms" class="control-label">[{oxmultilang ident="DD_VISUAL_EDITOR_CMS"}]</label>
                                <select name="timer[oxcontents__ddtmpltargetid]" class="form-control dd-cms-picker" placeholder="[{oxmultilang ident="DD_VISUAL_EDITOR_CHOOSE_CMS"}]" id="elm_tmpl_timer_cms">
                                    <option></option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="elm_tmpl_timer_date" class="control-label">[{oxmultilang ident="DD_VISUAL_EDITOR_TEMPLATE_TIMER_DATE"}]</label>
                                <input name="timer[oxcontents__ddtmpltargetdate]" class="form-control" placeholder="YYYY-MM-DD HH:MM:SS" id="elm_tmpl_timer_date" />
                            </div>

                        [{/block}]

                    </div>
                    <div class="modal-footer">
                        [{block name="visualcms_timer_actions"}]
                            <button type="button" class="btn btn-link" data-dismiss="modal">[{oxmultilang ident="DD_VISUAL_EDITOR_CANCEL"}]</button>
                            <button type="submit" class="btn btn-primary">[{oxmultilang ident="DD_VISUAL_EDITOR_SAVE"}]</button>
                        [{/block}]
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade dd-treeview-modal" tabindex="-1" role="dialog" aria-labelledby="treeviewModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form method="post" role="form" class="form-horizontal dd-treeview-form">
                    <input type="hidden" name="elm_edit_treeview_contentid" id="elm_edit_treeview_contentid" value="" />
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="treeviewModalLabel">[{oxmultilang ident="DD_VISUAL_EDITOR_TREEVIEW"}]</h4>
                    </div>
                    <div class="modal-body">

                        <div class="dd-treeview-form-fields" style="display: block;">

                            <div class="dd-tab-form">

                                <ul class="nav nav-tabs" role="tablist">
                                    [{block name="visualcms_treeview_tabs"}]
                                        <li role="presentation" class="active"><a href="#tab_treeview_main" id="btn_tab_treeview_main" role="tab" data-toggle="tab">[{oxmultilang ident="DD_VISUAL_EDITOR_TREEVIEW_MAIN_TAB"}]</a></li>
                                        <li role="presentation"><a href="#tab_treeview_snippets" id="btn_tab_treeview_snippets" role="tab" data-toggle="tab">[{oxmultilang ident="DD_VISUAL_EDITOR_TREEVIEW_SNIPPETS_TAB"}]</a></li>
                                    [{/block}]
                                </ul>

                                <div class="tab-content">

                                    [{block name="visualcms_treeview_tabs_content"}]

                                        <div role="tabpanel" class="tab-pane active" id="tab_treeview_main">

                                            [{block name="visualcms_treeview_tabs_main"}]

                                                [{block name="visualcms_treeview_tabs_main_search"}]
                                                    <div class="row">
                                                        <div class="col-sm-4 pull-right dd-treeview-search-wrapper">
                                                            <input type="text" class="form-control input-sm dd-treeview-search-main" placeholder="[{oxmultilang ident="DD_VISUAL_EDITOR_CHOOSE_CMS"}]" />
                                                            <a href="javascript:void(null);" id="btn_clear_treeview_search_main" class="pull-right"><i class="fa fa-times-circle" ></i></a>
                                                        </div>
                                                    </div>

                                                [{/block}]


                                                <div class="panel treeview-panel">
                                                    <div class="panel-body">
                                                        <div class="dd-ajax-loader dd-treeview-main-loader">
                                                            <img src="[{$oViewConf->getModuleUrl('ddoevisualcms','out/src/img/loading.svg')}]" />
                                                        </div>
                                                        <div id="dd-treeview-main-inner"></div>
                                                        <div class="col-xs-12 dd-treeview-main-no-results">[{oxmultilang ident="DD_VISUAL_EDITOR_TREEVIEW_NO_RESULTS"}]</div>
                                                    </div>
                                                </div>

                                            [{/block}]

                                        </div>

                                        <div role="tabpanel" class="tab-pane" id="tab_treeview_snippets">

                                            [{block name="visualcms_treeview_tabs_snippets"}]

                                                [{block name="visualcms_treeview_tabs_snippets_search"}]
                                                    <div class="row">
                                                        <div class="col-sm-4 pull-right dd-treeview-search-wrapper">
                                                            <input type="text" class="form-control input-sm dd-treeview-search-snippets" placeholder="[{oxmultilang ident="DD_VISUAL_EDITOR_CHOOSE_CMS"}]" />
                                                            <a href="javascript:void(null);" id="btn_clear_treeview_search_snippets" class="pull-right"><i class="fa fa-times-circle" ></i></a>
                                                        </div>
                                                    </div>
                                                [{/block}]

                                                <div class="panel snippets-panel">
                                                    <div class="panel-body">
                                                        <div class="dd-ajax-loader dd-treeview-snippets-loader">
                                                            <img src="[{$oViewConf->getModuleUrl('ddoevisualcms','out/src/img/loading.svg')}]" />
                                                        </div>
                                                        <div id="dd-treeview-snippets-inner"></div>
                                                        <div class="col-xs-12 dd-treeview-snippets-no-results">[{oxmultilang ident="DD_VISUAL_EDITOR_TREEVIEW_NO_RESULTS"}]</div>
                                                    </div>
                                                </div>

                                            [{/block}]

                                        </div>

                                    [{/block}]

                                </div>

                            </div>

                        </div>

                    </div>
                    <div class="modal-footer">
                        [{block name="visualcms_treeview_actions"}]
                            <button type="button" class="btn btn-link" data-dismiss="modal">[{oxmultilang ident="DD_VISUAL_EDITOR_CANCEL"}]</button>
                            <button type="submit" class="btn btn-primary">[{oxmultilang ident="DD_VISUAL_EDITOR_TREEVIEW_LOAD_CONTENT"}]</button>
                        [{/block}]
                    </div>
                </form>
            </div>
        </div>
    </div>

[{/capture}]

[{capture append="header"}]

    <form class="navbar-form navbar-right hidden-xs">

        [{block name="visualcms_header_navbar_actions"}]

            <div class="form-group">
                [{if $blHasDemoPage}]
                    <button type="button" class="btn btn-primary dd-demodata-action hasDemodata">
                        [{oxmultilang ident="DD_VISUAL_EDITOR_LOAD_DEMODATA"}]
                    </button>
                [{else}]
                    <button type="button" class="btn btn-primary dd-demodata-action">
                        [{oxmultilang ident="DD_VISUAL_EDITOR_DEMODATA_INSTALL"}]
                    </button>
                [{/if}]
            </div>

            <div class="form-group">
                <select name="lang" class="form-control" id="elm_lang">
                    [{foreach from=$lang item="sLangName" key="sId"}]
                        <option value="[{$sId}]"[{if $sId == $sActiveLang}] selected[{/if}]>[{$sLangName}]</option>
                    [{/foreach}]
                </select>
            </div>

            <div class="form-group">
                <a href="https://docs.oxid-esales.com/modules/vcms/[{$oView->getVisualCmsHelpVersion()}]" class="btn btn-default" target="_blank">
                    <i class="fa fa-question-circle"></i> [{oxmultilang ident="DD_VISUAL_EDITOR_HELP"}]
                </a>
            </div>

        [{/block}]

    </form>

[{/capture}]

[{oxscript include=$oViewConf->getModuleUrl('ddoevisualcms','out/src/js/ace.min.js') priority=10}]
[{oxscript include=$oViewConf->getModuleUrl('ddoevisualcms','out/src/js/jstree/jstree.min.js') priority=11}]
[{oxscript include=$oViewConf->getModuleUrl('ddoevisualcms','out/src/js/backend.min.js') priority=10}]

[{foreach from=$aCustomScripts item="sScript"}]
    [{oxscript add=$sScript priority=10}]
[{/foreach}]

[{include file="ddoevisualcmsadmin_ui.tpl" title="DD_VISUAL_EDITOR"|oxmultilangassign icon=$oViewConf->getModuleUrl('ddoevisualcms','out/src/img/module_icon_light.svg')}]
