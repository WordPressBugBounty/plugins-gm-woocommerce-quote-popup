import React from 'react';
import Button from './Button';
import Icon from './Icon';

export const Badge = ({ tone = 'neutral', children }) => (
    <span className={`gmwqp-badge gmwqp-badge--${tone}`}>{children}</span>
);

/**
 * columns: [{ key, title, render?(row), width? }]
 * rows: array of objects (row.id used as key when present)
 */
export const DataTable = ({ columns = [], rows = [], emptyText = 'Nothing here yet.', rowKey = 'id' }) => (
    <div className="gmwqp-table-wrap">
        <table className="gmwqp-table">
            <thead>
                <tr>
                    {columns.map((c) => (
                        <th key={c.key} style={c.width ? { width: c.width } : undefined}>{c.title}</th>
                    ))}
                </tr>
            </thead>
            <tbody>
                {rows.length ? (
                    rows.map((row, i) => (
                        <tr key={row[rowKey] ?? i}>
                            {columns.map((c) => (
                                <td key={c.key} data-label={typeof c.title === 'string' ? c.title : ''}>
                                    {c.render ? c.render(row) : (row[c.key] ?? '—')}
                                </td>
                            ))}
                        </tr>
                    ))
                ) : (
                    <tr>
                        <td className="gmwqp-table__empty" colSpan={columns.length}>
                            <Icon name="inbox" size={28} />
                            <span>{emptyText}</span>
                        </td>
                    </tr>
                )}
            </tbody>
        </table>
    </div>
);

export const Pagination = ({ page, totalPages, onChange }) => (
    <div className="gmwqp-pagination">
        <Button isSmall disabled={page <= 1} onClick={() => onChange(page - 1)}>
            <Icon name="chevronL" size={14} /> Previous
        </Button>
        <span className="gmwqp-pagination__info">Page <strong>{page}</strong> of {totalPages}</span>
        <Button isSmall disabled={page >= totalPages} onClick={() => onChange(page + 1)}>
            Next <Icon name="chevronR" size={14} />
        </Button>
    </div>
);
