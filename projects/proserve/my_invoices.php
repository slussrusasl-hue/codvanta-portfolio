
<?php
session_start();
date_default_timezone_set('Asia/Colombo');

/*
|--------------------------------------------------------------------------
| ProServe - My Invoices
|--------------------------------------------------------------------------
| Session-based customer invoice demonstration.
| No database is required.
*/

function e($value): string
{
    return htmlspecialchars((string)($value ?? ''), ENT_QUOTES, 'UTF-8');
}

if (!isset($_SESSION['proserve_customer'])) {
    $_SESSION['proserve_invoice_notice'] =
        'Please log in to view your invoices.';

    header('Location: login.php');
    exit;
}

$customer = $_SESSION['proserve_customer'];
$profile = $_SESSION['proserve_profile'] ?? [];

$customerName = $profile['name']
    ?? $customer['name']
    ?? 'Customer';

$customerEmail = $profile['email']
    ?? $customer['email']
    ?? '';

$customerCompany = $profile['company']
    ?? $customer['company']
    ?? '';

$customerPhone = $profile['phone']
    ?? $customer['phone']
    ?? '';

/*
|--------------------------------------------------------------------------
| Demo invoice data
|--------------------------------------------------------------------------
| These sample invoices are added only when the session does not
| already contain invoice records.
|
| Other pages can append invoice records to:
| $_SESSION['proserve_invoices']
|--------------------------------------------------------------------------
*/

if (!isset($_SESSION['proserve_invoices'])) {
    $_SESSION['proserve_invoices'] = [
        [
            'invoice_number' => 'PS-INV-2026-001',
            'reference' => 'PS-INV-2026-001',
            'quote_reference' => 'PS-QT-2026-014',
            'service' => 'Website Development',
            'description' => 'Business website design and development.',
            'issue_date' => date('Y-m-d', strtotime('-20 days')),
            'due_date' => date('Y-m-d', strtotime('-5 days')),
            'amount' => 850.00,
            'tax' => 0.00,
            'discount' => 50.00,
            'currency' => 'USD',
            'status' => 'PAID',
            'payment_method' => 'Bank Transfer',
            'paid_date' => date('Y-m-d', strtotime('-8 days')),
            'email' => $customerEmail,
            'company' => $customerCompany,
            'phone' => $customerPhone,
            'created_at' => date('Y-m-d H:i:s', strtotime('-20 days')),
        ],
        [
            'invoice_number' => 'PS-INV-2026-002',
            'reference' => 'PS-INV-2026-002',
            'quote_reference' => 'PS-QT-2026-021',
            'service' => 'Business Management System',
            'description' => 'Initial development milestone for a business management system.',
            'issue_date' => date('Y-m-d', strtotime('-7 days')),
            'due_date' => date('Y-m-d', strtotime('+7 days')),
            'amount' => 1200.00,
            'tax' => 0.00,
            'discount' => 0.00,
            'currency' => 'USD',
            'status' => 'PENDING',
            'payment_method' => '',
            'paid_date' => '',
            'email' => $customerEmail,
            'company' => $customerCompany,
            'phone' => $customerPhone,
            'created_at' => date('Y-m-d H:i:s', strtotime('-7 days')),
        ],
        [
            'invoice_number' => 'PS-INV-2026-003',
            'reference' => 'PS-INV-2026-003',
            'quote_reference' => 'PS-QT-2026-025',
            'service' => 'Website Maintenance',
            'description' => 'Monthly website maintenance and technical support.',
            'issue_date' => date('Y-m-d', strtotime('-2 days')),
            'due_date' => date('Y-m-d', strtotime('+12 days')),
            'amount' => 150.00,
            'tax' => 0.00,
            'discount' => 0.00,
            'currency' => 'USD',
            'status' => 'UNPAID',
            'payment_method' => '',
            'paid_date' => '',
            'email' => $customerEmail,
            'company' => $customerCompany,
            'phone' => $customerPhone,
            'created_at' => date('Y-m-d H:i:s', strtotime('-2 days')),
        ],
    ];
}

/*
|--------------------------------------------------------------------------
| Filter invoices belonging to the current customer
|--------------------------------------------------------------------------
*/

$allInvoices = $_SESSION['proserve_invoices'];

if (!is_array($allInvoices)) {
    $allInvoices = [];
}

$invoices = [];

