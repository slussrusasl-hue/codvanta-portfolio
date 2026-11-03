
<?php
session_start();
date_default_timezone_set('Asia/Colombo');

/*
|--------------------------------------------------------------------------
| ProServe CRM - Admin Invoices
|--------------------------------------------------------------------------
| Session-based demonstration page.
| No database connection is required.
|--------------------------------------------------------------------------
*/

function e($value): string
{
    return htmlspecialchars((string)($value ?? ''), ENT_QUOTES, 'UTF-8');
}

function invoiceMoney($amount): string
{
    return 'LKR ' . number_format((float)$amount, 2);
}

function invoiceStatusClass($status): string
{
    $status = strtoupper(trim((string)$status));

    $classes = [
        'DRAFT'     => 'secondary',
        'UNPAID'    => 'warning',
        'SENT'      => 'info',
        'PENDING'   => 'warning',
        'PARTIAL'   => 'primary',
        'PAID'      => 'success',
        'OVERDUE'   => 'danger',
        'CANCELLED' => 'dark',
    ];

    return $classes[$status] ?? 'secondary';
}

function invoiceReference(array $invoice): string
{
    return (string)(
        $invoice['invoice_reference']
        ?? $invoice['invoice_number']
        ?? 'INV-UNKNOWN'
    );
}

function invoiceCustomer(array $invoice): string
{
    return (string)(
        $invoice['customer_name']
        ?? $invoice['name']
        ?? 'Unknown Customer'
    );
}

function invoiceService(array $invoice): string
{
    return (string)(
        $invoice['service']
        ?? $invoice['service_name']
        ?? 'Custom Service'
    );
}

function invoiceAmount(array $invoice): float
{
    return (float)(
        $invoice['amount']
        ?? $invoice['total_amount']
        ?? 0
    );
}

function invoiceIsOverdue(array $invoice): bool
{
    $status = strtoupper((string)($invoice['status'] ?? ''));

    if (in_array($status, ['PAID', 'CANCELLED', 'DRAFT'], true)) {
        return false;
    }

    $dueDate = $invoice['due_date'] ?? '';

    return $dueDate !== ''
        && strtotime($dueDate) !== false
        && strtotime($dueDate) < strtotime(date('Y-m-d'));
}

/*
|--------------------------------------------------------------------------
| Initialize demo invoices
|--------------------------------------------------------------------------
*/

if (!isset($_SESSION['proserve_invoices'])) {
    $_SESSION['proserve_invoices'] = [
        [
            'invoice_reference' => 'INV-2026-001',
            'quotation_reference' => 'QUO-2026-001',
            'customer_name' => 'Amelia Fernando',
            'email' => 'amelia@example.com',
            'company' => 'Amelia Retail Solutions',
            'service' => 'E-commerce Development',
            'amount' => 275000,
            'status' => 'SENT',
            'issue_date' => date('Y-m-d', strtotime('-12 days')),
            'due_date' => date('Y-m-d', strtotime('+18 days')),
            'payment_method' => 'Bank Transfer',
            'notes' => 'E-commerce platform development invoice.',
            'created_at' => date('Y-m-d H:i:s', strtotime('-12 days')),
        ],
        [
            'invoice_reference' => 'INV-2026-002',
            'quotation_reference' => 'QUO-2026-002',
            'customer_name' => 'Daniel Perera',
            'email' => 'daniel@example.com',
            'company' => 'Perera Business Group',
            'service' => 'Business Management Systems',
            'amount' => 450000,
            'status' => 'PAID',
            'issue_date' => date('Y-m-d', strtotime('-30 days')),
            'due_date' => date('Y-m-d', strtotime('-5 days')),
            'payment_method' => 'Bank Transfer',
            'notes' => 'Business management system development.',
            'created_at' => date('Y-m-d H:i:s', strtotime('-30 days')),
        ],
        [
            'invoice_reference' => 'INV-2026-003',
            'quotation_reference' => 'QUO-2026-003',
            'customer_name' => 'Sarah Wijesinghe',
            'email' => 'sarah@example.com',
            'company' => 'SW Digital',
            'service' => 'Custom Web Application Development',
            'amount' => 180000,
            'status' => 'UNPAID',
            'issue_date' => date('Y-m-d', strtotime('-20 days')),
            'due_date' => date('Y-m-d', strtotime('-3 days')),
            'payment_method' => 'Not specified',
            'notes' => 'Custom web application development.',
            'created_at' => date('Y-m-d H:i:s', strtotime('-20 days')),
        ],
        [
            'invoice_reference' => 'INV-2026-004',
            'quotation_reference' => 'QUO-2026-004',
            'customer_name' => 'Michael Silva',
            'email' => 'michael@example.com',
            'company' => 'Silva Technologies',
            'service' => 'Database Development',
            'amount' => 85000,
            'status' => 'PARTIAL',
            'issue_date' => date('Y-m-d', strtotime('-8 days')),
            'due_date' => date('Y-m-d', strtotime('+22 days')),
            'payment_method' => 'Card Payment',
            'notes' => 'Database design and implementation.',
            'created_at' => date('Y-m-d H:i:s', strtotime('-8 days')),
            'paid_amount' => 25000,
        ],
        [
            'invoice_reference' => 'INV-2026-005',
            'quotation_reference' => '',
            'customer_name' => 'Nethmi Jayawardena',
            'email' => 'nethmi@example.com',
            'company' => 'NJ Consulting',
            'service' => 'Website Maintenance',
            'amount' => 25000,
            'status' => 'DRAFT',
            'issue_date' => date('Y-m-d'),
            'due_date' => date('Y-m-d', strtotime('+30 days')),
            'payment_method' => 'Not specified',
            'notes' => 'Monthly website maintenance service.',
            'created_at' => date('Y-m-d H:i:s'),
        ],
    ];
}

if (!isset($_SESSION['proserve_invoice_drafts'])) {
    $_SESSION['proserve_invoice_drafts'] = [];
}

if (!isset($_SESSION['proserve_invoices_csrf'])) {
    $_SESSION['proserve_invoices_csrf'] = bin2hex(random_bytes(32));
}

$notice = $_SESSION['proserve_invoices_notice'] ?? null;
unset($_SESSION['proserve_invoices_notice']);

