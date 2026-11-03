<?php
session_start();

/*
|--------------------------------------------------------------------------
| Azure Bay Resort - Guest Services Management
|--------------------------------------------------------------------------
| Demonstration features:
| - Create and manage guest service requests
| - Search and filter requests
| - Assign staff members
| - Update request status and priority
| - Track service charges
|
| This page uses PHP session data and does not require MySQL.
|--------------------------------------------------------------------------
*/

if (!isset($_SESSION['azurebay_admin_guest_services'])) {
    $_SESSION['azurebay_admin_guest_services'] = [
        [
            'id' => 5001,
            'request_code' => 'SRV-5001',
            'guest_name' => 'Olivia Bennett',
            'room_number' => 'A-101',
            'category' => 'Housekeeping',
            'service_name' => 'Extra Towels and Toiletries',
            'description' => 'Guest requested additional bath towels and toiletries.',
            'priority' => 'Normal',
            'status' => 'Completed',
            'assigned_to' => 'Maria Silva',
            'requested_at' => date('Y-m-d\TH:i', strtotime('-1 day')),
            'due_at' => date('Y-m-d\TH:i', strtotime('-1 day + 1 hour')),
            'charge' => 0,
            'notes' => 'Delivered to the room.'
        ],
        [
            'id' => 5002,
            'request_code' => 'SRV-5002',
            'guest_name' => 'James Anderson',
            'room_number' => 'B-201',
            'category' => 'Room Service',
            'service_name' => 'Breakfast in Room',
            'description' => 'Continental breakfast for two guests.',
            'priority' => 'High',
            'status' => 'In Progress',
            'assigned_to' => 'Daniel Perera',
            'requested_at' => date('Y-m-d\TH:i', strtotime('-30 minutes')),
            'due_at' => date('Y-m-d\TH:i', strtotime('+30 minutes')),
            'charge' => 35,
            'notes' => 'Prepare fresh juice and coffee.'
        ],
        [
            'id' => 5003,
            'request_code' => 'SRV-5003',
            'guest_name' => 'Sophia Williams',
            'room_number' => 'B-205',
            'category' => 'Maintenance',
            'service_name' => 'Air Conditioning Inspection',
            'description' => 'Air conditioner is not cooling sufficiently.',
            'priority' => 'Urgent',
            'status' => 'Pending',
            'assigned_to' => 'Unassigned',
            'requested_at' => date('Y-m-d\TH:i', strtotime('-20 minutes')),
            'due_at' => date('Y-m-d\TH:i', strtotime('+20 minutes')),
            'charge' => 0,
            'notes' => 'Please inspect as soon as possible.'
        ],
        [
            'id' => 5004,
            'request_code' => 'SRV-5004',
            'guest_name' => 'William Taylor',
            'room_number' => 'A-102',
            'category' => 'Transportation',
            'service_name' => 'Airport Transfer',
            'description' => 'Airport pickup for two guests.',
            'priority' => 'High',
            'status' => 'Pending',
            'assigned_to' => 'Michael Fernando',
            'requested_at' => date('Y-m-d\TH:i', strtotime('-2 hours')),
            'due_at' => date('Y-m-d\TH:i', strtotime('+1 day')),
            'charge' => 65,
            'notes' => 'Confirm flight arrival time with guest.'
        ],
        [
            'id' => 5005,
            'request_code' => 'SRV-5005',
            'guest_name' => 'Isabella Martinez',
            'room_number' => 'B-208',
            'category' => 'Concierge',
            'service_name' => 'Restaurant Reservation',
            'description' => 'Reserve a table for two at a seafood restaurant.',
            'priority' => 'Normal',
            'status' => 'Completed',
            'assigned_to' => 'Amara Jayasinghe',
            'requested_at' => date('Y-m-d\TH:i', strtotime('-2 days')),
            'due_at' => date('Y-m-d\TH:i', strtotime('-2 days + 2 hours')),
            'charge' => 0,
            'notes' => 'Reservation confirmed for 7:30 PM.'
        ],
        [
            'id' => 5006,
            'request_code' => 'SRV-5006',
            'guest_name' => 'Emma Thompson',
            'room_number' => 'A-101',
            'category' => 'Laundry',
            'service_name' => 'Express Laundry',
            'description' => 'Express laundry service for personal clothing.',
            'priority' => 'Normal',
            'status' => 'In Progress',
            'assigned_to' => 'Maria Silva',
            'requested_at' => date('Y-m-d\TH:i', strtotime('-45 minutes')),
            'due_at' => date('Y-m-d\TH:i', strtotime('+3 hours')),
            'charge' => 25,
            'notes' => 'Return items to the guest room.'
        ],
        [
            'id' => 5007,
            'request_code' => 'SRV-5007',
            'guest_name' => 'Daniel Cooper',
            'room_number' => 'V-001',
            'category' => 'Housekeeping',
            'service_name' => 'Additional Bedding',
            'description' => 'One extra pillow and a blanket requested.',
            'priority' => 'Low',
            'status' => 'Cancelled',
            'assigned_to' => 'Unassigned',
            'requested_at' => date('Y-m-d\TH:i', strtotime('-3 days')),
            'due_at' => date('Y-m-d\TH:i', strtotime('-3 days + 1 hour')),
            'charge' => 0,
            'notes' => 'Guest cancelled the request.'
        ],
        [
            'id' => 5008,
            'request_code' => 'SRV-5008',
            'guest_name' => 'Olivia Bennett',
            'room_number' => 'A-101',
            'category' => 'Spa & Wellness',
            'service_name' => 'Couples Spa Appointment',
            'description' => 'Arrange a couples massage session.',
            'priority' => 'Normal',
            'status' => 'Pending',
            'assigned_to' => 'Amara Jayasinghe',
            'requested_at' => date('Y-m-d\TH:i', strtotime('-10 minutes')),
            'due_at' => date('Y-m-d\TH:i', strtotime('+2 hours')),
            'charge' => 120,
            'notes' => 'Confirm available treatment times.'
        ]
    ];
}

$categories = [
    'Housekeeping',
    'Room Service',
    'Maintenance',
    'Transportation',
    'Concierge',
    'Laundry',
    'Spa & Wellness',
    'Other'
];

$statuses = [
    'Pending',
    'In Progress',
    'Completed',
    'Cancelled'
];

