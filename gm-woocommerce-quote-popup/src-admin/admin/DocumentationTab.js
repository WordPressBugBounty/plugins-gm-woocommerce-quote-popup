
import { PanelBody, Button } from '../components';
import { __ } from '../i18n';

const DocumentationTab = () => {
    return (
        <PanelBody title={__('Plugin Documentation', 'gm-woocommerce-quote-popup')}>
            <div className="gm-documentation-content">
                <h2>{__('Product Enquiry for WooCommerce', 'gm-woocommerce-quote-popup')}</h2>
                <p>
                    {__('The Product Enquiry for WooCommerce plugin adds an enquiry button to every WooCommerce Product Page. Using this button, a potential customer can send you an enquiry or request a quote for a product he is unsure of purchasing. By addressing his enquiries or quote requests, you can convert an unsure customer, into a buyer!', 'gm-woocommerce-quote-popup')}
                </p>

                <h3>{__('Features', 'gm-woocommerce-quote-popup')}</h3>

                <h4>{__('Enquiry Button Settings', 'gm-woocommerce-quote-popup')}</h4>
                <ul>
                    <li>{__('Enable/Disable', 'gm-woocommerce-quote-popup')}</li>
                    <li>{__('Display Page : Shop Page and Single Product Page', 'gm-woocommerce-quote-popup')}</li>
                    <li>{__('Single Product Button Location : After Add to Cart Button, Enquiry in Tab, or Custom Location', 'gm-woocommerce-quote-popup')}</li>
                </ul>

                <h4>{__('Shortcodes', 'gm-woocommerce-quote-popup')}</h4>
                <ul>
                    <li>
                        <strong>{__('Enquiry Cart:', 'gm-woocommerce-quote-popup')}</strong> <code>[gm_woo_enquiry_cart]</code>
                        <br />
                        {__('Use this shortcode to display the Enquiry Cart on any page.', 'gm-woocommerce-quote-popup')}
                    </li>
                    <li>
                        <strong>{__('Single Product Button:', 'gm-woocommerce-quote-popup')}</strong> <code>[gmwqp_enquiry_single_product]</code>
                        <br />
                        {__('Use this shortcode to display the Enquiry Button in a custom location on the single product page.', 'gm-woocommerce-quote-popup')}
                    </li>
                    <li>
                        <strong>{__('Specific Product Button:', 'gm-woocommerce-quote-popup')}</strong> <code>[gmwqp_enquiry_single_product id="product_id"]</code>
                        <br />
                        {__('Use this shortcode to display the Enquiry Button for a specific product ID.', 'gm-woocommerce-quote-popup')}
                    </li>
                </ul>

                <h4>{__('Enquiry List', 'gm-woocommerce-quote-popup')}</h4>
                <ul>
                    <li>{__('Store All data of Enqiry Cart and Single Product Enquiry', 'gm-woocommerce-quote-popup')}</li>
                    <li>{__('Filter Enquiries by Date Range (Start Date & End Date)', 'gm-woocommerce-quote-popup')}</li>
                    <li>{__('Filter Enquiries by specific Field and Value', 'gm-woocommerce-quote-popup')}</li>
                    <li>{__('Export Enquiries to CSV', 'gm-woocommerce-quote-popup')}</li>
                    <li>{__('Delete Enquiries', 'gm-woocommerce-quote-popup')}</li>
                </ul>

                <h4>{__('General Settings', 'gm-woocommerce-quote-popup')}</h4>
                <ul>
                    <li>{__('Remove Price From Product', 'gm-woocommerce-quote-popup')}</li>
                    <li>{__('Hide Add to Cart Button', 'gm-woocommerce-quote-popup')}</li>
                    <li>{__('Google Recaptcha (v2/v3) Integration', 'gm-woocommerce-quote-popup')}</li>
                    <li>{__('Enable/Disable in form Label / Placeholder', 'gm-woocommerce-quote-popup')}</li>
                    <li>{__('Customize Button Background Color - Text Color', 'gm-woocommerce-quote-popup')}</li>
                    <li>{__('Customize Button Hover Background Color - Text Color', 'gm-woocommerce-quote-popup')}</li>
                    <li>{__('Redirect after Enquiry form Submission', 'gm-woocommerce-quote-popup')}</li>
                    <li>{__('Disable Cart and Checkout Page & Redirect', 'gm-woocommerce-quote-popup')}</li>
                </ul>

                <h4>{__('Webhook Settings', 'gm-woocommerce-quote-popup')}</h4>
                <ul>
                    <li>{__('Enable/Disable Webhook functionality', 'gm-woocommerce-quote-popup')}</li>
                    <li>{__('Webhook URL : Send enquiry data to external URL via POST request', 'gm-woocommerce-quote-popup')}</li>
                </ul>

                <h4>{__('Include/Exclude', 'gm-woocommerce-quote-popup')}</h4>
                <ul>
                    <li>{__('Users Show : All Users, Only Logged in Users, Only Logged out Users', 'gm-woocommerce-quote-popup')}</li>
                    <li>{__('Include/Exclude based on Category', 'gm-woocommerce-quote-popup')}</li>
                    <li>{__('Show Enquiry Button When Product is out of stock', 'gm-woocommerce-quote-popup')}</li>
                </ul>

                <h4>{__('Form Customizer', 'gm-woocommerce-quote-popup')}</h4>
                <ul>
                    <li>{__('Add Custom Field', 'gm-woocommerce-quote-popup')}</li>
                    <li>{__('Field Type : Text, Radio, Checkbox, Select, Textarea', 'gm-woocommerce-quote-popup')}</li>
                    <li>{__('Enable/Disable Field And Required', 'gm-woocommerce-quote-popup')}</li>
                    <li>{__('Content Before Enquiry From', 'gm-woocommerce-quote-popup')}</li>
                    <li>{__('Content After Enquiry From', 'gm-woocommerce-quote-popup')}</li>
                </ul>

                <h4>{__('Email Customizer', 'gm-woocommerce-quote-popup')}</h4>
                <ul>
                    <li>{__('Customize Recipient\'s Email', 'gm-woocommerce-quote-popup')}</li>
                    <li>{__('Customize Email Subject', 'gm-woocommerce-quote-popup')}</li>
                    <li>{__('Send Enquiry Email to Customer As Well', 'gm-woocommerce-quote-popup')}</li>
                    <li>{__('Customize Success Message', 'gm-woocommerce-quote-popup')}</li>
                    <li>{__('Customize Email Body', 'gm-woocommerce-quote-popup')}</li>
                </ul>

                <h4>{__('Translation', 'gm-woocommerce-quote-popup')}</h4>
                <ul>
                    <li>{__('Customize/Translate frontend labels and messages', 'gm-woocommerce-quote-popup')}</li>
                    <li>{__('Form Title, Button Labels, Success Messages, etc.', 'gm-woocommerce-quote-popup')}</li>
                </ul>

                <h3>{__('DOM events', 'gm-woocommerce-quote-popup')}</h3>
                <pre>
                    <code>
                        {`window.addEventListener('Gm_enquiry_submitted', function(event) {
  // Handle the custom event here
  console.log('Custom event triggered!');
  console.log(event.detail);
});`}
                    </code>
                </pre>

                <div className="mt-10">
                    <Button isPrimary href="https://codesmade.com/demo/shop/" target="_blank">
                        {__('Demo', 'gm-woocommerce-quote-popup')}
                    </Button>
                    &nbsp;
                    <Button isSecondary href="https://www.codesmade.com/contact-us/" target="_blank">
                        {__('Support', 'gm-woocommerce-quote-popup')}
                    </Button>
                </div>
            </div>
        </PanelBody>
    );
};

export default DocumentationTab;
