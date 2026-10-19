
<?php
session_start();

/*
|--------------------------------------------------------------------------
| FitCore Admin - Attendance Management
|--------------------------------------------------------------------------
| Demonstration page using PHP sessions.
| No database connection is required.
|--------------------------------------------------------------------------
*/

if (!isset($_SESSION['fitcore_admin_attendance'])) {
    $_SESSION['fitcore_admin_attendance'] = [
        [
            'id' => 1,
            'member_id' => 'FCM-1001',
            'member' => 'Olivia Bennett',
            'class' => 'Strength & Conditioning',
            'trainer' => 'Alex Morgan',
            'date' => date('Y-m-d'),
            'checkin' => '06:02',
            'status' => 'Present',
            'notes' => 'Completed the full session.'
        ],
        [
            'id' => 2,
            'member_id' => 'FCM-1002',
            'member' => 'Ethan Brooks',
            'class' => 'Morning Yoga Flow',
            'trainer' => 'Sarah Williams',
            'date' => date('Y-m-d'),
            'checkin' => '08:00',
            'status' => 'Present',
            'notes' => ''
        ],
        [
            'id' => 3,
            'member_id' => 'FCM-1003',
            'member' => 'Sophia Mitchell',
            'class' => 'HIIT Burn',
            'trainer' => 'Daniel Carter',
            'date' => date('Y-m-d'),
            'checkin' => '',
            'status' => 'Absent',
            'notes' => 'Member did not attend.'
        ],
        [
            'id' => 4,
            'member_id' => 'FCM-1004',
            'member' => 'Noah Wilson',
            'class' => 'Pilates Fundamentals',
            'trainer' => 'Sarah Williams',
            'date' => date('Y-m-d', strtotime('-1 day')),
            'checkin' => '09:12',
            'status' => 'Late',
            'notes' => 'Arrived after the scheduled start.'
        ],
        [
            'id' => 5,
            'member_id' => 'FCM-1005',
            'member' => 'Ava Thompson',
            'class' => 'Power Lifting',
            'trainer' => 'Alex Morgan',
            'date' => date('Y-m-d', strtotime('-1 day')),
            'checkin' => '17:55',
            'status' => 'Present',
            'notes' => ''
        ],
        [
            'id' => 6,
            'member_id' => 'FCM-1006',
            'member' => 'Liam Anderson',
            'class' => 'Boxing Fitness',
            'trainer' => 'Daniel Carter',
            'date' => date('Y-m-d', strtotime('-1 day')),
            'checkin' => '',
            'status' => 'Absent',
            'notes' => ''
        ],
        [
            'id' => 7,
            'member_id' => 'FCM-1007',
            'member' => 'Isabella Clark',
            'class' => 'Strength & Conditioning',
            'trainer' => 'Alex Morgan',
            'date' => date('Y-m-d', strtotime('-2 days')),
            'checkin' => '06:00',
            'status' => 'Present',
            'notes' => 'Good participation.'
        ],
        [
            'id' => 8,
            'member_id' => 'FCM-1008',
            'member' => 'James Parker',
            'class' => 'Morning Yoga Flow',
            'trainer' => 'Sarah Williams',
            'date' => date('Y-m-d', strtotime('-2 days')),
            'checkin' => '08:09',
            'status' => 'Late',
            'notes' => ''
        ],
        [
            'id' => 9,
            'member_id' => 'FCM-1009',
            'member' => 'Mia Robinson',
            'class' => 'HIIT Burn',
            'trainer' => 'Daniel Carter',
            'date' => date('Y-m-d', strtotime('-3 days')),
            'checkin' => '',
            'status' => 'Absent',
            'notes' => 'No attendance recorded.'
        ],
        [
            'id' => 10,
            'member_id' => 'FCM-1010',
            'member' => 'Lucas Martin',
            'class' => 'Functional Fitness',
            'trainer' => 'Alex Morgan',
            'date' => date('Y-m-d', strtotime('-3 days')),
            'checkin' => '10:00',
            'status' => 'Present',
            'notes' => ''
        ],
        [
            'id' => 11,
            'member_id' => 'FCM-1011',
            'member' => 'Charlotte Davis',
            'class' => 'Power Lifting',
            'trainer' => 'Alex Morgan',
            'date' => date('Y-m-d', strtotime('-4 days')),
            'checkin' => '18:04',
            'status' => 'Late',
            'notes' => 'Check-in recorded late.'
        ],
        [
            'id' => 12,
            'member_id' => 'FCM-1012',
            'member' => 'Benjamin Harris',
            'class' => 'Boxing Fitness',
            'trainer' => 'Daniel Carter',
            'date' => date('Y-m-d', strtotime('-4 days')),
            'checkin' => '',
            'status' => 'Absent',
            'notes' => ''
        ]
    ];
}

if (!isset($_SESSION['fitcore_attendance_csrf'])) {
    $_SESSION['fitcore_attendance_csrf'] = bin2hex(random_bytes(32));
}

$csrfToken = $_SESSION['fitcore_attendance_csrf'];

/*
|--------------------------------------------------------------------------
| Helper functions
|--------------------------------------------------------------------------
*/

