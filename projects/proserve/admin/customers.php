
<?php
session_start();
date_default_timezone_set('Asia/Colombo');

function e($value): string
{
    return htmlspecialchars((string)($value ?? ''), ENT_QUOTES, 'UTF-8');
}

function customerStatusClass(string $status): string
{
    return match (strtoupper($status)) {
        'ACTIVE' => 'status-active',
        'INACTIVE' => 'status-inactive',
        default => 'status-pending',
    };
}

/*
|--------------------------------------------------------------------------
| Demo administrator access
|--------------------------------------------------------------------------
| Replace this demonstration check with real administrator authentication
| before deploying the application publicly.
*/

if (!isset($_SESSION['proserve_admin_logged_in'])) {
    $_SESSION['proserve_admin_logged_in'] = true;
}

if (!isset($_SESSION['proserve_customers'])) {
    $_SESSION['proserve_customers'] = [
        [
            'id' => 1,
            'name' => 'Daniel Fernando',
            'email' => 'daniel@example.com',
            'company' => 'DF Solutions',
            'phone' => '+94 77 123 4567',
            'country' => 'Sri Lanka',
            'status' => 'ACTIVE',
            'created_at' => date('Y-m-d H:i:s', strtotime('-12 days')),
        ],
        [
            'id' => 2,
            'name' => 'Sarah Perera',
            'email' => 'sarah@example.com',
            'company' => 'Perera Enterprises',
            'phone' => '+94 71 234 5678',
            'country' => 'Sri Lanka',
            'status' => 'ACTIVE',
            'created_at' => date('Y-m-d H:i:s', strtotime('-9 days')),
        ],
        [
            'id' => 3,
            'name' => 'Michael Silva',
            'email' => 'michael@example.com',
            'company' => 'Silva Holdings',
            'phone' => '+94 76 345 6789',
            'country' => 'Sri Lanka',
            'status' => 'INACTIVE',
            'created_at' => date('Y-m-d H:i:s', strtotime('-6 days')),
        ],
        [
            'id' => 4,
            'name' => 'Amelia Jayawardena',
            'email' => 'amelia@example.com',
            'company' => 'AJ Creative',
            'phone' => '+94 72 456 7890',
            'country' => 'Sri Lanka',
            'status' => 'ACTIVE',
            'created_at' => date('Y-m-d H:i:s', strtotime('-3 days')),
        ],
    ];
}

$customers = &$_SESSION['proserve_customers'];

if (!isset($_SESSION['proserve_customers_csrf'])) {
    $_SESSION['proserve_customers_csrf'] = bin2hex(random_bytes(32));
}

$csrfToken = $_SESSION['proserve_customers_csrf'];
$adminName = $_SESSION['proserve_admin_name'] ?? 'Administrator';

$message = '';
$messageType = 'success';
$editingCustomer = null;

function nextCustomerId(array $customers): int
{
    $ids = array_map(
        fn($customer) => (int)($customer['id'] ?? 0),
        $customers
    );

    return $ids ? max($ids) + 1 : 1;
}

