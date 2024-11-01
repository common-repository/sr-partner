<?php
$portfolioOption   = get_option(SR_PARTNER_PORTFOLIO_OPTION);
$portfolioOption   = $portfolioOption ? unserialize($portfolioOption) : array();
$attributes        = array();

if (!empty($portfolioOption) && is_array($portfolioOption)) {
    foreach ($portfolioOption as $key => $option) {
        switch ($key) {
            case 'sr_partner_web_portfolio_no_cards_per_row': $attributes[] = 'data-cards-per-row=' . $option; break;
            case 'sr_partner_web_portfolio_mockup_title_color': $attributes[] = 'data-title-color=' . $option; break;
            case 'sr_partner_web_portfolio_button_primary_color': $attributes[] = 'data-button-primary=' . $option; break;
            case 'sr_partner_web_portfolio_button_secondary_color': $attributes[] = 'data-button-secondary=' . $option; break;
            case 'sr_partner_web_portfolio_show_mockup_menu': $attributes[] = 'data-show-mockup-menu=' . $option; break;
        }
    }
}
?>
<div class="">
    <div class="form-field colorpicker-wrap w99p">
        <label><?php echo __("Preview", "sr-partner");?></label>
    </div>
    <div style="width: 100%; min-height: 600px;overflow-x: auto;">
        <div class="sr-partner-portfolio" <?php echo  implode(' ', $attributes); ?>></div>
    </div>
</div>