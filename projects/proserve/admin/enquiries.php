
<?php
session_start();
date_default_timezone_set('Asia/Colombo');

/*
|--------------------------------------------------------------------------
| ProServe CRM - Enquiry Management
|--------------------------------------------------------------------------
| Demonstration page using PHP sessions. No database required.
*/

function e($value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function enquiryReference(array $enquiry): string
{
    return (string) (
        $enquiry['reference']
        ?? $enquiry['enquiry_reference']
        ?? 'ENQ-000'
    );
}

function enquiryCustomerName(array $enquiry): string
{
    return (string) (
        $enquiry['name']
        ?? $enquiry['full_name']
        ?? $enquiry['customer_name']
        ?? 'Unknown Customer'
    );
}

function enquiryStatusClass($status): string
{
    return match (strtoupper(trim((string) $status))) {
        'NEW', 'NEW LEAD' => 'status-new',
        'IN PROGRESS', 'FOLLOW UP' => 'status-progress',
        'RESPONDED', 'REPLIED' => 'status-responded',
        'CLOSED', 'RESOLVED' => 'status-closed',
        default => 'status-neutral',
    };
}

function enquiryDate($date): string
{
    if (!$date) {
        return '—';
    }

    $timestamp = strtotime((string) $date);

    return $timestamp !== false
        ? date('d M Y', $timestamp)
        : '—';
}

function enquiryDateTime($date): string
{
    if (!$date) {
        return '—';
    }

    $timestamp = strtotime((string) $date);

    return $timestamp !== false
        ? date('d M Y, h:i A', $timestamp)
        : '—';
}

function enquiryMessage(array $enquiry): string
{
    return (string) (
        $enquiry['message']
        ?? $enquiry['inquiry']
        ?? $enquiry['enquiry_message']
        ?? $enquiry['brief']
        ?? ''
    );
}

function enquiryService(array $enquiry): string
{
    return (string) (
        $enquiry['service']
        ?? $enquiry['domain']
        ?? $enquiry['service_name']
        ?? 'General Enquiry'
    );
}

/*
|--------------------------------------------------------------------------
| CSRF token
|--------------------------------------------------------------------------
*/

if (empty($_SESSION['proserve_enquiries_csrf'])) {
    $_SESSION['proserve_enquiries_csrf'] = bin2hex(random_bytes(32));
}

$csrfToken = $_SESSION['proserve_enquiries_csrf'];

/*
|--------------------------------------------------------------------------
| Sample enquiries
|--------------------------------------------------------------------------
| These are loaded only when the session has no enquiry collection.
| Existing records submitted from contact.php are preserved.
*/

if (!isset($_SESSION['proserve_enquiries'])) {
    $_SESSION['proserve_enquiries'] = [
        [
            'reference' => 'ENQ-2026-001',
            'name' => 'Amelia Fernando',
            'email' => 'amelia@example.com',
            'phone' => '+94 77 123 4567',
            'company' => 'Lanka Retail Solutions',
            'service' => 'Custom Web Application Development',
            'subject' => 'Business management system',
            'message' => 'We are interested in developing a business management system to manage customers, sales and inventory. Please contact us to discuss the requirements.',
            'preferred_contact' => 'Email',
            'status' => 'NEW',
            'admin_notes' => '',
            'created_at' => date('Y-m-d H:i:s', strtotime('-1 day')),
        ],
        [
            'reference' => 'ENQ-2026-002',
            'name' => 'Daniel Perera',
            'email' => 'daniel@example.com',
            'phone' => '+94 71 234 5678',
            'company' => 'DP Enterprises',
            'service' => 'E-commerce Development',
            'subject' => 'Online shopping website',
            'message' => 'I would like to create an online shopping platform with product management, a shopping cart, order tracking and an administrator dashboard.',
            'preferred_contact' => 'WhatsApp',
            'status' => 'IN PROGRESS',
            'admin_notes' => 'Initial discussion required.',
            'created_at' => date('Y-m-d H:i:s', strtotime('-3 days')),
        ],
        [
            'reference' => 'ENQ-2026-003',
            'name' => 'Sarah Wijesinghe',
            'email' => 'sarah@example.com',
            'phone' => '+94 76 345 6789',
            'company' => 'BrightPath Academy',
            'service' => 'Database Development',
            'subject' => 'Database design consultation',
            'message' => 'We need assistance designing a structured database for student records, course registrations and academic reports.',
            'preferred_contact' => 'Phone',
            'status' => 'RESPONDED',
            'admin_notes' => 'Initial response sent. Waiting for requirements document.',
            'created_at' => date('Y-m-d H:i:s', strtotime('-5 days')),
        ],
        [
            'reference' => 'ENQ-2026-004',
            'name' => 'Michael Silva',
            'email' => 'michael@example.com',
            'phone' => '+94 75 456 7890',
            'company' => 'MS Consulting',
            'service' => 'API Integration',
            'subject' => 'Third-party API integration',
            'message' => 'Our existing system needs to connect with a third-party API. We would appreciate a technical discussion and an estimated project cost.',
            'preferred_contact' => 'Email',
            'status' => 'CLOSED',
            'admin_notes' => 'Enquiry completed for demonstration purposes.',
            'created_at' => date('Y-m-d H:i:s', strtotime('-12 days')),
        ],
        [
            'reference' => 'ENQ-2026-005',
            'name' => 'Nethmi Jayawardena',
            'email' => 'nethmi@example.com',
            'phone' => '+94 72 567 8901',
            'company' => 'Nethmi Design Studio',
            'service' => 'Website Maintenance',
            'subject' => 'Website maintenance services',
            'message' => 'I need ongoing maintenance, security updates, bug fixes and content updates for my company website.',
            'preferred_contact' => 'Email',
            'status' => 'NEW',
            'admin_notes' => '',
            'created_at' => date('Y-m-d H:i:s', strtotime('-2 hours')),
        ],
        [
            'reference' => 'ENQ-2026-006',
            'name' => 'Kevin Rodrigo',
            'email' => 'kevin@example.com',
            'phone' => '+94 70 678 9012',
            'company' => 'KR Logistics',
            'service' => 'Business Management Systems',
            'subject' => 'Logistics management platform',
            'message' => 'We are exploring a system for managing deliveries, customer records, employees and operational reports.',
            'preferred_contact' => 'Phone',
            'status' => 'IN PROGRESS',
            'admin_notes' => 'Arrange a requirements discussion.',
            'created_at' => date('Y-m-d H:i:s', strtotime('-7 days')),
        ],
    ];
}

/*
|--------------------------------------------------------------------------
| Notifications and redirect
|--------------------------------------------------------------------------
*/

function enquiryRedirect(string $message, string $type = 'success'): void
{
    $_SESSION['proserve_enquiries_notice'] = [
        'message' => $message,
        'type' => $type,
    ];

    header('Location: enquiries.php');
    exit;
}

/*
|--------------------------------------------------------------------------
| POST actions
|--------------------------------------------------------------------------
*/

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $submittedToken = (string) ($_POST['csrf_token'] ?? '');

    if (!hash_equals(
        (string) $_SESSION['proserve_enquiries_csrf'],
        $submittedToken
    )) {
        enquiryRedirect(
            'Your session token is invalid. Please try again.',
            'danger'
        );
    }

    $enquiries = $_SESSION['proserve_enquiries'];
    $action = (string) ($_POST['action'] ?? '');
    $reference = trim((string) ($_POST['reference'] ?? ''));

    if ($action === 'update') {
        $allowedStatuses = [
            'NEW',
            'IN PROGRESS',
            'RESPONDED',
            'CLOSED',
        ];

        $newStatus = strtoupper(trim((string) ($_POST['status'] ?? 'NEW')));
        $adminNotes = trim((string) ($_POST['admin_notes'] ?? ''));

        if (!in_array($newStatus, $allowedStatuses, true)) {
            enquiryRedirect('Please select a valid enquiry status.', 'danger');
        }

        if (strlen($adminNotes) > 3000) {
            enquiryRedirect('Internal notes must not exceed 3000 characters.', 'danger');
        }

        $found = false;

        foreach ($enquiries as $index => $enquiry) {
            if (enquiryReference($enquiry) === $reference) {
                $enquiries[$index]['status'] = $newStatus;
                $enquiries[$index]['admin_notes'] = $adminNotes;
                $enquiries[$index]['updated_at'] = date('Y-m-d H:i:s');

                if ($newStatus === 'RESPONDED') {
                    $enquiries[$index]['responded_at'] = date('Y-m-d H:i:s');
                }

                if ($newStatus === 'CLOSED') {
                    $enquiries[$index]['closed_at'] = date('Y-m-d H:i:s');
                }

                $found = true;
                break;
            }
        }

        if (!$found) {
            enquiryRedirect('The selected enquiry could not be found.', 'danger');
        }

        $_SESSION['proserve_enquiries'] = array_values($enquiries);

        enquiryRedirect('Enquiry details updated successfully.');
    }

    if ($action === 'delete') {
        $found = false;

        foreach ($enquiries as $index => $enquiry) {
            if (enquiryReference($enquiry) === $reference) {
                array_splice($enquiries, $index, 1);
                $found = true;
                break;
            }
        }

        if (!$found) {
            enquiryRedirect('The selected enquiry could not be found.', 'danger');
        }

        $_SESSION['proserve_enquiries'] = array_values($enquiries);

        enquiryRedirect('Enquiry deleted successfully.');
    }

    enquiryRedirect('Unknown action. Please try again.', 'danger');
}