function attendanceEscape($value): string
{
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

function attendanceRedirect(string $message, string $type = 'success'): void
{
    $_SESSION['fitcore_attendance_flash'] = [
        'message' => $message,
        'type' => $type
    ];

    header('Location: attendance.php');
    exit;
}

function attendanceFindIndex(int $id): int
{
    foreach ($_SESSION['fitcore_admin_attendance'] as $index => $record) {
        if ((int)$record['id'] === $id) {
            return $index;
        }
    }

    return -1;
}

function attendanceTimeDisplay(string $time): string
{
    if ($time === '') {
        return '—';
    }

    $timestamp = strtotime($time);

    return $timestamp ? date('h:i A', $timestamp) : $time;
}

/*
|--------------------------------------------------------------------------
| Sample data used by the attendance form
|--------------------------------------------------------------------------
*/

$sampleMembers = [
    'FCM-1001' => 'Olivia Bennett',
    'FCM-1002' => 'Ethan Brooks',
    'FCM-1003' => 'Sophia Mitchell',
    'FCM-1004' => 'Noah Wilson',
    'FCM-1005' => 'Ava Thompson',
    'FCM-1006' => 'Liam Anderson',
    'FCM-1007' => 'Isabella Clark',
    'FCM-1008' => 'James Parker',
    'FCM-1009' => 'Mia Robinson',
    'FCM-1010' => 'Lucas Martin',
    'FCM-1011' => 'Charlotte Davis',
    'FCM-1012' => 'Benjamin Harris'
];

$sampleClasses = [
    'Strength & Conditioning' => 'Alex Morgan',
    'Morning Yoga Flow' => 'Sarah Williams',
    'HIIT Burn' => 'Daniel Carter',
    'Pilates Fundamentals' => 'Sarah Williams',
    'Power Lifting' => 'Alex Morgan',
    'Boxing Fitness' => 'Daniel Carter',
    'Weekend Yoga' => 'Sarah Williams',
    'Functional Fitness' => 'Alex Morgan'
];

$validStatuses = ['Present', 'Absent', 'Late'];

/*
|--------------------------------------------------------------------------
| Process submitted forms
|--------------------------------------------------------------------------
*/

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $submittedToken = (string)($_POST['csrf_token'] ?? '');

    if (!hash_equals($csrfToken, $submittedToken)) {
        attendanceRedirect(
            'Your session token is invalid. Please try again.',
            'danger'
        );
    }

    $action = trim((string)($_POST['action'] ?? ''));

    if ($action === 'save_attendance') {
        $recordId = (int)($_POST['record_id'] ?? 0);
        $memberId = trim((string)($_POST['member_id'] ?? ''));
        $className = trim((string)($_POST['class'] ?? ''));
        $date = trim((string)($_POST['date'] ?? ''));
        $checkin = trim((string)($_POST['checkin'] ?? ''));
        $status = trim((string)($_POST['status'] ?? ''));
        $notes = trim((string)($_POST['notes'] ?? ''));

        $dateObject = DateTime::createFromFormat('!Y-m-d', $date);
        $validDate = $dateObject && $dateObject->format('Y-m-d') === $date;

        $validTime = $checkin === '' ||
            preg_match('/^(?:[01]\d|2[0-3]):[0-5]\d$/', $checkin);

        if (
            !isset($sampleMembers[$memberId]) ||
            !isset($sampleClasses[$className]) ||
            !$validDate ||
            !$validTime ||
            !in_array($status, $validStatuses, true) ||
            strlen($notes) > 300
        ) {
            attendanceRedirect(
                'Please complete the attendance form using valid details.',
                'danger'
            );
        }

        if ($status === 'Absent') {
            $checkin = '';
        }

        $trainer = $sampleClasses[$className];
        $memberName = $sampleMembers[$memberId];

        /*
         * A member should not have duplicate attendance entries
         * for the same class on the same date.
         */
        foreach ($_SESSION['fitcore_admin_attendance'] as $existing) {
            if (
                (int)$existing['id'] !== $recordId &&
                $existing['member_id'] === $memberId &&
                $existing['class'] === $className &&
                $existing['date'] === $date
            ) {
                attendanceRedirect(
                    'An attendance record already exists for this member, class, and date.',
                    'danger'
                );
            }
        }

        if ($recordId > 0) {
            $index = attendanceFindIndex($recordId);

            if ($index === -1) {
                attendanceRedirect(
                    'The selected attendance record could not be found.',
                    'danger'
                );
            }

            $_SESSION['fitcore_admin_attendance'][$index] = [
                'id' => $recordId,
                'member_id' => $memberId,
                'member' => $memberName,
                'class' => $className,
                'trainer' => $trainer,
                'date' => $date,
                'checkin' => $checkin,
                'status' => $status,
                'notes' => $notes
            ];

            attendanceRedirect(
                'Attendance record updated successfully in this demo.'
            );
        }

        $newId = 1;

        foreach ($_SESSION['fitcore_admin_attendance'] as $existing) {
            $newId = max($newId, (int)$existing['id'] + 1);
        }

        $_SESSION['fitcore_admin_attendance'][] = [
            'id' => $newId,
            'member_id' => $memberId,
            'member' => $memberName,
            'class' => $className,
            'trainer' => $trainer,
            'date' => $date,
            'checkin' => $checkin,
            'status' => $status,
            'notes' => $notes
        ];

        attendanceRedirect(
            'Attendance recorded successfully in this demo.'
        );
    }

    if ($action === 'delete_attendance') {
        $recordId = (int)($_POST['record_id'] ?? 0);
        $index = attendanceFindIndex($recordId);

        if ($index === -1) {
            attendanceRedirect(
                'The selected attendance record could not be found.',
                'danger'
            );
        }

        array_splice($_SESSION['fitcore_admin_attendance'], $index, 1);

        attendanceRedirect(
            'Attendance record deleted successfully from this demo.'
        );
    }

    attendanceRedirect('Invalid request. Please try again.', 'danger');
}