$priorities = [
    'Low',
    'Normal',
    'High',
    'Urgent'
];

$staffMembers = [
    'Unassigned',
    'Maria Silva',
    'Daniel Perera',
    'Michael Fernando',
    'Amara Jayasinghe',
    'Nimal Fernando',
    'Samantha Peris'
];

function serviceEscape($value)
{
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

function serviceRedirect($message, $type = 'success')
{
    $_SESSION['azurebay_guest_services_flash'] = [
        'message' => $message,
        'type' => $type
    ];

    header('Location: guest_services.php');
    exit;
}

function serviceValidDate($value)
{
    if ($value === '') {
        return false;
    }

    $date = DateTime::createFromFormat('Y-m-d\TH:i', $value);

    return $date !== false &&
        $date->format('Y-m-d\TH:i') === $value;
}

function serviceFormatDate($value)
{
    $date = DateTime::createFromFormat('Y-m-d\TH:i', (string)$value);

    return $date ? $date->format('d M Y, h:i A') : '—';
}

function serviceStatusClass($status)
{
    $classes = [
        'Pending' => 'status-pending',
        'In Progress' => 'status-progress',
        'Completed' => 'status-completed',
        'Cancelled' => 'status-cancelled'
    ];

    return $classes[$status] ?? 'status-pending';
}

function servicePriorityClass($priority)
{
    $classes = [
        'Low' => 'priority-low',
        'Normal' => 'priority-normal',
        'High' => 'priority-high',
        'Urgent' => 'priority-urgent'
    ];

    return $classes[$priority] ?? 'priority-normal';
}

/*
|--------------------------------------------------------------------------
| Handle POST Actions
|--------------------------------------------------------------------------
*/

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'create_request') {
        $guestName = trim($_POST['guest_name'] ?? '');
        $roomNumber = trim($_POST['room_number'] ?? '');
        $category = $_POST['category'] ?? '';
        $serviceName = trim($_POST['service_name'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $priority = $_POST['priority'] ?? 'Normal';
        $assignedTo = $_POST['assigned_to'] ?? 'Unassigned';
        $dueAt = $_POST['due_at'] ?? '';
        $charge = $_POST['charge'] ?? '0';
        $notes = trim($_POST['notes'] ?? '');

        if (
            $guestName === '' ||
            strlen($guestName) > 120 ||
            $roomNumber === '' ||
            strlen($roomNumber) > 30 ||
            $serviceName === '' ||
            strlen($serviceName) > 120 ||
            $description === '' ||
            strlen($description) > 1000 ||
            strlen($notes) > 1000
        ) {
            serviceRedirect(
                'Please complete the required fields using valid lengths.',
                'danger'
            );
        }

        if (!in_array($category, $categories, true)) {
            serviceRedirect('Please select a valid service category.', 'danger');
        }

        if (!in_array($priority, $priorities, true)) {
            serviceRedirect('Please select a valid priority.', 'danger');
        }

        if (!in_array($assignedTo, $staffMembers, true)) {
            serviceRedirect('Please select a valid staff member.', 'danger');
        }

        if (!serviceValidDate($dueAt)) {
            serviceRedirect('Please enter a valid expected completion time.', 'danger');
        }

        if (!is_numeric($charge) || (float)$charge < 0 || (float)$charge > 100000) {
            serviceRedirect('Please enter a valid service charge.', 'danger');
        }

        $newId = 5001;

        foreach ($_SESSION['azurebay_admin_guest_services'] as $request) {
            $newId = max($newId, (int)$request['id'] + 1);
        }

        $_SESSION['azurebay_admin_guest_services'][] = [
            'id' => $newId,
            'request_code' => 'SRV-' . $newId,
            'guest_name' => $guestName,
            'room_number' => $roomNumber,
            'category' => $category,
            'service_name' => $serviceName,
            'description' => $description,
            'priority' => $priority,
            'status' => 'Pending',
            'assigned_to' => $assignedTo,
            'requested_at' => date('Y-m-d\TH:i'),
            'due_at' => $dueAt,
            'charge' => (float)$charge,
            'notes' => $notes
        ];

        serviceRedirect('New guest service request created successfully.');
    }

    if ($action === 'update_request') {
        $requestId = isset($_POST['request_id'])
            ? (int)$_POST['request_id']
            : 0;

        $requestIndex = null;

        foreach ($_SESSION['azurebay_admin_guest_services'] as $index => $request) {
            if ((int)$request['id'] === $requestId) {
                $requestIndex = $index;
                break;
            }
        }

        if ($requestIndex === null) {
            serviceRedirect('Guest service request was not found.', 'danger');
        }

        $status = $_POST['status'] ?? '';
        $priority = $_POST['priority'] ?? '';
        $assignedTo = $_POST['assigned_to'] ?? '';
        $dueAt = $_POST['due_at'] ?? '';
        $charge = $_POST['charge'] ?? '0';
        $notes = trim($_POST['notes'] ?? '');

        if (!in_array($status, $statuses, true)) {
            serviceRedirect('Invalid service request status.', 'danger');
        }

        if (!in_array($priority, $priorities, true)) {
            serviceRedirect('Invalid service priority.', 'danger');
        }

        if (!in_array($assignedTo, $staffMembers, true)) {
            serviceRedirect('Invalid staff assignment.', 'danger');
        }

        if (!serviceValidDate($dueAt)) {
            serviceRedirect('Please enter a valid expected completion time.', 'danger');
        }

        if (!is_numeric($charge) || (float)$charge < 0 || (float)$charge > 100000) {
            serviceRedirect('Please enter a valid service charge.', 'danger');
        }

        if (strlen($notes) > 1000) {
            serviceRedirect('Notes must not exceed 1000 characters.', 'danger');
        }

        $_SESSION['azurebay_admin_guest_services'][$requestIndex]['status'] = $status;
        $_SESSION['azurebay_admin_guest_services'][$requestIndex]['priority'] = $priority;
        $_SESSION['azurebay_admin_guest_services'][$requestIndex]['assigned_to'] = $assignedTo;
        $_SESSION['azurebay_admin_guest_services'][$requestIndex]['due_at'] = $dueAt;
        $_SESSION['azurebay_admin_guest_services'][$requestIndex]['charge'] = (float)$charge;
        $_SESSION['azurebay_admin_guest_services'][$requestIndex]['notes'] = $notes;

        serviceRedirect('Guest service request updated successfully.');
    }

    serviceRedirect('Unknown service management action.', 'danger');
}

/*
|--------------------------------------------------------------------------
| Flash Message
|--------------------------------------------------------------------------
*/

$flash = $_SESSION['azurebay_guest_services_flash'] ?? null;
unset($_SESSION['azurebay_guest_services_flash']);

/*
|--------------------------------------------------------------------------
| Search and Filtering
|--------------------------------------------------------------------------
*/

$requests = $_SESSION['azurebay_admin_guest_services'];

$search = trim($_GET['search'] ?? '');
$statusFilter = $_GET['status'] ?? '';
$categoryFilter = $_GET['category'] ?? '';
$priorityFilter = $_GET['priority'] ?? '';
$sort = $_GET['sort'] ?? 'newest';

$filteredRequests = array_filter(
    $requests,
    function ($request) use (
        $search,
        $statusFilter,
        $categoryFilter,
        $priorityFilter
    ) {
        $searchable = strtolower(
            $request['request_code'] . ' ' .
            $request['guest_name'] . ' ' .
            $request['room_number'] . ' ' .
            $request['service_name'] . ' ' .
            $request['assigned_to']
        );

        if ($search !== '' && strpos($searchable, strtolower($search)) === false) {
            return false;
        }

        if ($statusFilter !== '' && $request['status'] !== $statusFilter) {
            return false;
        }

        if ($categoryFilter !== '' && $request['category'] !== $categoryFilter) {
            return false;
        }

        if ($priorityFilter !== '' && $request['priority'] !== $priorityFilter) {
            return false;
        }

        return true;
    }
);

usort($filteredRequests, function ($a, $b) use ($sort) {
    if ($sort === 'oldest') {
        return strcmp($a['requested_at'], $b['requested_at']);
    }

    if ($sort === 'priority') {
        $rank = [
            'Urgent' => 4,
            'High' => 3,
            'Normal' => 2,
            'Low' => 1
        ];

        $priorityComparison =
            ($rank[$b['priority']] ?? 0) <=> ($rank[$a['priority']] ?? 0);

        if ($priorityComparison !== 0) {
            return $priorityComparison;
        }

        return strcmp($a['requested_at'], $b['requested_at']);
    }

    if ($sort === 'due_soonest') {
        return strcmp($a['due_at'], $b['due_at']);
    }

    return strcmp($b['requested_at'], $a['requested_at']);
});

/*
|--------------------------------------------------------------------------
| Dashboard Statistics
|--------------------------------------------------------------------------
*/

$totalRequests = count($requests);
$pendingRequests = 0;
$inProgressRequests = 0;
$completedRequests = 0;
$urgentRequests = 0;
$totalCharges = 0;
$completedCharges = 0;
$overdueRequests = 0;

$now = date('Y-m-d\TH:i');

foreach ($requests as $request) {
    if ($request['status'] === 'Pending') {
        $pendingRequests++;
    }

    if ($request['status'] === 'In Progress') {
        $inProgressRequests++;
    }

    if ($request['status'] === 'Completed') {
        $completedRequests++;
        $completedCharges += (float)$request['charge'];
    }

    if (
        $request['priority'] === 'Urgent' &&
        in_array($request['status'], ['Pending', 'In Progress'], true)
    ) {
        $urgentRequests++;
    }

    if ($request['status'] !== 'Cancelled') {
        $totalCharges += (float)$request['charge'];
    }

    if (
        in_array($request['status'], ['Pending', 'In Progress'], true) &&
        $request['due_at'] < $now
    ) {
        $overdueRequests++;
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Guest Services | Azure Bay Resort</title>

    <link rel="stylesheet" href="../../../assets/bootstrap/bootstrap.min.css">

    <style>
        :root {
            --ocean: #073b4c;
            --ocean-dark: #052c39;
            --ocean-light: #eaf3f5;
            --gold: #c9a45c;
            --cream: #fbf8f1;
            --muted: #7c8991;
            --border: #e7e9eb;
            --white: #ffffff;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: #f5f7f8;
            color: #24343c;
            font-family: "Trebuchet MS", Arial, sans-serif;
        }

        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            z-index: 1000;
            width: 250px;
            padding: 28px 18px;
            overflow-y: auto;
            background: var(--ocean-dark);
            color: white;
            transition: transform .25s ease;
        }

        .brand {
            padding: 0 10px 28px;
            margin-bottom: 24px;
            border-bottom: 1px solid rgba(255,255,255,.12);
        }

        .brand-name {
            font-size: 23px;
            font-weight: 800;
            letter-spacing: .5px;
        }

        .brand-name span {
            color: var(--gold);
        }

        .brand small {
            display: block;
            margin-top: 5px;
            color: #b8cbd0;
            font-size: 11px;
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        .nav-heading {
            padding: 0 12px;
            margin: 22px 0 10px;
            color: #91aeb6;
            font-size: 10px;
            font-weight: bold;
            letter-spacing: 1.8px;
            text-transform: uppercase;
        }

        .sidebar a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 14px;
            margin-bottom: 5px;
            border-radius: 9px;
            color: #d8e4e7;
            font-size: 13px;
            text-decoration: none;
            transition: .2s ease;
        }

        .sidebar a:hover,
        .sidebar a.active {
            background: rgba(255,255,255,.12);
            color: white;
        }

        .sidebar a.active {
            border-left: 3px solid var(--gold);
        }

        .nav-icon {
            width: 22px;
            text-align: center;
            font-size: 16px;
        }

        .main {
            margin-left: 250px;
            padding: 28px 32px 45px;
        }

        .topbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            margin-bottom: 25px;
        }

        .eyebrow {
            margin-bottom: 6px;
            color: #9a7a3c;
            font-size: 11px;
            font-weight: bold;
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        .page-title {
            margin: 0;
            color: var(--ocean-dark);
            font-size: clamp(24px, 3vw, 31px);
            font-weight: 800;
        }

        .page-subtitle {
            margin-top: 7px;
            color: var(--muted);
            font-size: 13px;
        }

        .admin-profile {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 15px;
            border: 1px solid var(--border);
            border-radius: 12px;
            background: white;
        }

        .avatar {
            display: grid;
            width: 40px;
            height: 40px;
            place-items: center;
            border-radius: 50%;
            background: var(--ocean-light);
            color: var(--ocean);
            font-weight: bold;
        }

        .profile-name {
            font-size: 13px;
            font-weight: bold;
        }

        .profile-role {
            margin-top: 3px;
            color: var(--muted);
            font-size: 11px;
        }

        .mobile-menu {
            display: none;
            padding: 8px 12px;
            border: 1px solid var(--border);
            border-radius: 8px;
            background: white;
            color: var(--ocean);
        }

        .demo-note {
            padding: 13px 16px;
            margin-bottom: 20px;
            border: 1px solid #e7ddc4;
            border-radius: 10px;
            background: #fffaf0;
            color: #76633b;
            font-size: 12px;
            line-height: 1.6;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 17px;
            margin-bottom: 24px;
        }

        .stat-card {
            position: relative;
            overflow: hidden;
            padding: 21px;
            border: 1px solid var(--border);
            border-radius: 15px;
            background: white;
            box-shadow: 0 4px 16px rgba(7,59,76,.025);
        }

        .stat-card::after {
            position: absolute;
            top: -23px;
            right: -20px;
            width: 88px;
            height: 88px;
            border-radius: 50%;
            background: var(--ocean-light);
            content: "";
            opacity: .8;
        }

        .stat-icon {
            display: grid;
            width: 42px;
            height: 42px;
            place-items: center;
            margin-bottom: 15px;
            border-radius: 11px;
            background: var(--ocean-light);
            color: var(--ocean);
            font-size: 20px;
        }

        .stat-label {
            color: var(--muted);
            font-size: 12px;
        }

        .stat-value {
            margin-top: 5px;
            color: var(--ocean-dark);
            font-size: 27px;
            font-weight: 800;
        }

        .stat-note {
            margin-top: 7px;
            color: #8a969c;
            font-size: 11px;
        }

        .panel {
            margin-bottom: 24px;
            border: 1px solid var(--border);
            border-radius: 15px;
            background: white;
            box-shadow: 0 4px 18px rgba(7,59,76,.025);
        }

        .panel-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 15px;
            padding: 21px 23px;
            border-bottom: 1px solid var(--border);
        }

        .panel-title {
            margin: 0;
            color: var(--ocean-dark);
            font-size: 17px;
            font-weight: 800;
        }

        .panel-description {
            margin-top: 5px;
            color: var(--muted);
            font-size: 12px;
        }

        .panel-body {
            padding: 22px 23px;
        }

        .form-label {
            margin-bottom: 7px;
            color: #52616a;
            font-size: 12px;
            font-weight: bold;
        }

        .form-control,
        .form-select {
            min-height: 42px;
            border-color: #dfe5e8;
            border-radius: 8px;
            font-size: 12px;
        }

        textarea.form-control {
            min-height: 92px;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--gold);
            box-shadow: 0 0 0 .2rem rgba(201,164,92,.14);
        }

        .btn-ocean {
            min-height: 42px;
            padding: 10px 17px;
            border: 0;
            border-radius: 8px;
            background: var(--ocean);
            color: white;
            font-size: 12px;
            font-weight: bold;
        }

        .btn-ocean:hover {
            background: var(--ocean-dark);
            color: white;
        }

        .btn-gold {
            min-height: 42px;
            padding: 10px 17px;
            border: 0;
            border-radius: 8px;
            background: var(--gold);
            color: #172d33;
            font-size: 12px;
            font-weight: bold;
        }

        .btn-gold:hover {
            background: #b89148;
            color: #172d33;
        }

        .btn-reset {
            display: inline-flex;
            min-height: 42px;
            align-items: center;
            justify-content: center;
            padding: 10px 15px;
            border: 1px solid #dfe5e8;
            border-radius: 8px;
            color: #52616a;
            font-size: 12px;
            text-decoration: none;
        }

        .btn-reset:hover {
            background: #f4f7f8;
            color: var(--ocean);
        }

        .table-wrap {
            width: 100%;
            overflow-x: auto;
        }

        .service-table {
            width: 100%;
            min-width: 1150px;
            margin: 0;
            border-collapse: separate;
            border-spacing: 0;
        }

        .service-table thead th {
            padding: 15px;
            border-bottom: 1px solid var(--border);
            background: #f8fafb;
            color: #74828a;
            font-size: 10px;
            font-weight: bold;
            letter-spacing: .7px;
            text-transform: uppercase;
            white-space: nowrap;
        }

        .service-table tbody td {
            padding: 16px 15px;
            border-bottom: 1px solid #edf0f1;
            vertical-align: middle;
            font-size: 12px;
        }

        .service-table tbody tr:last-child td {
            border-bottom: 0;
        }

        .service-table tbody tr:hover {
            background: #fcfdfd;
        }

        .request-code {
            color: #9a7a3c;
            font-size: 10px;
            font-weight: bold;
        }

        .service-name {
            margin-top: 5px;
            color: var(--ocean-dark);
            font-size: 13px;
            font-weight: bold;
        }

        .service-category {
            margin-top: 4px;
            color: var(--muted);
            font-size: 11px;
        }

        .guest-name {
            color: var(--ocean-dark);
            font-weight: bold;
        }

        .room-tag {
            display: inline-block;
            padding: 5px 8px;
            border-radius: 6px;
            background: var(--ocean-light);
            color: var(--ocean);
            font-size: 11px;
            font-weight: bold;
            white-space: nowrap;
        }

        .status-badge,
        .priority-badge {
            display: inline-block;
            padding: 6px 9px;
            border-radius: 20px;
            font-size: 10px;
            font-weight: bold;
            white-space: nowrap;
        }

        .status-pending {
            background: #fff1d8;
            color: #986817;
        }

        .status-progress {
            background: #e4efff;
            color: #245d9d;
        }

        .status-completed {
            background: #e4f5ec;
            color: #23774c;
        }

        .status-cancelled {
            background: #f0eeee;
            color: #6d7478;
        }

        .priority-low {
            background: #eef2f4;
            color: #66747b;
        }

        .priority-normal {
            background: #eaf3f5;
            color: #286171;
        }

        .priority-high {
            background: #fff0dd;
            color: #a76518;
        }

        .priority-urgent {
            background: #fde8e7;
            color: #b33d37;
        }

        .staff-name {
            color: #53636b;
            font-size: 11px;
        }

        .date-text {
            color: #4e6068;
            font-size: 11px;
            line-height: 1.7;
            white-space: nowrap;
        }

        .charge {
            color: var(--ocean-dark);
            font-size: 13px;
            font-weight: bold;
            white-space: nowrap;
        }

        .action-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 7px 10px;
            border: 1px solid #dce3e6;
            border-radius: 7px;
            background: white;
            color: var(--ocean);
            font-size: 11px;
            font-weight: bold;
        }

        .action-button:hover {
            border-color: var(--gold);
            background: var(--cream);
        }

        .dropdown-menu {
            border: 1px solid var(--border);
            border-radius: 10px;
            box-shadow: 0 8px 25px rgba(0,0,0,.08);
        }

        .dropdown-item {
            padding: 9px 14px;
            font-size: 12px;
        }

        .dropdown-item:active {
            background: var(--ocean);
        }

        .empty-state {
            padding: 48px 20px;
            text-align: center;
        }

        .empty-icon {
            margin-bottom: 12px;
            color: #9aadb4;
            font-size: 40px;
        }

        .empty-title {
            color: var(--ocean-dark);
            font-size: 17px;
            font-weight: bold;
        }

        .empty-text {
            margin-top: 7px;
            color: var(--muted);
            font-size: 12px;
        }

        .footer-note {
            padding: 8px 0;
            color: #8a969c;
            font-size: 11px;
            text-align: center;
        }

        .modal-content {
            overflow: hidden;
            border: 0;
            border-radius: 15px;
        }

        .modal-header {
            background: var(--ocean-dark);
            color: white;
        }

        .modal-header .btn-close {
            filter: invert(1);
        }

        .modal-title {
            font-size: 17px;
            font-weight: bold;
        }

        .modal-body {
            padding: 25px;
        }

        .modal-footer {
            padding: 16px 25px;
        }

        .detail-box {
            height: 100%;
            padding: 14px;
            border: 1px solid var(--border);
            border-radius: 10px;
            background: #fbfcfc;
        }

        .detail-label {
            margin-bottom: 6px;
            color: var(--muted);
            font-size: 10px;
            font-weight: bold;
            letter-spacing: .7px;
            text-transform: uppercase;
        }

        .detail-value {
            color: var(--ocean-dark);
            font-size: 13px;
            font-weight: bold;
            overflow-wrap: anywhere;
        }

        .summary-strip {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 18px;
        }

        .summary-pill {
            padding: 9px 12px;
            border: 1px solid var(--border);
            border-radius: 8px;
            background: #fbfcfc;
            color: #52616a;
            font-size: 11px;
        }

        .summary-pill strong {
            color: var(--ocean-dark);
        }

        @media (max-width: 1200px) {
            .stats-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .main {
                padding: 25px 22px;
            }
        }

        @media (max-width: 850px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .main {
                margin-left: 0;
                padding: 22px 16px;
            }

            .mobile-menu {
                display: inline-block;
            }

            .topbar {
                align-items: flex-start;
            }

            .admin-profile {
                display: none;
            }

            .panel-header,
            .panel-body {
                padding: 18px;
            }
        }

        @media (max-width: 520px) {
            .stats-grid {
                grid-template-columns: 1fr;
                gap: 12px;
            }

            .stat-card {
                padding: 18px;
            }

            .page-title {
                font-size: 24px;
            }
        }
    </style>