foreach ($allInvoices as $key => $invoice) {
    if (!is_array($invoice)) {
        continue;
    }

    $invoiceEmail = strtolower(trim((string)(
        $invoice['email'] ?? $invoice['customer_email'] ?? ''
    )));

    /*
     * Do not show another customer's invoice.
     * Records without an email are not displayed.
     */
    if (
        $customerEmail !== '' &&
        $invoiceEmail !== '' &&
        $invoiceEmail === strtolower(trim($customerEmail))
    ) {
        $invoice['_session_key'] = $key;
        $invoices[] = $invoice;
    }
}

/*
|--------------------------------------------------------------------------
| Search and status filters
|--------------------------------------------------------------------------
*/

$search = trim((string)($_GET['search'] ?? ''));
$statusFilter = strtoupper(trim((string)($_GET['status'] ?? 'ALL')));

$allowedStatuses = [
    'ALL',
    'PAID',
    'PENDING',
    'UNPAID',
    'OVERDUE',
    'CANCELLED',
];

if (!in_array($statusFilter, $allowedStatuses, true)) {
    $statusFilter = 'ALL';
}

$filteredInvoices = [];

foreach ($invoices as $invoice) {
    $invoiceNumber = (string)(
        $invoice['invoice_number']
        ?? $invoice['invoice_no']
        ?? $invoice['reference']
        ?? 'N/A'
    );

    $service = (string)(
        $invoice['service']
        ?? $invoice['service_name']
        ?? 'Service'
    );

    $invoiceStatus = strtoupper(trim((string)(
        $invoice['status'] ?? 'PENDING'
    )));

    $dueDate = (string)($invoice['due_date'] ?? '');

    // Derive overdue status for unpaid invoices past their due date.
    if (
        in_array($invoiceStatus, ['UNPAID', 'PENDING', 'SENT'], true) &&
        $dueDate !== '' &&
        strtotime($dueDate) !== false &&
        strtotime($dueDate) < strtotime(date('Y-m-d'))
    ) {
        $invoiceStatus = 'OVERDUE';
    }

    $searchText = strtolower(
        $invoiceNumber . ' ' .
        $service . ' ' .
        ($invoice['quote_reference'] ?? '') . ' ' .
        ($invoice['description'] ?? '')
    );

    if ($search !== '' && strpos($searchText, strtolower($search)) === false) {
        continue;
    }

    if ($statusFilter !== 'ALL' && $invoiceStatus !== $statusFilter) {
        continue;
    }

    $invoice['_display_status'] = $invoiceStatus;
    $filteredInvoices[] = $invoice;
}

/*
|--------------------------------------------------------------------------
| Summary calculations
|--------------------------------------------------------------------------
*/

$totalInvoices = count($invoices);
$paidCount = 0;
$unpaidCount = 0;
$overdueCount = 0;
$totalPaid = 0.0;
$totalOutstanding = 0.0;

foreach ($invoices as $invoice) {
    $status = strtoupper(trim((string)($invoice['status'] ?? 'PENDING')));
    $dueDate = (string)($invoice['due_date'] ?? '');

    if (
        in_array($status, ['UNPAID', 'PENDING', 'SENT'], true) &&
        $dueDate !== '' &&
        strtotime($dueDate) !== false &&
        strtotime($dueDate) < strtotime(date('Y-m-d'))
    ) {
        $status = 'OVERDUE';
    }

    $amount = (float)($invoice['amount'] ?? $invoice['total'] ?? 0);
    $tax = (float)($invoice['tax'] ?? 0);
    $discount = (float)($invoice['discount'] ?? 0);
    $total = max(0, $amount + $tax - $discount);

    if (in_array($status, ['PAID', 'COMPLETED'], true)) {
        $paidCount++;
        $totalPaid += $total;
    } elseif (in_array($status, ['OVERDUE'], true)) {
        $overdueCount++;
        $totalOutstanding += $total;
    } elseif (!in_array($status, ['CANCELLED', 'VOID'], true)) {
        $unpaidCount++;
        $totalOutstanding += $total;
    }
}

function invoiceStatusClass(string $status): string
{
    return match (strtoupper($status)) {
        'PAID', 'COMPLETED' => 'status-paid',
        'OVERDUE' => 'status-overdue',
        'CANCELLED', 'VOID' => 'status-cancelled',
        default => 'status-pending',
    };
}

