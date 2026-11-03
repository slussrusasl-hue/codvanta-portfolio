
<?php
session_start();
date_default_timezone_set('Asia/Colombo');

/*
|--------------------------------------------------------------------------
| ProServe - Administrator Quotation Management
|--------------------------------------------------------------------------
| Features:
| - View customer quotation requests
| - Search and filter quotations
| - Review project requirements
| - Set estimated quotation amounts
| - Update quotation status
| - Add administrator notes
| - Delete demonstration records
| - Convert an approved quotation into an invoice draft
|
| Demonstration data is stored in PHP sessions.
|--------------------------------------------------------------------------
*/

function e($value): string
{
    return htmlspecialchars((string)($value ?? ''), ENT_QUOTES, 'UTF-8');
}

function moneyLKR($amount): string
{
    return 'LKR ' . number_format((float)$amount, 2);
}

function quotationStatusClass($status): string
{
    $status = strtoupper(trim((string)$status));

    $classes = [
        'NEW' => 'status-new',
        'PENDING' => 'status-new',
        'UNDER REVIEW' => 'status-review',
        'QUOTE SENT' => 'status-sent',
        'APPROVED' => 'status-approved',
        'REJECTED' => 'status-rejected',
        'CANCELLED' => 'status-cancelled'
    ];

    return $classes[$status] ?? 'status-review';
}

function quotationReference(array $quotation, int $index): string
{
    return (string)(
        $quotation['reference']
        ?? $quotation['quote_reference']
        ?? $quotation['quotation_number']
        ?? ('QUO-' . str_pad((string)($index + 1), 4, '0', STR_PAD_LEFT))
    );
}

function quotationAmount(array $quotation): float
{
    foreach ([
        'quoted_amount',
        'estimated_price',
        'quote_amount',
        'amount',
        'price'
    ] as $field) {
        if (isset($quotation[$field]) && is_numeric($quotation[$field])) {
            return (float)$quotation[$field];
        }
    }

    return 0;
}

function quotationDate(array $quotation): string
{
    return (string)($quotation['created_at'] ?? $quotation['date'] ?? '');
}

function quotationCustomerName(array $quotation): string
{
    return (string)(
        $quotation['name']
        ?? $quotation['full_name']
        ?? $quotation['customer_name']
        ?? 'Customer'
    );
}

function quotationService(array $quotation): string
{
    return (string)(
        $quotation['service']
        ?? $quotation['service_name']
        ?? $quotation['service_type']
        ?? 'Custom Service'
    );
}

/*
|--------------------------------------------------------------------------
| Initialize demonstration quotations
|--------------------------------------------------------------------------
| Existing customer-side request_quote.php should store requests in:
| $_SESSION['proserve_quotes']
|--------------------------------------------------------------------------
*/

if (!isset($_SESSION['proserve_quotes'])) {
    $_SESSION['proserve_quotes'] = [
        [
            'reference' => 'QUO-2026-001',
            'name' => 'Amelia Fernando',
            'email' => 'amelia@example.com',
            'phone' => '+94 77 123 4567',
            'company' => 'Urban Retail Solutions',
            'service' => 'E-commerce Development',
            'description' => 'We need a responsive e-commerce platform with product management, cart, checkout, order tracking, and an administrator dashboard.',
            'status' => 'UNDER REVIEW',
            'quoted_amount' => 275000,
            'admin_notes' => 'Review payment gateway and delivery integration requirements.',
            'created_at' => date('Y-m-d H:i:s', strtotime('-2 days'))
        ],
        [
            'reference' => 'QUO-2026-002',
            'name' => 'Daniel Perera',
            'email' => 'daniel@example.com',
            'phone' => '+94 71 234 5678',
            'company' => 'DP Business Services',
            'service' => 'Business Management Systems',
            'description' => 'Looking for an integrated system to manage customers, appointments, employees, invoices, and monthly reports.',
            'status' => 'QUOTE SENT',
            'quoted_amount' => 450000,
            'admin_notes' => 'Quotation prepared. Waiting for customer confirmation.',
            'created_at' => date('Y-m-d H:i:s', strtotime('-4 days'))
        ],
        [
            'reference' => 'QUO-2026-003',
            'name' => 'Sarah Wijesinghe',
            'email' => 'sarah@example.com',
            'phone' => '+94 76 345 6789',
            'company' => 'Sarah Wellness Studio',
            'service' => 'Custom Web Application Development',
            'description' => 'A booking website for customers to schedule consultations and receive email confirmations.',
            'status' => 'APPROVED',
            'quoted_amount' => 180000,
            'admin_notes' => 'Customer approved the quotation. Prepare project onboarding.',
            'created_at' => date('Y-m-d H:i:s', strtotime('-7 days'))
        ],
        [
            'reference' => 'QUO-2026-004',
            'name' => 'Michael Silva',
            'email' => 'michael@example.com',
            'phone' => '+94 72 456 7890',
            'company' => 'Silva Logistics',
            'service' => 'Database Development',
            'description' => 'Database redesign, data migration, and query optimization for an existing logistics application.',
            'status' => 'NEW',
            'quoted_amount' => 0,
            'admin_notes' => '',
            'created_at' => date('Y-m-d H:i:s', strtotime('-1 day'))
        ],
        [
            'reference' => 'QUO-2026-005',
            'name' => 'Nethmi Jayawardena',
            'email' => 'nethmi@example.com',
            'phone' => '+94 75 567 8901',
            'company' => 'NJ Digital',
            'service' => 'Website Maintenance',
            'description' => 'Monthly maintenance and security updates for a business website.',
            'status' => 'REJECTED',
            'quoted_amount' => 25000,
            'admin_notes' => 'Customer postponed the project.',
            'created_at' => date('Y-m-d H:i:s', strtotime('-10 days'))
        ],
        [
            'reference' => 'QUO-2026-006',
            'name' => 'Kevin Rodrigo',
            'email' => 'kevin@example.com',
            'phone' => '+94 70 678 9012',
            'company' => 'KR Consulting',
            'service' => 'API Integration',
            'description' => 'Integrate a third-party payment provider and an external inventory service.',
            'status' => 'PENDING',
            'quoted_amount' => 85000,
            'admin_notes' => 'Waiting for technical requirements.',
            'created_at' => date('Y-m-d H:i:s', strtotime('-3 days'))
        ]
    ];
}