</head>

<body>

<!-- Sidebar -->
<aside class="sidebar" id="sidebar">
    <div class="brand">
        <div class="brand-name">AZURE <span>BAY</span></div>
        <small>Resort Administration</small>
    </div>

    <div class="nav-heading">Main Menu</div>

    <a href="index.php">
        <span class="nav-icon">▦</span> Dashboard
    </a>

    <a href="rooms.php">
        <span class="nav-icon">▣</span> Room Management
    </a>

    <a href="reservations.php">
        <span class="nav-icon">▤</span> Reservations
    </a>

    <a href="customers.php">
        <span class="nav-icon">♙</span> Customers
    </a>

    <a href="guest_services.php" class="active">
        <span class="nav-icon">✦</span> Guest Services
    </a>

    <div class="nav-heading">Finance & Reports</div>

    <a href="payments.php">
        <span class="nav-icon">＄</span> Payments
    </a>

    <a href="discounts.php">
        <span class="nav-icon">％</span> Discounts
    </a>

    <a href="reports.php">
        <span class="nav-icon">▥</span> Revenue Reports
    </a>

    <a href="availability.php">
        <span class="nav-icon">◷</span> Room Availability
    </a>

    <div class="nav-heading">Website</div>

    <a href="../index.php">
        <span class="nav-icon">⌂</span> View Resort Website
    </a>

    <a href="../azurebay_demo.php">
        <span class="nav-icon">↩</span> Back to Demo Selection
    </a>