/*
|--------------------------------------------------------------------------
| Flash message
|--------------------------------------------------------------------------
*/

$flash = $_SESSION['fitcore_attendance_flash'] ?? null;
unset($_SESSION['fitcore_attendance_flash']);

/*
|--------------------------------------------------------------------------
| Search and filters
|--------------------------------------------------------------------------
*/

$allRecords = $_SESSION['fitcore_admin_attendance'];

$search = trim((string)($_GET['search'] ?? ''));
$statusFilter = trim((string)($_GET['status'] ?? ''));
$dateFilter = trim((string)($_GET['date'] ?? ''));

$filteredRecords = array_filter(
    $allRecords,
    function ($record) use ($search, $statusFilter, $dateFilter) {
        $searchText = strtolower(
            $record['member_id'] . ' ' .
            $record['member'] . ' ' .
            $record['class'] . ' ' .
            $record['trainer']
        );

        $matchesSearch = $search === '' ||
            strpos($searchText, strtolower($search)) !== false;

        $matchesStatus = $statusFilter === '' ||
            $record['status'] === $statusFilter;

        $matchesDate = $dateFilter === '' ||
            $record['date'] === $dateFilter;

        return $matchesSearch && $matchesStatus && $matchesDate;
    }
);

$filteredRecords = array_values($filteredRecords);

/*
|--------------------------------------------------------------------------
| Statistics
|--------------------------------------------------------------------------
*/

$totalRecords = count($allRecords);
$presentCount = 0;
$absentCount = 0;
$lateCount = 0;

foreach ($allRecords as $record) {
    if ($record['status'] === 'Present') {
        $presentCount++;
    } elseif ($record['status'] === 'Absent') {
        $absentCount++;
    } elseif ($record['status'] === 'Late') {
        $lateCount++;
    }
}

$attendedCount = $presentCount + $lateCount;
$attendanceRate = $totalRecords > 0
    ? round(($attendedCount / $totalRecords) * 100)
    : 0;

/*
|--------------------------------------------------------------------------
| Class attendance summary
|--------------------------------------------------------------------------
*/

$classSummary = [];

foreach ($allRecords as $record) {
    $className = $record['class'];

    if (!isset($classSummary[$className])) {
        $classSummary[$className] = [
            'total' => 0,
            'present' => 0,
            'absent' => 0,
            'late' => 0
        ];
    }

    $classSummary[$className]['total']++;

    if ($record['status'] === 'Present') {
        $classSummary[$className]['present']++;
    } elseif ($record['status'] === 'Absent') {
        $classSummary[$className]['absent']++;
    } elseif ($record['status'] === 'Late') {
        $classSummary[$className]['late']++;
    }
}

