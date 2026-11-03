
<?php
session_start();
date_default_timezone_set('Asia/Colombo');

/*
|--------------------------------------------------------------------------
| ProServe - Administrator Service Management
|--------------------------------------------------------------------------
| Demonstration features:
| - View and search services
| - Filter services by category and status
| - Add new services
| - Edit existing services
| - Activate and deactivate services
| - Delete services
| - View service statistics
|
| Data is stored in PHP sessions for demonstration purposes.
|--------------------------------------------------------------------------
*/

if (!isset($_SESSION['proserve_services'])) {
    $_SESSION['proserve_services'] = [
        [
            'id' => 'SRV001',
            'name' => 'Custom Web Application Development',
            'category' => 'Development',
            'description' => 'Custom web applications designed to automate business processes and improve operational efficiency.',
            'price' => 150000,
            'billing_type' => 'Starting price',
            'duration' => '2–8 weeks',
            'icon' => 'bi-code-slash',
            'status' => 'ACTIVE',
            'created_at' => date('Y-m-d H:i:s', strtotime('-30 days'))
        ],
        [
            'id' => 'SRV002',
            'name' => 'Business Management Systems',
            'category' => 'Business Solutions',
            'description' => 'Integrated CRM, inventory, employee, booking, and other business management solutions.',
            'price' => 200000,
            'billing_type' => 'Starting price',
            'duration' => '4–10 weeks',
            'icon' => 'bi-diagram-3',
            'status' => 'ACTIVE',
            'created_at' => date('Y-m-d H:i:s', strtotime('-27 days'))
        ],
        [
            'id' => 'SRV003',
            'name' => 'E-commerce Development',
            'category' => 'Development',
            'description' => 'Online stores with product catalogs, shopping carts, order management, and payment integration.',
            'price' => 175000,
            'billing_type' => 'Starting price',
            'duration' => '3–8 weeks',
            'icon' => 'bi-cart3',
            'status' => 'ACTIVE',
            'created_at' => date('Y-m-d H:i:s', strtotime('-24 days'))
        ],
        [
            'id' => 'SRV004',
            'name' => 'Database Development',
            'category' => 'Technology',
            'description' => 'Database design, data modeling, query optimization, and secure data management solutions.',
            'price' => 50000,
            'billing_type' => 'Starting price',
            'duration' => '1–4 weeks',
            'icon' => 'bi-database',
            'status' => 'ACTIVE',
            'created_at' => date('Y-m-d H:i:s', strtotime('-20 days'))
        ],
        [
            'id' => 'SRV005',
            'name' => 'API Integration',
            'category' => 'Technology',
            'description' => 'Connect applications with payment gateways, external platforms, and third-party services.',
            'price' => 35000,
            'billing_type' => 'Starting price',
            'duration' => '1–3 weeks',
            'icon' => 'bi-plug',
            'status' => 'ACTIVE',
            'created_at' => date('Y-m-d H:i:s', strtotime('-18 days'))
        ],
        [
            'id' => 'SRV006',
            'name' => 'Website Maintenance',
            'category' => 'Support',
            'description' => 'Website updates, troubleshooting, performance monitoring, and ongoing technical assistance.',
            'price' => 15000,
            'billing_type' => 'Monthly',
            'duration' => 'Ongoing',
            'icon' => 'bi-tools',
            'status' => 'ACTIVE',
            'created_at' => date('Y-m-d H:i:s', strtotime('-15 days'))
        ],
        [
            'id' => 'SRV007',
            'name' => 'IT Consulting',
            'category' => 'Consulting',
            'description' => 'Technical guidance, system planning, digital transformation, and IT infrastructure consulting.',
            'price' => 10000,
            'billing_type' => 'Per consultation',
            'duration' => '1–2 hours',
            'icon' => 'bi-lightbulb',
            'status' => 'ACTIVE',
            'created_at' => date('Y-m-d H:i:s', strtotime('-12 days'))
        ],
        [
            'id' => 'SRV008',
            'name' => 'Digital Marketing',
            'category' => 'Marketing',
            'description' => 'Digital marketing strategy, search engine optimization, content planning, and campaign support.',
            'price' => 40000,
            'billing_type' => 'Monthly',
            'duration' => 'Ongoing',
            'icon' => 'bi-megaphone',
            'status' => 'INACTIVE',
            'created_at' => date('Y-m-d H:i:s', strtotime('-9 days'))
        ],
        [
            'id' => 'SRV009',
            'name' => 'UI/UX Design',
            'category' => 'Design',
            'description' => 'User-centered interface design, wireframes, prototypes, and responsive website layouts.',
            'price' => 45000,
            'billing_type' => 'Starting price',
            'duration' => '1–4 weeks',
            'icon' => 'bi-palette',
            'status' => 'ACTIVE',
            'created_at' => date('Y-m-d H:i:s', strtotime('-7 days'))
        ],
        [
            'id' => 'SRV010',
            'name' => 'Software Repair and Support',
            'category' => 'Support',
            'description' => 'Software troubleshooting, bug fixing, compatibility improvements, and technical support.',
            'price' => 8000,
            'billing_type' => 'Starting price',
            'duration' => 'Varies',
            'icon' => 'bi-wrench-adjustable',
            'status' => 'INACTIVE',
            'created_at' => date('Y-m-d H:i:s', strtotime('-5 days'))
        ]
    ];

    $_SESSION['proserve_next_service_number'] = 11;
}

