<?php
    $portfolioShortCode = get_option(SR_PARTNER_PORTFOLIO_OPTION);
    $portfolioShortCode = (!empty($portfolioShortCode)) ? unserialize($portfolioShortCode) : array();
?>

<div class="sr-partner-w512 sr-partner-fl">
    <?php if (!empty($portfolioShortCode)) { ?>
        <div class="form-field colorpicker-wrap w99p">
            <label><?php echo __("Short Code", "sr-partner");?></label>
            <input type="text" class="dops-text-input sr-partner-width-145 " readonly="readonly" value="[sr-partner-os-portfolio]">
        </div>
    <?php } ?>
    <div class="form-field colorpicker-wrap w99p">
        <label><?php echo __("No of Thumbnail per Row", "sr-partner");?></label>
        <select class="sr-partner-w40p sr-partner-fl"
                id="sr_partner_web_portfolio_no_cards_per_row"
                name="sr_partner_web_portfolio_no_cards_per_row">
            <option <?php echo (isset($portfolioShortCode['sr_partner_web_portfolio_no_cards_per_row']) && intval($portfolioShortCode['sr_partner_web_portfolio_no_cards_per_row']) == 2) ? 'selected' : ''; ?>>2</option>
            <option <?php echo (isset($portfolioShortCode['sr_partner_web_portfolio_no_cards_per_row']) && intval($portfolioShortCode['sr_partner_web_portfolio_no_cards_per_row']) == 3) ? 'selected' : ''; ?>>3</option>
            <option <?php echo (isset($portfolioShortCode['sr_partner_web_portfolio_no_cards_per_row']) && intval($portfolioShortCode['sr_partner_web_portfolio_no_cards_per_row']) == 4) ? 'selected' : ''; ?> >4</option>
        </select>
    </div>
    <div class="form-field colorpicker-wrap w99p">
        <div class="dops-section-header__actions ">
            <label class="form-toggle__label" >
                <input class="form-toggle is-compact"
                       type="checkbox"
                       name="sr_partner_web_portfolio_show_mockup_menu"
                       value="1"
                       data-trigger-change="1"
                       id="switch-portfolio-show-mockup-menu" <?php echo ((isset($portfolioShortCode['sr_partner_web_portfolio_show_mockup_menu']) && intval($portfolioShortCode['sr_partner_web_portfolio_show_mockup_menu']) == 0)) ? '' : 'checked="checked"'; ?>>
                <label class="form-toggle__label"  >
                    <span class="form-toggle__switch sr-partner-form-toggle__switch" role="checkbox" data-id="#switch-portfolio-show-mockup-menu" data-target="dashboard-page" >
                    </span>
                    <span class="form-toggle__label-content" ><?php echo __("Show Mockup Categories","sr-partner");?></span>
                </label>
            </label>
        </div>
    </div>
</div>

<div class="sr-partner-w382 sr-partner-fl">

    <div class="form-field colorpicker-wrap w99p">
        <label><?php echo __("Mockup Title Color","sr-partner");?></label>

        <div class="sr-partner-color-preview"
             data-target=""
             data-target_change=""
             style="background:<?php echo (isset($portfolioShortCode['sr_partner_web_portfolio_mockup_title_color'])) ? $portfolioShortCode['sr_partner_web_portfolio_mockup_title_color'] :'#FFF'?>">
        </div>
        <input id="sr_partner_web_portfolio_mockup_title_color"
               readonly
               name="sr_partner_web_portfolio_mockup_title_color"
               class="sr-partner-color-picker colorpicker-no-background"
               type="text"
               value="<?php echo (isset($portfolioShortCode['sr_partner_web_portfolio_mockup_title_color'])) ? $portfolioShortCode['sr_partner_web_portfolio_mockup_title_color'] :'#FFF'?>"
               data-target=""
               data-target_change="" />
    </div>

    <div class="form-field colorpicker-wrap w49p">
        <label><?php echo __("Button Primary Color", "sr-partner");?></label>

        <div class="sr-partner-color-preview"
             data-target=""
             data-target_change=""
             style="background:<?php echo (isset($portfolioShortCode['sr_partner_web_portfolio_button_primary_color'])) ? $portfolioShortCode['sr_partner_web_portfolio_button_primary_color'] :'#0A89C7'?>">
        </div>
        <input id="sr_partner_web_portfolio_button_primary_color"
               readonly
               name="sr_partner_web_portfolio_button_primary_color"
               class="sr-partner-color-picker colorpicker-no-background"
               type="text"
               value="<?php echo (isset($portfolioShortCode['sr_partner_web_portfolio_button_primary_color'])) ? $portfolioShortCode['sr_partner_web_portfolio_button_primary_color'] :'#0A89C7'?>"
               data-target=""
               data-target_change="" />
    </div>

    <div class="form-field colorpicker-wrap w49p">
        <label><?php echo __("Button Secondary Color","sr-partner");?></label>
        <div class="sr-partner-color-preview"
             data-target=""
             data-target_change=""
             style="background:<?php echo (isset($portfolioShortCode['sr_partner_web_portfolio_button_secondary_color'])) ? $portfolioShortCode['sr_partner_web_portfolio_button_secondary_color'] :'#FFF'?>">
        </div>
        <input id="sr_partner_web_portfolio_button_secondary_color"
               readonly
               name="sr_partner_web_portfolio_button_secondary_color"
               class="sr-partner-color-picker colorpicker-no-background"
               type="text"
               value="<?php echo (isset($portfolioShortCode['sr_partner_web_portfolio_button_secondary_color'])) ? $portfolioShortCode['sr_partner_web_portfolio_button_secondary_color'] :'#FFF'?>"
               data-target=""
               data-target_change="" />
    </div>

</div>