ksort($classSummary);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Attendance Management | FitCore Admin</title>

    <link rel="stylesheet" href="../../../assets/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.3.1/css/all.min.css" integrity="sha512-..." crossorigin="anonymous" referrerpolicy="no-referrer" />


    <style>
        :root {
            --fc-bg: #101010;
            --fc-sidebar: #171717;
            --fc-card: #1b1b1b;
            --fc-orange: #ff6b2c;
            --fc-orange-hover: #ff824d;
            --fc-text: #f4f4f4;
            --fc-muted: #a2a2a2;
            --fc-border: #303030;
            --fc-green: #36c98f;
            --fc-red: #ff6868;
            --fc-yellow: #f3bd4f;
            --fc-blue: #68a8ff;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: var(--fc-bg);
            color: var(--fc-text);
            font-family: "Trebuchet MS", Arial, sans-serif;
        }

        a {
            text-decoration: none;
        }

        .sidebar {
            width: 250px;
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            overflow-y: auto;
            background: var(--fc-sidebar);
            border-right: 1px solid var(--fc-border);
            padding: 25px 16px;
            z-index: 1030;
            transition: transform .25s ease;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 0 10px 28px;
            border-bottom: 1px solid var(--fc-border);
            margin-bottom: 25px;
        }

        .brand-icon {
            width: 43px;
            height: 43px;
            border-radius: 12px;
            background: var(--fc-orange);
            color: #111;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 21px;
        }

        .brand h4 {
            font-weight: 800;
            margin: 0;
            letter-spacing: .5px;
        }

        .brand small {
            display: block;
            color: var(--fc-muted);
            font-size: 11px;
            margin-top: 3px;
        }

        .nav-heading {
            color: #777;
            font-size: 10px;
            letter-spacing: 1.5px;
            font-weight: 700;
            padding: 0 12px;
            margin: 23px 0 10px;
        }

        .sidebar .nav-link {
            display: flex;
            align-items: center;
            gap: 12px;
            color: #bdbdbd;
            padding: 12px 13px;
            border-radius: 9px;
            margin-bottom: 5px;
            font-size: 13px;
            transition: .2s;
        }

        .sidebar .nav-link i {
            width: 18px;
            text-align: center;
        }

        .sidebar .nav-link:hover {
            background: #272727;
            color: #fff;
        }

        .sidebar .nav-link.active {
            background: rgba(255, 107, 44, .14);
            color: var(--fc-orange);
            font-weight: 700;
        }

        .sidebar-bottom {
            border-top: 1px solid var(--fc-border);
            margin-top: 28px;
            padding-top: 16px;
        }

        .main-content {
            margin-left: 250px;
            padding: 28px 32px;
            min-height: 100vh;
        }

        .topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 15px;
            margin-bottom: 30px;
        }

        .page-title {
            font-size: clamp(24px, 3vw, 30px);
            font-weight: 800;
            margin: 0;
        }

        .page-subtitle {
            color: var(--fc-muted);
            font-size: 13px;
            margin-top: 7px;
        }

        .admin-profile {
            display: flex;
            align-items: center;
            gap: 11px;
        }

        .admin-avatar {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            background: #353535;
            color: var(--fc-orange);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
        }

        .admin-profile strong {
            font-size: 13px;
            display: block;
        }

        .admin-profile small {
            color: var(--fc-muted);
            font-size: 11px;
        }

        .btn-orange {
            background: var(--fc-orange);
            border: 1px solid var(--fc-orange);
            color: #111;
            font-weight: 800;
            border-radius: 8px;
            padding: 10px 15px;
            font-size: 13px;
        }

        .btn-orange:hover {
            background: var(--fc-orange-hover);
            border-color: var(--fc-orange-hover);
            color: #111;
        }

        .btn-outline-light {
            border-color: #454545;
            color: #ddd;
        }

        .btn-outline-light:hover {
            background: #303030;
            border-color: #555;
            color: #fff;
        }

        .stat-card {
            height: 100%;
            padding: 21px;
            border: 1px solid var(--fc-border);
            border-radius: 13px;
            background: var(--fc-card);
        }

        .stat-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 17px;
        }

        .stat-label {
            color: var(--fc-muted);
            font-size: 12px;
        }

        .stat-icon {
            width: 43px;
            height: 43px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(255, 107, 44, .13);
            color: var(--fc-orange);
            border-radius: 11px;
            font-size: 17px;
        }

        .stat-value {
            font-size: 27px;
            font-weight: 800;
            margin-top: 5px;
        }

        .stat-note {
            color: var(--fc-muted);
            font-size: 11px;
            margin-top: 7px;
        }

        .panel {
            background: var(--fc-card);
            border: 1px solid var(--fc-border);
            border-radius: 13px;
            overflow: hidden;
        }

        .panel-header {
            padding: 21px 22px;
            border-bottom: 1px solid var(--fc-border);
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 15px;
            flex-wrap: wrap;
        }

        .panel-header h5 {
            margin: 0;
            font-size: 16px;
            font-weight: 800;
        }

        .panel-header p {
            color: var(--fc-muted);
            font-size: 12px;
            margin: 5px 0 0;
        }

        .filters {
            padding: 18px 22px;
            display: grid;
            grid-template-columns: minmax(180px, 1fr) 170px 180px auto;
            gap: 10px;
            border-bottom: 1px solid var(--fc-border);
        }

        .form-control,
        .form-select {
            background-color: #151515;
            color: #eee;
            border: 1px solid #393939;
            border-radius: 8px;
            min-height: 42px;
            font-size: 13px;
        }

        .form-control:focus,
        .form-select:focus {
            background-color: #151515;
            color: #fff;
            border-color: var(--fc-orange);
            box-shadow: 0 0 0 .2rem rgba(255, 107, 44, .12);
        }

        .form-control::placeholder {
            color: #777;
        }

        .form-select option {
            background: #1b1b1b;
            color: #fff;
        }

        .table {
            --bs-table-bg: transparent;
            --bs-table-color: #ddd;
            --bs-table-border-color: var(--fc-border);
            margin: 0;
            vertical-align: middle;
            white-space: nowrap;
        }

        .table thead th {
            color: #929292;
            background: #191919;
            font-size: 10px;
            letter-spacing: .8px;
            text-transform: uppercase;
            font-weight: 800;
            padding: 15px 17px;
            border-bottom: 1px solid var(--fc-border);
        }

        .table tbody td {
            padding: 15px 17px;
            font-size: 12px;
        }

        .table tbody tr:hover td {
            background: rgba(255,255,255,.018);
        }

        .member-info {
            display: flex;
            align-items: center;
            gap: 11px;
        }

        .member-avatar {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--fc-orange);
            background: rgba(255, 107, 44, .12);
            font-weight: 800;
            flex-shrink: 0;
        }

        .member-info strong {
            display: block;
            color: #f4f4f4;
            font-size: 12px;
            margin-bottom: 4px;
        }

        .secondary-text {
            color: var(--fc-muted);
            font-size: 11px;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            border-radius: 20px;
            padding: 6px 10px;
            font-size: 10px;
            font-weight: 800;
        }

        .status-badge::before {
            content: "";
            width: 5px;
            height: 5px;
            border-radius: 50%;
            background: currentColor;
        }

        .status-present {
            background: rgba(54, 201, 143, .12);
            color: var(--fc-green);
        }

        .status-absent {
            background: rgba(255, 104, 104, .12);
            color: var(--fc-red);
        }

        .status-late {
            background: rgba(243, 189, 79, .12);
            color: var(--fc-yellow);
        }

        .action-btn {
            width: 31px;
            height: 31px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 7px;
            border: 1px solid #3a3a3a;
            color: #cfcfcf;
            background: #242424;
            margin-right: 3px;
            font-size: 11px;
        }

        .action-btn:hover {
            color: var(--fc-orange);
            border-color: var(--fc-orange);
        }

        .action-btn.delete:hover {
            color: var(--fc-red);
            border-color: var(--fc-red);
        }

        .empty-state {
            padding: 55px 20px;
            text-align: center;
            color: var(--fc-muted);
        }

        .empty-state i {
            font-size: 35px;
            color: #666;
            margin-bottom: 15px;
        }

        .summary-panel {
            height: 100%;
            padding: 22px;
            background: var(--fc-card);
            border: 1px solid var(--fc-border);
            border-radius: 13px;
        }

        .summary-panel h6 {
            font-size: 14px;
            font-weight: 800;
            margin-bottom: 8px;
        }

        .summary-panel > p {
            font-size: 12px;
            color: var(--fc-muted);
            margin-bottom: 20px;
        }

        .summary-number {
            font-size: 31px;
            font-weight: 800;
            margin-bottom: 10px;
        }

        .summary-number span {
            font-size: 12px;
            color: var(--fc-muted);
            font-weight: 400;
        }

        .progress {
            height: 7px;
            background: #353535;
            border-radius: 20px;
        }

        .progress-bar {
            border-radius: 20px;
        }

        .category-row {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            padding: 12px 0;
            border-bottom: 1px solid var(--fc-border);
            font-size: 12px;
        }

        .category-row:last-child {
            border-bottom: 0;
        }

        .category-row .value {
            color: var(--fc-orange);
            font-weight: 800;
        }

        .modal-content {
            background: #1b1b1b;
            color: #f4f4f4;
            border: 1px solid #393939;
            border-radius: 13px;
        }

        .modal-header,
        .modal-footer {
            border-color: #363636;
        }

        .modal-title {
            font-weight: 800;
        }

        .btn-close {
            filter: invert(1);
        }

        .form-label {
            color: #c8c8c8;
            font-size: 12px;
            margin-bottom: 7px;
        }

        .alert {
            border-radius: 9px;
            font-size: 13px;
        }

        .sidebar-toggle {
            display: none;
            background: #252525;
            border: 1px solid #3a3a3a;
            color: #fff;
            padding: 9px 12px;
            border-radius: 8px;
        }

        @media (max-width: 1199px) {
            .main-content {
                padding: 25px 22px;
            }

            .filters {
                grid-template-columns: 1fr 1fr;
            }
        }

        @media (max-width: 991px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .sidebar-overlay.show {
                display: block;
                position: fixed;
                inset: 0;
                background: rgba(0,0,0,.65);
                z-index: 1020;
            }

            .main-content {
                margin-left: 0;
                padding: 22px;
            }

            .sidebar-toggle {
                display: inline-flex;
                align-items: center;
                justify-content: center;
            }
        }

        .sidebar-overlay {
            display: none;
        }

        @media (max-width: 575px) {
            .main-content {
                padding: 17px 12px;
            }

            .topbar {
                align-items: flex-start;
                flex-wrap: wrap;
            }

            .admin-profile {
                display: none;
            }

            .filters {
                grid-template-columns: 1fr;
                padding: 15px;
            }

            .panel-header {
                padding: 17px 15px;
            }

            .stat-card {
                padding: 16px;
            }

            .stat-value {
                font-size: 23px;
            }

            .table thead th,
            .table tbody td {
                padding-left: 13px;
                padding-right: 13px;
            }
        }

        @media print {
            .sidebar,
            .sidebar-overlay,
            .sidebar-toggle,
            .no-print,
            .filters,
            .action-column {
                display: none !important;
            }

            .main-content {
                margin: 0;
                padding: 0;
            }

            body {
                background: #fff;
                color: #111;
            }

            .panel,
            .stat-card,
            .summary-panel {
                background: #fff;
                color: #111;
                border-color: #ddd;
            }

            .table,
            .table thead th,
            .table tbody td {
                color: #111;
                background: #fff;
            }
        }
    </style>
