import { useEffect, useState } from 'react';
import {
    PanelBody,
    Button,
    Spinner,
    TextControl,
    ProBadge,
} from '../components';
import { fetchData, saveData } from './api';

const TranslationTab = ({ openPopup, setActiveTab }) => {
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

    const translationArr = gmwqp_wp_ajax.gmwqp_translation || {};

    // Friendly names + help text for each translatable string.
    const META = {
        gmwqp_trasnlation_button_label: { title: 'Enquiry Button Text', help: 'Text on the button that opens the popup.' },
        gmwqp_trasnlation_form_title: { title: 'Popup Title', help: 'Heading shown at the top of the popup.' },
        gmwqp_trasnlation_form_required: { title: 'Validation Message', help: 'Shown under each required field left empty, e.g. "Please Enter" or "This field is required".' },
        gmwqp_trasnlation_email_sucesemsg: { title: 'Success Message', help: 'Shown after the enquiry is sent.' },
        gmwqp_trasnlation_button_submit: { title: 'Submit Button Text', help: 'Text on the popup’s send button.' },
    };

    return (
        <PanelBody>
            {loading ? (
                <Spinner />
            ) : (
                <>
                    <p className="gmwqp-pro-note">
                        <ProBadge /> Editing the button, popup, validation and success texts is available in Pro. The defaults shown below are used in Lite (and can still be translated with a .pot / WPML).
                    </p>
                    <table className="GMadminTable">
                        <tbody>
                            {Object.entries(translationArr).map(([key, value]) => (
                                <tr key={key}>
                                    <th>
                                        <label>{META[key]?.title || value.label}</label>
                                    </th>
                                    <td>
                                        <TextControl
                                            onChange={() => {}}
                                            disabled
                                            value={data[key] || ''}
                                            help={META[key]?.help}
                                        />
                                    </td>
                                </tr>
                            ))}
                        </tbody>
                    </table>
                </>
            )}
        </PanelBody>
    );
};

export default TranslationTab;
