import React, { createContext, useCallback, useContext, useEffect, useRef, useState } from 'react';
import Icon from './Icon';

const ToastContext = createContext(() => {});

export const useToast = () => useContext(ToastContext);

const guessType = (message = '') => (/fail|error|invalid|denied|wrong/i.test(message) ? 'error' : 'success');

export const ToastProvider = ({ children }) => {
    const [items, setItems] = useState([]);
    const counter = useRef(0);

    const dismiss = useCallback((id) => {
        setItems((list) => list.filter((t) => t.id !== id));
    }, []);

    const notify = useCallback((message, type) => {
        if (!message) return;
        const id = ++counter.current;
        setItems((list) => [...list, { id, message, type: type || guessType(message) }]);
        setTimeout(() => dismiss(id), 4200);
    }, [dismiss]);

    return (
        <ToastContext.Provider value={notify}>
            {children}
            <div className="gmwqp-toasts" aria-live="polite">
                {items.map((t) => (
                    <div key={t.id} className={`gmwqp-toast gmwqp-toast--${t.type}`} role="status">
                        <span className="gmwqp-toast__icon">
                            <Icon name={t.type === 'error' ? 'alert' : 'check'} size={16} />
                        </span>
                        <span className="gmwqp-toast__msg">{t.message}</span>
                        <button type="button" className="gmwqp-toast__close" aria-label="Dismiss" onClick={() => dismiss(t.id)}>
                            <Icon name="close" size={14} />
                        </button>
                    </div>
                ))}
            </div>
        </ToastContext.Provider>
    );
};
