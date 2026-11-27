import React, { createContext, useCallback, useContext, useEffect, useState } from 'react';
import Button from './Button';
import Icon from './Icon';

const ConfirmContext = createContext(() => Promise.resolve(true));

/** const confirm = useConfirm(); if (await confirm({ title, message })) { ... } */
export const useConfirm = () => useContext(ConfirmContext);

export const ConfirmProvider = ({ children }) => {
    const [state, setState] = useState(null);

    const confirm = useCallback(
        (options = {}) => new Promise((resolve) => setState({ ...options, resolve })),
        []
    );

    const close = (answer) => {
        state.resolve(answer);
        setState(null);
    };

    useEffect(() => {
        if (!state) return undefined;
        const onKey = (e) => {
            if (e.key === 'Escape') {
                state.resolve(false);
                setState(null);
            }
        };
        document.addEventListener('keydown', onKey);
        return () => document.removeEventListener('keydown', onKey);
    }, [state]);

    return (
        <ConfirmContext.Provider value={confirm}>
            {children}
            {state && (
                <div className="gmwqp-modal-overlay" onMouseDown={(e) => e.target === e.currentTarget && close(false)}>
                    <div className="gmwqp-confirm" role="alertdialog" aria-modal="true">
                        <span className="gmwqp-confirm__icon"><Icon name="alert" size={22} /></span>
                        <h3 className="gmwqp-confirm__title">{state.title || 'Are you sure?'}</h3>
                        {state.message && <p className="gmwqp-confirm__msg">{state.message}</p>}
                        <div className="gmwqp-confirm__actions">
                            <Button onClick={() => close(false)}>Cancel</Button>
                            <Button isDestructive autoFocus onClick={() => close(true)}>
                                {state.confirmLabel || 'Delete'}
                            </Button>
                        </div>
                    </div>
                </div>
            )}
        </ConfirmContext.Provider>
    );
};
