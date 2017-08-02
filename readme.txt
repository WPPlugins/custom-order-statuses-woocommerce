=== Custom Order Status for WooCommerce ===
Contributors: algoritmika,anbinder
Donate link: https://www.paypal.me/anbinder
Tags: woocommerce,order status
Requires at least: 4.4
Tested up to: 4.7
Stable tag: 1.3.1
License: GNU General Public License v3.0
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Add custom order statuses to WooCommerce.

== Description ==

Plugin lets you add **custom order statuses** to WooCommerce. When adding status, you can set:

* Custom status **slug**.
* Custom status **label**.
* Custom status **icon**.
* Custom status **icon color**.

Added custom statuses can be added to admin order list **bulk actions** and to admin **reports**.

= Feedback =
* We are open to your suggestions and feedback. Thank you for using or trying out one of our plugins!

= More =
* Visit the [Custom Order Status for WooCommerce plugin page](https://wpcodefactory.com/item/custom-order-status-woocommerce/).

== Installation ==

1. Upload the entire 'custom-order-statuses-woocommerce' folder to the '/wp-content/plugins/' directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Start by visiting plugin settings at WooCommerce > Settings > Custom Order Status.

== Screenshots ==

1. Custom order status tool.
2. Order with custom status.

== Changelog ==

= 1.3.1 - 10/05/2017 =
* Fix - `Too few arguments to function Alg_WC_Custom_Order_Statuses_Settings_Section::get_settings()` fixed.

= 1.3.0 - 30/04/2017 =
* Dev - WooCommerce 3.x.x compatibility - Order ID.
* Dev - Custom Order Status Tool - Sanitizing slug before adding new status.
* Dev - Custom Order Status Tool - "Delete with fallback" separate button added. Simple "Delete" button now deletes statuses without any fallback.
* Dev - Custom Order Status Tool - "Edit" functionality moved from Pro to free version.
* Tweak - readme.txt and plugin header updated.
* Tweak - Custom Order Status Tool - Restyled.
* Tweak - Custom Order Status Tool - Code refactoring.
* Tweak - Link changed from `coder.fm` to `wpcodefactory.com`.

= 1.2.1 - 23/01/2017 =
* Dev - "Reset settings" button added.
* Tweak - readme.txt fixed.

= 1.2.0 - 17/01/2017 =
* Fix - Tool - Add - Checking for duplicate default WooCommerce status added.
* Dev - Tool - "Edit" custom status button added.
* Dev - Fallback status on delete.
* Dev - "Add Custom Statuses to Admin Order List Action Buttons" options added.
* Dev - Extended (paid) version added.
* Tweak - Plugin "Tags" updated.

= 1.1.0 - 14/12/2016 =
* Fix - `load_plugin_textdomain()` moved from `init` hook to constructor.
* Fix - All `get_option` calls have default value now.
* Dev - Language (POT) file added. Domain 'custom-order-statuses-for-woocommerce' changed to 'custom-order-statuses-woocommerce'.
* Dev - Bulk actions added in proper way for WordPress version >= 4.7.
* Tweak - Donate link updated.

= 1.0.0 - 12/11/2016 =
* Initial Release.

== Upgrade Notice ==

= 1.0.0 =
This is the first release of the plugin.
