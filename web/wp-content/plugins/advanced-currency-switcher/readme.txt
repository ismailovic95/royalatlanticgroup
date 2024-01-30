=== Currency Switcher for WordPress ===
Contributors: pratikchaskar
Tags: Currency switcher, Currency converter, Multi-currency, Currency plugin, Multi currency plugin
Requires at least: 4.2
Requires PHP: 5.3
Tested up to: 6.4
Stable tag: 1.0.5
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

The Currency Switcher plugin provides an easier way to let users switch between currencies in real time to help them make a purchase decision.

**The easiest way to allow website visitors switch currencies with one click!**

With the constantly changing exchange rates it is difficult for an eCommerce website to keep updating the pricing plans in different currencies. This lack of information at times results in the loss of potential customers who do not find ready information they are looking for.

The Currency Switcher plugin is a powerful solution that helps you display an automated currency converter on your website. With this handy plugin, one can cater to users coming from the United States, European countries, India and Australia.

== How does this work? ==

The Currency Switcher plugin can be installed and activated like any other WordPress plugin.
Once installed, you can move on to *Settings > Currency Switcher* option.

== Manage Global Settings ==

The Global settings allow you to select whether you wish to manage currency conversions manually or through the Open Exchange Rate API. The options below depend on this choice.

**1.Manual Conversion Rate**
When you select this option, the Currency Switcher plugin will consider the value that you've entered manually. You will have to keep coming back to change the value periodically.

**2.Open Exchange Rate API**

This option allows you to fetch real-time exchange rates through Open Exchange Rate by authenticating their App ID.
With this option, you simply authenticate your ID once, set the frequency you wish to fetch the values in (hourly, daily or weekly), select the display type and number format.
Save your changes!

== Using Shortcodes ==

You will find the necessary shortcodes within the *User Manual* section.

**1.Shortcode for Currency Switcher -**

``[currency value=""]``
This shortcode lets you display field within which the original and the converted price will be seen.
You will need to enter the cost (numerical value) of the product within the inverted commas (“”). For example, if the cost of a product is $100, the shortcode will be [currency value=”100”].

**2.Shortcode for the Switcher Type -**

`[currency-switch]`
Simply copy and paste this shortcode in the place you wish to add the switcher on the page.
The Global Settings allow you to select the switcher type you wish to use. This can either be a drop down menu or a button.

These shortcodes can be added in any page builder including, Elementor, Beaver Builder, etc.

You can use the currency switcher shortcode multiple times on a page. However, the switcher type shortcode can be used only once on a particular page.

== Frequently Asked Questions ==

= How to get started with the Currency Switcher plugin? =
Install the Currency Switcher plugin like any other WordPress plugin. Open Settings -> Currency Switcher.
Select the conversion method through Global settings and add the respective shortcodes on the desired page.

= Where can I add a currency switcher on my website? =
The Currency Switcher plugin gives you a shortcode to be added. Therefore, you can add a currency switcher any place that accepts shortcodes on your website.

= What are the switcher types available? =
As of now, the Currency Switcher plugin gives you two switcher types to choose from - A drop down or a button.

= How to update the currency according to the latest exchange rate? =
The Currency Switcher plugin allows you to select a feasible method to update currency. You can do it manually or opt to use the Open Exchange Rate API.

= Do I need to have the paid version of the Open Exchange Rate API? =
The Currency Switcher plugin works with both - the free and paid versions of Open Exchange Rate API. However, we'd recommend you to opt for the paid version since the free one has a limited number of requests that can be passed and you get conversion rates based on USD only.

= How can I round up the value in the changed currency? =
You need not do this manually! The Currency Switcher plugin gives you an option to select the number type, where you can select whether you wish to display the value in decimals or a rounded up figure.

= Will this affect the transactions on my website? =
The Currency Switcher plugin is just an addon to help users get an idea of how much they'll be spending in their currency. This has nothing to do with the transaction.

== Screenshots ==

1. Visitor Can Change the Currency with Just One Click.
2. Add Currency Conversion Rates Manually.
3. Get the Real-time Exchange Rates Automatically.
4. Choose Conversion Method - Dropdown/Toggle/Button.
5. Use of Shortcodes to Display Price and Conversion Method.
6. User Manual to See How to Get Started.

== Changelog ==

= 1.0.5 =
* Improvement: Added compatibility to WordPress 6.1

= 1.0.4 =
* Improvement: esc_url_raw() applied to prevent potential XSS issues.

= 1.0.3 =
- Fix: Added compatibility with WordPress v5.7 for jQuery migration warnings.

= 1.0.2 =
- Add Button option in Display type.

= 1.0.1 =
- Remove duplicate JS file that is being loaded.
- Reduce the space between currency symbol and the price.

= 1.0.0 =
- Initial Release.
