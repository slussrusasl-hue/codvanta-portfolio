<?php
session_start();

/*
|--------------------------------------------------------------------------
| FitCore Administrator - Class Management
|--------------------------------------------------------------------------
| Demonstration-only page.
| Class records are stored in PHP sessions, not in a database.
|--------------------------------------------------------------------------
*/

if (!isset($_SESSION['fitcore_admin_classes'])) {
    $_SESSION['fitcore_admin_classes'] = [
        [
            'id' => 1,
            'name' => 'Strength & Conditioning',
            'category' => 'Strength',
            'trainer' => 'Alex Morgan',
            'day' => 'Monday',
            'time' => '06:00 AM',
            'duration' => 60,
            'capacity' => 20,
            'enrolled' => 16,
            'level' => 'Intermediate',
            'status' => 'Active'
        ],
        [
            'id' => 2,
            'name' => 'Morning Yoga Flow',
            'category' => 'Yoga',
            'trainer' => 'Sarah Williams',
            'day' => 'Monday',
            'time' => '08:00 AM',
            'duration' => 45,
            'capacity' => 15,
            'enrolled' => 11,
            'level' => 'Beginner',
            'status' => 'Active'
        ],
        [
            'id' => 3,
            'name' => 'HIIT Burn',
            'category' => 'Cardio',
            'trainer' => 'Daniel Carter',
            'day' => 'Tuesday',
            'time' => '05:30 PM',
            'duration' => 45,
            'capacity' => 18,
            'enrolled' => 18,
            'level' => 'Advanced',
            'status' => 'Active'
        ],
        [
            'id' => 4,
            'name' => 'Pilates Fundamentals',
            'category' => 'Pilates',
            'trainer' => 'Sarah Williams',
            'day' => 'Wednesday',
            'time' => '09:00 AM',
            'duration' => 50,
            'capacity' => 14,
            'enrolled' => 9,
            'level' => 'Beginner',
            'status' => 'Active'
        ],
        [
            'id' => 5,
            'name' => 'Power Lifting',
            'category' => 'Strength',
            'trainer' => 'Alex Morgan',
            'day' => 'Thursday',
            'time' => '06:00 PM',
            'duration' => 60,
            'capacity' => 16,
            'enrolled' => 12,
            'level' => 'Advanced',
            'status' => 'Active'
        ],
        [
            'id' => 6,
            'name' => 'Boxing Fitness',
            'category' => 'Cardio',
            'trainer' => 'Daniel Carter',
            'day' => 'Friday',
            'time' => '05:00 PM',
            'duration' => 45,
            'capacity' => 20,
            'enrolled' => 7,
            'level' => 'Intermediate',
            'status' => 'Active'
        ],
        [
            'id' => 7,
            'name' => 'Weekend Yoga',
            'category' => 'Yoga',
            'trainer' => 'Sarah Williams',
            'day' => 'Saturday',
            'time' => '08:30 AM',
            'duration' => 45,
            'capacity' => 15,
            'enrolled' => 0,
            'level' => 'Beginner',
            'status' => 'Inactive'
        ],
        [
            'id' => 8,
            'name' => 'Functional Fitness',
            'category' => 'Functional',
            'trainer' => 'Alex Morgan',
            'day' => 'Sunday',
            'time' => '10:00 AM',
            'duration' => 50,
            'capacity' => 18,
            'enrolled' => 10,
            'level' => 'Intermediate',
            'status' => 'Active'
        ]
    ];
}

if (!isset($_SESSION['fitcore_classes_csrf'])) {
    $_SESSION['fitcore_classes_csrf'] = bin2hex(random_bytes(32));
}

$csrfToken = $_SESSION['fitcore_classes_csrf'];

function classRedirect(string $message, string $type = 'success'): void
{
    $_SESSION['fitcore_classes_flash'] = [
        'message' => $message,
        'type' => $type
    ];

    header('Location: classes.php');
    exit;
}