function invoiceMoney(array $invoice): float
{
    $amount = (float)($invoice['amount'] ?? $invoice['total'] ?? 0);
    $tax = (float)($invoice['tax'] ?? 0);
    $discount = (float)($invoice['discount'] ?? 0);

    return max(0, $amount + $tax - $discount);
}

function invoiceCurrency(array $invoice): string
{
    return (string)($invoice['currency'] ?? 'USD');
}

function invoiceDate(string $date): string
{
    if ($date === '' || strtotime($date) === false) {
        return '—';
    }

    return date('d M Y', strtotime($date));
}

$flashMessage = $_SESSION['proserve_invoice_notice'] ?? '';
unset($_SESSION['proserve_invoice_notice']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>My Invoices | ProServe</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css"
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
            --muted: #64748b;
            --border: #e7edf5;
            --background: #f5f8fc;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: var(--background);
            color: #243247;
            font-family: 'Inter', 'Trebuchet MS', sans-serif;
            font-size: 14px;
        }

        .navbar {
            background: #fff;
            border-bottom: 1px solid var(--border);
        }

        .navbar-brand {
            color: var(--navy);
            font-size: 22px;
            font-weight: 800;
            letter-spacing: -0.7px;
        }

        .navbar-brand span {
            color: var(--primary);
        }

        .navbar .nav-link {
            color: #536176;
            font-weight: 600;
            padding: 10px 13px;
        }

        .navbar .nav-link:hover,
        .navbar .nav-link.active {
            color: var(--primary);
        }

        .page-header {
            padding: 34px 0 22px;
        }

        .page-title {
            color: var(--navy);
            font-size: clamp(25px, 3vw, 32px);
            font-weight: 800;
            letter-spacing: -1px;
        }

        .muted {
            color: var(--muted);
        }

        .btn-primary {
            background: var(--primary);
            border-color: var(--primary);
            font-weight: 600;
        }

        .btn-primary:hover {
            background: var(--primary-dark);
            border-color: var(--primary-dark);
        }

        .summary-card {
            height: 100%;
            padding: 21px;
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(15, 35, 65, .025);
        }

        .summary-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 43px;
            height: 43px;
            border-radius: 12px;
            font-size: 20px;
        }

        .icon-blue {
            background: #eaf1ff;
            color: #2563eb;
        }

        .icon-green {
            background: #e6f8ef;
            color: #159957;
        }

        .icon-orange {
            background: #fff2df;
            color: #c77713;
        }

        .icon-red {
            background: #ffe9e9;
            color: #dc3545;
        }

        .summary-label {
            margin-top: 17px;
            color: var(--muted);
            font-size: 12px;
            font-weight: 600;
        }

        .summary-value {
            margin-top: 5px;
            color: var(--navy);
            font-size: 25px;
            font-weight: 800;
            letter-spacing: -.6px;
        }

        .content-card {
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(15, 35, 65, .025);
        }

        .content-card-header {
            padding: 21px 23px;
            border-bottom: 1px solid var(--border);
        }

        .content-card-body {
            padding: 22px 23px;
        }

        .section-title {
            margin: 0;
            color: var(--navy);
            font-size: 16px;
            font-weight: 800;
        }

        .form-control,
        .form-select {
            min-height: 43px;
            border-color: #dfe7f1;
            border-radius: 9px;
            font-size: 13px;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #93b4ff;
            box-shadow: 0 0 0 .2rem rgba(37, 99, 235, .10);
        }

        .table {
            margin-bottom: 0;
            vertical-align: middle;
        }

        .table thead th {
            padding: 14px 16px;
            background: #f8faff;
            color: #718096;
            font-size: 11px;
            font-weight: 800;
            letter-spacing: .45px;
            text-transform: uppercase;
            white-space: nowrap;
            border-bottom: 1px solid var(--border);
        }

        .table tbody td {
            padding: 17px 16px;
            border-bottom: 1px solid #edf1f7;
        }

        .table tbody tr:last-child td {
            border-bottom: 0;
        }

        .invoice-number {
            color: var(--primary);
            font-weight: 800;
        }

        .invoice-service {
            color: var(--navy);
            font-weight: 700;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 10px;
            border-radius: 30px;
            font-size: 10px;
            font-weight: 800;
            white-space: nowrap;
        }

        .status-paid {
            background: #e5f8ed;
            color: #15803d;
        }

        .status-pending {
            background: #fff3d9;
            color: #a16207;
        }

        .status-overdue {
            background: #ffe5e5;
            color: #c62828;
        }

        .status-cancelled {
            background: #eef1f5;
            color: #64748b;
        }

        .action-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 34px;
            height: 34px;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            background: #fff;
            color: #526178;
            text-decoration: none;
            cursor: pointer;
        }

        .action-btn:hover {
            background: #edf4ff;
            color: var(--primary);
            border-color: #c9dcff;
        }

        .empty-state {
            padding: 55px 20px;
            text-align: center;
        }

        .empty-state-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 70px;
            height: 70px;
            margin: 0 auto 18px;
            border-radius: 20px;
            background: #edf3ff;
            color: var(--primary);
            font-size: 30px;
        }

        .help-banner {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 19px 22px;
            background: #edf4ff;
            border: 1px solid #dce8ff;
            border-radius: 13px;
        }

        .help-banner i {
            color: var(--primary);
            font-size: 25px;
        }

        footer {
            margin-top: 45px;
            padding: 25px 0;
            background: #fff;
            border-top: 1px solid var(--border);
            color: var(--muted);
            font-size: 12px;
        }

        .invoice-paper {
            padding: 30px;
            background: #fff;
        }

        .invoice-paper h2 {
            color: var(--navy);
            font-weight: 800;
        }

        .invoice-total {
            padding: 15px;
            background: #f3f7ff;
            border-radius: 10px;
        }

        @media (max-width: 767px) {
            .page-header {
                padding-top: 25px;
            }

            .summary-card {
                padding: 16px;
            }

            .summary-value {
                font-size: 21px;
            }

            .content-card-header,
            .content-card-body {
                padding: 17px;
            }

            .invoice-paper {
                padding: 18px;
            }
        }

        @media print {
            body {
                background: #fff !important;
            }

            body * {
                visibility: hidden;
            }

            #invoicePrintArea,
            #invoicePrintArea * {
                visibility: visible;
            }

            #invoicePrintArea {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                border: 0 !important;
                box-shadow: none !important;
            }

            .modal-header,
            .modal-footer,
            .no-print {
                display: none !important;
            }
        }
    </style>