/*
|--------------------------------------------------------------------------
| Handle customer actions
|--------------------------------------------------------------------------
*/

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $postedToken = $_POST['csrf_token'] ?? '';

    if (!is_string($postedToken) ||
        !hash_equals($csrfToken, $postedToken)) {
        $message = 'Your session token is invalid. Please refresh the page and try again.';
        $messageType = 'danger';
    } else {
        $action = $_POST['action'] ?? '';

        if ($action === 'save') {
            $id = filter_var($_POST['id'] ?? '', FILTER_VALIDATE_INT);
            $name = trim((string)($_POST['name'] ?? ''));
            $email = strtolower(trim((string)($_POST['email'] ?? '')));
            $phone = trim((string)($_POST['phone'] ?? ''));
            $company = trim((string)($_POST['company'] ?? ''));
            $country = trim((string)($_POST['country'] ?? ''));
            $status = strtoupper(trim((string)($_POST['status'] ?? 'ACTIVE')));

            if ($name === '' || mb_strlen($name) > 100) {
                $message = 'Enter a customer name with no more than 100 characters.';
                $messageType = 'danger';
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL) ||
                strlen($email) > 190) {
                $message = 'Enter a valid email address.';
                $messageType = 'danger';
            } elseif (strlen($phone) > 40 ||
                strlen($company) > 150 ||
                strlen($country) > 100) {
                $message = 'One or more fields exceed the allowed length.';
                $messageType = 'danger';
            } elseif (!in_array($status, ['ACTIVE', 'INACTIVE'], true)) {
                $message = 'Select a valid customer status.';
                $messageType = 'danger';
            } else {
                $duplicate = false;

                foreach ($customers as $existing) {
                    if (
                        strtolower((string)($existing['email'] ?? '')) === $email &&
                        (int)($existing['id'] ?? 0) !== (int)($id ?: 0)
                    ) {
                        $duplicate = true;
                        break;
                    }
                }

                if ($duplicate) {
                    $message = 'Another customer already uses this email address.';
                    $messageType = 'danger';
                } elseif ($id) {
                    $found = false;

                    foreach ($customers as &$customer) {
                        if ((int)($customer['id'] ?? 0) === (int)$id) {
                            $customer['name'] = $name;
                            $customer['email'] = $email;
                            $customer['phone'] = $phone;
                            $customer['company'] = $company;
                            $customer['country'] = $country;
                            $customer['status'] = $status;
                            $customer['updated_at'] = date('Y-m-d H:i:s');
                            $found = true;
                            break;
                        }
                    }
                    unset($customer);

                    if ($found) {
                        $message = 'Customer information updated successfully.';
                    } else {
                        $message = 'The selected customer could not be found.';
                        $messageType = 'danger';
                    }
                } else {
                    $customers[] = [
                        'id' => nextCustomerId($customers),
                        'name' => $name,
                        'email' => $email,
                        'phone' => $phone,
                        'company' => $company,
                        'country' => $country,
                        'status' => $status,
                        'created_at' => date('Y-m-d H:i:s'),
                    ];

                    $message = 'New customer added successfully.';
                }
            }
        } elseif ($action === 'toggle_status') {
            $id = filter_var($_POST['id'] ?? '', FILTER_VALIDATE_INT);
            $found = false;

            foreach ($customers as &$customer) {
                if ((int)($customer['id'] ?? 0) === (int)($id ?: 0)) {
                    $current = strtoupper($customer['status'] ?? 'ACTIVE');
                    $customer['status'] = $current === 'ACTIVE'
                        ? 'INACTIVE'
                        : 'ACTIVE';

                    $customer['updated_at'] = date('Y-m-d H:i:s');
                    $found = true;
                    break;
                }
            }
            unset($customer);

            if ($found) {
                $message = 'Customer status updated successfully.';
            } else {
                $message = 'Customer record not found.';
                $messageType = 'danger';
            }
        } elseif ($action === 'delete') {
            $id = filter_var($_POST['id'] ?? '', FILTER_VALIDATE_INT);
            $found = false;

            foreach ($customers as $index => $customer) {
                if ((int)($customer['id'] ?? 0) === (int)($id ?: 0)) {
                    array_splice($customers, $index, 1);
                    $found = true;
                    break;
                }
            }

            if ($found) {
                $message = 'Customer record deleted from the demonstration.';
            } else {
                $message = 'Customer record not found.';
                $messageType = 'danger';
            }
        }
    }

    /*
     * Redirect after POST to prevent accidental repeat submissions.
     */
    $_SESSION['proserve_customer_flash'] = [
        'message' => $message,
        'type' => $messageType,
    ];

    header('Location: customers.php');
    exit;
}

/*
|--------------------------------------------------------------------------
| Retrieve one-time action feedback
|--------------------------------------------------------------------------
*/

if (isset($_SESSION['proserve_customer_flash'])) {
    $flash = $_SESSION['proserve_customer_flash'];
    $message = $flash['message'] ?? '';
    $messageType = $flash['type'] ?? 'success';
    unset($_SESSION['proserve_customer_flash']);
}

/*
|--------------------------------------------------------------------------
| Edit customer selection
|--------------------------------------------------------------------------
*/

$editId = filter_input(INPUT_GET, 'edit', FILTER_VALIDATE_INT);

if ($editId) {
    foreach ($customers as $customer) {
        if ((int)($customer['id'] ?? 0) === $editId) {
            $editingCustomer = $customer;
            break;
        }
    }

    if (!$editingCustomer) {
        $message = 'The selected customer could not be found.';
        $messageType = 'warning';
    }
}

/*
|--------------------------------------------------------------------------
| Search and filtering
|--------------------------------------------------------------------------
*/

$search = trim((string)($_GET['search'] ?? ''));
$statusFilter = strtoupper(trim((string)($_GET['status'] ?? 'ALL')));

if (!in_array($statusFilter, ['ALL', 'ACTIVE', 'INACTIVE'], true)) {
    $statusFilter = 'ALL';
}