function classEscape($value): string
{
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

function classPostString(string $key): string
{
    return trim((string)($_POST[$key] ?? ''));
}

function classFindIndex(int $id): int
{
    foreach ($_SESSION['fitcore_admin_classes'] as $index => $class) {
        if ((int)$class['id'] === $id) {
            return $index;
        }
    }

    return -1;
}

/*
|--------------------------------------------------------------------------
| Handle form submissions
|--------------------------------------------------------------------------
*/

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $submittedToken = (string)($_POST['csrf_token'] ?? '');

    if (!hash_equals($csrfToken, $submittedToken)) {
        classRedirect('Your session token is invalid. Please try again.', 'danger');
    }

    $action = classPostString('action');

    if ($action === 'save_class') {
        $classId = (int)($_POST['class_id'] ?? 0);
        $name = classPostString('name');
        $category = classPostString('category');
        $trainer = classPostString('trainer');
        $day = classPostString('day');
        $time = classPostString('time');
        $duration = (int)($_POST['duration'] ?? 0);
        $capacity = (int)($_POST['capacity'] ?? 0);
        $level = classPostString('level');
        $status = classPostString('status');

        $validCategories = [
            'Strength', 'Yoga', 'Cardio', 'Pilates', 'Functional'
        ];

        $validDays = [
            'Monday', 'Tuesday', 'Wednesday', 'Thursday',
            'Friday', 'Saturday', 'Sunday'
        ];

        $validLevels = ['Beginner', 'Intermediate', 'Advanced'];
        $validStatuses = ['Active', 'Inactive'];

        $validTrainers = [
            'Alex Morgan',
            'Sarah Williams',
            'Daniel Carter'
        ];

        if (
            $name === '' ||
            strlen($name) > 100 ||
            !in_array($category, $validCategories, true) ||
            !in_array($trainer, $validTrainers, true) ||
            !in_array($day, $validDays, true) ||
            !preg_match('/^\d{2}:\d{2}$/', $time) ||
            !in_array($level, $validLevels, true) ||
            !in_array($status, $validStatuses, true) ||
            $duration < 15 ||
            $duration > 240 ||
            $capacity < 1 ||
            $capacity > 200
        ) {
            classRedirect(
                'Please complete all fields with valid class details.',
                'danger'
            );
        }

        if ($classId > 0) {
            $index = classFindIndex($classId);

            if ($index === -1) {
                classRedirect('The selected class could not be found.', 'danger');
            }

            $existingEnrolled = (int)$_SESSION['fitcore_admin_classes'][$index]['enrolled'];

            if ($capacity < $existingEnrolled) {
                classRedirect(
                    'Capacity cannot be lower than the number of enrolled members (' .
                    $existingEnrolled . ').',
                    'danger'
                );
            }

            $_SESSION['fitcore_admin_classes'][$index] = [
                'id' => $classId,
                'name' => $name,
                'category' => $category,
                'trainer' => $trainer,
                'day' => $day,
                'time' => $time,
                'duration' => $duration,
                'capacity' => $capacity,
                'enrolled' => $existingEnrolled,
                'level' => $level,
                'status' => $status
            ];

            classRedirect('Class details updated successfully in this demo.');
        }

        $newId = 1;

        foreach ($_SESSION['fitcore_admin_classes'] as $existingClass) {
            $newId = max($newId, (int)$existingClass['id'] + 1);
        }

        $_SESSION['fitcore_admin_classes'][] = [
            'id' => $newId,
            'name' => $name,
            'category' => $category,
            'trainer' => $trainer,
            'day' => $day,
            'time' => $time,
            'duration' => $duration,
            'capacity' => $capacity,
            'enrolled' => 0,
            'level' => $level,
            'status' => $status
        ];

        classRedirect('New fitness class added successfully in this demo.');
    }

    if ($action === 'toggle_status') {
        $classId = (int)($_POST['class_id'] ?? 0);
        $index = classFindIndex($classId);

        if ($index === -1) {
            classRedirect('The selected class could not be found.', 'danger');
        }

        $currentStatus = $_SESSION['fitcore_admin_classes'][$index]['status'];

        $_SESSION['fitcore_admin_classes'][$index]['status'] =
            $currentStatus === 'Active' ? 'Inactive' : 'Active';

        classRedirect(
            'Class status changed to ' .
            $_SESSION['fitcore_admin_classes'][$index]['status'] .
            ' in this demo.'
        );
    }

    if ($action === 'delete_class') {
        $classId = (int)($_POST['class_id'] ?? 0);
        $index = classFindIndex($classId);

        if ($index === -1) {
            classRedirect('The selected class could not be found.', 'danger');
        }

        array_splice($_SESSION['fitcore_admin_classes'], $index, 1);

        classRedirect('Class deleted successfully from this demo.');
    }

    classRedirect('Invalid request. Please try again.', 'danger');
}