$invoices = &$_SESSION['proserve_invoices'];
$drafts = &$_SESSION['proserve_invoice_drafts'];

$allowedStatuses = [
    'DRAFT',
    'UNPAID',
    'SENT',
    'PARTIAL',
    'PAID',
    'OVERDUE',
    'CANCELLED',
];

/*
|--------------------------------------------------------------------------
| Process form submissions
|--------------------------------------------------------------------------
*/

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $csrf = (string)($_POST['csrf_token'] ?? '');

    if (!hash_equals($_SESSION['proserve_invoices_csrf'], $csrf)) {
        $_SESSION['proserve_invoices_notice'] = [
            'type' => 'danger',
            'message' => 'Your session validation failed. Please refresh and try again.',
        ];
    } else {
        $action = (string)($_POST['action'] ?? '');

        /*
        |------------------------------------------------------------------
        | Create an invoice from a quotation draft
        |------------------------------------------------------------------
        */

        if ($action === 'create_from_draft') {
            $draftIndex = filter_var(
                $_POST['draft_index'] ?? null,
                FILTER_VALIDATE_INT
            );

            if ($draftIndex === false || !isset($drafts[$draftIndex])) {
                $_SESSION['proserve_invoices_notice'] = [
                    'type' => 'danger',
                    'message' => 'The selected invoice draft could not be found.',
                ];
            } else {
                $draft = $drafts[$draftIndex];
                $quotationReference = (string)(
                    $draft['quotation_reference'] ?? ''
                );

                $alreadyExists = false;

                foreach ($invoices as $existingInvoice) {
                    if (
                        $quotationReference !== ''
                        && ($existingInvoice['quotation_reference'] ?? '') === $quotationReference
                    ) {
                        $alreadyExists = true;
                        break;
                    }
                }

                if ($alreadyExists) {
                    $_SESSION['proserve_invoices_notice'] = [
                        'type' => 'warning',
                        'message' => 'An invoice already exists for this quotation.',
                    ];
                } else {
                    $newReference = 'INV-' . date('Y') . '-' .
                        str_pad((string)(count($invoices) + 1), 3, '0', STR_PAD_LEFT);

                    $invoices[] = [
                        'invoice_reference' => $newReference,
                        'quotation_reference' => $quotationReference,
                        'customer_name' => $draft['customer_name'] ?? 'Unknown Customer',
                        'email' => $draft['email'] ?? '',
                        'company' => $draft['company'] ?? '',
                        'service' => $draft['service'] ?? 'Custom Service',
                        'amount' => (float)($draft['amount'] ?? 0),
                        'status' => 'DRAFT',
                        'issue_date' => date('Y-m-d'),
                        'due_date' => date('Y-m-d', strtotime('+30 days')),
                        'payment_method' => 'Not specified',
                        'notes' => 'Created from approved quotation ' . $quotationReference,
                        'created_at' => date('Y-m-d H:i:s'),
                    ];

                    unset($drafts[$draftIndex]);
                    $drafts = array_values($drafts);

                    $_SESSION['proserve_invoices_notice'] = [
                        'type' => 'success',
                        'message' => 'Invoice ' . $newReference . ' was created successfully.',
                    ];
                }
            }
        }

        /*
        |------------------------------------------------------------------
        | Update an invoice
        |------------------------------------------------------------------
        */

        elseif ($action === 'update') {
            $index = filter_var(
                $_POST['invoice_index'] ?? null,
                FILTER_VALIDATE_INT
            );

            if ($index === false || !isset($invoices[$index])) {
                $_SESSION['proserve_invoices_notice'] = [
                    'type' => 'danger',
                    'message' => 'Invoice not found.',
                ];
            } else {
                $status = strtoupper(trim((string)($_POST['status'] ?? 'DRAFT')));
                $amount = filter_var(
                    $_POST['amount'] ?? null,
                    FILTER_VALIDATE_FLOAT
                );
                $dueDate = trim((string)($_POST['due_date'] ?? ''));
                $paidAmount = filter_var(
                    $_POST['paid_amount'] ?? 0,
                    FILTER_VALIDATE_FLOAT
                );
                $paymentMethod = trim((string)($_POST['payment_method'] ?? 'Not specified'));
                $notes = trim((string)($_POST['notes'] ?? ''));

                $validDate = $dueDate === ''
                    || (
                        preg_match('/^\d{4}-\d{2}-\d{2}$/', $dueDate)
                        && strtotime($dueDate) !== false
                    );

                if (
                    !in_array($status, $allowedStatuses, true)
                    || $amount === false
                    || $amount < 0
                    || $paidAmount === false
                    || $paidAmount < 0
                    || !$validDate
                    || $paidAmount > $amount
                ) {
                    $_SESSION['proserve_invoices_notice'] = [
                        'type' => 'danger',
                        'message' => 'Please enter valid invoice details. Paid amount cannot exceed the invoice amount.',
                    ];
                } else {
                    $invoices[$index]['amount'] = (float)$amount;
                    $invoices[$index]['status'] = $status;
                    $invoices[$index]['due_date'] = $dueDate;
                    $invoices[$index]['paid_amount'] = (float)$paidAmount;
                    $invoices[$index]['payment_method'] = $paymentMethod !== ''
                        ? $paymentMethod
                        : 'Not specified';
                    $invoices[$index]['notes'] = $notes;
                    $invoices[$index]['updated_at'] = date('Y-m-d H:i:s');

                    if ($status === 'PAID') {
                        $invoices[$index]['paid_amount'] = (float)$amount;
                    }

                    $_SESSION['proserve_invoices_notice'] = [
                        'type' => 'success',
                        'message' => 'Invoice ' . invoiceReference($invoices[$index]) . ' was updated successfully.',
                    ];
                }
            }
        }

        /*
        |------------------------------------------------------------------
        | Delete an invoice
        |------------------------------------------------------------------
        */

        elseif ($action === 'delete') {
            $index = filter_var(
                $_POST['invoice_index'] ?? null,
                FILTER_VALIDATE_INT
            );

            if ($index === false || !isset($invoices[$index])) {
                $_SESSION['proserve_invoices_notice'] = [
                    'type' => 'danger',
                    'message' => 'Invoice not found.',
                ];
            } else {
                $reference = invoiceReference($invoices[$index]);
                array_splice($invoices, $index, 1);

                $_SESSION['proserve_invoices_notice'] = [
                    'type' => 'success',
                    'message' => 'Invoice ' . $reference . ' was deleted from the demo.',
                ];
            }
        }
    }

    header('Location: invoices.php');
    exit;
}