if (!isset($_SESSION['proserve_quotations_csrf'])) {
    $_SESSION['proserve_quotations_csrf'] = bin2hex(random_bytes(32));
}

if (!isset($_SESSION['proserve_quotations_notice'])) {
    $_SESSION['proserve_quotations_notice'] = null;
}

/*
|--------------------------------------------------------------------------
| Handle administrator actions
|--------------------------------------------------------------------------
*/

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = (string)($_POST['csrf_token'] ?? '');
    $sessionToken = (string)$_SESSION['proserve_quotations_csrf'];

    $message = '';
    $messageType = 'success';

    if (!hash_equals($sessionToken, $token)) {
        $message = 'Session verification failed. Please refresh and try again.';
        $messageType = 'danger';
    } else {
        $action = (string)($_POST['action'] ?? '');
        $reference = trim((string)($_POST['reference'] ?? ''));

        $quotationIndex = null;

        foreach ($_SESSION['proserve_quotes'] as $index => $quotation) {
            if (quotationReference($quotation, $index) === $reference) {
                $quotationIndex = $index;
                break;
            }
        }

        if ($action === 'delete') {
            if ($quotationIndex === null) {
                $message = 'Quotation not found.';
                $messageType = 'danger';
            } else {
                array_splice($_SESSION['proserve_quotes'], $quotationIndex, 1);
                $message = 'Quotation deleted from the demonstration records.';
            }
        } elseif ($action === 'update') {
            if ($quotationIndex === null) {
                $message = 'Quotation not found.';
                $messageType = 'danger';
            } else {
                $status = strtoupper(trim((string)($_POST['status'] ?? 'UNDER REVIEW')));
                $amountRaw = trim((string)($_POST['quoted_amount'] ?? '0'));
                $notes = trim((string)($_POST['admin_notes'] ?? ''));

                $allowedStatuses = [
                    'NEW',
                    'PENDING',
                    'UNDER REVIEW',
                    'QUOTE SENT',
                    'APPROVED',
                    'REJECTED',
                    'CANCELLED'
                ];

                if (!in_array($status, $allowedStatuses, true)) {
                    $message = 'Please select a valid quotation status.';
                    $messageType = 'danger';
                } elseif (
                    !is_numeric($amountRaw) ||
                    (float)$amountRaw < 0 ||
                    (float)$amountRaw > 999999999999
                ) {
                    $message = 'Enter a valid non-negative quotation amount.';
                    $messageType = 'danger';
                } elseif (strlen($notes) > 2000) {
                    $message = 'Administrator notes cannot exceed 2,000 characters.';
                    $messageType = 'danger';
                } else {
                    $_SESSION['proserve_quotes'][$quotationIndex]['status'] = $status;
                    $_SESSION['proserve_quotes'][$quotationIndex]['quoted_amount'] = round((float)$amountRaw, 2);
                    $_SESSION['proserve_quotes'][$quotationIndex]['admin_notes'] = $notes;
                    $_SESSION['proserve_quotes'][$quotationIndex]['updated_at'] = date('Y-m-d H:i:s');

                    $message = 'Quotation updated successfully.';
                }
            }
        } elseif ($action === 'invoice_draft') {
            if ($quotationIndex === null) {
                $message = 'Quotation not found.';
                $messageType = 'danger';
            } else {
                $quotation = $_SESSION['proserve_quotes'][$quotationIndex];
                $status = strtoupper((string)($quotation['status'] ?? ''));

                if ($status !== 'APPROVED') {
                    $message = 'Only approved quotations can be converted to invoice drafts.';
                    $messageType = 'warning';
                } else {
                    if (!isset($_SESSION['proserve_invoice_drafts'])) {
                        $_SESSION['proserve_invoice_drafts'] = [];
                    }

                    $alreadyExists = false;

                    foreach ($_SESSION['proserve_invoice_drafts'] as $draft) {
                        if (($draft['quotation_reference'] ?? '') === $reference) {
                            $alreadyExists = true;
                            break;
                        }
                    }

                    if ($alreadyExists) {
                        $message = 'An invoice draft already exists for this quotation.';
                        $messageType = 'warning';
                    } else {
                        $_SESSION['proserve_invoice_drafts'][] = [
                            'invoice_reference' => 'INV-DRAFT-' . date('ymd') . '-' . strtoupper(bin2hex(random_bytes(2))),
                            'quotation_reference' => $reference,
                            'customer_name' => quotationCustomerName($quotation),
                            'email' => (string)($quotation['email'] ?? ''),
                            'company' => (string)($quotation['company'] ?? ''),
                            'service' => quotationService($quotation),
                            'amount' => quotationAmount($quotation),
                            'status' => 'DRAFT',
                            'created_at' => date('Y-m-d H:i:s')
                        ];

                        $message = 'Invoice draft created for ' . $reference . '.';
                    }
                }
            }
        } else {
            $message = 'Unknown action.';
            $messageType = 'danger';
        }
    }

    $_SESSION['proserve_quotations_notice'] = [
        'message' => $message,
        'type' => $messageType
    ];

    header('Location: quotations.php');
    exit;
}

