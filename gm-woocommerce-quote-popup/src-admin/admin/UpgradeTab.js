import { PanelBody, Button, Icon } from '../components';

const PRO_URL = (window.gmwqp_wp_ajax && window.gmwqp_wp_ajax.pro_url) || 'https://www.codesmade.com/store/product-enquiry-for-woocommerce-pro/';

const FEATURES = [
    ['Hide Add to Cart', 'Turn your store into a quote-only catalog by hiding the Add to Cart button.'],
    ['Extended form builder', 'Select, Radio and Checkbox fields on top of Text and Textarea.'],
    ['Validation message per field', 'Write a different error message for each required field.'],
    ['Sticky mobile enquiry bar', 'A fixed enquiry button at the bottom of product pages on phones.'],
    ['Auto-close popup', 'Close the popup a few seconds after a successful enquiry.'],
    ['Close button colors', 'Choose the icon and background color of the popup close button.'],
];

const UpgradeTab = () => (
    <PanelBody>
        <div className="gmwqp-upgrade">
            <h2 className="gmwqp-upgrade__title">Get more from your enquiries with Pro</h2>
            <p className="gmwqp-upgrade__intro">
                You are using <strong>Product Enquiry for WooCommerce Lite</strong>. Everything below is included in Pro.
            </p>
            <ul className="gmwqp-upgrade__grid">
                {FEATURES.map(([title, text]) => (
                    <li key={title} className="gmwqp-upgrade__item">
                        <span className="gmwqp-upgrade__tick"><Icon name="check" size={15} /></span>
                        <span>
                            <strong>{title}</strong>
                            <small>{text}</small>
                        </span>
                    </li>
                ))}
            </ul>
            <Button isPrimary onClick={() => window.open(PRO_URL, '_blank', 'noopener')}>
                Upgrade to Pro <Icon name="chevronR" size={14} />
            </Button>
        </div>
    </PanelBody>
);

export default UpgradeTab;