</aside>

<!-- Main Content -->
<main class="main">

    <header class="topbar">
        <div class="d-flex align-items-start gap-3">
            <button class="mobile-menu" type="button" id="menuToggle"
                    aria-label="Toggle navigation">☰</button>

            <div>
                <div class="eyebrow">Guest Experience Operations</div>
                <h1 class="page-title">Guest Services</h1>
                <div class="page-subtitle">
                    Coordinate guest requests, staff assignments and service delivery.
                </div>
            </div>
        </div>

        <div class="admin-profile">
            <div class="avatar">A</div>
            <div>
                <div class="profile-name">Resort Administrator</div>
                <div class="profile-role">Administrator</div>
            </div>
        </div>
    </header>

    <div class="demo-note">
        <strong>Demo environment:</strong>
        Guest service requests are stored in the current PHP session.
        Changes demonstrate the administrative workflow and are not saved to a database.
    </div>

    <?php if ($flash): ?>
        <div class="alert alert-<?= serviceEscape($flash['type']) ?> alert-dismissible fade show"
             role="alert">
            <?= serviceEscape($flash['message']) ?>
            <button type="button" class="btn-close"
                    data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- Statistics -->
    <section class="stats-grid">

        <div class="stat-card">
            <div class="stat-icon">✦</div>
            <div class="stat-label">Total Requests</div>
            <div class="stat-value"><?= number_format($totalRequests) ?></div>
            <div class="stat-note">All recorded service requests</div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">◷</div>
            <div class="stat-label">Awaiting Action</div>
            <div class="stat-value"><?= number_format($pendingRequests) ?></div>
            <div class="stat-note">Requests waiting to be started</div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">↻</div>
            <div class="stat-label">In Progress</div>
            <div class="stat-value"><?= number_format($inProgressRequests) ?></div>
            <div class="stat-note">Requests currently being handled</div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">!</div>
            <div class="stat-label">Urgent Open Requests</div>
            <div class="stat-value"><?= number_format($urgentRequests) ?></div>
            <div class="stat-note">Urgent requests not yet resolved</div>
        </div>

    </section>

    <!-- Additional operational summary -->
    <section class="panel">
        <div class="panel-body">
            <div class="summary-strip">
                <div class="summary-pill">
                    Completed requests:
                    <strong><?= number_format($completedRequests) ?></strong>
                </div>

                <div class="summary-pill">
                    Overdue open requests:
                    <strong><?= number_format($overdueRequests) ?></strong>
                </div>

                <div class="summary-pill">
                    Open service charges:
                    <strong>$<?= number_format($totalCharges, 2) ?></strong>
                </div>

                <div class="summary-pill">
                    Charges on completed services:
                    <strong>$<?= number_format($completedCharges, 2) ?></strong>
                </div>
            </div>

            <div class="small text-secondary">
                Service-charge totals are demonstration figures. They are not automatically
                added to guest bills or payment records.
            </div>
        </div>
    </section>

    <!-- Create Service Request -->
    <section class="panel">

        <div class="panel-header">
            <div>
                <h2 class="panel-title">Create Service Request</h2>
                <div class="panel-description">
                    Record a new request received from a resort guest.
                </div>
            </div>

            <span class="status-badge status-pending">New Request</span>
        </div>

        <div class="panel-body">
            <form method="POST" action="guest_services.php">

                <input type="hidden" name="action" value="create_request">

                <div class="row g-3">

                    <div class="col-lg-4 col-md-6">
                        <label for="guest_name" class="form-label">
                            Guest Name *
                        </label>
                        <input type="text" class="form-control" id="guest_name"
                               name="guest_name" maxlength="120"
                               placeholder="Enter guest name" required>
                    </div>

                    <div class="col-lg-2 col-md-6">
                        <label for="room_number" class="form-label">
                            Room Number *
                        </label>
                        <input type="text" class="form-control" id="room_number"
                               name="room_number" maxlength="30"
                               placeholder="e.g. A-101" required>
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <label for="category" class="form-label">
                            Service Category *
                        </label>
                        <select class="form-select" id="category" name="category" required>
                            <option value="">Select category</option>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?= serviceEscape($category) ?>">
                                    <?= serviceEscape($category) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <label for="service_name" class="form-label">
                            Service Name *
                        </label>
                        <input type="text" class="form-control" id="service_name"
                               name="service_name" maxlength="120"
                               placeholder="e.g. Airport Transfer" required>
                    </div>

                    <div class="col-lg-6">
                        <label for="description" class="form-label">
                            Request Description *
                        </label>
                        <textarea class="form-control" id="description"
                                  name="description" maxlength="1000"
                                  placeholder="Describe the guest's request..."
                                  required></textarea>
                    </div>

                    <div class="col-lg-6">
                        <label for="notes" class="form-label">
                            Internal Notes
                        </label>
                        <textarea class="form-control" id="notes"
                                  name="notes" maxlength="1000"
                                  placeholder="Optional instructions for resort staff..."></textarea>
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <label for="priority" class="form-label">Priority</label>
                        <select class="form-select" id="priority" name="priority">
                            <?php foreach ($priorities as $priority): ?>
                                <option value="<?= serviceEscape($priority) ?>"
                                    <?= $priority === 'Normal' ? 'selected' : '' ?>>
                                    <?= serviceEscape($priority) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <label for="assigned_to" class="form-label">
                            Assign Staff Member
                        </label>
                        <select class="form-select" id="assigned_to" name="assigned_to">
                            <?php foreach ($staffMembers as $staff): ?>
                                <option value="<?= serviceEscape($staff) ?>">
                                    <?= serviceEscape($staff) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <label for="due_at" class="form-label">
                            Expected Completion *
                        </label>
                        <input type="datetime-local" class="form-control"
                               id="due_at" name="due_at" required>
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <label for="charge" class="form-label">
                            Service Charge ($)
                        </label>
                        <input type="number" class="form-control" id="charge"
                               name="charge" min="0" max="100000" step="0.01"
                               value="0" required>
                    </div>

                    <div class="col-12">
                        <button type="submit" class="btn btn-ocean">
                            + Create Service Request
                        </button>
                    </div>

                </div>
            </form>
        </div>

    </section>

    <!-- Search and Filters -->
    <section class="panel">

        <div class="panel-header">
            <div>
                <h2 class="panel-title">Search Service Requests</h2>
                <div class="panel-description">
                    Filter requests by guest, category, priority or workflow status.
                </div>
            </div>
        </div>

        <div class="panel-body">
            <form method="GET" action="guest_services.php">

                <div class="row g-3">

                    <div class="col-lg-3 col-md-6">
                        <label for="search" class="form-label">
                            Search
                        </label>
                        <input type="text" class="form-control" id="search"
                               name="search" value="<?= serviceEscape($search) ?>"
                               placeholder="Guest, room, request ID...">
                    </div>

                    <div class="col-lg-2 col-md-6">
                        <label for="statusFilter" class="form-label">
                            Request Status
                        </label>
                        <select class="form-select" id="statusFilter" name="status">
                            <option value="">All statuses</option>
                            <?php foreach ($statuses as $status): ?>
                                <option value="<?= serviceEscape($status) ?>"
                                    <?= $statusFilter === $status ? 'selected' : '' ?>>
                                    <?= serviceEscape($status) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-lg-2 col-md-6">
                        <label for="categoryFilter" class="form-label">
                            Category
                        </label>
                        <select class="form-select" id="categoryFilter" name="category">
                            <option value="">All categories</option>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?= serviceEscape($category) ?>"
                                    <?= $categoryFilter === $category ? 'selected' : '' ?>>
                                    <?= serviceEscape($category) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-lg-2 col-md-6">
                        <label for="priorityFilter" class="form-label">
                            Priority
                        </label>
                        <select class="form-select" id="priorityFilter" name="priority">
                            <option value="">All priorities</option>
                            <?php foreach ($priorities as $priority): ?>
                                <option value="<?= serviceEscape($priority) ?>"
                                    <?= $priorityFilter === $priority ? 'selected' : '' ?>>
                                    <?= serviceEscape($priority) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <label for="sort" class="form-label">
                            Sort Requests
                        </label>
                        <select class="form-select" id="sort" name="sort">
                            <option value="newest"
                                <?= $sort === 'newest' ? 'selected' : '' ?>>
                                Newest first
                            </option>
                            <option value="oldest"
                                <?= $sort === 'oldest' ? 'selected' : '' ?>>
                                Oldest first
                            </option>
                            <option value="priority"
                                <?= $sort === 'priority' ? 'selected' : '' ?>>
                                Highest priority
                            </option>
                            <option value="due_soonest"
                                <?= $sort === 'due_soonest' ? 'selected' : '' ?>>
                                Earliest due time
                            </option>
                        </select>
                    </div>

                    <div class="col-12 d-flex flex-wrap gap-2">
                        <button type="submit" class="btn btn-ocean">
                            Search Requests
                        </button>

                        <a href="guest_services.php" class="btn-reset">
                            Reset Filters
                        </a>
                    </div>

                </div>
            </form>
        </div>

    </section>

    <!-- Service Request Table -->
    <section class="panel">

        <div class="panel-header">
            <div>
                <h2 class="panel-title">Service Request Register</h2>
                <div class="panel-description">
                    Review and update guest service requests.
                </div>
            </div>

            <span class="small text-secondary">
                Showing <?= number_format(count($filteredRequests)) ?>
                of <?= number_format($totalRequests) ?> requests
            </span>
        </div>

        <?php if (empty($filteredRequests)): ?>

            <div class="empty-state">
                <div class="empty-icon">⌕</div>
                <div class="empty-title">No service requests found</div>
                <div class="empty-text">
                    Try adjusting your search terms or filters.
                </div>

                <a href="guest_services.php" class="btn btn-ocean mt-3">
                    View All Requests
                </a>
            </div>

        <?php else: ?>

            <div class="table-wrap">
                <table class="service-table">

                    <thead>
                        <tr>
                            <th>Request</th>
                            <th>Guest / Room</th>
                            <th>Priority</th>
                            <th>Assigned Staff</th>
                            <th>Requested / Due</th>
                            <th>Charge</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>

                    <?php foreach ($filteredRequests as $request): ?>

                        <?php
                            $requestId = (int)$request['id'];
                            $isOverdue =
                                in_array($request['status'], ['Pending', 'In Progress'], true) &&
                                $request['due_at'] < $now;
                        ?>

                        <tr>
                            <td>
                                <div class="request-code">
                                    <?= serviceEscape($request['request_code']) ?>
                                </div>

                                <div class="service-name">
                                    <?= serviceEscape($request['service_name']) ?>
                                </div>

                                <div class="service-category">
                                    <?= serviceEscape($request['category']) ?>
                                </div>
                            </td>

                            <td>
                                <div class="guest-name">
                                    <?= serviceEscape($request['guest_name']) ?>
                                </div>

                                <div class="mt-2">
                                    <span class="room-tag">
                                        Room <?= serviceEscape($request['room_number']) ?>
                                    </span>
                                </div>
                            </td>

                            <td>
                                <span class="priority-badge <?= serviceEscape(servicePriorityClass($request['priority'])) ?>">
                                    <?= serviceEscape($request['priority']) ?>
                                </span>
                            </td>

                            <td>
                                <div class="staff-name">
                                    <?= serviceEscape($request['assigned_to']) ?>
                                </div>
                            </td>

                            <td>
                                <div class="date-text">
                                    <strong>Requested:</strong><br>
                                    <?= serviceEscape(serviceFormatDate($request['requested_at'])) ?>
                                </div>

                                <div class="date-text mt-2">
                                    <strong>Due:</strong><br>
                                    <?= serviceEscape(serviceFormatDate($request['due_at'])) ?>
                                </div>

                                <?php if ($isOverdue): ?>
                                    <div class="mt-2">
                                        <span class="priority-badge priority-urgent">
                                            Overdue
                                        </span>
                                    </div>
                                <?php endif; ?>
                            </td>

                            <td>
                                <div class="charge">
                                    $<?= number_format((float)$request['charge'], 2) ?>
                                </div>
                            </td>

                            <td>
                                <span class="status-badge <?= serviceEscape(serviceStatusClass($request['status'])) ?>">
                                    <?= serviceEscape($request['status']) ?>
                                </span>
                            </td>

                            <td>
                                <div class="dropdown">
                                    <button class="action-button dropdown-toggle"
                                            type="button" data-bs-toggle="dropdown"
                                            aria-expanded="false">
                                        Manage
                                    </button>

                                    <ul class="dropdown-menu dropdown-menu-end">

                                        <li>
                                            <button
                                                class="dropdown-item"
                                                type="button"
                                                data-bs-toggle="modal"
                                                data-bs-target="#requestModal"
                                                data-id="<?= $requestId ?>"
                                                data-code="<?= serviceEscape($request['request_code']) ?>"
                                                data-guest="<?= serviceEscape($request['guest_name']) ?>"
                                                data-room="<?= serviceEscape($request['room_number']) ?>"
                                                data-category="<?= serviceEscape($request['category']) ?>"
                                                data-service="<?= serviceEscape($request['service_name']) ?>"
                                                data-description="<?= serviceEscape($request['description']) ?>"
                                                data-priority="<?= serviceEscape($request['priority']) ?>"
                                                data-status="<?= serviceEscape($request['status']) ?>"
                                                data-assigned="<?= serviceEscape($request['assigned_to']) ?>"
                                                data-requested="<?= serviceEscape($request['requested_at']) ?>"
                                                data-due="<?= serviceEscape($request['due_at']) ?>"
                                                data-charge="<?= serviceEscape($request['charge']) ?>"
                                                data-notes="<?= serviceEscape($request['notes']) ?>">
                                                View / Update Request
                                            </button>
                                        </li>

                                        <?php if ($request['status'] !== 'Completed' && $request['status'] !== 'Cancelled'): ?>

                                            <li>
                                                <form method="POST" action="guest_services.php"
                                                      onsubmit="return confirm('Mark this request as completed?');">

                                                    <input type="hidden" name="action"
                                                           value="update_request">
                                                    <input type="hidden" name="request_id"
                                                           value="<?= $requestId ?>">

                                                    <input type="hidden" name="status"
                                                           value="Completed">
                                                    <input type="hidden" name="priority"
                                                           value="<?= serviceEscape($request['priority']) ?>">
                                                    <input type="hidden" name="assigned_to"
                                                           value="<?= serviceEscape($request['assigned_to']) ?>">
                                                    <input type="hidden" name="due_at"
                                                           value="<?= serviceEscape($request['due_at']) ?>">
                                                    <input type="hidden" name="charge"
                                                           value="<?= serviceEscape($request['charge']) ?>">
                                                    <input type="hidden" name="notes"
                                                           value="<?= serviceEscape($request['notes']) ?>">

                                                    <button type="submit"
                                                            class="dropdown-item text-success">
                                                        Mark Completed
                                                    </button>
                                                </form>
                                            </li>

                                        <?php endif; ?>

                                    </ul>
                                </div>
                            </td>
                        </tr>

                    <?php endforeach; ?>

                    </tbody>
                </table>
            </div>

        <?php endif; ?>

    </section>

    <div class="footer-note">
        Azure Bay Resort · Guest Services · Demonstration Environment
    </div>