</head>

<body>

<nav class="navbar navbar-expand-lg sticky-top">
    <div class="container">
        <a class="navbar-brand" href="index.php">
            Pro<span>Serve</span>
        </a>

        <button
            class="navbar-toggler"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#mainNavbar"
            aria-controls="mainNavbar"
            aria-expanded="false"
            aria-label="Toggle navigation"
        >
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="mainNavbar">
            <ul class="navbar-nav ms-auto align-items-lg-center">
                <li class="nav-item">
                    <a class="nav-link" href="index.php">Home</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="services.php">Services</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="dashboard.php">Dashboard</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="my_appointments.php">
                        Appointments
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="my_quotations.php">
                        Quotations
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link active" href="my_invoices.php">
                        Invoices
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="profile.php">My Profile</a>
                </li>

                <li class="nav-item ms-lg-2">
                    <a class="btn btn-outline-primary btn-sm px-3" href="logout.php">
                        <i class="bi bi-box-arrow-right me-1"></i> Logout
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<main class="container">

    <section class="page-header">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <div class="small muted mb-2">
                    <i class="bi bi-house-door me-1"></i>
                    Customer Portal / My Invoices
                </div>

                <h1 class="page-title mb-2">My Invoices</h1>

                <p class="muted mb-0">
                    View your service invoices, monitor outstanding payments,
                    and print invoice details.
                </p>
            </div>

            <a href="my_quotations.php" class="btn btn-primary px-4 py-2">
                <i class="bi bi-file-earmark-text me-2"></i>
                View Quotations
            </a>
        </div>
    </section>

    <?php if ($flashMessage !== ''): ?>
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            <i class="bi bi-info-circle me-2"></i>
            <?= e($flashMessage) ?>

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
                aria-label="Close"
            ></button>
        </div>
    <?php endif; ?>

    <section class="row g-3 mb-4">

        <div class="col-6 col-xl-3">
            <div class="summary-card">
                <div class="summary-icon icon-blue">
                    <i class="bi bi-receipt"></i>
                </div>

                <div class="summary-label">Total Invoices</div>
                <div class="summary-value"><?= $totalInvoices ?></div>

                <div class="small muted mt-2">
                    All your invoices
                </div>
            </div>
        </div>

        <div class="col-6 col-xl-3">
            <div class="summary-card">
                <div class="summary-icon icon-green">
                    <i class="bi bi-check-circle"></i>
                </div>

                <div class="summary-label">Paid Invoices</div>
                <div class="summary-value"><?= $paidCount ?></div>

                <div class="small muted mt-2">
                    <?= e(number_format($totalPaid, 2)) ?>
                    in recorded payments
                </div>
            </div>
        </div>

        <div class="col-6 col-xl-3">
            <div class="summary-card">
                <div class="summary-icon icon-orange">
                    <i class="bi bi-hourglass-split"></i>
                </div>

                <div class="summary-label">Unpaid Invoices</div>
                <div class="summary-value"><?= $unpaidCount ?></div>

                <div class="small muted mt-2">
                    Awaiting payment
                </div>
            </div>
        </div>

        <div class="col-6 col-xl-3">
            <div class="summary-card">
                <div class="summary-icon icon-red">
                    <i class="bi bi-exclamation-circle"></i>
                </div>

                <div class="summary-label">Overdue Invoices</div>
                <div class="summary-value"><?= $overdueCount ?></div>

                <div class="small muted mt-2">
                    Past their due date
                </div>
            </div>
        </div>

    </section>

    <section class="content-card mb-4">
        <div class="content-card-header">
            <div class="d-flex flex-column flex-md-row justify-content-between gap-2">
                <div>
                    <h2 class="section-title">Invoice Records</h2>
                    <p class="small muted mb-0 mt-2">
                        Search and filter invoices associated with your account.
                    </p>
                </div>

                <div class="small muted align-self-md-center">
                    Outstanding balance:
                    <strong class="text-dark">
                        <?= e(number_format($totalOutstanding, 2)) ?>
                    </strong>
                </div>
            </div>
        </div>

        <div class="content-card-body">
            <form method="GET" action="my_invoices.php" class="row g-3">

                <div class="col-md-6">
                    <label for="search" class="form-label fw-semibold">
                        Search invoices
                    </label>

                    <div class="input-group">
                        <span class="input-group-text bg-white">
                            <i class="bi bi-search muted"></i>
                        </span>

                        <input
                            type="text"
                            id="search"
                            name="search"
                            class="form-control"
                            placeholder="Invoice number, service or quotation..."
                            value="<?= e($search) ?>"
                        >
                    </div>
                </div>

                <div class="col-md-3">
                    <label for="status" class="form-label fw-semibold">
                        Invoice status
                    </label>

                    <select id="status" name="status" class="form-select">
                        <?php
                        $statusOptions = [
                            'ALL' => 'All statuses',
                            'PAID' => 'Paid',
                            'PENDING' => 'Pending',
                            'UNPAID' => 'Unpaid',
                            'OVERDUE' => 'Overdue',
                            'CANCELLED' => 'Cancelled',
                        ];
                        ?>

                        <?php foreach ($statusOptions as $value => $label): ?>
                            <option
                                value="<?= e($value) ?>"
                                <?= $statusFilter === $value ? 'selected' : '' ?>
                            >
                                <?= e($label) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-3 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-primary flex-grow-1">
                        <i class="bi bi-funnel me-1"></i> Apply
                    </button>

                    <a href="my_invoices.php" class="btn btn-outline-secondary">
                        Reset
                    </a>
                </div>

            </form>
        </div>

        <?php if (count($filteredInvoices) > 0): ?>

            <div class="table-responsive">
                <table class="table">
                    <thead>
                    <tr>
                        <th>Invoice</th>
                        <th>Service</th>
                        <th>Issue Date</th>
                        <th>Due Date</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th class="text-center">Actions</th>
                    </tr>
                    </thead>

                    <tbody>
                    <?php foreach ($filteredInvoices as $invoice): ?>
                        <?php
                        $invoiceNumber = $invoice['invoice_number']
                            ?? $invoice['invoice_no']
                            ?? $invoice['reference']
                            ?? 'N/A';

                        $service = $invoice['service']
                            ?? $invoice['service_name']
                            ?? 'Service';

                        $currency = invoiceCurrency($invoice);
                        $total = invoiceMoney($invoice);
                        $status = $invoice['_display_status'] ?? 'PENDING';
                        ?>

                        <tr>
                            <td>
                                <div class="invoice-number">
                                    <?= e($invoiceNumber) ?>
                                </div>

                                <div class="small muted mt-1">
                                    <?= e($invoice['quote_reference'] ?? 'Service invoice') ?>
                                </div>
                            </td>

                            <td>
                                <div class="invoice-service">
                                    <?= e($service) ?>
                                </div>

                                <div class="small muted mt-1">
                                    <?= e($invoice['company'] ?? $customerCompany ?: 'Individual customer') ?>
                                </div>
                            </td>

                            <td>
                                <?= e(invoiceDate((string)($invoice['issue_date'] ?? $invoice['created_at'] ?? ''))) ?>
                            </td>

                            <td>
                                <?= e(invoiceDate((string)($invoice['due_date'] ?? ''))) ?>
                            </td>

                            <td class="fw-bold text-dark text-nowrap">
                                <?= e($currency) ?>
                                <?= e(number_format($total, 2)) ?>
                            </td>

                            <td>
                                <span class="status-badge <?= e(invoiceStatusClass($status)) ?>">
                                    <i class="bi bi-circle-fill" style="font-size:6px"></i>
                                    <?= e($status) ?>
                                </span>
                            </td>

                            <td>
                                <div class="d-flex justify-content-center gap-2">
                                    <button
                                        type="button"
                                        class="action-btn"
                                        title="View invoice"
                                        aria-label="View invoice"
                                        data-bs-toggle="modal"
                                        data-bs-target="#invoiceModal"
                                        data-invoice="<?= e(json_encode(
                                            $invoice,
                                            JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP
                                        )) ?>"
                                    >
                                        <i class="bi bi-eye"></i>
                                    </button>

                                    <button
                                        type="button"
                                        class="action-btn"
                                        title="Print invoice"
                                        aria-label="Print invoice"
                                        data-print-invoice="<?= e(json_encode(
                                            $invoice,
                                            JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP
                                        )) ?>"
                                    >
                                        <i class="bi bi-printer"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="px-4 py-3 border-top d-flex justify-content-between align-items-center flex-wrap gap-2">
                <span class="small muted">
                    Showing <?= count($filteredInvoices) ?> of <?= $totalInvoices ?> invoices
                </span>

                <span class="small muted">
                    <i class="bi bi-shield-check me-1"></i>
                    Customer invoice records
                </span>
            </div>

        <?php else: ?>

            <div class="empty-state">
                <div class="empty-state-icon">
                    <i class="bi bi-receipt-cutoff"></i>
                </div>

                <h3 class="h5 fw-bold text-dark">No invoices found</h3>

                <p class="muted mb-3">
                    There are no invoices matching your search and filter criteria.
                </p>

                <a href="my_invoices.php" class="btn btn-outline-primary">
                    Clear Filters
                </a>
            </div>

        <?php endif; ?>
    </section>

    <section class="help-banner">
        <i class="bi bi-headset"></i>

        <div class="flex-grow-1">
            <h3 class="h6 fw-bold text-dark mb-1">
                Need help with an invoice?
            </h3>

            <p class="small muted mb-0">
                Contact our team if you have questions about an invoice,
                payment status, or billing details.
            </p>
        </div>

        <a href="contact.php" class="btn btn-primary btn-sm px-3">
            Contact Us
        </a>
    </section>