/*
|--------------------------------------------------------------------------
| Flash message
|--------------------------------------------------------------------------
*/

$flash = $_SESSION['fitcore_classes_flash'] ?? null;
unset($_SESSION['fitcore_classes_flash']);

/*
|--------------------------------------------------------------------------
| Search and filtering
|--------------------------------------------------------------------------
*/

$allClasses = $_SESSION['fitcore_admin_classes'];

$search = trim((string)($_GET['search'] ?? ''));
$categoryFilter = trim((string)($_GET['category'] ?? ''));
$statusFilter = trim((string)($_GET['status'] ?? ''));

$filteredClasses = array_filter(
    $allClasses,
    function ($class) use ($search, $categoryFilter, $statusFilter) {
        $searchText = strtolower(
            $class['name'] . ' ' .
            $class['trainer'] . ' ' .
            $class['category'] . ' ' .
            $class['day']
        );

        $matchesSearch = $search === '' ||
            strpos($searchText, strtolower($search)) !== false;

        $matchesCategory = $categoryFilter === '' ||
            $class['category'] === $categoryFilter;

        $matchesStatus = $statusFilter === '' ||
            $class['status'] === $statusFilter;

        return $matchesSearch && $matchesCategory && $matchesStatus;
    }
);

$filteredClasses = array_values($filteredClasses);

/*
|--------------------------------------------------------------------------
| Dashboard statistics
|--------------------------------------------------------------------------
*/

$totalClasses = count($allClasses);
$activeClasses = 0;
$inactiveClasses = 0;
$totalCapacity = 0;
$totalEnrolled = 0;
$trainerNames = [];

foreach ($allClasses as $class) {
    if ($class['status'] === 'Active') {
        $activeClasses++;
    } else {
        $inactiveClasses++;
    }

    $totalCapacity += (int)$class['capacity'];
    $totalEnrolled += (int)$class['enrolled'];
    $trainerNames[$class['trainer']] = true;
}

$totalTrainers = count($trainerNames);
$occupancy = $totalCapacity > 0
    ? round(($totalEnrolled / $totalCapacity) * 100)
    : 0;

