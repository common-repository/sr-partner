var pp_js = jQuery.noConflict();
pp_js(document).ready(function() {

    function sr_partner_web_audit(unique_code) {
        var $embedded_div = pp_js('#'+unique_code);
        var $embedded_div_type = $embedded_div.data('type');
        var takeover_scroll = false;
        var _parent = this;
        this.init = function() {

            _parent.embedLoaderWrapper();
            switch ($embedded_div_type) {
                case 'large':
                    _parent.formLarge();
                    break;                    
                case 'small':
                    _parent.formSmall();
                    break;
                case 'slim':
                    _parent.formSlim();
                    break;
                case 'notification':
                    _parent.formNotification();
                    break;
                case 'takeover':
                    _parent.formTakeover();
                    break;
                case 'calltoaction':
                    _parent.formCalltoaction();
                    break;                        
            }
        },
        this.formLarge = function () {
            var form = _parent.generateForm();
            $embedded_div.html(form);
            _parent.addEvents();
        },
        this.formSmall = function () {
            var form = _parent.generateForm();
            $embedded_div.html(form).addClass('web-audit-gen-inline-block');
            _parent.addEvents();
        },
        this.formSlim = function () {
            var form = _parent.generateForm();
            $embedded_div.html(form);
            _parent.addEvents();
        },
        this.formNotification = function () {
            var banner = _parent.generateBanner();
            pp_js('body').prepend(banner);
            pp_js('#web-audit-gen-banner-wrapper-'+unique_code).animate({'margin-top' : '0'}, { duration: 0, queue: false });
            pp_js('html').animate({'margin-top' : '45px'}, { duration: 330, queue: false});
            _parent.embedModalWrapper();
            _parent.addModalEvents();
            _parent.addEvents();

            var cookie = _parent.getCookie("web-audit-gen-cookie-"+unique_code);
            if (cookie) {
                pp_js('#web-audit-gen-banner-wrapper-'+unique_code).animate({'margin-top' : '-45px'}, { duration: 0, queue: false });
                pp_js('html').animate({'margin-top' : '0'}, { duration: 330, queue: false});
                
                _parent.checkNotificationCookie();
            }
        },
        this.formTakeover = function () {
            _parent.embedModalWrapper();
            _parent.addModalEvents();
            _parent.addEvents();
            var takeover_type   = _parent.getData('takeover_type');

            if(takeover_type == 'scroll') {
                var takeover_scroll = parseInt(_parent.getData('takeover_scroll'));                
                var has_vscroll = (pp_js(document).height() > pp_js(window).height()) ? true : false;
                if( takeover_scroll > 0  && has_vscroll ) {
                    _parent.addEventOnScroll(takeover_scroll);
                } else {
                    _parent.showModal();
                    takeover_scroll = false;
                }    
            } else if(takeover_type == 'delay') {
                _parent.addEventTakeoverDelay();
            }

            
        },
        this.formCalltoaction = function () {                  
            var button      = _parent.getPageButton();
            $embedded_div.html(button).addClass('web-audit-gen-inline-block');
            _parent.embedModalWrapper();
            _parent.addModalEvents();
            _parent.addEvents();

            var $page_button = pp_js('#web-audit-gen-calltoaction-button-'+unique_code);
            $page_button.on("click", function() {
                _parent.showModal();
            });

        },
        this.addEventTakeoverDelay = function () {
            if(takeover_scroll === false) {
                takeover_scroll = true;
                
                setTimeout(function() {
                    _parent.showModal();
                    takeover_scroll = false;
                },_parent.getData('takeover_delay'));
            }                
        },  
        this.addEventOnScroll = function (takeover_scroll) {
               
            pp_js(window).on('scroll', function () {
                var scroll_percentage = parseInt(_parent.getScrollPercentage());
                if(scroll_percentage >= takeover_scroll) {
                    if(takeover_scroll === false) {
                        _parent.showModal();       
                         takeover_scroll = true;                     
                    }
                }
            });
       
        },
        this.checkNotificationCookie = function () {
            
            if($embedded_div_type == 'notification') {
                var cookie = _parent.getCookie("web-audit-gen-cookie-"+unique_code);
                if (cookie) {
                    setTimeout(function(){
                        _parent.checkNotificationCookie();
                    },1000);
                    
                } else {
                    pp_js('#web-audit-gen-banner-wrapper-'+unique_code).animate({'margin-top' : '0'}, { duration: 0, queue: false });
                    pp_js('html').animate({'margin-top' : '45px'}, { duration: 330, queue: false});                        
                }
            }
            
        },
        this.addLoaderWrapperEvents = function () {                
            var $loader_wrapper  = pp_js('#web-audit-gen-loader-wrapper-'+unique_code);
            var $loader_content  = pp_js('#web-audit-gen-loader-content-'+unique_code);
            $loader_wrapper.on("click", function() {
                _parent.hideLoader();
            });

            $loader_content.on("click", function(e) {
                e.stopPropagation();
            });
            
        },
        this.removedLoaderWrapperEvents = function () {
            var $loader_wrapper = pp_js('#web-audit-gen-loader-wrapper-'+unique_code);
            $loader_wrapper.unbind("click");
        },
        this.addModalEvents = function () {
            var $banner_button   = pp_js('#web-audit-gen-banner-button-'+unique_code);
            var $close_modal     = pp_js('#web-audit-gen-modal-close-'+unique_code);
            var $close_banner    = pp_js('#web-audit-gen-banner-close-'+unique_code);
            var $email           = pp_js('#web-audit-gen-form-text-email'+unique_code);
            var $url             = pp_js('#web-audit-gen-form-text-url'+unique_code);

            $close_modal.on("click", function() {                    
                _parent.hideModal();
                takeover_scroll = true;
                $email.val('');
                $url.val('');
            });
            $banner_button.on("click", function() {
                _parent.showModal();

            });
            $close_banner.on("click", function() {
                pp_js('#web-audit-gen-banner-wrapper-'+unique_code).animate({'margin-top' : '-45px'}, { duration: 0, queue: false });
                pp_js('html').animate({'margin-top' : '0'}, { duration: 330, queue: false});

                var cookie = _parent.getCookie("web-audit-gen-cookie-"+unique_code);                    
                if (cookie == "") {
                    _parent.setCookie("web-audit-gen-cookie-"+unique_code, '1', _parent.getData('banner_reshow_delay'));
                }
                _parent.checkNotificationCookie();
            });
        },
        this.addEvents = function () {
            var $run_audit       = pp_js('#web-audit-gen-form-button-'+unique_code);
            var $close_loader    = pp_js('#web-audit-gen-loader-close-'+unique_code);

            $run_audit.on("click", function() {

                var validation_errors = _parent.validateForm();
                if (_parent.isEmpty(validation_errors)) {
                    pp_js('#web-audit-gen-loader-alert-'+unique_code).html('');
                    _parent.removedLoaderWrapperEvents();
                    _parent.runAudit();
                } else {
                    _parent.addLoaderWrapperEvents();

                }

            });
            $close_loader.on("click", function() {
                _parent.hideLoader();

            });
        },
        this.generateBanner = function () {
            var $html = ' \
                        <div id="web-audit-gen-banner-wrapper-'+unique_code+'" class="web-audit-gen-banner-wrapper" align="center" style="background-color:'+_parent.getData('banner_background_color')+';color:'+_parent.getData('banner_text_color')+'" > \
                            <label>'+_parent.getData('banner_label')+'</label> \
                            <input type="button" id="web-audit-gen-banner-button-'+unique_code+'" class="web-audit-gen-banner-button" value="'+_parent.getData('banner_button_label')+'" style="background-color:'+_parent.getData('banner_button_background_color')+';border-color:'+_parent.getData('banner_button_background_color')+';color:'+_parent.getData('banner_button_color')+'" > \
                            <div id="web-audit-gen-banner-close-'+unique_code+'" class="web-audit-gen-banner-close" align="right"></div> \
                        </div> \
                        ';
            return $html;
        },
        this.generateForm = function () {
            var $html = '';
            switch ($embedded_div_type) {
                case 'large':
                    $html = ' \
                        <div class="web-audit-gen-form-wrapper" style="background-color:'+_parent.getData('form_background_color')+';color:'+_parent.getData('form_text_color')+'" > \
                            '+_parent.getHeading(2)+' \
                            '+_parent.getSubHeading()+' \
                            <div> \
                                '+_parent.getInputUrl('web-audit-gen-w300')+' \
                                '+_parent.getInputEmail('web-audit-gen-w300')+' \
                                <div> \
                                    '+_parent.getInputRunAudit('web-audit-gen-w300')+' \
                                </div> \
                            </div> \
                        </div>';
                    break;
                case 'small':
                    $html = ' \
                        <div align="left" class="web-audit-gen-w300"> \
                            '+_parent.getHeading(3)+' \
                            '+_parent.getSubHeading()+' \
                            <div> \
                                <div> \
                                    '+_parent.getInputUrl('web-audit-gen-w300')+' \
                                </div> \
                                <div> \
                                    '+_parent.getInputEmail('web-audit-gen-w300')+' \
                                </div> \
                                <div> \
                                    '+_parent.getInputRunAudit('web-audit-gen-w300')+' \
                                </div> \
                            </div> \
                        </div>';
                    break;
                case 'slim':
                    $html = ' \
                        <div class="web-audit-gen-form-wrapper" style="background-color:'+_parent.getData('form_background_color')+';color:'+_parent.getData('form_text_color')+'" > \
                            '+_parent.getInputUrl('web-audit-gen-w30p')+' \
                            '+_parent.getInputEmail('web-audit-gen-w30p')+' \
                            '+_parent.getInputRunAudit('web-audit-gen-w30p')+' \
                        </div>';
                    break;
                case 'notification':
                case 'takeover':
                case 'calltoaction':
                    $html = ' \
                            <div id="web-audit-gen-modal-content-'+unique_code+'" class="web-audit-gen-modal-content"> \
                                <div id="web-audit-gen-modal-close-'+unique_code+'" class="web-audit-gen-modal-close" align="right"></div> \
                                <div class="web-audit-gen-form-wrapper" style="background-color:'+_parent.getData('form_background_color')+';color:'+_parent.getData('form_text_color')+'" > \
                                    '+_parent.getHeading(2)+' \
                                    '+_parent.getSubHeading()+' \
                                    <div> \
                                        '+_parent.getInputUrl('web-audit-gen-w300')+' \
                                        '+_parent.getInputEmail('web-audit-gen-w300')+' \
                                        <div> \
                                            '+_parent.getInputRunAudit('web-audit-gen-w300')+' \
                                        </div> \
                                    </div> \
                                </div> \
                            </div>';
                    break;
                default:
            }                

            return $html;
        },
        this.getHeading = function (h) {
            return '<h'+h+' class="web-audit-gen-form-heading" style="color:'+_parent.getData('form_text_color')+';">'+_parent.getData('heading')+'</h'+h+'>';
        },
        this.getSubHeading = function () {
            return '<p class="web-audit-gen-form-subheading" >'+_parent.getData('subheading')+'</p>';
        },
        this.getInputUrl = function (width) {
            return '<input name="url" type="text" id="web-audit-gen-form-text-url'+unique_code+'" class="web-audit-gen-form-text '+width+'" placeholder="URL">';
        },
        this.getInputEmail = function (width) {
            return '<input name="email" type="email" id="web-audit-gen-form-text-email'+unique_code+'" class="web-audit-gen-form-text '+width+'" placeholder="Email">';
        },
        this.getInputRunAudit = function (width) {
            return '<input  type="button" \
                            id="web-audit-gen-form-button-'+unique_code+'" \
                            class="web-audit-gen-form-button '+width+'" \
                            value="'+_parent.getLocalization('run_audit', "Run Audit")+'" style="background-color:'+_parent.getData('button_background_color')+'; border-color: '+_parent.getData('button_background_color')+'; color: '+_parent.getData('button_text_color')+';">';
        },            
        this.getPageButton = function () {
            return '<input  type="button" \
                            id="web-audit-gen-calltoaction-button-'+unique_code+'" \
                            class="web-audit-gen-calltoaction-button"  \
                            value="'+_parent.getData('page_button_label')+'" style="background-color:'+_parent.getData('page_button_background_color')+'; border-color: '+_parent.getData('page_button_background_color')+'; color: '+_parent.getData('page_button_color')+';">';
        },
        this.getData = function (index) {
            var temp =  $embedded_div.data(index);
            if (temp) {
                return _parent.stripSlashes(temp);
            } else {
                return _parent.getDefaultData(index);
            }
        },
        this.getDefaultData = function (index) {
            var def = {
                heading                         : "Analyze your site's SEO performance",
                subheading                      : "Enter your email and website to get a free SEO analysis!",
                form_background_color           : "#EFEFEF",
                form_text_color                 : "#000000",
                button_background_color         : "#67BF35",
                button_text_color               : "#FFFFFF",
                page_background_color           : "#333333",
                page_background_opacity         : "0.5",
                takeover_type                   : "delay",
                takeover_delay                  : "5000",
                takeover_scroll                 : "0",
                banner_label                    : "Does your site have good SEO?",
                banner_button_label             : "Get Your Free SEO Audit!",
                banner_background_color         : "#EA4919",
                banner_text_color               : "#FFFFFF",
                banner_button_background_color  : "#FFED26",
                banner_button_color             : "#000000",
                banner_reshow_delay             : "60",
                page_button_label               : "Get Your Free SEO Audit!",
                page_button_background_color    : "#FFED26",
                page_button_color               : "#000000"
            };

            return def.hasOwnProperty(index) ? def[index] : false;
        },
        this.showLoader = function () {
            pp_js('#web-audit-gen-loader-wrapper-'+unique_code).css('display','block');
        },
        this.hideLoader = function () {
            pp_js('#web-audit-gen-loader-wrapper-'+unique_code).css('display','none');
        },
        this.showModal = function () {
            pp_js('#web-audit-gen-modal-wrapper-'+unique_code).css('display','block');
        },
        this.hideModal = function () {
            pp_js('#web-audit-gen-modal-wrapper-'+unique_code).css('display','none');
        },
        this.embedLoaderWrapper = function () {
            var rgb = _parent.hexToRgbA(_parent.getData('page_background_color'));
            pp_js('<div id="web-audit-gen-loader-wrapper-'+unique_code+'" class="web-audit-gen-loader-wrapper" style="background-color: rgba('+rgb+','+_parent.getData('page_background_opacity')+');"></div>').appendTo('body');
            this.createLoader();
        },
        this.createLoader = function () {
            var $html = '<div id="web-audit-gen-loader-content-'+unique_code+'" class="web-audit-gen-loader-content"> \
            <div id="web-audit-gen-loader-close-'+unique_code+'" class="web-audit-gen-loader-close" align="right"></div> \
            <div id="web-audit-gen-loader-alert-'+unique_code+'" align="left"></div> \
            </div>';
            pp_js('#web-audit-gen-loader-wrapper-'+unique_code).html($html);
        },
        this.embedModalWrapper = function () {
            var rgb = _parent.hexToRgbA(_parent.getData('page_background_color'));
            pp_js('<div id="web-audit-gen-modal-wrapper-'+unique_code+'" class="web-audit-gen-modal-wrapper" style="background-color: rgba('+rgb+','+_parent.getData('page_background_opacity')+');"></div>').appendTo('body');
            this.createModal();
        },
        this.createModal = function () {
            var form = _parent.generateForm();
            pp_js('#web-audit-gen-modal-wrapper-'+unique_code).html(form);
        },
        this.validateForm = function (str) {
            var $email       = pp_js('#web-audit-gen-form-text-email'+unique_code).val();
            var $url         = pp_js('#web-audit-gen-form-text-url'+unique_code).val();
            var errors      = [];

            if (_parent.isEmpty($url)) {
                errors.push(_parent.getLocalization('web_url_required', "Website URL is required."));
            }
            else if (!_parent.validateURL($url)) {
                errors.push(_parent.getLocalization('web_url_invalid', "Website URL is invalid."));
            }

            if (_parent.isEmpty($email)) {
                errors.push(_parent.getLocalization('email_required', "Email is required."));
            }
            else if (!_parent.validateEmail($email)) {
                errors.push(_parent.getLocalization('email_invalid', "Email is invalid."));
            }

            


            if (_parent.isEmpty(errors))
                return "";
            else {
                var el="";
                pp_js.each(errors, function (index, error) {
                    if ( typeof(error) !== 'undefined')
                        el +='<div>'+error+'</div>';
                });
                _parent.setHtmlAlert(el,'danger');
                _parent.showLoader();
                return 'error';
            }
        },
        this.setHtmlAlert = function (elem, alert, show_close) {
            var $temp = '<div class="web-audit-gen-loader-alert web-audit-gen-loader-alert-'+alert+'" >'+elem+'</div>';
            pp_js('#web-audit-gen-loader-alert-'+unique_code).html($temp);

            var $close_loader = pp_js('#web-audit-gen-loader-close-'+unique_code);
            if(show_close) {
                $close_loader.css('display', 'block');
            } else {
                $close_loader.css('display', 'none');
            }
        },
        this.runAudit = function () {
            _parent.setHtmlAlert('<b>'+_parent.getLocalization('running_audit', "Running your web audit.")+'</b> '+_parent.getLocalization('please_wait', "Please wait...")+'','info', false);
            _parent.showLoader();

            var $email   = pp_js('#web-audit-gen-form-text-email'+unique_code);
            var $url     = pp_js('#web-audit-gen-form-text-url'+unique_code);
            var data = {
                action     : 'sr_partner_ajax',
                email      : $email.val().toLowerCase(),
                url        : $url.val().toLowerCase(),
                source_url : window.location.href
            };
            pp_js.ajax({
                url: sr_partner_ajax_object.ajax_url, // this is the object instantiated in wp_localize_script function
                type: 'POST',
                dataType: 'json',
                data: data,
                success : function(response,status,xhr) {
                    if (response.hasOwnProperty('statusCode') && response.statusCode == 200 ) {

                        _parent.setHtmlAlert('<b>'+_parent.getLocalization('success', "Success!")+'</b> '+response.response.info+'','success');
                        $email.val('');
                        $url.val('');

                    } else if(response.hasOwnProperty('statusCode') &&  response.statusCode == 400 || response.statusCode == 422 ) {
                        if (typeof(response.data) !== 'undefined' && typeof(response.data.error) !== 'undefined') {
                            _parent.setHtmlAlert('<b>'+_parent.getLocalization('oh_snap', "Oh snap!")+'</b> '+response.data.error+'','danger');    
                        } else if (typeof(response.response) !== 'undefined' && typeof(response.response.error) !== 'undefined') {
                            _parent.setHtmlAlert('<b>'+_parent.getLocalization('oh_snap', "Oh snap!")+'</b> '+response.response.error+'','danger');    
                        } else {
                            _parent.setHtmlAlert('<b>'+_parent.getLocalization('oh_snap', "Oh snap!")+'</b> '+_parent.getLocalization('error_occured', "There was a problem processing your request. Please try again."),'danger');
                        }                            
                        
                    } else {
                        _parent.setHtmlAlert('<b>'+_parent.getLocalization('oh_snap', "Oh snap!")+'</b> '+_parent.getLocalization('error_occured', "There was a problem processing your request. Please try again."),'danger');
                    }                        
                    _parent.addLoaderWrapperEvents();
                },
                error : function(xhr,status,error) {                
                    _parent.setHtmlAlert('<b>'+_parent.getLocalization('oh_snap', "Oh snap!")+'</b> '+_parent.getLocalization('error_occured', "There was a problem processing your request. Please try again."),'danger');
                    _parent.addLoaderWrapperEvents();
                }
              });

        },
        
        this.validateEmail = function (email) {
            var regexp = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;                
            return regexp.test(email);
        },
        this.validateURL = function (str) {
            var regexp =  /^(?:(?:https?|ftp):\/\/)?(?:(?!(?:10|127)(?:\.\d{1,3}){3})(?!(?:169\.254|192\.168)(?:\.\d{1,3}){2})(?!172\.(?:1[6-9]|2\d|3[0-1])(?:\.\d{1,3}){2})(?:[1-9]\d?|1\d\d|2[01]\d|22[0-3])(?:\.(?:1?\d{1,2}|2[0-4]\d|25[0-5])){2}(?:\.(?:[1-9]\d?|1\d\d|2[0-4]\d|25[0-4]))|(?:(?:[a-z\u00a1-\uffff0-9]-*)*[a-z\u00a1-\uffff0-9]+)(?:\.(?:[a-z\u00a1-\uffff0-9]-*)*[a-z\u00a1-\uffff0-9]+)*(?:\.(?:[a-z\u00a1-\uffff]{2,})))(?::\d{2,5})?(?:\/\S*)?$/;
            str = (str != null) ? str.toLowerCase() : str;
            return regexp.test(str);
        },
        this.isEmpty = function (str) {
            return (!str || 0 === str.length);
        },
        this.stripSlashes = function (str) {
            if(isNaN(str)) {
                return str.replace(/\\(.)/mg, "$1");
            } else {
                return str;
            }
            
        },
        this.hexToRgbA = function (hex) {
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
        this.setCookie = function (cname, cvalue, exseconds) {
            var d = new Date();
            d.setTime(d.getTime() + (exseconds * 1000));
            var expires = "expires="+d.toUTCString();
            document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
        },
        this.getCookie = function (cname) {
            var name = cname + "=";
            var ca = document.cookie.split(';');
            for(var i = 0; i < ca.length; i++) {
                var c = ca[i];
                while (c.charAt(0) == ' ') {
                    c = c.substring(1);
                }
                if (c.indexOf(name) == 0) {
                    return c.substring(name.length, c.length);
                }
            }
            return "";
        },
        this.getScrollPercentage = function () {
            var s = pp_js(window).scrollTop(),
            d = pp_js(document).height(),
            c = pp_js(window).height();
            var scrollPercent = (s / (d-c)) * 100;
            return scrollPercent;
        },
        this.getLocalization = function(index, default_value) {
            if (typeof sr_partner_localization !== 'undefined') {
                if(sr_partner_localization.hasOwnProperty(index)) {
                    return sr_partner_localization[index];
                } else {
                    return default_value;
                }
            } else {
                return default_value;
            }
        }
    }

     pp_js(".sr-partner-audit-widget").each(function(){
        var sr_partner_widget = new sr_partner_web_audit(pp_js(this).prop('id'));
        sr_partner_widget.init();
    });    
});