if (!isset($_SESSION['proserve_services_csrf'])) {
    $_SESSION['proserve_services_csrf'] = bin2hex(random_bytes(32));
}

function e($value): string
{
    return htmlspecialchars((string)($value ?? ''), ENT_QUOTES, 'UTF-8');
}

function serviceMoney($amount): string
{
    return 'LKR ' . number_format((float)$amount, 2);
}

function serviceStatusClass($status): string
{
    return strtoupper((string)$status) === 'ACTIVE'
        ? 'status-active'
        : 'status-inactive';
}

$categories = [
    'Development',
    'Business Solutions',
    'Technology',
    'Support',
    'Consulting',
    'Marketing',
    'Design',
    'Other'
];

$billingTypes = [
    'Starting price',
    'Fixed price',
    'Monthly',
    'Per consultation',
    'Hourly',
    'Custom quotation'
];

$availableIcons = [
    'bi-code-slash' => 'Web Development',
    'bi-diagram-3' => 'Business Systems',
    'bi-cart3' => 'E-commerce',
    'bi-database' => 'Database',
    'bi-plug' => 'API Integration',
    'bi-tools' => 'Maintenance',
    'bi-lightbulb' => 'Consulting',
    'bi-megaphone' => 'Marketing',
    'bi-palette' => 'Design',
    'bi-wrench-adjustable' => 'Technical Support',
    'bi-cloud' => 'Cloud Services',
    'bi-shield-check' => 'Security',
    'bi-phone' => 'Mobile Development',
    'bi-gear' => 'Other Services'
];

