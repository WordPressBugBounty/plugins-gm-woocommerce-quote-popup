import React from 'react';

// Tiny inline icon set (24px grid, stroke based) — no icon font, no external dependency.
const PATHS = {
    cursor: 'M5 3l14 7-6 2-2 6-6-15z',
    inbox: 'M3 13h5l1 3h6l1-3h5M3 13l3-8h12l3 8v6H3v-6z',
    sliders: 'M4 6h10M18 6h2M4 12h4M12 12h8M4 18h12M20 18h0M14 4v4M8 10v4M16 16v4',
    filter: 'M3 5h18l-7 8v6l-4-2v-4L3 5z',
    layout: 'M4 4h16v16H4zM4 10h16M10 10v10',
    mail: 'M3 6h18v12H3zM3 7l9 7 9-7',
    globe: 'M12 3a9 9 0 100 18 9 9 0 000-18zM3 12h18M12 3c3 3 3 15 0 18M12 3c-3 3-3 15 0 18',
    book: 'M5 4h10a3 3 0 013 3v13H8a3 3 0 01-3-3V4zM5 17a3 3 0 013-3h10',
    check: 'M4 12.5L9.5 18 20 6',
    alert: 'M12 8v5M12 16.5v.5M10.3 4.2L2.8 17.5A2 2 0 004.5 20.5h15a2 2 0 001.7-3L13.7 4.2a2 2 0 00-3.4 0z',
    info: 'M12 11v5M12 8v.5M12 3a9 9 0 100 18 9 9 0 000-18z',
    close: 'M6 6l12 12M18 6L6 18',
    trash: 'M4 7h16M9 7V4h6v3M6 7l1 13h10l1-13M10 11v6M14 11v6',
    edit: 'M4 20h4L19 9l-4-4L4 16v4zM13 7l4 4',
    plus: 'M12 5v14M5 12h14',
    download: 'M12 4v11M7 10l5 5 5-5M4 20h16',
    search: 'M11 4a7 7 0 100 14 7 7 0 000-14zM20 20l-4-4',
    star: 'M12 3l2.7 5.6 6.1.8-4.5 4.2 1.1 6-5.4-3-5.4 3 1.1-6-4.5-4.2 6.1-.8L12 3z',
    lifebuoy: 'M12 3a9 9 0 100 18 9 9 0 000-18zM12 9a3 3 0 100 6 3 3 0 000-6zM5.6 5.6l3.2 3.2M15.2 15.2l3.2 3.2M18.4 5.6l-3.2 3.2M8.8 15.2l-3.2 3.2',
    chevronL: 'M15 5l-7 7 7 7',
    chevronR: 'M9 5l7 7-7 7',
    chevronD: 'M5 9l7 7 7-7',
    quote: 'M7 7h4v4c0 3-1.5 5-4 6M15 7h4v4c0 3-1.5 5-4 6',
};

const Icon = ({ name, size = 18, className = '' }) => (
    <svg
        className={`gmwqp-icon ${className}`.trim()}
        width={size}
        height={size}
        viewBox="0 0 24 24"
        fill="none"
        stroke="currentColor"
        strokeWidth="1.8"
        strokeLinecap="round"
        strokeLinejoin="round"
        aria-hidden="true"
    >
        <path d={PATHS[name] || ''} />
    </svg>
);

export default Icon;
