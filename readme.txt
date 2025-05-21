=== Store Opening Closing Hours Manager ===
Tags: woocommerce, store-closing-opening, store-manager, shop-manager, shop-closing-opening
Contributors: sajjad67
Author: Sajjad Hossain Sagor
Tested up to: 6.8
Requires at least: 5.6
Stable tag: 2.0.2
Requires PHP: 8.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Setup your WooComerce store opening and closing hours to manage your business at ease!

== Description ==
Let your customers know when your store is open or closed! Take action when store is closed, you can even disable shopping at all! Add a notice that your store is going to close soon!! Customers can also see if store is closed when it will be open again! Enable Widget to show your entire week store opening & closing hours! Plugin Comes with 6 types of notice designs which also has 5 types of countdown timer design!

= Features: =
- Enable/Disable The Plugin Functionality
- Set The Store Status Close Manually
- Show Store Opening/Closing Hours In A Widget
- Show Store Opening/Closing Hours In Shortcode `[sochm_display_table]`
- Enable Countdown
- Choose Timer Design From 5 Different Types
- Set Your Store Timezone
- Add Store Closed/Open Notice In WP Admin Area (Admin Bar)
- Choose Notice Design From 6 Different Types
- Add Your Own Store Closed Notice Message
- Enable Store Going To Close Soon Notice
- Add Your Own Store Going To Close Soon Notice Message
- Auto Clear Existed Carts If Store Closed
- Disable The Entire Checkout System If Store Closed
- Remove The Proceed To Checkout Button From Cart Page If Store Closed
- Disable Add To Cart Button If Store Closed
- Remove The Add To Cart Button Entirely If Store Closed
- You Can Even Show A Message To Your Customers If They Click The Add To Cart Button If Store Closed
- Change The Notice Text Color & Background Color
- Templating system for the notice & timer designs.

== Overriding Plugin Templates ==
To override a specific template, copy the desired file from the plugin’s /templates/ directory and place it in your child theme under /templates/sochm/. You can then modify the template as needed.

Example:
If the plugin template is located at:
`wp-content/plugins/store-opening-closing-hours-manager/templates/notice-type-single-page.php`
You can override it by copying it to:
`wp-content/themes/your-theme/templates/sochm/notice-type-single-page.php`

Make sure to maintain the same file structure to ensure proper loading.

== Compatibity With Cache Plugins ==
If your site uses page caching, certain dynamic functionalities may not work as expected. This plugin includes built-in support to automatically purge cache when needed, ensuring everything runs smoothly.

It is compatible with the following popular caching plugins:

- Breeze Cache ✅
- Cache Enabler ✅
- GoDaddy Cache ✅
- Kinsta Cache ✅
- LiteSpeed Cache ✅
- NitroPack ✅
- Speed Optimizer ✅
- Super Page Cache ✅
- WP Fastest Cache ✅
- WP Optimize Cache ✅
- WP Super Cache ✅
- W3 Total Cache ✅
- WP Rocket ✅
- WPEngine Cache ✅

== Installation ==
To add a WordPress Plugin using the built-in plugin installer:

Go to Plugins > Add New.

1. Type in the name "Store Opening Closing Hours Manager" in Search Plugins box
2. Find the "Store Opening Closing Hours Manager" Plugin to install.
3. Click Install Now to begin the plugin installation.
4. The resulting installation screen will list the installation as successful or note any problems during the install.
If successful, click Activate Plugin to activate it, or Return to Plugin Installer for further actions.

To add a WordPress Plugin from GitHub repo / plugin zip file :
1. Go to WordPress plugin page
2. Click Add New & Upload Plugin
3. Drag / Click upload the plugin zip file
4. The resulting installation screen will list the installation as successful or note any problems during the install.
If successful, click Activate Plugin to activate it, or Return to Plugin Installer for further actions.

== Frequently Asked Questions ==
= How to use this plugin? =
Just after installing the plugin, go to settings page and set the plugin settings according to your needs.. And then see the magic.. See screenshots.

== Screenshots ==
1. settings
2. store-closed-notice-single-page-boxed-with-flipping
3. store-closed-notice-dialog-boxed
4. store-closed-notice-dialog-boxed-with-flipping
5. store-closed-notice-dialog-circular-border
6. store-closed-notice-dialog-circular-border-with-filling
7. store-closed-notice-dialog-default
8. store-closed-notice-single-page-boxed
9. store-closed-notice-single-page-boxed-with-flipping
10. store-closed-notice-single-page-boxed-with-flipping
11. store-closed-notice-single-page-circular-border
12. store-closed-notice-single-page-circular-border-with-filling
13. store-closed-notice-single-page-default
14. store-closed-notice-sticky-footer-boxed
15. store-closed-notice-sticky-footer-boxed-with-flipping
16. store-closed-notice-sticky-footer-circular-border
17. store-closed-notice-sticky-footer-circular-border-with-filling
18. store-closed-notice-sticky-footer-default
19. store-closed-notice-sticky-header-boxed
20. store-closed-notice-sticky-header-boxed-with-flipping
21. store-closed-notice-sticky-header-circular-border
22. store-closed-notice-sticky-header-circular-border-with-filling
23. store-closed-notice-sticky-header-default
24. store-closed-notice-toast-boxed
25. store-closed-notice-toast-boxed-with-flipping
26. store-closed-notice-toast-circular-border
27. store-closed-notice-toast-circular-border-with-filling
28. store-going-to-close-soon-notice
29. store-opening-closing-hours-in-widget
30. table

== Changelog ==
= 2.0.0 =
- Checked for latest wp version 6.8
= 1.0.5 =
- Compatibility check for wp v6.6
= 1.0.4 =
- Compatibility check for wp v6.3
= 1.0.3 =
- fixed wc_add_notice fatal error issue happens sometimes.
= 1.0.2 =
- Added Shortcode Feature
= 1.0.1 =
- Added Manual Store Closing Feature
= 1.0.0 =
- Initial release.

== Upgrade Notice ==
Always try to keep your plugin update so that you can get the improved and additional features added to this plugin up to date.