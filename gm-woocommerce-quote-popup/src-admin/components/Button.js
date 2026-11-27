import React from 'react';

/**
 * Custom Button component.
 * isPrimary, isSecondary, isDestructive, isGhost, isSmall, disabled, onClick, className
 */
const Button = ({
    isPrimary,
    isSecondary,
    isDestructive,
    isGhost,
    isSmall,
    disabled,
    onClick,
    className = '',
    children,
    type = 'button',
    ...rest
}) => {
    const variant = isDestructive
        ? 'gmwqp-btn--danger'
        : isPrimary
            ? 'gmwqp-btn--primary'
            : isGhost
                ? 'gmwqp-btn--ghost'
                : 'gmwqp-btn--secondary';

    return (
        <button
            type={type}
            className={`gmwqp-btn ${variant} ${isSmall ? 'gmwqp-btn--sm' : ''} ${className}`.trim()}
            disabled={disabled}
            onClick={onClick}
            {...rest}
        >
            {children}
        </button>
    );
};

export default Button;
