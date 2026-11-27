import React from 'react';

const Spinner = ({ className = '' }) => (
    <span className={`gmwqp-spinner ${className}`.trim()} role="status" aria-label="Loading">
        <span className="gmwqp-spinner__dot" />
        <span className="gmwqp-spinner__dot" />
        <span className="gmwqp-spinner__dot" />
    </span>
);

export default Spinner;
