
<?php
session_start();
date_default_timezone_set('Asia/Colombo');

/*
|--------------------------------------------------------------------------
| ProServe CRM - Admin Settings
|--------------------------------------------------------------------------
| Session-based portfolio demonstration.
| No database is required.
*/

function e($value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function settingsDefaultValues(): array
{
    return [
        'company_name' => 'ProServe',
        'tagline' => 'CRM & Service Management System',
        'company_email' => 'info@proserve.demo',
        'company_phone' => '+94 11 234 5678',
        'company_website' => 'https://www.proserve.demo',
        'company_address' => 'Colombo, Sri Lanka',
        'currency' => 'LKR',
        'timezone' => 'Asia/Colombo',
        'date_format' => 'd M Y',
        'appointment_duration' => '30',
        'appointment_buffer' => '10',
        'working_start' => '09:00',
        'working_end' => '17:00',
        'working_days' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'],
        'quotation_validity' => '14',
        'invoice_due_days' => '30',
        'tax_enabled' => '0',
        'tax_rate' => '0',
        'email_notifications' => '1',
        'new_enquiry_notification' => '1',
        'new_appointment_notification' => '1',
        'quotation_notification' => '1',
        'invoice_notification' => '1',
        'maintenance_mode' => '0',
        'allow_customer_registration' => '1',
        'allow_appointment_booking' => '1',
        'allow_quote_requests' => '1',
        'footer_text' => 'ProServe — CRM & Service Management System',
    ];
}

if (!isset($_SESSION['proserve_settings']) || !is_array($_SESSION['proserve_settings'])) {
    $_SESSION['proserve_settings'] = settingsDefaultValues();
} else {
    $_SESSION['proserve_settings'] = array_merge(
        settingsDefaultValues(),
        $_SESSION['proserve_settings']
    );
}

if (!isset($_SESSION['proserve_settings_csrf'])) {
    $_SESSION['proserve_settings_csrf'] = bin2hex(random_bytes(32));
}

$settings = $_SESSION['proserve_settings'];
$notice = null;
$noticeType = 'success';

/*
|--------------------------------------------------------------------------
| Process form submissions
|--------------------------------------------------------------------------
*/

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $postedToken = (string) ($_POST['csrf_token'] ?? '');

    if (!hash_equals($_SESSION['proserve_settings_csrf'], $postedToken)) {
        $notice = 'Your session validation failed. Please refresh the page and try again.';
        $noticeType = 'danger';
    } else {
        $action = (string) ($_POST['action'] ?? '');

        if ($action === 'save_general') {
            $companyName = trim((string) ($_POST['company_name'] ?? ''));
            $tagline = trim((string) ($_POST['tagline'] ?? ''));
            $email = trim((string) ($_POST['company_email'] ?? ''));
            $phone = trim((string) ($_POST['company_phone'] ?? ''));
            $website = trim((string) ($_POST['company_website'] ?? ''));
            $address = trim((string) ($_POST['company_address'] ?? ''));

            if ($companyName === '') {
                $notice = 'Company name is required.';
                $noticeType = 'danger';
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $notice = 'Please enter a valid company email address.';
                $noticeType = 'danger';
            } elseif ($website !== '' && !filter_var($website, FILTER_VALIDATE_URL)) {
                $notice = 'Please enter a valid website URL, including https://.';
                $noticeType = 'danger';
            } else {
                $settings['company_name'] = mb_substr($companyName, 0, 100);
                $settings['tagline'] = mb_substr($tagline, 0, 180);
                $settings['company_email'] = $email;
                $settings['company_phone'] = mb_substr($phone, 0, 40);
                $settings['company_website'] = $website;
                $settings['company_address'] = mb_substr($address, 0, 250);

                $notice = 'Company information saved successfully.';
            }
        } elseif ($action === 'save_preferences') {
            $allowedCurrencies = ['LKR', 'USD', 'EUR', 'GBP', 'AUD', 'INR'];
            $allowedTimezones = [
                'Asia/Colombo',
                'Asia/Kolkata',
                'Asia/Dubai',
                'Europe/London',
                'Europe/Paris',
                'America/New_York',
                'America/Los_Angeles',
                'Australia/Sydney',
                'UTC',
            ];
            $allowedDateFormats = ['d M Y', 'Y-m-d', 'm/d/Y', 'd/m/Y'];

            $currency = (string) ($_POST['currency'] ?? 'LKR');
            $timezone = (string) ($_POST['timezone'] ?? 'Asia/Colombo');
            $dateFormat = (string) ($_POST['date_format'] ?? 'd M Y');

            if (!in_array($currency, $allowedCurrencies, true)) {
                $notice = 'Please select a valid currency.';
                $noticeType = 'danger';
            } elseif (!in_array($timezone, $allowedTimezones, true)) {
                $notice = 'Please select a valid time zone.';
                $noticeType = 'danger';
            } elseif (!in_array($dateFormat, $allowedDateFormats, true)) {
                $notice = 'Please select a valid date format.';
                $noticeType = 'danger';
            } else {
                $settings['currency'] = $currency;
                $settings['timezone'] = $timezone;
                $settings['date_format'] = $dateFormat;

                $notice = 'Regional preferences saved successfully.';
            }
        } elseif ($action === 'save_appointments') {
            $duration = (int) ($_POST['appointment_duration'] ?? 30);
            $buffer = (int) ($_POST['appointment_buffer'] ?? 10);
            $start = (string) ($_POST['working_start'] ?? '09:00');
            $end = (string) ($_POST['working_end'] ?? '17:00');

            $validDays = [
                'Monday', 'Tuesday', 'Wednesday', 'Thursday',
                'Friday', 'Saturday', 'Sunday',
            ];

            $days = $_POST['working_days'] ?? [];

            if (!is_array($days)) {
                $days = [];
            }

            $days = array_values(array_intersect($validDays, $days));

            $validTime = static function ($value): bool {
                if (!is_string($value)) {
                    return false;
                }

                $date = DateTime::createFromFormat('!H:i', $value);

                return $date !== false && $date->format('H:i') === $value;
            };

            if ($duration < 10 || $duration > 240) {
                $notice = 'Appointment duration must be between 10 and 240 minutes.';
                $noticeType = 'danger';
            } elseif ($buffer < 0 || $buffer > 120) {
                $notice = 'Appointment buffer must be between 0 and 120 minutes.';
                $noticeType = 'danger';
            } elseif (!$validTime($start) || !$validTime($end) || $start >= $end) {
                $notice = 'Please enter a valid working-hour range.';
                $noticeType = 'danger';
            } elseif (count($days) === 0) {
                $notice = 'Select at least one working day.';
                $noticeType = 'danger';
            } else {
                $settings['appointment_duration'] = (string) $duration;
                $settings['appointment_buffer'] = (string) $buffer;
                $settings['working_start'] = $start;
                $settings['working_end'] = $end;
                $settings['working_days'] = $days;

                $notice = 'Appointment preferences saved successfully.';
            }
        } elseif ($action === 'save_billing') {
            $validity = (int) ($_POST['quotation_validity'] ?? 14);
            $dueDays = (int) ($_POST['invoice_due_days'] ?? 30);
            $taxEnabled = isset($_POST['tax_enabled']) ? '1' : '0';
            $taxRate = (float) ($_POST['tax_rate'] ?? 0);

            if ($validity < 1 || $validity > 365) {
                $notice = 'Quotation validity must be between 1 and 365 days.';
                $noticeType = 'danger';
            } elseif ($dueDays < 1 || $dueDays > 365) {
                $notice = 'Invoice payment terms must be between 1 and 365 days.';
                $noticeType = 'danger';
            } elseif ($taxRate < 0 || $taxRate > 100) {
                $notice = 'Tax rate must be between 0 and 100 percent.';
                $noticeType = 'danger';
            } else {
                $settings['quotation_validity'] = (string) $validity;
                $settings['invoice_due_days'] = (string) $dueDays;
                $settings['tax_enabled'] = $taxEnabled;
                $settings['tax_rate'] = (string) $taxRate;

                $notice = 'Quotation and invoice settings saved successfully.';
            }
        } elseif ($action === 'save_notifications') {
            $settings['email_notifications'] = isset($_POST['email_notifications']) ? '1' : '0';
            $settings['new_enquiry_notification'] = isset($_POST['new_enquiry_notification']) ? '1' : '0';
            $settings['new_appointment_notification'] = isset($_POST['new_appointment_notification']) ? '1' : '0';
            $settings['quotation_notification'] = isset($_POST['quotation_notification']) ? '1' : '0';
            $settings['invoice_notification'] = isset($_POST['invoice_notification']) ? '1' : '0';

            $notice = 'Notification preferences saved successfully.';
        } elseif ($action === 'save_system') {
            $settings['maintenance_mode'] = isset($_POST['maintenance_mode']) ? '1' : '0';
            $settings['allow_customer_registration'] = isset($_POST['allow_customer_registration']) ? '1' : '0';
            $settings['allow_appointment_booking'] = isset($_POST['allow_appointment_booking']) ? '1' : '0';
            $settings['allow_quote_requests'] = isset($_POST['allow_quote_requests']) ? '1' : '0';

            $footerText = trim((string) ($_POST['footer_text'] ?? ''));

            if ($footerText === '') {
                $notice = 'Footer text cannot be empty.';
                $noticeType = 'danger';
            } else {
                $settings['footer_text'] = mb_substr($footerText, 0, 180);
                $notice = 'System preferences saved successfully.';
            }
        } elseif ($action === 'reset_settings') {
            $settings = settingsDefaultValues();
            $notice = 'All settings have been reset to the demonstration defaults.';
        } else {
            $notice = 'Unknown settings action.';
            $noticeType = 'danger';
        }

        if ($noticeType === 'success') {
            $_SESSION['proserve_settings'] = $settings;
        }
    }

    $_SESSION['proserve_settings_notice'] = [
        'message' => $notice,
        'type' => $noticeType,
    ];

    header('Location: settings.php');
    exit;
}

