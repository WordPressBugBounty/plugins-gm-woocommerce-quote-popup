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
    Spinner,
    NumberControl,
    Flex,
    FlexItem,
    FlexBlock,
    FormToggle
} from '../components';
import { __ } from '../i18n';
import { fetchData, saveData } from './api';

const EnquiryButtonTab = ({ openPopup }) => {
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
        console.log(modifiedFields);
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
                                <label>Enable</label>
                            </th>
                            <td>
                                <FormToggle
                                    checked={data.gmwqp_enable_setting === 'yes'}
                                    onChange={() =>
                                        updateField(
                                            'gmwqp_enable_setting',
                                            data.gmwqp_enable_setting === 'yes' ? 'no' : 'yes'
                                        )
                                    }
                                />
                            </td>
                        </tr>
                        <tr>
                            <th>
                                <label>Display Page</label>
                            </th>
                            <td>
                                <RadioControl
                                    selected={data.gmwqp_display}
                                    options={[
                                        { label: 'Shop and Single Product Page', value: 'all' },
                                        { label: 'Single Product Page', value: 'single' },
                                    ]}
                                    onChange={(value) => updateField('gmwqp_display', value)}
                                />
                            </td>
                        </tr>
                        <tr>
                            <th>
                                <label>Single Product Button Location</label>
                            </th>
                            <td>
                                <RadioControl
                                    selected={data.gmwqp_sp_bl}
                                    options={[
                                        { label: 'After Add to Cart Button', value: 'after_add_cart' },
                                        { label: 'Enquiry in Tab', value: 'tabshow' },
                                        { label: 'Custom Location', value: 'custom' },
                                    ]}
                                    onChange={(value) => updateField('gmwqp_sp_bl', value)}
                                />
                                <h4>
                                    Note: Custom Location requires using the shortcode{' '}
                                    <code>[gmwqp_enquiry_single_product]</code> or{' '}
                                    <code>[gmwqp_enquiry_single_product id="product_id"]</code>
                                </h4>
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

export default EnquiryButtonTab;
