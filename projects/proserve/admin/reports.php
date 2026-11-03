<?php
session_start();
date_default_timezone_set('Asia/Colombo');

/*
|--------------------------------------------------------------------------
| ProServe CRM - Admin Reports
|--------------------------------------------------------------------------
| Session-based portfolio demonstration.
| No database is required.
*/

if (!isset($_SESSION['proserve_quotes']) || !is_array($_SESSION['proserve_quotes'])) {
    $_SESSION['proserve_quotes'] = [
        [
            'reference' => 'QUO-2026-001',
            'customer_name' => 'Amelia Fernando',
            'email' => 'amelia@example.com',
            'service' => 'E-commerce Development',
            'quoted_amount' => 275000,
            'status' => 'UNDER REVIEW',
            'created_at' => date('Y-m-d H:i:s', strtotime('-3 days')),
        ],
        [
            'reference' => 'QUO-2026-002',
            'customer_name' => 'Daniel Perera',
            'email' => 'daniel@example.com',
            'service' => 'Business Management Systems',
            'quoted_amount' => 450000,
            'status' => 'QUOTE SENT',
            'created_at' => date('Y-m-d H:i:s', strtotime('-10 days')),
        ],
        [
            'reference' => 'QUO-2026-003',
            'customer_name' => 'Sarah Wijesinghe',
            'email' => 'sarah@example.com',
            'service' => 'Custom Web Application Development',
            'quoted_amount' => 180000,
            'status' => 'APPROVED',
            'created_at' => date('Y-m-d H:i:s', strtotime('-18 days')),
        ],
        [
            'reference' => 'QUO-2026-004',
            'customer_name' => 'Michael Silva',
            'email' => 'michael@example.com',
            'service' => 'Database Development',
            'quoted_amount' => 85000,
            'status' => 'NEW',
            'created_at' => date('Y-m-d H:i:s', strtotime('-35 days')),
        ],
        [
            'reference' => 'QUO-2026-005',
            'customer_name' => 'Nethmi Jayawardena',
            'email' => 'nethmi@example.com',
            'service' => 'Website Maintenance',
            'quoted_amount' => 25000,
            'status' => 'REJECTED',
            'created_at' => date('Y-m-d H:i:s', strtotime('-55 days')),
        ],
        [
            'reference' => 'QUO-2026-006',
            'customer_name' => 'Kevin Rodrigo',
            'email' => 'kevin@example.com',
            'service' => 'API Integration',
            'quoted_amount' => 85000,
            'status' => 'PENDING',
            'created_at' => date('Y-m-d H:i:s', strtotime('-75 days')),
        ],
    ];
}

if (!isset($_SESSION['proserve_invoices']) || !is_array($_SESSION['proserve_invoices'])) {
    $_SESSION['proserve_invoices'] = [
        [
            'invoice_reference' => 'INV-2026-001',
            'quotation_reference' => 'QUO-2026-001',
            'customer_name' => 'Amelia Fernando',
            'email' => 'amelia@example.com',
            'service' => 'E-commerce Development',
            'amount' => 275000,
            'paid_amount' => 0,
            'status' => 'SENT',
            'issue_date' => date('Y-m-d', strtotime('-12 days')),
            'due_date' => date('Y-m-d', strtotime('+18 days')),
        ],
        [
            'invoice_reference' => 'INV-2026-002',
            'quotation_reference' => 'QUO-2026-002',
            'customer_name' => 'Daniel Perera',
            'email' => 'daniel@example.com',
            'service' => 'Business Management Systems',
            'amount' => 450000,
            'paid_amount' => 450000,
            'status' => 'PAID',
            'issue_date' => date('Y-m-d', strtotime('-30 days')),
            'due_date' => date('Y-m-d', strtotime('-5 days')),
        ],
        [
            'invoice_reference' => 'INV-2026-003',
            'quotation_reference' => 'QUO-2026-003',
            'customer_name' => 'Sarah Wijesinghe',
            'email' => 'sarah@example.com',
            'service' => 'Custom Web Application Development',
            'amount' => 180000,
            'paid_amount' => 0,
            'status' => 'UNPAID',
            'issue_date' => date('Y-m-d', strtotime('-20 days')),
            'due_date' => date('Y-m-d', strtotime('-3 days')),
        ],
        [
            'invoice_reference' => 'INV-2026-004',
            'quotation_reference' => 'QUO-2026-004',
            'customer_name' => 'Michael Silva',
            'email' => 'michael@example.com',
            'service' => 'Database Development',
            'amount' => 85000,
            'paid_amount' => 25000,
            'status' => 'PARTIAL',
            'issue_date' => date('Y-m-d', strtotime('-40 days')),
            'due_date' => date('Y-m-d', strtotime('+22 days')),
        ],
        [
            'invoice_reference' => 'INV-2026-005',
            'quotation_reference' => '',
            'customer_name' => 'Nethmi Jayawardena',
            'email' => 'nethmi@example.com',
            'service' => 'Website Maintenance',
            'amount' => 25000,
            'paid_amount' => 0,
            'status' => 'DRAFT',
            'issue_date' => date('Y-m-d', strtotime('-65 days')),
            'due_date' => date('Y-m-d', strtotime('+10 days')),
        ],
    ];
}

if (!isset($_SESSION['proserve_appointments']) || !is_array($_SESSION['proserve_appointments'])) {
    $_SESSION['proserve_appointments'] = [
        [
            'reference' => 'APT-2026-001',
            'full_name' => 'Amelia Fernando',
            'email' => 'amelia@example.com',
            'service' => 'Web Development Consultation',
            'appointment_date' => date('Y-m-d', strtotime('-2 days')),
            'status' => 'CONFIRMED',
        ],
        [
            'reference' => 'APT-2026-002',
            'full_name' => 'Daniel Perera',
            'email' => 'daniel@example.com',
            'service' => 'Business Systems Consultation',
            'appointment_date' => date('Y-m-d', strtotime('-5 days')),
            'status' => 'COMPLETED',
        ],
        [
            'reference' => 'APT-2026-003',
            'full_name' => 'Sarah Wijesinghe',
            'email' => 'sarah@example.com',
            'service' => 'E-commerce Consultation',
            'appointment_date' => date('Y-m-d', strtotime('-12 days')),
            'status' => 'PENDING CONFIRMATION',
        ],
        [
            'reference' => 'APT-2026-004',
            'full_name' => 'Michael Silva',
            'email' => 'michael@example.com',
            'service' => 'Database Consultation',
            'appointment_date' => date('Y-m-d', strtotime('-25 days')),
            'status' => 'CANCELLED',
        ],
        [
            'reference' => 'APT-2026-005',
            'full_name' => 'Kevin Rodrigo',
            'email' => 'kevin@example.com',
            'service' => 'API Integration Consultation',
            'appointment_date' => date('Y-m-d', strtotime('-45 days')),
            'status' => 'COMPLETED',
        ],
    ];
}