/*
|--------------------------------------------------------------------------
| Flash message after redirect
|--------------------------------------------------------------------------
*/

if (isset($_SESSION['proserve_settings_notice'])) {
    $flash = $_SESSION['proserve_settings_notice'];
    unset($_SESSION['proserve_settings_notice']);

    $notice = $flash['message'] ?? null;
    $noticeType = $flash['type'] ?? 'success';
}

$settings = $_SESSION['proserve_settings'];

$workingDays = [
    'Monday', 'Tuesday', 'Wednesday', 'Thursday',
    'Friday', 'Saturday', 'Sunday',
];

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Settings | ProServe Admin</title>

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
            --body-bg: #f4f7fb;
            --muted: #718096;
            --border: #e8edf4;
            --success: #159a68;
            --danger: #dc4c64;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: var(--body-bg);
            color: #25334a;
            font-family: 'Inter', 'Trebuchet MS', sans-serif;
            font-size: 13px;
        }

        a {
            text-decoration: none;
        }

        .sidebar {
            position: fixed;
            inset: 0 auto 0 0;
            width: 250px;
            background: var(--navy);
            color: #fff;
            padding: 24px 16px;
            overflow-y: auto;
            z-index: 1030;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 2px 10px 26px;
            color: #fff;
        }

        .brand-icon {
            width: 42px;
            height: 42px;
            display: grid;
            place-items: center;
            border-radius: 13px;
            background: var(--primary);
            font-size: 21px;
        }

        .brand-name {
            font-size: 19px;
            font-weight: 800;
            letter-spacing: -.6px;
        }

        .brand-subtitle {
            margin-top: 3px;
            color: #9eb1ce;
            font-size: 10px;
        }

        .nav-heading {
            margin: 18px 12px 9px;
            color: #8297b6;
            font-size: 10px;
            font-weight: 800;
            letter-spacing: 1.2px;
            text-transform: uppercase;
        }

        .side-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 11px 13px;
            margin: 3px 0;
            border-radius: 9px;
            color: #c1cde0;
            font-size: 12px;
            font-weight: 600;
            transition: .2s;
        }

        .side-link i {
            width: 18px;
            font-size: 16px;
        }

        .side-link:hover,
        .side-link.active {
            background: rgba(75, 128, 240, .19);
            color: #fff;
        }

        .side-link.active {
            box-shadow: inset 3px 0 #60a5fa;
        }

        .sidebar-footer {
            margin-top: 26px;
            padding: 16px 12px;
            border-top: 1px solid rgba(255,255,255,.12);
            color: #9eb1ce;
            font-size: 11px;
            line-height: 1.8;
        }

        .main {
            min-height: 100vh;
            margin-left: 250px;
        }

        .topbar {
            height: 74px;
            padding: 0 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #fff;
            border-bottom: 1px solid var(--border);
        }

        .topbar-title {
            font-size: 16px;
            font-weight: 800;
            color: var(--navy);
        }

        .topbar-subtitle {
            color: var(--muted);
            font-size: 11px;
            margin-top: 4px;
        }

        .admin-avatar {
            width: 37px;
            height: 37px;
            display: grid;
            place-items: center;
            border-radius: 12px;
            background: #e9efff;
            color: var(--primary);
            font-size: 16px;
        }

        .content {
            padding: 28px 30px 35px;
            max-width: 1500px;
            margin: 0 auto;
        }

        .page-heading {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 18px;
            margin-bottom: 24px;
        }

        .page-heading h1 {
            margin: 0;
            color: var(--navy);
            font-size: 24px;
            font-weight: 800;
            letter-spacing: -.8px;
        }

        .page-heading p {
            margin: 8px 0 0;
            color: var(--muted);
            line-height: 1.6;
        }

        .btn-primary-custom,
        .btn-light-custom,
        .btn-danger-custom {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 10px 15px;
            border-radius: 9px;
            font-size: 12px;
            font-weight: 700;
            transition: .2s;
        }

        .btn-primary-custom {
            border: 0;
            background: var(--primary);
            color: #fff;
        }

        .btn-primary-custom:hover {
            background: var(--primary-dark);
            color: #fff;
        }

        .btn-light-custom {
            border: 1px solid var(--border);
            background: #fff;
            color: #394a63;
        }

        .btn-light-custom:hover {
            background: #f8fafc;
        }

        .btn-danger-custom {
            border: 1px solid #ffd9de;
            background: #fff;
            color: #c63e55;
        }

        .settings-layout {
            display: grid;
            grid-template-columns: 235px minmax(0, 1fr);
            align-items: start;
            gap: 20px;
        }

        .settings-nav,
        .settings-card {
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 14px;
            box-shadow: 0 3px 12px rgba(24, 46, 79, .025);
        }

        .settings-nav {
            padding: 12px;
            position: sticky;
            top: 18px;
        }

        .settings-nav-title {
            padding: 10px 10px 14px;
            color: #8996a8;
            font-size: 10px;
            font-weight: 800;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .settings-nav a {
            display: flex;
            align-items: center;
            gap: 11px;
            padding: 12px 10px;
            margin-bottom: 3px;
            border-radius: 9px;
            color: #586980;
            font-size: 11px;
            font-weight: 700;
        }

        .settings-nav a i {
            width: 18px;
            font-size: 15px;
        }

        .settings-nav a:hover,
        .settings-nav a.active {
            background: #edf3ff;
            color: var(--primary);
        }

        .settings-content {
            display: flex;
            flex-direction: column;
            gap: 20px;
            min-width: 0;
        }

        .settings-card {
            padding: 23px;
            scroll-margin-top: 20px;
        }

        .card-heading {
            display: flex;
            align-items: flex-start;
            gap: 13px;
            padding-bottom: 20px;
            margin-bottom: 22px;
            border-bottom: 1px solid #edf1f6;
        }

        .heading-icon {
            width: 42px;
            height: 42px;
            flex: 0 0 42px;
            display: grid;
            place-items: center;
            border-radius: 12px;
            background: #edf3ff;
            color: var(--primary);
            font-size: 19px;
        }

        .card-heading h2 {
            margin: 2px 0 0;
            color: var(--navy);
            font-size: 15px;
            font-weight: 800;
        }

        .card-heading p {
            margin: 7px 0 0;
            color: var(--muted);
            font-size: 11px;
            line-height: 1.7;
        }

        .form-label {
            display: block;
            margin-bottom: 7px;
            color: #40516a;
            font-size: 11px;
            font-weight: 700;
        }

        .form-control,
        .form-select {
            min-height: 41px;
            border-color: #dfe6ef;
            border-radius: 8px;
            font-size: 12px;
        }

        textarea.form-control {
            min-height: 90px;
            resize: vertical;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #91b4ff;
            box-shadow: 0 0 0 3px rgba(37,99,235,.1);
        }

        .form-text {
            color: #8996a8;
            font-size: 10px;
            line-height: 1.6;
        }

        .form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 9px;
            margin-top: 23px;
            padding-top: 18px;
            border-top: 1px solid #edf1f6;
        }

        .setting-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 18px;
            padding: 16px 0;
            border-bottom: 1px solid #edf1f6;
        }

        .setting-row:first-child {
            padding-top: 0;
        }

        .setting-row:last-child {
            padding-bottom: 0;
            border-bottom: 0;
        }

        .setting-label {
            color: var(--navy);
            font-size: 12px;
            font-weight: 700;
        }

        .setting-description {
            margin-top: 5px;
            color: #8996a8;
            font-size: 10px;
            line-height: 1.7;
        }

        .form-check-input {
            width: 36px;
            height: 20px;
            margin-top: 0;
            cursor: pointer;
        }

        .form-check-input:checked {
            background-color: var(--primary);
            border-color: var(--primary);
        }

        .form-check-input:focus {
            box-shadow: 0 0 0 3px rgba(37,99,235,.12);
        }

        .days-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 9px;
        }

        .day-option {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 11px;
            border: 1px solid var(--border);
            border-radius: 9px;
            background: #fbfcfe;
            color: #52637a;
            font-size: 11px;
            font-weight: 600;
        }

        .day-option:has(input:checked) {
            border-color: #a9c4ff;
            background: #f1f6ff;
            color: var(--primary);
        }

        .alert {
            border-radius: 10px;
            font-size: 12px;
        }

        .demo-note {
            padding: 15px 17px;
            border: 1px solid #cfe0ff;
            border-radius: 12px;
            background: #eff5ff;
            color: #38598e;
            font-size: 11px;
            line-height: 1.8;
        }

        .demo-note strong {
            color: #204da2;
        }

        .danger-zone {
            border-color: #f2d8dc;
        }

        .danger-zone .heading-icon {
            background: #fff0f1;
            color: #c63e55;
        }

        .mobile-menu-btn {
            display: none;
            border: 0;
            background: transparent;
            color: var(--navy);
            font-size: 22px;
        }

        .sidebar-overlay {
            display: none;
        }

        .footer {
            padding: 25px 8px 5px;
            color: #8996a8;
            font-size: 10px;
            text-align: center;
            line-height: 1.8;
        }

        @media (max-width: 1100px) {
            .settings-layout {
                grid-template-columns: 190px minmax(0, 1fr);
                gap: 14px;
            }

            .days-grid {
                grid-template-columns: repeat(3, minmax(0, 1fr));
            }
        }

        @media (max-width: 900px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform .25s ease;
            }

            .sidebar.open {
                transform: translateX(0);
            }

            .sidebar-overlay.show {
                display: block;
                position: fixed;
                inset: 0;
                z-index: 1020;
                background: rgba(15, 23, 42, .45);
            }

            .main {
                margin-left: 0;
            }

            .mobile-menu-btn {
                display: inline-block;
                margin-right: 10px;
            }

            .topbar {
                padding: 0 20px;
            }

            .content {
                padding: 23px 20px 30px;
            }
        }

        @media (max-width: 700px) {
            .settings-layout {
                grid-template-columns: 1fr;
            }

            .settings-nav {
                position: static;
                display: flex;
                gap: 5px;
                overflow-x: auto;
                padding: 9px;
            }

            .settings-nav-title {
                display: none;
            }

            .settings-nav a {
                flex: 0 0 auto;
                margin: 0;
                padding: 10px;
            }

            .settings-nav a span {
                white-space: nowrap;
            }

            .settings-nav a i {
                width: auto;
            }

            .days-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .settings-card {
                padding: 17px;
            }
        }

        @media (max-width: 500px) {
            .content {
                padding: 18px 12px 25px;
            }

            .topbar {
                height: 65px;
                padding: 0 12px;
            }

            .page-heading {
                flex-direction: column;
            }

            .page-heading h1 {
                font-size: 21px;
            }

            .settings-card {
                padding: 14px;
            }

            .setting-row {
                align-items: flex-start;
            }

            .form-actions {
                flex-direction: column-reverse;
            }

            .form-actions button,
            .form-actions a {
                width: 100%;
            }

            .days-grid {
                grid-template-columns: 1fr 1fr;
            }
        }
    </style>