</main>

<!-- View / Update Request Modal -->
<div class="modal fade" id="requestModal" tabindex="-1"
     aria-labelledby="requestModalLabel" aria-hidden="true">

    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">

            <div class="modal-header">
                <div>
                    <h2 class="modal-title" id="requestModalLabel">
                        Manage Service Request
                    </h2>
                    <div class="small text-white-50 mt-1" id="modalRequestCode"></div>
                </div>

                <button type="button" class="btn-close"
                        data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form method="POST" action="guest_services.php">

                <input type="hidden" name="action" value="update_request">
                <input type="hidden" name="request_id" id="modalRequestId">

                <div class="modal-body">

                    <div class="row g-3 mb-4">

                        <div class="col-md-6">
                            <div class="detail-box">
                                <div class="detail-label">Guest</div>
                                <div class="detail-value" id="modalGuest"></div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="detail-box">
                                <div class="detail-label">Room Number</div>
                                <div class="detail-value" id="modalRoom"></div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="detail-box">
                                <div class="detail-label">Service</div>
                                <div class="detail-value" id="modalService"></div>
                                <div class="muted-small" id="modalCategory"></div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="detail-box">
                                <div class="detail-label">Description</div>
                                <div class="detail-value" id="modalDescription"></div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="detail-box">
                                <div class="detail-label">Requested At</div>
                                <div class="detail-value" id="modalRequested"></div>
                            </div>
                        </div>

                    </div>

                    <hr class="mb-4">

                    <h6 class="fw-bold mb-3" style="color: var(--ocean-dark);">
                        Update Service Workflow
                    </h6>

                    <div class="row g-3">

                        <div class="col-md-6">
                            <label for="modalStatus" class="form-label">
                                Request Status
                            </label>

                            <select class="form-select" name="status"
                                    id="modalStatus" required>
                                <?php foreach ($statuses as $status): ?>
                                    <option value="<?= serviceEscape($status) ?>">
                                        <?= serviceEscape($status) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="modalPriority" class="form-label">
                                Priority
                            </label>

                            <select class="form-select" name="priority"
                                    id="modalPriority" required>
                                <?php foreach ($priorities as $priority): ?>
                                    <option value="<?= serviceEscape($priority) ?>">
                                        <?= serviceEscape($priority) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="modalAssigned" class="form-label">
                                Assigned Staff
                            </label>

                            <select class="form-select" name="assigned_to"
                                    id="modalAssigned" required>
                                <?php foreach ($staffMembers as $staff): ?>
                                    <option value="<?= serviceEscape($staff) ?>">
                                        <?= serviceEscape($staff) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="modalDue" class="form-label">
                                Expected Completion
                            </label>

                            <input type="datetime-local" class="form-control"
                                   name="due_at" id="modalDue" required>
                        </div>

                        <div class="col-md-6">
                            <label for="modalCharge" class="form-label">
                                Service Charge ($)
                            </label>

                            <input type="number" class="form-control"
                                   name="charge" id="modalCharge"
                                   min="0" max="100000" step="0.01" required>
                        </div>

                        <div class="col-12">
                            <label for="modalNotes" class="form-label">
                                Internal Notes
                            </label>

                            <textarea class="form-control" name="notes"
                                      id="modalNotes" maxlength="1000"></textarea>
                        </div>

                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn-reset"
                            data-bs-dismiss="modal">
                        Cancel
                    </button>

                    <button type="submit" class="btn btn-ocean">
                        Save Changes
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>


