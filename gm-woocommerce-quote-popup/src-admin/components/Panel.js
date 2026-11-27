import React, { useState } from 'react';

export const Panel = ({ children, className = '' }) => (
    <div className={`gmwqp-panel ${className}`.trim()}>{children}</div>
);

export const PanelBody = ({ title, children, initialOpen = true, className = '' }) => {
    const [open, setOpen] = useState(initialOpen);

    return (
        <div className={`gmwqp-panel-body ${className}`.trim()}>
            {title && (
                <button
                    type="button"
                    className="gmwqp-panel-body__header"
                    onClick={() => setOpen((o) => !o)}
                >
                    <span className="gmwqp-panel-body__title">{title}</span>
                    <span className={`gmwqp-panel-body__chevron ${open ? 'is-open' : ''}`}>
                        &#9662;
                    </span>
                </button>
            )}
            {open && <div className="gmwqp-panel-body__content">{children}</div>}
        </div>
    );
};

export const PanelRow = ({ children, className = '' }) => (
    <div className={`gmwqp-panel-row ${className}`.trim()}>{children}</div>
);

export default Panel;
