import React, { useId } from 'react';

const RadioControl = ({ label, selected, options = [], onChange, className = '' }) => {
    const name = useId();

    const group = (
        <div className={`gmwqp-radio-group ${className}`.trim()} role="radiogroup" aria-label={label}>
            {options.map((opt) => (
                <label key={opt.value} className="gmwqp-radio">
                    <input
                        type="radio"
                        name={name}
                        value={opt.value}
                        checked={selected === opt.value}
                        onChange={() => onChange && onChange(opt.value)}
                    />
                    <span className="gmwqp-radio__dot" />
                    <span className="gmwqp-radio__label">{opt.label}</span>
                </label>
            ))}
        </div>
    );

    // With a label, render it like every other field (label above the options).
    if (!label) return group;
    return (
        <div className="gmwqp-field gmwqp-field--radio">
            <span className="gmwqp-field__label">{label}</span>
            {group}
        </div>
    );
};

export default RadioControl;
