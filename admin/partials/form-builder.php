<form id="sr-partner-create-shortcode" method="post" action="<?php echo esc_url($_SERVER['REQUEST_URI']) ?>" class="validate ">    
    <input type="hidden" value="english" name="sr_partner_language" />


    <div class="form-field wrap-sr-partner-page-button-label">
        <label for="sr_partner_page_button_label"><?php echo __("Page Button Text","sr-partner");?></label>
        <input name="sr_partner_page_button_label" class="dops-text-input" id="sr_partner_page_button_label" type="text"
               value="<?php echo $this->get_post_value('sr_partner_page_button_label', $this->get_post_value_from_data($edit_data, 'page_button_label', __("Get Your Free SEO Audit!","sr-partner"))) ?>">
    </div>


    <div class="form-field wrap-sr-partner-page-button-background-color colorpicker-wrap w49p">
        <label><?php echo __("Page Button BG Color","sr-partner");?></label>

        <div    class="sr-partner-color-preview"
                data-target=".sr-partner-preview-calltoaction-button"
                data-target_change="background-color|border-color"
                style="background: <?php echo $this->get_post_value('sr_partner_page_button_background_color', $this->get_post_value_from_data($edit_data, 'page_button_background_color', '#FFED26')) ?>">
        </div>
        <input  id="sr_partner_page_button_background_color"
                readonly
                name="sr_partner_page_button_background_color"
                class="sr-partner-color-picker"
                type="text"
                value="<?php echo $this->get_post_value('sr_partner_page_button_background_color', $this->get_post_value_from_data($edit_data, 'page_button_background_color', '#FFED26')) ?>"
                data-target=".sr-partner-preview-calltoaction-button"
                data-target_change="background-color|border-color" >
    </div>


    <div class="form-field wrap-sr-partner-page-button-color colorpicker-wrap w49p">
        <label><?php echo __("Page Button Text Color","sr-partner");?></label>

        <div    class="sr-partner-color-preview"
                data-target=".sr-partner-preview-calltoaction-button"
                data-target_change="color"
                style="background: <?php echo $this->get_post_value('sr_partner_page_button_color', $this->get_post_value_from_data($edit_data, 'page_button_color', '#000')) ?>">
        </div>
        <input  id="sr_partner_page_button_color"
                readonly
                name="sr_partner_page_button_color"
                class="sr-partner-color-picker"
                type="text"
                value="<?php echo $this->get_post_value('sr_partner_page_button_color', $this->get_post_value_from_data($edit_data, 'page_button_color', '#000')) ?>"
                data-target=".sr-partner-preview-calltoaction-button"
                data-target_change="color" >
    </div>

    <hr class="clearBoth wrap-sr-partner-page-hr">

    <div class="form-field wrap-sr-partner-banner-label">
        <label for="sr_partner_banner_label"><?php echo __("Banner Label Text","sr-partner");?></label>
        <input name="sr_partner_banner_label" class="dops-text-input" id="sr_partner_banner_label" type="text"
               value="<?php echo $this->get_post_value('sr_partner_banner_label', $this->get_post_value_from_data($edit_data, 'banner_label', __("Does your site have good SEO?","sr-partner"))) ?>">
    </div>

    <div class="form-field wrap-sr-partner-banner-button-label">
        <label for="sr_partner_banner_button_label"><?php echo __("Banner Button Text","sr-partner");?></label>
        <input name="sr_partner_banner_button_label" class="dops-text-input" id="sr_partner_banner_button_label" type="text"
               value="<?php echo $this->get_post_value('sr_partner_banner_button_label', $this->get_post_value_from_data($edit_data, 'banner_button_label', "Get Your Free SEO Audit!")) ?>">
    </div>



    <div class="form-field wrap-sr-partner-banner-background-color colorpicker-wrap w49p">
        <label><?php echo __("Banner Background Color","sr-partner");?></label>

        <div    class="sr-partner-color-preview"
                data-target=".sr-partner-preview-notification-others-banner .banner"
                data-target_change="background-color"
                style="background: <?php echo $this->get_post_value('sr_partner_banner_background_color', $this->get_post_value_from_data($edit_data, 'banner_background_color', '#EA4919')) ?>">
        </div>
        <input  id="sr_partner_banner_background_color"
                readonly
                name="sr_partner_banner_background_color"
                class="sr-partner-color-picker"
                type="text"
                value="<?php echo $this->get_post_value('sr_partner_banner_background_color', $this->get_post_value_from_data($edit_data, 'banner_background_color', '#EA4919')) ?>"
                data-target=".sr-partner-preview-notification-others-banner .banner"
                data-target_change="background-color" >
    </div>


    <div class="form-field wrap-sr-partner-banner-text-color colorpicker-wrap w49p">
        <label><?php echo __("Banner Text Color","sr-partner");?></label>

        <div    class="sr-partner-color-preview"
                data-target=".sr-partner-preview-notification-others-banner .banner"
                data-target_change="color"
                style="background: <?php echo $this->get_post_value('sr_partner_banner_text_color', $this->get_post_value_from_data($edit_data, 'banner_text_color', '#fff')) ?>">
        </div>
        <input  id="sr_partner_banner_text_color"
                readonly
                name="sr_partner_banner_text_color"
                class="sr-partner-color-picker"
                type="text"
                value="<?php echo $this->get_post_value('sr_partner_banner_text_color', $this->get_post_value_from_data($edit_data, 'banner_text_color', '#fff')) ?>"
                data-target=".sr-partner-preview-notification-others-banner .banner"
                data-target_change="color" >
    </div>


    <div class="form-field wrap-sr-partner-banner-button-background-color colorpicker-wrap w49p">
        <label><?php echo __("Banner Button BG Color","sr-partner");?></label>

        <div    class="sr-partner-color-preview"
                data-target=".sr-partner-preview-notification-button"
                data-target_change="background-color|border-color"
                style="background: <?php echo $this->get_post_value('sr_partner_banner_button_background_color', $this->get_post_value_from_data($edit_data, 'banner_button_background_color', '#FFED26')) ?>">
        </div>
        <input  id="sr_partner_banner_button_background_color"
                readonly
                name="sr_partner_banner_button_background_color"
                class="sr-partner-color-picker"
                type="text"
                value="<?php echo $this->get_post_value('sr_partner_banner_button_background_color', $this->get_post_value_from_data($edit_data, 'banner_button_background_color', '#FFED26')) ?>"
                data-target=".sr-partner-preview-notification-button"
                data-target_change="background-color|border-color" >
    </div>


    <div class="form-field wrap-sr-partner-banner-button-color colorpicker-wrap w49p">
        <label><?php echo __("Banner Button Text Color","sr-partner");?></label>

        <div    class="sr-partner-color-preview"
                data-target=".sr-partner-preview-notification-button"
                data-target_change="color"
                style="background: <?php echo $this->get_post_value('sr_partner_banner_button_color', $this->get_post_value_from_data($edit_data, 'banner_button_color', '#000')) ?>">
        </div>
        <input  id="sr_partner_banner_button_color"
                readonly
                name="sr_partner_banner_button_color"
                class="sr-partner-color-picker"
                type="text"
                value="<?php echo $this->get_post_value('sr_partner_banner_button_color', $this->get_post_value_from_data($edit_data, 'banner_button_color', '#000')) ?>"
                data-target=".sr-partner-preview-notification-button"
                data-target_change="color" >
    </div>



    <div class="form-field wrap-sr-partner-banner-reshow-delay clearBoth colorpicker-wrap w99p">
        <label><?php echo __("Delay","sr-partner");?> <small>(<?php echo __("Time before notification pops up again after closing, in seconds","sr-partner");?>)</small></label>
        <label><small>0 - <?php echo __("means no close button","sr-partner");?></small></label>
        <input
            id="sr_partner_banner_reshow_delay"
            name="sr_partner_banner_reshow_delay"
            type="number"
            min="0"
            maxlength="5"
            oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
            class="postform w153px"
            value="<?php echo $this->get_post_value('sr_partner_banner_reshow_delay', $this->get_post_value_from_data($edit_data, 'banner_reshow_delay', '60')) ?>"/>
    </div>


    
    <hr class="clearBoth wrap-sr-partner-banner-hr">
    

    <div class="form-field wrap-sr-partner-heading ">
        <label for="sr_partner_heading"><?php echo __("Heading","sr-partner");?></label>
        <input name="sr_partner_heading" class="dops-text-input" id="sr_partner_heading" type="text"
               value="<?php echo $this->get_post_value('sr_partner_heading', $this->get_post_value_from_data($edit_data, 'heading', __("Analyze your site's SEO performance","sr-partner"))) ?>">
    </div>

    <div class="form-field wrap-sr-partner-subheading ">
        <label for="sr_partner_subheading"><?php echo __("Sub Heading","sr-partner");?></label>
        <textarea name="sr_partner_subheading" rows="3" class="dops-text-input sr-partner-width-95per" id="sr_partner_subheading" ><?php echo $this->get_post_value('sr_partner_subheading', $this->get_post_value_from_data($edit_data, 'subheading', __("Enter your email and website to get a free SEO analysis!","sr-partner"))) ?></textarea>

    </div>

    <div class="form-field wrap-sr-partner-form-background-color colorpicker-wrap w49p">
        <label><?php echo __("Form Background Color","sr-partner");?></label>

        <div    class="sr-partner-color-preview"
                data-target=".sr-partner-preview .wrapper"
                data-target_change="background-color"
                style="background: <?php echo $this->get_post_value('sr_partner_form_background_color', $this->get_post_value_from_data($edit_data, 'form_background_color', '#EFEFEF')) ?>">
        </div>
        <input  id="sr_partner_form_background_color"
                readonly
                name="sr_partner_form_background_color"
                class="sr-partner-color-picker"
                type="text"
                value="<?php echo $this->get_post_value('sr_partner_form_background_color', $this->get_post_value_from_data($edit_data, 'form_background_color', '#EFEFEF')) ?>"
                data-target=".sr-partner-preview .wrapper"
                data-target_change="background-color" >
    </div>

    <div class="form-field wrap-sr-partner-form-text-color colorpicker-wrap w49p">
        <label><?php echo __("Form Text Color","sr-partner");?></label>

        <div    class="sr-partner-color-preview"
                data-target=".sr-partner-preview .wrapper|.sr-partner-preview h2"
                data-target_change="color"
                style="background: <?php echo $this->get_post_value('sr_partner_form_text_color', $this->get_post_value_from_data($edit_data, 'form_text_color', '#000000')) ?>">                        
        </div>
        <input  id="sr_partner_form_text_color"
                readonly
                name="sr_partner_form_text_color"
                class="sr-partner-color-picker "
                type="text"
                value="<?php echo $this->get_post_value('sr_partner_form_text_color', $this->get_post_value_from_data($edit_data, 'form_text_color', '#000000')) ?>"
                data-target=".sr-partner-preview .wrapper|.sr-partner-preview h2"
                data-target_change="color" >
    </div>


    <div class="form-field wrap-sr-partner-button-background-color colorpicker-wrap clearBoth w49p">
        <label><?php echo __("Button Background Color","sr-partner");?></label>

        <div    class="sr-partner-color-preview"
                data-target=".sr-partner-admin-create-form-preview .button|.sr-partner-admin-create-form-preview .button:hover"
                data-target_change="background-color|border-color"
                style="background: <?php echo $this->get_post_value('sr_partner_button_background_color', $this->get_post_value_from_data($edit_data, 'button_background_color', '#67BF35')) ?>">                        
        </div>
        <input  id="sr_partner_button_background_color"
                readonly
                name="sr_partner_button_background_color"
                class="sr-partner-color-picker"
                type="text"
                value="<?php echo $this->get_post_value('sr_partner_button_background_color', $this->get_post_value_from_data($edit_data, 'button_background_color', '#67BF35')) ?>"
                data-target=".sr-partner-admin-create-form-preview .button|.sr-partner-admin-create-form-preview .button:hover"
                data-target_change="background-color|border-color" >
    </div>
    

    <div class="form-field wrap-sr-partner-button-text-color colorpicker-wrap w49p">
        <label><?php echo __("Button Text Color","sr-partner");?></label>

        <div    class="sr-partner-color-preview"
                data-target=".sr-partner-admin-create-form-preview .button|.sr-partner-admin-create-form-preview .button:hover"
                data-target_change="color"
                style="background: <?php echo $this->get_post_value('sr_partner_button_text_color', $this->get_post_value_from_data($edit_data, 'button_text_color', '#ffffff')) ?>">                        
        </div>
        <input  id="sr_partner_button_text_color"
                readonly
                name="sr_partner_button_text_color"
                class="sr-partner-color-picker"
                type="text"
                value="<?php echo $this->get_post_value('sr_partner_button_text_color', $this->get_post_value_from_data($edit_data, 'button_text_color', '#ffffff')) ?>"
                data-target=".sr-partner-admin-create-form-preview .button|.sr-partner-admin-create-form-preview .button:hover"
                data-target_change="color" >
    </div>


    <div class="form-field wrap-sr-partner-page-background-color colorpicker-wrap w49p">
        <label><?php echo __("Page Background Color","sr-partner");?></label>

        <div    class="sr-partner-color-preview"
                data-target=".sr-partner-preview-takeover-form|.sr-partner-preview-notification-form|.sr-partner-preview-calltoaction-form"
                data-target_change="background-color"
                style="background: <?php echo $this->get_post_value('sr_partner_page_background_color', $this->get_post_value_from_data($edit_data, 'page_background_color', '#333')) ?>"> 
        </div>
        <input  id="sr_partner_page_background_color"
                readonly
                name="sr_partner_page_background_color"
                class="sr-partner-color-picker"
                type="text"
                value="<?php echo $this->get_post_value('sr_partner_page_background_color', $this->get_post_value_from_data($edit_data, 'page_background_color', '#333')) ?>"
                data-target=".sr-partner-preview-takeover-form|.sr-partner-preview-notification-form|.sr-partner-preview-calltoaction-form"
                data-target_change="background-color" >
    </div>


    <div class="form-field wrap-sr-partner-page-background-opacity colorpicker-wrap w49p">
        <label><?php echo __("Page Background Opacity","sr-partner");?></label>            
        0<input
            id="sr_partner_page_background_opacity"
            name="sr_partner_page_background_opacity"
            type="range"
            value="<?php echo $this->get_post_value('sr_partner_page_background_opacity', $this->get_post_value_from_data($edit_data, 'page_background_opacity', '0.5')) ?>"
            min="0"
            max="1"
            step=".01"
            oninput="sr_partner_page_background_opacity_value.value=sr_partner_page_background_opacity.value"
            data-target=".sr-partner-preview-takeover-form|.sr-partner-preview-notification-form|.sr-partner-preview-calltoaction-form"
            data-target_change="background-color"
            class ="sr-partner-display-inline-block sr-partner-vertical-align-middle sr-partner-width-100" />1 - 
        <b><output id="sr_partner_page_background_opacity_value" name="sr_partner_page_background_opacity_value" for="sr_partner_page_background_opacity">0</output></b>
    </div>


   
    <div class="form-field wrap-sr-partner-takeover-type clearBoth colorpicker-wrap w49p">
        <label><?php echo __("Takeover Type","sr-partner");?></label>
        <select name="sr_partner_takeover_type" id="sr_partner_takeover_type" class="postform">
            <?php
            $temp = $this->get_takeover_types();
            $selected = $this->get_post_value('sr_partner_takeover_type', $this->get_post_value_from_data($edit_data, 'takeover_type', 'delay'));
            foreach ($temp as $key => $val) {
                if ($key == $selected) {
                    echo '<option value="' . $key . '" selected="selected">' . $val . '</option>';
                } else {
                    echo '<option value="' . $key . '">' . $val . '</option>';
                }
            }
            ?>

        </select>
    </div>
    
    <div class="form-field wrap-sr-partner-takeover-delay colorpicker-wrap w49p">
        <label><?php echo __("Delay","sr-partner");?><small> (<?php echo __("In milliseconds","sr-partner");?>)</small></label>
        <input
            class="dops-text-input sr-partner-width-100 sr-partner-oninput-check-length"
            id="sr_partner_takeover_delay"
            name="sr_partner_takeover_delay"
            type="number"
            min="0"                
            maxlength="5"            
            class="postform w153px"
            value="<?php echo $this->get_post_value('sr_partner_takeover_delay', $this->get_post_value_from_data($edit_data, 'takeover_delay', '5000')) ?>"/>
    </div>

    <div class="form-field wrap-sr-partner-takeover-scroll colorpicker-wrap w49p">
        <label><?php echo __("Scroll","sr-partner");?> <small>(<?php echo __("Scroll Percentage before pops up starts to show","sr-partner");?>)</small></label>            
        0<input
            id="sr_partner_takeover_scroll"
            name="sr_partner_takeover_scroll"
            type="range"
            value="<?php echo $this->get_post_value('sr_partner_takeover_scroll', $this->get_post_value_from_data($edit_data, 'takeover_scroll', '0')) ?>"
            min="0"
            max="100"
            step="1"            
            data-passto="sr_partner_takeover_scroll_value"
            class ="sr-partner-display-inline-block sr-partner-vertical-align-middle sr-partner-width-100 sr-partner-oninput-pass-value" />100 - 
        <b><output id="sr_partner_takeover_scroll_value" name="sr_partner_takeover_scroll_value" for="sr_partner_takeover_scroll">0</output>%</b>
    </div>
</form>