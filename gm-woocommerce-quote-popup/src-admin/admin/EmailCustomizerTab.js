import { useEffect, useState } from 'react';
import {
    PanelBody,
    Button,
    Spinner,
    TextControl,
    FormToggle,
    TextareaControl 
} from '../components';
import { fetchData, saveData } from './api';

const EmailCustomizerTab = ({ openPopup }) => {
    const [isSaving, setIsSaving] = useState(false);
    const [loading, setLoading] = useState(true);
    const [data, setData] = useState({});
    const [modifiedFields, setModifiedFields] = useState({});

    useEffect(() => {
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
                    setModifiedFields({}); // Clear modified fields after saving
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
        <PanelBody>
            {loading ? (
                <Spinner />
            ) : (
                <>
                    <table className="GMadminTable">
                        <tr>
                            <th>
                                <label>Recipient Email</label>
                            </th>
                            <td>
                                <TextControl
                                    onChange={(value) => updateField('gmwqp_reci_email', value)}
                                    value={data.gmwqp_reci_email}
                                />
                            </td>
                        </tr>
                        <tr>
                            <th>
                                <label>Email Subject</label>
                            </th>
                            <td>
                                <TextControl
                                    onChange={(value) => updateField('gmwqp_email_sub', value)}
                                    value={data.gmwqp_email_sub}
                                />
                            </td>
                        </tr>
                        <tr>
                            <th>
                                <label>Send Enquiry Email to Customer As Well</label>
                            </th>
                            <td>
                                <FormToggle
                                    checked={data.gmwqp_send_enquiry_email_cutomer === 'yes'}
                                    onChange={() =>
                                        updateField(
                                            'gmwqp_send_enquiry_email_cutomer',
                                            data.gmwqp_send_enquiry_email_cutomer === 'yes' ? 'no' : 'yes'
                                        )
                                    }
                                />
                            </td>
                        </tr>
                        <tr>
                            <th>
                                <label>Reply To Customer Email</label>
                            </th>
                            <td>
                                <FormToggle
                                    checked={data.gmwqp_send_enquiry_replyto_customer_email === 'yes'}
                                    onChange={() =>
                                        updateField(
                                            'gmwqp_send_enquiry_replyto_customer_email',
                                            data.gmwqp_send_enquiry_replyto_customer_email === 'yes' ? 'no' : 'yes'
                                        )
                                    }
                                />
                            </td>
                        </tr>
                        <tr>
                            <th>
                                <label>Customer Email Subject</label>
                            </th>
                            <td>
                                <TextControl
                                    onChange={(value) => updateField('gmwqp_customer_email_subject', value)}
                                    value={data.gmwqp_customer_email_subject}
                                />
                            </td>
                        </tr>
                        <tr>
                            <th>
                                <label>Email Body</label>
                            </th>
                            <td>
                                <TextareaControl
                                    value={data.gmwqp_email_body}
                                    onChange={(value) => updateField('gmwqp_email_body', value)}
                                />
                                <p>
                                    {data.gmwqp_field_customizer_enble &&
                                        Object.entries(data.gmwqp_field_customizer_enble).map(([key]) => (
                                            <code key={key}>[{key}]</code>
                                        ))}
                                    <code>[product]</code>
                                    <code>[product_id]</code>
                                    <code>[product_name_link]</code>
                                    <code>[site_url]</code>
                                    <code>[site_title]</code>
                                </p>
                            </td>
                        </tr>
                    </table>
                    <Button isPrimary onClick={saveSettings} disabled={isSaving}>
                        {isSaving ? <Spinner /> : 'Save Settings'}
                    </Button>
                </>
            )}
        </PanelBody>
    );
};

export default EmailCustomizerTab;
