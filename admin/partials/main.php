<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       Author URI
 * @since      1.0.0
 *
 * @package    SR_Partner
 * @subpackage SR_Partner/admin/partials
 */
?>
<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<?php
$is_edit = false;
$edit_data ="";
if( isset($_GET['edit']) && is_numeric($_GET['edit']) ) {

    $edit_data = $this->_db->get_shortcode_object($_GET['edit']);
    if (!empty($edit_data)) {
        $is_edit =true;
        $edit_data = unserialize($edit_data->attributes);
    }
}
?>
<?php if (is_numeric(get_option(SR_PARTNER_INSTALLED_TO_ALL))) {
    $temp_id =get_option(SR_PARTNER_INSTALLED_TO_ALL);
    delete_option(SR_PARTNER_INSTALLED_TO_ALL);
?>
    <div id="sr-partner-alert-wrapper">
        <div id="sr-partner-alert-content">
            <p align="center">
                <?php echo __("Audit Form Installed in All Pages.","sr-partner");?>
            </p>
            <div align="center">
                <button type="button" class="dops-button alert-continue"><?php echo __("Continue","sr-partner");?></button>&nbsp;
                <a href="<?php echo admin_url('admin.php?page=sr-partner')?>&edit=<?php echo $temp_id;?>#setup-audit-widget"  class="dops-button is-primary "><?php echo __("Customize","sr-partner");?></a>
            </div>
        </div>
    </div>
<?php
}
?>

<?php

$dashboard_is_active = get_option(SR_PARTNER_DASHBOARD_STATUS);
$dashboard_is_active = $dashboard_is_active !== false ? $dashboard_is_active : 0;
$dashboard_page_info = unserialize(get_option(SR_PARTNER_DASHBOARD_PAGE_INFO));

$portfolio_is_active = get_option(SR_PARTNER_PORTFOLIO_STATUS);
$portfolio_is_active = $portfolio_is_active !== false ? $portfolio_is_active : 0;
$portfolio_page_info = unserialize(get_option(SR_PARTNER_PORTFOLIO_PAGE_INFO));

$audit_is_active = get_option(PARTNER_PLUGIN_STATUS);
$audit_is_active = $audit_is_active !== false ? $audit_is_active : 0;

$lead_is_active = get_option(SR_PARTNER_LEAD_STATUS);
$lead_is_active = $lead_is_active !== false ? $lead_is_active : 0;

$plugin_key = $this->_helper->get_key();
?>

<?php
    if (isset($_SESSION['sr_partner_update_message'])) {
        $message = $_SESSION['sr_partner_update_message'];
        unset($_SESSION['sr_partner_update_message']);
        ?>

        <div id="sr-partner-alert-wrapper">
            <div id="sr-partner-alert-content">
                <p align="left">
                    <?php echo $message;?>
                </p>
                <div align="right">
                    <button type="button" class="dops-button is-primary delete-alert-continue">&nbsp;&nbsp;<?php echo __("Ok","sr-partner");?>&nbsp;&nbsp;</button>
                </div>
            </div>
        </div>

        <?php
    }

?>
<form id="form-partner-p" method="post" action="<?php add_query_arg(array(),esc_url($_SERVER['REQUEST_URI'])) ?>" data-url="<?php echo esc_url($_SERVER['REQUEST_URI']) ?>">

