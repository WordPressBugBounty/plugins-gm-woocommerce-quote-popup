import './frontend.scss';
import React, { useState, useEffect, useCallback } from 'react';
import { createRoot } from 'react-dom/client';
import domReady from './ready';
import { Modal, EnquiryForm, Spinner } from './components';

const boot = window.gmwqp_frontend_data || {};

// Settings + a fresh nonce are fetched at runtime so full-page caches can never serve stale values.
let configPromise = null;
const loadConfig = () => {
    if (!configPromise) {
        const body = new FormData();
        body.append('action', 'gmwqp_config');
        configPromise = fetch(boot.ajax_url, { method: 'POST', body, credentials: 'same-origin' })
            .then((r) => r.json())
            .catch(() => {
                configPromise = null; // allow retry on next open
                return null;
            });
    }
    return configPromise;
};

const useConfig = () => {
    const [config, setConfig] = useState(null);
    useEffect(() => {
        let alive = true;
        loadConfig().then((c) => alive && c && setConfig({ ...c, ajax_url: boot.ajax_url }));
        return () => { alive = false; };
    }, []);
    return config;
};

// Only colours that were actually chosen in the admin are applied; anything reset to
// "theme" is simply left alone so the theme / built-in default shows through.
const applyButtonColors = (colors = {}) => {
    const root = document.documentElement;
    const map = [
        ['bg', '--gmwqp-f-btn-bg', 'gmwqp-c-bg'],
        ['bg_hover', '--gmwqp-f-btn-bg-hover', 'gmwqp-c-bg-h'],
        ['text', '--gmwqp-f-btn-text', 'gmwqp-c-text'],
        ['text_hover', '--gmwqp-f-btn-text-hover', 'gmwqp-c-text-h'],
    ];
    map.forEach(([key, cssVar, cls]) => {
        if (colors[key]) {
            root.style.setProperty(cssVar, colors[key]);
            if (cls) root.classList.add(cls);
        } else {
            root.style.removeProperty(cssVar);
            if (cls) root.classList.remove(cls);
        }
    });
};

const PopupApp = ({ config }) => {
    const [product, setProduct] = useState(null);

    useEffect(() => {
        const onClick = (event) => {
            const trigger = event.target.closest('.gmwqp_inq');
            if (!trigger) return;
            event.preventDefault();
            setProduct({
                title: trigger.getAttribute('title') || '',
                id: trigger.getAttribute('attr_id') || '',
            });
        };
        document.addEventListener('click', onClick);
        return () => document.removeEventListener('click', onClick);
    }, []);

    const close = useCallback(() => setProduct(null), []);
    if (!product) return null;

    return (
        <Modal title={config ? config.form_title : ''} subtitle={product.title} onClose={close}>
            {config ? (
                <EnquiryForm config={config} productTitle={product.title} productId={product.id} />
            ) : (
                <div className="gmwqp-f-loading"><Spinner /></div>
            )}
        </Modal>
    );
};

const InlineForm = ({ el }) => {
    const config = useConfig();
    if (!config) return <div className="gmwqp-f-loading"><Spinner /></div>;
    return (
        <EnquiryForm
            config={config}
            productTitle={el.dataset.productTitle || ''}
            productId={el.dataset.productId || ''}
        />
    );
};

const Root = () => {
    const config = useConfig();
    useEffect(() => {
        if (config) {
            applyButtonColors(config.colors);
        }
    }, [config]);
    return <PopupApp config={config} />;
};

const mountInlineForms = () => {
    document.querySelectorAll('.gmwqp-frontend-inline').forEach((el) => {
        if (el.dataset.gmwqpMounted) return;
        el.dataset.gmwqpMounted = 'true';
        createRoot(el).render(<InlineForm el={el} />);
    });
};

domReady(() => {
    let container = document.getElementById('gmwqp-frontend-root');
    if (!container) {
        container = document.createElement('div');
        container.id = 'gmwqp-frontend-root';
        document.body.appendChild(container);
    }
    createRoot(container).render(<Root />);
    mountInlineForms();
    // Warm the config as soon as the browser is idle so the popup opens instantly.
    if (window.requestIdleCallback) {
        window.requestIdleCallback(() => loadConfig(), { timeout: 2000 });
    } else {
        setTimeout(loadConfig, 200);
    }
});
