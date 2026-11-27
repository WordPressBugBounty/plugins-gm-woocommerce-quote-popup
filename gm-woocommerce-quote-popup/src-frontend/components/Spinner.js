import React from 'react';

const Spinner = ({ className = '' }) => (
    <span className={`gmwqp-f-spinner ${className}`.trim()} role="status" aria-label="Loading">
        <span className="gmwqp-f-spinner__dot" />
        <span className="gmwqp-f-spinner__dot" />
        <span className="gmwqp-f-spinner__dot" />
    </span>
);

export default Spinner;