$notice = '';
$noticeType = 'success';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $submittedToken = (string)($_POST['csrf_token'] ?? '');

    if (!hash_equals((string)$_SESSION['proserve_services_csrf'], $submittedToken)) {
        $notice = 'Session verification failed. Please try again.';
        $noticeType = 'danger';
    } else {
        $action = (string)($_POST['action'] ?? '');
        $serviceId = trim((string)($_POST['service_id'] ?? ''));
        $serviceIndex = null;

        foreach ($_SESSION['proserve_services'] as $index => $service) {
            if (
                isset($service['id']) &&
                hash_equals((string)$service['id'], $serviceId)
            ) {
                $serviceIndex = $index;
                break;
            }
        }

        switch ($action) {
            case 'save':
                $name = trim((string)($_POST['name'] ?? ''));
                $category = trim((string)($_POST['category'] ?? ''));
                $description = trim((string)($_POST['description'] ?? ''));
                $priceRaw = trim((string)($_POST['price'] ?? ''));
                $billingType = trim((string)($_POST['billing_type'] ?? ''));
                $duration = trim((string)($_POST['duration'] ?? ''));
                $icon = trim((string)($_POST['icon'] ?? 'bi-gear'));
                $status = strtoupper(trim((string)($_POST['status'] ?? 'ACTIVE')));

                $errors = [];

                if ($name === '' || strlen($name) > 120) {
                    $errors[] = 'Enter a service name of up to 120 characters.';
                }

                if (!in_array($category, $categories, true)) {
                    $errors[] = 'Choose a valid service category.';
                }

                if ($description === '' || strlen($description) > 1000) {
                    $errors[] = 'Enter a description of up to 1,000 characters.';
                }

                if (!is_numeric($priceRaw) || (float)$priceRaw < 0 || (float)$priceRaw > 999999999) {
                    $errors[] = 'Enter a valid non-negative service price.';
                }

                if (!in_array($billingType, $billingTypes, true)) {
                    $errors[] = 'Choose a valid billing type.';
                }

                if ($duration === '' || strlen($duration) > 80) {
                    $errors[] = 'Enter a service duration of up to 80 characters.';
                }

                if (!array_key_exists($icon, $availableIcons)) {
                    $icon = 'bi-gear';
                }

                if (!in_array($status, ['ACTIVE', 'INACTIVE'], true)) {
                    $errors[] = 'Choose a valid service status.';
                }

                // Avoid duplicate service names, ignoring case.
                foreach ($_SESSION['proserve_services'] as $existingIndex => $existing) {
                    if ($serviceIndex !== null && $existingIndex === $serviceIndex) {
                        continue;
                    }

                    if (strcasecmp(trim((string)($existing['name'] ?? '')), $name) === 0) {
                        $errors[] = 'A service with this name already exists.';
                        break;
                    }
                }

                if ($errors) {
                    $notice = implode(' ', $errors);
                    $noticeType = 'danger';
                } else {
                    $serviceData = [
                        'name' => $name,
                        'category' => $category,
                        'description' => $description,
                        'price' => round((float)$priceRaw, 2),
                        'billing_type' => $billingType,
                        'duration' => $duration,
                        'icon' => $icon,
                        'status' => $status
                    ];

                    if ($serviceIndex !== null) {
                        $_SESSION['proserve_services'][$serviceIndex] = array_merge(
                            $_SESSION['proserve_services'][$serviceIndex],
                            $serviceData,
                            ['updated_at' => date('Y-m-d H:i:s')]
                        );

                        $notice = 'Service details updated successfully.';
                    } else {
                        $number = (int)($_SESSION['proserve_next_service_number'] ?? 11);

                        $serviceData['id'] = 'SRV' . str_pad((string)$number, 3, '0', STR_PAD_LEFT);
                        $serviceData['created_at'] = date('Y-m-d H:i:s');

                        $_SESSION['proserve_services'][] = $serviceData;
                        $_SESSION['proserve_next_service_number'] = $number + 1;

                        $notice = 'New service added successfully.';
                    }
                }
                break;

            case 'toggle':
                if ($serviceIndex === null) {
                    $notice = 'Service not found.';
                    $noticeType = 'danger';
                    break;
                }

                $currentStatus = strtoupper((string)($_SESSION['proserve_services'][$serviceIndex]['status'] ?? 'INACTIVE'));

                $_SESSION['proserve_services'][$serviceIndex]['status'] =
                    $currentStatus === 'ACTIVE' ? 'INACTIVE' : 'ACTIVE';

                $_SESSION['proserve_services'][$serviceIndex]['updated_at'] = date('Y-m-d H:i:s');

                $notice = 'Service status changed to ' .
                    strtolower($_SESSION['proserve_services'][$serviceIndex]['status']) . '.';
                break;

            case 'delete':
                if ($serviceIndex === null) {
                    $notice = 'Service not found.';
                    $noticeType = 'danger';
                    break;
                }

                array_splice($_SESSION['proserve_services'], $serviceIndex, 1);
                $notice = 'Service deleted successfully.';
                break;

            default:
                $notice = 'Unknown action.';
                $noticeType = 'danger';
        }
    }

    $_SESSION['proserve_services_notice'] = [
        'message' => $notice,
        'type' => $noticeType
    ];

    // Keep the current page URL clean and prevent form resubmission.
    header('Location: services.php');
    exit;
}

