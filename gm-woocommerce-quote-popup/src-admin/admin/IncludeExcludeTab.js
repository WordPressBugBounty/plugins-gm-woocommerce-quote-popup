import { useEffect, useState } from 'react';
import {
    PanelBody,
    Button,
    RadioControl,
    Spinner,
    CheckboxControl,
    Scrollable,
    FormToggle
} from '../components';
import { fetchData, saveData } from './api';

const IncludeExcludeTab = ({ openPopup, setActiveTab }) => {
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
                ? currentValues.filter((item) => item !== key)
                : [...currentValues, key];

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
                    setModifiedFields({});
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
                        <tbody>
                            <tr>
                                    <th>
                                        <label>Users Show</label>
                                    </th>
                                    <td>
                                        <RadioControl
                                            selected={data.gmwqp_usershow}
                                            options={ [
                                                { label: 'All Users', value: 'all' },
                                                { label: 'Only Logged in Users', value: 'logged_user' },
                                                { label: 'Only Logged out Users', value: 'logged_out' },
                                            ] }
                                            onChange={(value) => updateField('gmwqp_usershow', value)}
                                           
                                        />
                                    </td>
                                </tr>
                            <tr>
                                <th>
                                    <label>Include/Exclude Based On Category</label>
                                </th>
                                <td>
                                    <RadioControl
                                        selected={data.gmwqp_include_exclude}
                                        options={[
                                            { label: 'All', value: 'all' },
                                            { label: 'Include', value: 'include' },
                                            { label: 'Exclude', value: 'exclude' },
                                        ]}
                                        onChange={(value) => updateField('gmwqp_include_exclude', value)}
                                    />
                                </td>
                            </tr>
                            {data.gmwqp_include_exclude === 'include' && (
                                <tr>
                                    <th>
                                        <label>Include From Category</label>
                                    </th>
                                    <td>
                                        <Scrollable
                                            scrollDirection="y"
                                            style={{ height: 300 }}
                                        >
                                            {gmwqp_wp_ajax.gmwqpcategory.map((catdata) => (
                                                <CheckboxControl
                                                    key={String(catdata.term_id)}
                                                    label={catdata.name}
                                                    checked={data.gmwqp_include_category?.includes(String(catdata.term_id))}
                                                    onChange={() => toggleCheckbox('gmwqp_include_category', String(catdata.term_id))}
                                                />
                                            ))}
                                        </Scrollable>
                                    </td>
                                </tr>
                            )}
                            {data.gmwqp_include_exclude === 'exclude' && (
                                <tr>
                                    <th>
                                        <label>Exclude From Category</label>
                                    </th>
                                    <td>
                                        <Scrollable
                                            scrollDirection="y"
                                            style={{ height: 300 }}
                                        >
                                            {gmwqp_wp_ajax.gmwqpcategory.map((catdata) => (
                                                <CheckboxControl
                                                    key={String(catdata.term_id)}
                                                    label={catdata.name}
                                                    checked={data.gmwqp_exclude_category?.includes(String(catdata.term_id))}
                                                    onChange={() => toggleCheckbox('gmwqp_exclude_category', String(catdata.term_id))}
                                                />
                                            ))}
                                        </Scrollable>
                                    </td>
                                </tr>
                            )}
                            <tr>
                                <th>
                                    <label>Show Enquiry Button When Product is out of stock</label>
                                </th>
                                <td>
                                    <FormToggle
                                      checked={data.gmwqp_show_product_outofstock === 'yes'}
                                      onChange={() =>
                                            updateField('gmwqp_show_product_outofstock', data.gmwqp_show_product_outofstock === 'yes' ? 'no' : 'yes')
                                        }
                                    />
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <Button isPrimary onClick={saveSettings} disabled={isSaving}>
                        {isSaving ? <Spinner /> : 'Save Settings'}
                    </Button>
                </>
            )}
        </PanelBody>
    );
};

export default IncludeExcludeTab;