if (!isset($_SESSION['proserve_enquiries']) || !is_array($_SESSION['proserve_enquiries'])) {
    $_SESSION['proserve_enquiries'] = [
        [
            'reference' => 'ENQ-2026-001',
            'name' => 'Amelia Fernando',
            'email' => 'amelia@example.com',
            'service' => 'E-commerce Development',
            'status' => 'NEW',
            'created_at' => date('Y-m-d H:i:s', strtotime('-1 day')),
        ],
        [
            'reference' => 'ENQ-2026-002',
            'name' => 'Daniel Perera',
            'email' => 'daniel@example.com',
            'service' => 'Business Management Systems',
            'status' => 'IN PROGRESS',
            'created_at' => date('Y-m-d H:i:s', strtotime('-8 days')),
        ],
        [
            'reference' => 'ENQ-2026-003',
            'name' => 'Sarah Wijesinghe',
            'email' => 'sarah@example.com',
            'service' => 'Website Maintenance',
            'status' => 'RESPONDED',
            'created_at' => date('Y-m-d H:i:s', strtotime('-20 days')),
        ],
        [
            'reference' => 'ENQ-2026-004',
            'name' => 'Kevin Rodrigo',
            'email' => 'kevin@example.com',
            'service' => 'API Integration',
            'status' => 'CLOSED',
            'created_at' => date('Y-m-d H:i:s', strtotime('-50 days')),
        ],
    ];
}

if (!isset($_SESSION['proserve_leads']) || !is_array($_SESSION['proserve_leads'])) {
    $_SESSION['proserve_leads'] = [];
}

if (!isset($_SESSION['proserve_customers']) || !is_array($_SESSION['proserve_customers'])) {
    $_SESSION['proserve_customers'] = [];
}

/* --------------------------------------------------------------------------
   Helper functions
-------------------------------------------------------------------------- */

