
<?php
session_start();
date_default_timezone_set('Asia/Colombo');

function e($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

// Require customer login
if (empty($_SESSION['proserve_customer'])) {
    $_SESSION['proserve_profile_notice'] = [
        'type' => 'warning',
        'message' => 'Please log in to view your quotations.'
    ];

    header('Location: login.php');
    exit;
}

$customer = $_SESSION['proserve_customer'];
$profile = $_SESSION['proserve_profile'] ?? [];

$customerName = $profile['name'] ?? $customer['name'] ?? 'Customer';
$customerEmail = $profile['email'] ?? $customer['email'] ?? '';

// Generate a CSRF token for quotation actions
if (empty($_SESSION['proserve_quotations_csrf'])) {
    $_SESSION['proserve_quotations_csrf'] = bin2hex(random_bytes(32));
}

// Customer ownership check
function belongsToCustomer($record, $email) {
    if ($email === '') {
        return true;
    }

    $recordEmail = $record['email']
        ?? $record['customer_email']
        ?? '';

    return strcasecmp(trim((string)$recordEmail), trim((string)$email)) === 0;
}

// Find quotations created by this customer.
// The session key should match request_quote.php.
$allQuotes = $_SESSION['proserve_quotes'] ?? [];
$quotations = [];

foreach ($allQuotes as $key => $quote) {
    if (is_array($quote) && belongsToCustomer($quote, $customerEmail)) {
        $quote['_session_key'] = $key;
        $quotations[] = $quote;
    }
}

function quoteStatusClass($status) {
    $status = strtoupper(trim((string)$status));

    if (in_array($status, ['APPROVED', 'ACCEPTED', 'COMPLETED'], true)) {
        return 'status-success';
    }

    if (in_array($status, ['REJECTED', 'DECLINED', 'CANCELLED', 'EXPIRED'], true)) {
        return 'status-danger';
    }

    if (in_array($status, ['SENT', 'QUOTE SENT', 'READY'], true)) {
        return 'status-info';
    }

    return 'status-pending';
}

function formatQuoteDate($value, $format = 'd M Y') {
    if (empty($value)) {
        return '—';
    }

    $timestamp = strtotime((string)$value);
    return $timestamp ? date($format, $timestamp) : e($value);
}

function quoteReference($quote) {
    return $quote['reference']
        ?? $quote['quote_reference']
        ?? $quote['quotation_number']
        ?? 'Not assigned';
}

function quoteService($quote) {
    return $quote['service']
        ?? $quote['service_name']
        ?? $quote['service_type']
        ?? 'General Service';
}

function quoteStatus($quote) {
    return strtoupper(trim((string)($quote['status'] ?? 'PENDING')));
}

function quoteAmount($quote) {
    foreach (['quoted_amount', 'estimated_price', 'quote_amount', 'amount', 'price'] as $field) {
        if (isset($quote[$field]) && $quote[$field] !== '' && is_numeric($quote[$field])) {
            return (float)$quote[$field];
        }
    }

    return null;
}

$filter = strtoupper(trim($_GET['status'] ?? 'ALL'));
$allowedFilters = [
    'ALL',
    'PENDING',
    'UNDER REVIEW',
    'QUOTE SENT',
    'APPROVED',
    'REJECTED',
    'CANCELLED'
];

if (!in_array($filter, $allowedFilters, true)) {
    $filter = 'ALL';
}

$search = trim($_GET['search'] ?? '');

$filteredQuotes = array_values(array_filter($quotations, function ($quote) use ($filter, $search) {
    $status = quoteStatus($quote);

    if ($filter !== 'ALL') {
        if ($filter === 'PENDING' && !in_array($status, ['PENDING', 'NEW', 'NEW REQUEST'], true)) {
            return false;
        } elseif ($filter === 'UNDER REVIEW' && !in_array($status, ['UNDER REVIEW', 'IN REVIEW'], true)) {
            return false;
        } elseif ($filter === 'QUOTE SENT' && !in_array($status, ['QUOTE SENT', 'SENT', 'READY'], true)) {
            return false;
        } elseif ($filter === 'APPROVED' && !in_array($status, ['APPROVED', 'ACCEPTED'], true)) {
            return false;
        } elseif ($filter === 'REJECTED' && !in_array($status, ['REJECTED', 'DECLINED'], true)) {
            return false;
        } elseif ($filter === 'CANCELLED' && $status !== 'CANCELLED') {
            return false;
        } elseif (!in_array($filter, ['PENDING', 'UNDER REVIEW', 'QUOTE SENT', 'APPROVED', 'REJECTED', 'CANCELLED'], true)) {
            return false;
        }
    }

    if ($search !== '') {
        $haystack = implode(' ', [
            quoteReference($quote),
            quoteService($quote),
            $quote['description'] ?? '',
            $quote['project_description'] ?? '',
            $quote['company'] ?? '',
            $quote['status'] ?? ''
        ]);

        if (stripos($haystack, $search) === false) {
            return false;
        }
    }

    return true;
}));

// Process customer cancellation requests for quotations that have not been reviewed.
$flash = $_SESSION['proserve_quotations_flash'] ?? null;
unset($_SESSION['proserve_quotations_flash']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $postedToken = $_POST['csrf_token'] ?? '';
    $action = $_POST['action'] ?? '';
    $quoteKey = $_POST['quote_key'] ?? '';

    if (!is_string($postedToken) ||
        !hash_equals($_SESSION['proserve_quotations_csrf'], $postedToken)) {
        $flash = [
            'type' => 'danger',
            'message' => 'Session verification failed. Please refresh the page and try again.'
        ];
    } elseif ($action === 'cancel' && (is_string($quoteKey) || is_int($quoteKey))) {
        $allQuotes = $_SESSION['proserve_quotes'] ?? [];
        $keyFound = false;

        foreach ($allQuotes as $key => $quote) {
            if ((string)$key !== (string)$quoteKey || !is_array($quote)) {
                continue;
            }

            if (!belongsToCustomer($quote, $customerEmail)) {
                continue;
            }

            $status = quoteStatus($quote);

            // Only unreviewed quotations may be cancelled in this demo.
            if (!in_array($status, ['PENDING', 'NEW', 'NEW REQUEST'], true)) {
                $flash = [
                    'type' => 'warning',
                    'message' => 'Only pending quotation requests can be cancelled.'
                ];
            } else {
                $_SESSION['proserve_quotes'][$key]['status'] = 'CANCELLED';
                $_SESSION['proserve_quotes'][$key]['cancelled_at'] = date('Y-m-d H:i:s');

                $flash = [
                    'type' => 'success',
                    'message' => 'Your quotation request has been cancelled.'
                ];
            }

            $keyFound = true;
            break;
        }

        if (!$keyFound) {
            $flash = [
                'type' => 'danger',
                'message' => 'The quotation could not be found in your account.'
            ];
        }
    }

    $_SESSION['proserve_quotations_flash'] = $flash;
    header('Location: my_quotations.php');
    exit;
}

// Calculate summary statistics
$totalQuotes = count($quotations);
$pendingCount = 0;
$underReviewCount = 0;
$sentCount = 0;
$approvedCount = 0;

foreach ($quotations as $quote) {
    $status = quoteStatus($quote);

    if (in_array($status, ['PENDING', 'NEW', 'NEW REQUEST'], true)) {
        $pendingCount++;
    } elseif (in_array($status, ['UNDER REVIEW', 'IN REVIEW'], true)) {
        $underReviewCount++;
    } elseif (in_array($status, ['QUOTE SENT', 'SENT', 'READY'], true)) {
        $sentCount++;
    } elseif (in_array($status, ['APPROVED', 'ACCEPTED'], true)) {
        $approvedCount++;
    }
}

$initials = '';
foreach (array_slice(preg_split('/\s+/', trim($customerName)) ?: [], 0, 2) as $part) {
    if ($part !== '') {
        $initials .= mb_strtoupper(mb_substr($part, 0, 1));
    }
}
if ($initials === '') {
    $initials = 'C';
}

// Sort quotations newest first
usort($filteredQuotes, function ($a, $b) {
    $dateA = strtotime($a['created_at'] ?? $a['date'] ?? '') ?: 0;
    $dateB = strtotime($b['created_at'] ?? $b['date'] ?? '') ?: 0;
    return $dateB <=> $dateA;
});
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Quotations | ProServe</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap"
          rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
          rel="stylesheet">

    <style>
        :root {
            --primary: #2563eb;
            --primary-dark: #1d4ed8;
            --navy: #10233f;
            --muted: #64748b;
            --background: #f4f7fb;
            --border: #e2e8f0;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: 'Inter', 'Trebuchet MS', sans-serif;
            background: var(--background);
            color: #1e293b;
        }

        a {
            text-decoration: none;
        }

        .navbar {
            background: #fff;
            border-bottom: 1px solid var(--border);
        }

        .navbar-brand {
            color: var(--navy);
            font-size: 1.5rem;
            font-weight: 800;
            letter-spacing: -1px;
        }

        .brand-highlight {
            color: var(--primary);
        }

        .navbar .nav-link {
            color: #475569;
            font-size: .9rem;
            font-weight: 600;
            padding: .7rem .75rem;
        }

        .navbar .nav-link:hover,
        .navbar .nav-link.active {
            color: var(--primary);
        }

        .page-wrap {
            padding-top: 34px;
            padding-bottom: 45px;
        }

        .eyebrow {
            color: var(--primary);
            font-size: .76rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1.5px;
        }

        .page-title {
            color: var(--navy);
            font-size: clamp(1.8rem, 4vw, 2.4rem);
            font-weight: 800;
            letter-spacing: -1px;
            margin: 7px 0;
        }

        .page-description {
            color: var(--muted);
            font-size: .9rem;
            line-height: 1.7;
            margin: 0;
        }

        .btn-primary-custom {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            background: var(--primary);
            color: #fff;
            border: 1px solid var(--primary);
            border-radius: 10px;
            padding: 11px 17px;
            font-size: .87rem;
            font-weight: 700;
        }

        .btn-primary-custom:hover {
            background: var(--primary-dark);
            color: #fff;
        }

        .btn-light-custom {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 7px;
            padding: 10px 14px;
            border: 1px solid var(--border);
            border-radius: 10px;
            background: #fff;
            color: #334155;
            font-size: .84rem;
            font-weight: 700;
        }

        .btn-light-custom:hover {
            border-color: var(--primary);
            color: var(--primary);
        }

        .stat-card {
            height: 100%;
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 21px;
            box-shadow: 0 4px 18px rgba(15,23,42,.025);
        }

        .stat-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 10px;
            margin-bottom: 17px;
        }

        .stat-label {
            color: var(--muted);
            font-size: .8rem;
            font-weight: 600;
        }

        .stat-icon {
            width: 42px;
            height: 42px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
            font-size: 1.15rem;
        }

        .blue {
            background: #eff6ff;
            color: #2563eb;
        }

        .orange {
            background: #fff7ed;
            color: #ea580c;
        }

        .purple {
            background: #f5f3ff;
            color: #7c3aed;
        }

        .green {
            background: #f0fdf4;
            color: #16a34a;
        }

        .stat-number {
            color: var(--navy);
            font-size: 2rem;
            font-weight: 800;
            letter-spacing: -1px;
            line-height: 1.1;
        }

        .stat-caption {
            color: var(--muted);
            font-size: .76rem;
            margin-top: 8px;
        }

        .content-card {
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 17px;
            margin-top: 24px;
            overflow: hidden;
            box-shadow: 0 4px 18px rgba(15,23,42,.025);
        }

        .card-heading {
            padding: 22px 24px;
            border-bottom: 1px solid var(--border);
        }

        .card-heading h2 {
            color: var(--navy);
            font-size: 1.05rem;
            font-weight: 800;
            margin-bottom: 6px;
        }

        .card-heading p {
            color: var(--muted);
            font-size: .82rem;
            margin: 0;
        }

        .filter-area {
            padding: 20px 24px;
            background: #fbfdff;
            border-bottom: 1px solid var(--border);
        }

        .form-control,
        .form-select {
            min-height: 44px;
            border: 1px solid #dbe3ee;
            border-radius: 10px;
            font-size: .86rem;
            box-shadow: none !important;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--primary);
        }

        .table-responsive {
            padding: 0 20px 8px;
        }

        .table {
            vertical-align: middle;
            margin-bottom: 0;
        }

        .table thead th {
            color: #64748b;
            font-size: .72rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .4px;
            padding: 16px 12px;
            border-bottom: 1px solid var(--border);
            white-space: nowrap;
        }

        .table tbody td {
            color: #475569;
            font-size: .82rem;
            padding: 17px 12px;
            border-color: #f1f5f9;
        }

        .table tbody tr:last-child td {
            border-bottom: 0;
        }

        .quote-service {
            color: var(--navy);
            font-weight: 800;
            margin-bottom: 5px;
        }

        .quote-ref {
            color: var(--muted);
            font-size: .73rem;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            padding: 6px 9px;
            border-radius: 30px;
            font-size: .68rem;
            font-weight: 800;
            white-space: nowrap;
        }

        .status-success {
            color: #15803d;
            background: #dcfce7;
        }

        .status-pending {
            color: #a16207;
            background: #fef9c3;
        }

        .status-danger {
            color: #b91c1c;
            background: #fee2e2;
        }

        .status-info {
            color: #1d4ed8;
            background: #dbeafe;
        }

        .amount {
            color: var(--navy);
            font-weight: 800;
            white-space: nowrap;
        }

        .empty-state {
            padding: 55px 20px;
            text-align: center;
        }

        .empty-icon {
            width: 65px;
            height: 65px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 17px;
            border-radius: 18px;
            background: #eff6ff;
            color: var(--primary);
            font-size: 1.7rem;
        }

        .empty-state h3 {
            color: var(--navy);
            font-size: 1.05rem;
            font-weight: 800;
        }

        .empty-state p {
            color: var(--muted);
            font-size: .85rem;
            max-width: 440px;
            line-height: 1.7;
            margin: 8px auto 20px;
        }

        .quotation-details {
            background: #f8fafc;
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 16px;
            margin-bottom: 18px;
        }

        .detail-label {
            color: var(--muted);
            font-size: .75rem;
            margin-bottom: 5px;
        }

        .detail-value {
            color: var(--navy);
            font-size: .88rem;
            font-weight: 700;
            overflow-wrap: anywhere;
        }

        .modal-content {
            border: 0;
            border-radius: 17px;
            overflow: hidden;
        }

        .modal-header {
            background: #f8fafc;
            border-bottom: 1px solid var(--border);
            padding: 20px 24px;
        }

        .modal-title {
            color: var(--navy);
            font-size: 1.05rem;
            font-weight: 800;
        }

        .modal-body {
            padding: 24px;
        }

        footer {
            background: #fff;
            border-top: 1px solid var(--border);
            padding: 24px 0;
            color: var(--muted);
            font-size: .82rem;
        }

        footer strong {
            color: var(--navy);
        }

        @media (max-width: 767px) {
            .page-wrap {
                padding-top: 25px;
            }

            .card-heading,
            .filter-area {
                padding: 18px;
            }

            .table-responsive {
                padding: 0 10px 8px;
            }
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg sticky-top">
    <div class="container">
        <a class="navbar-brand" href="index.php">
            Pro<span class="brand-highlight">Serve</span>
        </a>

        <button class="navbar-toggler" type="button"
                data-bs-toggle="collapse" data-bs-target="#mainNavbar"
                aria-controls="mainNavbar" aria-expanded="false"
                aria-label="Toggle navigation">
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
                    <a class="nav-link" href="my_appointments.php">Appointments</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="my_quotations.php">My Quotations</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="profile.php">My Profile</a>
                </li>
                <li class="nav-item ms-lg-2">
                    <a href="request_quote.php" class="btn-primary-custom">
                        <i class="bi bi-plus-lg"></i> Request a Quote
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<main class="container page-wrap">

    <div class="d-flex flex-column flex-md-row justify-content-between
                align-items-md-center gap-3 mb-4">
        <div>
            <div class="eyebrow">Customer portal</div>
            <h1 class="page-title">My Quotations</h1>
            <p class="page-description">
                View your quotation requests, track progress, and review available estimates.
            </p>
        </div>

        <a href="request_quote.php" class="btn-primary-custom">
            <i class="bi bi-file-earmark-plus"></i> Request New Quote
        </a>
    </div>

    <?php if ($flash): ?>
        <div class="alert alert-<?= e($flash['type'] ?? 'info') ?> alert-dismissible fade show"
             role="alert">
            <?= e($flash['message'] ?? '') ?>
            <button type="button" class="btn-close"
                    data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- Summary cards -->
    <div class="row g-3">
        <div class="col-6 col-xl-3">
            <div class="stat-card">
                <div class="stat-top">
                    <span class="stat-label">Total Requests</span>
                    <div class="stat-icon blue">
                        <i class="bi bi-files"></i>
                    </div>
                </div>
                <div class="stat-number"><?= $totalQuotes ?></div>
                <div class="stat-caption">All your quotation requests</div>
            </div>
        </div>

        <div class="col-6 col-xl-3">
            <div class="stat-card">
                <div class="stat-top">
                    <span class="stat-label">Pending</span>
                    <div class="stat-icon orange">
                        <i class="bi bi-hourglass-split"></i>
                    </div>
                </div>
                <div class="stat-number"><?= $pendingCount ?></div>
                <div class="stat-caption">Awaiting initial review</div>
            </div>
        </div>

        <div class="col-6 col-xl-3">
            <div class="stat-card">
                <div class="stat-top">
                    <span class="stat-label">Under Review</span>
                    <div class="stat-icon purple">
                        <i class="bi bi-search"></i>
                    </div>
                </div>
                <div class="stat-number"><?= $underReviewCount ?></div>
                <div class="stat-caption">Being reviewed by the team</div>
            </div>
        </div>

        <div class="col-6 col-xl-3">
            <div class="stat-card">
                <div class="stat-top">
                    <span class="stat-label">Approved</span>
                    <div class="stat-icon green">
                        <i class="bi bi-check-circle"></i>
                    </div>
                </div>
                <div class="stat-number"><?= $approvedCount ?></div>
                <div class="stat-caption">Approved or accepted requests</div>
            </div>
        </div>
    </div>

    <!-- Quotations table -->
    <section class="content-card">
        <div class="card-heading d-flex flex-column flex-md-row
                    justify-content-between align-items-md-center gap-2">
            <div>
                <h2>Quotation Request History</h2>
                <p>Search, filter, and review the quotations associated with your account.</p>
            </div>
            <span class="text-secondary small">
                <?= count($filteredQuotes) ?> result(s)
            </span>
        </div>

        <div class="filter-area">
            <form action="my_quotations.php" method="GET" class="row g-3">
                <div class="col-md-7">
                    <label for="search" class="form-label small fw-bold">
                        Search quotations
                    </label>
                    <input type="search" id="search" name="search"
                           class="form-control"
                           value="<?= e($search) ?>"
                           placeholder="Reference, service, description...">
                </div>

                <div class="col-md-3">
                    <label for="status" class="form-label small fw-bold">
                        Filter by status
                    </label>
                    <select id="status" name="status" class="form-select">
                        <?php foreach ($allowedFilters as $option): ?>
                            <option value="<?= e($option) ?>"
                                <?= $filter === $option ? 'selected' : '' ?>>
                                <?= e(ucwords(strtolower($option))) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-2 d-flex align-items-end gap-2">
                    <button type="submit" class="btn-primary-custom w-100">
                        <i class="bi bi-search"></i> Search
                    </button>
                </div>

                <?php if ($search !== '' || $filter !== 'ALL'): ?>
                    <div class="col-12">
                        <a href="my_quotations.php" class="small">
                            <i class="bi bi-x-circle"></i> Clear filters
                        </a>
                    </div>
                <?php endif; ?>
            </form>
        </div>

        <?php if (!empty($filteredQuotes)): ?>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Quotation</th>
                            <th>Requested Date</th>
                            <th>Estimated Amount</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($filteredQuotes as $quote): ?>
                            <?php
                            $reference = quoteReference($quote);
                            $service = quoteService($quote);
                            $status = quoteStatus($quote);
                            $amount = quoteAmount($quote);
                            $requestedDate = $quote['created_at'] ?? $quote['date'] ?? '';

                            $description = $quote['description']
                                ?? $quote['project_description']
                                ?? $quote['requirements']
                                ?? 'No additional description provided.';

                            $modalId = 'quoteModal' . substr(
                                hash('sha256', (string)$quote['_session_key']),
                                0,
                                12
                            );
                            ?>
                            <tr>
                                <td>
                                    <div class="quote-service"><?= e($service) ?></div>
                                    <div class="quote-ref">
                                        Ref: <?= e($reference) ?>
                                    </div>
                                </td>

                                <td><?= e(formatQuoteDate($requestedDate)) ?></td>

                                <td>
                                    <?php if ($amount !== null): ?>
                                        <div class="amount">
                                            <?= e($quote['currency'] ?? 'LKR') ?>
                                            <?= e(number_format($amount, 2)) ?>
                                        </div>
                                    <?php else: ?>
                                        <span class="text-secondary">Not yet quoted</span>
                                    <?php endif; ?>
                                </td>

                                <td>
                                    <span class="status-badge <?= e(quoteStatusClass($status)) ?>">
                                        <?= e(ucwords(strtolower($status))) ?>
                                    </span>
                                </td>

                                <td class="text-end">
                                    <button type="button" class="btn-light-custom"
                                            data-bs-toggle="modal"
                                            data-bs-target="#<?= e($modalId) ?>">
                                        <i class="bi bi-eye"></i> Details
                                    </button>
                                </td>
                            </tr>

                            <!-- Quotation detail modal -->
                            <div class="modal fade" id="<?= e($modalId) ?>"
                                 tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <div>
                                                <h2 class="modal-title">Quotation Details</h2>
                                                <div class="text-secondary small mt-1">
                                                    Reference: <?= e($reference) ?>
                                                </div>
                                            </div>
                                            <button type="button" class="btn-close"
                                                    data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                        </div>

                                        <div class="modal-body">
                                            <div class="d-flex flex-wrap justify-content-between
                                                        align-items-start gap-3 mb-4">
                                                <div>
                                                    <div class="text-secondary small mb-1">
                                                        Requested service
                                                    </div>
                                                    <h3 class="h5 fw-bold mb-0">
                                                        <?= e($service) ?>
                                                    </h3>
                                                </div>

                                                <span class="status-badge <?= e(quoteStatusClass($status)) ?>">
                                                    <?= e(ucwords(strtolower($status))) ?>
                                                </span>
                                            </div>

                                            <div class="quotation-details">
                                                <div class="row g-3">
                                                    <div class="col-md-6">
                                                        <div class="detail-label">Request Date</div>
                                                        <div class="detail-value">
                                                            <?= e(formatQuoteDate($requestedDate, 'd M Y, h:i A')) ?>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="detail-label">Estimated Amount</div>
                                                        <div class="detail-value">
                                                            <?php if ($amount !== null): ?>
                                                                <?= e($quote['currency'] ?? 'LKR') ?>
                                                                <?= e(number_format($amount, 2)) ?>
                                                            <?php else: ?>
                                                                Awaiting quotation
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="detail-label">Customer</div>
                                                        <div class="detail-value">
                                                            <?= e($quote['name'] ?? $quote['full_name'] ?? $customerName) ?>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="detail-label">Email Address</div>
                                                        <div class="detail-value">
                                                            <?= e($quote['email'] ?? $customerEmail ?: 'Not provided') ?>
                                                        </div>
                                                    </div>

                                                    <?php if (!empty($quote['phone'])): ?>
                                                        <div class="col-md-6">
                                                            <div class="detail-label">Phone</div>
                                                            <div class="detail-value">
                                                                <?= e($quote['phone']) ?>
                                                            </div>
                                                        </div>
                                                    <?php endif; ?>

                                                    <?php if (!empty($quote['company'])): ?>
                                                        <div class="col-md-6">
                                                            <div class="detail-label">Company</div>
                                                            <div class="detail-value">
                                                                <?= e($quote['company']) ?>
                                                            </div>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>

                                            <h4 class="h6 fw-bold mb-2">Project Description</h4>
                                            <p class="text-secondary small"
                                               style="line-height:1.8;white-space:pre-line;"><?= e($description) ?></p>

                                            <?php if (!empty($quote['admin_notes']) || !empty($quote['notes'])): ?>
                                                <div class="alert alert-light border mt-3 mb-0">
                                                    <div class="fw-bold small mb-1">
                                                        Additional Notes
                                                    </div>
                                                    <div class="small text-secondary"
                                                         style="white-space:pre-line;">
                                                        <?= e($quote['admin_notes'] ?? $quote['notes']) ?>
                                                    </div>
                                                </div>
                                            <?php endif; ?>

                                            <div class="d-flex flex-wrap gap-2 mt-4">
                                                <a href="contact.php" class="btn-primary-custom">
                                                    <i class="bi bi-chat-dots"></i>
                                                    Contact ProServe
                                                </a>

                                                <?php if (in_array($status, ['PENDING', 'NEW', 'NEW REQUEST'], true)): ?>
                                                    <form method="POST" action="my_quotations.php"
                                                          onsubmit="return confirm('Cancel this quotation request?');">
                                                        <input type="hidden" name="csrf_token"
                                                               value="<?= e($_SESSION['proserve_quotations_csrf']) ?>">
                                                        <input type="hidden" name="action" value="cancel">
                                                        <input type="hidden" name="quote_key"
                                                               value="<?= e($quote['_session_key']) ?>">

                                                        <button type="submit" class="btn btn-outline-danger rounded-3">
                                                            <i class="bi bi-x-circle"></i>
                                                            Cancel Request
                                                        </button>
                                                    </form>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="empty-state">
                <div class="empty-icon">
                    <i class="bi bi-file-earmark-text"></i>
                </div>

                <?php if ($search !== '' || $filter !== 'ALL'): ?>
                    <h3>No matching quotations found</h3>
                    <p>
                        Try changing your search phrase or selecting a different status filter.
                    </p>
                    <a href="my_quotations.php" class="btn-light-custom">
                        <i class="bi bi-arrow-counterclockwise"></i> Clear Filters
                    </a>
                <?php else: ?>
                    <h3>You haven't requested a quotation yet</h3>
                    <p>
                        Tell us about your project or business requirements.
                        Our team can review your request and prepare a suitable quotation.
                    </p>
                    <a href="request_quote.php" class="btn-primary-custom">
                        <i class="bi bi-plus-lg"></i> Request Your First Quote
                    </a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </section>

    <div class="row g-3 mt-1">
        <div class="col-md-6">
            <div class="quotation-details h-100 mb-0">
                <div class="d-flex gap-3">
                    <div class="stat-icon blue flex-shrink-0">
                        <i class="bi bi-info-circle"></i>
                    </div>
                    <div>
                        <div class="fw-bold mb-1">About quotation statuses</div>
                        <p class="small text-secondary mb-0" style="line-height:1.7;">
                            Pending requests are awaiting review. Under Review means
                            the team is assessing your requirements. Quote Sent means
                            an estimate is available for consideration.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="quotation-details h-100 mb-0">
                <div class="d-flex gap-3">
                    <div class="stat-icon green flex-shrink-0">
                        <i class="bi bi-headset"></i>
                    </div>
                    <div>
                        <div class="fw-bold mb-1">Need help with a quotation?</div>
                        <p class="small text-secondary mb-2" style="line-height:1.7;">
                            Contact our team if you need clarification about a request
                            or an estimate.
                        </p>
                        <a href="contact.php" class="small fw-bold">
                            Contact ProServe <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

</main>

<footer>
    <div class="container d-flex flex-column flex-md-row
                justify-content-between align-items-center gap-2">
        <div>
            <strong>ProServe</strong> &mdash; CRM &amp; Service Management System
        </div>
        <div>Portfolio demonstration by <strong>Codvanta</strong>.</div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