/*
|--------------------------------------------------------------------------
| Read notification
|--------------------------------------------------------------------------
*/

$notice = $_SESSION['proserve_quotations_notice'];
unset($_SESSION['proserve_quotations_notice']);

$noticeMessage = is_array($notice) ? (string)($notice['message'] ?? '') : '';
$noticeType = is_array($notice) ? (string)($notice['type'] ?? 'success') : 'success';

/*
|--------------------------------------------------------------------------
| Prepare metrics and filters
|--------------------------------------------------------------------------
*/

$quotations = array_values($_SESSION['proserve_quotes']);

$totalCount = count($quotations);
$newCount = 0;
$reviewCount = 0;
$sentCount = 0;
$approvedCount = 0;
$totalQuotedValue = 0;

foreach ($quotations as $quotation) {
    $status = strtoupper(trim((string)($quotation['status'] ?? 'NEW')));

    if (in_array($status, ['NEW', 'PENDING'], true)) {
        $newCount++;
    }

    if ($status === 'UNDER REVIEW') {
        $reviewCount++;
    }

    if ($status === 'QUOTE SENT') {
        $sentCount++;
    }

    if ($status === 'APPROVED') {
        $approvedCount++;
    }

    $totalQuotedValue += quotationAmount($quotation);
}

$search = trim((string)($_GET['search'] ?? ''));
$statusFilter = strtoupper(trim((string)($_GET['status'] ?? 'ALL')));

$validFilters = [
    'ALL',
    'NEW',
    'PENDING',
    'UNDER REVIEW',
    'QUOTE SENT',
    'APPROVED',
    'REJECTED',
    'CANCELLED'
];

if (!in_array($statusFilter, $validFilters, true)) {
    $statusFilter = 'ALL';
}

$filteredQuotations = [];

foreach ($quotations as $index => $quotation) {
    $status = strtoupper(trim((string)($quotation['status'] ?? 'NEW')));

    if ($statusFilter !== 'ALL' && $status !== $statusFilter) {
        continue;
    }

    $searchable = implode(' ', [
        quotationReference($quotation, $index),
        quotationCustomerName($quotation),
        $quotation['email'] ?? '',
        $quotation['company'] ?? '',
        quotationService($quotation),
        $quotation['description'] ?? $quotation['project_description'] ?? $quotation['requirements'] ?? ''
    ]);

    if ($search !== '' && stripos($searchable, $search) === false) {
        continue;
    }

    $quotation['_display_reference'] = quotationReference($quotation, $index);
    $quotation['_display_name'] = quotationCustomerName($quotation);
    $quotation['_display_service'] = quotationService($quotation);
    $quotation['_display_amount'] = quotationAmount($quotation);
    $quotation['_display_date'] = quotationDate($quotation);

    $filteredQuotations[] = $quotation;
}

