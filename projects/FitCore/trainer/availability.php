
<?php
session_start();
date_default_timezone_set('Asia/Colombo');

/*
|--------------------------------------------------------------------------
| FitCore Trainer Portal - Availability Management
|--------------------------------------------------------------------------
| Demonstration features:
| - Enable or disable weekly working days
| - Set start and end times
| - Configure break times
| - Block specific dates
| - Remove blocked dates
|
| Settings are stored in PHP sessions, not a database.
*/

$trainerName = "Alex Morgan";
$trainerSpecialty = "Strength & Conditioning";

function e($value)
{
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

$days = [
    'Monday',
    'Tuesday',
    'Wednesday',
    'Thursday',
    'Friday',
    'Saturday',
    'Sunday'
];

/*
|--------------------------------------------------------------------------
| Default weekly availability
|--------------------------------------------------------------------------
*/

$defaultAvailability = [
    'Monday' => [
        'enabled' => true,
        'start' => '08:00',
        'end' => '17:00',
        'break_start' => '12:00',
        'break_end' => '13:00'
    ],
    'Tuesday' => [
        'enabled' => true,
        'start' => '08:00',
        'end' => '17:00',
        'break_start' => '12:00',
        'break_end' => '13:00'
    ],
    'Wednesday' => [
        'enabled' => true,
        'start' => '09:00',
        'end' => '18:00',
        'break_start' => '13:00',
        'break_end' => '14:00'
    ],
    'Thursday' => [
        'enabled' => true,
        'start' => '08:00',
        'end' => '17:00',
        'break_start' => '12:00',
        'break_end' => '13:00'
    ],
    'Friday' => [
        'enabled' => true,
        'start' => '08:00',
        'end' => '16:00',
        'break_start' => '12:00',
        'break_end' => '13:00'
    ],
    'Saturday' => [
        'enabled' => true,
        'start' => '08:00',
        'end' => '13:00',
        'break_start' => '',
        'break_end' => ''
    ],
    'Sunday' => [
        'enabled' => false,
        'start' => '09:00',
        'end' => '13:00',
        'break_start' => '',
        'break_end' => ''
    ]
];

if (!isset($_SESSION['fitcore_trainer_availability'])) {
    $_SESSION['fitcore_trainer_availability'] = $defaultAvailability;
}

if (!isset($_SESSION['fitcore_trainer_blocked_dates'])) {
    $_SESSION['fitcore_trainer_blocked_dates'] = [
        date('Y-m-d', strtotime('+7 days')) => 'Personal commitment',
        date('Y-m-d', strtotime('+14 days')) => 'Day off'
    ];
}

if (empty($_SESSION['fitcore_trainer_availability_csrf'])) {
    $_SESSION['fitcore_trainer_availability_csrf'] = bin2hex(random_bytes(32));
}

$csrfToken = $_SESSION['fitcore_trainer_availability_csrf'];

$flashMessage = $_SESSION['fitcore_trainer_availability_flash'] ?? null;
unset($_SESSION['fitcore_trainer_availability_flash']);

/*
|--------------------------------------------------------------------------
| Helpers
|--------------------------------------------------------------------------
*/

function validTime($time)
{
    if (!is_string($time)) {
        return false;
    }

    $parsed = DateTime::createFromFormat('!H:i', $time);

    return $parsed !== false && $parsed->format('H:i') === $time;
}

function timeToMinutes($time)
{
    [$hours, $minutes] = array_map('intval', explode(':', $time));

    return ($hours * 60) + $minutes;
}

function formatTime($time)
{
    if ($time === '') {
        return 'Not set';
    }

    return date('h:i A', strtotime($time));
}

function calculateWorkingHours($settings)
{
    $totalMinutes = 0;

    foreach ($settings as $day) {
        if (empty($day['enabled'])) {
            continue;
        }

        $start = timeToMinutes($day['start']);
        $end = timeToMinutes($day['end']);

        $minutes = $end - $start;

        if (
            $day['break_start'] !== '' &&
            $day['break_end'] !== ''
        ) {
            $minutes -= (
                timeToMinutes($day['break_end']) -
                timeToMinutes($day['break_start'])
            );
        }

        $totalMinutes += max(0, $minutes);
    }

    return $totalMinutes;
}

/*
|--------------------------------------------------------------------------
| Handle form submissions
|--------------------------------------------------------------------------
*/

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $submittedToken = $_POST['csrf_token'] ?? '';

    if (
        !is_string($submittedToken) ||
        !hash_equals($csrfToken, $submittedToken)
    ) {
        $_SESSION['fitcore_trainer_availability_flash'] = [
            'type' => 'danger',
            'message' => 'Your request could not be verified. Please try again.'
        ];

        header('Location: availability.php');
        exit;
    }

    $action = $_POST['action'] ?? '';

    /*
    | Save weekly schedule
    */

    if ($action === 'save_schedule') {

        $newAvailability = [];
        $errors = [];

        $enabledDays = $_POST['enabled_days'] ?? [];
        $startTimes = $_POST['start'] ?? [];
        $endTimes = $_POST['end'] ?? [];
        $breakStarts = $_POST['break_start'] ?? [];
        $breakEnds = $_POST['break_end'] ?? [];

        if (!is_array($enabledDays)) {
            $enabledDays = [];
        }

        if (!is_array($startTimes)) {
            $startTimes = [];
        }

        if (!is_array($endTimes)) {
            $endTimes = [];
        }

        if (!is_array($breakStarts)) {
            $breakStarts = [];
        }

        if (!is_array($breakEnds)) {
            $breakEnds = [];
        }

        foreach ($days as $day) {

            $enabled = in_array($day, $enabledDays, true);

            $start = $startTimes[$day] ?? '';
            $end = $endTimes[$day] ?? '';
            $breakStart = $breakStarts[$day] ?? '';
            $breakEnd = $breakEnds[$day] ?? '';

            if (
                !is_string($start) ||
                !is_string($end) ||
                !is_string($breakStart) ||
                !is_string($breakEnd)
            ) {
                $errors[] = "$day contains invalid time values.";
                continue;
            }

            if (
                !validTime($start) ||
                !validTime($end)
            ) {
                $errors[] = "$day must have valid start and end times.";
                continue;
            }

            if (timeToMinutes($start) >= timeToMinutes($end)) {
                $errors[] = "$day's end time must be later than its start time.";
                continue;
            }

            $hasBreakStart = $breakStart !== '';
            $hasBreakEnd = $breakEnd !== '';

            if ($hasBreakStart !== $hasBreakEnd) {
                $errors[] = "$day must have both break times or leave both empty.";
                continue;
            }

            if ($hasBreakStart && $hasBreakEnd) {

                if (
                    !validTime($breakStart) ||
                    !validTime($breakEnd)
                ) {
                    $errors[] = "$day has invalid break times.";
                    continue;
                }

                $startMinutes = timeToMinutes($start);
                $endMinutes = timeToMinutes($end);
                $breakStartMinutes = timeToMinutes($breakStart);
                $breakEndMinutes = timeToMinutes($breakEnd);

                if ($breakStartMinutes >= $breakEndMinutes) {
                    $errors[] = "$day's break end must be later than its break start.";
                    continue;
                }

                if (
                    $breakStartMinutes < $startMinutes ||
                    $breakEndMinutes > $endMinutes
                ) {
                    $errors[] = "$day's break must fall within working hours.";
                    continue;
                }
            }

            $newAvailability[$day] = [
                'enabled' => $enabled,
                'start' => $start,
                'end' => $end,
                'break_start' => $breakStart,
                'break_end' => $breakEnd
            ];
        }

        if (empty($errors) && count($newAvailability) === count($days)) {

            $_SESSION['fitcore_trainer_availability'] = $newAvailability;

            $_SESSION['fitcore_trainer_availability_flash'] = [
                'type' => 'success',
                'message' => 'Your weekly availability has been saved successfully.'
            ];

        } else {

            $_SESSION['fitcore_trainer_availability_flash'] = [
                'type' => 'danger',
                'message' => implode(' ', $errors)
            ];
        }

        header('Location: availability.php');
        exit;
    }

    /*
    | Add a blocked date
    */

    if ($action === 'add_blocked_date') {

        $blockedDate = $_POST['blocked_date'] ?? '';
        $reason = trim($_POST['reason'] ?? '');

        $dateObject = is_string($blockedDate)
            ? DateTime::createFromFormat('!Y-m-d', $blockedDate)
            : false;

        $validDate = $dateObject !== false &&
            $dateObject->format('Y-m-d') === $blockedDate;

        if (
            !$validDate ||
            $blockedDate < date('Y-m-d')
        ) {
            $_SESSION['fitcore_trainer_availability_flash'] = [
                'type' => 'danger',
                'message' => 'Choose today or a future date to block.'
            ];

        } elseif (strlen($reason) > 100) {

            $_SESSION['fitcore_trainer_availability_flash'] = [
                'type' => 'danger',
                'message' => 'The reason must not exceed 100 characters.'
            ];

        } else {

            $_SESSION['fitcore_trainer_blocked_dates'][$blockedDate] =
                $reason !== '' ? $reason : 'Unavailable';

            $_SESSION['fitcore_trainer_availability_flash'] = [
                'type' => 'success',
                'message' => 'The selected date has been blocked successfully.'
            ];
        }

        header('Location: availability.php');
        exit;
    }

    /*
    | Remove a blocked date
    */

    if ($action === 'remove_blocked_date') {

        $blockedDate = $_POST['blocked_date'] ?? '';

        if (
            is_string($blockedDate) &&
            isset($_SESSION['fitcore_trainer_blocked_dates'][$blockedDate])
        ) {
            unset($_SESSION['fitcore_trainer_blocked_dates'][$blockedDate]);

            $_SESSION['fitcore_trainer_availability_flash'] = [
                'type' => 'success',
                'message' => 'The blocked date has been removed.'
            ];

        } else {

            $_SESSION['fitcore_trainer_availability_flash'] = [
                'type' => 'warning',
                'message' => 'The selected blocked date could not be found.'
            ];
        }

        header('Location: availability.php');
        exit;
    }

    /*
    | Restore default availability
    */

    if ($action === 'reset_schedule') {

        $_SESSION['fitcore_trainer_availability'] = $defaultAvailability;

        $_SESSION['fitcore_trainer_availability_flash'] = [
            'type' => 'success',
            'message' => 'The default weekly schedule has been restored.'
        ];

        header('Location: availability.php');
        exit;
    }

    $_SESSION['fitcore_trainer_availability_flash'] = [
        'type' => 'danger',
        'message' => 'Unknown action.'
    ];

    header('Location: availability.php');
    exit;
}

