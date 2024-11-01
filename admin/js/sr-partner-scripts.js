var pp_js = jQuery.noConflict();
pp_js( document ).ready(function() {
    var Page = {
        init : function() {
            Page.check_hash();
            Page.preview_init();
            Page.setup_audit_form_button();

            var selected = pp_js('#sr_partner_type').val();
            Page.sr_partner_type_change(selected);

            //on input
            pp_js('.sr-partner-oninput-check-length').on('input', function(e) {
                var val     = pp_js(this).val();
                var len     = val.length;
                var max_len = pp_js(this).prop('maxlength');

                if (len > max_len) {
                    var slice  = val.slice(0,max_len);
                    pp_js(this).val(slice);
                }

            });

            //on input
            pp_js('.sr-partner-oninput-pass-value').on('input', function(e) {
                var passto = pp_js(this).data('passto');
                pp_js('#'+passto).val(pp_js(this).val());
            });


            //alert ok
            pp_js('.delete-alert-continue').on('click', function(e) {
                pp_js('#sr-partner-alert-wrapper').hide().remove();
            }); 

             //global notices
            if (pp_js('.global-notices').length > 0) {                

                var base = 3000;
                pp_js('.dops-notice-close').each(function() {

                    var t = pp_js(this);
                    setTimeout(function(){
                        t.fadeOut('slow', 'linear', function(){
                          t.remove();
                        })
                    },base);
                    base +=500;
                });
                
            } 

            //alert continue 
            pp_js('.alert-continue').on('click', function() {
                pp_js('#sr-partner-alert-wrapper').hide();
            });
            //tabs
            pp_js('.dops-section-nav-tab').on('click', function(e) {
                


                if(typeof(pp_js(this).attr('disabled')) === 'undefined' || pp_js(this).attr('disabled')== "") {
                 window.location.hash = pp_js(this).data('hash');
                 Page.check_hash();
                } else {
                    window.location.hash = '#plugin-features';
                    e.stopPropagation();
                    e.preventDefault();
                }
            });

            //toggle switch
            pp_js('.sr-partner-form-toggle__switch').on('click', function() {
                var id = pp_js(this).data('id');
                var target = pp_js(this).data('target');
                var attr = pp_js(id).attr('checked');
                setTimeout(function() {
                    if (typeof attr ===  'undefined') {
                        pp_js(id).attr('checked','checked').val(1);

                        if (target!='') {
                            if(target == 'audit-page') {
                                pp_js('.btn-install-on, .goto-audit-builder, #setup-audit-widget-nav-link, .dops-section-nav-tab[data-hash="setup-audit-widget"]').prop('disabled',false);
                                pp_js('#setup-audit-form-type').prop('disabled',false).trigger("change");
                                pp_js('#setup-audit-widget-nav-link').removeAttr('disabled');                            
                            } else {
                                pp_js('#'+target).prop('disabled',false);

                                if(target =='dashboard-page') {
                                    pp_js('#'+target).val('Dashboard'); 
                                    var url = window.location.protocol + "//" + window.location.host + "/dashboard";
                                    pp_js('#dashboard-permalink').val(url); 
                                    
                                }                            
                                else if(target =='portfolio-page') {
                                    pp_js('#'+target).val('Web Portfolio'); 
                                    var url = window.location.protocol + "//" + window.location.host + "/web-portfolio";
                                    pp_js('#portfolio-permalink').val(url);
                                    pp_js('#setup-web-portfolio-nav-link, .dops-section-nav-tab[data-hash="setup-web-portfolio"]').removeAttr('disabled');
                                }
                                
                            }
                        }
                    } else {
                        pp_js(id).removeAttr('checked').val(0);
                        if (target!='') {
                            if(target == 'audit-page') {
                                pp_js('.btn-install-on, .goto-audit-builder, #setup-audit-widget-nav-link, .dops-section-nav-tab[data-hash="setup-audit-widget"]').prop('disabled',true);
                                pp_js('#setup-audit-widget-nav-link').attr('disabled','disabled');
                                pp_js('#setup-audit-form-type').prop('disabled',true).trigger("change");
                            } else if(target =='portfolio-page') {
                                pp_js('#setup-web-portfolio-nav-link, .dops-section-nav-tab[data-hash="setup-web-portfolio"]').attr('disabled','disabled');
                                pp_js('#'+target).prop('disabled',true);
                            } else {
                                pp_js('#'+target).prop('disabled',true);
                            } 
                        }
                    }
                    if (pp_js(id).data('triggerChange')) {
                        pp_js(id).trigger('change');
                    }

                }, 100);
            });
            
            // toggle arrow
            pp_js('.sr-partner-icon-arrow-toggle').on('click', function() {
                var is_arrow_down = pp_js(this).hasClass('sr-partner-icon-arrow-down');
                Page.toggle_arrow(pp_js(this), is_arrow_down);
            }).each(function() {
                var is_arrow_down = pp_js(this).hasClass('sr-partner-icon-arrow-down');
                Page.toggle_arrow(pp_js(this), !is_arrow_down);
            });

            //got to audit builder
            pp_js('.goto-audit-builder').on('click', function() {
                pp_js('.dops-section-nav-tab[data-hash="setup-audit-widget"]').click();
                window.scrollTo(0,0);
            });

            //got to portfolio builder
            pp_js('.goto-web-portfolio-builder').on('click', function(e) {
                e.preventDefault();
                pp_js('.dops-section-nav-tab[data-hash="setup-web-portfolio"]').click();
                window.scrollTo(0,0);
            });

            //delete-code 
            pp_js('.delete-code').on('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                var url         = pp_js(this).data('href');
                var shortcode   = pp_js(this).data('shortcode');
                var txt         = Page.getLocalization('are_you_sure_you_want_to_delete', "Are you sure wou want to delete [code]?");
                txt             = txt.replace("[code]", shortcode);

                var html = ' \
                <div id="sr-partner-alert-wrapper"> \
                    <div id="sr-partner-alert-content"> \
                        <p align="center"> \
                            '+txt+' \
                        </p> \
                        <div align="center"> \
                            <a href="'+url+'"  class="dops-button ">&nbsp;&nbsp;'+Page.getLocalization('yes',"Yes")+'&nbsp;&nbsp;</a>&nbsp; \
                            <button type="button" class="dops-button is-primary delete-alert-continue">&nbsp;&nbsp;'+Page.getLocalization('no',"No")+'&nbsp;&nbsp;</button>\
                        </div> \
                    </div> \
                </div> \
                ';
                pp_js('body').append(html);
                pp_js('.delete-alert-continue').on('click', function(e) {
                    pp_js('#sr-partner-alert-wrapper').hide().remove();
                });                

            });            
            //alert close
            pp_js('.sr-partner-alert-close').on('click', function() {
                pp_js(this).parent().hide();
            });

            //form types
            pp_js('#setup-audit-form-type').change(function () {

                var selected = pp_js(this).val();                
                pp_js('#sr_partner_type').val(selected).trigger("change");
                Page.setup_audit_form_button();
            });
            pp_js('#sr_partner_type').change(function () {
                var selected = pp_js('#sr_partner_type').val();
                Page.sr_partner_type_change(selected);                
            });

            //take over change
            pp_js('#sr_partner_takeover_type').change(function () {
                var selected = pp_js('#sr_partner_takeover_type').val();
                Page.sr_partner_takeover_type_change(selected);
            });

            /*** start form builer events ***/
            /*** start form builer events ***/
            /*** start form builer events ***/
            pp_js('.colorpicker-wrap .sr-partner-color-preview').click(function () {
                var self = pp_js(this);
                var target = self.data('target');
                var target_change = self.data('target_change');
                var colorPickerDragTimer;
                var doneDraggingInterval = 500;

                self.ColorPicker({
                    color: Page.getColor(self),
                    onShow: function (colpkr) {
                        pp_js(colpkr).fadeIn(500);
                        return false;
                    },
                    onHide: function (colpkr) {
                        pp_js(colpkr).fadeOut(500);
                        return false;
                    },
                    onChange: function (hsb, hex, rgb) {
                        self.parent().children('.sr-partner-color-preview').attr('style', 'background: #' + hex + ' !important');
                        self.parent().children('.sr-partner-color-picker').val('#' + hex);

                        pp_js.each(target.split("|"), function( key, value ) {                    
                            pp_js.each(target_change.split("|"), function( key2, value2 ) {
                                pp_js(value).css(value2, '#' + hex);
                            });
                        });

                        clearTimeout(colorPickerDragTimer);
                        colorPickerDragTimer = setTimeout(function doneTyping() {
                            // trigger
                            self.parent().children('.sr-partner-color-picker').trigger('onDragEnd');
                        }, doneDraggingInterval);
                    }
                });
            });
            pp_js('.colorpicker-wrap .sr-partner-color-picker').focus(function () {
                var self = pp_js(this);
                var target = self.data('target');
                var target_change = self.data('target_change');
                self.ColorPicker({
                    color: Page.getColor(self),
                    onShow: function (colpkr) {
                        pp_js(colpkr).fadeIn(500);
                        return false;
                    },
                    onHide: function (colpkr) {
                        pp_js(colpkr).fadeOut(500);
                        return false;
                    },
                    onChange: function (hsb, hex, rgb) {
                        self.parent().children('.sr-partner-color-preview').attr('style', 'background: #' + hex + ' !important');
                        self.parent().children('.sr-partner-color-picker').val('#' + hex);

                        pp_js.each(target.split("|"), function( key, value ) {
                            pp_js.each(target_change.split("|"), function( key2, value2 ) {
                                pp_js(value).css(value2, '#' + hex);
                            });
                            
                        });
                    }

                });
            });
            pp_js('#sr_partner_page_background_opacity').on('input change', function() {
                var self = pp_js(this);
                var target = self.data('target');
                var target_change = self.data('target_change');
                var rgb = Page.hexToRgbA(pp_js('#sr_partner_page_background_color').val());        
                pp_js.each(target.split("|"), function( key, value ) {
                    pp_js(value).css('background-color','rgba('+rgb+','+self.val()+')');

                    
                });
            });

            pp_js('#sr_partner_heading').on('keyup', function() {
                pp_js('.sr-partner-preview-heading').html(pp_js(this).val());
            });

            pp_js('#sr_partner_subheading').on('keyup', function() {
                pp_js('.sr-partner-preview-subheading').html(pp_js(this).val());
            });

            pp_js('#sr_partner_banner_label').on('keyup', function() {
                pp_js('.sr-partner-preview-notification-text').html(pp_js(this).val());
            });

            pp_js('#sr_partner_banner_button_label').on('keyup', function() {
                pp_js('.sr-partner-preview-notification-button').val(pp_js(this).val());
            });

            pp_js('#sr_partner_page_button_label').on('keyup', function() {
                pp_js('.sr-partner-preview-calltoaction-button').val(pp_js(this).val());
            });
            /*** end form builer events ***/
            /*** end form builer events ***/
            /*** end form builer events ***/
        },
        setup_audit_form_button : function() {
                        
            if (typeof(pp_js('#setup-audit-form-type').prop('disabled')) === 'undefined' || pp_js('#setup-audit-form-type').prop('disabled')== false) {
                var selected = pp_js('#setup-audit-form-type').val();
                if(selected =='notification' || selected == 'takeover') {
                    pp_js('.btn-install-on').prop('disabled',false);
                } else {
                    pp_js('.btn-install-on').prop('disabled',true);
                }
            }            
        },
        toggle_arrow : function($this, is_arrow_down) {            
            var id = $this.data('id');
            if(is_arrow_down) {
                pp_js(id).show();
                $this.removeClass('sr-partner-icon-arrow-down').addClass('sr-partner-icon-arrow-up');
            } else {
                pp_js(id).hide();
                $this.removeClass('sr-partner-icon-arrow-up').addClass('sr-partner-icon-arrow-down');
            }
        },
        check_hash : function() {
            var hash =  window.location.hash;
            var page = (hash =='' || hash =='#plugin-features') ?  '#plugin-features' : hash;

            if(page == '#plugin-features') {
                pp_js('#sr-partner-form-dashboard-submit').show();
            } else {
                pp_js('#sr-partner-form-dashboard-submit').hide();
            }
            page = '#page-' + page.replace('#', '');
            pp_js('.page').hide();

            pp_js(page).show();
            Page.update_navigation(page);
        },
        update_navigation : function(hash) {
            var hash = hash.replace("#page-", "");
            pp_js('.dops-section-nav-tab').removeClass('is-selected');
            pp_js('.dops-section-nav-tab[data-hash="'+hash+'"]').addClass('is-selected');

            //update form post url
            var url = pp_js('#form-partner-p').data('url') + '#' + hash;

        },
        sr_partner_takeover_type_change : function(selected) {
            pp_js('.wrap-sr-partner-takeover-delay').hide();
            pp_js('.wrap-sr-partner-takeover-scroll').hide();
            if(selected == 'delay') {
                pp_js('.wrap-sr-partner-takeover-delay').show();
            } else if(selected == 'scroll') {
                pp_js('.wrap-sr-partner-takeover-scroll').show();
            }
        },
        sr_partner_type_change : function(selected) {

            pp_js('#wrapper-install-to-all-pages').hide();
            if(selected == "takeover" || selected == 'notification') {
                pp_js('#wrapper-install-to-all-pages').show();
            } else {
                pp_js("#switch-install-to-all-pages").removeAttr("checked");
            }

            Page.hide_all();
            pp_js('.preview_h1').show();
            if(selected == "large") {
                pp_js('.wrap-sr-partner-heading').show();
                pp_js('.wrap-sr-partner-subheading').show();
                pp_js('.wrap-sr-partner-form-background-color').show();
                pp_js('.wrap-sr-partner-form-text-color').show();
                pp_js('.wrap-sr-partner-button-background-color').show();
                pp_js('.wrap-sr-partner-button-text-color').show();

                pp_js('.sr-partner-preview-large-form').show();        
            } else if(selected == "small") {
                pp_js('.wrap-sr-partner-heading').show();
                pp_js('.wrap-sr-partner-subheading').show();            
                pp_js('.wrap-sr-partner-button-background-color').show();
                pp_js('.wrap-sr-partner-button-text-color').show();

                pp_js('.sr-partner-preview-small-form').show();
            } else if(selected == "slim") {
                pp_js('.wrap-sr-partner-form-background-color').show();
                pp_js('.wrap-sr-partner-form-text-color').show();
                pp_js('.wrap-sr-partner-button-background-color').show();
                pp_js('.wrap-sr-partner-button-text-color').show();

                pp_js('.sr-partner-preview-slim-form').show();
            } else if(selected == "takeover") {
                pp_js('.wrap-sr-partner-heading').show();
                pp_js('.wrap-sr-partner-subheading').show();
                pp_js('.wrap-sr-partner-form-background-color').show();
                pp_js('.wrap-sr-partner-form-text-color').show();
                pp_js('.wrap-sr-partner-button-background-color').show();
                pp_js('.wrap-sr-partner-button-text-color').show();


                pp_js('.wrap-sr-partner-page-background-color').show();
                pp_js('.wrap-sr-partner-page-background-opacity').show();

                pp_js('.wrap-sr-partner-takeover-type').show();

                var selected = pp_js('#sr_partner_takeover_type').val();
                Page.sr_partner_takeover_type_change(selected);

                pp_js('.sr-partner-preview-takeover-form').show('fast',function(){
                    pp_js('.preview_h1').hide();
                });
                

            } else if(selected == "notification") {
                pp_js('.wrap-sr-partner-heading').show();
                pp_js('.wrap-sr-partner-subheading').show();

                pp_js('.wrap-sr-partner-banner-label').show();
                pp_js('.wrap-sr-partner-banner-button-label').show();   


                pp_js('.wrap-sr-partner-form-background-color').show();
                pp_js('.wrap-sr-partner-form-text-color').show();
                pp_js('.wrap-sr-partner-button-background-color').show();
                pp_js('.wrap-sr-partner-button-text-color').show();


                pp_js('.wrap-sr-partner-page-background-color').show();
                pp_js('.wrap-sr-partner-page-background-opacity').show();


                pp_js('.sr-partner-preview-notification-others').show();
                pp_js('.sr-partner-preview-notification-others-banner').show();
                pp_js('.wrap-sr-partner-banner-hr').show();


                pp_js('.wrap-sr-partner-banner-button-color').show();
                pp_js('.wrap-sr-partner-banner-button-background-color').show();
                pp_js('.wrap-sr-partner-banner-text-color').show();
                pp_js('.wrap-sr-partner-banner-background-color').show();

                pp_js('.wrap-sr-partner-banner-reshow-delay').show();


                pp_js('.sr-partner-preview-notification-form').show('fast',function(){
                    pp_js('.preview_h1').hide();
                });
                

            } else if(selected == "calltoaction") {
                pp_js('.wrap-sr-partner-heading').show();
                pp_js('.wrap-sr-partner-subheading').show();

                

                pp_js('.wrap-sr-partner-form-background-color').show();
                pp_js('.wrap-sr-partner-form-text-color').show();
                pp_js('.wrap-sr-partner-button-background-color').show();
                pp_js('.wrap-sr-partner-button-text-color').show();


                pp_js('.wrap-sr-partner-page-background-color').show();
                pp_js('.wrap-sr-partner-page-background-opacity').show();

                
                pp_js('.wrap-sr-partner-page-hr').show();

                pp_js('.sr-partner-preview-calltoaction-others').show();

                pp_js('.wrap-sr-partner-page-button-label').show();
                pp_js('.wrap-sr-partner-page-button-background-color').show();
                pp_js('.wrap-sr-partner-page-button-color').show();

                
                pp_js('.sr-partner-preview-notification-others').show();
                pp_js('.sr-partner-preview-calltoaction-form').show('fast',function(){
                    pp_js('.preview_h1').hide();
                });
                

            }
        },
        hide_all : function() {

            pp_js('.wrap-sr-partner-heading').hide();
            pp_js('.wrap-sr-partner-subheading').hide();

            pp_js('.wrap-sr-partner-banner-label').hide();
            pp_js('.wrap-sr-partner-banner-button-label').hide();

            pp_js('.wrap-sr-partner-form-background-color').hide();
            pp_js('.wrap-sr-partner-form-text-color').hide();
            pp_js('.wrap-sr-partner-button-background-color').hide();
            pp_js('.wrap-sr-partner-button-text-color').hide();

            pp_js('.sr-partner-preview-large-form').hide();
            pp_js('.sr-partner-preview-small-form').hide();
            pp_js('.sr-partner-preview-slim-form').hide();
            pp_js('.sr-partner-preview-takeover-form').hide();
            pp_js('.sr-partner-preview-notification-form').hide();
            pp_js('.sr-partner-preview-calltoaction-form').hide();

            pp_js('.sr-partner-preview-calltoaction-others').hide();

            pp_js('.wrap-sr-partner-page-background-color').hide();
            pp_js('.wrap-sr-partner-page-background-opacity').hide();

            pp_js('.sr-partner-preview-notification-others').hide();
            pp_js('.sr-partner-preview-notification-others-banner').hide();

            pp_js('.wrap-sr-partner-banner-hr').hide();

            pp_js('.wrap-sr-partner-banner-button-color').hide();
            pp_js('.wrap-sr-partner-banner-button-background-color').hide();
            pp_js('.wrap-sr-partner-banner-text-color').hide();
            pp_js('.wrap-sr-partner-banner-background-color').hide();

            pp_js('.wrap-sr-partner-banner-reshow-delay').hide();

            pp_js('.wrap-sr-partner-page-hr').hide();

            pp_js('.wrap-sr-partner-page-button-label').hide();
            pp_js('.wrap-sr-partner-page-button-background-color').hide();
            pp_js('.wrap-sr-partner-page-button-color').hide();

            pp_js('.wrap-sr-partner-takeover-type').hide();
            pp_js('.wrap-sr-partner-takeover-delay').hide();
            pp_js('.wrap-sr-partner-takeover-scroll').hide();

        },
        preview_init : function() {
            if(pp_js('.sr-partner-preview-heading').length == 0) return;

            pp_js('.sr-partner-preview-heading').html(pp_js('#sr_partner_heading').val()); 
            pp_js('.sr-partner-preview-subheading').html(pp_js('#sr_partner_subheading').val());


            pp_js('.sr-partner-preview-notification-text').html(pp_js('#sr_partner_banner_label').val()); 
            pp_js('.sr-partner-preview-notification-button').val(pp_js('#sr_partner_banner_button_label').val()); 


            pp_js('.sr-partner-preview-calltoaction-button').val(pp_js('#sr_partner_page_button_label').val()); 

            pp_js('.sr-partner-preview .wrapper')
                .css('background-color', pp_js('#sr_partner_form_background_color').val())
                .css('color', pp_js('#sr_partner_form_text_color').val());

            pp_js('.sr-partner-preview h2').css('color', pp_js('#sr_partner_form_text_color').val());

            pp_js('.sr-partner-admin-create-form-preview .button, .sr-partner-admin-create-form-preview .button:hover')
                .css('background-color', pp_js('#sr_partner_button_background_color').val())
                .css('border-color', pp_js('#sr_partner_button_background_color').val())
                .css('color', pp_js('#sr_partner_button_text_color').val());


            var rgb = Page.hexToRgbA(pp_js('#sr_partner_page_background_color').val());                            
            pp_js('.sr-partner-preview-takeover-form, .sr-partner-preview-notification-form, .sr-partner-preview-calltoaction-form')
                .css('background-color','rgba('+rgb+','+pp_js('#sr_partner_page_background_opacity').val()+')');            

            pp_js('.sr-partner-preview-notification-others-banner .banner')
                .css('background-color', pp_js('#sr_partner_banner_background_color').val())
                .css('color', pp_js('#sr_partner_banner_text_color').val());

            pp_js('.sr-partner-preview-notification-button')
                .css('background-color', pp_js('#sr_partner_banner_button_background_color').val())
                .css('border-color', pp_js('#sr_partner_banner_button_background_color').val())
                .css('color', pp_js('#sr_partner_banner_button_color').val());

            pp_js('.sr-partner-preview-calltoaction-button')
                .css('background-color', pp_js('#sr_partner_page_button_background_color').val())
                .css('border-color', pp_js('#sr_partner_page_button_background_color').val())
                .css('color', pp_js('#sr_partner_page_button_color').val());

            pp_js('#sr_partner_page_background_opacity_value').html(pp_js('#sr_partner_page_background_opacity').val()); 
            pp_js('#sr_partner_takeover_scroll_value').html(pp_js('#sr_partner_takeover_scroll').val());
        },
        hexToRgbA : function(hex) {

            var c;
            if(/^#([A-Fa-f0-9]{3}){1,2}$/.test(hex)){
                c= hex.substring(1).split('');
                if(c.length== 3){
                    c= [c[0], c[0], c[1], c[1], c[2], c[2]];
                }
                c= '0x'+c.join('');
                return ''+[(c>>16)&255, (c>>8)&255, c&255].join(',');
            }
            throw new Error('Bad Hex');

        },
        getColor : function(_self) {
            var color = _self.parent().children('.sr-partner-color-picker').val();
            color = color == '' ? '#8aab15' : color;
            return color;
        },
        getLocalization : function(index, default_value) {
            if (typeof sr_partner_localization !== 'undefined') {
                if(sr_partner_localization.hasOwnProperty(index)) {
                    return sr_partner_localization[index];
                } else {
                    return default_value;
                }

            } else {
                return default_value;
            }
        },
    };
    Page.init();
});