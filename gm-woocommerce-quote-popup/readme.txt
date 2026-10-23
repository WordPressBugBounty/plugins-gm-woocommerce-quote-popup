=== Product Enquiry for WooCommerce ===
Contributors: gravitymaster97
Tags: request a quote woocommerce, product enquiry for woocommerce, woocommerce catalog mode, hide price woocommerce, woocommerce quote
Requires at least: 5.8
Tested up to: 7.0
Stable tag: 3.2
Requires PHP: 7.2
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Add a Request a Quote or Product Enquiry button to your WooCommerce store. Hide prices, enable catalog mode, and capture B2B leads.

== Description ==

Turn your WooCommerce store into a powerful Request a Quote and Product Enquiry system.

**Product Enquiry for WooCommerce** allows customers to ask questions, request custom pricing, or submit quote requests directly from product pages — without going through the standard checkout process.

Perfect for B2B stores, wholesalers, manufacturers, distributors, and businesses that need flexible pricing or catalog mode functionality. Capture high-intent leads and convert price-sensitive visitors into serious enquiries.

*🔗 [Documentation](https://www.codesmade.com/product-enquiry-for-woocommerce-documentation/)* | *🔗 [Demo](https://codesmade.com/demo/product/beanie/)* | *🔗 [Pro Version](https://www.codesmade.com/store/product-enquiry-for-woocommerce-pro/)* | *🔗 [Support](https://www.codesmade.com/contact-us/)*

### Why Use This Plugin?

* Replace "Add to Cart" with "Request a Quote"
* Hide prices for selected products or your entire store
* Run WooCommerce in full Catalog Mode
* Collect structured enquiry information via a custom form
* Allow customers to submit bulk quote requests for multiple products
* Increase B2B lead generation with every product page visit

Whether you need a simple "Ask a Question" button or a complete WooCommerce quote system, this plugin gives you full control.

### Free Features

**Quote Request & Enquiry Button**

* Add a "Request a Quote" or "Product Enquiry" button to product pages
* Replace or hide the Add to Cart button
* Hide product prices globally or per product
* Enable WooCommerce Catalog Mode for the entire store
* Custom button placement on the product page
* Enquiry cart for submitting bulk quote requests across multiple products

**Advanced Form Builder**

* Drag and drop custom fields onto your enquiry form
* Supports Text, Select, Checkbox, Radio, and Textarea field types
* Required field validation
* Custom labels and placeholder text
* Google reCAPTCHA v2 and v3 support to prevent spam

**Email Notifications**

* Instant admin email notification on every new enquiry
* Automatic customer confirmation email after submission
* Customizable email subject and body content
* Dynamic placeholders to include product name, customer details, and form data

**Webhook Integration**

* Send enquiry data to any external system via webhook
* Compatible with CRMs, Slack, Zapier, Make (Integromat), and more
* POST request support
* Easy enable/disable toggle per form

**Display Rules & Visibility Controls**

* Show the enquiry button for all visitors or logged-in users only
* Include or exclude specific product categories
* Show the enquiry button for out-of-stock products
* Flexible per-product visibility settings

**Design & Customization**

* Customize button colors and styling
* Add custom content before or after the enquiry form
* Choose between label or placeholder display modes
* Fully responsive layout for mobile and desktop

**Management & Reporting**

* View all enquiries in a dedicated WordPress dashboard panel
* Filter enquiries by date range
* Export all enquiry data to CSV
* Track and manage customer requests from one place

**Translation & Compatibility**

* Fully translatable with .pot file included
* Compatible with WPML and other multilingual plugins
* RTL and LTR layout support
* Works with most WooCommerce themes

### Pro Features

Upgrade to the Pro version for advanced controls and extended functionality:

* **Advanced Category Rules** — Fine-grained category-based display logic with include/exclude conditions
* **Extended Form Builder** — Additional field types and conditional logic for more powerful forms
* **Enhanced Email Customization** — Advanced email templates with HTML support and more dynamic placeholders
* **Enhanced Catalog Mode** — More granular control over which products show prices or Add to Cart
* **Conditional Visibility** — Show or hide the enquiry button based on user role, product type, stock status, and more

*🔗 [View Pro Version](https://www.codesmade.com/store/product-enquiry-for-woocommerce-pro/)*

### Perfect For

* Wholesale and B2B WooCommerce stores
* Manufacturers and distributors
* Custom pricing and negotiable-price businesses
* Catalog-only stores with no public pricing
* Call-for-price product pages
* High-value or configurable products
* Stores that need lead capture before selling

### Shortcodes

Display the enquiry cart anywhere on your site:
`[gm_woo_enquiry_cart]`

Display the enquiry button on a product page:
`[gmwqp_enquiry_single_product]`

Display the enquiry button for a specific product by ID:
`[gmwqp_enquiry_single_product id="123"]`

### Developer Hooks

A custom JavaScript event is triggered after a successful enquiry submission, allowing you to run custom code or fire analytics events:

`window.addEventListener('Gm_enquiry_submitted', function(event) {
    console.log('Enquiry submitted!');
    console.log(event.detail);
});`

== Installation ==

= Automatic Installation (Recommended) =

1. Log in to your WordPress dashboard.
2. Go to **Plugins > Add New**.
3. Search for **Product Enquiry for WooCommerce**.
4. Click **Install Now**, then click **Activate**.

= Manual Installation =

1. Download the plugin .zip file from the WordPress Plugin Directory.
2. In your WordPress dashboard, go to **Plugins > Add New**.
3. Click **Upload Plugin** and select the downloaded .zip file.
4. Click **Install Now**, then click **Activate**.

= After Activation =

1. Go to **WooCommerce > Product Enquiry Settings** in your dashboard.
2. Configure your display options — choose which products or categories show the enquiry button.
3. Set up your enquiry form fields using the drag-and-drop form builder.
4. Configure admin and customer email notification settings.
5. Save your settings and test the button on a product page.

== Frequently Asked Questions ==

= Does this plugin require WooCommerce? =

Yes. WooCommerce must be installed and active for this plugin to work. The enquiry button and form are displayed on WooCommerce product pages.

= Can I use this plugin to enable Catalog Mode? =

Yes. You can hide both the product price and the Add to Cart button store-wide or for specific products and categories, effectively turning your WooCommerce store into a catalog-only website.

= Can I replace the Add to Cart button with a quote button? =

Yes. You can replace the Add to Cart button with your own custom "Request a Quote" or "Product Enquiry" button. The button label is fully editable.

= Can customers request quotes for multiple products at once? =

Yes. The enquiry cart feature allows customers to add multiple products to an enquiry list and submit a single bulk quote request for all of them.

= Can I customize the enquiry form fields? =

Yes. The plugin includes a drag-and-drop form builder that lets you add, remove, and reorder fields. Supported field types include Text, Select, Checkbox, Radio button, and Textarea. You can set required fields, custom labels, and placeholder text.

= Does it support Google reCAPTCHA? =

Yes. The plugin supports both Google reCAPTCHA v2 (checkbox) and v3 (invisible) to protect your enquiry form from spam submissions.

= Will I receive an email when a customer submits an enquiry? =

Yes. An email notification is sent to the admin immediately after each enquiry submission. A confirmation email is also sent to the customer. Both emails are fully customizable with dynamic placeholders.

= Where are enquiries stored? =

All enquiries are saved to your WordPress database and are accessible from a dedicated panel in your dashboard. You can filter by date and export all entries as a CSV file.

= Can I send enquiry data to my CRM or Zapier? =

Yes. The webhook integration feature lets you send enquiry data via POST request to any external URL, including CRMs, Slack, Zapier, Make (Integromat), or any custom endpoint.

= Can I show the enquiry button only to logged-in users? =

Yes. The visibility controls let you choose whether the enquiry button is shown to all visitors or only to logged-in users.

= Is the plugin translation-ready? =

Yes. The plugin includes a .pot translation file and is compatible with WPML and other multilingual plugins. RTL layouts are also supported.

= Where can I get support? =

Visit the [support page](https://www.codesmade.com/contact-us/) or post in the [WordPress support forum](https://wordpress.org/support/plugin/gm-woocommerce-quote-popup/).

== Screenshots ==

1. Product page — enquiry button displayed below the product details, replacing or alongside Add to Cart.
2. Enquiry popup form — customizable form fields presented in a popup modal on the product page.
3. Enquiry cart — customers can add multiple products and submit a single bulk quote request.
4. Form builder — drag-and-drop interface to add and configure custom form fields.
5. Email notification settings — configure admin and customer confirmation email content and subject.
6. Display rules settings — control which products, categories, and user types see the enquiry button.
7. Button customization — configure button label, color, and placement on the product page.
8. Enquiry entries dashboard — view, filter, and manage all submitted enquiries from one panel.
9. Webhook settings — configure a webhook URL to send enquiry data to external systems.

== Changelog ==

= 3.2 =
* Tested and confirmed compatibility with WordPress 6.9.1.
* Performance and stability improvements.

= 3.1 =
* Added Google reCAPTCHA v3 (invisible) support.
* Improved webhook POST request reliability.
* Bug fixes for enquiry cart on variable products.

= 3.0 =
* Major update: introduced drag-and-drop form builder.
* Added enquiry cart for bulk product quote requests.
* Added CSV export for enquiry entries.
* Added webhook integration support.

= 2.0 =
* Added WooCommerce Catalog Mode support.
* Added per-category display rules.
* Added customer confirmation email notifications.
* Added dynamic email placeholders.

= 1.0.0 =
* Initial release.

== Upgrade Notice ==

= 3.2 =
Recommended update for all users. Includes compatibility improvements for WordPress 6.9.1 and stability fixes.