<script>
    // Toggle sidebar on smaller screens.
    document.getElementById('menuToggle').addEventListener('click', function () {
        document.getElementById('sidebar').classList.toggle('show');
    });

    // Set a convenient default expected completion time.
    const dueInput = document.getElementById('due_at');

    if (dueInput) {
        const defaultDue = new Date(Date.now() + 60 * 60 * 1000);
        defaultDue.setMinutes(
            defaultDue.getMinutes() - defaultDue.getTimezoneOffset()
        );

        dueInput.value = defaultDue.toISOString().slice(0, 16);
    }

    // Populate the request management modal.
    const requestModal = document.getElementById('requestModal');

    requestModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        const data = button.dataset;

        document.getElementById('modalRequestId').value = data.id || '';
        document.getElementById('modalRequestCode').textContent = data.code || '';

        document.getElementById('modalGuest').textContent = data.guest || '';
        document.getElementById('modalRoom').textContent = data.room || '';
        document.getElementById('modalService').textContent = data.service || '';
        document.getElementById('modalCategory').textContent = data.category || '';
        document.getElementById('modalDescription').textContent =
            data.description || '';

        document.getElementById('modalRequested').textContent =
            data.requested ? data.requested.replace('T', ' ') : '';

        document.getElementById('modalStatus').value = data.status || 'Pending';
        document.getElementById('modalPriority').value = data.priority || 'Normal';
        document.getElementById('modalAssigned').value =
            data.assigned || 'Unassigned';
        document.getElementById('modalDue').value = data.due || '';
        document.getElementById('modalCharge').value = data.charge || '0';
        document.getElementById('modalNotes').value = data.notes || '';
    });
</script>

</body>
</html>