/*
|--------------------------------------------------------------------------
| Search and filters
|--------------------------------------------------------------------------
*/

$enquiries = $_SESSION['proserve_enquiries'];

$search = trim((string) ($_GET['search'] ?? ''));
$statusFilter = strtoupper(trim((string) ($_GET['status'] ?? '')));
$serviceFilter = trim((string) ($_GET['service'] ?? ''));
$sort = (string) ($_GET['sort'] ?? 'newest');

$allowedStatuses = ['NEW', 'IN PROGRESS', 'RESPONDED', 'CLOSED'];

$services = [];

foreach ($enquiries as $enquiry) {
    $service = enquiryService($enquiry);

    if ($service !== '') {
        $services[] = $service;
    }
}

$services = array_values(array_unique($services));
sort($services, SORT_NATURAL | SORT_FLAG_CASE);

$filteredEnquiries = array_values(array_filter(
    $enquiries,
    function ($enquiry) use ($search, $statusFilter, $serviceFilter) {
        $searchable = implode(' ', [
            enquiryReference($enquiry),
            enquiryCustomerName($enquiry),
            $enquiry['email'] ?? '',
            $enquiry['phone'] ?? '',
            $enquiry['company'] ?? '',
            $enquiry['subject'] ?? '',
            enquiryService($enquiry),
            enquiryMessage($enquiry),
        ]);

        if ($search !== '' && stripos($searchable, $search) === false) {
            return false;
        }

        if (
            $statusFilter !== '' &&
            strtoupper((string) ($enquiry['status'] ?? 'NEW')) !== $statusFilter
        ) {
            return false;
        }

        if (
            $serviceFilter !== '' &&
            enquiryService($enquiry) !== $serviceFilter
        ) {
            return false;
        }

        return true;
    }
));

