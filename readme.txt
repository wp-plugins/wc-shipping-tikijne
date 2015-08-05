# Epeken JNE Plugin for Woocommerce
Contributors: support@epeken.com

Tags: ecommerce, e-commerce, commerce, wordpress ecommerce, plugin jne gratis,affiliate, online shop, toko online, cart, checkout, reports, download, downloadable, digital, inventory, stock, pengiriman, reports, shipping, tax, JNE, Tiki, Indonesia, kurir, jne kurir, woocommerce jne, epeken

Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_xclick&business=imam.prkso@gmail.com&item_name=Donation+for+JNE+Plugin

Requires at least: 3.8

Tested up to: 4.2.2

License: GPLv2

License URI: http://www.gnu.org/licenses/gpl-2.0.html

Epeken JNE Plugin - WooCommerce JNE courier <b>Shipping Method</b>. Bank Mandiri, BCA, BNI <b>Payment Method</b> for Indonesia market.  

== Description ==

This plugin is calculated shipping plugin with JNE shipping courire for Indonesia marketplace. You would have shipping method using JNE based on kota/kabupaten(City) and Kecamatan by installing this plugin in your woocommerce website. Starting v.1.2.2 you will also get configurable bank payment method including Mandiri, BCA and BNI. Free shipping based on minimum total purchase functionality is also available.

[Try Our Demo](http://www.epeken.com/epdemo/)

This plugin is developed by epeken. copyright (c) 2015 by [epeken](http://www.epeken.com)

= Strength & flexibility =

This plugin is built using WordPress best practises both on the front and the back end. This results in an efficient, robust and intuitive plugin. Currently this plugin supports latest woocommerce v.2.3.8 and latest wordpress 4.2.2.

= Customizable =

Your business is unique, you may modify this plugin to meet your business requirement. However, re-distribution after modification is prohibited. You may refer to woocommerce plugin customization page and wordpress plugin development page to do it. This version will allow you to get tariff JNE tariff from Jakarta to all around cities in Indonesia in Regular (JNE REG) tariff only. Starting from version 1.1.3, we also include free shipping capability. We have full version of plugin with JNE OKE and YES and it supports volume metrics tariff. [Buy full version now](http://www.epeken.com), [Try Our Demo](http://www.epeken.com/demo), Or, Any concerns and questions, you may submit them and contact us : support@epeken.com.

== Installation ==

= Minimum Requirement =

* Wordpress 4.0
* Woocommerce version 2.3.5
* PHP version 5.2.4 or greater
* MySQL version 5.0 or greater

= Automatic installation =

Automatic installation is the easiest option as WordPress handles the file transfers itself and you don’t need to leave your web browser. To do an automatic install of wc_shipping_tikijne, log in to your WordPress dashboard, navigate to the Plugins menu and click Add New.

In the search field type “jne” and click Search Plugins. Once you’ve found our WC Shipping TikiJne plugin you can view details about it such as the the point release, rating and description. Most importantly of course, you can install it by simply clicking “Install Now”.

= Manual installation =

The manual installation method involves downloading our eCommerce plugin and uploading it to your webserver via your favourite FTP application. The WordPress codex contains [instructions on how to do this here](http://codex.wordpress.org/Managing_Plugins#Manual_Plugin_Installation).

= Updating =

Automatic updates should work like a charm; as always though, ensure you backup your site just in case.

= Configuration =

Once plugin is installed please activate it from activate link in the wordpress plugin page. After that, go to woocommerce wp-admin dashboard menu and click Settings. This will populate jne tariff data into database. while data is loaded, you will see pop up information telling this process is taking place. You may follow other recommended configurations also from the [epeken blog](http://blog.epeken.com).

== Screenshots ==
1. Configuration : This is what check out screen looks like when using our plugin

== Change Log ==

= 1.0 =
Base uploaded
= 1.1 =
* Fix - jne_tariff.csv, There are more than 100 records in ver 1.0 are missing in database. File was corrupted. Now it is fixed. To refresh jne tariff, please deactivate plugin and re-activate plugin and then go to woocommerce > settings menu from your dashboard.
= 1.1.1 =
* Tweak - adding popup while checkout page is loading kecamatan
= 1.1.2 =
* Fix - Some bugs were fixed, like removing var_dump from kecamatan drop down.
= 1.1.3 =
* Add - New Free shipping functionality, if customer spend more than a particular value of cart total.
= 1.1.4 =
* Add - Redirect to homepage after user login
= 1.2.0 =
* Tweak - Customer doesn't need to login to get JNE tariff. BIG CHANGE, SIMPLER. Customer Experience will increase.
= 1.2.1 =
* Add - Cek Resi JNE. powered by [cekresi.com](http://www.cekresi.com)
= 1.2.2 =
* Add - Add Payment Method Bank Mandiri, Bank BCA, and Bank BNI
* Tweak - fix bug failure getting shipping tarif if there are some encoded characters like slash(/) character.
= 1.2.3 = 
* Add - Penyesuaian tarif sudah disesuaikan dengan tarif JNE asal kota Jakarta per 8 Juni 2015
= 1.2.4 =
* Tweak - Bring back Order Notes Field
= 1.2.5 =
* Add - Intuitive update tarif Do it your self. User can have option to display Cek Resi or not.
= 1.2.5.1 =
* Tweak - Fix Invalid shipping Method when it is run with latest woocommerce 2.3.11
= 1.2.5.2 =
* Tweak - Address is auto populated in checkout billing and shipping form for user which is logged in and accessing checkout page
