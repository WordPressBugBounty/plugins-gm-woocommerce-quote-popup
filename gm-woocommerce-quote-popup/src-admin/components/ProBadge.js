import React from 'react';

const PRO_URL = 'https://www.codesmade.com/store/product-enquiry-for-woocommerce-pro/';

export const ProBadge = ({ label = 'PRO', href = PRO_URL }) => (
    <a
        href={href}
        target="_blank"
        rel="noopener noreferrer"
        className="gmwqp-pro-badge"
        title="Upgrade to Pro"
    >
        <span className="gmwqp-pro-badge__lock">&#128274;</span>
        {label}
    </a>
);

/**
 * Wraps a Pro-only field row: dims the control and appends a PRO badge
 * next to the label so Lite users can see what's available in Pro.
 * The badge links out to the Pro product page.
 */
export const LockedField = ({ label, children }) => (
    <div className="gmwqp-locked-field">
        <label className="gmwqp-locked-field__label">
            {label}
            <ProBadge />
        </label>
        <div className="gmwqp-locked-field__control">{children}</div>
    </div>
);

/**
 * A locked settings row for the Lite version: shows what Pro adds, with the control dimmed
 * and non-interactive. No Pro functionality exists in this plugin; this is only a preview.
 */
export const ProRow = ({ title, description, children }) => (
    <tr className="gmwqp-pro-row">
        <th>
            <label>{title}<ProBadge /></label>
            {description && <p className="description">{description}</p>}
        </th>
        <td>
            <div className="gmwqp-pro-row__control" aria-disabled="true" inert="">{children}</div>
        </td>
    </tr>
);

export default ProBadge;
