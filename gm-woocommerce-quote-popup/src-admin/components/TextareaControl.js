import React, { useId } from 'react';

const TextareaControl = ({
    label,
    value,
    onChange,
    placeholder,
    rows = 4,
    className = '',
    help,
    ...rest
}) => {
    const id = useId();

    return (
        <div className={`gmwqp-field ${className}`.trim()}>
            {label && <label htmlFor={id} className="gmwqp-field__label">{label}</label>}
            <textarea
                id={id}
                className="gmwqp-textarea"
                rows={rows}
                value={value ?? ''}
                placeholder={placeholder}
                onChange={(e) => onChange && onChange(e.target.value)}
                {...rest}
            />
            {help && <p className="gmwqp-field__help">{help}</p>}
        </div>
    );
};

export default TextareaControl;