</head>

<body>

<div class="sidebar-overlay" id="sidebarOverlay"></div>

<aside class="sidebar" id="sidebar">

    <a href="index.php" class="brand">
        <div class="brand-icon">
            <i class="bi bi-grid-1x2-fill"></i>
        </div>

        <div>
            <div class="brand-name">ProServe</div>
            <div class="brand-subtitle">CRM & Service Management</div>
        </div>
    </a>

    <div class="nav-heading">Overview</div>

    <a href="index.php" class="side-link">
        <i class="bi bi-speedometer2"></i>
        Dashboard
    </a>

    <div class="nav-heading">Customer Management</div>

    <a href="customers.php" class="side-link">
        <i class="bi bi-people"></i>
        Customers
    </a>

    <a href="leads.php" class="side-link">
        <i class="bi bi-person-lines-fill"></i>
        Leads
    </a>

    <a href="enquiries.php" class="side-link">
        <i class="bi bi-chat-left-text"></i>
        Enquiries
    </a>

    <a href="appointments.php" class="side-link">
        <i class="bi bi-calendar-check"></i>
        Appointments
    </a>

    <div class="nav-heading">Business Operations</div>

    <a href="services.php" class="side-link">
        <i class="bi bi-box-seam"></i>
        Services
    </a>

    <a href="quotations.php" class="side-link">
        <i class="bi bi-file-earmark-text"></i>
        Quotations
    </a>

    <a href="invoices.php" class="side-link">
        <i class="bi bi-receipt"></i>
        Invoices
    </a>

    <div class="nav-heading">System</div>

    <a href="reports.php" class="side-link">
        <i class="bi bi-bar-chart-line"></i>
        Reports & Analytics
    </a>

    <a href="settings.php" class="side-link active">
        <i class="bi bi-gear"></i>
        Settings
    </a>

    <div class="sidebar-footer">
        <strong style="color:#fff;">ProServe Admin</strong><br>
        System configuration<br>
        Portfolio demonstration by Codvanta
    </div>