if ($sort === 'oldest') {
    usort($filteredEnquiries, fn($a, $b) =>
        strcmp($a['created_at'] ?? '', $b['created_at'] ?? '')
    );
} elseif ($sort === 'name') {
    usort($filteredEnquiries, fn($a, $b) =>
        strcasecmp(enquiryCustomerName($a), enquiryCustomerName($b))
    );
} else {
    usort($filteredEnquiries, fn($a, $b) =>
        strcmp($b['created_at'] ?? '', $a['created_at'] ?? '')
    );
}

/*
|--------------------------------------------------------------------------
| Summary statistics
|--------------------------------------------------------------------------
*/

$totalEnquiries = count($enquiries);
$newEnquiries = 0;
$inProgressEnquiries = 0;
$respondedEnquiries = 0;
$closedEnquiries = 0;

foreach ($enquiries as $enquiry) {
    $status = strtoupper((string) ($enquiry['status'] ?? 'NEW'));

    if (in_array($status, ['NEW', 'NEW LEAD'], true)) {
        $newEnquiries++;
    } elseif (in_array($status, ['IN PROGRESS', 'FOLLOW UP'], true)) {
        $inProgressEnquiries++;
    } elseif (in_array($status, ['RESPONDED', 'REPLIED'], true)) {
        $respondedEnquiries++;
    } elseif (in_array($status, ['CLOSED', 'RESOLVED'], true)) {
        $closedEnquiries++;
    }
}

