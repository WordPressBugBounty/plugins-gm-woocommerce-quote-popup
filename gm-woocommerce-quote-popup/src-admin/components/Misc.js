import React from 'react';

export const Text = ({ children, className = '', ...rest }) => (
    <span className={`gmwqp-text ${className}`.trim()} {...rest}>
        {children}
    </span>
);

export const Scrollable = ({ children, scrollDirection = 'y', style = {}, className = '' }) => (
    <div
        className={`gmwqp-scrollable gmwqp-scrollable--${scrollDirection} ${className}`.trim()}
        style={style}
    >
        {children}
    </div>
);

export const Flex = ({ children, className = '', gap = 3, align, justify, ...rest }) => (
    <div
        className={`gmwqp-flex ${className}`.trim()}
        style={{
            gap: `${gap * 4}px`,
            alignItems: align,
            justifyContent: justify,
        }}
        {...rest}
    >
        {children}
    </div>
);

export const FlexItem = ({ children, className = '', ...rest }) => (
    <div className={`gmwqp-flex-item ${className}`.trim()} {...rest}>
        {children}
    </div>
);

export const FlexBlock = ({ children, className = '', ...rest }) => (
    <div className={`gmwqp-flex-block ${className}`.trim()} {...rest}>
        {children}
    </div>
);