</aside>

<main class="main">

    <header class="topbar">

        <div class="d-flex align-items-center">
            <button
                class="mobile-menu-btn"
                id="mobileMenuBtn"
                aria-label="Open navigation"
            >
                <i class="bi bi-list"></i>
            </button>

            <div>
                <div class="topbar-title">System Settings</div>
                <div class="topbar-subtitle">Configure your ProServe workspace</div>
            </div>
        </div>

        <div class="d-flex align-items-center gap-3">
            <div class="text-end d-none d-sm-block">
                <div style="font-size:12px;font-weight:800;color:#10233f;">
                    Administrator
                </div>
                <div style="font-size:10px;color:#8996a8;margin-top:3px;">
                    System Management
                </div>
            </div>

            <div class="admin-avatar">
                <i class="bi bi-person-fill-gear"></i>
            </div>
        </div>

    </header>

    <div class="content">

        <section class="page-heading">
            <div>
                <h1>Settings</h1>
                <p>
                    Manage company details, regional preferences, appointment rules,
                    billing defaults, notifications, and system behaviour.
                </p>
            </div>
        </section>

        <?php if ($notice): ?>
            <div class="alert alert-<?= e($noticeType) ?> alert-dismissible fade show mb-4" role="alert">
                <i class="bi <?= $noticeType === 'success' ? 'bi-check-circle-fill' : 'bi-exclamation-triangle-fill' ?> me-2"></i>
                <?= e($notice) ?>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"
                    aria-label="Close"
                ></button>
            </div>
        <?php endif; ?>

        <div class="demo-note mb-4">
            <i class="bi bi-info-circle-fill me-2"></i>
            <strong>Demonstration mode:</strong>
            these preferences are saved in the current PHP session. The appointment,
            billing, notification, and registration options are configuration examples;
            other pages must explicitly read and enforce them before they affect their
            behaviour.
        </div>

        <div class="settings-layout">

            <nav class="settings-nav" aria-label="Settings sections">
                <div class="settings-nav-title">Configuration</div>

                <a href="#general" class="active">
                    <i class="bi bi-building"></i>
                    <span>Company Information</span>
                </a>

                <a href="#regional">
                    <i class="bi bi-globe2"></i>
                    <span>Regional Preferences</span>
                </a>

                <a href="#appointments">
                    <i class="bi bi-calendar-week"></i>
                    <span>Appointments</span>
                </a>

                <a href="#billing">
                    <i class="bi bi-receipt-cutoff"></i>
                    <span>Billing & Quotations</span>
                </a>

                <a href="#notifications">
                    <i class="bi bi-bell"></i>
                    <span>Notifications</span>
                </a>

                <a href="#system">
                    <i class="bi bi-sliders"></i>
                    <span>System Preferences</span>
                </a>

                <a href="#danger">
                    <i class="bi bi-shield-exclamation"></i>
                    <span>Reset Settings</span>
                </a>
            </nav>

            <div class="settings-content">

                <!-- COMPANY INFORMATION -->

                <section class="settings-card" id="general">

                    <div class="card-heading">
                        <div class="heading-icon">
                            <i class="bi bi-building"></i>
                        </div>

                        <div>
                            <h2>Company Information</h2>
                            <p>
                                Configure the business identity and contact information
                                displayed across the system.
                            </p>
                        </div>
                    </div>

                    <form method="POST" action="settings.php#general">
                        <input type="hidden" name="csrf_token" value="<?= e($_SESSION['proserve_settings_csrf']) ?>">
                        <input type="hidden" name="action" value="save_general">

                        <div class="row g-3">

                            <div class="col-md-6">
                                <label for="company_name" class="form-label">Company Name *</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    id="company_name"
                                    name="company_name"
                                    maxlength="100"
                                    required
                                    value="<?= e($settings['company_name']) ?>"
                                >
                            </div>

                            <div class="col-md-6">
                                <label for="tagline" class="form-label">Company Tagline</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    id="tagline"
                                    name="tagline"
                                    maxlength="180"
                                    value="<?= e($settings['tagline']) ?>"
                                >
                            </div>

                            <div class="col-md-6">
                                <label for="company_email" class="form-label">Business Email *</label>
                                <input
                                    type="email"
                                    class="form-control"
                                    id="company_email"
                                    name="company_email"
                                    maxlength="190"
                                    required
                                    value="<?= e($settings['company_email']) ?>"
                                >
                            </div>

                            <div class="col-md-6">
                                <label for="company_phone" class="form-label">Business Phone</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    id="company_phone"
                                    name="company_phone"
                                    maxlength="40"
                                    value="<?= e($settings['company_phone']) ?>"
                                >
                            </div>

                            <div class="col-md-6">
                                <label for="company_website" class="form-label">Company Website</label>
                                <input
                                    type="url"
                                    class="form-control"
                                    id="company_website"
                                    name="company_website"
                                    maxlength="255"
                                    placeholder="https://example.com"
                                    value="<?= e($settings['company_website']) ?>"
                                >
                                <div class="form-text">Include https:// in the website URL.</div>
                            </div>

                            <div class="col-md-6">
                                <label for="company_address" class="form-label">Business Address</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    id="company_address"
                                    name="company_address"
                                    maxlength="250"
                                    value="<?= e($settings['company_address']) ?>"
                                >
                            </div>

                        </div>

                        <div class="form-actions">
                            <button type="submit" class="btn-primary-custom">
                                <i class="bi bi-check2-circle"></i>
                                Save Company Information
                            </button>
                        </div>
                    </form>

                </section>

                <!-- REGIONAL PREFERENCES -->

                <section class="settings-card" id="regional">

                    <div class="card-heading">
                        <div class="heading-icon">
                            <i class="bi bi-globe2"></i>
                        </div>

                        <div>
                            <h2>Regional Preferences</h2>
                            <p>
                                Choose the default currency, time zone, and date format
                                for the demonstration.
                            </p>
                        </div>
                    </div>

                    <form method="POST" action="settings.php#regional">
                        <input type="hidden" name="csrf_token" value="<?= e($_SESSION['proserve_settings_csrf']) ?>">
                        <input type="hidden" name="action" value="save_preferences">

                        <div class="row g-3">

                            <div class="col-md-6">
                                <label for="currency" class="form-label">Default Currency</label>

                                <select name="currency" id="currency" class="form-select">
                                    <?php
                                    $currencies = [
                                        'LKR' => 'LKR — Sri Lankan Rupee',
                                        'USD' => 'USD — US Dollar',
                                        'EUR' => 'EUR — Euro',
                                        'GBP' => 'GBP — British Pound',
                                        'AUD' => 'AUD — Australian Dollar',
                                        'INR' => 'INR — Indian Rupee',
                                    ];
                                    ?>

                                    <?php foreach ($currencies as $code => $label): ?>
                                        <option
                                            value="<?= e($code) ?>"
                                            <?= $settings['currency'] === $code ? 'selected' : '' ?>
                                        >
                                            <?= e($label) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label for="timezone" class="form-label">System Time Zone</label>

                                <select name="timezone" id="timezone" class="form-select">
                                    <?php
                                    $timezones = [
                                        'Asia/Colombo',
                                        'Asia/Kolkata',
                                        'Asia/Dubai',
                                        'Europe/London',
                                        'Europe/Paris',
                                        'America/New_York',
                                        'America/Los_Angeles',
                                        'Australia/Sydney',
                                        'UTC',
                                    ];
                                    ?>

                                    <?php foreach ($timezones as $timezone): ?>
                                        <option
                                            value="<?= e($timezone) ?>"
                                            <?= $settings['timezone'] === $timezone ? 'selected' : '' ?>
                                        >
                                            <?= e($timezone) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label for="date_format" class="form-label">Date Format</label>

                                <select name="date_format" id="date_format" class="form-select">
                                    <?php
                                    $dateFormats = [
                                        'd M Y' => '21 Oct 2026',
                                        'Y-m-d' => '2026-10-21',
                                        'm/d/Y' => '10/21/2026',
                                        'd/m/Y' => '21/10/2026',
                                    ];
                                    ?>

                                    <?php foreach ($dateFormats as $format => $example): ?>
                                        <option
                                            value="<?= e($format) ?>"
                                            <?= $settings['date_format'] === $format ? 'selected' : '' ?>
                                        >
                                            <?= e($example) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Current Server Time</label>

                                <input
                                    type="text"
                                    class="form-control"
                                    readonly
                                    value="<?= e(date('d M Y, h:i A')) ?>"
                                >

                                <div class="form-text">
                                    The server currently uses <?= e(date_default_timezone_get()) ?>.
                                    Changing the saved preference does not change the server time zone.
                                </div>
                            </div>

                        </div>

                        <div class="form-actions">
                            <button type="submit" class="btn-primary-custom">
                                <i class="bi bi-check2-circle"></i>
                                Save Regional Preferences
                            </button>
                        </div>
                    </form>

                </section>

                <!-- APPOINTMENT SETTINGS -->

                <section class="settings-card" id="appointments">

                    <div class="card-heading">
                        <div class="heading-icon">
                            <i class="bi bi-calendar-week"></i>
                        </div>

                        <div>
                            <h2>Appointment Preferences</h2>
                            <p>
                                Set default appointment duration, buffer time,
                                working hours, and available working days.
                            </p>
                        </div>
                    </div>

                    <form method="POST" action="settings.php#appointments">
                        <input type="hidden" name="csrf_token" value="<?= e($_SESSION['proserve_settings_csrf']) ?>">
                        <input type="hidden" name="action" value="save_appointments">

                        <div class="row g-3 mb-4">

                            <div class="col-md-6">
                                <label for="appointment_duration" class="form-label">
                                    Appointment Duration (minutes)
                                </label>

                                <select
                                    name="appointment_duration"
                                    id="appointment_duration"
                                    class="form-select"
                                >
                                    <?php foreach ([15, 20, 30, 45, 60, 90, 120, 180, 240] as $duration): ?>
                                        <option
                                            value="<?= $duration ?>"
                                            <?= (int) $settings['appointment_duration'] === $duration ? 'selected' : '' ?>
                                        >
                                            <?= $duration ?> minutes
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label for="appointment_buffer" class="form-label">
                                    Buffer Between Appointments (minutes)
                                </label>

                                <select
                                    name="appointment_buffer"
                                    id="appointment_buffer"
                                    class="form-select"
                                >
                                    <?php foreach ([0, 5, 10, 15, 20, 30, 45, 60, 90, 120] as $buffer): ?>
                                        <option
                                            value="<?= $buffer ?>"
                                            <?= (int) $settings['appointment_buffer'] === $buffer ? 'selected' : '' ?>
                                        >
                                            <?= $buffer ?> minutes
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label for="working_start" class="form-label">Working Hours Start</label>

                                <input
                                    type="time"
                                    class="form-control"
                                    name="working_start"
                                    id="working_start"
                                    required
                                    value="<?= e($settings['working_start']) ?>"
                                >
                            </div>

                            <div class="col-md-6">
                                <label for="working_end" class="form-label">Working Hours End</label>

                                <input
                                    type="time"
                                    class="form-control"
                                    name="working_end"
                                    id="working_end"
                                    required
                                    value="<?= e($settings['working_end']) ?>"
                                >
                            </div>

                        </div>

                        <label class="form-label mb-3">Working Days</label>

                        <div class="days-grid">

                            <?php foreach ($workingDays as $day): ?>
                                <label class="day-option">
                                    <input
                                        type="checkbox"
                                        class="form-check-input"
                                        name="working_days[]"
                                        value="<?= e($day) ?>"
                                        <?= in_array($day, $settings['working_days'], true) ? 'checked' : '' ?>
                                    >

                                    <?= e($day) ?>
                                </label>
                            <?php endforeach; ?>

                        </div>

                        <div class="form-text mt-3">
                            These are default preferences. Actual availability also depends
                            on the appointment booking page's implementation.
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="btn-primary-custom">
                                <i class="bi bi-check2-circle"></i>
                                Save Appointment Preferences
                            </button>
                        </div>
                    </form>

                </section>

                <!-- BILLING AND QUOTATIONS -->

                <section class="settings-card" id="billing">

                    <div class="card-heading">
                        <div class="heading-icon">
                            <i class="bi bi-receipt-cutoff"></i>
                        </div>

                        <div>
                            <h2>Billing & Quotations</h2>
                            <p>
                                Configure default quotation validity, invoice payment
                                terms, and optional tax settings.
                            </p>
                        </div>
                    </div>

                    <form method="POST" action="settings.php#billing">
                        <input type="hidden" name="csrf_token" value="<?= e($_SESSION['proserve_settings_csrf']) ?>">
                        <input type="hidden" name="action" value="save_billing">

                        <div class="row g-3">

                            <div class="col-md-6">
                                <label for="quotation_validity" class="form-label">
                                    Quotation Validity (days)
                                </label>

                                <input
                                    type="number"
                                    class="form-control"
                                    id="quotation_validity"
                                    name="quotation_validity"
                                    min="1"
                                    max="365"
                                    required
                                    value="<?= e($settings['quotation_validity']) ?>"
                                >

                                <div class="form-text">
                                    Default number of days a quotation remains valid.
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label for="invoice_due_days" class="form-label">
                                    Invoice Payment Terms (days)
                                </label>

                                <input
                                    type="number"
                                    class="form-control"
                                    id="invoice_due_days"
                                    name="invoice_due_days"
                                    min="1"
                                    max="365"
                                    required
                                    value="<?= e($settings['invoice_due_days']) ?>"
                                >

                                <div class="form-text">
                                    Default payment deadline after invoice issuance.
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label for="tax_rate" class="form-label">Tax Rate (%)</label>

                                <div class="input-group">
                                    <input
                                        type="number"
                                        class="form-control"
                                        id="tax_rate"
                                        name="tax_rate"
                                        min="0"
                                        max="100"
                                        step="0.01"
                                        required
                                        value="<?= e($settings['tax_rate']) ?>"
                                    >

                                    <span class="input-group-text">%</span>
                                </div>
                            </div>

                            <div class="col-md-6 d-flex align-items-center">
                                <div class="setting-row w-100">
                                    <div>
                                        <div class="setting-label">Enable Tax</div>
                                        <div class="setting-description">
                                            Apply the configured tax rate in connected billing logic.
                                        </div>
                                    </div>

                                    <input
                                        class="form-check-input"
                                        type="checkbox"
                                        name="tax_enabled"
                                        value="1"
                                        aria-label="Enable tax"
                                        <?= $settings['tax_enabled'] === '1' ? 'checked' : '' ?>
                                    >
                                </div>
                            </div>

                        </div>

                        <div class="form-actions">
                            <button type="submit" class="btn-primary-custom">
                                <i class="bi bi-check2-circle"></i>
                                Save Billing Settings
                            </button>
                        </div>
                    </form>

                </section>

                <!-- NOTIFICATIONS -->

                <section class="settings-card" id="notifications">

                    <div class="card-heading">
                        <div class="heading-icon">
                            <i class="bi bi-bell"></i>
                        </div>

                        <div>
                            <h2>Notification Preferences</h2>
                            <p>
                                Select which types of business events should be enabled
                                for notifications in an integrated system.
                            </p>
                        </div>
                    </div>

                    <form method="POST" action="settings.php#notifications">
                        <input type="hidden" name="csrf_token" value="<?= e($_SESSION['proserve_settings_csrf']) ?>">
                        <input type="hidden" name="action" value="save_notifications">

                        <div class="setting-row">
                            <div>
                                <div class="setting-label">Enable Email Notifications</div>
                                <div class="setting-description">
                                    Master preference for outgoing email notifications.
                                </div>
                            </div>

                            <input
                                class="form-check-input"
                                type="checkbox"
                                name="email_notifications"
                                value="1"
                                aria-label="Enable email notifications"
                                <?= $settings['email_notifications'] === '1' ? 'checked' : '' ?>
                            >
                        </div>

                        <div class="setting-row">
                            <div>
                                <div class="setting-label">New Enquiry Notifications</div>
                                <div class="setting-description">
                                    Notify the relevant team when a new enquiry is submitted.
                                </div>
                            </div>

                            <input
                                class="form-check-input"
                                type="checkbox"
                                name="new_enquiry_notification"
                                value="1"
                                aria-label="Enable new enquiry notifications"
                                <?= $settings['new_enquiry_notification'] === '1' ? 'checked' : '' ?>
                            >
                        </div>

                        <div class="setting-row">
                            <div>
                                <div class="setting-label">Appointment Notifications</div>
                                <div class="setting-description">
                                    Notify the relevant team when an appointment is requested.
                                </div>
                            </div>

                            <input
                                class="form-check-input"
                                type="checkbox"
                                name="new_appointment_notification"
                                value="1"
                                aria-label="Enable appointment notifications"
                                <?= $settings['new_appointment_notification'] === '1' ? 'checked' : '' ?>
                            >
                        </div>

                        <div class="setting-row">
                            <div>
                                <div class="setting-label">Quotation Notifications</div>
                                <div class="setting-description">
                                    Enable notifications for quotation updates and approvals.
                                </div>
                            </div>

                            <input
                                class="form-check-input"
                                type="checkbox"
                                name="quotation_notification"
                                value="1"
                                aria-label="Enable quotation notifications"
                                <?= $settings['quotation_notification'] === '1' ? 'checked' : '' ?>
                            >
                        </div>

                        <div class="setting-row">
                            <div>
                                <div class="setting-label">Invoice Notifications</div>
                                <div class="setting-description">
                                    Enable notifications for invoice creation and payment updates.
                                </div>
                            </div>

                            <input
                                class="form-check-input"
                                type="checkbox"
                                name="invoice_notification"
                                value="1"
                                aria-label="Enable invoice notifications"
                                <?= $settings['invoice_notification'] === '1' ? 'checked' : '' ?>
                            >
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="btn-primary-custom">
                                <i class="bi bi-check2-circle"></i>
                                Save Notification Preferences
                            </button>
                        </div>
                    </form>

                </section>

                <!-- SYSTEM PREFERENCES -->

                <section class="settings-card" id="system">

                    <div class="card-heading">
                        <div class="heading-icon">
                            <i class="bi bi-sliders"></i>
                        </div>

                        <div>
                            <h2>System Preferences</h2>
                            <p>
                                Manage the default public-facing options and system
                                configuration flags for the demonstration.
                            </p>
                        </div>
                    </div>

                    <form method="POST" action="settings.php#system">
                        <input type="hidden" name="csrf_token" value="<?= e($_SESSION['proserve_settings_csrf']) ?>">
                        <input type="hidden" name="action" value="save_system">

                        <div class="setting-row">
                            <div>
                                <div class="setting-label">Maintenance Mode</div>
                                <div class="setting-description">
                                    Configuration flag intended to indicate planned maintenance.
                                    It does not automatically block access to other pages.
                                </div>
                            </div>

                            <input
                                class="form-check-input"
                                type="checkbox"
                                name="maintenance_mode"
                                value="1"
                                aria-label="Enable maintenance mode"
                                <?= $settings['maintenance_mode'] === '1' ? 'checked' : '' ?>
                            >
                        </div>

                        <div class="setting-row">
                            <div>
                                <div class="setting-label">Customer Registration</div>
                                <div class="setting-description">
                                    Allow new customer registrations when the registration
                                    page checks this setting.
                                </div>
                            </div>

                            <input
                                class="form-check-input"
                                type="checkbox"
                                name="allow_customer_registration"
                                value="1"
                                aria-label="Allow customer registration"
                                <?= $settings['allow_customer_registration'] === '1' ? 'checked' : '' ?>
                            >
                        </div>

                        <div class="setting-row">
                            <div>
                                <div class="setting-label">Appointment Booking</div>
                                <div class="setting-description">
                                    Enable the default permission for customers to request
                                    appointments in an integrated implementation.
                                </div>
                            </div>

                            <input
                                class="form-check-input"
                                type="checkbox"
                                name="allow_appointment_booking"
                                value="1"
                                aria-label="Allow appointment booking"
                                <?= $settings['allow_appointment_booking'] === '1' ? 'checked' : '' ?>
                            >
                        </div>

                        <div class="setting-row">
                            <div>
                                <div class="setting-label">Quotation Requests</div>
                                <div class="setting-description">
                                    Enable the default permission for customers to submit
                                    quotation requests.
                                </div>
                            </div>

                            <input
                                class="form-check-input"
                                type="checkbox"
                                name="allow_quote_requests"
                                value="1"
                                aria-label="Allow quotation requests"
                                <?= $settings['allow_quote_requests'] === '1' ? 'checked' : '' ?>
                            >
                        </div>

                        <div class="mt-4">
                            <label for="footer_text" class="form-label">
                                System Footer Text
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                id="footer_text"
                                name="footer_text"
                                maxlength="180"
                                required
                                value="<?= e($settings['footer_text']) ?>"
                            >

                            <div class="form-text">
                                Default footer wording for pages that integrate this setting.
                            </div>
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="btn-primary-custom">
                                <i class="bi bi-check2-circle"></i>
                                Save System Preferences
                            </button>
                        </div>
                    </form>

                </section>

                <!-- RESET SETTINGS -->

                <section class="settings-card danger-zone" id="danger">

                    <div class="card-heading">
                        <div class="heading-icon">
                            <i class="bi bi-shield-exclamation"></i>
                        </div>

                        <div>
                            <h2>Reset Settings</h2>
                            <p>
                                Restore this demonstration's configuration to its original
                                default values.
                            </p>
                        </div>
                    </div>

                    <div class="setting-row">
                        <div>
                            <div class="setting-label">Restore Default Configuration</div>
                            <div class="setting-description">
                                Resets company information, preferences, appointment defaults,
                                billing settings, notification options, and system flags.
                                This does not delete your customers, appointments, quotations,
                                invoices, or enquiries.
                            </div>
                        </div>

                        <button
                            type="button"
                            class="btn-danger-custom flex-shrink-0"
                            data-bs-toggle="modal"
                            data-bs-target="#resetSettingsModal"
                        >
                            <i class="bi bi-arrow-counterclockwise"></i>
                            Reset
                        </button>
                    </div>

                </section>

                <footer class="footer">
                    <strong>ProServe — CRM & Service Management System</strong><br>
                    Portfolio demonstration by Codvanta.<br>
                    Settings updated through this page are stored in the current PHP session.
                </footer>

            </div>

        </div>

    </div>