/*
|--------------------------------------------------------------------------
| Load page data
|--------------------------------------------------------------------------
*/

$availability = $_SESSION['fitcore_trainer_availability'];
$blockedDates = $_SESSION['fitcore_trainer_blocked_dates'];

ksort($blockedDates);

$activeDays = 0;

foreach ($availability as $daySettings) {
    if (!empty($daySettings['enabled'])) {
        $activeDays++;
    }
}

$totalMinutes = calculateWorkingHours($availability);
$totalHours = number_format($totalMinutes / 60, 1);

$todayName = date('l');
$todayAvailability = $availability[$todayName];

$blockedDatesCount = count($blockedDates);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Manage Availability | FitCore Trainer Portal</title>

    <link rel="stylesheet" href="../../../assets/bootstrap/bootstrap.min.css">

    <style>
        :root {
            --fc-bg: #101010;
            --fc-card: #191919;
            --fc-card-light: #202020;
            --fc-orange: #ff6b2c;
            --fc-orange-hover: #ff824c;
            --fc-text: #f5f5f5;
            --fc-muted: #a5a5a5;
            --fc-border: #303030;
            --fc-green: #38c987;
            --fc-yellow: #ffc857;
            --fc-red: #ff6969;
            --fc-blue: #63a8ff;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: var(--fc-bg);
            color: var(--fc-text);
            font-family: "Trebuchet MS", Arial, sans-serif;
            min-height: 100vh;
        }

        a {
            text-decoration: none;
        }

        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            width: 255px;
            padding: 25px 17px;
            background: #151515;
            border-right: 1px solid var(--fc-border);
            overflow-y: auto;
            z-index: 1000;
            transition: transform .25s ease;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 11px;
            padding: 0 10px 28px;
            border-bottom: 1px solid var(--fc-border);
            color: var(--fc-text);
        }

        .brand-icon {
            width: 43px;
            height: 43px;
            border-radius: 12px;
            display: flex;
            justify-content: center;
            align-items: center;
            background: var(--fc-orange);
            color: #111;
            font-size: 24px;
            font-weight: bold;
        }

        .brand-name {
            font-size: 23px;
            font-weight: bold;
            letter-spacing: -.5px;
        }

        .brand-name span {
            color: var(--fc-orange);
        }

        .brand-subtitle {
            color: var(--fc-muted);
            font-size: 11px;
            margin-top: 1px;
        }

        .sidebar-label {
            color: #777;
            font-size: 10px;
            font-weight: bold;
            letter-spacing: 1.6px;
            text-transform: uppercase;
            padding: 25px 12px 10px;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 13px;
            border-radius: 9px;
            color: #bcbcbc;
            margin-bottom: 5px;
            font-size: 14px;
            transition: .2s;
        }

        .sidebar-link:hover,
        .sidebar-link.active {
            background: rgba(255, 107, 44, .13);
            color: var(--fc-orange);
        }

        .nav-icon {
            width: 22px;
            text-align: center;
            font-size: 17px;
        }

        .sidebar-bottom {
            margin-top: 30px;
            padding: 15px 12px;
            border: 1px solid var(--fc-border);
            background: #1b1b1b;
            border-radius: 12px;
        }

        .sidebar-bottom p {
            color: var(--fc-muted);
            font-size: 12px;
            margin-bottom: 10px;
        }

        .sidebar-bottom a {
            color: var(--fc-orange);
            font-size: 12px;
        }

        .main-content {
            margin-left: 255px;
            padding: 27px 32px 35px;
            min-height: 100vh;
        }

        .topbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            margin-bottom: 26px;
        }

        .page-title {
            font-size: 27px;
            font-weight: bold;
            margin: 0 0 5px;
        }

        .page-subtitle {
            font-size: 13px;
            color: var(--fc-muted);
            margin: 0;
        }

        .profile {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .avatar {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            background: rgba(255, 107, 44, .17);
            border: 1px solid rgba(255, 107, 44, .45);
            color: var(--fc-orange);
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 13px;
            font-weight: bold;
            flex-shrink: 0;
        }

        .profile-name {
            font-size: 13px;
            font-weight: bold;
        }

        .profile-role {
            font-size: 11px;
            color: var(--fc-muted);
            margin-top: 3px;
        }

        .breadcrumb-custom {
            display: flex;
            gap: 9px;
            align-items: center;
            color: #777;
            font-size: 12px;
            margin-bottom: 22px;
        }

        .breadcrumb-custom a {
            color: var(--fc-orange);
        }

        .breadcrumb-custom span {
            color: #555;
        }

        .intro-banner {
            padding: 25px;
            background: linear-gradient(110deg, #201b18, #291e18);
            border: 1px solid #453026;
            border-radius: 14px;
            margin-bottom: 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
        }

        .intro-banner h2 {
            font-size: 21px;
            font-weight: bold;
            margin-bottom: 8px;
        }

        .intro-banner p {
            font-size: 12px;
            line-height: 1.7;
            color: #bdb5b0;
            margin: 0;
            max-width: 650px;
        }

        .intro-icon {
            width: 60px;
            height: 60px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(255, 107, 44, .14);
            color: var(--fc-orange);
            font-size: 28px;
            flex-shrink: 0;
        }

        .stat-card {
            height: 100%;
            padding: 19px;
            background: var(--fc-card);
            border: 1px solid var(--fc-border);
            border-radius: 13px;
        }

        .stat-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 17px;
        }

        .stat-icon {
            width: 41px;
            height: 41px;
            border-radius: 11px;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 19px;
        }

        .icon-orange {
            color: var(--fc-orange);
            background: rgba(255, 107, 44, .13);
        }

        .icon-green {
            color: var(--fc-green);
            background: rgba(56, 201, 135, .12);
        }

        .icon-blue {
            color: var(--fc-blue);
            background: rgba(99, 168, 255, .12);
        }

        .icon-yellow {
            color: var(--fc-yellow);
            background: rgba(255, 200, 87, .12);
        }

        .stat-number {
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .stat-label {
            color: var(--fc-muted);
            font-size: 12px;
        }

        .stat-caption {
            color: #777;
            font-size: 10px;
            margin-top: 8px;
        }

        .panel {
            background: var(--fc-card);
            border: 1px solid var(--fc-border);
            border-radius: 13px;
            padding: 23px;
        }

        .panel-heading {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 15px;
            margin-bottom: 20px;
        }

        .panel-heading h3 {
            font-size: 17px;
            font-weight: bold;
            margin: 0 0 6px;
        }

        .panel-heading p {
            font-size: 12px;
            color: var(--fc-muted);
            margin: 0;
            line-height: 1.6;
        }

        .section-tag {
            color: var(--fc-orange);
            background: rgba(255, 107, 44, .12);
            padding: 7px 10px;
            border-radius: 6px;
            font-size: 10px;
            white-space: nowrap;
        }

        .availability-table {
            width: 100%;
            min-width: 750px;
            border-collapse: separate;
            border-spacing: 0;
        }

        .availability-table th {
            background: #202020;
            color: #999;
            border-bottom: 1px solid #383838;
            padding: 13px 12px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: .6px;
            white-space: nowrap;
        }

        .availability-table td {
            padding: 15px 12px;
            border-bottom: 1px solid #2b2b2b;
            vertical-align: middle;
            background: transparent;
        }

        .availability-table tbody tr:last-child td {
            border-bottom: none;
        }

        .day-name {
            font-size: 12px;
            font-weight: bold;
            color: #eee;
        }

        .day-subtitle {
            font-size: 10px;
            color: #777;
            margin-top: 5px;
        }

        .day-cell {
            min-width: 120px;
        }

        .day-toggle {
            display: flex;
            align-items: center;
            gap: 9px;
        }

        .form-check-input {
            width: 35px;
            height: 19px;
            margin: 0;
            background-color: #444;
            border-color: #555;
            cursor: pointer;
        }

        .form-check-input:checked {
            background-color: var(--fc-orange);
            border-color: var(--fc-orange);
        }

        .form-check-input:focus {
            box-shadow: 0 0 0 .2rem rgba(255, 107, 44, .15);
            border-color: var(--fc-orange);
        }

        .day-status {
            color: var(--fc-green);
            font-size: 10px;
        }

        .day-status.off {
            color: #888;
        }

        .time-input {
            min-width: 125px;
        }

        .form-control,
        .form-select {
            background-color: #111;
            color: #eee;
            border: 1px solid #383838;
            border-radius: 8px;
            padding: 9px 10px;
            font-size: 11px;
            min-height: 39px;
        }

        .form-control:focus,
        .form-select:focus {
            background-color: #151515;
            color: #fff;
            border-color: var(--fc-orange);
            box-shadow: 0 0 0 .2rem rgba(255, 107, 44, .12);
        }

        .form-control:disabled {
            background-color: #242424;
            color: #777;
            border-color: #303030;
            opacity: .75;
        }

        .form-control::-webkit-calendar-picker-indicator {
            filter: invert(1);
            cursor: pointer;
        }

        .btn-orange {
            background: var(--fc-orange);
            border: 1px solid var(--fc-orange);
            color: #111;
            border-radius: 8px;
            padding: 10px 16px;
            font-size: 12px;
            font-weight: bold;
        }

        .btn-orange:hover {
            background: var(--fc-orange-hover);
            border-color: var(--fc-orange-hover);
            color: #111;
        }

        .btn-outline-custom {
            display: inline-flex;
            justify-content: center;
            align-items: center;
            background: transparent;
            border: 1px solid #444;
            color: #ddd;
            border-radius: 8px;
            padding: 9px 13px;
            font-size: 11px;
        }

        .btn-outline-custom:hover {
            background: #292929;
            border-color: #666;
            color: #fff;
        }

        .help-text {
            font-size: 10px;
            color: #858585;
            line-height: 1.6;
        }

        .schedule-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 15px;
            flex-wrap: wrap;
            margin-top: 20px;
            padding-top: 19px;
            border-top: 1px solid #303030;
        }

        .schedule-actions-left,
        .schedule-actions-right {
            display: flex;
            align-items: center;
            gap: 9px;
            flex-wrap: wrap;
        }

        .blocked-date-form {
            display: grid;
            grid-template-columns: minmax(150px, 1fr) minmax(180px, 1.4fr) auto;
            align-items: end;
            gap: 12px;
            margin-bottom: 22px;
        }

        .form-label {
            color: #c9c9c9;
            font-size: 11px;
            margin-bottom: 7px;
        }

        .blocked-date-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 15px;
            padding: 15px;
            border: 1px solid #303030;
            background: #1e1e1e;
            border-radius: 9px;
            margin-bottom: 10px;
        }

        .blocked-date-item:last-child {
            margin-bottom: 0;
        }

        .blocked-date-left {
            display: flex;
            align-items: center;
            gap: 12px;
            min-width: 0;
        }

        .calendar-icon {
            width: 43px;
            height: 43px;
            border-radius: 10px;
            background: rgba(255, 107, 44, .12);
            color: var(--fc-orange);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            flex-shrink: 0;
        }

        .blocked-date-title {
            font-size: 12px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .blocked-date-reason {
            font-size: 10px;
            color: #999;
            overflow-wrap: anywhere;
        }

        .blocked-date-weekday {
            color: var(--fc-orange);
            font-size: 10px;
            margin-top: 4px;
        }

        .remove-btn {
            color: var(--fc-red);
            background: rgba(255, 105, 105, .08);
            border: 1px solid rgba(255, 105, 105, .25);
            border-radius: 7px;
            padding: 7px 10px;
            font-size: 10px;
            white-space: nowrap;
        }

        .remove-btn:hover {
            background: rgba(255, 105, 105, .17);
            color: #ff9696;
        }

        .today-card {
            display: flex;
            align-items: center;
            gap: 15px;
            background: #1d1d1d;
            border: 1px solid #303030;
            padding: 17px;
            border-radius: 10px;
        }

        .today-icon {
            width: 45px;
            height: 45px;
            background: rgba(255, 107, 44, .12);
            color: var(--fc-orange);
            border-radius: 11px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 21px;
            flex-shrink: 0;
        }

        .today-label {
            font-size: 10px;
            color: #999;
            margin-bottom: 5px;
        }

        .today-value {
            font-size: 13px;
            font-weight: bold;
        }

        .today-description {
            color: #888;
            font-size: 10px;
            margin-top: 4px;
        }

        .notice {
            display: flex;
            align-items: flex-start;
            gap: 11px;
            padding: 14px;
            background: rgba(99, 168, 255, .07);
            border: 1px solid rgba(99, 168, 255, .2);
            border-radius: 9px;
            color: #b7d5ff;
            font-size: 11px;
            line-height: 1.7;
        }

        .notice-icon {
            font-size: 17px;
            color: var(--fc-blue);
            flex-shrink: 0;
        }

        .alert {
            border-radius: 9px;
            font-size: 12px;
        }

        .alert-success {
            background: rgba(56, 201, 135, .1);
            border: 1px solid rgba(56, 201, 135, .25);
            color: #76e4b0;
        }

        .alert-danger {
            background: rgba(255, 105, 105, .1);
            border: 1px solid rgba(255, 105, 105, .25);
            color: #ff9c9c;
        }

        .alert-warning {
            background: rgba(255, 200, 87, .1);
            border: 1px solid rgba(255, 200, 87, .25);
            color: #ffda8a;
        }

        .empty-state {
            text-align: center;
            padding: 35px 15px;
            border: 1px dashed #3b3b3b;
            border-radius: 10px;
        }

        .empty-state-icon {
            font-size: 29px;
            color: var(--fc-orange);
            margin-bottom: 12px;
        }

        .empty-state h4 {
            font-size: 14px;
            margin-bottom: 7px;
        }

        .empty-state p {
            color: #888;
            font-size: 11px;
            margin: 0;
        }

        .footer {
            border-top: 1px solid var(--fc-border);
            margin-top: 30px;
            padding-top: 20px;
            display: flex;
            justify-content: space-between;
            gap: 12px;
            flex-wrap: wrap;
            color: #777;
            font-size: 11px;
        }

        .footer a {
            color: var(--fc-orange);
        }

        .mobile-menu-btn {
            display: none;
            border: 1px solid var(--fc-border);
            background: var(--fc-card);
            color: #fff;
            border-radius: 8px;
            padding: 8px 11px;
            margin-right: 10px;
        }

        .sidebar-overlay {
            display: none;
        }

        @media (max-width: 1199px) {
            .sidebar {
                width: 230px;
            }

            .main-content {
                margin-left: 230px;
                padding: 24px;
            }
        }

        @media (max-width: 991px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.open {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
                padding: 22px;
            }

            .mobile-menu-btn {
                display: inline-block;
            }

            .sidebar-overlay.show {
                display: block;
                position: fixed;
                inset: 0;
                z-index: 999;
                background: rgba(0, 0, 0, .65);
            }

            .availability-table {
                min-width: 750px;
            }
        }

        @media (max-width: 575px) {
            .main-content {
                padding: 17px 13px;
            }

            .page-title {
                font-size: 21px;
            }

            .page-subtitle {
                font-size: 11px;
            }

            .profile-name,
            .profile-role {
                display: none;
            }

            .intro-banner {
                padding: 19px;
            }

            .intro-banner h2 {
                font-size: 18px;
            }

            .intro-icon {
                width: 45px;
                height: 45px;
                font-size: 22px;
            }

            .panel {
                padding: 15px;
            }

            .panel-heading {
                flex-direction: column;
            }

            .blocked-date-form {
                grid-template-columns: 1fr;
            }

            .blocked-date-item {
                align-items: flex-start;
            }

            .schedule-actions-left,
            .schedule-actions-right {
                width: 100%;
            }

            .schedule-actions-right .btn {
                flex: 1;
            }

            .footer {
                flex-direction: column;
            }
        }
    </style>
</head>

<body>

<div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

<!-- Sidebar -->
<aside class="sidebar" id="sidebar">

    <a href="dashboard.php" class="brand">
        <img src="../../images/fitcore/fitcore_main.png" width="60px" height="60px">
        <div>
            <div class="brand-name">Fit<span>Core</span></div>
            <div class="brand-subtitle">TRAINER PORTAL</div>
        </div>
    </a>

    <div class="sidebar-label">Workspace</div>

    <a href="dashboard.php" class="sidebar-link">
        <span class="nav-icon">▦</span>
        Dashboard
    </a>

    <a href="appointments.php" class="sidebar-link">
        <span class="nav-icon">▣</span>
        Appointments
    </a>

    <a href="availability.php" class="sidebar-link active">
        <span class="nav-icon">◷</span>
        Manage Availability
    </a>

    <a href="schedule_session.php" class="sidebar-link">
        <span class="nav-icon">＋</span>
        Schedule Sessions
    </a>

    <div class="sidebar-label">Members</div>

    <a href="members.php" class="sidebar-link">
        <span class="nav-icon">♙</span>
        My Members
    </a>

    <a href="session_notes.php" class="sidebar-link">
        <span class="nav-icon">▤</span>
        Session Notes
    </a>

    <div class="sidebar-label">Other</div>

    <a href="../customer/index.php" class="sidebar-link">
        <span class="nav-icon">⌂</span>
        Member Website
    </a>

    <a href="../fitcore_demo.php" class="sidebar-link">
        <span class="nav-icon">↩</span>
        Demo Role Selection
    </a>

    <div class="sidebar-bottom">
        <p><strong style="color:#eee;">Trainer Demo Mode</strong></p>
        <p>Manage sample working hours and unavailable dates.</p>
        <a href="../index.php">← Back to Codvanta</a>
    </div>

</aside>

<!-- Main content -->
<main class="main-content">

    <header class="topbar">

        <div class="d-flex align-items-start">
            <button class="mobile-menu-btn" type="button"
                    onclick="toggleSidebar()" aria-label="Open navigation">
                ☰
            </button>

            <div>
                <h1 class="page-title">Manage Availability</h1>
                <p class="page-subtitle">
                    Set your weekly working hours and manage days off.
                </p>
            </div>
        </div>

        <div class="profile">
            <div class="avatar">AM</div>
            <div>
                <div class="profile-name"><?= e($trainerName) ?></div>
                <div class="profile-role"><?= e($trainerSpecialty) ?></div>
            </div>
        </div>

    </header>

    <div class="breadcrumb-custom">
        <a href="dashboard.php">Dashboard</a>
        <span>/</span>
        <div>Manage Availability</div>
    </div>

    <?php if ($flashMessage): ?>
        <div class="alert alert-<?= e($flashMessage['type']) ?> alert-dismissible fade show mb-4"
             role="alert">
            <?= e($flashMessage['message']) ?>
            <button type="button" class="btn-close"
                    data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- Intro banner -->
    <section class="intro-banner">

        <div>
            <h2>Make time for your members. 🗓️</h2>
            <p>
                Set the hours when members can request training sessions.
                You can also block specific dates for holidays, personal
                commitments, or other occasions when you are unavailable.
            </p>
        </div>

        <div class="intro-icon">◷</div>

    </section>

    <!-- Statistics -->
    <section class="mb-4">

        <div class="row g-3">

            <div class="col-6 col-xl-3">
                <div class="stat-card">
                    <div class="stat-top">
                        <div class="stat-icon icon-orange">▦</div>
                    </div>
                    <div class="stat-number"><?= $activeDays ?></div>
                    <div class="stat-label">Available Days</div>
                    <div class="stat-caption">Days enabled in your schedule</div>
                </div>
            </div>

            <div class="col-6 col-xl-3">
                <div class="stat-card">
                    <div class="stat-top">
                        <div class="stat-icon icon-green">◷</div>
                    </div>
                    <div class="stat-number"><?= e($totalHours) ?></div>
                    <div class="stat-label">Hours Per Week</div>
                    <div class="stat-caption">Excluding configured breaks</div>
                </div>
            </div>

            <div class="col-6 col-xl-3">
                <div class="stat-card">
                    <div class="stat-top">
                        <div class="stat-icon icon-blue">◉</div>
                    </div>
                    <div class="stat-number"><?= $blockedDatesCount ?></div>
                    <div class="stat-label">Blocked Dates</div>
                    <div class="stat-caption">Dates marked unavailable</div>
                </div>
            </div>

            <div class="col-6 col-xl-3">
                <div class="stat-card">
                    <div class="stat-top">
                        <div class="stat-icon icon-yellow">☀</div>
                    </div>
                    <div class="stat-number" style="font-size:21px;">
                        <?= !empty($todayAvailability['enabled']) ? 'Available' : 'Day Off' ?>
                    </div>
                    <div class="stat-label"><?= e($todayName) ?></div>
                    <div class="stat-caption">
                        <?php if (!empty($todayAvailability['enabled'])): ?>
                            <?= e(formatTime($todayAvailability['start'])) ?>
                            – <?= e(formatTime($todayAvailability['end'])) ?>
                        <?php else: ?>
                            No regular working hours
                        <?php endif; ?>
                    </div>
                </div>
            </div>

        </div>

    </section>

    <!-- Weekly schedule -->
    <section class="panel mb-4">

        <div class="panel-heading">

            <div>
                <h3>Weekly Working Hours</h3>
                <p>
                    Enable the days you work, then set your start time,
                    end time, and break period.
                </p>
            </div>

            <span class="section-tag">WEEKLY SCHEDULE</span>

        </div>

        <form action="availability.php" method="POST" id="availabilityForm">

            <input type="hidden" name="csrf_token" value="<?= e($csrfToken) ?>">
            <input type="hidden" name="action" value="save_schedule">

            <div class="table-responsive">

                <table class="availability-table">

                    <thead>
                        <tr>
                            <th>Day</th>
                            <th>Availability</th>
                            <th>Start Time</th>
                            <th>End Time</th>
                            <th>Break Start</th>
                            <th>Break End</th>
                        </tr>
                    </thead>

                    <tbody>

                        <?php foreach ($days as $day): ?>

                            <?php $settings = $availability[$day]; ?>

                            <tr>

                                <td class="day-cell">
                                    <div class="day-name"><?= e($day) ?></div>

                                    <div class="day-subtitle">
                                        <?php if ($day === $todayName): ?>
                                            Today
                                        <?php else: ?>
                                            Weekly schedule
                                        <?php endif; ?>
                                    </div>
                                </td>

                                <td>
                                    <div class="day-toggle">

                                        <input
                                            type="checkbox"
                                            class="form-check-input day-checkbox"
                                            id="day_<?= e($day) ?>"
                                            name="enabled_days[]"
                                            value="<?= e($day) ?>"
                                            <?= !empty($settings['enabled']) ? 'checked' : '' ?>
                                        >

                                        <label
                                            for="day_<?= e($day) ?>"
                                            class="day-status <?= empty($settings['enabled']) ? 'off' : '' ?>"
                                            id="status_<?= e($day) ?>"
                                        >
                                            <?= !empty($settings['enabled']) ? 'Available' : 'Day off' ?>
                                        </label>

                                    </div>
                                </td>

                                <td>
                                    <input
                                        type="time"
                                        class="form-control time-input schedule-time"
                                        name="start[<?= e($day) ?>]"
                                        value="<?= e($settings['start']) ?>"
                                        data-day="<?= e($day) ?>"
                                        required
                                    >
                                </td>

                                <td>
                                    <input
                                        type="time"
                                        class="form-control time-input schedule-time"
                                        name="end[<?= e($day) ?>]"
                                        value="<?= e($settings['end']) ?>"
                                        data-day="<?= e($day) ?>"
                                        required
                                    >
                                </td>

                                <td>
                                    <input
                                        type="time"
                                        class="form-control time-input schedule-time"
                                        name="break_start[<?= e($day) ?>]"
                                        value="<?= e($settings['break_start']) ?>"
                                        data-day="<?= e($day) ?>"
                                    >
                                </td>

                                <td>
                                    <input
                                        type="time"
                                        class="form-control time-input schedule-time"
                                        name="break_end[<?= e($day) ?>]"
                                        value="<?= e($settings['break_end']) ?>"
                                        data-day="<?= e($day) ?>"
                                    >
                                </td>

                            </tr>

                        <?php endforeach; ?>

                    </tbody>

                </table>

            </div>

            <div class="help-text mt-3">
                Tip: Leave both break fields empty if you do not need a
                scheduled break. Disabled days will not count toward your
                weekly working hours.
            </div>

            <div class="schedule-actions">

                <div class="schedule-actions-left">
                    <span class="help-text">
                        Changes apply to this demonstration session.
                    </span>
                </div>

                <div class="schedule-actions-right">

                    <button type="submit"
                            name="action"
                            value="save_schedule"
                            class="btn btn-orange">
                        Save Availability
                    </button>

                    <button type="submit"
                            name="action"
                            value="reset_schedule"
                            class="btn-outline-custom"
                            formnovalidate
                            onclick="return confirm('Restore the default weekly schedule?');">
                        Reset Schedule
                    </button>

                </div>

            </div>

        </form>

    </section>

    <!-- Blocked dates -->
    <section class="panel mb-4">

        <div class="panel-heading">

            <div>
                <h3>Blocked Dates & Days Off</h3>
                <p>
                    Mark specific dates when you cannot accept training
                    appointments.
                </p>
            </div>

            <span class="section-tag">TIME OFF</span>

        </div>

        <form action="availability.php" method="POST" class="blocked-date-form">

            <input type="hidden" name="csrf_token" value="<?= e($csrfToken) ?>">
            <input type="hidden" name="action" value="add_blocked_date">

            <div>
                <label for="blocked_date" class="form-label">
                    Select Date
                </label>

                <input
                    type="date"
                    name="blocked_date"
                    id="blocked_date"
                    class="form-control"
                    min="<?= e(date('Y-m-d')) ?>"
                    required
                >
            </div>

            <div>
                <label for="reason" class="form-label">
                    Reason
                </label>

                <input
                    type="text"
                    name="reason"
                    id="reason"
                    class="form-control"
                    maxlength="100"
                    placeholder="e.g. Personal commitment, holiday"
                >
            </div>

            <button type="submit" class="btn btn-orange">
                + Block Date
            </button>

        </form>

        <?php if (count($blockedDates) > 0): ?>

            <?php foreach ($blockedDates as $blockedDate => $reason): ?>

                <div class="blocked-date-item">

                    <div class="blocked-date-left">

                        <div class="calendar-icon">▣</div>

                        <div>
                            <div class="blocked-date-title">
                                <?= e(date('M d, Y', strtotime($blockedDate))) ?>
                            </div>

                            <div class="blocked-date-reason">
                                <?= e($reason) ?>
                            </div>

                            <div class="blocked-date-weekday">
                                <?= e(date('l', strtotime($blockedDate))) ?>
                            </div>
                        </div>

                    </div>

                    <form action="availability.php" method="POST"
                          onsubmit="return confirm('Remove this blocked date?');">

                        <input type="hidden" name="csrf_token"
                               value="<?= e($csrfToken) ?>">

                        <input type="hidden" name="action"
                               value="remove_blocked_date">

                        <input type="hidden" name="blocked_date"
                               value="<?= e($blockedDate) ?>">

                        <button type="submit" class="remove-btn">
                            Remove
                        </button>

                    </form>

                </div>

            <?php endforeach; ?>

        <?php else: ?>

            <div class="empty-state">
                <div class="empty-state-icon">✓</div>
                <h4>No blocked dates</h4>
                <p>You have not marked any dates as unavailable.</p>
            </div>

        <?php endif; ?>

    </section>

    <!-- Information notice -->
    <div class="notice">
        <div class="notice-icon">ⓘ</div>
        <div>
            <strong>Demonstration notice</strong><br>
            Availability settings are stored in PHP session data. They
            demonstrate the trainer workflow but do not automatically
            change the booking options shown on the member website.
            A database-backed version would connect this schedule to
            appointment booking and prevent bookings during unavailable
            times.
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div>
            © <?= date('Y') ?> FitCore Trainer Portal.
            Demonstration website.
        </div>

        <div>
            Developed by <a href="../index.php">Codvanta</a>
            · Turning Vision into Digital Reality.
        </div>
    </footer>

</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>


<script>
    function toggleSidebar() {
        document.getElementById("sidebar").classList.toggle("open");
        document.getElementById("sidebarOverlay").classList.toggle("show");
    }

    document.querySelectorAll(".sidebar-link").forEach(function(link) {
        link.addEventListener("click", function() {
            if (window.innerWidth <= 991) {
                document.getElementById("sidebar").classList.remove("open");
                document.getElementById("sidebarOverlay").classList.remove("show");
            }
        });
    });

    /*
     * Disable time fields when a day is marked as unavailable.
     * Disabled fields are not submitted, so this script preserves their
     * values in hidden inputs before disabling them.
     */
    function updateDayState(checkbox) {
        const day = checkbox.value;
        const status = document.getElementById("status_" + day);

        status.textContent = checkbox.checked ? "Available" : "Day off";
        status.classList.toggle("off", !checkbox.checked);

        document.querySelectorAll('.schedule-time[data-day="' + day + '"]').forEach(function(input) {
            input.disabled = !checkbox.checked;
            input.required = checkbox.checked &&
                (input.name.startsWith("start[") || input.name.startsWith("end["));
        });
    }

    document.querySelectorAll(".day-checkbox").forEach(function(checkbox) {
        checkbox.addEventListener("change", function() {
            updateDayState(checkbox);
        });

        updateDayState(checkbox);
    });

    /*
     * Prevent the break from falling outside working hours in the UI.
     * Server-side validation remains authoritative.
     */
    document.getElementById("availabilityForm").addEventListener("submit", function(event) {
        const resetButton = event.submitter &&
            event.submitter.value === "reset_schedule";

        if (resetButton) {
            return;
        }

        let valid = true;

        document.querySelectorAll(".day-checkbox:checked").forEach(function(checkbox) {
            const day = checkbox.value;

            const start = document.querySelector('input[name="start[' + day + ']"]');
            const end = document.querySelector('input[name="end[' + day + ']"]');
            const breakStart = document.querySelector('input[name="break_start[' + day + ']"]');
            const breakEnd = document.querySelector('input[name="break_end[' + day + ']"]');

            if (start.value >= end.value) {
                valid = false;
                alert(day + ": the end time must be later than the start time.");
                return;
            }

            if ((breakStart.value && !breakEnd.value) ||
                (!breakStart.value && breakEnd.value)) {
                valid = false;
                alert(day + ": enter both break times or leave both empty.");
                return;
            }

            if (breakStart.value && breakEnd.value) {
                if (
                    breakStart.value >= breakEnd.value ||
                    breakStart.value < start.value ||
                    breakEnd.value > end.value
                ) {
                    valid = false;
                    alert(day + ": check that the break falls within working hours.");
                }
            }
        });

        if (!valid) {
            event.preventDefault();
        }
    });
</script>

</body>
</html>