/*
|--------------------------------------------------------------------------
| Search and filtering
|--------------------------------------------------------------------------
*/

$search = trim((string)($_GET['search'] ?? ''));
$statusFilter = strtoupper(trim((string)($_GET['status'] ?? 'ALL')));

$filteredInvoices = [];

foreach ($invoices as $index => $invoice) {
    $reference = invoiceReference($invoice);
    $customer = invoiceCustomer($invoice);
    $service = invoiceService($invoice);

    $searchable = strtolower(
        $reference . ' ' .
        $customer . ' ' .
        $service . ' ' .
        ($invoice['email'] ?? '') . ' ' .
        ($invoice['company'] ?? '') . ' ' .
        ($invoice['quotation_reference'] ?? '')
    );

    if ($search !== '' && strpos($searchable, strtolower($search)) === false) {
        continue;
    }

    $currentStatus = strtoupper((string)($invoice['status'] ?? 'DRAFT'));

    if ($statusFilter !== 'ALL' && $currentStatus !== $statusFilter) {
        if (!($statusFilter === 'OVERDUE' && invoiceIsOverdue($invoice))) {
            continue;
        }
    }

    $invoice['_index'] = $index;
    $filteredInvoices[] = $invoice;
}

usort($filteredInvoices, function ($a, $b) {
    return strcmp(
        (string)($b['created_at'] ?? ''),
        (string)($a['created_at'] ?? '')
    );
});

/*
|--------------------------------------------------------------------------
| Statistics
|--------------------------------------------------------------------------
*/

$totalInvoiced = 0;
$totalPaid = 0;
$totalOutstanding = 0;
$totalOverdue = 0;
$paidCount = 0;
$unpaidCount = 0;
$overdueCount = 0;

foreach ($invoices as $invoice) {
    $status = strtoupper((string)($invoice['status'] ?? 'DRAFT'));
    $amount = invoiceAmount($invoice);
    $paid = (float)($invoice['paid_amount'] ?? 0);

    if ($status !== 'CANCELLED') {
        $totalInvoiced += $amount;
    }

    if ($status === 'PAID') {
        $totalPaid += $amount;
        $paidCount++;
    } else {
        $totalPaid += $paid;
    }

    if (!in_array($status, ['PAID', 'CANCELLED', 'DRAFT'], true)) {
        $outstanding = max(0, $amount - $paid);
        $totalOutstanding += $outstanding;

        if (invoiceIsOverdue($invoice)) {
            $totalOverdue += $outstanding;
            $overdueCount++;
        }

        $unpaidCount++;
    }
}