</head>
<body>

<div class="sidebar-overlay" id="sidebarOverlay"></div>

<aside class="sidebar" id="sidebar">
    <a href="index.php" class="brand text-white">
        <div class="brand-icon">
            <i class="fas fa-dumbbell"></i>
        </div>
        <div>
            <h4>FITCORE</h4>
            <small>ADMINISTRATION</small>
        </div>
    </a>

    <div class="nav-heading">MAIN MENU</div>

    <nav class="nav flex-column">
        <a href="index.php" class="nav-link">
            <i class="fas fa-chart-pie"></i> Dashboard
        </a>
        <a href="members.php" class="nav-link">
            <i class="fas fa-users"></i> Members
        </a>
        <a href="trainers.php" class="nav-link">
            <i class="fas fa-user-tie"></i> Trainers
        </a>
        <a href="memberships.php" class="nav-link">
            <i class="fas fa-id-card"></i> Memberships
        </a>
        <a href="payments.php" class="nav-link">
            <i class="fas fa-wallet"></i> Payments
        </a>
        <a href="classes.php" class="nav-link">
            <i class="fas fa-calendar-alt"></i> Classes
        </a>
        <a href="attendance.php" class="nav-link active">
            <i class="fas fa-clipboard-check"></i> Attendance
        </a>
        <a href="reports.php" class="nav-link">
            <i class="fas fa-chart-bar"></i> Reports
        </a>
    </nav>

    <div class="nav-heading">DEMO NAVIGATION</div>

    <nav class="nav flex-column">
        <a href="../fitcore_demo.php" class="nav-link">
            <i class="fas fa-exchange-alt"></i> Switch Role
        </a>
        <a href="../index.php" class="nav-link">
            <i class="fas fa-globe"></i> Main Website
        </a>
    </nav>

    <div class="sidebar-bottom">
        <div class="nav-link">
            <i class="fas fa-shield-alt"></i>
            <span>Demo Administrator</span>
        </div>
        <div class="nav-link">
            <i class="fas fa-circle" style="color: var(--fc-green); font-size: 8px;"></i>
            <span>Demo environment active</span>
        </div>
    </div>