$notice = $_SESSION['proserve_enquiries_notice'] ?? null;
unset($_SESSION['proserve_enquiries_notice']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Enquiry Management | ProServe CRM</title>

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
            --background: #f5f7fb;
            --muted: #718096;
            --border: #e8edf5;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: var(--background);
            color: #243047;
            font-family: 'Inter', 'Trebuchet MS', sans-serif;
            font-size: 14px;
        }

        a {
            text-decoration: none;
        }

        .sidebar {
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            width: 252px;
            padding: 25px 16px;
            background: var(--navy);
            color: #fff;
            overflow-y: auto;
            z-index: 1000;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 0 10px 28px;
            color: #fff;
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

        .brand strong {
            display: block;
            font-size: 21px;
            letter-spacing: -0.7px;
        }

        .brand small {
            color: #a9b9d2;
            font-size: 11px;
        }

        .nav-label {
            margin: 18px 12px 9px;
            color: #8194b1;
            font-size: 10px;
            font-weight: 800;
            letter-spacing: 1.4px;
            text-transform: uppercase;
        }

        .sidebar .nav-link {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 4px;
            padding: 11px 13px;
            border-radius: 9px;
            color: #b8c5d9;
            font-size: 13px;
            font-weight: 500;
            transition: 0.2s;
        }

        .sidebar .nav-link i {
            width: 19px;
            font-size: 16px;
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background: rgba(65, 119, 235, 0.2);
            color: #fff;
        }

        .sidebar .nav-link.active {
            box-shadow: inset 3px 0 #60a5fa;
        }

        .main-content {
            margin-left: 252px;
            min-height: 100vh;
        }

        .topbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 15px;
            min-height: 78px;
            padding: 16px 32px;
            background: #fff;
            border-bottom: 1px solid var(--border);
        }

        .breadcrumb-text {
            color: var(--muted);
            font-size: 12px;
        }

        .topbar-title {
            margin: 3px 0 0;
            font-size: 19px;
            font-weight: 800;
        }

        .admin-avatar {
            display: grid;
            place-items: center;
            width: 39px;
            height: 39px;
            border-radius: 50%;
            background: #e9efff;
            color: var(--primary);
            font-weight: 800;
        }

        .content {
            padding: 30px 32px 24px;
        }

        .page-heading {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 20px;
            margin-bottom: 25px;
        }

        .page-heading h1 {
            margin: 0 0 8px;
            color: #17253d;
            font-size: 26px;
            font-weight: 800;
            letter-spacing: -0.8px;
        }

        .page-heading p {
            margin: 0;
            color: var(--muted);
            line-height: 1.6;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 17px;
            margin-bottom: 24px;
        }

        .stat-card {
            display: flex;
            align-items: center;
            gap: 15px;
            min-width: 0;
            padding: 20px;
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 14px;
            box-shadow: 0 3px 12px rgba(25, 42, 70, 0.025);
        }

        .stat-icon {
            display: grid;
            place-items: center;
            flex-shrink: 0;
            width: 48px;
            height: 48px;
            border-radius: 13px;
            font-size: 21px;
        }

        .stat-icon.blue {
            background: #eaf0ff;
            color: #3768e8;
        }

        .stat-icon.orange {
            background: #fff3df;
            color: #d88712;
        }

        .stat-icon.purple {
            background: #f1eaff;
            color: #8254d9;
        }

        .stat-icon.green {
            background: #e5f8ef;
            color: #13945c;
        }

        .stat-label {
            margin-bottom: 5px;
            color: var(--muted);
            font-size: 12px;
        }

        .stat-value {
            color: #182943;
            font-size: 27px;
            font-weight: 800;
            letter-spacing: -0.5px;
        }

        .stat-foot {
            margin-top: 4px;
            color: #8692a6;
            font-size: 10px;
        }

        .panel {
            margin-bottom: 23px;
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 14px;
            box-shadow: 0 3px 12px rgba(25, 42, 70, 0.025);
        }

        .panel-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 15px;
            padding: 20px 23px;
            border-bottom: 1px solid var(--border);
        }

        .panel-header h2 {
            margin: 0;
            font-size: 15px;
            font-weight: 800;
        }

        .panel-header p {
            margin: 5px 0 0;
            color: var(--muted);
            font-size: 12px;
        }

        .filter-body {
            padding: 20px 23px;
        }

        .form-label {
            margin-bottom: 7px;
            color: #526079;
            font-size: 12px;
            font-weight: 700;
        }

        .form-control,
        .form-select {
            min-height: 42px;
            border-color: #e1e7f0;
            border-radius: 8px;
            font-size: 12px;
            box-shadow: none !important;
        }

        textarea.form-control {
            min-height: 100px;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #8eb0ff;
        }

        .table-responsive {
            border-radius: 0 0 14px 14px;
        }

        .enquiry-table {
            min-width: 1050px;
            margin: 0;
            vertical-align: middle;
        }

        .enquiry-table thead th {
            padding: 14px 18px;
            background: #f9faff;
            border-bottom: 1px solid var(--border);
            color: #78859a;
            font-size: 10px;
            font-weight: 800;
            letter-spacing: 0.65px;
            text-transform: uppercase;
            white-space: nowrap;
        }

        .enquiry-table tbody td {
            padding: 16px 18px;
            border-color: #eef1f6;
            color: #44516a;
            font-size: 12px;
        }

        .enquiry-table tbody tr:hover {
            background: #fbfcff;
        }

        .customer-info {
            display: flex;
            align-items: center;
            gap: 11px;
            min-width: 190px;
        }

        .customer-avatar {
            display: grid;
            place-items: center;
            flex-shrink: 0;
            width: 39px;
            height: 39px;
            border-radius: 11px;
            background: #edf2ff;
            color: #3266e8;
            font-size: 12px;
            font-weight: 800;
        }

        .customer-name {
            color: #1d2d46;
            font-weight: 800;
            font-size: 12px;
        }

        .customer-sub {
            margin-top: 4px;
            color: #8a96a8;
            font-size: 10px;
        }

        .subject-text {
            max-width: 230px;
            overflow: hidden;
            color: #2d3a50;
            font-weight: 700;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .service-pill {
            display: inline-block;
            max-width: 190px;
            padding: 6px 9px;
            border-radius: 6px;
            background: #f0f4fa;
            color: #56647b;
            font-size: 10px;
            font-weight: 700;
            white-space: normal;
        }

        .status-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 9px;
            border-radius: 30px;
            font-size: 10px;
            font-weight: 800;
            white-space: nowrap;
        }

        .status-pill::before {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: currentColor;
            content: "";
        }

        .status-new {
            background: #fff3df;
            color: #a96709;
        }

        .status-progress {
            background: #eaf0ff;
            color: #2856c6;
        }

        .status-responded {
            background: #e8f4ff;
            color: #1466a8;
        }

        .status-closed {
            background: #e5f8ef;
            color: #14804a;
        }

        .status-neutral {
            background: #edf0f5;
            color: #68758a;
        }

        .action-group {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .action-btn {
            display: inline-grid;
            place-items: center;
            width: 32px;
            height: 32px;
            padding: 0;
            border: 1px solid #e5eaf2;
            border-radius: 8px;
            background: #fff;
            color: #64728a;
            font-size: 14px;
            cursor: pointer;
        }

        .action-btn:hover {
            border-color: #c8d6ff;
            background: #eff4ff;
            color: var(--primary);
        }

        .action-btn.danger:hover {
            border-color: #ffd1d1;
            background: #fff0f0;
            color: #d92d20;
        }

        .table-footer {
            display: flex;
            justify-content: space-between;
            gap: 15px;
            padding: 16px 22px;
            color: #7b879b;
            font-size: 11px;
        }

        .empty-state {
            padding: 50px 20px !important;
            text-align: center;
        }

        .empty-state i {
            display: block;
            margin-bottom: 12px;
            color: #b7c4d8;
            font-size: 36px;
        }

        .empty-state strong {
            display: block;
            margin-bottom: 6px;
            color: #263751;
            font-size: 14px;
        }

        .empty-state span {
            color: #8490a3;
        }

        .modal-content {
            border: 0;
            border-radius: 15px;
            box-shadow: 0 20px 60px rgba(15, 35, 65, 0.2);
        }

        .modal-header {
            padding: 21px 24px;
            border-bottom-color: var(--border);
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
            border-top-color: var(--border);
        }

        .detail-label {
            margin-bottom: 5px;
            color: #8591a5;
            font-size: 10px;
            font-weight: 800;
            letter-spacing: 0.6px;
            text-transform: uppercase;
        }

        .detail-value {
            color: #2a3951;
            font-size: 12px;
            font-weight: 600;
            overflow-wrap: anywhere;
        }

        .message-box {
            padding: 16px;
            border: 1px solid #edf0f6;
            border-radius: 10px;
            background: #f8faff;
            color: #4a5870;
            font-size: 12px;
            line-height: 1.8;
            overflow-wrap: anywhere;
            white-space: pre-wrap;
        }

        .notes-box {
            padding: 14px;
            border: 1px solid #f6dfb7;
            border-radius: 10px;
            background: #fff9ed;
            color: #74521c;
            font-size: 12px;
            line-height: 1.7;
            white-space: pre-wrap;
            overflow-wrap: anywhere;
        }

        .demo-note {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            padding: 13px 15px;
            border: 1px solid #dbe7ff;
            border-radius: 10px;
            background: #f0f5ff;
            color: #4d638d;
            font-size: 11px;
            line-height: 1.6;
        }

        .footer {
            padding: 20px 32px;
            border-top: 1px solid var(--border);
            color: #8490a3;
            font-size: 11px;
        }

        .footer strong {
            color: #506079;
        }

        @media (max-width: 1250px) {
            .stats-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .content {
                padding-right: 22px;
                padding-left: 22px;
            }
        }

        @media (max-width: 900px) {
            .sidebar {
                position: static;
                width: 100%;
                padding: 14px;
            }

            .brand {
                padding-bottom: 10px;
            }

            .sidebar nav {
                display: grid;
                grid-template-columns: repeat(3, minmax(0, 1fr));
                gap: 4px;
            }

            .nav-label {
                margin-top: 12px;
            }

            .sidebar .nav-link {
                margin: 0;
                padding: 10px;
            }

            .main-content {
                margin-left: 0;
            }

            .topbar {
                padding: 15px 22px;
            }
        }

        @media (max-width: 600px) {
            .sidebar nav {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .content {
                padding: 22px 13px;
            }

            .topbar {
                padding: 15px;
            }

            .page-heading {
                flex-direction: column;
            }

            .page-heading h1 {
                font-size: 23px;
            }

            .stats-grid {
                gap: 10px;
            }

            .stat-card {
                gap: 10px;
                padding: 13px;
            }

            .stat-icon {
                width: 38px;
                height: 38px;
                font-size: 17px;
            }

            .stat-value {
                font-size: 22px;
            }

            .stat-label {
                font-size: 10px;
            }

            .panel-header,
            .filter-body {
                padding: 16px;
            }

            .footer {
                padding: 18px 15px;
            }
        }
    </style>
</head>

<body>

<!-- Sidebar -->
<aside class="sidebar">
    <a href="index.php" class="brand">
        <span class="brand-icon"><i class="bi bi-grid-1x2-fill"></i></span>
        <span>
            <strong>ProServe</strong>
            <small>CRM Administration</small>
        </span>
    </a>

    <nav>
        <div class="nav-label">Main Menu</div>

        <a href="index.php" class="nav-link">
            <i class="bi bi-speedometer2"></i> Dashboard
        </a>

        <a href="customers.php" class="nav-link">
            <i class="bi bi-people"></i> Customers
        </a>

        <a href="leads.php" class="nav-link">
            <i class="bi bi-funnel"></i> Leads
        </a>

        <a href="appointments.php" class="nav-link">
            <i class="bi bi-calendar-check"></i> Appointments
        </a>

        <div class="nav-label">Business Management</div>

        <a href="services.php" class="nav-link">
            <i class="bi bi-briefcase"></i> Services
        </a>

        <a href="quotations.php" class="nav-link">
            <i class="bi bi-file-earmark-text"></i> Quotations
        </a>

        <a href="invoices.php" class="nav-link">
            <i class="bi bi-receipt"></i> Invoices
        </a>

        <a href="employees.php" class="nav-link">
            <i class="bi bi-person-badge"></i> Employees
        </a>

        <a href="enquiries.php" class="nav-link active">
            <i class="bi bi-chat-left-text"></i> Enquiries
        </a>

        <div class="nav-label">Insights & Settings</div>

        <a href="reports.php" class="nav-link">
            <i class="bi bi-bar-chart-line"></i> Reports
        </a>

        <a href="settings.php" class="nav-link">
            <i class="bi bi-gear"></i> Settings
        </a>

        <div class="nav-label">Customer Website</div>

        <a href="../index.php" class="nav-link">
            <i class="bi bi-box-arrow-up-right"></i> View Website
        </a>
    </nav>
</aside>

<!-- Main Content -->
<main class="main-content">

    <header class="topbar">
        <div>
            <div class="breadcrumb-text">ProServe / Business Management / Enquiries</div>
            <h2 class="topbar-title">Enquiry Management</h2>
        </div>

        <div class="d-flex align-items-center gap-3">
            <div class="text-end d-none d-sm-block">
                <div class="fw-bold" style="font-size:12px;">Administrator</div>
                <div class="text-secondary" style="font-size:10px;">System Management</div>
            </div>
            <div class="admin-avatar">AD</div>
        </div>
    </header>

    <div class="content">

        <!-- Page heading -->
        <div class="page-heading">
            <div>
                <h1>Customer Enquiries</h1>
                <p>Review customer messages, manage follow-ups and track enquiry progress.</p>
            </div>
        </div>

        <!-- Notification -->
        <?php if ($notice): ?>
            <div class="alert alert-<?= e($notice['type'] ?? 'success') ?> alert-dismissible fade show mb-4" role="alert">
                <i class="bi bi-info-circle me-2"></i>
                <?= e($notice['message'] ?? '') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <!-- Statistics -->
        <section class="stats-grid">

            <div class="stat-card">
                <div class="stat-icon blue">
                    <i class="bi bi-chat-square-text-fill"></i>
                </div>
                <div>
                    <div class="stat-label">Total Enquiries</div>
                    <div class="stat-value"><?= number_format($totalEnquiries) ?></div>
                    <div class="stat-foot">All submitted enquiries</div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon orange">
                    <i class="bi bi-bell-fill"></i>
                </div>
                <div>
                    <div class="stat-label">New Enquiries</div>
                    <div class="stat-value"><?= number_format($newEnquiries) ?></div>
                    <div class="stat-foot">Waiting for review</div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon purple">
                    <i class="bi bi-arrow-repeat"></i>
                </div>
                <div>
                    <div class="stat-label">In Progress</div>
                    <div class="stat-value"><?= number_format($inProgressEnquiries) ?></div>
                    <div class="stat-foot">Follow-up required</div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon green">
                    <i class="bi bi-check2-circle"></i>
                </div>
                <div>
                    <div class="stat-label">Closed Enquiries</div>
                    <div class="stat-value"><?= number_format($closedEnquiries) ?></div>
                    <div class="stat-foot">Marked as completed</div>
                </div>
            </div>

        </section>

        <!-- Demo notice -->
        <div class="demo-note mb-4">
            <i class="bi bi-info-circle-fill fs-5"></i>
            <div>
                <strong>Portfolio demonstration:</strong>
                Enquiry records and administrator updates are stored in PHP sessions.
                Status changes and internal notes do not send emails or messages automatically.
            </div>
        </div>

        <!-- Search and filters -->
        <section class="panel">
            <div class="panel-header">
                <div>
                    <h2><i class="bi bi-funnel me-2 text-primary"></i>Search & Filter</h2>
                    <p>Find enquiries by reference, customer, company, subject or requested service.</p>
                </div>
            </div>

            <div class="filter-body">
                <form method="GET" action="enquiries.php">
                    <div class="row g-3 align-items-end">

                        <div class="col-lg-4 col-md-6">
                            <label for="search" class="form-label">Search Enquiries</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0" style="border-color:#e1e7f0;">
                                    <i class="bi bi-search text-secondary"></i>
                                </span>
                                <input
                                    type="text"
                                    class="form-control border-start-0"
                                    id="search"
                                    name="search"
                                    value="<?= e($search) ?>"
                                    placeholder="Name, email, subject, reference..."
                                >
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6">
                            <label for="status" class="form-label">Enquiry Status</label>
                            <select name="status" id="status" class="form-select">
                                <option value="">All Statuses</option>
                                <?php foreach ($allowedStatuses as $status): ?>
                                    <option
                                        value="<?= e($status) ?>"
                                        <?= $statusFilter === $status ? 'selected' : '' ?>
                                    >
                                        <?= e(ucwords(strtolower($status))) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-lg-3 col-md-6">
                            <label for="service" class="form-label">Requested Service</label>
                            <select name="service" id="service" class="form-select">
                                <option value="">All Services</option>
                                <?php foreach ($services as $service): ?>
                                    <option
                                        value="<?= e($service) ?>"
                                        <?= $serviceFilter === $service ? 'selected' : '' ?>
                                    >
                                        <?= e($service) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-lg-2 col-md-6">
                            <label for="sort" class="form-label">Sort By</label>
                            <select name="sort" id="sort" class="form-select">
                                <option value="newest" <?= $sort === 'newest' ? 'selected' : '' ?>>Newest First</option>
                                <option value="oldest" <?= $sort === 'oldest' ? 'selected' : '' ?>>Oldest First</option>
                                <option value="name" <?= $sort === 'name' ? 'selected' : '' ?>>Customer Name</option>
                            </select>
                        </div>

                        <div class="col-12 d-flex justify-content-end gap-2">
                            <a href="enquiries.php" class="btn btn-light border">
                                <i class="bi bi-arrow-counterclockwise me-1"></i> Reset
                            </a>
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="bi bi-search me-1"></i> Apply Filters
                            </button>
                        </div>

                    </div>
                </form>
            </div>
        </section>

        <!-- Enquiry table -->
        <section class="panel">

            <div class="panel-header">
                <div>
                    <h2>Enquiry Inbox</h2>
                    <p>Review messages and manage customer follow-ups.</p>
                </div>

                <span class="badge rounded-pill text-bg-light border px-3 py-2">
                    <?= count($filteredEnquiries) ?> result<?= count($filteredEnquiries) === 1 ? '' : 's' ?>
                </span>
            </div>

            <div class="table-responsive">
                <table class="table enquiry-table">
                    <thead>
                    <tr>
                        <th>Customer</th>
                        <th>Subject / Reference</th>
                        <th>Requested Service</th>
                        <th>Received</th>
                        <th>Preferred Contact</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                    </thead>

                    <tbody>
                    <?php if (empty($filteredEnquiries)): ?>

                        <tr>
                            <td colspan="7" class="empty-state">
                                <i class="bi bi-inbox"></i>
                                <strong>No enquiries found</strong>
                                <span>Try different filters or check back when new enquiries arrive.</span>
                            </td>
                        </tr>

                    <?php else: ?>

                        <?php foreach ($filteredEnquiries as $enquiry): ?>
                            <?php
                                $reference = enquiryReference($enquiry);
                                $customerName = enquiryCustomerName($enquiry);
                                $status = strtoupper((string) ($enquiry['status'] ?? 'NEW'));
                                $preferredContact = $enquiry['preferred_contact']
                                    ?? $enquiry['contact_preference']
                                    ?? 'Not specified';
                                $company = $enquiry['company'] ?? '';
                                $email = $enquiry['email'] ?? '';
                                $phone = $enquiry['phone'] ?? '';
                                $subject = $enquiry['subject'] ?? 'General Enquiry';
                            ?>

                            <tr>
                                <td>
                                    <div class="customer-info">
                                        <div class="customer-avatar">
                                            <?= e(implode('', array_map(
                                                fn($part) => strtoupper(substr($part, 0, 1)),
                                                array_slice(
                                                    preg_split('/\s+/', trim($customerName)),
                                                    0,
                                                    2
                                                )
                                            ))) ?>
                                        </div>

                                        <div>
                                            <div class="customer-name">
                                                <?= e($customerName) ?>
                                            </div>
                                            <div class="customer-sub">
                                                <?= e($email) ?>
                                            </div>
                                            <?php if ($company !== ''): ?>
                                                <div class="customer-sub">
                                                    <i class="bi bi-building me-1"></i><?= e($company) ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </td>

                                <td>
                                    <div class="subject-text" title="<?= e($subject) ?>">
                                        <?= e($subject) ?>
                                    </div>
                                    <div class="customer-sub"><?= e($reference) ?></div>
                                </td>

                                <td>
                                    <span class="service-pill">
                                        <?= e(enquiryService($enquiry)) ?>
                                    </span>
                                </td>

                                <td>
                                    <div class="fw-semibold" style="font-size:11px;">
                                        <?= e(enquiryDate($enquiry['created_at'] ?? '')) ?>
                                    </div>
                                    <div class="customer-sub">
                                        <?= e(!empty($enquiry['created_at']) && strtotime((string) $enquiry['created_at']) !== false
                                            ? date('h:i A', strtotime((string) $enquiry['created_at']))
                                            : '') ?>
                                    </div>
                                </td>

                                <td>
                                    <div class="fw-semibold" style="font-size:11px;">
                                        <?= e($preferredContact) ?>
                                    </div>
                                    <div class="customer-sub"><?= e($phone) ?></div>
                                </td>

                                <td>
                                    <span class="status-pill <?= e(enquiryStatusClass($status)) ?>">
                                        <?= e(ucwords(strtolower($status))) ?>
                                    </span>
                                </td>

                                <td>
                                    <div class="action-group">

                                        <button
                                            type="button"
                                            class="action-btn"
                                            title="View enquiry"
                                            data-enquiry="<?= e(json_encode($enquiry, JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_TAG | JSON_HEX_AMP)) ?>"
                                            onclick="viewEnquiry(this)"
                                            data-bs-toggle="modal"
                                            data-bs-target="#viewEnquiryModal"
                                        >
                                            <i class="bi bi-eye"></i>
                                        </button>

                                        <button
                                            type="button"
                                            class="action-btn"
                                            title="Manage enquiry"
                                            data-enquiry="<?= e(json_encode($enquiry, JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_TAG | JSON_HEX_AMP)) ?>"
                                            onclick="editEnquiry(this)"
                                        >
                                            <i class="bi bi-pencil-square"></i>
                                        </button>

                                        <form method="POST" action="enquiries.php" class="d-inline">
                                            <input type="hidden" name="csrf_token" value="<?= e($csrfToken) ?>">
                                            <input type="hidden" name="action" value="delete">
                                            <input type="hidden" name="reference" value="<?= e($reference) ?>">

                                            <button
                                                type="submit"
                                                class="action-btn danger"
                                                title="Delete enquiry"
                                                onclick="return confirm('Delete enquiry <?= e($reference) ?>? This cannot be undone within the current session.');"
                                            >
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
                <span>
                    Showing <?= count($filteredEnquiries) ?> of <?= $totalEnquiries ?> enquiries
                </span>
                <span><i class="bi bi-shield-check me-1"></i> Demo session data</span>
            </div>

        </section>

    </div>

    <footer class="footer d-flex flex-wrap justify-content-between gap-2">
        <span><strong>ProServe</strong> — CRM & Service Management System</span>
        <span>Portfolio demonstration by <strong>Codvanta</strong>.</span>
    </footer>

</main>

<!-- View Enquiry Modal -->
<div class="modal fade" id="viewEnquiryModal" tabindex="-1" aria-labelledby="viewEnquiryTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">

            <div class="modal-header">
                <div>
                    <h5 class="modal-title" id="viewEnquiryTitle">Enquiry Details</h5>
                    <div class="text-secondary mt-1" style="font-size:11px;">
                        Customer message and follow-up information.
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">

                <div class="d-flex flex-wrap align-items-start justify-content-between gap-3 mb-4">
                    <div>
                        <div class="detail-label">Enquiry Reference</div>
                        <div class="detail-value" id="viewReference">—</div>
                    </div>
                    <div>
                        <div class="detail-label">Current Status</div>
                        <span class="status-pill status-new" id="viewStatus">NEW</span>
                    </div>
                </div>

                <div class="row g-4 mb-4">
                    <div class="col-md-6">
                        <div class="detail-label">Customer Name</div>
                        <div class="detail-value" id="viewCustomer">—</div>
                    </div>

                    <div class="col-md-6">
                        <div class="detail-label">Company</div>
                        <div class="detail-value" id="viewCompany">—</div>
                    </div>

                    <div class="col-md-6">
                        <div class="detail-label">Email Address</div>
                        <div class="detail-value" id="viewEmail">—</div>
                    </div>

                    <div class="col-md-6">
                        <div class="detail-label">Phone Number</div>
                        <div class="detail-value" id="viewPhone">—</div>
                    </div>

                    <div class="col-md-6">
                        <div class="detail-label">Requested Service</div>
                        <div class="detail-value" id="viewService">—</div>
                    </div>

                    <div class="col-md-6">
                        <div class="detail-label">Preferred Contact</div>
                        <div class="detail-value" id="viewPreferredContact">—</div>
                    </div>

                    <div class="col-12">
                        <div class="detail-label">Subject</div>
                        <div class="detail-value" id="viewSubject">—</div>
                    </div>

                    <div class="col-12">
                        <div class="detail-label">Date Received</div>
                        <div class="detail-value" id="viewCreatedAt">—</div>
                    </div>
                </div>

                <div class="mb-4">
                    <div class="detail-label mb-2">Customer Message</div>
                    <div class="message-box" id="viewMessage">No message supplied.</div>
                </div>

                <div>
                    <div class="detail-label mb-2">Internal Notes</div>
                    <div class="notes-box" id="viewAdminNotes">No internal notes.</div>
                </div>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="editFromViewButton">
                    <i class="bi bi-pencil-square me-1"></i> Manage Enquiry
                </button>
            </div>

        </div>
    </div>
</div>

<!-- Update Enquiry Modal -->
<div class="modal fade" id="editEnquiryModal" tabindex="-1" aria-labelledby="editEnquiryTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">

            <form method="POST" action="enquiries.php" id="editEnquiryForm">
                <input type="hidden" name="csrf_token" value="<?= e($csrfToken) ?>">
                <input type="hidden" name="action" value="update">
                <input type="hidden" name="reference" id="editReference">

                <div class="modal-header">
                    <div>
                        <h5 class="modal-title" id="editEnquiryTitle">Manage Enquiry</h5>
                        <div class="text-secondary mt-1" style="font-size:11px;">
                            Update the enquiry status and record internal follow-up notes.
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">

                    <div class="mb-4 p-3 rounded-3" style="background:#f7f9fc;">
                        <div class="detail-label">Selected Enquiry</div>
                        <div class="fw-bold mb-1" id="editCustomerSummary">—</div>
                        <div class="text-secondary" style="font-size:11px;" id="editReferenceSummary">—</div>
                        <div class="mt-2" style="font-size:12px;" id="editSubjectSummary">—</div>
                    </div>

                    <div class="mb-4">
                        <label for="editStatus" class="form-label">Enquiry Status *</label>
                        <select class="form-select" id="editStatus" name="status" required>
                            <option value="NEW">New</option>
                            <option value="IN PROGRESS">In Progress</option>
                            <option value="RESPONDED">Responded</option>
                            <option value="CLOSED">Closed</option>
                        </select>

                        <div class="form-text">
                            Use In Progress while following up, Responded after replying, and Closed when completed.
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="editAdminNotes" class="form-label">Internal Notes</label>
                        <textarea
                            class="form-control"
                            id="editAdminNotes"
                            name="admin_notes"
                            maxlength="3000"
                            rows="5"
                            placeholder="Record follow-up actions, discussion notes or next steps..."
                        ></textarea>
                    </div>

                    <div class="demo-note">
                        <i class="bi bi-info-circle"></i>
                        <div>
                            Internal notes are for the administrator's reference in this demonstration.
                            They are not sent to the customer.
                        </div>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="bi bi-check2-circle me-1"></i> Save Changes
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    let selectedEnquiry = null;

    const viewModalElement = document.getElementById('viewEnquiryModal');
    const editModalElement = document.getElementById('editEnquiryModal');

    function safeText(value, fallback = '—') {
        return value === null || value === undefined || String(value).trim() === ''
            ? fallback
            : String(value);
    }

    function formatDateTime(value) {
        if (!value) {
            return '—';
        }

        const date = new Date(String(value).replace(' ', 'T'));

        if (Number.isNaN(date.getTime())) {
            return String(value);
        }

        return date.toLocaleString('en-GB', {
            day: '2-digit',
            month: 'short',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit',
            hour12: true
        });
    }

    function getEnquiryFromButton(button) {
        try {
            return JSON.parse(button.dataset.enquiry);
        } catch (error) {
            alert('Unable to load this enquiry.');
            return null;
        }
    }

    function getReference(enquiry) {
        return enquiry.reference
            || enquiry.enquiry_reference
            || 'ENQ-000';
    }

    function getCustomerName(enquiry) {
        return enquiry.name
            || enquiry.full_name
            || enquiry.customer_name
            || 'Unknown Customer';
    }

    function getService(enquiry) {
        return enquiry.service
            || enquiry.domain
            || enquiry.service_name
            || 'General Enquiry';
    }

    function getMessage(enquiry) {
        return enquiry.message
            || enquiry.inquiry
            || enquiry.enquiry_message
            || enquiry.brief
            || '';
    }

    function getPreferredContact(enquiry) {
        return enquiry.preferred_contact
            || enquiry.contact_preference
            || 'Not specified';
    }

    function statusClass(status) {
        switch (String(status || '').toUpperCase()) {
            case 'NEW':
            case 'NEW LEAD':
                return 'status-new';

            case 'IN PROGRESS':
            case 'FOLLOW UP':
                return 'status-progress';

            case 'RESPONDED':
            case 'REPLIED':
                return 'status-responded';

            case 'CLOSED':
            case 'RESOLVED':
                return 'status-closed';

            default:
                return 'status-neutral';
        }
    }

    function viewEnquiry(button) {
        const enquiry = getEnquiryFromButton(button);

        if (!enquiry) {
            return;
        }

        selectedEnquiry = enquiry;

        const setText = (id, value, fallback = '—') => {
            document.getElementById(id).textContent = safeText(value, fallback);
        };

        const reference = getReference(enquiry);
        const customerName = getCustomerName(enquiry);
        const status = String(enquiry.status || 'NEW').toUpperCase();

        setText('viewReference', reference);
        setText('viewCustomer', customerName);
        setText('viewCompany', enquiry.company);
        setText('viewEmail', enquiry.email);
        setText('viewPhone', enquiry.phone);
        setText('viewService', getService(enquiry));
        setText('viewPreferredContact', getPreferredContact(enquiry));
        setText('viewSubject', enquiry.subject || 'General Enquiry');
        setText('viewCreatedAt', formatDateTime(enquiry.created_at));
        setText('viewMessage', getMessage(enquiry), 'No message supplied.');
        setText('viewAdminNotes', enquiry.admin_notes, 'No internal notes.');

        const statusElement = document.getElementById('viewStatus');
        statusElement.textContent = status;
        statusElement.className = 'status-pill ' + statusClass(status);

        document.getElementById('editFromViewButton').onclick = function () {
            bootstrap.Modal.getOrCreateInstance(viewModalElement).hide();

            window.setTimeout(function () {
                openEditModal(enquiry);
            }, 200);
        };
    }

    function editEnquiry(button) {
        const enquiry = getEnquiryFromButton(button);

        if (!enquiry) {
            return;
        }

        openEditModal(enquiry);
    }

    function openEditModal(enquiry) {
        selectedEnquiry = enquiry;

        document.getElementById('editReference').value = getReference(enquiry);
        document.getElementById('editCustomerSummary').textContent =
            getCustomerName(enquiry);
        document.getElementById('editReferenceSummary').textContent =
            getReference(enquiry);
        document.getElementById('editSubjectSummary').textContent =
            enquiry.subject || 'General Enquiry';

        const currentStatus = String(enquiry.status || 'NEW').toUpperCase();
        const statusSelect = document.getElementById('editStatus');

        const validStatuses = [
            'NEW',
            'IN PROGRESS',
            'RESPONDED',
            'CLOSED'
        ];

        statusSelect.value = validStatuses.includes(currentStatus)
            ? currentStatus
            : 'NEW';

        document.getElementById('editAdminNotes').value =
            enquiry.admin_notes || '';

        bootstrap.Modal.getOrCreateInstance(editModalElement).show();
    }
</script>

</body>
</html>