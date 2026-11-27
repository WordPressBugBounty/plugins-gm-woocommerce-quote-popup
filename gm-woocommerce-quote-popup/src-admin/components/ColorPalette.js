import React from 'react';

const DEFAULT_COLORS = [
    '#111827', '#4f46e5', '#2563eb', '#059669', '#d97706',
    '#dc2626', '#db2777', '#6b7280', '#ffffff',
];

const isColor = (v) => typeof v === 'string' && /^#[0-9a-f]{3,8}$/i.test(v);

/**
 * resetTo: value applied by the Reset button ('theme' = use the theme's own colour).
 * resetLabel: text for the Reset button.
 */
const ColorPalette = ({ value, onChange, colors = DEFAULT_COLORS, className = '', resetTo, resetLabel = 'Reset' }) => {
    const hasColor = isColor(value);
    const canReset = resetTo !== undefined && value !== resetTo;

    return (
        <div className={`gmwqp-color-palette ${className}`.trim()}>
            <div className="gmwqp-color-palette__swatches">
                {colors.map((c) => (
                    <button
                        key={c}
                        type="button"
                        className={`gmwqp-color-swatch ${value === c ? 'is-active' : ''}`}
                        style={{ backgroundColor: c }}
                        aria-label={c}
                        onClick={() => onChange && onChange(c)}
                    />
                ))}
            </div>
            <div className="gmwqp-color-palette__row">
                <label className="gmwqp-color-palette__custom">
                    <input
                        type="color"
                        value={hasColor ? value : '#000000'}
                        onChange={(e) => onChange && onChange(e.target.value)}
                    />
                    <span>{hasColor ? value : (resetTo === 'theme' ? 'Theme default' : 'Custom')}</span>
                </label>
                {resetTo !== undefined && (
                    <button
                        type="button"
                        className="gmwqp-color-palette__reset"
                        disabled={!canReset}
                        onClick={() => onChange && onChange(resetTo)}
                    >
                        {resetLabel}
                    </button>
                )}
            </div>
        </div>
    );
};

export default ColorPalette;