</aside>

<main class="main-content">

    <header class="topbar">
        <div class="d-flex align-items-start gap-3">
            <button class="sidebar-toggle" id="sidebarToggle"
                    type="button" aria-label="Open navigation">
                <i class="fas fa-bars"></i>
            </button>

            <div>
                <h1 class="page-title">Attendance Management</h1>
                <div class="page-subtitle">
                    Track member participation and manage class attendance records.
                </div>
            </div>
        </div>

        <div class="d-flex align-items-center gap-3">
            <div class="admin-profile">
                <div class="admin-avatar">AD</div>
                <div>
                    <strong>Administrator</strong>
                    <small>System Manager</small>
                </div>
            </div>

            <button type="button" class="btn btn-orange"
                    onclick="openAddModal()">
                <i class="fas fa-plus me-1"></i> Record Attendance
            </button>
        </div>
    </header>

    <?php if ($flash): ?>
        <div class="alert alert-<?php echo attendanceEscape($flash['type']); ?> alert-dismissible fade show"
             role="alert">
            <?php echo attendanceEscape($flash['message']); ?>
            <button type="button" class="btn-close"
                    data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <section class="row g-3 mb-4">
        <div class="col-6 col-xl-3">
            <div class="stat-card">
                <div class="stat-top">
                    <span class="stat-label">Total Records</span>
                    <div class="stat-icon"><i class="fas fa-clipboard-list"></i></div>
                </div>
                <div class="stat-value"><?php echo $totalRecords; ?></div>
                <div class="stat-note">Recorded attendance entries</div>
            </div>
        </div>

        <div class="col-6 col-xl-3">
            <div class="stat-card">
                <div class="stat-top">
                    <span class="stat-label">Present</span>
                    <div class="stat-icon"><i class="fas fa-user-check"></i></div>
                </div>
                <div class="stat-value"><?php echo $presentCount; ?></div>
                <div class="stat-note">
                    <span style="color: var(--fc-green);">Attended as expected</span>
                </div>
            </div>
        </div>

        <div class="col-6 col-xl-3">
            <div class="stat-card">
                <div class="stat-top">
                    <span class="stat-label">Absent</span>
                    <div class="stat-icon"><i class="fas fa-user-times"></i></div>
                </div>
                <div class="stat-value"><?php echo $absentCount; ?></div>
                <div class="stat-note">
                    <span style="color: var(--fc-red);">Did not attend</span>
                </div>
            </div>
        </div>

        <div class="col-6 col-xl-3">
            <div class="stat-card">
                <div class="stat-top">
                    <span class="stat-label">Attendance Rate</span>
                    <div class="stat-icon"><i class="fas fa-chart-line"></i></div>
                </div>
                <div class="stat-value"><?php echo $attendanceRate; ?>%</div>
                <div class="stat-note">
                    <?php echo $lateCount; ?> late arrival(s) recorded
                </div>
            </div>
        </div>
    </section>

    <section class="panel mb-4">
        <div class="panel-header">
            <div>
                <h5>
                    <i class="fas fa-clipboard-check me-2"
                       style="color: var(--fc-orange);"></i>
                    Attendance Records
                </h5>
                <p>Review attendance and update member records.</p>
            </div>

            <div class="d-flex align-items-center gap-2 no-print">
                <button class="btn btn-outline-light btn-sm"
                        type="button" onclick="window.print()">
                    <i class="fas fa-print me-1"></i> Print
                </button>

                <span class="secondary-text">
                    <?php echo count($filteredRecords); ?> record(s)
                </span>
            </div>
        </div>

        <form method="GET" action="attendance.php"
              class="filters no-print">

            <input
                type="search"
                class="form-control"
                name="search"
                placeholder="Search member, class, trainer..."
                value="<?php echo attendanceEscape($search); ?>"
            >

            <select class="form-select" name="status">
                <option value="">All Statuses</option>
                <?php foreach ($validStatuses as $status): ?>
                    <option value="<?php echo attendanceEscape($status); ?>"
                        <?php echo $statusFilter === $status ? 'selected' : ''; ?>>
                        <?php echo attendanceEscape($status); ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <input
                type="date"
                class="form-control"
                name="date"
                aria-label="Filter by date"
                value="<?php echo attendanceEscape($dateFilter); ?>"
            >

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-orange flex-grow-1">
                    <i class="fas fa-search me-1"></i> Filter
                </button>

                <a href="attendance.php"
                   class="btn btn-outline-light"
                   title="Clear filters">
                    <i class="fas fa-redo"></i>
                </a>
            </div>
        </form>

        <?php if (count($filteredRecords) > 0): ?>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                    <tr>
                        <th>Member</th>
                        <th>Class / Trainer</th>
                        <th>Date</th>
                        <th>Check-in</th>
                        <th>Status</th>
                        <th class="action-column">Actions</th>
                    </tr>
                    </thead>

                    <tbody>
                    <?php foreach ($filteredRecords as $record): ?>
                        <?php
                        $initials = '';

                        foreach (explode(' ', $record['member']) as $part) {
                            if ($part !== '') {
                                $initials .= strtoupper(substr($part, 0, 1));
                            }
                        }

                        $initials = substr($initials, 0, 2);

                        $statusClass = match ($record['status']) {
                            'Present' => 'status-present',
                            'Absent' => 'status-absent',
                            'Late' => 'status-late',
                            default => ''
                        };

                        $dateTimestamp = strtotime($record['date']);
                        ?>

                        <tr>
                            <td>
                                <div class="member-info">
                                    <div class="member-avatar">
                                        <?php echo attendanceEscape($initials); ?>
                                    </div>

                                    <div>
                                        <strong><?php echo attendanceEscape($record['member']); ?></strong>
                                        <div class="secondary-text">
                                            <?php echo attendanceEscape($record['member_id']); ?>
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <td>
                                <strong><?php echo attendanceEscape($record['class']); ?></strong>
                                <div class="secondary-text mt-1">
                                    Trainer: <?php echo attendanceEscape($record['trainer']); ?>
                                </div>
                            </td>

                            <td>
                                <?php
                                echo $dateTimestamp
                                    ? attendanceEscape(date('d M Y', $dateTimestamp))
                                    : attendanceEscape($record['date']);
                                ?>
                            </td>

                            <td>
                                <?php echo attendanceEscape(attendanceTimeDisplay($record['checkin'])); ?>
                            </td>

                            <td>
                                <span class="status-badge <?php echo attendanceEscape($statusClass); ?>">
                                    <?php echo attendanceEscape($record['status']); ?>
                                </span>
                            </td>

                            <td class="action-column">
                                <button type="button"
                                        class="action-btn"
                                        title="Edit attendance"
                                        onclick='openEditModal(<?php echo json_encode($record, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP); ?>)'>
                                    <i class="fas fa-edit"></i>
                                </button>

                                <form method="POST" action="attendance.php"
                                      class="d-inline"
                                      onsubmit="return confirm('Delete this attendance record from the demo?');">
                                    <input type="hidden" name="csrf_token"
                                           value="<?php echo attendanceEscape($csrfToken); ?>">
                                    <input type="hidden" name="action"
                                           value="delete_attendance">
                                    <input type="hidden" name="record_id"
                                           value="<?php echo (int)$record['id']; ?>">

                                    <button type="submit"
                                            class="action-btn delete"
                                            title="Delete record">
                                        <i class="fas fa-trash-alt"></i>
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
                <i class="fas fa-clipboard-list d-block"></i>
                <h5 class="text-white">No attendance records found</h5>
                <p class="mb-3">Try a different search term, date, or status.</p>
                <a href="attendance.php" class="btn btn-outline-light btn-sm">
                    Clear Filters
                </a>
            </div>
        <?php endif; ?>
    </section>

    <section class="row g-3 mb-4">
        <div class="col-lg-5">
            <div class="summary-panel">
                <h6>
                    <i class="fas fa-chart-pie me-2"
                       style="color: var(--fc-orange);"></i>
                    Attendance Overview
                </h6>

                <p>Breakdown of attendance status across all demo records.</p>

                <div class="summary-number">
                    <?php echo $attendanceRate; ?>%
                    <span>attended or arrived late</span>
                </div>

                <div class="progress mb-3">
                    <div class="progress-bar"
                         style="width: <?php echo $attendanceRate; ?>%; background: var(--fc-green);">
                    </div>
                </div>

                <div class="category-row">
                    <span><i class="fas fa-circle me-2"
                             style="color: var(--fc-green); font-size: 8px;"></i>Present</span>
                    <span class="value"><?php echo $presentCount; ?></span>
                </div>

                <div class="category-row">
                    <span><i class="fas fa-circle me-2"
                             style="color: var(--fc-yellow); font-size: 8px;"></i>Late</span>
                    <span class="value"><?php echo $lateCount; ?></span>
                </div>

                <div class="category-row">
                    <span><i class="fas fa-circle me-2"
                             style="color: var(--fc-red); font-size: 8px;"></i>Absent</span>
                    <span class="value"><?php echo $absentCount; ?></span>
                </div>
            </div>
        </div>

        <div class="col-lg-7">
            <div class="summary-panel">
                <h6>
                    <i class="fas fa-dumbbell me-2"
                       style="color: var(--fc-orange);"></i>
                    Class Attendance Summary
                </h6>

                <p>Recorded attendance by fitness class.</p>

                <?php if (count($classSummary) > 0): ?>
                    <?php foreach ($classSummary as $className => $summary): ?>
                        <?php
                        $classRate = $summary['total'] > 0
                            ? round(
                                (($summary['present'] + $summary['late']) /
                                $summary['total']) * 100
                            )
                            : 0;
                        ?>

                        <div class="category-row align-items-center">
                            <div style="flex: 1; min-width: 0;">
                                <div class="mb-2">
                                    <?php echo attendanceEscape($className); ?>
                                </div>

                                <div class="progress">
                                    <div class="progress-bar"
                                         style="width: <?php echo $classRate; ?>%; background: var(--fc-orange);">
                                    </div>
                                </div>
                            </div>

                            <div class="text-end" style="min-width: 78px;">
                                <div class="value"><?php echo $classRate; ?>%</div>
                                <div class="secondary-text">
                                    <?php echo $summary['total']; ?> records
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="secondary-text">No class attendance data available.</div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <div class="text-center secondary-text pb-3">
        FitCore Administration Demo &copy; <?php echo date('Y'); ?>.
        Attendance information is stored in the current PHP session only.
    </div>

