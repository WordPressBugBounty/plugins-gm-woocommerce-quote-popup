import React, { useId } from 'react';

const FormField = ({ field, value, onChange, labelShow, error }) => {
    const id = useId();
    const errId = `${id}-err`;
    const aria = { id, 'aria-invalid': error ? true : undefined, 'aria-describedby': error ? errId : undefined };
    const wrapCls = `gmwqp-f-field${error ? ' has-error' : ''}`;
    const errorNode = error ? <span id={errId} className="gmwqp-f-field__error">{error}</span> : null;
    const showLabel = labelShow !== 'show_placeholder';
    const placeholder = !showLabel
        ? `${field.label}${field.required ? ' *' : ''}`
        : undefined;

    const labelNode = showLabel && (
        <label htmlFor={id} className="gmwqp-f-field__label">
            {field.label}
            {field.required ? <span className="gmwqp-f-field__required">*</span> : null}
        </label>
    );

    if (field.type === 'textarea') {
        return (
            <div className={wrapCls}>
                {labelNode}
                <textarea
                    className="gmwqp-f-textarea"
                    {...aria}
                    name={field.name}
                    placeholder={placeholder}
                    value={value ?? ''}
                    onChange={(e) => onChange(field.name, e.target.value)}
                    rows={4}
                />
                {errorNode}
            </div>
        );
    }

    if (field.type === 'select') {
        return (
            <div className={wrapCls}>
                {labelNode}
                <div className="gmwqp-f-select-wrap">
                    <select
                        className="gmwqp-f-select"
                        {...aria}
                        name={field.name}
                        value={value ?? ''}
                        onChange={(e) => onChange(field.name, e.target.value)}
                    >
                        {!showLabel && <option value="">{field.label}</option>}
                        {(field.options || []).map((opt) => (
                            <option key={opt} value={opt}>{opt}</option>
                        ))}
                    </select>
                </div>
                {errorNode}
            </div>
        );
    }

    if (field.type === 'radio') {
        return (
            <div className={wrapCls}>
                {labelNode}
                <div className="gmwqp-f-radio-group">
                    {(field.options || []).map((opt) => (
                        <label key={opt} className="gmwqp-f-radio">
                            <input
                                type="radio"
                                name={field.name}
                                value={opt}
                                checked={value === opt}
                                onChange={() => onChange(field.name, opt)}
                            />
                            <span className="gmwqp-f-radio__dot" />
                            <span>{opt}</span>
                        </label>
                    ))}
                </div>
                {errorNode}
            </div>
        );
    }

    if (field.type === 'checkbox') {
        const values = Array.isArray(value) ? value : [];
        const toggle = (opt) => {
            const next = values.includes(opt)
                ? values.filter((v) => v !== opt)
                : [...values, opt];
            onChange(field.name, next);
        };
        return (
            <div className={wrapCls}>
                {labelNode}
                <div className="gmwqp-f-checkbox-group">
                    {(field.options || []).map((opt) => (
                        <label key={opt} className="gmwqp-f-checkbox">
                            <input
                                type="checkbox"
                                name={`${field.name}[]`}
                                checked={values.includes(opt)}
                                onChange={() => toggle(opt)}
                            />
                            <span className="gmwqp-f-checkbox__box">
                                <svg viewBox="0 0 16 16" className="gmwqp-f-checkbox__tick">
                                    <path d="M3.5 8.5L6.5 11.5L12.5 4.5" />
                                </svg>
                            </span>
                            <span>{opt}</span>
                        </label>
                    ))}
                </div>
                {errorNode}
            </div>
        );
    }

    // text / email (default)
    return (
        <div className={wrapCls}>
            {labelNode}
            <input
                className="gmwqp-f-input"
                {...aria}
                type={field.type === 'email' ? 'email' : 'text'}
                name={field.name}
                placeholder={placeholder}
                value={value ?? ''}
                onChange={(e) => onChange(field.name, e.target.value)}
            />
            {errorNode}
        </div>
    );
};

export default FormField;