$draftCount = count($drafts);
$invoiceCount = count($invoices);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoices | ProServe Admin</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary: #2563eb;
            --primary-dark: #1d4ed8;
            --navy: #10233f;
            --background: #f5f7fb;
            --border: #e7ebf2;
            --muted: #718096;
            --sidebar-width: 255px;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: var(--background);
            color: #1e293b;
            font-family: 'Inter', 'Trebuchet MS', sans-serif;
            font-size: 14px;
        }

        a {
            text-decoration: none;
        }

        .sidebar {
            width: var(--sidebar-width);
            position: fixed;
            inset: 0 auto 0 0;
            background: var(--navy);
            color: #fff;
            display: flex;
            flex-direction: column;
            z-index: 1040;
            overflow-y: auto;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 25px 22px;
            border-bottom: 1px solid rgba(255,255,255,.1);
        }

        .brand-icon {
            display: grid;
            place-items: center;
            width: 43px;
            height: 43px;
            border-radius: 13px;
            background: var(--primary);
            font-size: 22px;
        }

        .brand-name {
            font-size: 20px;
            font-weight: 800;
            letter-spacing: -.7px;
        }

        .brand small {
            display: block;
            color: #a9bbd4;
            font-size: 10px;
            margin-top: 3px;
        }

        .nav-heading {
            padding: 24px 23px 9px;
            color: #8fa4c1;
            font-size: 10px;
            font-weight: 800;
            letter-spacing: 1.4px;
            text-transform: uppercase;
        }

        .side-nav {
            padding: 0 12px;
        }

        .side-nav a {
            display: flex;
            align-items: center;
            gap: 13px;
            padding: 12px 14px;
            margin: 3px 0;
            color: #c5d2e4;
            border-radius: 9px;
            font-size: 13px;
            font-weight: 500;
            transition: .2s;
        }

        .side-nav a i {
            font-size: 17px;
        }

        .side-nav a:hover,
        .side-nav a.active {
            color: #fff;
            background: rgba(37,99,235,.9);
        }

        .sidebar-bottom {
            margin-top: auto;
            padding: 20px;
            border-top: 1px solid rgba(255,255,255,.1);
        }

        .admin-profile {
            display: flex;
            align-items: center;
            gap: 11px;
        }

        .avatar {
            width: 39px;
            height: 39px;
            border-radius: 50%;
            display: grid;
            place-items: center;
            background: #dbeafe;
            color: var(--primary);
            font-weight: 800;
        }

        .admin-profile strong {
            display: block;
            font-size: 12px;
        }

        .admin-profile small {
            color: #9fb1ca;
            font-size: 11px;
        }

        .main {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
        }

        .topbar {
            height: 75px;
            background: #fff;
            border-bottom: 1px solid var(--border);
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 34px;
        }

        .breadcrumb-text {
            color: var(--muted);
            font-size: 12px;
        }

        .breadcrumb-text strong {
            color: #1e293b;
        }

        .topbar-title {
            font-size: 17px;
            font-weight: 800;
        }

        .admin-chip {
            display: flex;
            align-items: center;
            gap: 9px;
            font-weight: 600;
        }

        .admin-chip .avatar {
            width: 35px;
            height: 35px;
        }

        .content {
            padding: 30px 34px;
            max-width: 1700px;
            margin: auto;
        }

        .page-heading {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 20px;
            margin-bottom: 26px;
        }

        .page-heading h1 {
            font-size: 27px;
            font-weight: 800;
            letter-spacing: -1px;
            margin: 0 0 8px;
        }

        .page-heading p {
            color: var(--muted);
            margin: 0;
        }

        .btn-primary {
            background: var(--primary);
            border-color: var(--primary);
        }

        .btn-primary:hover {
            background: var(--primary-dark);
            border-color: var(--primary-dark);
        }

        .stat-card {
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 21px;
            height: 100%;
        }

        .stat-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            margin-bottom: 17px;
        }

        .stat-label {
            color: var(--muted);
            font-size: 12px;
            font-weight: 600;
        }

        .stat-icon {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            display: grid;
            place-items: center;
            font-size: 19px;
        }

        .icon-blue { background: #eaf1ff; color: #2563eb; }
        .icon-green { background: #e5f8ed; color: #16834b; }
        .icon-orange { background: #fff3df; color: #c07812; }
        .icon-red { background: #ffe9e9; color: #d43c3c; }

        .stat-value {
            font-size: clamp(19px, 2vw, 25px);
            font-weight: 800;
            letter-spacing: -.8px;
            overflow-wrap: anywhere;
        }

        .stat-sub {
            color: var(--muted);
            font-size: 11px;
            margin-top: 8px;
        }

        .panel {
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 14px;
            overflow: hidden;
        }

        .panel-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 15px;
            padding: 22px 23px;
            border-bottom: 1px solid var(--border);
        }

        .panel-header h5 {
            margin: 0;
            font-size: 15px;
            font-weight: 800;
        }

        .panel-header p {
            margin: 6px 0 0;
            font-size: 12px;
            color: var(--muted);
        }

        .filters {
            padding: 19px 23px;
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
            border-bottom: 1px solid var(--border);
        }

        .search-box {
            position: relative;
            flex: 1 1 230px;
        }

        .search-box i {
            position: absolute;
            left: 13px;
            top: 11px;
            color: #94a3b8;
        }

        .search-box input {
            padding-left: 38px;
        }

        .form-control,
        .form-select {
            min-height: 41px;
            border-color: #dfe5ef;
            border-radius: 8px;
            font-size: 12px;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #93b4ff;
            box-shadow: 0 0 0 3px rgba(37,99,235,.1);
        }

        .table-responsive {
            width: 100%;
        }

        .table {
            margin: 0;
            min-width: 1080px;
        }

        .table thead th {
            background: #f8fafc;
            color: #64748b;
            font-size: 10px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .65px;
            padding: 15px 17px;
            border-bottom: 1px solid var(--border);
            white-space: nowrap;
        }

        .table tbody td {
            padding: 16px 17px;
            vertical-align: middle;
            border-color: #eef1f6;
            font-size: 12px;
        }

        .table tbody tr:hover {
            background: #fbfcff;
        }

        .invoice-ref {
            color: var(--primary);
            font-weight: 800;
            font-size: 12px;
        }

        .customer-name {
            font-weight: 700;
            color: #26354a;
            font-size: 12px;
        }

        .secondary-text {
            color: var(--muted);
            font-size: 11px;
            margin-top: 4px;
        }

        .service-name {
            max-width: 200px;
            font-weight: 600;
            line-height: 1.5;
        }

        .amount {
            font-weight: 800;
            white-space: nowrap;
        }

        .badge {
            padding: 7px 9px;
            border-radius: 6px;
            font-size: 9px;
            letter-spacing: .3px;
            font-weight: 800;
        }

        .action-btn {
            width: 31px;
            height: 31px;
            display: inline-grid;
            place-items: center;
            background: #fff;
            border: 1px solid var(--border);
            color: #64748b;
            border-radius: 7px;
            padding: 0;
        }

        .action-btn:hover {
            color: var(--primary);
            border-color: #a9c4ff;
            background: #f3f7ff;
        }

        .action-btn.delete:hover {
            color: #dc2626;
            border-color: #fecaca;
            background: #fff5f5;
        }

        .table-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 15px;
            flex-wrap: wrap;
            padding: 16px 23px;
            color: var(--muted);
            font-size: 11px;
        }

        .draft-card {
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 16px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 15px;
        }

        .draft-card + .draft-card {
            margin-top: 12px;
        }

        .draft-title {
            font-weight: 800;
            font-size: 12px;
        }

        .empty-state {
            padding: 45px 20px;
            text-align: center;
            color: var(--muted);
        }

        .empty-state i {
            display: block;
            font-size: 35px;
            margin-bottom: 12px;
            color: #b8c5d8;
        }

        .empty-state strong {
            display: block;
            color: #334155;
            margin-bottom: 5px;
        }

        .modal-content {
            border: 0;
            border-radius: 14px;
            overflow: hidden;
        }

        .modal-header {
            padding: 21px 24px;
            border-bottom: 1px solid var(--border);
        }

        .modal-title {
            font-size: 17px;
            font-weight: 800;
        }

        .modal-body {
            padding: 24px;
        }

        .modal-footer {
            padding: 16px 24px;
            border-top: 1px solid var(--border);
        }

        .detail-label {
            color: var(--muted);
            font-size: 11px;
            margin-bottom: 5px;
        }

        .detail-value {
            font-weight: 700;
            overflow-wrap: anywhere;
        }

        .detail-box {
            background: #f8fafc;
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 14px;
        }

        .footer {
            color: #8490a2;
            font-size: 11px;
            padding: 27px 0 10px;
            text-align: center;
        }

        .mobile-overlay {
            display: none;
        }

        @media (max-width: 1100px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform .25s ease;
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .mobile-overlay.show {
                display: block;
                position: fixed;
                inset: 0;
                z-index: 1030;
                background: rgba(15,23,42,.45);
            }

            .main {
                margin-left: 0;
            }

            .topbar {
                padding: 0 22px;
            }

            .content {
                padding: 25px 22px;
            }
        }

        @media (max-width: 650px) {
            .content {
                padding: 20px 14px;
            }

            .topbar {
                height: 65px;
                padding: 0 14px;
            }

            .page-heading {
                flex-direction: column;
            }

            .page-heading h1 {
                font-size: 24px;
            }

            .panel-header,
            .filters {
                padding: 16px;
            }

            .draft-card {
                align-items: flex-start;
                flex-direction: column;
            }

            .stat-card {
                padding: 16px;
            }
        }

        @media print {
            .sidebar,
            .topbar,
            .filters,
            .action-column,
            .footer,
            .no-print,
            .mobile-overlay {
                display: none !important;
            }

            .main {
                margin-left: 0;
            }

            body {
                background: #fff;
            }

            .content {
                padding: 0;
            }

            .panel,
            .stat-card {
                border: 1px solid #ddd;
                break-inside: avoid;
            }
        }
    </style>
</head>

<body>

<div class="mobile-overlay" id="mobileOverlay"></div>

<aside class="sidebar" id="sidebar">
    <div class="brand">
        <div class="brand-icon">
            <i class="bi bi-grid-1x2-fill"></i>
        </div>
        <div>
            <div class="brand-name">ProServe</div>
            <small>CRM &amp; Service Management</small>
        </div>
    </div>

    <div class="nav-heading">Workspace</div>

    <nav class="side-nav">
        <a href="index.php">
            <i class="bi bi-speedometer2"></i> Dashboard
        </a>
        <a href="customers.php">
            <i class="bi bi-people"></i> Customers
        </a>
        <a href="leads.php">
            <i class="bi bi-funnel"></i> Leads
        </a>
        <a href="appointments.php">
            <i class="bi bi-calendar-check"></i> Appointments
        </a>
        <a href="services.php">
            <i class="bi bi-box-seam"></i> Services
        </a>
        <a href="quotations.php">
            <i class="bi bi-file-earmark-text"></i> Quotations
        </a>
        <a href="invoices.php" class="active">
            <i class="bi bi-receipt"></i> Invoices
        </a>
        <a href="employees.php">
            <i class="bi bi-person-badge"></i> Employees
        </a>
        <a href="enquiries.php">
            <i class="bi bi-chat-left-text"></i> Enquiries
        </a>
        <a href="reports.php">
            <i class="bi bi-bar-chart-line"></i> Reports
        </a>
    </nav>

    <div class="nav-heading">System</div>

    <nav class="side-nav">
        <a href="settings.php">
            <i class="bi bi-gear"></i> Settings
        </a>
        <a href="../index.php">
            <i class="bi bi-box-arrow-up-right"></i> View Website
        </a>
    </nav>

    <div class="sidebar-bottom">
        <div class="admin-profile">
            <div class="avatar">AD</div>
            <div>
                <strong>Administrator</strong>
                <small>ProServe Demo Account</small>
            </div>
        </div>
    </div>
</aside>

<main class="main">

    <header class="topbar">
        <div class="d-flex align-items-center gap-3">
            <button class="btn btn-light d-lg-none" id="menuToggle" type="button" aria-label="Open navigation">
                <i class="bi bi-list fs-5"></i>
            </button>

            <div>
                <div class="breadcrumb-text">Workspace / Finance / <strong>Invoices</strong></div>
                <div class="topbar-title">Invoice Management</div>
            </div>
        </div>

        <div class="admin-chip">
            <div class="avatar">AD</div>
            <div class="d-none d-sm-block">
                <div style="font-size:12px;">Administrator</div>
                <div class="secondary-text">Demo Account</div>
            </div>
        </div>
    </header>

    <div class="content">

        <div class="page-heading">
            <div>
                <h1>Invoices</h1>
                <p>Manage customer invoices, payment statuses and outstanding balances.</p>
            </div>

            <div class="d-flex flex-wrap gap-2 no-print">
                <button type="button" class="btn btn-outline-secondary" onclick="window.print()">
                    <i class="bi bi-printer me-1"></i> Print Report
                </button>

                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createInvoiceModal">
                    <i class="bi bi-plus-lg me-1"></i> Create Invoice
                </button>
            </div>
        </div>

        <?php if ($notice): ?>
            <div class="alert alert-<?= e($notice['type'] ?? 'info') ?> alert-dismissible fade show mb-4" role="alert">
                <?= e($notice['message'] ?? '') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <!-- Statistics -->
        <div class="row g-3 mb-4">
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="stat-card">
                    <div class="stat-top">
                        <div class="stat-label">Total Invoiced</div>
                        <div class="stat-icon icon-blue"><i class="bi bi-receipt-cutoff"></i></div>
                    </div>
                    <div class="stat-value"><?= e(invoiceMoney($totalInvoiced)) ?></div>
                    <div class="stat-sub"><?= $invoiceCount ?> invoices recorded</div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-xl-3">
                <div class="stat-card">
                    <div class="stat-top">
                        <div class="stat-label">Total Collected</div>
                        <div class="stat-icon icon-green"><i class="bi bi-wallet2"></i></div>
                    </div>
                    <div class="stat-value"><?= e(invoiceMoney($totalPaid)) ?></div>
                    <div class="stat-sub"><?= $paidCount ?> fully paid invoices</div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-xl-3">
                <div class="stat-card">
                    <div class="stat-top">
                        <div class="stat-label">Outstanding Balance</div>
                        <div class="stat-icon icon-orange"><i class="bi bi-hourglass-split"></i></div>
                    </div>
                    <div class="stat-value"><?= e(invoiceMoney($totalOutstanding)) ?></div>
                    <div class="stat-sub"><?= $unpaidCount ?> unpaid or partially paid invoices</div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-xl-3">
                <div class="stat-card">
                    <div class="stat-top">
                        <div class="stat-label">Overdue Balance</div>
                        <div class="stat-icon icon-red"><i class="bi bi-exclamation-triangle"></i></div>
                    </div>
                    <div class="stat-value"><?= e(invoiceMoney($totalOverdue)) ?></div>
                    <div class="stat-sub"><?= $overdueCount ?> invoices past their due date</div>
                </div>
            </div>
        </div>

        <!-- Invoices Table -->
        <section class="panel mb-4">
            <div class="panel-header">
                <div>
                    <h5><i class="bi bi-file-earmark-ruled me-2 text-primary"></i>All Invoices</h5>
                    <p>Review, update and manage your invoices.</p>
                </div>
                <span class="badge bg-light text-dark"><?= count($filteredInvoices) ?> results</span>
            </div>

            <form class="filters" method="GET" action="invoices.php">
                <div class="search-box">
                    <i class="bi bi-search"></i>
                    <input
                        type="text"
                        name="search"
                        class="form-control"
                        placeholder="Search invoice, customer, service..."
                        value="<?= e($search) ?>"
                    >
                </div>

                <select name="status" class="form-select" style="max-width:190px;">
                    <option value="ALL" <?= $statusFilter === 'ALL' ? 'selected' : '' ?>>All statuses</option>
                    <?php foreach ($allowedStatuses as $status): ?>
                        <option value="<?= e($status) ?>" <?= $statusFilter === $status ? 'selected' : '' ?>>
                            <?= e(ucwords(strtolower($status))) ?>
                        </option>
                    <?php endforeach; ?>
                    <?php if ($statusFilter === 'OVERDUE'): ?>
                        <option value="OVERDUE" selected>Overdue</option>
                    <?php else: ?>
                        <option value="OVERDUE">Overdue</option>
                    <?php endif; ?>
                </select>

                <button class="btn btn-primary px-3" type="submit">
                    <i class="bi bi-funnel me-1"></i> Filter
                </button>

                <a href="invoices.php" class="btn btn-outline-secondary">Reset</a>
            </form>

            <div class="table-responsive">
                <table class="table">
                    <thead>
                    <tr>
                        <th>Invoice</th>
                        <th>Customer</th>
                        <th>Service</th>
                        <th>Issue Date</th>
                        <th>Due Date</th>
                        <th>Amount</th>
                        <th>Paid</th>
                        <th>Status</th>
                        <th class="action-column">Actions</th>
                    </tr>
                    </thead>

                    <tbody>
                    <?php if (empty($filteredInvoices)): ?>
                        <tr>
                            <td colspan="9">
                                <div class="empty-state">
                                    <i class="bi bi-receipt"></i>
                                    <strong>No invoices found</strong>
                                    Try changing your search term or status filter.
                                </div>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($filteredInvoices as $invoice): ?>
                            <?php
                            $index = (int)$invoice['_index'];
                            $status = strtoupper((string)($invoice['status'] ?? 'DRAFT'));
                            $isOverdue = invoiceIsOverdue($invoice);
                            $displayStatus = $isOverdue ? 'OVERDUE' : $status;
                            $amount = invoiceAmount($invoice);
                            $paidAmount = $status === 'PAID'
                                ? $amount
                                : (float)($invoice['paid_amount'] ?? 0);
                            ?>
                            <tr>
                                <td>
                                    <div class="invoice-ref"><?= e(invoiceReference($invoice)) ?></div>
                                    <div class="secondary-text">
                                        <?= e($invoice['quotation_reference'] ?? 'Manual invoice') ?>
                                    </div>
                                </td>

                                <td>
                                    <div class="customer-name"><?= e(invoiceCustomer($invoice)) ?></div>
                                    <div class="secondary-text"><?= e($invoice['company'] ?? '') ?></div>
                                </td>

                                <td>
                                    <div class="service-name"><?= e(invoiceService($invoice)) ?></div>
                                </td>

                                <td><?= e($invoice['issue_date'] ?? '-') ?></td>

                                <td>
                                    <span class="<?= $isOverdue ? 'text-danger fw-bold' : '' ?>">
                                        <?= e($invoice['due_date'] ?? '-') ?>
                                    </span>
                                    <?php if ($isOverdue): ?>
                                        <div class="secondary-text text-danger">Past due</div>
                                    <?php endif; ?>
                                </td>

                                <td class="amount"><?= e(invoiceMoney($amount)) ?></td>

                                <td>
                                    <div class="fw-bold"><?= e(invoiceMoney($paidAmount)) ?></div>
                                    <div class="secondary-text">
                                        <?= $amount > 0 ? e(number_format(($paidAmount / $amount) * 100, 0)) . '% paid' : '—' ?>
                                    </div>
                                </td>

                                <td>
                                    <span class="badge bg-<?= e(invoiceStatusClass($displayStatus)) ?>">
                                        <?= e($displayStatus) ?>
                                    </span>
                                </td>

                                <td class="action-column">
                                    <div class="d-flex gap-1">
                                        <button
                                            type="button"
                                            class="action-btn"
                                            title="View invoice"
                                            data-bs-toggle="modal"
                                            data-bs-target="#viewInvoiceModal"
                                            data-invoice="<?= e(json_encode($invoice, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP)) ?>"
                                        >
                                            <i class="bi bi-eye"></i>
                                        </button>

                                        <button
                                            type="button"
                                            class="action-btn"
                                            title="Edit invoice"
                                            data-bs-toggle="modal"
                                            data-bs-target="#editInvoiceModal"
                                            data-index="<?= $index ?>"
                                            data-reference="<?= e(invoiceReference($invoice)) ?>"
                                            data-amount="<?= e($amount) ?>"
                                            data-paid="<?= e($paidAmount) ?>"
                                            data-status="<?= e($status) ?>"
                                            data-due="<?= e($invoice['due_date'] ?? '') ?>"
                                            data-method="<?= e($invoice['payment_method'] ?? 'Not specified') ?>"
                                            data-notes="<?= e($invoice['notes'] ?? '') ?>"
                                        >
                                            <i class="bi bi-pencil"></i>
                                        </button>

                                        <form method="POST" action="invoices.php" onsubmit="return confirm('Delete this invoice from the demo?');">
                                            <input type="hidden" name="csrf_token" value="<?= e($_SESSION['proserve_invoices_csrf']) ?>">
                                            <input type="hidden" name="action" value="delete">
                                            <input type="hidden" name="invoice_index" value="<?= $index ?>">
                                            <button type="submit" class="action-btn delete" title="Delete invoice">
                                                <i class="bi bi-trash3"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <div class="table-footer">
                <span>Showing <?= count($filteredInvoices) ?> of <?= $invoiceCount ?> invoices</span>
                <span><i class="bi bi-info-circle me-1"></i> Demo data is stored in the current PHP session.</span>
            </div>
        </section>

        <!-- Invoice Drafts -->
        <section class="panel">
            <div class="panel-header">
                <div>
                    <h5><i class="bi bi-file-earmark-plus me-2 text-primary"></i>Quotation Invoice Drafts</h5>
                    <p>Create invoices from quotations approved in the quotation management page.</p>
                </div>
                <span class="badge bg-primary"><?= $draftCount ?> drafts</span>
            </div>

            <div class="p-3 p-md-4">
                <?php if (empty($drafts)): ?>
                    <div class="empty-state">
                        <i class="bi bi-file-earmark-check"></i>
                        <strong>No invoice drafts available</strong>
                        Approve a quotation in Quotations and select “Create Invoice” to generate a draft.
                        <div class="mt-3">
                            <a href="quotations.php" class="btn btn-outline-primary btn-sm">
                                <i class="bi bi-arrow-left me-1"></i> Go to Quotations
                            </a>
                        </div>
                    </div>
                <?php else: ?>
                    <?php foreach ($drafts as $draftIndex => $draft): ?>
                        <div class="draft-card">
                            <div>
                                <div class="draft-title">
                                    <?= e($draft['quotation_reference'] ?? 'Approved Quotation') ?>
                                    <span class="badge bg-secondary ms-1">READY TO CREATE</span>
                                </div>
                                <div class="secondary-text">
                                    <?= e($draft['customer_name'] ?? 'Unknown Customer') ?>
                                    · <?= e($draft['service'] ?? 'Custom Service') ?>
                                </div>
                                <div class="fw-bold mt-2">
                                    <?= e(invoiceMoney($draft['amount'] ?? 0)) ?>
                                </div>
                            </div>

                            <form method="POST" action="invoices.php">
                                <input type="hidden" name="csrf_token" value="<?= e($_SESSION['proserve_invoices_csrf']) ?>">
                                <input type="hidden" name="action" value="create_from_draft">
                                <input type="hidden" name="draft_index" value="<?= (int)$draftIndex ?>">
                                <button class="btn btn-primary btn-sm" type="submit">
                                    <i class="bi bi-plus-lg me-1"></i> Create Invoice
                                </button>
                            </form>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </section>

        <div class="footer">
            ProServe — CRM &amp; Service Management System<br>
            Portfolio demonstration by Codvanta.
        </div>
    </div>
</main>

<!-- Create Invoice Modal -->
<div class="modal fade" id="createInvoiceModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <h5 class="modal-title">Create Invoice</h5>
                    <div class="secondary-text">Choose an approved quotation draft below to create a linked invoice.</div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <?php if (empty($drafts)): ?>
                    <div class="empty-state py-3">
                        <i class="bi bi-file-earmark-plus"></i>
                        <strong>No quotation drafts available</strong>
                        Create an invoice draft from an approved quotation first.
                    </div>
                <?php else: ?>
                    <p class="text-muted small">Select a quotation draft to generate an invoice.</p>
                    <?php foreach ($drafts as $draftIndex => $draft): ?>
                        <form method="POST" action="invoices.php" class="border rounded-3 p-3 mb-2">
                            <input type="hidden" name="csrf_token" value="<?= e($_SESSION['proserve_invoices_csrf']) ?>">
                            <input type="hidden" name="action" value="create_from_draft">
                            <input type="hidden" name="draft_index" value="<?= (int)$draftIndex ?>">

                            <div class="d-flex justify-content-between align-items-center gap-3">
                                <div>
                                    <div class="fw-bold"><?= e($draft['quotation_reference'] ?? 'Quotation') ?></div>
                                    <div class="secondary-text"><?= e($draft['customer_name'] ?? 'Unknown Customer') ?></div>
                                    <div class="fw-bold mt-2"><?= e(invoiceMoney($draft['amount'] ?? 0)) ?></div>
                                </div>
                                <button type="submit" class="btn btn-primary btn-sm">Create</button>
                            </div>
                        </form>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- View Invoice Modal -->
<div class="modal fade" id="viewInvoiceModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <h5 class="modal-title">Invoice Details</h5>
                    <div class="secondary-text">Invoice summary and customer information</div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div class="d-flex justify-content-between align-items-start gap-3 mb-4">
                    <div>
                        <div class="detail-label">Invoice Number</div>
                        <div class="detail-value text-primary fs-5" id="viewReference">—</div>
                        <div class="secondary-text mt-2" id="viewQuotation"></div>
                    </div>
                    <div class="text-end">
                        <div class="detail-label">Invoice Status</div>
                        <span class="badge bg-secondary" id="viewStatus">—</span>
                    </div>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <div class="detail-box h-100">
                            <div class="detail-label">Bill To</div>
                            <div class="detail-value" id="viewCustomer">—</div>
                            <div class="secondary-text" id="viewCompany"></div>
                            <div class="secondary-text" id="viewEmail"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="detail-box h-100">
                            <div class="detail-label">Service</div>
                            <div class="detail-value" id="viewService">—</div>
                            <div class="secondary-text mt-3">Payment method</div>
                            <div class="detail-value" id="viewMethod">—</div>
                        </div>
                    </div>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-sm-4">
                        <div class="detail-label">Issue Date</div>
                        <div class="detail-value" id="viewIssueDate">—</div>
                    </div>
                    <div class="col-sm-4">
                        <div class="detail-label">Due Date</div>
                        <div class="detail-value" id="viewDueDate">—</div>
                    </div>
                    <div class="col-sm-4">
                        <div class="detail-label">Created At</div>
                        <div class="detail-value" id="viewCreated">—</div>
                    </div>
                </div>

                <div class="detail-box mb-3">
                    <div class="d-flex justify-content-between align-items-center gap-3 mb-2">
                        <span class="detail-label mb-0">Invoice Amount</span>
                        <strong class="fs-5" id="viewAmount">LKR 0.00</strong>
                    </div>
                    <div class="d-flex justify-content-between align-items-center gap-3 mb-2">
                        <span class="detail-label mb-0">Paid Amount</span>
                        <strong class="text-success" id="viewPaid">LKR 0.00</strong>
                    </div>
                    <div class="d-flex justify-content-between align-items-center gap-3">
                        <span class="detail-label mb-0">Outstanding</span>
                        <strong class="text-danger" id="viewOutstanding">LKR 0.00</strong>
                    </div>
                </div>

                <div class="detail-label">Notes</div>
                <div class="detail-value" id="viewNotes">No additional notes.</div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="window.print()">
                    <i class="bi bi-printer me-1"></i> Print
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Invoice Modal -->
<div class="modal fade" id="editInvoiceModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <form method="POST" action="invoices.php">
                <div class="modal-header">
                    <div>
                        <h5 class="modal-title">Edit Invoice</h5>
                        <div class="secondary-text" id="editReference">Update invoice details and payment status.</div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <input type="hidden" name="csrf_token" value="<?= e($_SESSION['proserve_invoices_csrf']) ?>">
                    <input type="hidden" name="action" value="update">
                    <input type="hidden" name="invoice_index" id="editIndex">

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Invoice Amount (LKR)</label>
                            <input type="number" name="amount" id="editAmount" class="form-control" min="0" step="0.01" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Paid Amount (LKR)</label>
                            <input type="number" name="paid_amount" id="editPaid" class="form-control" min="0" step="0.01" required>
                            <div class="form-text">Must not exceed the invoice amount.</div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Status</label>
                            <select name="status" id="editStatus" class="form-select" required>
                                <?php foreach ($allowedStatuses as $status): ?>
                                    <option value="<?= e($status) ?>"><?= e(ucwords(strtolower($status))) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Due Date</label>
                            <input type="date" name="due_date" id="editDueDate" class="form-control">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Payment Method</label>
                            <select name="payment_method" id="editMethod" class="form-select">
                                <option>Not specified</option>
                                <option>Bank Transfer</option>
                                <option>Cash</option>
                                <option>Card Payment</option>
                                <option>Online Payment</option>
                                <option>Cheque</option>
                            </select>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold">Internal Notes</label>
                            <textarea name="notes" id="editNotes" class="form-control" rows="3" maxlength="2000" placeholder="Add payment or invoice notes..."></textarea>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check2-circle me-1"></i> Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('mobileOverlay');
    const menuToggle = document.getElementById('menuToggle');

    function closeSidebar() {
        sidebar.classList.remove('show');
        overlay.classList.remove('show');
    }

    if (menuToggle) {
        menuToggle.addEventListener('click', () => {
            sidebar.classList.toggle('show');
            overlay.classList.toggle('show');
        });
    }

    overlay.addEventListener('click', closeSidebar);

    const currencyFormatter = new Intl.NumberFormat('en-LK', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });

    function formatCurrency(amount) {
        return 'LKR ' + currencyFormatter.format(Number(amount) || 0);
    }

    function setText(id, value) {
        document.getElementById(id).textContent =
            value === undefined || value === null || value === ''
                ? '—'
                : String(value);
    }

    /*
    |--------------------------------------------------------------------------
    | View invoice
    |--------------------------------------------------------------------------
    */

    const viewModal = document.getElementById('viewInvoiceModal');

    viewModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;

        try {
            const invoice = JSON.parse(button.getAttribute('data-invoice'));

            const amount = Number(invoice.amount ?? invoice.total_amount ?? 0);
            const status = String(invoice.status ?? 'DRAFT').toUpperCase();
            const paid = status === 'PAID'
                ? amount
                : Number(invoice.paid_amount ?? 0);

            setText('viewReference', invoice.invoice_reference ?? invoice.invoice_number);
            setText('viewQuotation', invoice.quotation_reference
                ? 'Quotation: ' + invoice.quotation_reference
                : 'Manual invoice');

            setText('viewCustomer', invoice.customer_name ?? invoice.name);
            setText('viewCompany', invoice.company);
            setText('viewEmail', invoice.email);
            setText('viewService', invoice.service ?? invoice.service_name);
            setText('viewMethod', invoice.payment_method);
            setText('viewIssueDate', invoice.issue_date);
            setText('viewDueDate', invoice.due_date);
            setText('viewCreated', invoice.created_at);
            setText('viewNotes', invoice.notes || 'No additional notes.');

            setText('viewAmount', formatCurrency(amount));
            setText('viewPaid', formatCurrency(paid));
            setText('viewOutstanding', formatCurrency(Math.max(0, amount - paid)));

            const statusElement = document.getElementById('viewStatus');
            statusElement.textContent = status;
            statusElement.className = 'badge bg-' + ({
                DRAFT: 'secondary',
                UNPAID: 'warning',
                SENT: 'info',
                PARTIAL: 'primary',
                PAID: 'success',
                OVERDUE: 'danger',
                CANCELLED: 'dark'
            }[status] || 'secondary');

        } catch (error) {
            setText('viewReference', 'Unable to load invoice details');
        }
    });

    /*
    |--------------------------------------------------------------------------
    | Edit invoice
    |--------------------------------------------------------------------------
    */

    const editModal = document.getElementById('editInvoiceModal');

    editModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;

        document.getElementById('editIndex').value = button.dataset.index ?? '';
        document.getElementById('editReference').textContent =
            'Editing ' + (button.dataset.reference ?? 'invoice');

        document.getElementById('editAmount').value = button.dataset.amount ?? '0';
        document.getElementById('editPaid').value = button.dataset.paid ?? '0';
        document.getElementById('editStatus').value = button.dataset.status ?? 'DRAFT';
        document.getElementById('editDueDate').value = button.dataset.due ?? '';
        document.getElementById('editMethod').value = button.dataset.method ?? 'Not specified';
        document.getElementById('editNotes').value = button.dataset.notes ?? '';
    });

    /*
    |--------------------------------------------------------------------------
    | Validate payment amounts before submitting
    |--------------------------------------------------------------------------
    */

    const editForm = editModal.querySelector('form');

    editForm.addEventListener('submit', function (event) {
        const amount = Number(document.getElementById('editAmount').value);
        const paid = Number(document.getElementById('editPaid').value);
        const status = document.getElementById('editStatus').value;

        if (amount < 0 || paid < 0 || paid > amount) {
            event.preventDefault();
            alert('Please enter valid amounts. Paid amount cannot exceed invoice amount.');
            return;
        }

        if (status === 'PAID' && paid < amount) {
            const confirmPaid = confirm(
                'The status is Paid. The system will record the full invoice amount as paid. Continue?'
            );

            if (!confirmPaid) {
                event.preventDefault();
            }
        }
    });
</script>

</body>
</html>