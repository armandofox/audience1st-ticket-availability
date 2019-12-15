=== Audience1st Ticket Availability ===
Tested up to: 5.3
Requires PHP: 7.2
Requires at least: 5.3
Plugin Name: Audience1st Ticket Availability
Plugin URI: https://github.com/armandofox/audience1st-ticket-availability
Description: Plugin for displaying ticket availability based on RSS feeds from Audience1st
Authors: Denise Beyer, Shane Rogers, Anne Stefanyk, Armando Fox
Contributors: armandofox
Version: 1.0.0
Stable tag: trunk
Author URI: https://github.com/armandofox
License URI: https://www.gnu.org/licenses/gpl-2.0.html
License: GPLv2 or later
Tags: theater, theatre, tickets, ticketing
Text Domain: audience1st-ticket-availability
Domain path: /languages
Donate link: https://www.audience1st.com/

Visual indicators of ticket availability for various performances, for theaters using the open source Audience1st system to sell tickets.

== Description ==

If your theater or other venue uses the open source Audience1st software for ticket and subscription sales, and you use WordPress to host your venue's main website, this plugin allows your site to display real-time ticket availability for upcoming performances.  The plugin retrieves and parses and RSS feed from your theater's Audience1st installation, and renders each performance's availability using a simple "color thermometer" to indicate whether seat availability is excellent (green), limited (yellow), or sold out/nearly sold out (red).  Settings can be customized in the "Appearance" menu of the WordPress admin screen.  Originally authored by Denise Beyer, Shane Rogers, and Anne Stefanyk of Kanopi Studios; with their permission, packaged as a WordPress plugin, distributed, and maintained by Armando Fox. 

== Installation ==

1. Install the plugin through the WordPress plugins screen directly, or if your WordPress site doesn't support that, upload the plugin files to the `/wp-content/plugins/plugin-name` directory.
1. Activate the plugin through the 'Plugins' screen in WordPress.
1. Use the Settings->Audience1st Ticket Availability screen to configure the plugin.

== Screenshots ==

1. Ticket availability thermometers with default styling.  You can
modify `style.css` to change the styling.

== Frequently Asked Questions ==

= What are the thresholds for Excellent vs. Limited vs. Nearly Sold Out?

These thresholds are set in your theater's installation of Audience1st, in the Options screen.

= How can I change the styling of the thermometers? =

Take a look at the `style.css` file included with the plugin, and override those styles in your theme's CSS file.  (Editing the plugin's CSS file directly is not recommended, since your changes will be overwritten if you upgrade the plugin later.)

= How many upcoming shows are displayed? =

This can be set in the Appearance section of the plugin setup.

== Upgrade Notice ==

No upgrades available yet.

== Changelog ==

= 1.0.0 =
* Initial version submitted to plugins directory
