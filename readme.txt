=== SEOReseller Partner Plugin ===
Contributors: itamarg
Tags: digital marketing agency, marketing, agency, seo, portfolio, lead tracking, seo audit, seo widget
Requires at least: 4.6
Tested up to: 5.3.2
Stable tag: 1.3.15
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

SEOReseller's suite of tools for building, managing, and growing your digital marketing agency.

== Description ==

Build and Grow your Digital Marketing Agency with SEOReseller’s comprehensive suite of tools available in an easy-to-install WordPress plugin.

= Lead Generation and Tracking =
Generate leads from your site with a free SEO audit widget - visitors just enter their URL & email. The WordPress plugin connects to your CRM and creates new entries with each lead from the widget or Contact Forms.

= On-demand Web Design Portfolio =
Build your own Web Design portfolio under your domain in minutes. Upon installation, you can use our library of designs as your portfolio and start selling Web Development.

= White Label Dashboard =
Use your dashboard to show the advanced SEO and analytics work you're doing for your clients. It's elegantly designed, fully customizable, and free!

== Installation ==

= Manual Installation =
Install Partner via the plugin directory, or upload the files manually to your server and follow the on-screen instructions. If you need additional help, read our detailed instructions [here](http://helpcenter.seoreseller.com/getting-started/setting-up-your-white-label-dashboard/set-up-your-white-label-dashboard-with-our-wordpress-plugin?from=wporg).

== Frequently Asked Questions ==

= Is this plugin compatible with other WordPress plugins? =
We have rigorously tested the WordPress plugin with a lot of major plugins available online but we cannot guarantee that it won’t cause problems with less popular plugins. There are too many WordPress plugins available on the market. We need your help to make sure it works with anything you use. If you see any issues with a plugin, send us a message and we'll fix it.

= Where do I view all the leads I generated from the On-Site SEO Audit Widget and the Lead Tracker? =
You have to login to your dashboard, and then check the CRM. Or click this [link](https://account.seoreseller.com/crm?from=wporg).

= Can I add additional mockups to the portfolio? =
At the moment, we have a defined list of mockups in the Portfolio and are not taking in new designs for it.

= Can I add the white label domain under my subdomain instead? =
Yes, but you have to use a different method in installing your subdomain. You can check this [article](http://helpcenter.seoreseller.com/getting-started/setting-up-your-white-label-dashboard/setup-your-dashboard-direct-your-domain-to-our-server?from=wporg) to see how to do it.

== Screenshots ==

1. A Better Way to Grow Your Agency: Our New SEOReseller WordPress Plugin + our New CRM gives you everything you need to start your agency, generate leads, and close prospects under one white label platform.
2. Generate Leads from Your Site: Generate leads via the new on-site SEO Audit Widget or through the Lead Tracker and see all your leads in your SEOReseller CRM.

== Changelog ==
= 1.3.15 =

* Release date: July 15, 2020

**Enhancements**
* Filter spam

= 1.3.14 =

* Release date: March 05, 2020

**Enhancements**
* Tested the plugin to work up to WP version 5.3.2
* Enhance Website Portfolio settings and display

= 1.3.13 =

* Release date: November 08, 2019

**Enhancements**
* Tested the plugin to work up to WP version 5.2.4

= 1.3.12 =

* Release date: December 14, 2018

**Bug fixes**
* Fixed web audit plugin being case sensitive.

= 1.3.11 =

* Release date: February 23, 2018

**Bug fixes**
* Fixed the JS and CSS for toggle in Safari.

= 1.3.10 =

* Release date: February 13, 2018

**Enhancements**
* Moved site availability checking from plugin code to api.

**Bug fixes**
* Removed Check for Updates button in Plugin Admin Dashboard.

= 1.2.9 =

* Release date: January 19, 2018

**Bug fixes**
* Fixed issue on declaring PHP arrays to cater lower PHP versions.

= 1.2.8 =

* Release date: November 22, 2017

**Enhancements**
* Blocked crawlers from being able to see whitelabel pages.

= 1.1.8 =

* Release date: October 24, 2017

**Bug fixes**
* Changed declaring PHP arrays from short syntax '[]' to 'array()'.
* Added 'IF EXISTS' in MySql query when dropping shor_codes table upon plugin deletion.
* Updated plugin minimum PHP version required from 5.0 to 5.2.4.
* Updated the plugin installation instructions link.

= 1.1.7 =

* Release date: October 09, 2017

**Bug fixes**
* Fixed table header class affecting other pages of WP admin.

= 1.1.6 =

* Release date: October 03, 2017

**Bug fixes**
* Fixed wrong html attribute to call to action button.

= 1.1.5 =

* Release date: September 22, 2017

**Bug fixes**
* Fixed request timeouts on some cases when using web audit widget.

= 1.1.4 =

* Release date: September 07, 2017

**Bug fixes**
* Removed any additional spaces when saving the token.

= 1.1.3 =

* Release date: August 22, 2017

**Bug fixes**
* Added jQuery.noConflict() to admin js to prevent issues with other js library.
* Removed jquery library calling outside of WP.
* Replaced Curl code to wp_remote_post/wp_remote_get/ to align with WP documentation.

= 1.1.2 =

* Release date: August 15, 2017

**Bug fixes**
* Added client IP when getting new leads.

= 1.1.1 =

* Release date: August 14, 2017

**Major Enhancements**
* The Lead tracker widget will now work without CFDB external plugin.

**Enhancements**
* Refactored some functions to speed up the widgets functions.
* Added a localization template.
* For now, localization is only available for English.
* Css and js for webaudit will be now included in the plugin. (from external source before)
* Better handling for showing alert messages.

**Bug fixes**
* Fixed when dashboard and web portfolio created pages where not deleted when deactivating the plugin.
* Solved PHP warnings on some functions.
* Web audit css and js will only be loaded if the web audit widget is activated on the active page.