if (isset($_SESSION['proserve_services_notice'])) {
    $savedNotice = $_SESSION['proserve_services_notice'];
    $notice = (string)($savedNotice['message'] ?? '');
    $noticeType = (string)($savedNotice['type'] ?? 'success');
    unset($_SESSION['proserve_services_notice']);
}

$services = array_values($_SESSION['proserve_services']);

$totalServices = count($services);
$activeCount = 0;
$inactiveCount = 0;
$categoryCount = [];

foreach ($services as $service) {
    $status = strtoupper((string)($service['status'] ?? 'INACTIVE'));

    if ($status === 'ACTIVE') {
        $activeCount++;
    } else {
        $inactiveCount++;
    }

    $categoryName = (string)($service['category'] ?? 'Other');
    $categoryCount[$categoryName] = ($categoryCount[$categoryName] ?? 0) + 1;
}

$search = trim((string)($_GET['search'] ?? ''));
$categoryFilter = trim((string)($_GET['category'] ?? 'ALL'));
$statusFilter = strtoupper(trim((string)($_GET['status'] ?? 'ALL')));

if ($categoryFilter !== 'ALL' && !in_array($categoryFilter, $categories, true)) {
    $categoryFilter = 'ALL';
}

if (!in_array($statusFilter, ['ALL', 'ACTIVE', 'INACTIVE'], true)) {
    $statusFilter = 'ALL';
}

$filteredServices = array_values(array_filter(
    $services,
    function ($service) use ($search, $categoryFilter, $statusFilter) {
        if (
            $categoryFilter !== 'ALL' &&
            ($service['category'] ?? '') !== $categoryFilter
        ) {
            return false;
        }

        if (
            $statusFilter !== 'ALL' &&
            strtoupper((string)($service['status'] ?? '')) !== $statusFilter
        ) {
            return false;
        }

        $searchable = implode(' ', [
            $service['id'] ?? '',
            $service['name'] ?? '',
            $service['category'] ?? '',
            $service['description'] ?? ''
        ]);

        return $search === '' || stripos($searchable, $search) !== false;
    }
));

$averagePrice = 0;