$filteredCustomers = array_values(array_filter(
    $customers,
    function ($customer) use ($search, $statusFilter) {
        $customerStatus = strtoupper($customer['status'] ?? 'ACTIVE');

        if ($statusFilter !== 'ALL' && $customerStatus !== $statusFilter) {
            return false;
        }

        if ($search !== '') {
            $haystack = implode(' ', [
                $customer['name'] ?? '',
                $customer['email'] ?? '',
                $customer['company'] ?? '',
                $customer['phone'] ?? '',
                $customer['country'] ?? '',
            ]);

            if (stripos($haystack, $search) === false) {
                return false;
            }
        }

        return true;
    }
));

usort($filteredCustomers, function ($a, $b) {
    return (int)($b['id'] ?? 0) <=> (int)($a['id'] ?? 0);
});

$totalCustomers = count($customers);
$activeCustomers = 0;
$inactiveCustomers = 0;

foreach ($customers as $customer) {
    if (strtoupper($customer['status'] ?? 'ACTIVE') === 'ACTIVE') {
        $activeCustomers++;
    } else {
        $inactiveCustomers++;
    }
}

$showForm = $editingCustomer !== null ||
    (isset($_GET['add']) && $_GET['add'] === '1');

$formCustomer = $editingCustomer ?? [
    'id' => '',
    'name' => '',
    'email' => '',
    'phone' => '',
    'company' => '',
    'country' => 'Sri Lanka',
    'status' => 'ACTIVE',
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Customer Management | ProServe Admin</title>

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
            --muted: #718096;
            --border: #e7edf5;
            --background: #f5f8fc;
            --sidebar-width: 255px;
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            background: var(--background);
            color: #26364a;
            font-family: 'Inter', 'Trebuchet MS', sans-serif;
            font-size: 13px;
        }

        a { text-decoration: none; }

        .sidebar {
            position: fixed;
            inset: 0 auto 0 0;
            z-index: 1040;
            display: flex;
            flex-direction: column;
            width: var(--sidebar-width);
            padding: 24px 15px 16px;
            background: #fff;
            border-right: 1px solid var(--border);
            overflow-y: auto;
            transition: transform .25s ease;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 0 10px 27px;
            color: var(--navy);
            font-size: 22px;
            font-weight: 800;
            letter-spacing: -.8px;
        }

        .brand-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 39px;
            height: 39px;
            border-radius: 12px;
            background: var(--primary);
            color: white;
            font-size: 20px;
        }

        .brand span { color: var(--primary); }

        .sidebar-label {
            margin: 15px 12px 9px;
            color: #98a5b7;
            font-size: 10px;
            font-weight: 800;
            letter-spacing: 1.1px;
            text-transform: uppercase;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 12px;
            margin: 2px 0;
            padding: 11px 12px;
            border-radius: 9px;
            color: #607086;
            font-size: 12px;
            font-weight: 600;
            transition: .2s;
        }

        .sidebar-link i {
            width: 19px;
            font-size: 16px;
            text-align: center;
        }

        .sidebar-link:hover {
            background: #f1f5ff;
            color: var(--primary);
        }

        .sidebar-link.active {
            background: #eaf1ff;
            color: var(--primary);
            font-weight: 800;
        }

        .sidebar-bottom {
            margin-top: auto;
            padding-top: 20px;
        }

        .admin-profile {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 14px 9px 0;
            border-top: 1px solid var(--border);
        }

        .avatar {
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            width: 37px;
            height: 37px;
            border-radius: 50%;
            background: #e8efff;
            color: var(--primary);
            font-weight: 800;
        }

        .admin-name {
            color: var(--navy);
            font-size: 12px;
            font-weight: 800;
        }

        .admin-role {
            margin-top: 3px;
            color: var(--muted);
            font-size: 10px;
        }

        .main-area { min-height: 100vh; margin-left: var(--sidebar-width); }

        .topbar {
            position: sticky;
            top: 0;
            z-index: 1020;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            min-height: 76px;
            padding: 14px 30px;
            background: rgba(255,255,255,.96);
            border-bottom: 1px solid var(--border);
            backdrop-filter: blur(10px);
        }

        .topbar-title {
            color: var(--navy);
            font-size: 18px;
            font-weight: 800;
        }

        .topbar-subtitle {
            margin-top: 4px;
            color: var(--muted);
            font-size: 11px;
        }

        .topbar-date {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 9px 12px;
            border: 1px solid var(--border);
            border-radius: 9px;
            background: #fff;
            color: #536176;
            font-size: 11px;
            font-weight: 600;
        }

        .page-content {
            max-width: 1600px;
            margin: 0 auto;
            padding: 27px 30px 35px;
        }

        .page-title {
            color: var(--navy);
            font-size: 25px;
            font-weight: 800;
            letter-spacing: -.8px;
        }

        .muted { color: var(--muted); }

        .btn-primary {
            background: var(--primary);
            border-color: var(--primary);
            font-weight: 600;
        }

        .btn-primary:hover {
            background: var(--primary-dark);
            border-color: var(--primary-dark);
        }

        .metric-card {
            height: 100%;
            padding: 20px;
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 14px;
            box-shadow: 0 4px 18px rgba(16,35,63,.025);
        }

        .metric-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
        }

        .metric-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 42px;
            height: 42px;
            border-radius: 12px;
            font-size: 19px;
        }

        .icon-blue { background: #eaf1ff; color: #2563eb; }
        .icon-green { background: #e6f8ef; color: #16985a; }
        .icon-orange { background: #fff2df; color: #ca7a15; }

        .metric-label {
            margin-top: 16px;
            color: var(--muted);
            font-size: 11px;
            font-weight: 600;
        }

        .metric-value {
            margin-top: 5px;
            color: var(--navy);
            font-size: 27px;
            font-weight: 800;
        }

        .metric-note {
            margin-top: 5px;
            color: var(--muted);
            font-size: 10px;
        }

        .panel {
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 14px;
            box-shadow: 0 4px 18px rgba(16,35,63,.025);
        }

        .panel-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 19px 21px;
            border-bottom: 1px solid var(--border);
        }

        .panel-title {
            margin: 0;
            color: var(--navy);
            font-size: 14px;
            font-weight: 800;
        }

        .panel-subtitle {
            margin-top: 5px;
            color: var(--muted);
            font-size: 10px;
        }

        .panel-body { padding: 21px; }

        .table {
            margin-bottom: 0;
            vertical-align: middle;
        }

        .table thead th {
            padding: 14px 17px;
            background: #f8faff;
            color: #8290a4;
            font-size: 9px;
            font-weight: 800;
            letter-spacing: .6px;
            text-transform: uppercase;
            white-space: nowrap;
            border-bottom: 1px solid var(--border);
        }

        .table tbody td {
            padding: 15px 17px;
            border-bottom: 1px solid #edf1f7;
            font-size: 11px;
        }

        .table tbody tr:last-child td { border-bottom: 0; }

        .customer-avatar {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 39px;
            height: 39px;
            flex-shrink: 0;
            border-radius: 12px;
            background: #eaf1ff;
            color: var(--primary);
            font-weight: 800;
        }

        .customer-name {
            color: var(--navy);
            font-size: 11px;
            font-weight: 800;
        }

        .customer-sub {
            margin-top: 4px;
            color: var(--muted);
            font-size: 10px;
        }

        .status-badge {
            display: inline-block;
            padding: 6px 9px;
            border-radius: 30px;
            font-size: 9px;
            font-weight: 800;
            white-space: nowrap;
        }

        .status-active { background: #e5f8ed; color: #168044; }
        .status-inactive { background: #edf1f5; color: #5f6b7a; }
        .status-pending { background: #fff3dc; color: #a16207; }

        .action-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 31px;
            height: 31px;
            border: 1px solid var(--border);
            border-radius: 8px;
            background: white;
            color: #65748a;
        }

        .action-btn:hover {
            background: #f1f5ff;
            color: var(--primary);
            border-color: #c9d9ff;
        }

        .action-btn.delete:hover {
            background: #fff0f0;
            color: #dc3545;
            border-color: #ffd0d0;
        }

        .form-label {
            color: #435269;
            font-size: 11px;
            font-weight: 700;
        }

        .form-control,
        .form-select {
            min-height: 42px;
            border-color: #dfe6ef;
            border-radius: 9px;
            font-size: 12px;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #8db0ff;
            box-shadow: 0 0 0 .2rem rgba(37,99,235,.1);
        }

        .filter-panel {
            padding: 17px 20px;
            border-bottom: 1px solid var(--border);
        }

        .footer {
            padding: 20px 30px;
            border-top: 1px solid var(--border);
            background: #fff;
            color: var(--muted);
            font-size: 10px;
        }

        .mobile-menu-button {
            display: none;
            width: 38px;
            height: 38px;
            border: 1px solid var(--border);
            border-radius: 9px;
            background: white;
            color: var(--navy);
        }

        .sidebar-backdrop { display: none; }

        @media (max-width: 1199px) {
            :root { --sidebar-width: 230px; }
            .page-content { padding: 23px 22px 30px; }
            .topbar { padding: 13px 22px; }
        }

        @media (max-width: 991px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.open { transform: translateX(0); }
            .main-area { margin-left: 0; }

            .mobile-menu-button {
                display: inline-flex;
                align-items: center;
                justify-content: center;
            }

            .sidebar-backdrop.show {
                display: block;
                position: fixed;
                inset: 0;
                z-index: 1030;
                background: rgba(16,35,63,.35);
            }
        }

        @media (max-width: 575px) {
            .topbar { padding: 12px 15px; }
            .page-content { padding: 20px 14px 28px; }
            .page-title { font-size: 22px; }
            .topbar-date { display: none; }
            .metric-card { padding: 15px; }
            .metric-value { font-size: 23px; }
            .panel-header, .panel-body { padding: 16px; }
            .footer { padding: 18px 15px; }
        }
    </style>
</head>

<body>

<div class="sidebar-backdrop" id="sidebarBackdrop"></div>

<aside class="sidebar" id="sidebar">
    <a href="index.php" class="brand">
        <span class="brand-icon"><i class="bi bi-grid-1x2-fill"></i></span>
        <span style="color:#10233f">Pro<span>Serve</span></span>
    </a>

    <div class="sidebar-label">Main Menu</div>

    <a href="index.php" class="sidebar-link">
        <i class="bi bi-speedometer2"></i> Dashboard
    </a>

    <a href="customers.php" class="sidebar-link active">
        <i class="bi bi-people"></i> Customers
    </a>

    <a href="leads.php" class="sidebar-link">
        <i class="bi bi-funnel"></i> Lead Management
    </a>

    <a href="appointments.php" class="sidebar-link">
        <i class="bi bi-calendar-check"></i> Appointments
    </a>

    <div class="sidebar-label">Business Management</div>

    <a href="services.php" class="sidebar-link">
        <i class="bi bi-grid"></i> Services
    </a>

    <a href="quotations.php" class="sidebar-link">
        <i class="bi bi-file-earmark-text"></i> Quotations
    </a>

    <a href="invoices.php" class="sidebar-link">
        <i class="bi bi-receipt"></i> Invoices
    </a>

    <a href="employees.php" class="sidebar-link">
        <i class="bi bi-person-workspace"></i> Employees
    </a>

    <div class="sidebar-label">Analytics & Support</div>

    <a href="reports.php" class="sidebar-link">
        <i class="bi bi-bar-chart-line"></i> Reports
    </a>

    <a href="enquiries.php" class="sidebar-link">
        <i class="bi bi-envelope"></i> Contact Enquiries
    </a>

    <a href="settings.php" class="sidebar-link">
        <i class="bi bi-gear"></i> Settings
    </a>

    <div class="sidebar-bottom">
        <a href="../index.php" class="sidebar-link">
            <i class="bi bi-globe2"></i> View Customer Website
        </a>

        <a href="../logout.php" class="sidebar-link">
            <i class="bi bi-box-arrow-right"></i> Logout
        </a>

        <div class="admin-profile">
            <div class="avatar"><?= e(strtoupper(substr($adminName, 0, 1))) ?></div>
            <div>
                <div class="admin-name"><?= e($adminName) ?></div>
                <div class="admin-role">System Administrator</div>
            </div>
        </div>
    </div>
</aside>

<div class="main-area">

    <header class="topbar">
        <div class="d-flex align-items-center gap-3">
            <button
                type="button"
                class="mobile-menu-button"
                id="mobileMenuButton"
                aria-label="Open navigation"
            >
                <i class="bi bi-list fs-5"></i>
            </button>

            <div>
                <div class="topbar-title">Customer Management</div>
                <div class="topbar-subtitle">
                    Search, view, and manage your customer records.
                </div>
            </div>
        </div>

        <div class="d-flex align-items-center gap-3">
            <div class="topbar-date">
                <i class="bi bi-calendar3 text-primary"></i>
                <?= date('D, d M Y') ?>
            </div>
            <div class="avatar"><?= e(strtoupper(substr($adminName, 0, 1))) ?></div>
        </div>
    </header>

    <main class="page-content">

        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
            <div>
                <h1 class="page-title mb-2">Customers</h1>
                <p class="muted mb-0">
                    Manage customer profiles, contact details, and account status.
                </p>
            </div>

            <a href="customers.php?add=1" class="btn btn-primary px-3 py-2">
                <i class="bi bi-person-plus me-2"></i>
                Add Customer
            </a>
        </div>

        <?php if ($message !== ''): ?>
            <div
                class="alert alert-<?= e($messageType) ?> alert-dismissible fade show border-0 shadow-sm"
                role="alert"
            >
                <i class="bi bi-info-circle me-2"></i>
                <?= e($message) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- Summary cards -->
        <section class="row g-3 mb-4">

            <div class="col-6 col-xl-4">
                <div class="metric-card">
                    <div class="metric-top">
                        <div class="metric-icon icon-blue">
                            <i class="bi bi-people"></i>
                        </div>
                        <i class="bi bi-graph-up-arrow text-primary"></i>
                    </div>

                    <div class="metric-label">Total Customers</div>
                    <div class="metric-value"><?= number_format($totalCustomers) ?></div>
                    <div class="metric-note">All customer records</div>
                </div>
            </div>

            <div class="col-6 col-xl-4">
                <div class="metric-card">
                    <div class="metric-top">
                        <div class="metric-icon icon-green">
                            <i class="bi bi-person-check"></i>
                        </div>
                        <span class="status-badge status-active">Active</span>
                    </div>

                    <div class="metric-label">Active Customers</div>
                    <div class="metric-value"><?= number_format($activeCustomers) ?></div>
                    <div class="metric-note">Accounts currently active</div>
                </div>
            </div>

            <div class="col-6 col-xl-4">
                <div class="metric-card">
                    <div class="metric-top">
                        <div class="metric-icon icon-orange">
                            <i class="bi bi-person-dash"></i>
                        </div>
                        <span class="status-badge status-inactive">Inactive</span>
                    </div>

                    <div class="metric-label">Inactive Customers</div>
                    <div class="metric-value"><?= number_format($inactiveCustomers) ?></div>
                    <div class="metric-note">Accounts not currently active</div>
                </div>
            </div>

        </section>

        <!-- Customer form -->
        <?php if ($showForm): ?>
            <section class="panel mb-4" id="customerForm">
                <div class="panel-header">
                    <div>
                        <h2 class="panel-title">
                            <?= $editingCustomer ? 'Edit Customer' : 'Add New Customer' ?>
                        </h2>
                        <div class="panel-subtitle">
                            Enter the customer's contact and company information.
                        </div>
                    </div>

                    <a href="customers.php" class="action-btn" aria-label="Close form">
                        <i class="bi bi-x-lg"></i>
                    </a>
                </div>

                <div class="panel-body">
                    <form method="POST" action="customers.php" class="row g-3">
                        <input type="hidden" name="csrf_token" value="<?= e($csrfToken) ?>">
                        <input type="hidden" name="action" value="save">
                        <input type="hidden" name="id" value="<?= e($formCustomer['id']) ?>">

                        <div class="col-md-6">
                            <label for="name" class="form-label">Customer Name *</label>
                            <input
                                type="text"
                                class="form-control"
                                id="name"
                                name="name"
                                maxlength="100"
                                required
                                value="<?= e($formCustomer['name']) ?>"
                                placeholder="Enter full name"
                            >
                        </div>

                        <div class="col-md-6">
                            <label for="email" class="form-label">Email Address *</label>
                            <input
                                type="email"
                                class="form-control"
                                id="email"
                                name="email"
                                maxlength="190"
                                required
                                value="<?= e($formCustomer['email']) ?>"
                                placeholder="customer@example.com"
                            >
                        </div>

                        <div class="col-md-6">
                            <label for="phone" class="form-label">Phone Number</label>
                            <input
                                type="tel"
                                class="form-control"
                                id="phone"
                                name="phone"
                                maxlength="40"
                                value="<?= e($formCustomer['phone']) ?>"
                                placeholder="+94 77 123 4567"
                            >
                        </div>

                        <div class="col-md-6">
                            <label for="company" class="form-label">Company</label>
                            <input
                                type="text"
                                class="form-control"
                                id="company"
                                name="company"
                                maxlength="150"
                                value="<?= e($formCustomer['company']) ?>"
                                placeholder="Company or business name"
                            >
                        </div>

                        <div class="col-md-6">
                            <label for="country" class="form-label">Country</label>
                            <input
                                type="text"
                                class="form-control"
                                id="country"
                                name="country"
                                maxlength="100"
                                value="<?= e($formCustomer['country']) ?>"
                                placeholder="Enter country"
                            >
                        </div>

                        <div class="col-md-6">
                            <label for="status" class="form-label">Account Status</label>
                            <select class="form-select" id="status" name="status" required>
                                <option
                                    value="ACTIVE"
                                    <?= strtoupper($formCustomer['status']) === 'ACTIVE' ? 'selected' : '' ?>
                                >
                                    Active
                                </option>

                                <option
                                    value="INACTIVE"
                                    <?= strtoupper($formCustomer['status']) === 'INACTIVE' ? 'selected' : '' ?>
                                >
                                    Inactive
                                </option>
                            </select>
                        </div>

                        <div class="col-12 d-flex flex-wrap justify-content-end gap-2 pt-2">
                            <a href="customers.php" class="btn btn-light border px-4">
                                Cancel
                            </a>

                            <button type="submit" class="btn btn-primary px-4">
                                <i class="bi bi-check2-circle me-2"></i>
                                <?= $editingCustomer ? 'Save Changes' : 'Add Customer' ?>
                            </button>
                        </div>
                    </form>
                </div>
            </section>
        <?php endif; ?>

        <!-- Customer records -->
        <section class="panel">

            <div class="panel-header">
                <div>
                    <h2 class="panel-title">Customer Directory</h2>
                    <div class="panel-subtitle">
                        <?= count($filteredCustomers) ?> customer(s) matching your filters
                    </div>
                </div>

                <span class="status-badge status-active">
                    <i class="bi bi-database-check me-1"></i>
                    Demo Records
                </span>
            </div>

            <div class="filter-panel">
                <form method="GET" action="customers.php" class="row g-3 align-items-end">

                    <div class="col-lg-6">
                        <label for="search" class="form-label">Search Customers</label>

                        <div class="input-group">
                            <span class="input-group-text bg-white">
                                <i class="bi bi-search text-muted"></i>
                            </span>

                            <input
                                type="search"
                                class="form-control"
                                id="search"
                                name="search"
                                value="<?= e($search) ?>"
                                placeholder="Search name, email, company, phone..."
                            >
                        </div>
                    </div>

                    <div class="col-sm-6 col-lg-3">
                        <label for="statusFilter" class="form-label">Account Status</label>

                        <select
                            class="form-select"
                            id="statusFilter"
                            name="status"
                        >
                            <option value="ALL" <?= $statusFilter === 'ALL' ? 'selected' : '' ?>>
                                All Customers
                            </option>

                            <option value="ACTIVE" <?= $statusFilter === 'ACTIVE' ? 'selected' : '' ?>>
                                Active
                            </option>

                            <option value="INACTIVE" <?= $statusFilter === 'INACTIVE' ? 'selected' : '' ?>>
                                Inactive
                            </option>
                        </select>
                    </div>

                    <div class="col-sm-6 col-lg-3 d-flex gap-2">
                        <button type="submit" class="btn btn-primary flex-grow-1">
                            <i class="bi bi-search me-1"></i> Search
                        </button>

                        <a href="customers.php" class="btn btn-light border">
                            <i class="bi bi-arrow-counterclockwise"></i>
                        </a>
                    </div>

                </form>
            </div>

            <div class="table-responsive">
                <table class="table">
                    <thead>
                    <tr>
                        <th>Customer</th>
                        <th>Contact Information</th>
                        <th>Company</th>
                        <th>Country</th>
                        <th>Joined</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                    </thead>

                    <tbody>
                    <?php if (empty($filteredCustomers)): ?>
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <div class="mb-2">
                                    <i class="bi bi-people fs-2 text-secondary"></i>
                                </div>

                                <div class="fw-bold text-dark">No customers found</div>

                                <div class="muted mt-1">
                                    Try another search or add a new customer.
                                </div>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($filteredCustomers as $customer): ?>
                            <?php
                            $customerName = $customer['name'] ?? 'Unnamed Customer';
                            $customerStatus = strtoupper($customer['status'] ?? 'ACTIVE');
                            ?>

                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="customer-avatar">
                                            <?= e(strtoupper(substr($customerName, 0, 1))) ?>
                                        </div>

                                        <div>
                                            <div class="customer-name">
                                                <?= e($customerName) ?>
                                            </div>

                                            <div class="customer-sub">
                                                Customer #<?= e($customer['id'] ?? '') ?>
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <td>
                                    <div class="text-dark">
                                        <i class="bi bi-envelope me-1 text-primary"></i>
                                        <?= e($customer['email'] ?? '') ?>
                                    </div>

                                    <div class="customer-sub mt-2">
                                        <i class="bi bi-telephone me-1"></i>
                                        <?= e($customer['phone'] ?? 'No phone provided') ?>
                                    </div>
                                </td>

                                <td>
                                    <div class="text-dark fw-semibold">
                                        <?= e($customer['company'] ?: 'Individual') ?>
                                    </div>
                                </td>

                                <td><?= e($customer['country'] ?? '—') ?></td>

                                <td class="text-nowrap">
                                    <?= e(
                                        !empty($customer['created_at']) &&
                                        strtotime($customer['created_at'])
                                            ? date('d M Y', strtotime($customer['created_at']))
                                            : '—'
                                    ) ?>
                                </td>

                                <td>
                                    <span class="status-badge <?= e(customerStatusClass($customerStatus)) ?>">
                                        <?= e($customerStatus) ?>
                                    </span>
                                </td>

                                <td>
                                    <div class="d-flex justify-content-end gap-1">
                                        <a
                                            href="customers.php?edit=<?= (int)$customer['id'] ?>"
                                            class="action-btn"
                                            title="Edit customer"
                                            aria-label="Edit customer"
                                        >
                                            <i class="bi bi-pencil-square"></i>
                                        </a>

                                        <button
                                            type="button"
                                            class="action-btn"
                                            title="Change status"
                                            aria-label="Change customer status"
                                            data-bs-toggle="modal"
                                            data-bs-target="#statusModal"
                                            data-id="<?= (int)$customer['id'] ?>"
                                            data-name="<?= e($customerName) ?>"
                                            data-status="<?= e($customerStatus) ?>"
                                        >
                                            <i class="bi bi-person-gear"></i>
                                        </button>

                                        <button
                                            type="button"
                                            class="action-btn delete"
                                            title="Delete customer"
                                            aria-label="Delete customer"
                                            data-bs-toggle="modal"
                                            data-bs-target="#deleteModal"
                                            data-id="<?= (int)$customer['id'] ?>"
                                            data-name="<?= e($customerName) ?>"
                                        >
                                            <i class="bi bi-trash3"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2 px-4 py-3 border-top">
                <span class="muted small">
                    Showing <?= count($filteredCustomers) ?> of <?= $totalCustomers ?> customer(s)
                </span>

                <a href="customers.php?add=1" class="small fw-bold text-primary">
                    <i class="bi bi-plus-circle me-1"></i>
                    Add another customer
                </a>
            </div>

        </section>

        <div class="alert alert-info border-0 mt-4 mb-0">
            <i class="bi bi-info-circle me-2"></i>
            <strong>Demonstration mode:</strong>
            Customer changes are stored in PHP session data. They are temporary and may
            disappear when the session expires or is cleared.
        </div>

    </main>

    <footer class="footer d-flex flex-column flex-md-row justify-content-between gap-2">
        <span>
            &copy; <?= date('Y') ?> ProServe — CRM &amp; Service Management System
        </span>

        <span>
            Portfolio demonstration by
            <strong class="text-primary">Codvanta</strong>
        </span>
    </footer>

</div>

<!-- Change status confirmation -->
<div class="modal fade" id="statusModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <form method="POST" action="customers.php">
                <input type="hidden" name="csrf_token" value="<?= e($csrfToken) ?>">
                <input type="hidden" name="action" value="toggle_status">
                <input type="hidden" name="id" id="statusCustomerId">

                <div class="modal-header border-0">
                    <h5 class="modal-title fw-bold">Change Customer Status</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    Change the account status for
                    <strong id="statusCustomerName"></strong>?

                    <p class="small muted mt-2 mb-0">
                        Active customers are marked as available; inactive customers
                        remain in the directory but are not currently active.
                    </p>
                </div>

                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light border" data-bs-dismiss="modal">
                        Cancel
                    </button>

                    <button type="submit" class="btn btn-primary">
                        Confirm Status Change
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete confirmation -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <form method="POST" action="customers.php">
                <input type="hidden" name="csrf_token" value="<?= e($csrfToken) ?>">
                <input type="hidden" name="action" value="delete">
                <input type="hidden" name="id" id="deleteCustomerId">

                <div class="modal-header border-0">
                    <h5 class="modal-title fw-bold">Delete Customer</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    Are you sure you want to delete
                    <strong id="deleteCustomerName"></strong>?

                    <p class="small text-danger mt-2 mb-0">
                        This removes the customer record from this session-based demonstration.
                    </p>
                </div>

                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light border" data-bs-dismiss="modal">
                        Cancel
                    </button>

                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-trash3 me-1"></i>
                        Delete Customer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // Responsive sidebar
    const sidebar = document.getElementById('sidebar');
    const sidebarBackdrop = document.getElementById('sidebarBackdrop');
    const mobileMenuButton = document.getElementById('mobileMenuButton');

    function closeSidebar() {
        sidebar.classList.remove('open');
        sidebarBackdrop.classList.remove('show');
    }

    mobileMenuButton.addEventListener('click', function () {
        sidebar.classList.toggle('open');
        sidebarBackdrop.classList.toggle('show');
    });

    sidebarBackdrop.addEventListener('click', closeSidebar);

    window.addEventListener('resize', function () {
        if (window.innerWidth > 991) {
            closeSidebar();
        }
    });

    // Populate status confirmation modal
    document.getElementById('statusModal').addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;

        document.getElementById('statusCustomerId').value = button.dataset.id;
        document.getElementById('statusCustomerName').textContent = button.dataset.name;
    });

    // Populate delete confirmation modal
    document.getElementById('deleteModal').addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;

        document.getElementById('deleteCustomerId').value = button.dataset.id;
        document.getElementById('deleteCustomerName').textContent = button.dataset.name;
    });
</script>

</body>
</html>