</main>

<!-- RESET CONFIRMATION MODAL -->

<div class="modal fade" id="resetSettingsModal" tabindex="-1" aria-labelledby="resetSettingsTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border:0;border-radius:15px;overflow:hidden;">

            <div class="modal-header" style="border-bottom:1px solid #edf1f6;padding:20px;">
                <h5 class="modal-title" id="resetSettingsTitle" style="font-size:15px;font-weight:800;color:#10233f;">
                    <i class="bi bi-exclamation-triangle-fill text-danger me-2"></i>
                    Reset System Settings?
                </h5>

                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body" style="padding:22px;color:#64748b;font-size:12px;line-height:1.8;">
                Are you sure you want to restore the default settings? Your current
                configuration preferences will be replaced. Your CRM records will not
                be deleted.
            </div>

            <div class="modal-footer" style="border-top:1px solid #edf1f6;padding:16px 20px;">

                <button type="button" class="btn-light-custom" data-bs-dismiss="modal">
                    Cancel
                </button>

                <form method="POST" action="settings.php#danger">
                    <input type="hidden" name="csrf_token" value="<?= e($_SESSION['proserve_settings_csrf']) ?>">
                    <input type="hidden" name="action" value="reset_settings">

                    <button type="submit" class="btn-danger-custom">
                        <i class="bi bi-arrow-counterclockwise"></i>
                        Yes, Reset Settings
                    </button>
                </form>

            </div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebarOverlay');
    const menuButton = document.getElementById('mobileMenuBtn');

    function closeSidebar() {
        sidebar.classList.remove('open');
        overlay.classList.remove('show');
    }

    menuButton.addEventListener('click', function () {
        sidebar.classList.toggle('open');
        overlay.classList.toggle('show');
    });

    overlay.addEventListener('click', closeSidebar);

    // Highlight the current section in the settings navigation.
    const settingsLinks = document.querySelectorAll('.settings-nav a');

    settingsLinks.forEach(function (link) {
        link.addEventListener('click', function () {
            settingsLinks.forEach(function (item) {
                item.classList.remove('active');
            });

            this.classList.add('active');
        });
    });

    // Keep the active settings section highlighted while scrolling.
    const settingSections = document.querySelectorAll('.settings-content > section[id]');

    if ('IntersectionObserver' in window) {
        const observer = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    settingsLinks.forEach(function (link) {
                        link.classList.toggle(
                            'active',
                            link.getAttribute('href') === '#' + entry.target.id
                        );
                    });
                }
            });
        }, {
            rootMargin: '-15% 0px -70% 0px'
        });

        settingSections.forEach(function (section) {
            observer.observe(section);
        });
    }
</script>

</body>
</html>