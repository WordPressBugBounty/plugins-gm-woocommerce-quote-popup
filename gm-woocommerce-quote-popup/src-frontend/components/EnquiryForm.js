import React, { useState } from 'react';
import FormField from './FormField';
import Spinner from './Spinner';

const EnquiryForm = ({ config, productTitle, productId }) => {
    const [values, setValues] = useState({});
    const [status, setStatus] = useState('idle'); // idle | submitting | success | error
    const [messages, setMessages] = useState([]);

    const fields = config.fields || [];


    const updateValue = (name, value) => {
        setValues((prev) => ({ ...prev, [name]: value }));
        setFieldErrors((prev) => {
            if (!prev[name]) return prev;
            const next = { ...prev };
            delete next[name];
            return next;
        });
    };

    const [fieldErrors, setFieldErrors] = useState({});

    const validate = () => {
        const errors = [];
        const byField = {};
        fields.forEach((field) => {
            if (!field.required) return;
            const value = values[field.name];
            const isEmpty = Array.isArray(value) ? value.length === 0 : !value;
            if (isEmpty) {
                byField[field.name] = config.required_label || 'This field is required';
                errors.push(`${config.required_label || 'This field is required:'} ${field.label}`);
            }
        });
        setFieldErrors(byField);
        return errors;
    };

    const handleSubmit = (e) => {
        e.preventDefault();
        const errors = validate();
        if (errors.length > 0) {
            setStatus('idle');
            setMessages([]);
            return;
        }

        setStatus('submitting');
        setMessages([]);

        const formData = new FormData();
        formData.append('action', 'gmqqp_enquiry');
        formData.append('_ajax_nonce', config.ajax_nonce);
        formData.append('gmqqp_product', productTitle || '');
        formData.append('gmqqp_product_id', productId || '');

        fields.forEach((field) => {
            const value = values[field.name];
            if (field.type === 'checkbox' && Array.isArray(value)) {
                value.forEach((v) => formData.append(`${field.name}[]`, v));
            } else {
                formData.append(field.name, value ?? '');
            }
        });

        if (config.captcha && config.captcha.enabled && window.grecaptcha) {
            try {
                formData.append('g-recaptcha-response', window.grecaptcha.getResponse());
            } catch (err) {
                // recaptcha not ready — server will reject if required
            }
        }

        fetch(config.ajax_url, {
            method: 'POST',
            body: formData,
        })
            .then((res) => res.json())
            .then((response) => {
                if (response.msg === 'error') {
                    setStatus('error');
                    setMessages([stripHtml(response.returnhtml) || 'Something went wrong. Please try again.']);
                } else {
                    setStatus('success');
                    setMessages([config.success_message || 'Thank you! Your enquiry has been sent.']);
                    setValues({});

                    window.dispatchEvent(new CustomEvent('Gm_enquiry_submitted', { detail: response }));

                    if (response.redirect === 'yes' && response.redirect_to) {
                        setTimeout(() => {
                            window.location.replace(response.redirect_to);
                        }, 1500);
                    }
                }
            })
            .catch(() => {
                setStatus('error');
                setMessages(['Something went wrong. Please try again.']);
            });
    };

    const stripHtml = (html) => {
        if (!html) return '';
        const div = document.createElement('div');
        div.innerHTML = html;
        return div.textContent || div.innerText || '';
    };

    return (
        <div className="gmwqp-f-form-wrap">
            {config.content_before && (
                <div
                    className="gmwqp-f-extra-content"
                    dangerouslySetInnerHTML={{ __html: config.content_before }}
                />
            )}

            {status === 'success' ? (
                <div className="gmwqp-f-result gmwqp-f-result--success">
                    <span className="gmwqp-f-result__icon">
                        <svg viewBox="0 0 24 24" width="28" height="28">
                            <path d="M4 12.5L9.5 18L20 6" fill="none" stroke="currentColor" strokeWidth="2.4" strokeLinecap="round" strokeLinejoin="round" />
                        </svg>
                    </span>
                    <p>{messages[0]}</p>
                </div>
            ) : (
                <form className="gmwqp-f-form" onSubmit={handleSubmit} noValidate>
                    {status === 'error' && messages.length > 0 && (
                        <div className="gmwqp-f-result gmwqp-f-result--error" role="alert">
                            {messages.map((m, i) => <p key={i}>{m}</p>)}
                        </div>
                    )}

                    {fields.map((field) => (
                        <FormField
                            key={field.name}
                            field={field}
                            value={values[field.name]}
                            onChange={updateValue}
                            error={fieldErrors[field.name]}
                            labelShow={config.label_show}
                        />
                    ))}

                    {config.captcha && config.captcha.enabled && config.captcha.site_key && (
                        <div className="g-recaptcha" data-sitekey={config.captcha.site_key} />
                    )}

                    <button type="submit" className="gmwqp-f-submit" disabled={status === 'submitting'}>
                        {status === 'submitting' ? <Spinner /> : (config.submit_label || 'Submit')}
                    </button>
                </form>
            )}

            {config.content_after && (
                <div
                    className="gmwqp-f-extra-content"
                    dangerouslySetInnerHTML={{ __html: config.content_after }}
                />
            )}
        </div>
    );
};

export default EnquiryForm;