function classTimeDisplay(string $time): string
{
    $timestamp = strtotime($time);

    return $timestamp ? date('h:i A', $timestamp) : $time;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Class Management | FitCore Admin</title>

    <link rel="stylesheet" href="../../../assets/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.3.1/css/all.min.css" integrity="sha512-..." crossorigin="anonymous" referrerpolicy="no-referrer" />


    <style>
        :root {
            --fc-bg: #101010;
            --fc-sidebar: #171717;
            --fc-card: #1b1b1b;
            --fc-card-soft: #202020;
            --fc-orange: #ff6b2c;
            --fc-orange-hover: #ff824d;
            --fc-text: #f4f4f4;
            --fc-muted: #a2a2a2;
            --fc-border: #303030;
            --fc-green: #36c98f;
            --fc-red: #ff6868;
            --fc-yellow: #f3bd4f;
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

        .stat-label {
            color: var(--fc-muted);
            font-size: 12px;
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
            grid-template-columns: minmax(180px, 1fr) 190px 160px auto;
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

        .table-responsive {
            width: 100%;
        }

        .table {
            --bs-table-bg: transparent;
            --bs-table-color: #ddd;
            --bs-table-border-color: var(--fc-border);
            margin: 0;
            white-space: nowrap;
            vertical-align: middle;
        }

        .table thead th {
            color: #929292;
            background: #191919;
            font-size: 10px;
            letter-spacing: .8px;
            text-transform: uppercase;
            font-weight: 800;
            padding: 15px 18px;
            border-bottom: 1px solid var(--fc-border);
        }

        .table tbody td {
            padding: 16px 18px;
            font-size: 12px;
        }

        .table tbody tr:hover td {
            background: rgba(255,255,255,.018);
        }

        .class-name {
            display: flex;
            align-items: center;
            gap: 11px;
        }

        .class-icon {
            width: 39px;
            height: 39px;
            border-radius: 10px;
            background: rgba(255, 107, 44, .12);
            color: var(--fc-orange);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            font-size: 15px;
        }

        .class-name strong {
            display: block;
            color: #f5f5f5;
            font-size: 12px;
            margin-bottom: 5px;
        }

        .class-name small,
        .secondary-text {
            color: var(--fc-muted);
            font-size: 11px;
        }

        .category-badge {
            display: inline-block;
            padding: 5px 9px;
            border-radius: 6px;
            background: #292929;
            color: #d4d4d4;
            font-size: 10px;
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

        .status-active {
            background: rgba(54, 201, 143, .12);
            color: var(--fc-green);
        }

        .status-inactive {
            background: rgba(255, 104, 104, .12);
            color: var(--fc-red);
        }

        .status-badge::before {
            content: "";
            width: 5px;
            height: 5px;
            border-radius: 50%;
            background: currentColor;
        }

        .capacity-cell {
            min-width: 120px;
        }

        .capacity-text {
            display: flex;
            justify-content: space-between;
            gap: 8px;
            margin-bottom: 7px;
            font-size: 10px;
        }

        .progress {
            height: 5px;
            background: #353535;
            border-radius: 20px;
        }

        .progress-bar {
            background: var(--fc-orange);
            border-radius: 20px;
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

        .occupancy-panel {
            padding: 22px;
            background: var(--fc-card);
            border: 1px solid var(--fc-border);
            border-radius: 13px;
            height: 100%;
        }

        .occupancy-panel h6 {
            font-size: 14px;
            font-weight: 800;
            margin-bottom: 8px;
        }

        .occupancy-panel p {
            font-size: 12px;
            color: var(--fc-muted);
            margin-bottom: 20px;
        }

        .occupancy-number {
            font-size: 31px;
            font-weight: 800;
            margin-bottom: 10px;
        }

        .occupancy-number span {
            font-size: 13px;
            color: var(--fc-muted);
            font-weight: 400;
        }

        .category-row {
            display: flex;
            justify-content: space-between;
            gap: 15px;
            padding: 11px 0;
            border-bottom: 1px solid var(--fc-border);
            font-size: 12px;
        }

        .category-row:last-child {
            border-bottom: 0;
        }

        .category-row span:last-child {
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

        .form-text {
            color: #888;
            font-size: 11px;
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

        .sidebar-overlay {
            display: none;
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
                transition: transform .25s ease;
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
            .occupancy-panel {
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
        <a href="classes.php" class="nav-link active">
            <i class="fas fa-calendar-alt"></i> Classes
        </a>
        <a href="attendance.php" class="nav-link">
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
                <h1 class="page-title">Class Management</h1>
                <div class="page-subtitle">
                    Create, organize, and manage FitCore fitness classes.
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

            <button class="btn btn-orange" type="button"
                    onclick="openAddModal()">
                <i class="fas fa-plus me-1"></i> Add Class
            </button>
        </div>
    </header>

    <?php if ($flash): ?>
        <div class="alert alert-<?php echo classEscape($flash['type']); ?> alert-dismissible fade show"
             role="alert">
            <?php echo classEscape($flash['message']); ?>
            <button type="button" class="btn-close"
                    data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <section class="row g-3 mb-4">
        <div class="col-6 col-xl-3">
            <div class="stat-card">
                <div class="stat-top">
                    <span class="stat-label">Total Classes</span>
                    <div class="stat-icon"><i class="fas fa-calendar-alt"></i></div>
                </div>
                <div class="stat-value"><?php echo $totalClasses; ?></div>
                <div class="stat-note">All registered class schedules</div>
            </div>
        </div>

        <div class="col-6 col-xl-3">
            <div class="stat-card">
                <div class="stat-top">
                    <span class="stat-label">Active Classes</span>
                    <div class="stat-icon"><i class="fas fa-check-circle"></i></div>
                </div>
                <div class="stat-value"><?php echo $activeClasses; ?></div>
                <div class="stat-note">
                    <span style="color: var(--fc-green);">
                        <?php echo $inactiveClasses; ?> inactive
                    </span>
                </div>
            </div>
        </div>

        <div class="col-6 col-xl-3">
            <div class="stat-card">
                <div class="stat-top">
                    <span class="stat-label">Total Capacity</span>
                    <div class="stat-icon"><i class="fas fa-users"></i></div>
                </div>
                <div class="stat-value"><?php echo $totalCapacity; ?></div>
                <div class="stat-note">
                    <?php echo $totalEnrolled; ?> current class enrolments
                </div>
            </div>
        </div>

        <div class="col-6 col-xl-3">
            <div class="stat-card">
                <div class="stat-top">
                    <span class="stat-label">Assigned Trainers</span>
                    <div class="stat-icon"><i class="fas fa-user-tie"></i></div>
                </div>
                <div class="stat-value"><?php echo $totalTrainers; ?></div>
                <div class="stat-note">Trainers assigned to listed classes</div>
            </div>
        </div>
    </section>

    <section class="panel mb-4">
        <div class="panel-header">
            <div>
                <h5><i class="fas fa-list-ul me-2"
                       style="color: var(--fc-orange);"></i>Fitness Classes</h5>
                <p>Manage class schedules, trainers, capacity, and availability.</p>
            </div>

            <div class="d-flex align-items-center gap-2 no-print">
                <button class="btn btn-outline-light btn-sm"
                        type="button" onclick="window.print()">
                    <i class="fas fa-print me-1"></i> Print
                </button>
                <span class="category-badge">
                    <?php echo count($filteredClasses); ?> result(s)
                </span>
            </div>
        </div>

        <form class="filters no-print" method="GET" action="classes.php">
            <input
                type="search"
                class="form-control"
                name="search"
                placeholder="Search class, trainer, day..."
                value="<?php echo classEscape($search); ?>"
            >

            <select name="category" class="form-select">
                <option value="">All Categories</option>
                <?php foreach (['Strength', 'Yoga', 'Cardio', 'Pilates', 'Functional'] as $category): ?>
                    <option value="<?php echo classEscape($category); ?>"
                        <?php echo $categoryFilter === $category ? 'selected' : ''; ?>>
                        <?php echo classEscape($category); ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <select name="status" class="form-select">
                <option value="">All Statuses</option>
                <option value="Active" <?php echo $statusFilter === 'Active' ? 'selected' : ''; ?>>
                    Active
                </option>
                <option value="Inactive" <?php echo $statusFilter === 'Inactive' ? 'selected' : ''; ?>>
                    Inactive
                </option>
            </select>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-orange flex-grow-1">
                    <i class="fas fa-search me-1"></i> Search
                </button>
                <a href="classes.php" class="btn btn-outline-light"
                   title="Reset filters">
                    <i class="fas fa-redo"></i>
                </a>
            </div>
        </form>

        <?php if (count($filteredClasses) > 0): ?>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                    <tr>
                        <th>Class Details</th>
                        <th>Trainer</th>
                        <th>Schedule</th>
                        <th>Capacity</th>
                        <th>Level</th>
                        <th>Status</th>
                        <th class="action-column">Actions</th>
                    </tr>
                    </thead>

                    <tbody>
                    <?php foreach ($filteredClasses as $class): ?>
                        <?php
                        $enrolled = (int)$class['enrolled'];
                        $capacity = max(1, (int)$class['capacity']);
                        $percentage = min(100, round(($enrolled / $capacity) * 100));

                        $icon = match ($class['category']) {
                            'Strength' => 'fa-dumbbell',
                            'Yoga' => 'fa-spa',
                            'Cardio' => 'fa-heartbeat',
                            'Pilates' => 'fa-child',
                            'Functional' => 'fa-running',
                            default => 'fa-calendar-alt'
                        };
                        ?>

                        <tr>
                            <td>
                                <div class="class-name">
                                    <div class="class-icon">
                                        <i class="fas <?php echo classEscape($icon); ?>"></i>
                                    </div>
                                    <div>
                                        <strong><?php echo classEscape($class['name']); ?></strong>
                                        <small>
                                            <span class="category-badge">
                                                <?php echo classEscape($class['category']); ?>
                                            </span>
                                            <span class="ms-1">
                                                <?php echo (int)$class['duration']; ?> min
                                            </span>
                                        </small>
                                    </div>
                                </div>
                            </td>

                            <td>
                                <strong><?php echo classEscape($class['trainer']); ?></strong>
                            </td>

                            <td>
                                <strong><?php echo classEscape($class['day']); ?></strong>
                                <div class="secondary-text mt-1">
                                    <?php echo classEscape(classTimeDisplay($class['time'])); ?>
                                </div>
                            </td>

                            <td class="capacity-cell">
                                <div class="capacity-text">
                                    <span><?php echo $enrolled; ?> enrolled</span>
                                    <span><?php echo (int)$class['capacity']; ?> max</span>
                                </div>
                                <div class="progress">
                                    <div class="progress-bar"
                                         style="width: <?php echo $percentage; ?>%;"></div>
                                </div>
                            </td>

                            <td>
                                <span class="secondary-text">
                                    <?php echo classEscape($class['level']); ?>
                                </span>
                            </td>

                            <td>
                                <span class="status-badge <?php echo $class['status'] === 'Active' ? 'status-active' : 'status-inactive'; ?>">
                                    <?php echo classEscape($class['status']); ?>
                                </span>
                            </td>

                            <td class="action-column">
                                <button type="button" class="action-btn"
                                        title="Edit class"
                                        onclick='openEditModal(<?php echo json_encode($class, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP); ?>)'>
                                    <i class="fas fa-edit"></i>
                                </button>

                                <form method="POST" action="classes.php"
                                      class="d-inline">
                                    <input type="hidden" name="csrf_token"
                                           value="<?php echo classEscape($csrfToken); ?>">
                                    <input type="hidden" name="action"
                                           value="toggle_status">
                                    <input type="hidden" name="class_id"
                                           value="<?php echo (int)$class['id']; ?>">

                                    <button type="submit" class="action-btn"
                                            title="<?php echo $class['status'] === 'Active' ? 'Deactivate class' : 'Activate class'; ?>"
                                            onclick="return confirm('Change the status of this class?');">
                                        <i class="fas <?php echo $class['status'] === 'Active' ? 'fa-pause' : 'fa-play'; ?>"></i>
                                    </button>
                                </form>

                                <form method="POST" action="classes.php"
                                      class="d-inline"
                                      onsubmit="return confirm('Are you sure you want to delete this class from the demo?');">
                                    <input type="hidden" name="csrf_token"
                                           value="<?php echo classEscape($csrfToken); ?>">
                                    <input type="hidden" name="action"
                                           value="delete_class">
                                    <input type="hidden" name="class_id"
                                           value="<?php echo (int)$class['id']; ?>">

                                    <button type="submit"
                                            class="action-btn delete"
                                            title="Delete class">
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
                <i class="fas fa-calendar-times d-block"></i>
                <h5 class="text-white">No classes found</h5>
                <p class="mb-3">Try changing your search or filter criteria.</p>
                <a href="classes.php" class="btn btn-outline-light btn-sm">
                    Clear Filters
                </a>
            </div>
        <?php endif; ?>
    </section>

    <section class="row g-3 mb-4">
        <div class="col-lg-5">
            <div class="occupancy-panel">
                <h6><i class="fas fa-chart-pie me-2"
                       style="color: var(--fc-orange);"></i>Class Occupancy</h6>
                <p>Current enrolments compared with the total available capacity.</p>

                <div class="occupancy-number">
                    <?php echo $occupancy; ?>%
                    <span>overall occupancy</span>
                </div>

                <div class="progress mb-3" style="height: 9px;">
                    <div class="progress-bar"
                         style="width: <?php echo $occupancy; ?>%;"></div>
                </div>

                <div class="d-flex justify-content-between secondary-text">
                    <span><?php echo $totalEnrolled; ?> enrolled places</span>
                    <span><?php echo max(0, $totalCapacity - $totalEnrolled); ?> places remaining</span>
                </div>
            </div>
        </div>

        <div class="col-lg-7">
            <div class="occupancy-panel">
                <h6><i class="fas fa-layer-group me-2"
                       style="color: var(--fc-orange);"></i>Classes by Category</h6>
                <p>Overview of the fitness activities offered by FitCore.</p>

                <?php
                $categoryCounts = [
                    'Strength' => 0,
                    'Yoga' => 0,
                    'Cardio' => 0,
                    'Pilates' => 0,
                    'Functional' => 0
                ];

                foreach ($allClasses as $class) {
                    if (isset($categoryCounts[$class['category']])) {
                        $categoryCounts[$class['category']]++;
                    }
                }
                ?>

                <?php foreach ($categoryCounts as $category => $count): ?>
                    <div class="category-row">
                        <span><?php echo classEscape($category); ?></span>
                        <span>
                            <?php echo $count; ?>
                            <?php echo $count === 1 ? 'class' : 'classes'; ?>
                        </span>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <div class="text-center secondary-text pb-3">
        FitCore Administration Demo &copy; <?php echo date('Y'); ?>.
        Class information is stored in the current PHP session only.
    </div>

</main>

<!-- Add / Edit Class Modal -->
<div class="modal fade" id="classModal" tabindex="-1"
     aria-labelledby="classModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <form method="POST" action="classes.php" id="classForm">
                <div class="modal-header">
                    <div>
                        <h5 class="modal-title" id="classModalLabel">Add New Class</h5>
                        <div class="secondary-text mt-1">
                            Enter the fitness class information below.
                        </div>
                    </div>
                    <button type="button" class="btn-close"
                            data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body p-4">
                    <input type="hidden" name="csrf_token"
                           value="<?php echo classEscape($csrfToken); ?>">
                    <input type="hidden" name="action" value="save_class">
                    <input type="hidden" name="class_id" id="class_id" value="0">

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label" for="class_name">
                                Class Name *
                            </label>
                            <input type="text" class="form-control"
                                   id="class_name" name="name"
                                   maxlength="100" required
                                   placeholder="e.g. Morning Strength Training">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label" for="category">
                                Category *
                            </label>
                            <select class="form-select" id="category"
                                    name="category" required>
                                <option value="">Select category</option>
                                <option value="Strength">Strength</option>
                                <option value="Yoga">Yoga</option>
                                <option value="Cardio">Cardio</option>
                                <option value="Pilates">Pilates</option>
                                <option value="Functional">Functional</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label" for="trainer">
                                Assigned Trainer *
                            </label>
                            <select class="form-select" id="trainer"
                                    name="trainer" required>
                                <option value="">Select trainer</option>
                                <option value="Alex Morgan">Alex Morgan</option>
                                <option value="Sarah Williams">Sarah Williams</option>
                                <option value="Daniel Carter">Daniel Carter</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label" for="day">
                                Class Day *
                            </label>
                            <select class="form-select" id="day"
                                    name="day" required>
                                <option value="">Select day</option>
                                <option>Monday</option>
                                <option>Tuesday</option>
                                <option>Wednesday</option>
                                <option>Thursday</option>
                                <option>Friday</option>
                                <option>Saturday</option>
                                <option>Sunday</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label" for="time">
                                Start Time *
                            </label>
                            <input type="time" class="form-control"
                                   id="time" name="time" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label" for="duration">
                                Duration (minutes) *
                            </label>
                            <input type="number" class="form-control"
                                   id="duration" name="duration"
                                   min="15" max="240" value="45" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label" for="capacity">
                                Maximum Capacity *
                            </label>
                            <input type="number" class="form-control"
                                   id="capacity" name="capacity"
                                   min="1" max="200" value="20" required>
                            <div class="form-text">
                                The maximum number of members who can enrol.
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label" for="level">
                                Difficulty Level *
                            </label>
                            <select class="form-select" id="level"
                                    name="level" required>
                                <option value="Beginner">Beginner</option>
                                <option value="Intermediate" selected>Intermediate</option>
                                <option value="Advanced">Advanced</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label" for="status">
                                Class Status *
                            </label>
                            <select class="form-select" id="status"
                                    name="status" required>
                                <option value="Active">Active</option>
                                <option value="Inactive">Inactive</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="modal-footer p-3">
                    <button type="button" class="btn btn-outline-light"
                            data-bs-dismiss="modal">
                        Cancel
                    </button>
                    <button type="submit" class="btn btn-orange">
                        <i class="fas fa-save me-1"></i>
                        Save Class
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>


<script>
    const classModalElement = document.getElementById('classModal');
    const classModal = new bootstrap.Modal(classModalElement);

    function openAddModal() {
        document.getElementById('classForm').reset();
        document.getElementById('class_id').value = '0';
        document.getElementById('classModalLabel').textContent = 'Add New Class';
        document.getElementById('duration').value = '45';
        document.getElementById('capacity').value = '20';
        document.getElementById('level').value = 'Intermediate';
        document.getElementById('status').value = 'Active';

        classModal.show();
    }

    function openEditModal(classData) {
        document.getElementById('classForm').reset();

        document.getElementById('class_id').value = classData.id;
        document.getElementById('class_name').value = classData.name;
        document.getElementById('category').value = classData.category;
        document.getElementById('trainer').value = classData.trainer;
        document.getElementById('day').value = classData.day;
        document.getElementById('time').value = classData.time;
        document.getElementById('duration').value = classData.duration;
        document.getElementById('capacity').value = classData.capacity;
        document.getElementById('level').value = classData.level;
        document.getElementById('status').value = classData.status;

        document.getElementById('classModalLabel').textContent = 'Edit Class';
        classModal.show();
    }

    // Responsive sidebar for smaller screens.
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