usort($filteredQuotations, function ($a, $b) {
    return strcmp(
        (string)($b['_display_date'] ?? ''),
        (string)($a['_display_date'] ?? '')
    );
});

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Quotation Management | ProServe Admin</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary: #2563eb;
            --primary-dark: #1d4ed8;
            --navy: #10233f;
            --muted: #718096;
            --border: #e8edf4;
            --background: #f5f8fc;
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            background: var(--background);
            color: #26364b;
            font-family: 'Inter', 'Trebuchet MS', sans-serif;
            font-size: 14px;
        }

        a { text-decoration: none; }

        .sidebar {
            width: 250px;
            position: fixed;
            inset: 0 auto 0 0;
            background: var(--navy);
            color: white;
            padding: 26px 17px;
            z-index: 1030;
            overflow-y: auto;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 11px;
            color: white;
            font-weight: 800;
            font-size: 23px;
            padding: 0 12px;
            margin-bottom: 34px;
        }

        .brand-icon {
            width: 39px;
            height: 39px;
            display: grid;
            place-items: center;
            border-radius: 12px;
            background: var(--primary);
            font-size: 21px;
        }

        .sidebar-label {
            color: #94a8c5;
            font-size: 10px;
            font-weight: 800;
            letter-spacing: 1.5px;
            padding: 0 12px;
            margin: 22px 0 9px;
        }

        .nav-link {
            color: #c4d0e1;
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px;
            border-radius: 9px;
            margin-bottom: 5px;
            font-size: 13px;
            transition: .2s;
        }

        .nav-link:hover, .nav-link.active {
            color: white;
            background: rgba(59, 130, 246, .22);
        }

        .nav-link.active { box-shadow: inset 3px 0 #60a5fa; }
        .nav-link i { font-size: 17px; }

        .sidebar-bottom {
            margin-top: 35px;
            border-top: 1px solid rgba(255,255,255,.12);
            padding-top: 16px;
        }

        .main { margin-left: 250px; min-height: 100vh; }

        .topbar {
            background: white;
            min-height: 76px;
            border-bottom: 1px solid var(--border);
            padding: 16px 30px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 15px;
        }

        .topbar-title { font-weight: 800; color: var(--navy); font-size: 17px; }

        .admin-profile { display: flex; align-items: center; gap: 10px; }

        .admin-avatar {
            width: 39px;
            height: 39px;
            border-radius: 50%;
            display: grid;
            place-items: center;
            color: var(--primary);
            background: #e9f0ff;
            font-weight: 800;
        }

        .content { padding: 28px 30px; }

        .page-heading {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 18px;
            margin-bottom: 25px;
            flex-wrap: wrap;
        }

        .page-heading h1 {
            font-size: 25px;
            font-weight: 800;
            color: var(--navy);
            letter-spacing: -.7px;
            margin-bottom: 7px;
        }

        .page-heading p { color: var(--muted); margin: 0; }

        .metric-card, .panel {
            background: white;
            border: 1px solid var(--border);
            border-radius: 15px;
            box-shadow: 0 4px 16px rgba(16, 35, 63, .025);
        }

        .metric-card { padding: 19px; height: 100%; }

        .metric-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
            margin-bottom: 15px;
        }

        .metric-label { color: var(--muted); font-size: 12px; font-weight: 600; }

        .metric-icon {
            width: 41px;
            height: 41px;
            display: grid;
            place-items: center;
            border-radius: 12px;
            font-size: 19px;
            flex-shrink: 0;
        }

        .metric-value {
            font-size: 27px;
            line-height: 1.1;
            color: var(--navy);
            font-weight: 800;
            margin-bottom: 6px;
        }

        .metric-note { font-size: 11px; color: var(--muted); }

        .panel { overflow: hidden; margin-top: 25px; }

        .panel-heading {
            padding: 20px 22px;
            border-bottom: 1px solid var(--border);
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 15px;
            flex-wrap: wrap;
        }

        .panel-heading h2 {
            color: var(--navy);
            font-size: 16px;
            font-weight: 800;
            margin: 0 0 5px;
        }

        .panel-heading p { margin: 0; color: var(--muted); font-size: 12px; }

        .filters {
            display: grid;
            grid-template-columns: minmax(190px, 1.5fr) minmax(160px, 1fr) auto;
            gap: 10px;
            padding: 18px 22px;
            border-bottom: 1px solid var(--border);
        }

        .form-control, .form-select {
            border-color: #dce4ef;
            border-radius: 8px;
            font-size: 12px;
            min-height: 40px;
        }

        .form-control:focus, .form-select:focus {
            border-color: #93b4ff;
            box-shadow: 0 0 0 .2rem rgba(37, 99, 235, .09);
        }

        .table { margin: 0; white-space: nowrap; }

        .table thead th {
            background: #f8fafd;
            color: #718096;
            font-size: 10px;
            font-weight: 800;
            letter-spacing: .5px;
            text-transform: uppercase;
            padding: 14px 16px;
            border-bottom: 1px solid var(--border);
        }

        .table tbody td {
            padding: 15px 16px;
            border-color: #edf1f6;
            vertical-align: middle;
            font-size: 12px;
        }

        .table tbody tr:hover { background: #fbfdff; }

        .customer-avatar {
            width: 40px;
            height: 40px;
            border-radius: 11px;
            display: grid;
            place-items: center;
            color: var(--primary);
            background: #eaf0ff;
            font-weight: 800;
            flex-shrink: 0;
        }

        .customer-name { color: var(--navy); font-weight: 700; font-size: 12px; }
        .subtext { color: var(--muted); font-size: 10px; margin-top: 5px; }

        .service-name { color: var(--navy); font-weight: 700; font-size: 12px; }
        .reference { color: var(--primary); font-size: 11px; font-weight: 800; }

        .status {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 6px 9px;
            border-radius: 20px;
            font-size: 9px;
            font-weight: 800;
        }

        .status::before {
            content: '';
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: currentColor;
        }

        .status-new { color: #9a6700; background: #fff3cd; }
        .status-review { color: #2459a9; background: #e7efff; }
        .status-sent { color: #6841a5; background: #f0e8ff; }
        .status-approved { color: #24704b; background: #e4f8ed; }
        .status-rejected { color: #b42318; background: #feeceb; }
        .status-cancelled { color: #687386; background: #edf0f5; }

        .action-btn {
            border: 1px solid #e0e7f0;
            width: 33px;
            height: 33px;
            border-radius: 8px;
            background: white;
            color: #64748b;
            display: inline-grid;
            place-items: center;
            transition: .2s;
        }

        .action-btn:hover { color: var(--primary); border-color: #b8ccff; background: #f5f8ff; }

        .empty-state { text-align: center; padding: 55px 20px; color: var(--muted); }
        .empty-state i { font-size: 37px; color: #aabbd3; display: block; margin-bottom: 12px; }
        .empty-state h3 { font-size: 16px; color: var(--navy); font-weight: 800; }

        .modal-content { border: 0; border-radius: 15px; overflow: hidden; }
        .modal-header { background: #f7f9fd; border-bottom: 1px solid var(--border); }
        .modal-title { color: var(--navy); font-weight: 800; font-size: 16px; }
        .form-label { font-size: 12px; color: #52637a; font-weight: 700; }

        .detail-box {
            border: 1px solid var(--border);
            background: #fbfcff;
            border-radius: 10px;
            padding: 13px;
            height: 100%;
        }

        .detail-label {
            font-size: 10px;
            font-weight: 800;
            color: var(--muted);
            text-transform: uppercase;
            letter-spacing: .5px;
            margin-bottom: 7px;
        }

        .detail-value {
            font-size: 12px;
            font-weight: 600;
            color: var(--navy);
            overflow-wrap: anywhere;
        }

        .page-footer {
            color: var(--muted);
            font-size: 11px;
            padding: 22px 30px;
            border-top: 1px solid var(--border);
            margin-top: 35px;
            display: flex;
            justify-content: space-between;
            gap: 10px;
            flex-wrap: wrap;
        }

        @media (max-width: 1200px) {
            .sidebar { width: 220px; }
            .main { margin-left: 220px; }
            .filters { grid-template-columns: 1fr 1fr; }
        }

        @media (max-width: 767px) {
            .sidebar {
                position: relative;
                width: 100%;
                padding: 15px;
            }

            .brand { margin-bottom: 14px; }
            .sidebar-label { margin-top: 12px; }
            .sidebar nav { display: grid; grid-template-columns: 1fr 1fr; gap: 4px; }
            .nav-link { margin: 0; padding: 10px; }
            .sidebar-bottom { margin-top: 15px; }
            .main { margin-left: 0; }
            .topbar { padding: 15px 18px; }
            .content { padding: 22px 15px; }
            .filters { grid-template-columns: 1fr; padding: 15px; }
            .panel-heading { padding: 17px 15px; }
            .page-heading h1 { font-size: 22px; }
            .page-footer { padding: 20px 15px; }
        }
    </style>
</head>
<body>

<aside class="sidebar">
    <a href="index.php" class="brand">
        <span class="brand-icon"><i class="bi bi-layers-half"></i></span>
        ProServe
    </a>

    <div class="sidebar-label">MAIN MENU</div>

    <nav>
        <a href="index.php" class="nav-link"><i class="bi bi-grid-1x2"></i> Dashboard</a>
        <a href="customers.php" class="nav-link"><i class="bi bi-people"></i> Customers</a>
        <a href="leads.php" class="nav-link"><i class="bi bi-funnel"></i> Lead Management</a>
        <a href="appointments.php" class="nav-link"><i class="bi bi-calendar-check"></i> Appointments</a>
        <a href="services.php" class="nav-link"><i class="bi bi-grid"></i> Services</a>
        <a href="quotations.php" class="nav-link active"><i class="bi bi-file-earmark-text"></i> Quotations</a>
        <a href="invoices.php" class="nav-link"><i class="bi bi-receipt"></i> Invoices</a>
        <a href="employees.php" class="nav-link"><i class="bi bi-person-workspace"></i> Employees</a>
        <a href="enquiries.php" class="nav-link"><i class="bi bi-chat-left-text"></i> Contact Enquiries</a>
        <a href="reports.php" class="nav-link"><i class="bi bi-bar-chart"></i> Reports</a>
        <a href="settings.php" class="nav-link"><i class="bi bi-gear"></i> Settings</a>
    </nav>

    <div class="sidebar-bottom">
        <a href="../index.php" class="nav-link"><i class="bi bi-box-arrow-up-right"></i> View Customer Site</a>
        <a href="../dashboard.php" class="nav-link"><i class="bi bi-person-circle"></i> Customer Dashboard</a>
    </div>
</aside>

<div class="main">
    <header class="topbar">
        <div>
            <div class="topbar-title">Quotation Management</div>
            <div class="text-secondary small mt-1">ProServe / Administrator / Quotations</div>
        </div>

        <div class="admin-profile">
            <div class="text-end d-none d-sm-block">
                <div class="fw-bold" style="font-size:12px;color:#10233f;">Administrator</div>
                <div class="text-secondary" style="font-size:10px;">System Manager</div>
            </div>
            <div class="admin-avatar">A</div>
        </div>
    </header>

    <main class="content">
        <div class="page-heading">
            <div>
                <h1>Quotation Management</h1>
                <p>Review project requests, prepare quotations, and manage customer decisions.</p>
            </div>
            <div>
                <button class="btn btn-outline-primary btn-sm px-3 py-2" onclick="window.print()">
                    <i class="bi bi-printer me-1"></i> Print Page
                </button>
            </div>
        </div>

        <?php if ($noticeMessage !== ''): ?>
            <?php
            $allowedAlertTypes = ['success', 'warning', 'danger', 'info'];
            if (!in_array($noticeType, $allowedAlertTypes, true)) {
                $noticeType = 'info';
            }
            ?>
            <div class="alert alert-<?= e($noticeType) ?> alert-dismissible fade show" role="alert">
                <i class="bi bi-info-circle me-2"></i><?= e($noticeMessage) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="row g-3">
            <div class="col-6 col-xl-3">
                <div class="metric-card">
                    <div class="metric-top">
                        <div class="metric-label">Total Quotations</div>
                        <div class="metric-icon" style="background:#eaf0ff;color:#2563eb;"><i class="bi bi-file-earmark-text"></i></div>
                    </div>
                    <div class="metric-value"><?= $totalCount ?></div>
                    <div class="metric-note">All quotation requests</div>
                </div>
            </div>

            <div class="col-6 col-xl-3">
                <div class="metric-card">
                    <div class="metric-top">
                        <div class="metric-label">New Requests</div>
                        <div class="metric-icon" style="background:#fff3cd;color:#9a6700;"><i class="bi bi-inbox"></i></div>
                    </div>
                    <div class="metric-value"><?= $newCount ?></div>
                    <div class="metric-note">Awaiting initial review</div>
                </div>
            </div>

            <div class="col-6 col-xl-3">
                <div class="metric-card">
                    <div class="metric-top">
                        <div class="metric-label">Quotations Sent</div>
                        <div class="metric-icon" style="background:#f0e8ff;color:#6841a5;"><i class="bi bi-send"></i></div>
                    </div>
                    <div class="metric-value"><?= $sentCount ?></div>
                    <div class="metric-note">Waiting for customer decisions</div>
                </div>
            </div>

            <div class="col-6 col-xl-3">
                <div class="metric-card">
                    <div class="metric-top">
                        <div class="metric-label">Approved Quotations</div>
                        <div class="metric-icon" style="background:#e1f8eb;color:#238453;"><i class="bi bi-check-circle"></i></div>
                    </div>
                    <div class="metric-value"><?= $approvedCount ?></div>
                    <div class="metric-note">Approved by customers in the demo</div>
                </div>
            </div>
        </div>

        <div class="row g-3 mt-1">
            <div class="col-md-6">
                <div class="metric-card">
                    <div class="metric-top">
                        <div class="metric-label">Total Quoted Value</div>
                        <div class="metric-icon" style="background:#e5f6ff;color:#0878a5;"><i class="bi bi-currency-exchange"></i></div>
                    </div>
                    <div class="metric-value" style="font-size:23px;"><?= e(moneyLKR($totalQuotedValue)) ?></div>
                    <div class="metric-note">Sum of the amounts currently recorded on quotations</div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="metric-card">
                    <div class="metric-top">
                        <div class="metric-label">Under Review</div>
                        <div class="metric-icon" style="background:#eaf0ff;color:#2563eb;"><i class="bi bi-hourglass-split"></i></div>
                    </div>
                    <div class="metric-value"><?= $reviewCount ?></div>
                    <div class="metric-note">Requests being assessed by the administrator</div>
                </div>
            </div>
        </div>

        <section class="panel">
            <div class="panel-heading">
                <div>
                    <h2><i class="bi bi-clipboard-data me-2" style="color:#2563eb;"></i>Customer Quotations</h2>
                    <p>Open a quotation to review requirements, update pricing, and change its status.</p>
                </div>
                <span class="badge rounded-pill text-bg-light border px-3 py-2">
                    <?= count($filteredQuotations) ?> result(s)
                </span>
            </div>

            <form method="GET" action="quotations.php" class="filters">
                <input type="search" name="search" class="form-control"
                       placeholder="Search reference, customer, company, service..."
                       value="<?= e($search) ?>">

                <select name="status" class="form-select" aria-label="Filter by quotation status">
                    <option value="ALL" <?= $statusFilter === 'ALL' ? 'selected' : '' ?>>All statuses</option>
                    <option value="NEW" <?= $statusFilter === 'NEW' ? 'selected' : '' ?>>New</option>
                    <option value="PENDING" <?= $statusFilter === 'PENDING' ? 'selected' : '' ?>>Pending</option>
                    <option value="UNDER REVIEW" <?= $statusFilter === 'UNDER REVIEW' ? 'selected' : '' ?>>Under Review</option>
                    <option value="QUOTE SENT" <?= $statusFilter === 'QUOTE SENT' ? 'selected' : '' ?>>Quote Sent</option>
                    <option value="APPROVED" <?= $statusFilter === 'APPROVED' ? 'selected' : '' ?>>Approved</option>
                    <option value="REJECTED" <?= $statusFilter === 'REJECTED' ? 'selected' : '' ?>>Rejected</option>
                    <option value="CANCELLED" <?= $statusFilter === 'CANCELLED' ? 'selected' : '' ?>>Cancelled</option>
                </select>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary btn-sm px-3">
                        <i class="bi bi-search me-1"></i> Filter
                    </button>
                    <a href="quotations.php" class="btn btn-light border btn-sm px-3">Reset</a>
                </div>
            </form>

            <?php if (count($filteredQuotations) > 0): ?>
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                        <tr>
                            <th>Quotation</th>
                            <th>Customer</th>
                            <th>Requested Service</th>
                            <th>Estimated Amount</th>
                            <th>Request Date</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($filteredQuotations as $quotation): ?>
                            <?php
                            $status = strtoupper((string)($quotation['status'] ?? 'NEW'));
                            $reference = (string)$quotation['_display_reference'];
                            $customerName = (string)$quotation['_display_name'];
                            $serviceName = (string)$quotation['_display_service'];
                            $amount = (float)$quotation['_display_amount'];
                            $createdDate = (string)$quotation['_display_date'];

                            $modalData = [
                                'reference' => $reference,
                                'name' => $customerName,
                                'email' => (string)($quotation['email'] ?? ''),
                                'phone' => (string)($quotation['phone'] ?? ''),
                                'company' => (string)($quotation['company'] ?? ''),
                                'service' => $serviceName,
                                'description' => (string)(
                                    $quotation['description']
                                    ?? $quotation['project_description']
                                    ?? $quotation['requirements']
                                    ?? ''
                                ),
                                'status' => $status,
                                'quoted_amount' => $amount,
                                'admin_notes' => (string)($quotation['admin_notes'] ?? ''),
                                'created_at' => $createdDate
                            ];
                            ?>
                            <tr>
                                <td>
                                    <div class="reference"><?= e($reference) ?></div>
                                    <div class="subtext">Quotation request</div>
                                </td>

                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="customer-avatar">
                                            <?= e(strtoupper(substr($customerName, 0, 1))) ?>
                                        </div>
                                        <div>
                                            <div class="customer-name"><?= e($customerName) ?></div>
                                            <div class="subtext"><?= e($quotation['company'] ?? 'Individual customer') ?></div>
                                        </div>
                                    </div>
                                </td>

                                <td>
                                    <div class="service-name"><?= e($serviceName) ?></div>
                                    <div class="subtext"><?= e($quotation['email'] ?? '') ?></div>
                                </td>

                                <td>
                                    <div class="fw-bold" style="color:#10233f;">
                                        <?= e(moneyLKR($amount)) ?>
                                    </div>
                                </td>

                                <td>
                                    <?= e($createdDate !== '' ? date('d M Y', strtotime($createdDate)) : '—') ?>
                                    <div class="subtext">
                                        <?= e($createdDate !== '' ? date('h:i A', strtotime($createdDate)) : '') ?>
                                    </div>
                                </td>

                                <td>
                                    <span class="status <?= e(quotationStatusClass($status)) ?>">
                                        <?= e(ucwords(strtolower($status))) ?>
                                    </span>
                                </td>

                                <td class="text-end">
                                    <button type="button" class="action-btn"
                                            title="View and manage quotation"
                                            data-bs-toggle="modal"
                                            data-bs-target="#quotationModal"
                                            data-quotation="<?= e(json_encode($modalData, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP)) ?>">
                                        <i class="bi bi-eye"></i>
                                    </button>

                                    <?php if ($status === 'APPROVED'): ?>
                                        <form method="POST" action="quotations.php" class="d-inline">
                                            <input type="hidden" name="csrf_token" value="<?= e($_SESSION['proserve_quotations_csrf']) ?>">
                                            <input type="hidden" name="action" value="invoice_draft">
                                            <input type="hidden" name="reference" value="<?= e($reference) ?>">
                                            <button type="submit" class="action-btn"
                                                    title="Create invoice draft"
                                                    onclick="return confirm('Create an invoice draft for this approved quotation?');">
                                                <i class="bi bi-receipt-cutoff"></i>
                                            </button>
                                        </form>
                                    <?php endif; ?>

                                    <form method="POST" action="quotations.php" class="d-inline">
                                        <input type="hidden" name="csrf_token" value="<?= e($_SESSION['proserve_quotations_csrf']) ?>">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="reference" value="<?= e($reference) ?>">
                                        <button type="submit" class="action-btn text-danger"
                                                title="Delete quotation"
                                                onclick="return confirm('Delete this quotation from the demonstration records?');">
                                            <i class="bi bi-trash3"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="empty-state">
                    <i class="bi bi-file-earmark-x"></i>
                    <h3>No quotations found</h3>
                    <p class="mb-3">Try another search term or status filter.</p>
                    <a href="quotations.php" class="btn btn-outline-primary btn-sm">Clear filters</a>
                </div>
            <?php endif; ?>
        </section>

        <div class="alert alert-light border mt-4 mb-0 small">
            <i class="bi bi-info-circle text-primary me-2"></i>
            <strong>Demo note:</strong> Quotation status changes and invoice drafts are stored in PHP sessions.
            This page does not send real emails, create a finalized invoice, or process payments.
        </div>
    </main>

    <footer class="page-footer">
        <span>© <?= date('Y') ?> ProServe — CRM &amp; Service Management System</span>
        <span>Portfolio demonstration by <strong>Codvanta</strong></span>
    </footer>
</div>

<!-- Quotation details and editing modal -->
<div class="modal fade" id="quotationModal" tabindex="-1" aria-labelledby="quotationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <form method="POST" action="quotations.php" id="quotationForm">
                <div class="modal-header px-4 py-3">
                    <div>
                        <h2 class="modal-title" id="quotationModalLabel">Quotation Details</h2>
                        <div class="small text-secondary mt-1" id="modalReference">Review customer requirements</div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body p-4">
                    <input type="hidden" name="csrf_token" value="<?= e($_SESSION['proserve_quotations_csrf']) ?>">
                    <input type="hidden" name="action" value="update">
                    <input type="hidden" name="reference" id="quotationReference">

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <div class="detail-box">
                                <div class="detail-label">Customer</div>
                                <div class="detail-value" id="detailName">—</div>
                                <div class="subtext" id="detailCompany"></div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="detail-box">
                                <div class="detail-label">Requested Service</div>
                                <div class="detail-value" id="detailService">—</div>
                                <div class="subtext" id="detailDate"></div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="detail-box">
                                <div class="detail-label">Email Address</div>
                                <div class="detail-value" id="detailEmail">—</div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="detail-box">
                                <div class="detail-label">Contact Number</div>
                                <div class="detail-value" id="detailPhone">—</div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="detail-box">
                                <div class="detail-label">Project Requirements</div>
                                <div class="detail-value" id="detailDescription"
                                     style="white-space:pre-wrap;line-height:1.8;font-weight:400;">—</div>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <h3 class="h6 fw-bold mb-3" style="color:#10233f;">Manage Quotation</h3>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="quotedAmount" class="form-label">Quotation Amount (LKR) *</label>
                            <input type="number" class="form-control" id="quotedAmount"
                                   name="quoted_amount" min="0" max="999999999999"
                                   step="0.01" required>
                        </div>

                        <div class="col-md-6">
                            <label for="quotationStatus" class="form-label">Quotation Status *</label>
                            <select class="form-select" id="quotationStatus" name="status" required>
                                <option value="NEW">New</option>
                                <option value="PENDING">Pending</option>
                                <option value="UNDER REVIEW">Under Review</option>
                                <option value="QUOTE SENT">Quote Sent</option>
                                <option value="APPROVED">Approved</option>
                                <option value="REJECTED">Rejected</option>
                                <option value="CANCELLED">Cancelled</option>
                            </select>
                        </div>

                        <div class="col-12">
                            <label for="adminNotes" class="form-label">Administrator Notes</label>
                            <textarea class="form-control" id="adminNotes" name="admin_notes"
                                      rows="4" maxlength="2000"
                                      placeholder="Record internal notes, quotation details, or follow-up actions..."></textarea>
                        </div>
                    </div>

                    <div class="alert alert-info small mt-4 mb-0">
                        <i class="bi bi-info-circle me-1"></i>
                        Mark a quotation as <strong>Quote Sent</strong> after preparing the offer.
                        Use <strong>Approved</strong> to demonstrate the next step toward invoice creation.
                    </div>
                </div>

                <div class="modal-footer px-4">
                    <button type="button" class="btn btn-light border btn-sm px-3" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary btn-sm px-3">
                        <i class="bi bi-check2-circle me-1"></i> Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('quotationModal');

    modal.addEventListener('show.bs.modal', function (event) {
        const trigger = event.relatedTarget;

        if (!trigger) return;

        let quotation;

        try {
            quotation = JSON.parse(trigger.getAttribute('data-quotation') || '{}');
        } catch (error) {
            return;
        }

        const setText = function (id, value) {
            document.getElementById(id).textContent = value || '—';
        };

        document.getElementById('quotationReference').value = quotation.reference || '';
        document.getElementById('modalReference').textContent = quotation.reference || 'Quotation';

        setText('detailName', quotation.name);
        setText('detailCompany', quotation.company);
        setText('detailService', quotation.service);
        setText('detailEmail', quotation.email);
        setText('detailPhone', quotation.phone);
        setText('detailDescription', quotation.description);
        setText('detailDate', quotation.created_at);

        document.getElementById('quotedAmount').value = quotation.quoted_amount ?? 0;
        document.getElementById('quotationStatus').value = quotation.status || 'NEW';
        document.getElementById('adminNotes').value = quotation.admin_notes || '';
    });
});
</script>
</body>
</html>
