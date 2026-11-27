import React, { useEffect, useId, useRef } from 'react';

const FOCUSABLE = 'a[href],button:not([disabled]),input:not([disabled]),select:not([disabled]),textarea:not([disabled]),[tabindex]:not([tabindex="-1"])';

/**
 * Custom accessible modal: focus moves in on open, is trapped while open,
 * and returns to the trigger on close. Esc / overlay click / close button dismiss it.
 */
const Modal = ({ title, subtitle, onClose, children, isSubmitting }) => {
    const titleId = useId();
    const dialogRef = useRef(null);

    useEffect(() => {
        const previouslyFocused = document.activeElement;
        document.body.classList.add('gmwqp-f-modal-open');

        const dialog = dialogRef.current;
        const first = dialog && dialog.querySelector('input,select,textarea');
        (first || dialog)?.focus({ preventScroll: true });

        const onKeyDown = (e) => {
            if (e.key === 'Escape') {
                onClose && onClose();
                return;
            }
            if (e.key !== 'Tab' || !dialog) return;
            const nodes = dialog.querySelectorAll(FOCUSABLE);
            if (!nodes.length) return;
            const firstNode = nodes[0];
            const lastNode = nodes[nodes.length - 1];
            if (e.shiftKey && document.activeElement === firstNode) {
                e.preventDefault();
                lastNode.focus();
            } else if (!e.shiftKey && document.activeElement === lastNode) {
                e.preventDefault();
                firstNode.focus();
            }
        };
        document.addEventListener('keydown', onKeyDown);

        return () => {
            document.removeEventListener('keydown', onKeyDown);
            document.body.classList.remove('gmwqp-f-modal-open');
            if (previouslyFocused && previouslyFocused.focus) previouslyFocused.focus({ preventScroll: true });
        };
        // eslint-disable-next-line react-hooks/exhaustive-deps
    }, []);

    return (
        <div
            className="gmwqp-f-overlay"
            onMouseDown={(e) => {
                if (e.target === e.currentTarget) onClose && onClose();
            }}
        >
            <div
                ref={dialogRef}
                tabIndex={-1}
                className={`gmwqp-f-modal ${isSubmitting ? 'is-busy' : ''}`}
                role="dialog"
                aria-modal="true"
                aria-labelledby={title ? titleId : undefined}
            >
                <button type="button" className="gmwqp-f-modal__close" aria-label="Close" onClick={() => onClose && onClose()}>
                    <svg viewBox="0 0 24 24" width="16" height="16" aria-hidden="true">
                        <path d="M6 6l12 12M18 6L6 18" fill="none" stroke="currentColor" strokeWidth="2.2" strokeLinecap="round" />
                    </svg>
                </button>

                {(title || subtitle) && (
                    <div className="gmwqp-f-modal__head">
                        <div>
                            {title && <h3 id={titleId} className="gmwqp-f-modal__title">{title}</h3>}
                            {subtitle && <p className="gmwqp-f-modal__subtitle">{subtitle}</p>}
                        </div>
                    </div>
                )}
                <div className="gmwqp-f-modal__body">{children}</div>
            </div>
        </div>
    );
};

export default Modal;
