import { useEffect, useState } from 'react';
import {
    PanelBody,
    Button,
    Spinner,
    TextControl,
    SelectControl,
    DataTable,
    Pagination,
    Icon,
    useConfirm,
} from '../components';
import { __ } from '../i18n';
import { fetchData, saveData } from './api';  // Assuming deleteData is your helper function to handle DELETE requests

const EnquiryListTab = ({ openPopup }) => {
    const confirm = useConfirm();
    const [loading, setLoading] = useState(true);
    const [data, setData] = useState([]);
    const [headers, setHeaders] = useState([]);
    const [currentPage, setCurrentPage] = useState(1);
    const [totalPages, setTotalPages] = useState(1);
    const [startDate, setStartDate] = useState('');
    const [endDate, setEndDate] = useState('');
    const [filterKey, setFilterKey] = useState('');
    const [filterValue, setFilterValue] = useState('');

    useEffect(() => {
        fetchEnquiries(currentPage, startDate, endDate, filterKey, filterValue);
    }, [currentPage]);

    const handleFilter = () => {
        setCurrentPage(1);
        fetchEnquiries(1, startDate, endDate, filterKey, filterValue);
    };

    const clearFilter = () => {
        setStartDate('');
        setEndDate('');
        setFilterKey('');
        setFilterValue('');
        setCurrentPage(1);
        fetchEnquiries(1, '', '', '', '');
    };

    const fetchEnquiries = (page, start, end, fKey, fValue) => {
        setLoading(true);
        let url = `${gmwqp_wp_ajax.getenquirys}&page=${page}&per_page=10`;
        if (start) url += `&start_date=${start}`;
        if (end) url += `&end_date=${end}`;
        if (fKey && fValue) {
            url += `&filter_key=${fKey}&filter_value=${fValue}`;
        }

        fetchData(url, gmwqp_wp_ajax.nonce)
            .then((response) => {
                setData(response.enquiry || []);
                setHeaders(response.header ? Object.entries(response.header) : []);
                setTotalPages(response.pagination?.total_pages || 1);
                setLoading(false);
            })
            .catch((error) => {
                setLoading(false);
                console.error('Error fetching enquiries:', error);
            });
    };

    const runDelete = (payload, okMsg, failMsg) => {
        setLoading(true);
        saveData(gmwqp_wp_ajax.deleteallenquirys, gmwqp_wp_ajax.nonce, payload)
            .then((response) => {
                setLoading(false);
                if (response.success) {
                    openPopup(okMsg, 'success');
                    fetchEnquiries(currentPage, startDate, endDate, filterKey, filterValue);
                } else {
                    openPopup(failMsg, 'error');
                }
            })
            .catch((error) => {
                setLoading(false);
                console.error(failMsg, error);
                openPopup(failMsg, 'error');
            });
    };

    const deleteAllEnquiries = async () => {
        const ok = await confirm({
            title: 'Delete all enquiries?',
            message: 'Every stored enquiry will be removed permanently. This cannot be undone.',
            confirmLabel: 'Delete all',
        });
        if (ok) runDelete(undefined, 'All enquiries have been deleted.', 'Failed to delete enquiries.');
    };

    const deleteEnquiry = async (id) => {
        const ok = await confirm({
            title: 'Delete this enquiry?',
            message: 'This enquiry will be removed permanently.',
        });
        if (ok) runDelete({ id }, 'Enquiry deleted.', 'Failed to delete enquiry.');
    };

    const columns = [
        ...headers.map(([key, title]) => ({
            key,
            title,
            render: (row) => {
                const v = row[key];
                return v === undefined || v === null || v === '' ? <span className="gmwqp-muted">N/A</span> : v;
            },
        })),
        {
            key: '__actions',
            title: 'Actions',
            width: 90,
            render: (row) => (
                <Button isDestructive isSmall onClick={() => deleteEnquiry(row.id)} aria-label="Delete enquiry">
                    <Icon name="trash" size={14} />
                </Button>
            ),
        },
    ];

    return (
        <PanelBody>
            <div className="gmwqp-toolbar">
                <div className="gmwqp-filters">
                    <TextControl label="From" type="date" value={startDate} onChange={setStartDate} />
                    <TextControl label="To" type="date" value={endDate} onChange={setEndDate} />
                    <SelectControl
                        label="Filter by"
                        value={filterKey}
                        options={[
                            { label: 'Any field', value: '' },
                            ...headers
                                .filter(([key]) => key !== 'id' && key !== 'date')
                                .map(([key, value]) => ({ label: value, value: key })),
                        ]}
                        onChange={setFilterKey}
                    />
                    <TextControl label="Search" placeholder="Type to match…" value={filterValue} onChange={setFilterValue} />
                    <div className="gmwqp-filters__actions">
                        <Button isPrimary onClick={handleFilter}><Icon name="search" size={15} /> Filter</Button>
                        <Button onClick={clearFilter}>Clear</Button>
                    </div>
                </div>
                <div className="gmwqp-toolbar__right">
                    <Button onClick={() => window.open(`${gmwqp_wp_ajax.site_url}/?action=download_enquiery_data`, '_blank')}>
                        <Icon name="download" size={15} /> Export
                    </Button>
                    <Button isDestructive onClick={deleteAllEnquiries}>
                        <Icon name="trash" size={15} /> Delete all
                    </Button>
                </div>
            </div>

            {loading ? (
                <div className="gmwqp-loading"><Spinner /></div>
            ) : (
                <>
                    <DataTable columns={columns} rows={data} emptyText="No enquiries found." />
                    <Pagination page={currentPage} totalPages={totalPages} onChange={setCurrentPage} />
                </>
            )}
        </PanelBody>
    );
};

export default EnquiryListTab;
