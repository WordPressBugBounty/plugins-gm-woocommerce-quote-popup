import React, { useId } from 'react';

const SelectControl = ({
    label,
    value,
    onChange,
    options = [],
    className = '',
    ...rest
}) => {
    const id = useId();

    return (
        <div className={`gmwqp-field ${className}`.trim()}>
            {label && <label htmlFor={id} className="gmwqp-field__label">{label}</label>}
            <div className="gmwqp-select-wrap">
                <select
                    id={id}
                    className="gmwqp-select"
                    value={value ?? ''}
                    onChange={(e) => onChange && onChange(e.target.value)}
                    {...rest}
                >
                    {options.map((opt) => (
                        <option key={opt.value} value={opt.value} disabled={opt.disabled}>
                            {opt.label}
                        </option>
                    ))}
                </select>
            </div>
        </div>
    );
};

export default SelectControl;
