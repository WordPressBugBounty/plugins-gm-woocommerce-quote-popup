import React, { useId } from 'react';

const CheckboxControl = ({ label, checked, onChange, className = '', ...rest }) => {
    const id = useId();

    return (
        <label htmlFor={id} className={`gmwqp-checkbox ${className}`.trim()}>
            <input
                id={id}
                type="checkbox"
                checked={!!checked}
                onChange={(e) => onChange && onChange(e.target.checked)}
                {...rest}
            />
            <span className="gmwqp-checkbox__box">
                <svg viewBox="0 0 16 16" className="gmwqp-checkbox__tick">
                    <path d="M3.5 8.5L6.5 11.5L12.5 4.5" />
                </svg>
            </span>
            {label && <span className="gmwqp-checkbox__label">{label}</span>}
        </label>
    );
};

export default CheckboxControl;
