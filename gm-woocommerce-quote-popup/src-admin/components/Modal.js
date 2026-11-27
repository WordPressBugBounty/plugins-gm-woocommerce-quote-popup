import React, { useEffect } from 'react';

const Modal = ({ title, onRequestClose, children, className = '' }) => {
    useEffect(() => {
        const onKeyDown = (e) => {
            if (e.key === 'Escape') onRequestClose && onRequestClose();
        };
        document.addEventListener('keydown', onKeyDown);
        document.body.classList.add('gmwqp-modal-open');
        return () => {
            document.removeEventListener('keydown', onKeyDown);
            document.body.classList.remove('gmwqp-modal-open');
        };
    }, [onRequestClose]);

    return (
        <div className="gmwqp-modal-overlay" onMouseDown={(e) => {
            if (e.target === e.currentTarget) onRequestClose && onRequestClose();
        }}>
            <div className={`gmwqp-modal ${className}`.trim()} role="dialog" aria-modal="true">
                <div className="gmwqp-modal__header">
                    <h2 className="gmwqp-modal__title">{title}</h2>
                    <button
                        type="button"
                        className="gmwqp-modal__close"
                        aria-label="Close"
                        onClick={onRequestClose}
                    >
                        &times;
                    </button>
                </div>
                <div className="gmwqp-modal__body">{children}</div>
            </div>
        </div>
    );
};

export default Modal;
