import { useEffect, useState } from 'react';
import {
  DataTable,
  Badge,
  Icon,
  useConfirm,
  ProBadge,
  PanelBody,
  Button,
  Modal,
  TextControl,
  TextareaControl,
  Spinner,
  RadioControl,
  NumberControl,
  SelectControl,
  FormToggle,
} from '../components';
import { fetchData, saveData } from './api';

const FormCustomizerTab = ({ openPopup }) => {
  const confirm = useConfirm();
  const PROTECTED = ['name', 'email', 'subject', 'mobile', 'enquiry'];
  const [isOpen, setIsOpen] = useState(false);
  
  const [isSaving, setIsSaving] = useState(false);
  const [fields, setFields] = useState([]); // Store existing fields
  const [editingFieldId, setEditingFieldId] = useState(null); // For editing
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState('');
  const [isDeleting, setIsDeleting] = useState(false);
  const [fieldName, setFieldName] = useState('');
  const [orderNumber, setOrderNumber] = useState('');
  const [fieldType, setFieldType] = useState('text'); // Field type state
  const [fieldLabel, setFieldLabel] = useState(''); // Field label state
  const [fieldRequired, setFieldRequired] = useState(false); // Field required state
  const [fieldEnable, setFieldEnable] = useState(false); 
  const [fieldOptions, setFieldOptions] = useState(''); // For options like select, radio

  useEffect(() => {
    fetchFields(); // Fetch existing fields on mount
  }, []);

  const fetchFields = () => {
    fetchData(gmwqp_wp_ajax.getsettings, gmwqp_wp_ajax.nonce)
      .then((fetchedData) => {
        setFields(fetchedData.custom_fields);
        setLoading(false);
      })
      .catch((error) => {
        setLoading(false);
        console.error('Error fetching settings:', error);
      });
  };

  const openModal = (field = null) => {
    setIsOpen(true);
    if (field) {
    console.log(field.options);
      setFieldName(field.name);
      setFieldLabel(field.label); // Label is being used as title
      setOrderNumber(field.order);
      setFieldType(field.type);
      setFieldRequired(field.required);
      setFieldEnable(field.enable);
      setFieldOptions(field.options.join('\n')); // Assuming options are stored as an array
    } else {
      resetForm(); // Reset for new field
    }
  };

  const closeModal = () => {
    setIsOpen(false);
    resetForm(); // Reset the form on close
  };

  const resetForm = () => {
    setFieldName('');
    setFieldLabel('');
    setOrderNumber('');
    setFieldType('text');
    setFieldRequired('');
    setFieldEnable('');
    setFieldOptions('');
    setEditingFieldId(null);
    setError("");
  };
 console.log(fieldType);
  const saveField = () => {

    const data = {
      name: fieldName,
      label: fieldLabel,
      order: orderNumber,
      type: fieldType,
      required: fieldRequired,
      enable: fieldEnable,
      options: fieldOptions,
    };
    setIsSaving(true);
    saveData(gmwqp_wp_ajax.savecustomfield, gmwqp_wp_ajax.nonce, data)
      .then((responseData) => {
        setIsSaving(false);
        if (responseData.success) {
          fetchFields();
          openPopup('Settings saved!');
        } else {
          setError(responseData.message);
        }
      })
      .catch((error) => {
        setIsSaving(false);
        console.error('Error saving settings:', error);
        openPopup('Failed to save settings.');
      });
  };

  const deleteField = async (fieldId) => {
    const ok = await confirm({ title: 'Delete this field?', message: 'The field will be removed from the enquiry form.' });
    if (ok) {
      setIsDeleting(true);
      const data = {
        name: fieldId,
      };
      saveData(gmwqp_wp_ajax.deletecustomfield, gmwqp_wp_ajax.nonce, data)
        .then((responseData) => {
          setIsDeleting(false);
          if (responseData.success) {
            fetchFields();
            openPopup(responseData.message);
          } else {
            openPopup(responseData.message);
          }
        })
        .catch((error) => {
          setIsDeleting(false);
          console.error('Error deleting field:', error);
          openPopup('Failed to delete field.');
        });
    }
  };

  return (
    <PanelBody>
      <div>
        <Button isPrimary onClick={() => openModal()} className="mb-10">
          <Icon name="plus" size={15} /> Add Field
        </Button>
      </div>
      {loading ? (
        <Spinner />
      ) : (
        <>
          <DataTable
            emptyText="No fields available."
            rowKey="name"
            rows={fields}
            columns={[
              { key: 'label', title: 'Label', render: (f) => <strong>{f.label}</strong> },
              { key: 'type', title: 'Type', render: (f) => <Badge tone="info">{f.type}</Badge> },
              { key: 'enable', title: 'Status', render: (f) => (
                <Badge tone={f.enable === 'yes' ? 'success' : 'neutral'}>{f.enable === 'yes' ? 'Enabled' : 'Disabled'}</Badge>
              ) },
              { key: 'required', title: 'Required', render: (f) => (f.required === 'yes' ? <Badge tone="warn">Required</Badge> : <span className="gmwqp-muted">Optional</span>) },
              { key: 'order', title: 'Order' },
              { key: 'options', title: 'Options', render: (f) => {
                const list = Array.isArray(f.options) ? f.options : (f.options || '').split('\n').filter(Boolean);
                return list.length ? <span className="gmwqp-chips">{list.map((o, i) => <span key={i} className="gmwqp-chip">{o}</span>)}</span> : <span className="gmwqp-muted">—</span>;
              } },
              { key: '__a', title: 'Actions', width: 110, render: (f) => (
                <span className="gmwqp-row-actions">
                  <Button isSmall onClick={() => openModal(f)} aria-label="Edit field"><Icon name="edit" size={14} /></Button>
                  {!PROTECTED.includes(f.name) && (
                    <Button isSmall isDestructive onClick={() => deleteField(f.name)} disabled={isDeleting} aria-label="Delete field"><Icon name="trash" size={14} /></Button>
                  )}
                </span>
              ) },
            ]}
          />
        </>
      )}
      {isOpen && (
        <Modal title="Custom Field Add/Edit" onRequestClose={closeModal}>
          <div>

            {!['name', 'email', 'subject', 'mobile', 'enquiry'].includes(fieldName) && (    
                <div className="mb-10">
                    <SelectControl
                      label="Field Type"
                      value={fieldType}
                      onChange={setFieldType}
                      options={[
                        { label: 'Text', value: 'text' },
                        { label: 'Textarea', value: 'textarea' },
                        { label: 'Select (Pro)', value: 'select', disabled: true },
                        { label: 'Radio (Pro)', value: 'radio', disabled: true },
                        { label: 'Checkbox (Pro)', value: 'checkbox', disabled: true },
                      ]}
                    />
                </div>
            )}
            <TextControl
              label="Field Label"
              value={fieldLabel}
              onChange={(value) => setFieldLabel(value)}
              placeholder="Enter Field Label"
            />
            <NumberControl
              label="Field Order Number"
              value={orderNumber}
              onChange={(value) => setOrderNumber(value)}
              placeholder="Enter Field Order Number"
              className="mb-10"
            />


            {!['name', 'email'].includes(fieldName) && ( 
            <div className="mb-10">
                <RadioControl
                    label="Field Enabled"  
                    selected={fieldEnable}
                    options={ [
                        { label: 'Yes', value: 'yes' },
                        { label: 'No', value: 'no' },
                    ] }
                    onChange={(value) => setFieldEnable(value)}
                   
                />
                 </div>
             )}
             {!['name', 'email'].includes(fieldName) && ( 
             <div>
                    <RadioControl
                        label="Field Required"  
                        selected={fieldRequired}
                        options={ [
                            { label: 'Yes', value: 'yes' },
                            { label: 'No', value: 'no' },
                        ] }
                        onChange={(value) => setFieldRequired(value)}
                       
                    />
                     </div>
            )}
            <div className="gmwqp-pro-field">
              <TextControl
                label={<>Validation Message <ProBadge /></>}
                value=""
                disabled
                placeholder="Custom message for this field"
                onChange={() => {}}
              />
            </div>
          </div>
           {error!='' && ( 
              <div className="mt-10 errormsg">
              {error}
              </div>
           )}
          <div  className="mt-10">
            <Button isPrimary onClick={saveField} disabled={isSaving}>
              {isSaving ? 'Saving...' : 'Save Field'}
            </Button>
            <Button isSecondary onClick={closeModal}>
              Cancel
            </Button>
          </div>
        </Modal>
      )}
    </PanelBody>
  );
};

export default FormCustomizerTab;
