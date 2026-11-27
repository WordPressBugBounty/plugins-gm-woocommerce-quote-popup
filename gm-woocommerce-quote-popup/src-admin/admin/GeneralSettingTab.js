import { useEffect, useState } from 'react';
import {
    PanelBody,
    Button,
    Modal,
    TextControl,
    SelectControl,
    TextareaControl,
    CheckboxControl,
    RadioControl,
    FormToggle,
    NumberControl,
    Spinner,
    ColorPalette,
    ProRow,
    Text
} from '../components';
import { __ } from '../i18n';
import { fetchData, saveData } from './api';
const GeneralSettingTab = ({ openPopup }) => {
    const [isSaving, setIsSaving] = useState(false);
    const [loading, setLoading] = useState(true);
    const [data, setData] = useState([]);
    const [modifiedFields, setModifiedFields] = useState({});
    const [redirectPages, setRedirectPages] = useState([]);
    useEffect(() => {
        // Fetch settings from the REST API
        fetchData(gmwqp_wp_ajax.getsettings, gmwqp_wp_ajax.nonce)
            .then((fetchedData) => {
                setData(fetchedData);
                setLoading(false);
            })
            .catch((error) => {
                setLoading(false);
                console.error('Error fetching settings:', error);
            });
    }, []);

    const updateField = (field, value) => {
        setData((prevData) => ({
            ...prevData,
            [field]: value,
        }));
        setModifiedFields((prevFields) => ({
            ...prevFields,
            [field]: value,
        }));
    };
    const toggleCheckbox = (fieldName, key) => {
        setData((prevData) => {
            const currentValues = prevData[fieldName] || [];
            const updatedValues = currentValues.includes(key)
                ? currentValues.filter((item) => item !== key) // Remove key if already present
                : [...currentValues, key]; // Add key if not present
            return {
                ...prevData,
                [fieldName]: updatedValues,
            };
        });
        setModifiedFields((prevFields) => ({
            ...prevFields,
            [fieldName]: data[fieldName]?.includes(key)
                ? data[fieldName].filter((item) => item !== key)
                : [...(data[fieldName] || []), key],
        }));
    };

    const saveSettings = () => {
        if (Object.keys(modifiedFields).length === 0) {
            openPopup('No changes to save.');
            return;
        }
        setIsSaving(true);

        saveData(gmwqp_wp_ajax.savedata, gmwqp_wp_ajax.nonce, modifiedFields)
            .then((responseData) => {
                setIsSaving(false);
                if (responseData.success) {
                    openPopup('Settings saved!');
                } else {
                    openPopup('Failed to save settings.');
                }
            })
            .catch((error) => {
                setIsSaving(false);
                console.error('Error saving settings:', error);
                openPopup('Failed to save settings.');
            });
    };
    return (
        <>
            {loading ? (
                <Spinner />
            ) : (
                <>
                    <PanelBody title="Setting">
                        <table className="GMadminTable">


                            <ProRow title="Remove Price From Product" description="Hide product prices on the shop and product pages for catalog-style stores.">
                                <FormToggle checked={false} onChange={() => {}} />
                            </ProRow>
                            <ProRow title="Hide Add to Cart Button" description="Hide the Add to Cart button and turn your shop into a quote-only catalog.">
                                <FormToggle checked={false} onChange={() => {}} />
                            </ProRow>
                            <ProRow title="Sticky Enquiry Button on Mobile" description="A fixed enquiry bar at the bottom of product pages on phones.">
                                <FormToggle checked={false} onChange={() => {}} />
                            </ProRow>
                            <ProRow title="Auto Close Popup After Submit" description="Close the popup automatically a few seconds after a successful enquiry.">
                                <FormToggle checked={false} onChange={() => {}} />
                            </ProRow>
                        </table>
                    </PanelBody>
                    <PanelBody title="Google Captcha">
                        <table className="GMadminTable">
                            <tr>
                                <th>
                                    <label>Captcha Show</label>
                                </th>
                                <td>
                                    <RadioControl
                                        selected={data.gmwqp_captcha}
                                        options={[
                                            { label: 'Yes', value: 'yes' },
                                            { label: 'No', value: 'no' },
                                        ]}
                                        onChange={(value) => updateField('gmwqp_captcha', value)}

                                    />
                                    <div className="mt-10" >
                                        <a href="https://www.google.com/recaptcha/admin/create" target="_blank">Generate Google Captcha Key</a>
                                    </div>
                                </td>
                            </tr>
                            {data.gmwqp_captcha === 'yes' && (
                                <>
                                    <tr>
                                        <th>
                                            <label>Captcha Version</label>
                                        </th>
                                        <td>
                                            <RadioControl
                                                selected={data.gmwqp_captcha_version}
                                                options={[
                                                    { label: 'Version 2', value: '2' },
                                                    { label: 'Version 3', value: '3' },
                                                ]}
                                                onChange={(value) => updateField('gmwqp_captcha_version', value)}

                                            />

                                        </td>
                                    </tr>
                                    <tr>
                                        <th>
                                            <label>Captcha Site Key</label>
                                        </th>
                                        <td>
                                            <TextControl
                                                onChange={(value) => updateField('gmwqp_captcha_site_key', value)}
                                                value={data.gmwqp_captcha_site_key}
                                            />
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>
                                            <label>Captcha Secret Key</label>
                                        </th>
                                        <td>
                                            <TextControl
                                                onChange={(value) => updateField('gmwqp_captcha_secrete_key', value)}
                                                value={data.gmwqp_captcha_secrete_key}
                                            />
                                        </td>
                                    </tr>
                                </>
                            )}
                        </table>
                    </PanelBody>
                    <PanelBody title="Form Setting">
                        <table className="GMadminTable">
                            <tr>
                                <th>
                                    <label>Label / Placeholder Display</label>
                                </th>
                                <td>
                                    <RadioControl
                                        selected={data.gmwqp_label_show}
                                        options={[
                                            { label: 'Show Label', value: 'show_label' },
                                            { label: 'Show Placeholder', value: 'show_placeholder' },
                                        ]}
                                        onChange={(value) => updateField('gmwqp_label_show', value)}

                                    />
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <label>Button Background Color</label>
                                </th>
                                <td>
                                    <ColorPalette
                                        onChange={(value) => updateField('gmwqp_enquiry_btn_bg_color', value)}
                                        value={data.gmwqp_enquiry_btn_bg_color}
                                        resetTo="theme"
                                        resetLabel="Reset to theme"
                                    />
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <label>Button Text Color</label>
                                </th>
                                <td>
                                    <ColorPalette
                                        onChange={(value) => updateField('gmwqp_enquiry_btn_text_color', value)}
                                        value={data.gmwqp_enquiry_btn_text_color}
                                        resetTo="theme"
                                        resetLabel="Reset to theme"
                                    />
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <label>Button Background Hover Color</label>
                                </th>
                                <td>
                                    <ColorPalette
                                        onChange={(value) => updateField('gmwqp_enquiry_btn_bg_hover_color', value)}
                                        value={data.gmwqp_enquiry_btn_bg_hover_color}
                                        resetTo="theme"
                                        resetLabel="Reset to theme"
                                    />
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <label>Button Text Hover Color</label>
                                </th>
                                <td>
                                    <ColorPalette
                                        onChange={(value) => updateField('gmwqp_enquiry_btn_text_hover_color', value)}
                                        value={data.gmwqp_enquiry_btn_text_hover_color}
                                        resetTo="theme"
                                        resetLabel="Reset to theme"
                                    />
                                </td>
                            </tr>
                            <ProRow title="Popup Close Button Colors" description="Choose the icon and background color of the popup close button.">
                                <ColorPalette value="#111827" onChange={() => {}} />
                            </ProRow>
                            
                            <tr>
                                <th>
                                    <label>Reset All Colors</label>
                                    <p className="description">Buttons go back to your theme colors.</p>
                                </th>
                                <td>
                                    <Button
                                        isSecondary
                                        onClick={() => {
                                            ['gmwqp_enquiry_btn_bg_color', 'gmwqp_enquiry_btn_text_color', 'gmwqp_enquiry_btn_bg_hover_color', 'gmwqp_enquiry_btn_text_hover_color'].forEach((k) => updateField(k, 'theme'));
                                        }}
                                    >
                                        Reset all colors
                                    </Button>
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <label>Content Before Enquiry From</label>
                                </th>
                                <td>
                                    <TextareaControl
                                        onChange={(value) => updateField('gmwqp_content_beforeform', value)}
                                        value={data.gmwqp_content_beforeform}
                                    />
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <label>Content After Enquiry From</label>
                                </th>
                                <td>
                                    <TextareaControl
                                        onChange={(value) => updateField('gmwqp_content_afterform', value)}
                                        value={data.gmwqp_content_afterform}
                                    />
                                </td>
                            </tr>
                        </table>
                    </PanelBody>
                    <PanelBody title="Redirection Setting">
                        <table className="GMadminTable">
                            <tr>
                                <th>
                                    <label>Redirect after Enquiry form Submission</label>
                                </th>
                                <td>
                                    <FormToggle
                                        checked={data.gmwqp_redirect_form_sub === 'yes'}
                                        onChange={() =>
                                            updateField('gmwqp_redirect_form_sub', data.gmwqp_redirect_form_sub === 'yes' ? 'no' : 'yes')
                                        }
                                    />
                                </td>
                            </tr>
                            {data.gmwqp_redirect_form_sub === 'yes' && (
                                <tr>
                                    <th>
                                        <label>Enter Redirect Page URL</label>
                                    </th>
                                    <td>
                                        <TextControl
                                            value={data.gmwqp_redirect_form_sub_page}
                                            onChange={(value) => updateField('gmwqp_redirect_form_sub_page', value)}
                                        />
                                    </td>
                                </tr>
                            )}
                            <tr>
                                <th>
                                    <label>Disable Woocommerce Cart and Checkout Page?</label>
                                </th>
                                <td>
                                    <FormToggle
                                        checked={data.gmwqp_disable_cart_checkout_page === 'yes'}
                                        onChange={() =>
                                            updateField('gmwqp_disable_cart_checkout_page', data.gmwqp_disable_cart_checkout_page === 'yes' ? 'no' : 'yes')
                                        }
                                    />
                                </td>
                            </tr>
                            {data.gmwqp_disable_cart_checkout_page === 'yes' && (
                                <tr>
                                    <th>
                                        <label>Enter Redirect Page URL</label>
                                    </th>
                                    <td>
                                        <TextControl
                                            value={data.gmwqp_redirect_page}
                                            onChange={(value) => updateField('gmwqp_redirect_disable_cart_checkout_page', value)}
                                        />

                                    </td>
                                </tr>
                            )}

                        </table>
                    </PanelBody>
                    <PanelBody title="Webhook Setting">
                        <table className="GMadminTable">
                            <tr>
                                <th>
                                    <label>Enable Webhook</label>
                                </th>
                                <td>
                                    <FormToggle
                                        checked={data.gmwqp_webhook_enable === 'yes'}
                                        onChange={() =>
                                            updateField('gmwqp_webhook_enable', data.gmwqp_webhook_enable === 'yes' ? 'no' : 'yes')
                                        }
                                    />
                                </td>
                            </tr>
                            {data.gmwqp_webhook_enable === 'yes' && (
                                <tr>
                                    <th>
                                        <label>Webhook URL</label>
                                    </th>
                                    <td>
                                        <TextControl
                                            placeholder="https://your-webhook-url.com"
                                            value={data.gmwqp_webhook_url}
                                            onChange={(value) => updateField('gmwqp_webhook_url', value)}
                                        />
                                        <p className="description">Enter the URL where you want to send the enquiry data via POST request.</p>
                                    </td>
                                </tr>
                            )}
                        </table>
                        <Button isPrimary onClick={saveSettings} disabled={isSaving}>
                            {isSaving ? <Spinner /> : 'Save Settings'}
                        </Button>
                    </PanelBody>
                </>
            )}
        </>
    );
};

export default GeneralSettingTab;
