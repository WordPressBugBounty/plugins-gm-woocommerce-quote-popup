import React, { useState } from 'react';
import Icon from './Icon';

/**
 * Custom TabPanel — renders a navigation rail (vertical on desktop, scrollable strip on mobile).
 * Props: tabs [{ name, title, icon?, content }], activeClass, children(tab, selected)
 */
const TabPanel = ({ tabs = [], activeClass = 'active-tab', className = '', children }) => {
    const [selected, setSelected] = useState(tabs[0]?.name);

    const onKeyDown = (e, index) => {
        let next = null;
        if (e.key === 'ArrowDown' || e.key === 'ArrowRight') next = (index + 1) % tabs.length;
        if (e.key === 'ArrowUp' || e.key === 'ArrowLeft') next = (index - 1 + tabs.length) % tabs.length;
        if (next !== null) {
            e.preventDefault();
            setSelected(tabs[next].name);
            e.currentTarget.parentElement.children[next].focus();
        }
    };

    return (
        <div className={`gmwqp-tabs ${className}`.trim()}>
            <nav className="gmwqp-tabs__nav" role="tablist" aria-label="Plugin settings">
                {tabs.map((tab, i) => (
                    <button
                        key={tab.name}
                        type="button"
                        role="tab"
                        aria-selected={selected === tab.name}
                        tabIndex={selected === tab.name ? 0 : -1}
                        className={`gmwqp-tabs__tab ${selected === tab.name ? `is-active ${activeClass}` : ''}`.trim()}
                        onClick={() => setSelected(tab.name)}
                        onKeyDown={(e) => onKeyDown(e, i)}
                    >
                        {tab.icon && <Icon name={tab.icon} size={17} />}
                        <span>{tab.title}</span>
                    </button>
                ))}
            </nav>
            <div className="gmwqp-tabs__panel" role="tabpanel" key={selected}>
                {children
                    ? children(tabs.find((t) => t.name === selected) || {}, selected)
                    : tabs.find((t) => t.name === selected)?.content}
            </div>
        </div>
    );
};

export default TabPanel;