</main>

<footer>
    <div class="container d-flex flex-column flex-md-row justify-content-between gap-2">
        <span>
            &copy; <?= date('Y') ?> ProServe — CRM &amp; Service Management System
        </span>

        <span>
            Portfolio demonstration by
            <strong class="text-primary">Codvanta</strong>
        </span>
    </div>
</footer>

<!-- Invoice details modal -->
<div
    class="modal fade"
    id="invoiceModal"
    tabindex="-1"
    aria-labelledby="invoiceModalLabel"
    aria-hidden="true"
>
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 rounded-4 overflow-hidden">

            <div class="modal-header px-4 py-3">
                <div>
                    <h2 class="modal-title fs-5 fw-bold" id="invoiceModalLabel">
                        Invoice Details
                    </h2>

                    <div class="small muted">
                        Review the invoice information below.
                    </div>
                </div>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Close"
                ></button>
            </div>

            <div class="modal-body p-0">
                <div id="invoicePrintArea" class="invoice-paper">
                    <div class="d-flex justify-content-between align-items-start gap-3 mb-4">
                        <div>
                            <h2 class="h3 mb-1">
                                Pro<span class="text-primary">Serve</span>
                            </h2>

                            <div class="small muted">
                                CRM &amp; Service Management System
                            </div>
                        </div>

                        <div class="text-end">
                            <div class="small muted">INVOICE NUMBER</div>
                            <div id="modalInvoiceNumber" class="fw-bold fs-6">
                                —
                            </div>

                            <div class="mt-2">
                                <span id="modalStatus" class="status-badge status-pending">
                                    PENDING
                                </span>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="row g-4 mb-4">
                        <div class="col-sm-6">
                            <div class="small muted mb-2">BILL TO</div>

                            <div id="modalCustomerName" class="fw-bold">
                                <?= e($customerName) ?>
                            </div>

                            <div id="modalCustomerCompany" class="small muted mt-1">
                                <?= e($customerCompany) ?>
                            </div>

                            <div id="modalCustomerEmail" class="small muted mt-1">
                                <?= e($customerEmail) ?>
                            </div>

                            <div id="modalCustomerPhone" class="small muted mt-1">
                                <?= e($customerPhone) ?>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="small muted mb-2">INVOICE INFORMATION</div>

                            <div class="d-flex justify-content-between gap-2 mb-2">
                                <span class="muted">Issue date</span>
                                <strong id="modalIssueDate">—</strong>
                            </div>

                            <div class="d-flex justify-content-between gap-2 mb-2">
                                <span class="muted">Due date</span>
                                <strong id="modalDueDate">—</strong>
                            </div>

                            <div class="d-flex justify-content-between gap-2">
                                <span class="muted">Quotation reference</span>
                                <strong id="modalQuoteReference">—</strong>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive mb-4">
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th>Description</th>
                                <th class="text-end">Amount</th>
                            </tr>
                            </thead>

                            <tbody>
                            <tr>
                                <td>
                                    <div id="modalService" class="fw-bold">
                                        Service
                                    </div>

                                    <div id="modalDescription" class="small muted mt-1">
                                        —
                                    </div>
                                </td>

                                <td id="modalBaseAmount" class="text-end text-nowrap">
                                    USD 0.00
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="row justify-content-end">
                        <div class="col-sm-7 col-md-6">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="muted">Subtotal</span>
                                <strong id="modalSubtotal">USD 0.00</strong>
                            </div>

                            <div class="d-flex justify-content-between mb-2">
                                <span class="muted">Tax</span>
                                <strong id="modalTax">USD 0.00</strong>
                            </div>

                            <div class="d-flex justify-content-between mb-3">
                                <span class="muted">Discount</span>
                                <strong id="modalDiscount">USD 0.00</strong>
                            </div>

                            <div class="invoice-total d-flex justify-content-between align-items-center gap-2">
                                <strong class="text-dark">Total</strong>

                                <strong id="modalTotal" class="fs-5 text-primary">
                                    USD 0.00
                                </strong>
                            </div>

                            <div class="small muted text-end mt-2">
                                Payment method:
                                <span id="modalPaymentMethod">Not recorded</span>
                            </div>

                            <div class="small muted text-end mt-1">
                                Paid date:
                                <span id="modalPaidDate">—</span>
                            </div>
                        </div>
                    </div>

                    <hr class="mt-4">

                    <div class="small muted text-center">
                        Thank you for choosing ProServe.
                        Please contact our team if you have questions about this invoice.
                    </div>
                </div>
            </div>

            <div class="modal-footer no-print px-4">
                <button
                    type="button"
                    class="btn btn-outline-secondary"
                    data-bs-dismiss="modal"
                >
                    Close
                </button>

                <button type="button" class="btn btn-primary" id="printModalInvoice">
                    <i class="bi bi-printer me-2"></i> Print Invoice
                </button>
            </div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    const customerDetails = {
        name: <?= json_encode($customerName, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>,
        email: <?= json_encode($customerEmail, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>,
        company: <?= json_encode($customerCompany, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>,
        phone: <?= json_encode($customerPhone, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>
    };

    const invoiceModal = document.getElementById('invoiceModal');
    let selectedInvoice = null;

    function safeText(value, fallback = '—') {
        if (value === null || value === undefined || value === '') {
            return fallback;
        }

        return String(value);
    }

    function formatMoney(amount, currency) {
        const number = Number(amount || 0);

        return safeText(currency, 'USD') + ' ' +
            number.toLocaleString('en-US', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
    }

    function formatDate(value) {
        if (!value) {
            return '—';
        }

        const date = new Date(String(value).replace(' ', 'T'));

        if (Number.isNaN(date.getTime())) {
            return '—';
        }

        return date.toLocaleDateString('en-GB', {
            day: '2-digit',
            month: 'short',
            year: 'numeric'
        });
    }

    function getInvoiceStatus(invoice) {
        let status = String(invoice._display_status || invoice.status || 'PENDING')
            .toUpperCase();

        const dueDate = invoice.due_date || '';

        if (
            ['UNPAID', 'PENDING', 'SENT'].includes(status) &&
            dueDate &&
            new Date(dueDate + 'T00:00:00') < new Date(new Date().toDateString())
        ) {
            status = 'OVERDUE';
        }

        return status;
    }

    function statusClass(status) {
        if (['PAID', 'COMPLETED'].includes(status)) {
            return 'status-paid';
        }

        if (status === 'OVERDUE') {
            return 'status-overdue';
        }

        if (['CANCELLED', 'VOID'].includes(status)) {
            return 'status-cancelled';
        }

        return 'status-pending';
    }

    function setText(id, value, fallback = '—') {
        document.getElementById(id).textContent = safeText(value, fallback);
    }

    function populateInvoice(invoice) {
        selectedInvoice = invoice;

        const number = invoice.invoice_number ||
            invoice.invoice_no ||
            invoice.reference ||
            'N/A';

        const service = invoice.service ||
            invoice.service_name ||
            'Service';

        const amount = Number(invoice.amount ?? invoice.total ?? 0);
        const tax = Number(invoice.tax || 0);
        const discount = Number(invoice.discount || 0);
        const total = Math.max(0, amount + tax - discount);
        const currency = invoice.currency || 'USD';
        const status = getInvoiceStatus(invoice);

        setText('modalInvoiceNumber', number);
        setText('modalCustomerName', customerDetails.name, 'Customer');
        setText('modalCustomerCompany', invoice.company || customerDetails.company, '');
        setText('modalCustomerEmail', invoice.email || customerDetails.email, '');
        setText('modalCustomerPhone', invoice.phone || customerDetails.phone, '');

        setText('modalIssueDate', formatDate(invoice.issue_date || invoice.created_at));
        setText('modalDueDate', formatDate(invoice.due_date));
        setText('modalQuoteReference', invoice.quote_reference);

        setText('modalService', service);
        setText('modalDescription', invoice.description, 'Service invoice');
        setText('modalBaseAmount', formatMoney(amount, currency));
        setText('modalSubtotal', formatMoney(amount, currency));
        setText('modalTax', formatMoney(tax, currency));
        setText('modalDiscount', formatMoney(discount, currency));
        setText('modalTotal', formatMoney(total, currency));
        setText('modalPaymentMethod', invoice.payment_method, 'Not recorded');
        setText('modalPaidDate', formatDate(invoice.paid_date));

        const statusElement = document.getElementById('modalStatus');
        statusElement.textContent = status;
        statusElement.className = 'status-badge ' + statusClass(status);
    }

    document.querySelectorAll('[data-invoice]').forEach(button => {
        button.addEventListener('click', function () {
            try {
                const invoice = JSON.parse(this.dataset.invoice);
                populateInvoice(invoice);
            } catch (error) {
                console.error('Unable to display invoice details.', error);
            }
        });
    });

    function printInvoice(invoice) {
        populateInvoice(invoice);

        // Populate the modal first, then print only the invoice content.
        const modalElement = document.getElementById('invoiceModal');
        const modalInstance = bootstrap.Modal.getOrCreateInstance(modalElement);

        modalInstance.show();

        setTimeout(() => {
            window.print();
        }, 350);
    }

    document.querySelectorAll('[data-print-invoice]').forEach(button => {
        button.addEventListener('click', function () {
            try {
                const invoice = JSON.parse(this.dataset.printInvoice);
                printInvoice(invoice);
            } catch (error) {
                console.error('Unable to print invoice.', error);
            }
        });
    });

    document.getElementById('printModalInvoice').addEventListener('click', function () {
        if (selectedInvoice) {
            window.print();
        }
    });
</script>

</body>
</html>
