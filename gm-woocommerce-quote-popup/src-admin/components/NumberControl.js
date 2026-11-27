import React, { useId } from 'react';

const NumberControl = ({
    label,
    value,
    onChange,
    placeholder,
    className = '',
    ...rest
}) => {
    const id = useId();

    return (
        <div className={`gmwqp-field ${className}`.trim()}>
            {label && <label htmlFor={id} className="gmwqp-field__label">{label}</label>}
            <input
                id={id}
                type="number"
                className="gmwqp-input"
                value={value ?? ''}
                placeholder={placeholder}
                onChange={(e) => onChange && onChange(e.target.value)}
                {...rest}
            />
        </div>
    );
};

export default NumberControl;