if ($totalServices > 0) {
    $totalPrice = array_sum(array_map(
        fn($service) => (float)($service['price'] ?? 0),
        $services
    ));

    $averagePrice = $totalPrice / $totalServices;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Service Management | ProServe Admin</title>

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

        .btn-primary { background: var(--primary); border-color: var(--primary); }
        .btn-primary:hover { background: var(--primary-dark); border-color: var(--primary-dark); }

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
            grid-template-columns: minmax(190px, 1.5fr) minmax(155px, 1fr) minmax(140px, 1fr) auto;
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

        .service-icon {
            width: 42px;
            height: 42px;
            display: grid;
            place-items: center;
            border-radius: 12px;
            background: #eaf0ff;
            color: var(--primary);
            font-size: 20px;
            flex-shrink: 0;
        }

        .service-name { color: var(--navy); font-weight: 700; font-size: 12px; }
        .subtext { color: var(--muted); font-size: 10px; margin-top: 5px; }

        .service-description {
            white-space: normal;
            min-width: 180px;
            max-width: 270px;
            color: #718096;
            line-height: 1.6;
            font-size: 11px;
        }

        .category-tag {
            display: inline-block;
            background: #f0f4fa;
            color: #53657e;
            padding: 6px 9px;
            border-radius: 7px;
            font-size: 10px;
            font-weight: 700;
        }

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

        .status-active { color: #24704b; background: #e4f8ed; }
        .status-inactive { color: #8b96a8; background: #edf0f5; }

        .action-btn {
            border: 1px solid #e0e7f0;
            width: 32px;
            height: 32px;
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
        .form-text { font-size: 10px; }

        .category-summary {
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 15px;
            height: 100%;
            background: #fff;
        }

        .category-summary .category-name { color: var(--navy); font-size: 12px; font-weight: 700; }
        .category-summary .category-number { color: var(--primary); font-size: 22px; font-weight: 800; margin-top: 8px; }

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
        <a href="index.php" class="nav-link">
            <i class="bi bi-grid-1x2"></i> Dashboard
        </a>
        <a href="customers.php" class="nav-link">
            <i class="bi bi-people"></i> Customers
        </a>
        <a href="leads.php" class="nav-link">
            <i class="bi bi-funnel"></i> Lead Management
        </a>
        <a href="appointments.php" class="nav-link">
            <i class="bi bi-calendar-check"></i> Appointments
        </a>
        <a href="services.php" class="nav-link active">
            <i class="bi bi-grid"></i> Services
        </a>
        <a href="quotations.php" class="nav-link">
            <i class="bi bi-file-earmark-text"></i> Quotations
        </a>
        <a href="invoices.php" class="nav-link">
            <i class="bi bi-receipt"></i> Invoices
        </a>
        <a href="employees.php" class="nav-link">
            <i class="bi bi-person-workspace"></i> Employees
        </a>
        <a href="enquiries.php" class="nav-link">
            <i class="bi bi-chat-left-text"></i> Contact Enquiries
        </a>
        <a href="reports.php" class="nav-link">
            <i class="bi bi-bar-chart"></i> Reports
        </a>
        <a href="settings.php" class="nav-link">
            <i class="bi bi-gear"></i> Settings
        </a>
    </nav>

    <div class="sidebar-bottom">
        <a href="../index.php" class="nav-link">
            <i class="bi bi-box-arrow-up-right"></i> View Customer Site
        </a>
        <a href="../dashboard.php" class="nav-link">
            <i class="bi bi-person-circle"></i> Customer Dashboard
        </a>
    </div>
</aside>

<div class="main">
    <header class="topbar">
        <div>
            <div class="topbar-title">Service Management</div>
            <div class="text-secondary small mt-1">ProServe / Administrator / Services</div>
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
                <h1>Service Management</h1>
                <p>Add, update, and manage the services offered by your business.</p>
            </div>

            <button type="button" class="btn btn-primary btn-sm px-3 py-2"
                    data-bs-toggle="modal" data-bs-target="#serviceModal">
                <i class="bi bi-plus-lg me-1"></i> Add New Service
            </button>
        </div>

        <?php if ($notice !== ''): ?>
            <div class="alert alert-<?= e(in_array($noticeType, ['success', 'warning', 'danger', 'info'], true) ? $noticeType : 'info') ?> alert-dismissible fade show" role="alert">
                <i class="bi bi-info-circle me-2"></i><?= e($notice) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="row g-3">
            <div class="col-6 col-xl-3">
                <div class="metric-card">
                    <div class="metric-top">
                        <div class="metric-label">Total Services</div>
                        <div class="metric-icon" style="background:#eaf0ff;color:#2563eb;">
                            <i class="bi bi-grid"></i>
                        </div>
                    </div>
                    <div class="metric-value"><?= $totalServices ?></div>
                    <div class="metric-note">All listed service offerings</div>
                </div>
            </div>

            <div class="col-6 col-xl-3">
                <div class="metric-card">
                    <div class="metric-top">
                        <div class="metric-label">Active Services</div>
                        <div class="metric-icon" style="background:#e1f8eb;color:#238453;">
                            <i class="bi bi-check-circle"></i>
                        </div>
                    </div>
                    <div class="metric-value"><?= $activeCount ?></div>
                    <div class="metric-note">Currently available in this demo</div>
                </div>
            </div>

            <div class="col-6 col-xl-3">
                <div class="metric-card">
                    <div class="metric-top">
                        <div class="metric-label">Inactive Services</div>
                        <div class="metric-icon" style="background:#edf0f5;color:#64748b;">
                            <i class="bi bi-pause-circle"></i>
                        </div>
                    </div>
                    <div class="metric-value"><?= $inactiveCount ?></div>
                    <div class="metric-note">Temporarily unavailable services</div>
                </div>
            </div>

            <div class="col-6 col-xl-3">
                <div class="metric-card">
                    <div class="metric-top">
                        <div class="metric-label">Average Listed Price</div>
                        <div class="metric-icon" style="background:#f0eaff;color:#7652ba;">
                            <i class="bi bi-currency-exchange"></i>
                        </div>
                    </div>
                    <div class="metric-value" style="font-size:21px;">
                        <?= e(serviceMoney($averagePrice)) ?>
                    </div>
                    <div class="metric-note">Across all listed services</div>
                </div>
            </div>
        </div>

        <section class="panel">
            <div class="panel-heading">
                <div>
                    <h2><i class="bi bi-collection me-2" style="color:#2563eb;"></i>Service Catalog</h2>
                    <p>Manage service descriptions, categories, pricing, and availability.</p>
                </div>

                <span class="badge rounded-pill text-bg-light border px-3 py-2">
                    <?= count($filteredServices) ?> result(s)
                </span>
            </div>

            <form method="GET" action="services.php" class="filters">
                <div>
                    <input type="search" name="search" class="form-control"
                           placeholder="Search service name or description..."
                           value="<?= e($search) ?>">
                </div>

                <div>
                    <select name="category" class="form-select" aria-label="Filter by category">
                        <option value="ALL">All categories</option>
                        <?php foreach ($categories as $categoryOption): ?>
                            <option value="<?= e($categoryOption) ?>"
                                <?= $categoryFilter === $categoryOption ? 'selected' : '' ?>>
                                <?= e($categoryOption) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <select name="status" class="form-select" aria-label="Filter by status">
                        <option value="ALL" <?= $statusFilter === 'ALL' ? 'selected' : '' ?>>All statuses</option>
                        <option value="ACTIVE" <?= $statusFilter === 'ACTIVE' ? 'selected' : '' ?>>Active</option>
                        <option value="INACTIVE" <?= $statusFilter === 'INACTIVE' ? 'selected' : '' ?>>Inactive</option>
                    </select>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary btn-sm px-3">
                        <i class="bi bi-search me-1"></i> Filter
                    </button>
                    <a href="services.php" class="btn btn-light border btn-sm px-3">Reset</a>
                </div>
            </form>

            <?php if (count($filteredServices) > 0): ?>
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                        <tr>
                            <th>Service</th>
                            <th>Category</th>
                            <th>Description</th>
                            <th>Price</th>
                            <th>Duration</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($filteredServices as $service): ?>
                            <?php
                                $serviceId = (string)($service['id'] ?? '');
                                $serviceStatus = strtoupper((string)($service['status'] ?? 'INACTIVE'));
                            ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="service-icon">
                                            <i class="bi <?= e($service['icon'] ?? 'bi-gear') ?>"></i>
                                        </div>
                                        <div>
                                            <div class="service-name"><?= e($service['name'] ?? '') ?></div>
                                            <div class="subtext"><?= e($serviceId) ?></div>
                                        </div>
                                    </div>
                                </td>

                                <td>
                                    <span class="category-tag"><?= e($service['category'] ?? 'Other') ?></span>
                                </td>

                                <td>
                                    <div class="service-description">
                                        <?= e($service['description'] ?? '') ?>
                                    </div>
                                </td>

                                <td>
                                    <div class="fw-bold" style="color:#10233f;">
                                        <?= e(serviceMoney($service['price'] ?? 0)) ?>
                                    </div>
                                    <div class="subtext"><?= e($service['billing_type'] ?? 'Starting price') ?></div>
                                </td>

                                <td><?= e($service['duration'] ?? 'Varies') ?></td>

                                <td>
                                    <span class="status <?= e(serviceStatusClass($serviceStatus)) ?>">
                                        <?= e(ucfirst(strtolower($serviceStatus))) ?>
                                    </span>
                                </td>

                                <td class="text-end">
                                    <div class="d-flex justify-content-end gap-1">
                                        <button type="button" class="action-btn"
                                                title="Edit service"
                                                data-bs-toggle="modal"
                                                data-bs-target="#serviceModal"
                                                data-service="<?= e(json_encode($service, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP)) ?>">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>

                                        <form method="POST" action="services.php">
                                            <input type="hidden" name="csrf_token" value="<?= e($_SESSION['proserve_services_csrf']) ?>">
                                            <input type="hidden" name="action" value="toggle">
                                            <input type="hidden" name="service_id" value="<?= e($serviceId) ?>">
                                            <button type="submit" class="action-btn"
                                                    title="<?= $serviceStatus === 'ACTIVE' ? 'Deactivate service' : 'Activate service' ?>"
                                                    onclick="return confirm('<?= $serviceStatus === 'ACTIVE' ? 'Deactivate' : 'Activate' ?> this service?');">
                                                <i class="bi <?= $serviceStatus === 'ACTIVE' ? 'bi-toggle-on text-success' : 'bi-toggle-off' ?>"></i>
                                            </button>
                                        </form>

                                        <form method="POST" action="services.php">
                                            <input type="hidden" name="csrf_token" value="<?= e($_SESSION['proserve_services_csrf']) ?>">
                                            <input type="hidden" name="action" value="delete">
                                            <input type="hidden" name="service_id" value="<?= e($serviceId) ?>">
                                            <button type="submit" class="action-btn text-danger"
                                                    title="Delete service"
                                                    onclick="return confirm('Delete this service permanently from the demonstration catalog?');">
                                                <i class="bi bi-trash3"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="empty-state">
                    <i class="bi bi-search"></i>
                    <h3>No services found</h3>
                    <p class="mb-3">Try a different search or category filter.</p>
                    <a href="services.php" class="btn btn-outline-primary btn-sm">Clear filters</a>
                </div>
            <?php endif; ?>
        </section>

        <section class="mt-4">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
                <div>
                    <h2 class="h6 fw-bold mb-1" style="color:#10233f;">Services by Category</h2>
                    <p class="text-secondary small mb-0">Overview of the service catalog.</p>
                </div>
            </div>

            <div class="row g-3">
                <?php
                    $categoryIcons = [
                        'Development' => 'bi-code-slash',
                        'Business Solutions' => 'bi-diagram-3',
                        'Technology' => 'bi-cpu',
                        'Support' => 'bi-tools',
                        'Consulting' => 'bi-lightbulb',
                        'Marketing' => 'bi-megaphone',
                        'Design' => 'bi-palette',
                        'Other' => 'bi-grid'
                    ];
                ?>

                <?php foreach ($categories as $categoryName): ?>
                    <?php if (isset($categoryCount[$categoryName])): ?>
                        <div class="col-6 col-md-4 col-xl-3">
                            <a href="services.php?category=<?= urlencode($categoryName) ?>"
                               class="text-reset">
                                <div class="category-summary">
                                    <div class="d-flex align-items-center justify-content-between gap-2">
                                        <div class="category-name"><?= e($categoryName) ?></div>
                                        <i class="bi <?= e($categoryIcons[$categoryName] ?? 'bi-grid') ?> text-primary fs-5"></i>
                                    </div>
                                    <div class="category-number"><?= (int)$categoryCount[$categoryName] ?></div>
                                    <div class="subtext">Service(s) in this category</div>
                                </div>
                            </a>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </section>
    </main>

    <footer class="page-footer">
        <span>© <?= date('Y') ?> ProServe — CRM &amp; Service Management System</span>
        <span>Portfolio demonstration by <strong>Codvanta</strong></span>
    </footer>
</div>

<!-- Add / Edit Service Modal -->
<div class="modal fade" id="serviceModal" tabindex="-1" aria-labelledby="serviceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <form method="POST" action="services.php" id="serviceForm">
                <div class="modal-header px-4 py-3">
                    <div>
                        <h2 class="modal-title" id="serviceModalLabel">Add New Service</h2>
                        <div class="small text-secondary mt-1">Enter the service information below.</div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body p-4">
                    <input type="hidden" name="csrf_token" value="<?= e($_SESSION['proserve_services_csrf']) ?>">
                    <input type="hidden" name="action" value="save">
                    <input type="hidden" name="service_id" id="serviceId">

                    <div class="row g-3">
                        <div class="col-md-8">
                            <label for="serviceName" class="form-label">Service Name *</label>
                            <input type="text" class="form-control" id="serviceName" name="name"
                                   maxlength="120" required placeholder="e.g. Mobile App Development">
                        </div>

                        <div class="col-md-4">
                            <label for="serviceCategory" class="form-label">Category *</label>
                            <select class="form-select" id="serviceCategory" name="category" required>
                                <option value="">Select category</option>
                                <?php foreach ($categories as $categoryOption): ?>
                                    <option value="<?= e($categoryOption) ?>"><?= e($categoryOption) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-12">
                            <label for="serviceDescription" class="form-label">Description *</label>
                            <textarea class="form-control" id="serviceDescription" name="description"
                                      rows="4" maxlength="1000" required
                                      placeholder="Describe the service and its main benefits..."></textarea>
                            <div class="form-text">Maximum 1,000 characters.</div>
                        </div>

                        <div class="col-md-6">
                            <label for="servicePrice" class="form-label">Price (LKR) *</label>
                            <input type="number" class="form-control" id="servicePrice" name="price"
                                   min="0" max="999999999" step="0.01" required placeholder="50000.00">
                        </div>

                        <div class="col-md-6">
                            <label for="billingType" class="form-label">Billing Type *</label>
                            <select class="form-select" id="billingType" name="billing_type" required>
                                <?php foreach ($billingTypes as $billingOption): ?>
                                    <option value="<?= e($billingOption) ?>"><?= e($billingOption) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="serviceDuration" class="form-label">Estimated Duration *</label>
                            <input type="text" class="form-control" id="serviceDuration" name="duration"
                                   maxlength="80" required placeholder="e.g. 2–4 weeks">
                        </div>

                        <div class="col-md-6">
                            <label for="serviceIcon" class="form-label">Service Icon</label>
                            <select class="form-select" id="serviceIcon" name="icon">
                                <?php foreach ($availableIcons as $iconClass => $iconLabel): ?>
                                    <option value="<?= e($iconClass) ?>"><?= e($iconLabel) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="serviceStatus" class="form-label">Status *</label>
                            <select class="form-select" id="serviceStatus" name="status" required>
                                <option value="ACTIVE">Active</option>
                                <option value="INACTIVE">Inactive</option>
                            </select>
                        </div>
                    </div>

                    <div class="alert alert-info small mt-4 mb-0">
                        <i class="bi bi-info-circle me-1"></i>
                        Prices are demonstration values. The application does not process real payments.
                    </div>
                </div>

                <div class="modal-footer px-4">
                    <button type="button" class="btn btn-light border btn-sm px-3" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-sm px-3">
                        <i class="bi bi-check2-circle me-1"></i>
                        <span id="saveButtonText">Add Service</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const serviceModal = document.getElementById('serviceModal');
    const serviceForm = document.getElementById('serviceForm');

    const defaultForm = function () {
        serviceForm.reset();

        document.getElementById('serviceId').value = '';
        document.getElementById('serviceModalLabel').textContent = 'Add New Service';
        document.getElementById('saveButtonText').textContent = 'Add Service';
        document.getElementById('serviceStatus').value = 'ACTIVE';
        document.getElementById('serviceIcon').value = 'bi-code-slash';
        document.getElementById('billingType').value = 'Starting price';
    };

    serviceModal.addEventListener('show.bs.modal', function (event) {
        const trigger = event.relatedTarget;
        defaultForm();

        if (!trigger || !trigger.hasAttribute('data-service')) {
            return;
        }

        let service = {};

        try {
            service = JSON.parse(trigger.getAttribute('data-service') || '{}');
        } catch (error) {
            service = {};
        }

        document.getElementById('serviceId').value = service.id || '';
        document.getElementById('serviceName').value = service.name || '';
        document.getElementById('serviceCategory').value = service.category || '';
        document.getElementById('serviceDescription').value = service.description || '';
        document.getElementById('servicePrice').value = service.price ?? 0;
        document.getElementById('billingType').value = service.billing_type || 'Starting price';
        document.getElementById('serviceDuration').value = service.duration || '';
        document.getElementById('serviceIcon').value = service.icon || 'bi-gear';
        document.getElementById('serviceStatus').value = service.status || 'ACTIVE';

        document.getElementById('serviceModalLabel').textContent = 'Edit Service';
        document.getElementById('saveButtonText').textContent = 'Save Changes';
    });
});
</script>
</body>
</html>