</main>

<!-- Add / Edit Attendance Modal -->
<div class="modal fade" id="attendanceModal" tabindex="-1"
     aria-labelledby="attendanceModalLabel" aria-hidden="true">

    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">

            <form method="POST" action="attendance.php" id="attendanceForm">
                <div class="modal-header">
                    <div>
                        <h5 class="modal-title" id="attendanceModalLabel">
                            Record Attendance
                        </h5>
                        <div class="secondary-text mt-1">
                            Enter the member's class attendance details.
                        </div>
                    </div>

                    <button type="button" class="btn-close"
                            data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body p-4">
                    <input type="hidden" name="csrf_token"
                           value="<?php echo attendanceEscape($csrfToken); ?>">
                    <input type="hidden" name="action" value="save_attendance">
                    <input type="hidden" name="record_id" id="record_id" value="0">

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label" for="member_id">
                                Member *
                            </label>

                            <select class="form-select" name="member_id"
                                    id="member_id" required>
                                <option value="">Select member</option>

                                <?php foreach ($sampleMembers as $id => $name): ?>
                                    <option value="<?php echo attendanceEscape($id); ?>">
                                        <?php echo attendanceEscape($name); ?>
                                        (<?php echo attendanceEscape($id); ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label" for="class">
                                Fitness Class *
                            </label>

                            <select class="form-select" name="class"
                                    id="class" required>
                                <option value="">Select class</option>

                                <?php foreach ($sampleClasses as $name => $trainer): ?>
                                    <option value="<?php echo attendanceEscape($name); ?>">
                                        <?php echo attendanceEscape($name); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label" for="attendance_date">
                                Attendance Date *
                            </label>

                            <input type="date" class="form-control"
                                   name="date" id="attendance_date"
                                   max="<?php echo date('Y-m-d'); ?>"
                                   value="<?php echo date('Y-m-d'); ?>"
                                   required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label" for="checkin">
                                Check-in Time
                            </label>

                            <input type="time" class="form-control"
                                   name="checkin" id="checkin">

                            <div class="form-text">
                                Not required when the member is absent.
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label" for="attendance_status">
                                Attendance Status *
                            </label>

                            <select class="form-select" name="status"
                                    id="attendance_status" required>
                                <option value="Present">Present</option>
                                <option value="Late">Late</option>
                                <option value="Absent">Absent</option>
                            </select>
                        </div>

                        <div class="col-12">
                            <label class="form-label" for="notes">
                                Notes (Optional)
                            </label>

                            <textarea class="form-control" name="notes"
                                      id="notes" rows="3" maxlength="300"
                                      placeholder="Add a short attendance note..."></textarea>

                            <div class="form-text">
                                Maximum 300 characters.
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer p-3">
                    <button type="button" class="btn btn-outline-light"
                            data-bs-dismiss="modal">
                        Cancel
                    </button>

                    <button type="submit" class="btn btn-orange">
                        <i class="fas fa-save me-1"></i> Save Attendance
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>


<script>
    const attendanceModalElement = document.getElementById('attendanceModal');
    const attendanceModal = new bootstrap.Modal(attendanceModalElement);

    const attendanceForm = document.getElementById('attendanceForm');
    const attendanceStatus = document.getElementById('attendance_status');
    const checkinInput = document.getElementById('checkin');
    const dateInput = document.getElementById('attendance_date');

    function updateCheckinRequirement() {
        if (attendanceStatus.value === 'Absent') {
            checkinInput.value = '';
            checkinInput.disabled = true;
        } else {
            checkinInput.disabled = false;
        }
    }

    attendanceStatus.addEventListener('change', updateCheckinRequirement);

    function openAddModal() {
        attendanceForm.reset();

        document.getElementById('record_id').value = '0';
        document.getElementById('attendanceModalLabel').textContent =
            'Record Attendance';

        dateInput.value = '<?php echo date('Y-m-d'); ?>';
        dateInput.max = '<?php echo date('Y-m-d'); ?>';

        attendanceStatus.value = 'Present';
        checkinInput.disabled = false;

        attendanceModal.show();
    }

    function openEditModal(record) {
        attendanceForm.reset();

        document.getElementById('record_id').value = record.id;
        document.getElementById('member_id').value = record.member_id;
        document.getElementById('class').value = record.class;
        dateInput.value = record.date;
        dateInput.max = '<?php echo date('Y-m-d'); ?>';
        checkinInput.value = record.checkin || '';
        attendanceStatus.value = record.status;
        document.getElementById('notes').value = record.notes || '';

        /*
         * Historical sample records are editable.
         * The form's date maximum prevents creating new future records.
         */
        document.getElementById('attendanceModalLabel').textContent =
            'Edit Attendance Record';

        updateCheckinRequirement();
        attendanceModal.show();
    }

    // Update the trainer hint when the class changes.
    const classSelect = document.getElementById('class');

    const classTrainers = <?php
        echo json_encode(
            $sampleClasses,
            JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP
        );
    ?>;

    classSelect.addEventListener('change', function () {
        const trainer = classTrainers[this.value];

        let hint = document.getElementById('trainerHint');

        if (!hint) {
            hint = document.createElement('div');
            hint.id = 'trainerHint';
            hint.className = 'form-text';
            classSelect.parentNode.appendChild(hint);
        }

        hint.textContent = trainer ? 'Assigned trainer: ' + trainer : '';
    });

    // Responsive sidebar.
    const sidebar = document.getElementById('sidebar');
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebarOverlay = document.getElementById('sidebarOverlay');

    function closeSidebar() {
        sidebar.classList.remove('show');
        sidebarOverlay.classList.remove('show');
    }

    sidebarToggle.addEventListener('click', function () {
        sidebar.classList.toggle('show');
        sidebarOverlay.classList.toggle('show');
    });

    sidebarOverlay.addEventListener('click', closeSidebar);

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') {
            closeSidebar();
        }
    });
</script>

</body>
</html>