<div class="sr-partner-plugin-container">
    <div class="sr-partner-masthead">
        <div class="sr-partner-masthead-container" align="left">
        </div>
    </div>
    <?php
        $messages = get_option(SR_PARTNER_ALERT_MESSAGES);
        if ($messages !== false) {
            $messages   = unserialize($messages);

            if (is_array($messages) && !empty($messages)) {
                ?>
                <div class="global-notices">
                <?php
                foreach ($messages as $key => $message)
                {
                    if (isset($message['message']) && !empty($message['message']) && isset($message['status']) && !empty($message['status']) && isset($message['auto_close']) )
                    {
                    ?>
                        <div class="dops-notice is-<?php echo $message['status'] ;?> <?php echo ($message['auto_close'] == 1 ) ? 'dops-notice-close' : '' ;?>">
                            <div class="dops-notice__content">
                                <div class="dops-notice__text">
                                    <?php echo $message['message'];?>
                                </div>
                            </div>
                            <div class="sr-partner-alert-close" align="right" id=""></div>
                        </div>
                    <?php
                    }
                }
                ?>
                </div>
                <?php
            }

           delete_option(SR_PARTNER_ALERT_MESSAGES);
        }
    ?>


    <div class="sr-partner-body">
        <div class="sr-partner-white" align="left">
        </div>
        <div class="sr-partner-logo" align="left">
            <img src="https://www.seoreseller.com/wp-content/uploads/2014/08/logo_seoreseller21.png">
        </div>
        <div class="sr-partner-nav-upper" align="right">
            <a target="_blank" href="<?php echo SR_PARTNER_ACCOUNT_URL;?>/account-settings/dashboard?from=wporg"><?php echo __("Agency Settings","sr-partner");?></a> |
            <a target="_blank" href="<?php echo SR_PARTNER_ACCOUNT_URL;?>/crm?from=wporg"><?php echo __("Open CRM","sr-partner");?></a> |
            <a target="_blank" href="<?php echo SR_PARTNER_ACCOUNT_URL;?>/store?from=wporg"><?php echo __("Visit Store","sr-partner");?></a>&nbsp;&nbsp;
        </div>

        <?php if (empty($plugin_key)) {
            $temp_token = isset($_POST['sr-partner-form-activate-token']) ? $_POST['sr-partner-form-activate-token'] : '';
        ?>
        <!-- start if plugin has no token save -->
        <div class="page-plugin-no-key">
            <div class="sr-partner-form-settings-card">
                <div class="dops-card dops-section-header is-compact">
                    <div class="dops-section-header__label">
                        <span class="dops-section-header__label-text">
                            <?php echo __("SEOReseller Agency Token","sr-partner");?>
                            <a href="<?php echo SR_PARTNER_HELPCENTER_URL;?>/partner-tools?from=wporg" target="_blank"> <span class="dashicons dashicons-editor-help"></span></a>
                        </span>

                    </div>
                    <div class="dops-section-header__actions">
                    </div>
                </div>
                <div class="sr-partner-form-settings-group">
                    <div class="dops-card sr-partner-form-has-child">
                        <fieldset class="sr-partner-form-fieldset">
                            <label class="sr-partner-form-label">
                                <legend class="sr-partner-form-legend sr-partner-w15p"><?php echo __("Token","sr-partner");?></legend>
                                <input type="text" name="sr-partner-form-activate-token" class="dops-text-input sr-partner-w80p" value="<?php echo $temp_token;?>" placeholder="<?php echo __("Please enter SEOReseller Agency Token","sr-partner");?>" />
                            </label>
                        </fieldset>
                        <div class="sr-partner-form-spacer"></div>
                        <p class="sr-partner-support-card__description">
                            <?php echo __("This is the unique key that we use to connect your SEOReseller account to your white label Partner plugin.","sr-partner");?>
                            <?php
                                $anchor_open    = '<a target="_blank" class="sr-partner-underline" href="'.SR_PARTNER_ACCOUNT_URL.'/account-settings/dashboard?from=wporg#get_plugin_token">';
                                $anchor_close   = '</a>';
                                echo sprintf( __("You can find your token %shere%s."), $anchor_open, $anchor_close);
                            ?>
                        </p>
                        <div class="sr-partner-form-spacer"></div>
                        <p class="sr-partner-support-card__description">
                            <a target="_blank" class="sr-partner-underline" href="<?php echo SR_PARTNER_ACCOUNT_URL;?>/sign-up?from=wporg">
                                <?php echo __("No account? Create one for free","sr-partner");?>
                            </a>
                        </p>
                    </div>
                    <div class="dops-card sr-partner-footer">
                        <div class="sr-partner-fl">

                        </div>
                        <div class="sr-partner-fr">
                            <input type="submit" name="sr-partner-form-activate-plugin-submit" id="sr-partner-form-activate-plugin-submit" class="dops-button is-compact is-primary" value="<?php echo __("Activate Plugin","sr-partner");?>" >
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- end if plugin has no token save -->
        <?php  } else { /*else plugin key is active*/ ?>


        <div class="dops-navigation">
            <div class="dops-section-nav has-pinned-items">
                <div class="dops-section-nav__panel">
                    <div class="dops--section-nav-group">
                        <div class="dops--section-nav-tabs">
                            <ul class="dops-section-nav-tabs__list">
                                <li class="dops-section-nav-tab " data-hash="plugin-features">
                                    <a href="<?php echo admin_url('admin.php?page=sr-partner#plugin-features') ?>" class="dops-section-nav-tab__link ">
                                        <span class="dops-section-nav-tab__text">
                                            <?php echo __("Plugin Features","sr-partner");?>
                                        </span>
                                    </a>
                                </li>
                                <li class="dops-section-nav-tab" data-hash="setup-audit-widget" <?php echo ($audit_is_active== true) ? '':'disabled="disabled"';?> >
                                    <a href="<?php echo admin_url('admin.php?page=sr-partner#setup-audit-widget') ?>" id="setup-audit-widget-nav-link" class="dops-section-nav-tab__link " <?php echo ($audit_is_active== true) ? '':'disabled="disabled"';?> >
                                        <span class="dops-section-nav-tab__text">
                                            <?php echo __("Setup Audit Widget","sr-partner");?>
                                        </span>
                                    </a>
                                </li>
                                <li class="dops-section-nav-tab" data-hash="setup-web-portfolio" <?php echo ($portfolio_is_active== true) ? '':'disabled="disabled"';?>>
                                    <a href="<?php echo admin_url('admin.php?page=sr-partner#setup-web-portfolio') ?>" id="setup-web-portfolio-nav-link" <?php echo ($portfolio_is_active== true) ? '':'disabled="disabled"';?> class="dops-section-nav-tab__link ">
                                        <span class="dops-section-nav-tab__text">
                                            <?php echo __("Setup Web Design Portfolio", "sr-partner");?>
                                        </span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="sr-partner-nav" align="right">
                <input type="submit" name="sr-partner-form-dashboard-submit" id="sr-partner-form-dashboard-submit" class="dops-button is-compact is-primary" value="<?php echo __("Save Changes","sr-partner");?>" >&nbsp;&nbsp;
            </div>
        </div>


        <!-- start page 1 -->
        <!-- start page 1 -->
        <!-- start page 1 -->
        <div id="page-plugin-features" class="page sr-partner-display-none">

            <!-- start Agency Token-->
            <div class="sr-partner-form-settings-card">
                <div class="dops-card dops-section-header is-compact">
                    <div class="dops-section-header__label">
                        <span class="dops-section-header__label-text">
                                <?php echo __("SEOReseller Agency Token","sr-partner");?>
                                <a href="<?php echo SR_PARTNER_HELPCENTER_URL;?>/partner-tools?from=wporg" target="_blank"> <span class="dashicons dashicons-editor-help"></span></a>
                        </span>

                    </div>
                    <div class="dops-section-header__actions">
                    </div>
                </div>
                <div class="sr-partner-form-settings-group">
                    <div class="dops-card sr-partner-form-has-child">
                        <fieldset class="sr-partner-form-fieldset">
                            <label class="sr-partner-form-label">
                                <legend class="sr-partner-form-legend sr-partner-w15p"><?php echo __("Token","sr-partner");?></legend>
                                <input type="text" readonly="true" class="dops-text-input sr-partner-w80p" value="<?php echo $plugin_key;?>" disabled="" />
                            </label>
                        </fieldset>
                        <div class="sr-partner-form-spacer"></div>
                        <p class="sr-partner-support-card__description">
                        <?php echo __("This is the unique key that we use to connect your SEOReseller account to your white label Partner plugin.","sr-partner");?>
                        </p>
                    </div>
                </div>
            </div>
            <!-- end Agency Token-->


            <!-- start White Label Dashboard-->
            <div class="sr-partner-form-settings-card">
                <div class="dops-card dops-section-header is-compact">
                    <div class="dops-section-header__label">
                        <span class="dops-section-header__label-text">
                            <?php echo __("White Label Dashboard","sr-partner");?>
                            <a href="<?php echo SR_PARTNER_HELPCENTER_URL;?>/partner-tools?from=wporg" target="_blank"> <span class="dashicons dashicons-editor-help"></span></a>
                        </span>
                        <div class="sr-partner-header-right ">
                            <?php echo __("Activate Dashboard Feature","sr-partner");?>&nbsp;&nbsp;
                        </div>
                    </div>
                    <div class="dops-section-header__actions ">
                        <label class="form-toggle__label" >
                            <input class="form-toggle is-compact" type="checkbox" name="enable_dashboard_status" value="<?php echo $dashboard_is_active; ?>"
                              id="switch-activate-dashboard-feature" <?php echo ($dashboard_is_active== true) ? 'checked="checked"':'';?>>
                            <label class="form-toggle__label"  >
                                <span class="form-toggle__switch sr-partner-form-toggle__switch" role="checkbox" data-id="#switch-activate-dashboard-feature" data-target="dashboard-page" >
                                </span>
                                <span class="form-toggle__label-content" ></span>
                            </label>
                        </label>
                    </div>
                </div>
                <div class="sr-partner-form-settings-group">
                    <div class="dops-card sr-partner-form-has-child">
                        <fieldset class="sr-partner-form-fieldset">
                            <label class="sr-partner-form-label">
                                <legend class="sr-partner-form-legend sr-partner-w15p"><?php echo __("Page Title","sr-partner");?></legend>
                                <input type="hidden" name="dashboard_page_id" value="<?php echo $dashboard_page_info['id']; ?>" />
                                <input type="text" <?php echo ($dashboard_is_active== true) ? '':'disabled';?>  name="dashboard-page" id="dashboard-page" placeholder="<?php echo __("Dashboard","sr-partner");?>" class="dops-text-input sr-partner-w80p" value="<?php echo $dashboard_page_info['title']; ?>"  />
                            </label>
                        </fieldset>
                        <div class="sr-partner-form-spacer"></div>
                        <fieldset class="sr-partner-form-fieldset">
                            <label class="sr-partner-form-label">
                                <legend class="sr-partner-form-legend sr-partner-w15p"><?php echo __("Dashboard URL","sr-partner");?></legend>
                                <input type="text" readonly="true" class="dops-text-input sr-partner-w80p" value="<?php echo $dashboard_page_info['permalink']; ?>" disabled="" placeholder="<?php echo __("Add Dashboard Page Name first","sr-partner");?>" id="dashboard-permalink" />
                                <a href="<?php echo $dashboard_page_info['permalink']; ?>" class="sr-partner-fl"  target="_BLANK"><span class="dashicons dashicons-migrate"></span></a>
                            </label>
                        </fieldset>
                        <div class="sr-partner-form-spacer"></div>
                        <p class="sr-partner-support-card__description">
                            <?php
                                $anchor_open    = '<a target="_blank" class="sr-partner-underline" href="'.SR_PARTNER_HELPCENTER_URL.'/partner-tools?from=wporg">';
                                $anchor_close   = '</a>';
                                echo sprintf( __("This is your new white label dashboard where your clients can log in to see their campaign. Learn more how to use it %shere%s."), $anchor_open, $anchor_close);
                            ?>
                        </p>
                    </div>
                </div>
            </div>
            <!-- end White Label Dashboard-->



            <!-- start Web Design Portfolio-->
            <div class="sr-partner-form-settings-card">
                <div class="dops-card dops-section-header is-compact">
                    <div class="dops-section-header__label">
                        <span class="dops-section-header__label-text">
                            <?php echo __("Web Design Portfolio","sr-partner");?>
                            <a href="<?php echo SR_PARTNER_HELPCENTER_URL;?>/white-label-features/white-label-web-design-portfolio?from=wporg" target="_blank"> <span class="dashicons dashicons-editor-help"></span></a>
                        </span>
                        <div class="sr-partner-header-right">
                            <?php echo __("Activate Portfolio Feature","sr-partner");?>&nbsp;&nbsp;
                        </div>
                    </div>
                    <div class="dops-section-header__actions">
                        <label class="form-toggle__label" >
                            <input class="form-toggle is-compact" type="checkbox" name="enable_portfolio_status" value="<?php echo $portfolio_is_active; ?>"
                              id="switch-web-design-portfolio" <?php echo ($portfolio_is_active== true) ? 'checked="checked"':'';?>>
                            <label class="form-toggle__label"  >
                                <span class="form-toggle__switch sr-partner-form-toggle__switch" role="checkbox" data-id="#switch-web-design-portfolio" data-target="portfolio-page">
                                </span>
                                <span class="form-toggle__label-content" ></span>
                            </label>
                        </label>
                    </div>
                </div>
                <div class="sr-partner-form-settings-group">
                    <div class="dops-card sr-partner-form-has-child">
                        <fieldset class="sr-partner-form-fieldset">
                            <label class="sr-partner-form-label">
                                <legend class="sr-partner-form-legend sr-partner-w15p"><?php echo __("Page Title","sr-partner");?></legend>
                                <input type="hidden" name="portfolio_page_id" value="<?php echo $portfolio_page_info['id']; ?>" />
                                <input type="text" <?php echo ($portfolio_is_active== true) ? '':'disabled';?> id="portfolio-page" name="portfolio-page" class="dops-text-input sr-partner-w80p" placeholder="<?php echo __("Web Portfolio","sr-partner");?>" value="<?php echo $portfolio_page_info['title']; ?>" />
                            </label>
                        </fieldset>
                        <div class="sr-partner-form-spacer"></div>
                        <fieldset class="sr-partner-form-fieldset">
                            <label class="sr-partner-form-label">
                                <legend class="sr-partner-form-legend sr-partner-w15p"><?php echo __("Portfolio URL","sr-partner");?></legend>
                                <input type="text" readonly="true" class="dops-text-input sr-partner-w80p" placeholder="<?php echo __("Add Portfolio Page Name first","sr-partner");?>"  value="<?php echo $portfolio_page_info['permalink']; ?>" disabled="" id="portfolio-permalink" />
                                <a href="<?php echo $portfolio_page_info['permalink']; ?>" class="sr-partner-fl"  target="_BLANK"><span class="dashicons dashicons-migrate"></span></a>
                            </label>
                        </fieldset>
                        <div class="sr-partner-form-spacer"></div>
                        <p class="sr-partner-support-card__description" style="margin-top: 20px;">
                            <?php
                            $anchor_open    = '<a class="sr-partner-underline goto-web-portfolio-builder" href="#">';
                            $anchor_close   = '</a>';
                            echo sprintf( __("Use shortcodes to have this inside your page. Set it up %shere%s."), $anchor_open, $anchor_close);
                            ?>
                        </p>
                        <p class="sr-partner-support-card__description">
                            <?php
                                $anchor_open    = '<a target="_blank" class="sr-partner-underline" href="'.SR_PARTNER_HELPCENTER_URL.'/white-label-features/white-label-web-design-portfolio?from=wporg">';
                                $anchor_close   = '</a>';
                                echo sprintf( __("This is a web design portfolio page on your domain, with your brand. Use it to sell more web design products. Learn more about it %shere%s."), $anchor_open, $anchor_close);
                            ?>
                        </p>
                    </div>
                </div>
            </div>
            <!-- end Web Design Portfolio-->



            <!-- start Audit Widget-->
            <div class="sr-partner-form-settings-card">
                <div class="dops-card dops-section-header is-compact">
                    <div class="dops-section-header__label">
                        <span class="dops-section-header__label-text">
                            <?php echo __("Audit Widget","sr-partner");?>
                            <a href="<?php echo SR_PARTNER_HELPCENTER_URL;?>/partner-tools/partner-on-site-audit-tool/set-up-your-on-site-audit-widget?from=wporg" target="_blank"> <span class="dashicons dashicons-editor-help"></span></a>
                            <b style="color:#f27997;font-size: 9px"><?php echo __("BETA","sr-partner");?></b>
                        </span>
                        <div class="sr-partner-header-right ">
                            <?php echo __("Activate Audit Widget Feature","sr-partner");?>&nbsp;&nbsp;
                        </div>
                    </div>
                    <div class="dops-section-header__actions ">
                        <label class="form-toggle__label" >
                            <input class="form-toggle is-compact" type="checkbox"  name="enable_audit_status" value="<?php echo $audit_is_active; ?>"
                            id="switch-audit-widget" <?php echo ($audit_is_active== true) ? 'checked="checked"':'';?>>
                            <label class="form-toggle__label"  >
                                <span class="form-toggle__switch sr-partner-form-toggle__switch" role="checkbox" data-id="#switch-audit-widget" data-target="audit-page">
                                </span>
                                <span class="form-toggle__label-content" ></span>
                            </label>
                        </label>
                    </div>
                </div>
                <div class="sr-partner-form-settings-group">
                    <div class="dops-card sr-partner-form-has-child">
                        <p class="sr-partner-support-card__description">
                            <?php echo __("The Web Audit feature creates forms that you can install on different pages of your site and provide free audits to your site visitors. You can embed it across your website and capture more leads while offering more value to your visitors. To start, you need to set it up in the Setup Audit Widget tab.","sr-partner");?>
                        </p>
                        <div class="sr-partner-form-spacer"></div>
                        <div class="sr-partner-form-spacer"></div>
                        <div>
                            <label class="sr-partner-form-legend sr-partner-w20p"><?php echo __("Setup Audit Form","sr-partner");?></label>
                            <select class="sr-partner-w40p sr-partner-fl" id="setup-audit-form-type" name="setup-audit-form-type" <?php echo ($audit_is_active== true) ? '':'disabled="disabled"';?>>
                                <?php
                                $selected = $this->get_post_value('sr_partner_setup_type',  $this->get_post_value_from_data($edit_data, 'setup_type', ''));
                                foreach ($this->get_form_types(false) as $k => $v) {
                                    if ($selected == $k) {
                                    ?>
                                        <option value="<?php echo $k;?>" selected="selected"><?php echo $v;?></option>
                                    <?php
                                    }
                                    else {
                                    ?>
                                        <option value="<?php echo $k;?>"><?php echo $v;?></option>
                                    <?php
                                    }
                                }
                                ?>
                            </select>
                            <div class="sr-partner-w35p sr-partner-fr" align="right">
                                <input <?php echo ($audit_is_active== true) ? '':'disabled="disabled"';?> type="submit" name="sr-partner-form-install-to-all-submit" class="btn-install-on dops-button is-compact is-primary" value="<?php echo __("Install to All Pages","sr-partner");?>" title="<?php echo __("Only Available for Notification Bar and Takeover Forms","sr-partner");?>">
                                <button <?php echo ($audit_is_active== true) ? '':'disabled="disabled"';?> type="button" class="dops-button is-compact goto-audit-builder"><?php echo __("Customize","sr-partner");?></button>
                            </div>
                        </div>
                    </div>
                    <div class="dops-card sr-partner-footer">
                        <div class="sr-partner-fl">
                            <div class="sr-partner-icon-rocket sr-partner-fl" ></div><span class="sr-partner-fontsize-11">&nbsp;<?php echo __("Turn Your Site Visitors Into Paying Clients","sr-partner");?></span>
                        </div>
                        <div class="sr-partner-fr">
                            <button <?php echo ($audit_is_active== true) ? '':'disabled="disabled"';?> type="button" class="dops-button is-compact is-primary goto-audit-builder"><?php echo __("Setup Audit Widget","sr-partner");?></button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end Audit Widget-->


            <!-- start Lead Tracker-->
            <div class="sr-partner-form-settings-card">
                <div class="dops-card dops-section-header is-compact">
                    <div class="dops-section-header__label">
                        <span class="dops-section-header__label-text">
                            <?php echo __("Lead Tracker","sr-partner");?>
                            <a href="<?php echo SR_PARTNER_HELPCENTER_URL;?>/partner-tools/partner-crm/get-new-leads-for-your-agency?from=wporg" target="_blank"> <span class="dashicons dashicons-editor-help"></span></a>
                        </span>
                        <div class="sr-partner-header-right ">
                            <?php echo __("Activate Lead Tracker Feature","sr-partner");?>&nbsp;&nbsp;
                        </div>
                    </div>
                    <div class="dops-section-header__actions ">
                        <label class="form-toggle__label" >
                            <input class="form-toggle is-compact" type="checkbox"  name="enable_lead_status" value="<?php echo $lead_is_active; ?>"
                            id="switch-lead-widget" <?php echo ($lead_is_active== true) ? 'checked="checked"':'';?>>
                            <label class="form-toggle__label"  >
                                <span class="form-toggle__switch sr-partner-form-toggle__switch" role="checkbox" data-id="#switch-lead-widget" data-target="lead-setup-button">
                                </span>
                                <span class="form-toggle__label-content" ></span>
                            </label>
                        </label>
                    </div>
                </div>
                <div class="sr-partner-form-settings-group">
                    <div class="dops-card sr-partner-form-has-child">
                        <p class="sr-partner-support-card__description">
                        <?php
                            $anchor_open_1  = '<a target="_blank" class="sr-partner-underline" href="'.SR_PARTNER_ACCOUNT_URL.'/crm?from=wporg" >';
                            $anchor_open_2  = '<a target="_blank" class="sr-partner-underline" href="'.SR_PARTNER_HELPCENTER_URL.'/partner-tools/partner-crm/get-new-leads-for-your-agency?from=wporg" >';
                            $anchor_close   = '</a>';
                            echo sprintf( __("The Lead Tracker feature allows you to track all leads coming into your website through the different contact forms on the site. Each lead is sent to your CRM. Access your leads %shere%s. Learn more about Lead Tracker %shere%s"), $anchor_open_1, $anchor_close, $anchor_open_2, $anchor_close);
                        ?>

                        </p>
                    </div>
                    <div class="dops-card sr-partner-footer">
                        <div class="sr-partner-fl">
                            <span class="dashicons dashicons-admin-generic"></span><span class="sr-partner-fontsize-11">&nbsp;<?php echo __("Manage your Sources by Defining Each Contact Form","sr-partner");?></span>
                        </div>
                        <div class="sr-partner-fr sr-partner-display-none">
                            <button <?php echo ($lead_is_active== true) ? '':'disabled="disabled"';?> type="button" class="dops-button is-compact is-primary " id="lead-setup-button"><?php echo __("Setup Sources","sr-partner");?></button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end Lead Tracker-->


        </div>
        <!-- end page 1 -->
        <!-- end page 1 -->
        <!-- end page 1 -->



        <!-- start page 2 -->
        <!-- start page 2 -->
        <!-- start page 2 -->
        <div id="page-setup-audit-widget" class="page sr-partner-display-none">

            <!-- start Audit Form Builder-->
            <!-- start Audit Form Builder-->
            <!-- start Audit Form Builder-->
            <div class="sr-partner-form-settings-card">
                <div class="dops-card dops-section-header is-compact">
                    <div class="dops-section-header__label">
                        <span class="dops-section-header__label-text">
                            <?php echo __("Audit Form Builder","sr-partner");?> - <?php echo ($is_edit) ? __("Edit","sr-partner") : __("Add","sr-partner");?>
                        </span>
                    </div>
                </div>
                <div class="sr-partner-form-settings-group">
                    <div class="dops-card sr-partner-form-has-child">
                        <p class="sr-partner-support-card__description sr-partner-margin-top--15">
                            <?php echo __("Install an Audit Form to your site by creating different Audit Forms. You can select the type, customize colors and text, and then generate a shortcode you can use to install the audit form on a specific web page.","sr-partner");?>
                        </p>
                        <div class="sr-partner-form-spacer"></div>
                        <p class="sr-partner-support-card__description">
                            <?php
                                $anchor_open  = '<a target="_blank" class="sr-partner-underline" href="'.SR_PARTNER_HELPCENTER_URL.'/partner-tools/partner-on-site-audit-tool/set-up-your-on-site-audit-widget?from=wporg" >';
                                $anchor_close   = '</a>';
                                echo sprintf( __("Click %shere%s to learn more about how to install Audit Forms."), $anchor_open, $anchor_close);
                            ?>
                        </p>
                        <br>

                        <fieldset class="sr-partner-form-fieldset">
                            <label class="sr-partner-form-label">
                                <legend class="sr-partner-form-legend sr-partner-w15p"><?php echo __("Form Name","sr-partner");?></legend>
                                <input  type="text"
                                        class="dops-text-input sr-partner-w50p"
                                        placeholder="<?php echo __("Web Audit General Form","sr-partner");?>"
                                        name="sr_partner_form_name"
                                        id="sr_partner_form_name"
                                        value="<?php echo $this->get_post_value('sr_partner_form_name', $this->get_post_value_from_data($edit_data, 'form_name', __("Web Audit General Form","sr-partner"))) ?>" >
                            </label>
                            <div class="sr-partner-form-spacer"></div>
                            <p class="sr-partner-support-card__description sr-partner-cl">
                                <?php echo __("Name your form. We'll use this to identify the different forms you create.","sr-partner");?>
                            </p>
                        </fieldset>

                        <div class="sr-partner-form-spacer"></div>
                        <div class="sr-partner-form-spacer"></div>
                        <fieldset class="sr-partner-form-fieldset">
                            <label class="sr-partner-form-label">
                                <legend class="sr-partner-form-legend sr-partner-w15p"><?php echo __("Form Type","sr-partner");?></legend>
                                <select class="sr-partner-w50p sr-partner-fl " id="sr_partner_type" name="sr_partner_type">
                                    <?php
                                    $selected = $this->get_post_value('sr_partner_type', $this->get_post_value_from_data($edit_data, 'type', 'large'));
                                    foreach ($this->get_form_types(true) as $k => $v) {
                                        if ($selected == $k) {
                                        ?>
                                            <option value="<?php echo $k;?>" selected="selected"><?php echo $v;?></option>
                                        <?php
                                        }
                                        else {
                                        ?>
                                            <option value="<?php echo $k;?>"><?php echo $v;?></option>
                                        <?php
                                        }
                                    }
                                    ?>
                                </select>
                            </label>
                            <div class="sr-partner-fl">
                                <?php
                                    $sr_partner_install_to_all_pages = get_option('sr_partner_install_to_all_pages');
                                    $checked = ($is_edit && $_GET['edit'] == $sr_partner_install_to_all_pages) ? 'checked="checked"' : '';
                                 ?>
                                 <div id="wrapper-install-to-all-pages">
                                    <div class="dops-section-header__actions sr-partner-fl sr-partner-audit-form-toggle" >
                                        <label class="form-toggle__label" >
                                            <input class="form-toggle is-compact" type="checkbox"  readonly="" id="switch-install-to-all-pages" value="on" name="sr_partner_install_to_all_pages" <?php echo $checked;?>>
                                            <label class="form-toggle__label"  >
                                                <span class="form-toggle__switch sr-partner-form-toggle__switch" role="checkbox" data-id="#switch-install-to-all-pages">
                                                </span>
                                                <span class="form-toggle__label-content" ></span>
                                            </label>
                                        </label>
                                    </div>
                                    <p class="sr-partner-support-card__description sr-partner-fl sr-partner-margin-top-4 sr-partner-margin-left--4" for="switch-install-to-all-pages">
                                    <?php echo __("Install to All Pages","sr-partner");?>
                                    </p>
                                </div>
                            </div>
                            <div class="sr-partner-form-spacer"></div>
                            <p class="sr-partner-support-card__description sr-partner-cl">
                                <?php echo __("You can generate different types of form based on the look or the trigger. Customize it below then generate.","sr-partner");?>
                            </p>
                        </fieldset>
                    </div>
                </div>
            </div>
            <!-- end Audit Form Builder-->
            <!-- end Audit Form Builder-->
            <!-- end Audit Form Builder-->




            <!-- start Customize Audit Form -->
            <!-- start Customize Audit Form -->
            <!-- start Customize Audit Form -->
            <div class="sr-partner-form-settings-card">
                <div class="dops-card dops-section-header is-compact">
                    <div class="dops-section-header__label">
                        <span class="dops-section-header__label-text">
                            <?php echo __("Customize Audit Form","sr-partner");?> - <?php echo ($is_edit) ? __("Edit","sr-partner") : __("Add","sr-partner");?>
                        </span>
                    </div>
                    <div class="sr-partner-icon-arrow-up sr-partner-fr sr-partner-icon-arrow-toggle" data-id="#arrow-toggle-customize-audit-forms"  ></div>
                </div>

                <div class="sr-partner-form-settings-group " id="arrow-toggle-customize-audit-forms">

                    <div class="dops-card ">
                        <p class="sr-partner-support-card__description sr-partner-cl sr-partner-margin-top--15">
                            <?php echo __("Customize your form based on your colors and the text you want for each form.","sr-partner");?>
                        </p>
                        <div class="sr-partner-form-spacer"></div>

                        <!-- Start Builder Preview -->
                        <!-- Start Builder Preview -->
                        <!-- Start Builder Preview -->
                        <div class="sr-partner-w512 sr-partner-fl">
                            <?php
                            require_once plugin_dir_path( __FILE__ ). 'form-preview.php';
                            ?>
                        </div>
                        <!-- End Builder Preview -->
                        <!-- End Builder Preview -->
                        <!-- End Builder Preview -->


                        <!-- Start Builder Form -->
                        <!-- Start Builder Form -->
                        <!-- Start Builder Form -->
                        <div class="sr-partner-w382 sr-partner-fl">
                            <?php
                            require_once plugin_dir_path( __FILE__ ). 'form-builder.php';
                            ?>
                        </div>

                        <!-- End Builder Form -->
                        <!-- End Builder Form -->
                        <!-- End Builder Form -->

                    </div>


                    <div class="dops-card sr-partner-footer">
                        <div class="sr-partner-fl">
                            <div class="sr-partner-icon-parallel sr-partner-fl" ></div><span class="sr-partner-fontsize-11">&nbsp;<?php echo __("Install Audit Forms To Your Article or Pages","sr-partner");?></span>
                        </div>
                        <div class="sr-partner-fr">
                            <?php
                            if ( isset($is_edit) && $is_edit=== true ) {
                            ?>
                                <input type="submit" name="sr-partner-form-update-submit" id="submit" class="dops-button is-compact is-primary" value="<?php echo __("Update Shortcode","sr-partner");?>">
                            <?php
                            } else {
                            ?>
                                <input type="submit" name="sr-partner-form-generate-submit" id="submit" class="dops-button is-compact is-primary" value="<?php echo __("Generate Shortcode","sr-partner");?>">
                            <?php
                            }
                            ?>
                        </div>
                    </div>
                </div>

            </div>
            <!-- end Customize Audit Form -->
            <!-- end Customize Audit Form -->
            <!-- end Customize Audit Form -->


            <!-- start Active Audit Form -->
            <!-- start Active Audit Form -->
            <!-- start Active Audit Form -->
            <div class="sr-partner-form-settings-card">
                <div class="dops-card dops-section-header is-compact">
                    <div class="dops-section-header__label">
                        <span class="dops-section-header__label-text">
                            <?php echo __("Active Audit Forms","sr-partner");?>
                        </span>
                    </div>
                    <div class="sr-partner-icon-arrow-up sr-partner-fr sr-partner-icon-arrow-toggle" data-id="#arrow-toggle-active-audit-forms" ></div>
                </div>

                <div class="sr-partner-form-settings-group " id="arrow-toggle-active-audit-forms">

                    <div class="dops-card ">
                        <p class="sr-partner-support-card__description sr-partner-cl sr-partner-margin-top--15">
                            <?php echo __("These are the different forms you have already created.","sr-partner");?>
                        </p>
                        <div class="sr-partner-form-spacer"></div>

                        <table class="sr-partner-table " border="0">
                            <thead>
                                <th >
                                    <?php echo __("Form Name","sr-partner");?>
                                </th>
                                <th align="center" class="sr-partner-width-12per">
                                    <?php echo __("Form Type","sr-partner");?>
                                </th>
                                <th align="center" class="sr-partner-width-18per">
                                    <?php echo __("Installed to All Pages","sr-partner");?>
                                </th>
                                <th align="center" class="sr-partner-width-20per">
                                    <?php echo __("Shortcode","sr-partner");?>
                                </th>
                                <th align="center" class="sr-partner-width-10per">
                                    <?php echo __("Actions","sr-partner");?>
                                </th>
                            </thead>
                            <tbody>
                            <?php
                                $list = $this->_db->get_all_shortcodes();
                                if (is_array($list)) {
                                    foreach ($list as $key => $value) {
                                        ?>
                                        <tr>
                                            <td>
                                                <?php echo  $value['form_name'];?>
                                            </td>
                                            <td align="center">
                                                <?php echo  $value['form_type'];?>
                                            </td>
                                            <td align="center">
                                                <?php echo  ($sr_partner_install_to_all_pages == $value['id']) ? __("Yes","sr-partner") : __("No","sr-partner"); ?>
                                            </td>
                                            <td align="center">
                                                <input type='text' class="dops-text-input sr-partner-width-145 " readonly='readonly' value="[sr-partner-os-audit id=<?php echo $value['id'];?>]" />
                                            </td>
                                            <td align="center">
                                                <a class="sr-partner-underline" href="<?php echo admin_url('admin.php?page=sr-partner')?>&edit=<?php echo $value['id'];?>#setup-audit-widget"><?php echo __("edit","sr-partner");?></a> |
                                                <a class="sr-partner-underline delete-code" href="" data-shortcode="[sr-partner-os-audit id=<?php echo $value['id'];?>]" data-href="<?php echo admin_url('admin.php?page=sr-partner')?>&delete=<?php echo $value['id'];?>#setup-audit-widget""><?php echo __("delete","sr-partner");?></a>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                }
                            ?>
                            </tbody>
                        </table>
                    </div>

                </div>

            </div>
            <!-- end Active Audit Form -->
            <!-- end Active Audit Form -->
            <!-- end Active Audit Form -->
        </div>
        <!-- end page 2 -->
        <!-- end page 2 -->
        <!-- end page 2 -->


        <!-- start page 3 -->
        <div id ="page-setup-web-portfolio" class="page sr-partner-display-none">
            <div class="sr-partner-form-settings-card">
                <div class="dops-card dops-section-header is-compact">
                    <div class="dops-section-header__label">
                    <span class="dops-section-header__label-text">
                        <?php echo __("Web Portfolio Builder", "sr-partner");?>
                    </span>
                    </div>
                </div>
                <div class="sr-partner-form-settings-group">
                    <div class="dops-card sr-partner-form-has-child">
                        <div class="sr-partner-support-card__description sr-partner-margin-top--15">
                            <?php
                                require_once plugin_dir_path( __FILE__ ). 'web-design-portfolio/form.php';
                            ?>
                        </div>
                        <div class="sr-partner-support-card__description sr-partner-margin-top--15">
                            <div class="sr-partner-full sr-partner-fl">
                                <input type="submit"
                                       name="sr-partner-form-generate-code-portfolio"
                                       id="submit-portfolio"
                                       class="dops-button is-compact is-primary float-right" value="<?php echo __("Save Changes","sr-partner");?>">
                            </div>
                        </div>
                        <div class="sr-partner-form-spacer"></div>
                        <div class="sr-partner-support-card__description sr-partner-margin-top--15">
                            <?php
                            require_once plugin_dir_path( __FILE__ ). 'web-design-portfolio/preview.php';
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- start page 3 -->
        <?php  } /*close else plugin key is active*/ ?>

    </div>
</div>
</form>
<script type="text/javascript">
var sr_partner_localization = {
    yes: "<?php echo __("Yes","sr-partner");?>",
    no: "<?php echo __("No","sr-partner");?>",
    are_you_sure_you_want_to_delete: "<?php echo __("Are you sure wou want to delete [code]?","sr-partner");?>",
};
</script>