function e($value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function reportMoney($amount): string
{
    return 'LKR ' . number_format((float) $amount, 2);
}

function reportValue(array $row, array $keys, $default = '')
{
    foreach ($keys as $key) {
        if (isset($row[$key]) && $row[$key] !== '') {
            return $row[$key];
        }
    }

    return $default;
}

function reportDate(array $row, array $keys): ?string
{
    $value = reportValue($row, $keys, '');

    if ($value === '') {
        return null;
    }

    $timestamp = strtotime((string) $value);

    if ($timestamp === false) {
        return null;
    }

    return date('Y-m-d', $timestamp);
}

function reportInRange(array $row, array $dateKeys, ?string $startDate, string $endDate): bool
{
    $date = reportDate($row, $dateKeys);

    // Keep records without a usable date visible in All time reports only.
    if ($date === null) {
        return $startDate === null;
    }

    if ($date > $endDate) {
        return false;
    }

    if ($startDate !== null && $date < $startDate) {
        return false;
    }

    return true;
}

function reportStatus(array $row): string
{
    return strtoupper(trim((string) reportValue($row, ['status', 'state'], 'UNKNOWN')));
}

function reportAmount(array $row): float
{
    return (float) reportValue(
        $row,
        ['amount', 'quoted_amount', 'estimated_price', 'quote_amount', 'price', 'total'],
        0
    );
}

function reportService(array $row): string
{
    return (string) reportValue(
        $row,
        ['service', 'service_name', 'service_type', 'subject'],
        'Other / Unspecified'
    );
}

function reportCustomerName(array $row): string
{
    return (string) reportValue(
        $row,
        ['customer_name', 'full_name', 'name', 'client_name'],
        'Unknown customer'
    );
}

function reportCountStatus(array $records, array $statuses): int
{
    $count = 0;

    foreach ($records as $record) {
        if (in_array(reportStatus($record), $statuses, true)) {
            $count++;
        }
    }

    return $count;
}

function reportCsvCell($value): string
{
    $value = (string) $value;

    // Prevent spreadsheet formula injection when opening exported CSV files.
    if (preg_match('/^[\s]*[=+\-@]/', $value)) {
        $value = "'" . $value;
    }

    return $value;
}

/* --------------------------------------------------------------------------
   Date range selection
-------------------------------------------------------------------------- */

$allowedRanges = ['7', '30', '90', 'all'];
$range = (string) ($_GET['range'] ?? '30');

if (!in_array($range, $allowedRanges, true)) {
    $range = '30';
}

$endDate = date('Y-m-d');
$startDate = $range === 'all'
    ? null
    : date('Y-m-d', strtotime('-' . ((int) $range - 1) . ' days'));

/* --------------------------------------------------------------------------
   Read and filter session records
-------------------------------------------------------------------------- */

$allInvoices = array_values($_SESSION['proserve_invoices']);
$allQuotes = array_values($_SESSION['proserve_quotes']);
$allAppointments = array_values($_SESSION['proserve_appointments']);
$allEnquiries = array_values($_SESSION['proserve_enquiries']);
$allLeads = array_values($_SESSION['proserve_leads']);
$allCustomers = array_values($_SESSION['proserve_customers']);

$invoices = array_values(array_filter($allInvoices, function ($row) use ($startDate, $endDate) {
    return is_array($row) && reportInRange(
        $row,
        ['issue_date', 'invoice_date', 'created_at', 'date'],
        $startDate,
        $endDate
    );
}));

$quotes = array_values(array_filter($allQuotes, function ($row) use ($startDate, $endDate) {
    return is_array($row) && reportInRange(
        $row,
        ['created_at', 'date', 'requested_at'],
        $startDate,
        $endDate
    );
}));

$appointments = array_values(array_filter($allAppointments, function ($row) use ($startDate, $endDate) {
    return is_array($row) && reportInRange(
        $row,
        ['appointment_date', 'date', 'created_at'],
        $startDate,
        $endDate
    );
}));

$enquiries = array_values(array_filter($allEnquiries, function ($row) use ($startDate, $endDate) {
    return is_array($row) && reportInRange(
        $row,
        ['created_at', 'date', 'submitted_at'],
        $startDate,
        $endDate
    );
}));

$leads = array_values(array_filter($allLeads, function ($row) use ($startDate, $endDate) {
    return is_array($row) && reportInRange(
        $row,
        ['created_at', 'date', 'lead_date'],
        $startDate,
        $endDate
    );
}));

$customers = array_values(array_filter($allCustomers, function ($row) use ($startDate, $endDate) {
    return is_array($row) && reportInRange(
        $row,
        ['created_at', 'registered_at', 'date', 'registration_date'],
        $startDate,
        $endDate
    );
}));

/* --------------------------------------------------------------------------
   Calculate report statistics
-------------------------------------------------------------------------- */

$totalInvoiced = 0;
$totalCollected = 0;
$totalOutstanding = 0;
$overdueCount = 0;

foreach ($invoices as $invoice) {
    $amount = reportAmount($invoice);
    $paid = (float) reportValue($invoice, ['paid_amount', 'amount_paid', 'paid'], 0);
    $status = reportStatus($invoice);

    $totalInvoiced += $amount;

    if ($status === 'PAID') {
        $paid = $amount;
    }

    $paid = max(0, min($paid, $amount));
    $totalCollected += $paid;
    $totalOutstanding += max(0, $amount - $paid);

    $dueDate = reportDate($invoice, ['due_date', 'payment_due_date']);

    if (
        $dueDate !== null &&
        $dueDate < date('Y-m-d') &&
        max(0, $amount - $paid) > 0 &&
        !in_array($status, ['DRAFT', 'CANCELLED', 'VOID'], true)
    ) {
        $overdueCount++;
    }
}

$totalQuoteValue = 0;

foreach ($quotes as $quote) {
    $totalQuoteValue += reportAmount($quote);
}

$approvedQuotes = reportCountStatus($quotes, ['APPROVED', 'ACCEPTED']);
$rejectedQuotes = reportCountStatus($quotes, ['REJECTED', 'DECLINED']);
$pendingQuotes = count($quotes) - $approvedQuotes - $rejectedQuotes;

$quoteConversion = count($quotes) > 0
    ? ($approvedQuotes / count($quotes)) * 100
    : 0;

$completedAppointments = reportCountStatus(
    $appointments,
    ['COMPLETED', 'COMPLETE', 'DONE']
);

$pendingAppointments = reportCountStatus(
    $appointments,
    ['PENDING', 'PENDING CONFIRMATION', 'NEW', 'REQUESTED']
);

$cancelledAppointments = reportCountStatus(
    $appointments,
    ['CANCELLED', 'CANCELED']
);

$confirmedAppointments = reportCountStatus(
    $appointments,
    ['CONFIRMED', 'ACCEPTED', 'SCHEDULED']
);

$newEnquiries = reportCountStatus($enquiries, ['NEW', 'NEW LEAD']);
$openEnquiries = reportCountStatus($enquiries, ['IN PROGRESS']);
$closedEnquiries = reportCountStatus($enquiries, ['CLOSED', 'RESOLVED']);

$knownCustomerEmails = [];

foreach ($customers as $customer) {
    $email = strtolower(trim((string) reportValue($customer, ['email'], '')));

    if ($email !== '') {
        $knownCustomerEmails[$email] = true;
    }
}

$uniqueCustomerEmails = $knownCustomerEmails;

foreach (array_merge($quotes, $invoices, $appointments, $enquiries) as $record) {
    $email = strtolower(trim((string) reportValue($record, ['email', 'customer_email'], '')));

    if ($email !== '') {
        $uniqueCustomerEmails[$email] = true;
    }
}

$uniqueCustomerCount = count($uniqueCustomerEmails);

/* --------------------------------------------------------------------------
   Service performance
-------------------------------------------------------------------------- */

$serviceStats = [];

foreach ($quotes as $quote) {
    $service = reportService($quote);
    $status = reportStatus($quote);

    if (!isset($serviceStats[$service])) {
        $serviceStats[$service] = [
            'name' => $service,
            'requests' => 0,
            'approved' => 0,
            'value' => 0,
        ];
    }

    $serviceStats[$service]['requests']++;
    $serviceStats[$service]['value'] += reportAmount($quote);

    if (in_array($status, ['APPROVED', 'ACCEPTED'], true)) {
        $serviceStats[$service]['approved']++;
    }
}

uasort($serviceStats, function ($a, $b) {
    return $b['value'] <=> $a['value'];
});

$serviceStats = array_slice($serviceStats, 0, 6);

/* --------------------------------------------------------------------------
   CSV export
-------------------------------------------------------------------------- */

if (isset($_GET['export']) && $_GET['export'] === 'csv') {
    $filename = 'proserve-report-' . date('Y-m-d') . '.csv';

    header('Content-Type: text/csv; charset=UTF-8');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Pragma: no-cache');
    header('Expires: 0');

    $output = fopen('php://output', 'w');

    // UTF-8 BOM helps Microsoft Excel recognize UTF-8 text.
    fwrite($output, "\xEF\xBB\xBF");

    fputcsv($output, ['ProServe CRM - Management Report']);
    fputcsv($output, ['Report period', $startDate ?? 'All available dates', $endDate]);
    fputcsv($output, []);
    fputcsv($output, ['FINANCIAL SUMMARY']);
    fputcsv($output, ['Metric', 'Amount (LKR)']);
    fputcsv($output, ['Total invoiced', number_format($totalInvoiced, 2, '.', '')]);
    fputcsv($output, ['Payments collected', number_format($totalCollected, 2, '.', '')]);
    fputcsv($output, ['Outstanding balance', number_format($totalOutstanding, 2, '.', '')]);
    fputcsv($output, ['Overdue invoices', $overdueCount]);
    fputcsv($output, []);

    fputcsv($output, ['QUOTATION SUMMARY']);
    fputcsv($output, ['Total quotations', count($quotes)]);
    fputcsv($output, ['Approved quotations', $approvedQuotes]);
    fputcsv($output, ['Rejected quotations', $rejectedQuotes]);
    fputcsv($output, ['Quotation conversion rate (%)', number_format($quoteConversion, 2)]);
    fputcsv($output, ['Total quotation value (LKR)', number_format($totalQuoteValue, 2, '.', '')]);
    fputcsv($output, []);

    fputcsv($output, ['APPOINTMENT SUMMARY']);
    fputcsv($output, ['Total appointments', count($appointments)]);
    fputcsv($output, ['Completed appointments', $completedAppointments]);
    fputcsv($output, ['Pending appointments', $pendingAppointments]);
    fputcsv($output, ['Cancelled appointments', $cancelledAppointments]);
    fputcsv($output, []);

    fputcsv($output, ['SERVICE PERFORMANCE']);
    fputcsv($output, ['Service', 'Quotation Requests', 'Approved', 'Quotation Value (LKR)']);

    foreach ($serviceStats as $service) {
        fputcsv($output, [
            reportCsvCell($service['name']),
            $service['requests'],
            $service['approved'],
            number_format($service['value'], 2, '.', ''),
        ]);
    }

    fputcsv($output, []);
    fputcsv($output, ['INVOICE DETAILS']);
    fputcsv($output, ['Invoice Reference', 'Customer', 'Service', 'Status', 'Amount (LKR)', 'Paid (LKR)', 'Due Date']);

    foreach ($invoices as $invoice) {
        $amount = reportAmount($invoice);
        $paid = (float) reportValue($invoice, ['paid_amount', 'amount_paid', 'paid'], 0);

        if (reportStatus($invoice) === 'PAID') {
            $paid = $amount;
        }

        fputcsv($output, [
            reportCsvCell(reportValue($invoice, ['invoice_reference', 'invoice_number', 'reference'], '')),
            reportCsvCell(reportCustomerName($invoice)),
            reportCsvCell(reportService($invoice)),
            reportCsvCell(reportStatus($invoice)),
            number_format($amount, 2, '.', ''),
            number_format(min($amount, max(0, $paid)), 2, '.', ''),
            reportValue($invoice, ['due_date', 'payment_due_date'], ''),
        ]);
    }

    fclose($output);
    exit;
}

/* --------------------------------------------------------------------------
   Chart data
-------------------------------------------------------------------------- */

$maxServiceValue = 0;

foreach ($serviceStats as $service) {
    $maxServiceValue = max($maxServiceValue, $service['value']);
}

$maxServiceValue = max($maxServiceValue, 1);

$invoiceStatuses = [];

foreach ($invoices as $invoice) {
    $status = reportStatus($invoice);
    $invoiceStatuses[$status] = ($invoiceStatuses[$status] ?? 0) + 1;
}

arsort($invoiceStatuses);

$quoteStatusGroups = [
    'Approved' => $approvedQuotes,
    'Rejected' => $rejectedQuotes,
    'Other / Pending' => max(0, $pendingQuotes),
];

$maxQuoteCount = max(1, count($quotes));
$maxAppointmentCount = max(1, count($appointments));

$rangeLabels = [
    '7' => 'Last 7 days',
    '30' => 'Last 30 days',
    '90' => 'Last 90 days',
    'all' => 'All time',
];

$exportQuery = http_build_query([
    'range' => $range,
    'export' => 'csv',
]);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Reports & Analytics | ProServe Admin</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
        rel="stylesheet"
    >

    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap"
        rel="stylesheet"
    >

    <style>
        :root {
            --primary: #2563eb;
            --primary-dark: #1d4ed8;
            --navy: #10233f;
            --body-bg: #f4f7fb;
            --muted: #718096;
            --border: #e8edf4;
            --success: #159a68;
            --danger: #dc4c64;
            --warning: #d99416;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: var(--body-bg);
            color: #25334a;
            font-family: 'Inter', 'Trebuchet MS', sans-serif;
            font-size: 13px;
        }

        a {
            text-decoration: none;
        }

        .sidebar {
            position: fixed;
            inset: 0 auto 0 0;
            width: 250px;
            background: var(--navy);
            color: #fff;
            padding: 24px 16px;
            overflow-y: auto;
            z-index: 1030;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 2px 10px 26px;
            color: #fff;
        }

        .brand-icon {
            width: 42px;
            height: 42px;
            display: grid;
            place-items: center;
            border-radius: 13px;
            background: var(--primary);
            font-size: 21px;
        }

        .brand-name {
            font-size: 19px;
            font-weight: 800;
            letter-spacing: -.6px;
        }

        .brand-subtitle {
            margin-top: 3px;
            color: #9eb1ce;
            font-size: 10px;
        }

        .nav-heading {
            margin: 18px 12px 9px;
            color: #8297b6;
            font-size: 10px;
            font-weight: 800;
            letter-spacing: 1.2px;
            text-transform: uppercase;
        }

        .side-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 11px 13px;
            margin: 3px 0;
            border-radius: 9px;
            color: #c1cde0;
            font-size: 12px;
            font-weight: 600;
            transition: .2s;
        }

        .side-link i {
            width: 18px;
            font-size: 16px;
        }

        .side-link:hover,
        .side-link.active {
            background: rgba(75, 128, 240, .19);
            color: #fff;
        }

        .side-link.active {
            box-shadow: inset 3px 0 #60a5fa;
        }

        .sidebar-footer {
            margin-top: 26px;
            padding: 16px 12px;
            border-top: 1px solid rgba(255,255,255,.12);
            color: #9eb1ce;
            font-size: 11px;
            line-height: 1.8;
        }

        .main {
            min-height: 100vh;
            margin-left: 250px;
        }

        .topbar {
            height: 74px;
            padding: 0 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #fff;
            border-bottom: 1px solid var(--border);
        }

        .topbar-title {
            font-size: 16px;
            font-weight: 800;
            color: var(--navy);
        }

        .topbar-subtitle {
            color: var(--muted);
            font-size: 11px;
            margin-top: 4px;
        }

        .admin-avatar {
            width: 37px;
            height: 37px;
            display: grid;
            place-items: center;
            border-radius: 12px;
            background: #e9efff;
            color: var(--primary);
            font-size: 16px;
        }

        .content {
            padding: 28px 30px 35px;
            max-width: 1600px;
            margin: 0 auto;
        }

        .page-heading {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 18px;
            margin-bottom: 24px;
        }

        .page-heading h1 {
            margin: 0;
            color: var(--navy);
            font-size: 24px;
            font-weight: 800;
            letter-spacing: -.8px;
        }

        .page-heading p {
            margin: 8px 0 0;
            color: var(--muted);
            line-height: 1.6;
        }

        .btn-primary-custom {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 10px 15px;
            border: 0;
            border-radius: 9px;
            background: var(--primary);
            color: #fff;
            font-size: 12px;
            font-weight: 700;
            transition: .2s;
        }

        .btn-primary-custom:hover {
            background: var(--primary-dark);
            color: #fff;
        }

        .btn-light-custom {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 10px 14px;
            border: 1px solid var(--border);
            border-radius: 9px;
            background: #fff;
            color: #394a63;
            font-size: 12px;
            font-weight: 700;
        }

        .btn-light-custom:hover {
            background: #f8fafc;
        }

        .filter-panel {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 18px;
            flex-wrap: wrap;
            padding: 16px 18px;
            margin-bottom: 22px;
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 13px;
        }

        .filter-label {
            display: block;
            margin-bottom: 7px;
            color: #596980;
            font-size: 11px;
            font-weight: 700;
        }

        .form-select,
        .form-control {
            min-height: 39px;
            border-color: #dfe6ef;
            border-radius: 8px;
            font-size: 12px;
        }

        .form-select:focus,
        .form-control:focus {
            border-color: #91b4ff;
            box-shadow: 0 0 0 3px rgba(37,99,235,.1);
        }

        .metric-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 17px;
            margin-bottom: 23px;
        }

        .metric-card {
            min-width: 0;
            padding: 19px;
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 14px;
            box-shadow: 0 3px 12px rgba(24, 46, 79, .025);
        }

        .metric-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
        }

        .metric-label {
            color: var(--muted);
            font-size: 11px;
            font-weight: 700;
        }

        .metric-icon {
            width: 39px;
            height: 39px;
            display: grid;
            place-items: center;
            border-radius: 12px;
            font-size: 18px;
        }

        .metric-icon.blue { background: #eaf1ff; color: #2563eb; }
        .metric-icon.green { background: #e5f8ef; color: #159a68; }
        .metric-icon.orange { background: #fff3df; color: #d99416; }
        .metric-icon.purple { background: #f0eaff; color: #805ad5; }

        .metric-value {
            margin-top: 16px;
            color: var(--navy);
            font-size: clamp(18px, 1.7vw, 25px);
            font-weight: 800;
            letter-spacing: -.8px;
            overflow-wrap: anywhere;
        }

        .metric-description {
            margin-top: 8px;
            color: var(--muted);
            font-size: 10px;
            line-height: 1.6;
        }

        .section-grid {
            display: grid;
            grid-template-columns: minmax(0, 1.2fr) minmax(300px, .8fr);
            gap: 20px;
            margin-bottom: 22px;
        }

        .report-card {
            min-width: 0;
            padding: 21px;
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 14px;
            box-shadow: 0 3px 12px rgba(24, 46, 79, .025);
        }

        .report-card + .report-card {
            margin-top: 0;
        }

        .card-heading {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 20px;
        }

        .card-heading h2 {
            margin: 0;
            color: var(--navy);
            font-size: 14px;
            font-weight: 800;
        }

        .card-heading p {
            margin: 6px 0 0;
            color: var(--muted);
            font-size: 11px;
            line-height: 1.6;
        }

        .subtle-tag {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 6px 9px;
            border-radius: 7px;
            background: #f1f5f9;
            color: #64748b;
            font-size: 10px;
            font-weight: 700;
            white-space: nowrap;
        }

        .bar-list {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .bar-topline {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 8px;
            font-size: 11px;
        }

        .bar-name {
            color: #465771;
            font-weight: 600;
            overflow-wrap: anywhere;
        }

        .bar-number {
            color: var(--navy);
            font-weight: 800;
            white-space: nowrap;
        }

        .bar-track {
            height: 8px;
            overflow: hidden;
            border-radius: 20px;
            background: #edf1f7;
        }

        .bar-fill {
            height: 100%;
            min-width: 0;
            border-radius: 20px;
            background: linear-gradient(90deg, #2563eb, #69a0ff);
        }

        .bar-fill.green { background: linear-gradient(90deg, #10966b, #55d6a1); }
        .bar-fill.orange { background: linear-gradient(90deg, #db8a10, #ffc45b); }
        .bar-fill.purple { background: linear-gradient(90deg, #805ad5, #b49aff); }

        .empty-chart {
            display: grid;
            place-items: center;
            padding: 35px 15px;
            border: 1px dashed #dce4ef;
            border-radius: 10px;
            color: #8a98ab;
            text-align: center;
            line-height: 1.7;
        }

        .empty-chart i {
            margin-bottom: 10px;
            font-size: 27px;
        }

        .status-list {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .status-row {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .status-dot {
            width: 9px;
            height: 9px;
            flex: 0 0 9px;
            border-radius: 50%;
        }

        .status-row-label {
            flex: 1;
            color: #52637a;
            font-size: 11px;
            font-weight: 600;
            overflow-wrap: anywhere;
        }

        .status-row-count {
            color: var(--navy);
            font-size: 12px;
            font-weight: 800;
        }

        .status-row-percent {
            min-width: 37px;
            color: #8996a8;
            font-size: 10px;
            text-align: right;
        }

        .status-track {
            height: 6px;
            margin: 7px 0 0 19px;
            overflow: hidden;
            border-radius: 20px;
            background: #edf1f7;
        }

        .status-track > div {
            height: 100%;
            border-radius: 20px;
        }

        .quick-stats {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 12px;
        }

        .quick-stat {
            padding: 15px;
            border: 1px solid var(--border);
            border-radius: 11px;
            background: #fbfcfe;
        }

        .quick-stat-label {
            color: var(--muted);
            font-size: 10px;
            font-weight: 700;
        }

        .quick-stat-value {
            margin-top: 9px;
            color: var(--navy);
            font-size: 21px;
            font-weight: 800;
        }

        .quick-stat-note {
            margin-top: 5px;
            color: #8996a8;
            font-size: 10px;
        }

        .table-responsive {
            border: 1px solid var(--border);
            border-radius: 10px;
        }

        .report-table {
            width: 100%;
            min-width: 570px;
            margin: 0;
            border-collapse: separate;
            border-spacing: 0;
        }

        .report-table thead th {
            padding: 13px 14px;
            background: #f8fafc;
            color: #718096;
            border-bottom: 1px solid var(--border);
            font-size: 10px;
            font-weight: 800;
            letter-spacing: .35px;
            text-transform: uppercase;
            white-space: nowrap;
        }

        .report-table tbody td {
            padding: 14px;
            border-bottom: 1px solid #edf1f6;
            color: #52637a;
            font-size: 11px;
            vertical-align: middle;
        }

        .report-table tbody tr:last-child td {
            border-bottom: 0;
        }

        .report-table tbody tr:hover td {
            background: #fbfcff;
        }

        .service-name {
            color: var(--navy);
            font-weight: 700;
        }

        .amount-text {
            color: var(--navy);
            font-weight: 800;
            white-space: nowrap;
        }

        .small-muted {
            margin-top: 4px;
            color: #8b98aa;
            font-size: 10px;
        }

        .status-badge {
            display: inline-block;
            padding: 6px 8px;
            border-radius: 7px;
            font-size: 9px;
            font-weight: 800;
            white-space: nowrap;
        }

        .status-paid,
        .status-approved,
        .status-completed {
            background: #e6f8ef;
            color: #14865c;
        }

        .status-sent,
        .status-confirmed,
        .status-progress {
            background: #e9f1ff;
            color: #285ec7;
        }

        .status-unpaid,
        .status-overdue,
        .status-rejected,
        .status-cancelled {
            background: #fff0f1;
            color: #c63e55;
        }

        .status-partial,
        .status-pending,
        .status-review {
            background: #fff4df;
            color: #a56b0b;
        }

        .status-draft,
        .status-other {
            background: #f0f3f7;
            color: #64748b;
        }

        .report-footer {
            padding: 24px 10px 4px;
            color: #8996a8;
            font-size: 10px;
            text-align: center;
            line-height: 1.8;
        }

        .mobile-menu-btn {
            display: none;
            border: 0;
            background: transparent;
            color: var(--navy);
            font-size: 22px;
        }

        .sidebar-overlay {
            display: none;
        }

        @media (max-width: 1250px) {
            .metric-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .section-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 900px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform .25s ease;
            }

            .sidebar.open {
                transform: translateX(0);
            }

            .sidebar-overlay.show {
                display: block;
                position: fixed;
                inset: 0;
                z-index: 1020;
                background: rgba(15, 23, 42, .45);
            }

            .main {
                margin-left: 0;
            }

            .mobile-menu-btn {
                display: inline-block;
                margin-right: 10px;
            }

            .topbar {
                padding: 0 20px;
            }

            .content {
                padding: 23px 20px 30px;
            }
        }

        @media (max-width: 600px) {
            .content {
                padding: 19px 13px 25px;
            }

            .topbar {
                height: 65px;
                padding: 0 13px;
            }

            .page-heading {
                flex-direction: column;
            }

            .page-heading h1 {
                font-size: 21px;
            }

            .page-actions {
                display: flex;
                width: 100%;
                gap: 8px;
            }

            .page-actions a,
            .page-actions button {
                flex: 1;
            }

            .metric-grid {
                gap: 10px;
            }

            .metric-card {
                padding: 14px;
            }

            .metric-value {
                font-size: 18px;
            }

            .metric-icon {
                width: 32px;
                height: 32px;
                font-size: 15px;
            }

            .filter-panel {
                align-items: stretch;
                padding: 14px;
            }

            .filter-panel form {
                width: 100%;
            }

            .filter-actions {
                display: flex;
                gap: 8px;
            }

            .filter-actions > * {
                flex: 1;
            }

            .report-card {
                padding: 15px;
            }

            .quick-stats {
                grid-template-columns: 1fr;
            }

            .quick-stat {
                padding: 13px;
            }

            .quick-stat-value {
                font-size: 19px;
            }
        }

        @media print {
            body {
                background: #fff !important;
                font-size: 10px;
            }

            .sidebar,
            .topbar,
            .sidebar-overlay,
            .mobile-menu-btn,
            .page-actions,
            .filter-panel,
            .no-print {
                display: none !important;
            }

            .main {
                margin: 0 !important;
            }

            .content {
                max-width: none;
                padding: 0;
            }

            .page-heading {
                margin-bottom: 15px;
            }

            .metric-grid {
                grid-template-columns: repeat(4, minmax(0, 1fr));
                gap: 8px;
            }

            .metric-card,
            .report-card {
                break-inside: avoid;
                box-shadow: none;
                border: 1px solid #d9e0e8;
                padding: 12px;
            }

            .section-grid {
                grid-template-columns: 1fr 1fr;
                gap: 10px;
            }

            .report-table thead th,
            .report-table tbody td {
                padding: 7px;
            }

            .report-footer {
                padding-top: 12px;
            }

            a {
                color: inherit !important;
            }
        }
    </style>
</head>

<body>

<div class="sidebar-overlay" id="sidebarOverlay"></div>

<aside class="sidebar" id="sidebar">

    <a href="index.php" class="brand">
        <div class="brand-icon">
            <i class="bi bi-grid-1x2-fill"></i>
        </div>

        <div>
            <div class="brand-name">ProServe</div>
            <div class="brand-subtitle">CRM & Service Management</div>
        </div>
    </a>

    <div class="nav-heading">Overview</div>

    <a href="index.php" class="side-link">
        <i class="bi bi-speedometer2"></i>
        Dashboard
    </a>

    <div class="nav-heading">Customer Management</div>

    <a href="customers.php" class="side-link">
        <i class="bi bi-people"></i>
        Customers
    </a>

    <a href="leads.php" class="side-link">
        <i class="bi bi-person-lines-fill"></i>
        Leads
    </a>

    <a href="enquiries.php" class="side-link">
        <i class="bi bi-chat-left-text"></i>
        Enquiries
    </a>

    <a href="appointments.php" class="side-link">
        <i class="bi bi-calendar-check"></i>
        Appointments
    </a>

    <div class="nav-heading">Business Operations</div>

    <a href="services.php" class="side-link">
        <i class="bi bi-box-seam"></i>
        Services
    </a>

    <a href="quotations.php" class="side-link">
        <i class="bi bi-file-earmark-text"></i>
        Quotations
    </a>

    <a href="invoices.php" class="side-link">
        <i class="bi bi-receipt"></i>
        Invoices
    </a>

    <div class="nav-heading">Analytics</div>

    <a href="reports.php" class="side-link active">
        <i class="bi bi-bar-chart-line"></i>
        Reports & Analytics
    </a>

    <div class="sidebar-footer">
        <strong style="color:#fff;">ProServe Admin</strong><br>
        Management reporting dashboard<br>
        Portfolio demonstration by Codvanta
    </div>

</aside>

<main class="main">

    <header class="topbar">

        <div class="d-flex align-items-center">
            <button class="mobile-menu-btn" id="mobileMenuBtn" aria-label="Open navigation">
                <i class="bi bi-list"></i>
            </button>

            <div>
                <div class="topbar-title">Reports & Analytics</div>
                <div class="topbar-subtitle">Business performance and management insights</div>
            </div>
        </div>

        <div class="d-flex align-items-center gap-3">
            <div class="text-end d-none d-sm-block">
                <div style="font-size:12px;font-weight:800;color:#10233f;">Administrator</div>
                <div style="font-size:10px;color:#8996a8;margin-top:3px;">System Management</div>
            </div>

            <div class="admin-avatar">
                <i class="bi bi-person-fill-gear"></i>
            </div>
        </div>

    </header>

    <div class="content">

        <section class="page-heading">

            <div>
                <h1>Reports & Analytics</h1>
                <p>
                    Monitor financial performance, quotations, appointments, customers,
                    and service demand from one dashboard.
                </p>
            </div>

            <div class="page-actions d-flex gap-2 flex-wrap">

                <button type="button" class="btn-light-custom" onclick="window.print()">
                    <i class="bi bi-printer"></i>
                    Print / PDF
                </button>

                <a href="?<?= e($exportQuery) ?>" class="btn-primary-custom">
                    <i class="bi bi-download"></i>
                    Export CSV
                </a>

            </div>

        </section>

        <section class="filter-panel">

            <div>
                <div style="font-size:13px;font-weight:800;color:#10233f;">
                    <i class="bi bi-calendar3 me-2" style="color:#2563eb;"></i>
                    Report period
                </div>

                <div style="margin-top:5px;color:#8996a8;font-size:11px;">
                    Select a period to update the report metrics.
                </div>
            </div>

            <form method="GET" class="d-flex align-items-end gap-2 flex-wrap">

                <div style="min-width:160px;">
                    <label for="range" class="filter-label">Date range</label>

                    <select name="range" id="range" class="form-select">
                        <?php foreach ($rangeLabels as $key => $label): ?>
                            <option
                                value="<?= e($key) ?>"
                                <?= $range === $key ? 'selected' : '' ?>
                            >
                                <?= e($label) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="filter-actions d-flex gap-2">
                    <button type="submit" class="btn-primary-custom">
                        <i class="bi bi-funnel"></i>
                        Apply
                    </button>

                    <a href="reports.php" class="btn-light-custom">
                        Reset
                    </a>
                </div>

            </form>

        </section>

        <div class="metric-grid">

            <article class="metric-card">
                <div class="metric-top">
                    <div class="metric-label">Total Invoiced</div>
                    <div class="metric-icon blue">
                        <i class="bi bi-receipt-cutoff"></i>
                    </div>
                </div>

                <div class="metric-value"><?= e(reportMoney($totalInvoiced)) ?></div>

                <div class="metric-description">
                    <?= count($invoices) ?> invoice(s) issued during the selected period
                </div>
            </article>

            <article class="metric-card">
                <div class="metric-top">
                    <div class="metric-label">Payments Collected</div>
                    <div class="metric-icon green">
                        <i class="bi bi-cash-stack"></i>
                    </div>
                </div>

                <div class="metric-value"><?= e(reportMoney($totalCollected)) ?></div>

                <div class="metric-description">
                    Recorded payments against the selected invoices
                </div>
            </article>

            <article class="metric-card">
                <div class="metric-top">
                    <div class="metric-label">Outstanding Balance</div>
                    <div class="metric-icon orange">
                        <i class="bi bi-wallet2"></i>
                    </div>
                </div>

                <div class="metric-value"><?= e(reportMoney($totalOutstanding)) ?></div>

                <div class="metric-description">
                    <?= $overdueCount ?> overdue invoice(s) with an unpaid balance
                </div>
            </article>

            <article class="metric-card">
                <div class="metric-top">
                    <div class="metric-label">Quotation Conversion</div>
                    <div class="metric-icon purple">
                        <i class="bi bi-graph-up-arrow"></i>
                    </div>
                </div>

                <div class="metric-value"><?= number_format($quoteConversion, 1) ?>%</div>

                <div class="metric-description">
                    <?= $approvedQuotes ?> approved out of <?= count($quotes) ?> quotation(s)
                </div>
            </article>

        </div>

        <div class="section-grid">

            <section class="report-card">

                <div class="card-heading">
                    <div>
                        <h2>Service Performance</h2>
                        <p>Quotation demand and estimated value by service.</p>
                    </div>

                    <span class="subtle-tag">
                        <i class="bi bi-bar-chart-fill"></i>
                        Top services
                    </span>
                </div>

                <?php if (count($serviceStats) > 0): ?>

                    <div class="bar-list">

                        <?php foreach ($serviceStats as $service): ?>
                            <?php
                                $percentage = ($service['value'] / $maxServiceValue) * 100;
                            ?>

                            <div>
                                <div class="bar-topline">
                                    <span class="bar-name">
                                        <?= e($service['name']) ?>
                                    </span>

                                    <span class="bar-number">
                                        <?= e(reportMoney($service['value'])) ?>
                                    </span>
                                </div>

                                <div class="bar-track">
                                    <div
                                        class="bar-fill"
                                        style="width:<?= min(100, max(0, $percentage)) ?>%;"
                                    ></div>
                                </div>

                                <div class="small-muted">
                                    <?= (int) $service['requests'] ?> request(s)
                                    &middot;
                                    <?= (int) $service['approved'] ?> approved
                                </div>
                            </div>

                        <?php endforeach; ?>

                    </div>

                <?php else: ?>

                    <div class="empty-chart">
                        <div>
                            <i class="bi bi-bar-chart"></i><br>
                            No quotation data is available for this period.
                        </div>
                    </div>

                <?php endif; ?>

            </section>

            <section class="report-card">

                <div class="card-heading">
                    <div>
                        <h2>Quotation Overview</h2>
                        <p>Current distribution of quotation outcomes in this report.</p>
                    </div>

                    <span class="subtle-tag">
                        <?= count($quotes) ?> total
                    </span>
                </div>

                <?php
                    $quoteColors = [
                        'Approved' => '#16a36d',
                        'Rejected' => '#e25568',
                        'Other / Pending' => '#e5a12b',
                    ];
                ?>

                <div class="status-list">

                    <?php foreach ($quoteStatusGroups as $label => $count): ?>
                        <?php $percent = count($quotes) > 0 ? ($count / count($quotes)) * 100 : 0; ?>

                        <div>
                            <div class="status-row">
                                <span
                                    class="status-dot"
                                    style="background:<?= e($quoteColors[$label]) ?>;"
                                ></span>

                                <span class="status-row-label"><?= e($label) ?></span>

                                <span class="status-row-count"><?= (int) $count ?></span>

                                <span class="status-row-percent">
                                    <?= number_format($percent, 1) ?>%
                                </span>
                            </div>

                            <div class="status-track">
                                <div
                                    style="
                                        width:<?= min(100, max(0, $percent)) ?>%;
                                        background:<?= e($quoteColors[$label]) ?>";
                                    
                                ></div>
                            </div>
                        </div>

                    <?php endforeach; ?>

                </div>

                <div style="margin-top:23px;padding-top:18px;border-top:1px solid #edf1f6;">
                    <div style="color:#718096;font-size:11px;font-weight:600;">
                        Total estimated quotation value
                    </div>

                    <div style="margin-top:8px;color:#10233f;font-size:23px;font-weight:800;">
                        <?= e(reportMoney($totalQuoteValue)) ?>
                    </div>
                </div>

            </section>

        </div>

        <div class="section-grid">

            <section class="report-card">

                <div class="card-heading">
                    <div>
                        <h2>Appointment Performance</h2>
                        <p>Appointment status breakdown for the selected period.</p>
                    </div>

                    <span class="subtle-tag">
                        <i class="bi bi-calendar-check"></i>
                        <?= count($appointments) ?> total
                    </span>
                </div>

                <?php
                    $appointmentGroups = [
                        ['label' => 'Completed', 'count' => $completedAppointments, 'color' => '#16a36d'],
                        ['label' => 'Confirmed / Scheduled', 'count' => $confirmedAppointments, 'color' => '#3678e8'],
                        ['label' => 'Pending', 'count' => $pendingAppointments, 'color' => '#e5a12b'],
                        ['label' => 'Cancelled', 'count' => $cancelledAppointments, 'color' => '#e25568'],
                    ];
                ?>

                <div class="status-list">

                    <?php foreach ($appointmentGroups as $group): ?>
                        <?php $percent = count($appointments) > 0 ? ($group['count'] / count($appointments)) * 100 : 0; ?>

                        <div>
                            <div class="status-row">
                                <span
                                    class="status-dot"
                                    style="background:<?= e($group['color']) ?>;"
                                ></span>

                                <span class="status-row-label">
                                    <?= e($group['label']) ?>
                                </span>

                                <span class="status-row-count">
                                    <?= (int) $group['count'] ?>
                                </span>

                                <span class="status-row-percent">
                                    <?= number_format($percent, 1) ?>%
                                </span>
                            </div>

                            <div class="status-track">
                                <div
                                    style="
                                        width:<?= min(100, max(0, $percent)) ?>%;
                                        background:<?= e($group['color']) ?>";
                                    
                                ></div>
                            </div>
                        </div>

                    <?php endforeach; ?>

                </div>

            </section>

            <section class="report-card">

                <div class="card-heading">
                    <div>
                        <h2>Customer & Lead Activity</h2>
                        <p>Customer records and enquiry activity in this period.</p>
                    </div>

                    <span class="subtle-tag">
                        <i class="bi bi-people"></i>
                        CRM
                    </span>
                </div>

                <div class="quick-stats">

                    <div class="quick-stat">
                        <div class="quick-stat-label">Unique Customers</div>
                        <div class="quick-stat-value"><?= $uniqueCustomerCount ?></div>
                        <div class="quick-stat-note">Across available CRM records</div>
                    </div>

                    <div class="quick-stat">
                        <div class="quick-stat-label">New Enquiries</div>
                        <div class="quick-stat-value"><?= $newEnquiries ?></div>
                        <div class="quick-stat-note">Awaiting initial handling</div>
                    </div>

                    <div class="quick-stat">
                        <div class="quick-stat-label">Open Enquiries</div>
                        <div class="quick-stat-value"><?= $openEnquiries ?></div>
                        <div class="quick-stat-note">Currently in progress</div>
                    </div>

                    <div class="quick-stat">
                        <div class="quick-stat-label">Closed Enquiries</div>
                        <div class="quick-stat-value"><?= $closedEnquiries ?></div>
                        <div class="quick-stat-note">Closed or resolved</div>
                    </div>

                    <div class="quick-stat">
                        <div class="quick-stat-label">Leads</div>
                        <div class="quick-stat-value"><?= count($leads) ?></div>
                        <div class="quick-stat-note">Matching selected period</div>
                    </div>

                    <div class="quick-stat">
                        <div class="quick-stat-label">Customer Records</div>
                        <div class="quick-stat-value"><?= count($customers) ?></div>
                        <div class="quick-stat-note">Matching selected period</div>
                    </div>

                </div>

            </section>

        </div>

        <section class="report-card mb-4">

            <div class="card-heading">
                <div>
                    <h2>Invoice Status Summary</h2>
                    <p>Invoice counts and financial values by status.</p>
                </div>

                <a href="invoices.php" class="btn-light-custom no-print">
                    Manage Invoices
                    <i class="bi bi-arrow-up-right"></i>
                </a>
            </div>

            <div class="table-responsive">

                <table class="report-table">
                    <thead>
                        <tr>
                            <th>Invoice Status</th>
                            <th class="text-center">Count</th>
                            <th class="text-end">Invoice Value</th>
                            <th class="text-end">Payments Received</th>
                            <th class="text-end">Outstanding</th>
                        </tr>
                    </thead>

                    <tbody>

                        <?php if (count($invoiceStatuses) > 0): ?>

                            <?php foreach ($invoiceStatuses as $status => $count): ?>
                                <?php
                                    $statusAmount = 0;
                                    $statusPaid = 0;

                                    foreach ($invoices as $invoice) {
                                        if (reportStatus($invoice) !== $status) {
                                            continue;
                                        }

                                        $amount = reportAmount($invoice);
                                        $paid = (float) reportValue($invoice, ['paid_amount', 'amount_paid', 'paid'], 0);

                                        if ($status === 'PAID') {
                                            $paid = $amount;
                                        }

                                        $statusAmount += $amount;
                                        $statusPaid += min($amount, max(0, $paid));
                                    }

                                    $statusOutstanding = max(0, $statusAmount - $statusPaid);

                                    $badgeClass = match ($status) {
                                        'PAID' => 'status-paid',
                                        'SENT' => 'status-sent',
                                        'UNPAID' => 'status-unpaid',
                                        'PARTIAL' => 'status-partial',
                                        'DRAFT' => 'status-draft',
                                        default => 'status-other',
                                    };
                                ?>

                                <tr>
                                    <td>
                                        <span class="status-badge <?= e($badgeClass) ?>">
                                            <?= e($status) ?>
                                        </span>
                                    </td>

                                    <td class="text-center"><?= (int) $count ?></td>

                                    <td class="text-end amount-text">
                                        <?= e(reportMoney($statusAmount)) ?>
                                    </td>

                                    <td class="text-end" style="color:#159a68;font-weight:700;">
                                        <?= e(reportMoney($statusPaid)) ?>
                                    </td>

                                    <td class="text-end" style="color:#d99416;font-weight:700;">
                                        <?= e(reportMoney($statusOutstanding)) ?>
                                    </td>
                                </tr>

                            <?php endforeach; ?>

                        <?php else: ?>

                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">
                                    No invoice records found for the selected period.
                                </td>
                            </tr>

                        <?php endif; ?>

                    </tbody>
                </table>

            </div>

        </section>

        <section class="report-card">

            <div class="card-heading">
                <div>
                    <h2>Top Services by Quotation Value</h2>
                    <p>Service-level summary based on quotations created during this period.</p>
                </div>

                <a href="quotations.php" class="btn-light-custom no-print">
                    Manage Quotations
                    <i class="bi bi-arrow-up-right"></i>
                </a>
            </div>

            <div class="table-responsive">

                <table class="report-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Service</th>
                            <th class="text-center">Requests</th>
                            <th class="text-center">Approved</th>
                            <th class="text-end">Estimated Value</th>
                            <th class="text-end">Share of Value</th>
                        </tr>
                    </thead>

                    <tbody>

                        <?php if (count($serviceStats) > 0): ?>

                            <?php $rank = 1; ?>

                            <?php foreach ($serviceStats as $service): ?>
                                <?php
                                    $share = $totalQuoteValue > 0
                                        ? ($service['value'] / $totalQuoteValue) * 100
                                        : 0;
                                ?>

                                <tr>
                                    <td>
                                        <span style="
                                            display:inline-grid;
                                            place-items:center;
                                            width:27px;
                                            height:27px;
                                            border-radius:8px;
                                            background:#edf3ff;
                                            color:#2563eb;
                                            font-weight:800;
                                        ">
                                            <?= $rank++ ?>
                                        </span>
                                    </td>

                                    <td>
                                        <div class="service-name">
                                            <?= e($service['name']) ?>
                                        </div>
                                    </td>

                                    <td class="text-center">
                                        <?= (int) $service['requests'] ?>
                                    </td>

                                    <td class="text-center">
                                        <span class="status-badge status-approved">
                                            <?= (int) $service['approved'] ?>
                                        </span>
                                    </td>

                                    <td class="text-end amount-text">
                                        <?= e(reportMoney($service['value'])) ?>
                                    </td>

                                    <td class="text-end">
                                        <div style="font-weight:800;color:#10233f;">
                                            <?= number_format($share, 1) ?>%
                                        </div>

                                        <div class="bar-track" style="width:100px;margin:6px 0 0 auto;height:5px;">
                                            <div
                                                class="bar-fill"
                                                style="width:<?= min(100, max(0, $share)) ?>%;"
                                            ></div>
                                        </div>
                                    </td>
                                </tr>

                            <?php endforeach; ?>

                        <?php else: ?>

                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">
                                    No service quotation data is available for this period.
                                </td>
                            </tr>

                        <?php endif; ?>

                    </tbody>
                </table>

            </div>

        </section>

        <footer class="report-footer">
            <strong>ProServe — CRM & Service Management System</strong><br>
            Portfolio demonstration by Codvanta.<br>
            Report generated on <?= e(date('d M Y, h:i A')) ?> ·
            Period: <?= e($startDate ? date('d M Y', strtotime($startDate)) : 'All available dates') ?>
            to <?= e(date('d M Y', strtotime($endDate))) ?>
        </footer>

    </div>

</main>

<script>
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebarOverlay');
    const menuButton = document.getElementById('mobileMenuBtn');

    function closeSidebar() {
        sidebar.classList.remove('open');
        overlay.classList.remove('show');
    }

    menuButton.addEventListener('click', function () {
        sidebar.classList.toggle('open');
        overlay.classList.toggle('show');
    });

    overlay.addEventListener('click', closeSidebar);
</script>

</body>
</html>