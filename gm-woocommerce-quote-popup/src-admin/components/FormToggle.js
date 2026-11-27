import React from 'react';

const FormToggle = ({ checked, onChange, className = '' }) => (
    <label className={`gmwqp-toggle ${className}`.trim()}>
        <input
            type="checkbox"
            checked={!!checked}
            onChange={(e) => onChange && onChange(e.target.checked)}
        />
        <span className="gmwqp-toggle__track">
            <span className="gmwqp-toggle__thumb" />
        </span>
    </label>
);

export default FormToggle;
