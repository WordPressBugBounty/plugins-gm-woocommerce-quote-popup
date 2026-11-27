import './admin.scss';
import React from 'react';
import { createRoot } from 'react-dom/client';
import domReady from './ready';
import { Panel, TabPanel, Icon, ToastProvider, ConfirmProvider, useToast } from './components';
import EnquiryButtonTab from './admin/EnquiryButtonTab';
import EnquiryListTab from './admin/EnquiryListTab';
import GeneralSettingTab from './admin/GeneralSettingTab';
import IncludeExcludeTab from './admin/IncludeExcludeTab';
import FormCustomizerTab from './admin/FormCustomizerTab';
import EmailCustomizerTab from './admin/EmailCustomizerTab';
import TranslationTab from './admin/TranslationTab';
import DocumentationTab from './admin/DocumentationTab';
import UpgradeTab from './admin/UpgradeTab';

const PRO_URL = (window.gmwqp_wp_ajax && window.gmwqp_wp_ajax.pro_url) || 'https://www.codesmade.com/store/product-enquiry-for-woocommerce-pro/';

const LINKS = [
    { label: 'Go Pro', icon: 'star', href: PRO_URL },
    { label: 'Documentation', icon: 'book', href: 'https://www.codesmade.com/product-enquiry-for-woocommerce-documentation/' },
    { label: 'Support', icon: 'lifebuoy', href: 'https://www.codesmade.com/contact-us/' },
    { label: 'Rate us', icon: 'check', href: 'https://wordpress.org/support/plugin/gm-woocommerce-quote-popup/reviews/' },
];

const Header = () => (
    <header className="gmwqp-hero">
        <div className="gmwqp-hero__brand">
            <span className="gmwqp-hero__logo"><Icon name="quote" size={26} /></span>
            <div>
                <h1 className="gmwqp-hero__title">Woo Quote Popup <span className="gmwqp-hero__pill">Lite</span></h1>
                <p className="gmwqp-hero__sub">Let customers request a quote on any product, straight from a beautiful popup.</p>
            </div>
        </div>
        <div className="gmwqp-hero__links">
            {LINKS.map((l) => (
                <a key={l.label} href={l.href} target="_blank" rel="noopener noreferrer" className="gmwqp-hero__link">
                    <Icon name={l.icon} size={15} /> {l.label}
                </a>
            ))}
        </div>
    </header>
);

const AdminShell = () => {
    const openPopup = useToast();

    const tabs = [
        { name: 'EnquiryButtonTab', title: 'Enquiry Button', icon: 'cursor', content: <EnquiryButtonTab openPopup={openPopup} /> },
        { name: 'EnquiryListTab', title: 'Enquiry List', icon: 'inbox', content: <EnquiryListTab openPopup={openPopup} /> },
        { name: 'GeneralSettingTab', title: 'General Setting', icon: 'sliders', content: <GeneralSettingTab openPopup={openPopup} /> },
        { name: 'IncludeExcludeTab', title: 'Include / Exclude', icon: 'filter', content: <IncludeExcludeTab openPopup={openPopup} /> },
        { name: 'FormCustomizerTab', title: 'Form Customizer', icon: 'layout', content: <FormCustomizerTab openPopup={openPopup} /> },
        { name: 'EmailCustomizerTab', title: 'Email Customizer', icon: 'mail', content: <EmailCustomizerTab openPopup={openPopup} /> },
        { name: 'TranslationTab', title: 'Translate', icon: 'globe', content: <TranslationTab openPopup={openPopup} /> },
        { name: 'DocumentationTab', title: 'Documentation', icon: 'book', content: <DocumentationTab /> },
        { name: 'UpgradeTab', title: 'Go Pro', icon: 'star', content: <UpgradeTab /> },
    ];

    return (
        <div className="gmwqp-app">
            <Header />
            <Panel>
                <TabPanel activeClass="active-tab" tabs={tabs}>
                    {(tab) => <div className="tab-content active-tab">{tab.content}</div>}
                </TabPanel>
            </Panel>
        </div>
    );
};

const AdminApp = () => (
    <ToastProvider>
        <ConfirmProvider>
            <AdminShell />
        </ConfirmProvider>
    </ToastProvider>
);

domReady(() => {
    const el = document.getElementById('GMWQP-admin-root');
    if (el) createRoot(el).render(<AdminApp />);
});
