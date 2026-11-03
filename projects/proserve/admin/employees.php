
<?php
session_start();
date_default_timezone_set('Asia/Colombo');

/*
|--------------------------------------------------------------------------
| ProServe CRM - Employee Management
|--------------------------------------------------------------------------
| Demonstration only: data is stored in PHP sessions, not a database.
*/

function e($value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function employeeMoney($amount): string
{
    return 'LKR ' . number_format((float) $amount, 2);
}

function employeeInitials($name): string
{
    $parts = preg_split('/\s+/', trim((string) $name));
    $initials = '';

    foreach (array_slice($parts, 0, 2) as $part) {
        if ($part !== '') {
            $initials .= strtoupper(substr($part, 0, 1));
        }
    }

    return $initials ?: 'EM';
}

function employeeStatusClass($status): string
{
    return match (strtoupper((string) $status)) {
        'ACTIVE' => 'status-active',
        'ON LEAVE' => 'status-leave',
        'INACTIVE' => 'status-inactive',
        default => 'status-inactive',
    };
}

function employeeReference(array $employee): string
{
    return (string) ($employee['employee_id'] ?? 'EMP-000');
}

function employeeDepartment(array $employee): string
{
    return (string) ($employee['department'] ?? 'General');
}

function employeeName(array $employee): string
{
    return (string) ($employee['name'] ?? 'Unknown Employee');
}

/*
|--------------------------------------------------------------------------
| CSRF protection
|--------------------------------------------------------------------------
*/

if (empty($_SESSION['proserve_employees_csrf'])) {
    $_SESSION['proserve_employees_csrf'] = bin2hex(random_bytes(32));
}

$csrfToken = $_SESSION['proserve_employees_csrf'];

/*
|--------------------------------------------------------------------------
| Demo employee records
|--------------------------------------------------------------------------
*/

if (!isset($_SESSION['proserve_employees'])) {
    $today = date('Y-m-d');

    $_SESSION['proserve_employees'] = [
        [
            'employee_id' => 'EMP-001',
            'name' => 'Amelia Fernando',
            'email' => 'amelia@proserve.demo',
            'phone' => '+94 77 123 4567',
            'job_title' => 'Project Manager',
            'department' => 'Management',
            'salary' => 150000,
            'joined_date' => date('Y-m-d', strtotime('-2 years')),
            'status' => 'ACTIVE',
            'employment_type' => 'Full-time',
            'address' => 'Colombo, Sri Lanka',
            'notes' => 'Manages project planning and delivery.',
        ],
        [
            'employee_id' => 'EMP-002',
            'name' => 'Daniel Perera',
            'email' => 'daniel@proserve.demo',
            'phone' => '+94 71 234 5678',
            'job_title' => 'Senior Developer',
            'department' => 'Development',
            'salary' => 125000,
            'joined_date' => date('Y-m-d', strtotime('-18 months')),
            'status' => 'ACTIVE',
            'employment_type' => 'Full-time',
            'address' => 'Negombo, Sri Lanka',
            'notes' => 'Responsible for application development.',
        ],
        [
            'employee_id' => 'EMP-003',
            'name' => 'Sarah Wijesinghe',
            'email' => 'sarah@proserve.demo',
            'phone' => '+94 76 345 6789',
            'job_title' => 'UI/UX Designer',
            'department' => 'Design',
            'salary' => 95000,
            'joined_date' => date('Y-m-d', strtotime('-1 year')),
            'status' => 'ACTIVE',
            'employment_type' => 'Full-time',
            'address' => 'Gampaha, Sri Lanka',
            'notes' => 'Designs user-friendly interfaces.',
        ],
        [
            'employee_id' => 'EMP-004',
            'name' => 'Michael Silva',
            'email' => 'michael@proserve.demo',
            'phone' => '+94 75 456 7890',
            'job_title' => 'Marketing Executive',
            'department' => 'Marketing',
            'salary' => 80000,
            'joined_date' => date('Y-m-d', strtotime('-9 months')),
            'status' => 'ON LEAVE',
            'employment_type' => 'Full-time',
            'address' => 'Wattala, Sri Lanka',
            'notes' => 'Handles campaigns and customer outreach.',
        ],
        [
            'employee_id' => 'EMP-005',
            'name' => 'Nethmi Jayawardena',
            'email' => 'nethmi@proserve.demo',
            'phone' => '+94 72 567 8901',
            'job_title' => 'Customer Support Officer',
            'department' => 'Customer Support',
            'salary' => 65000,
            'joined_date' => date('Y-m-d', strtotime('-6 months')),
            'status' => 'ACTIVE',
            'employment_type' => 'Full-time',
            'address' => 'Ja-Ela, Sri Lanka',
            'notes' => 'Supports customer requests and enquiries.',
        ],
        [
            'employee_id' => 'EMP-006',
            'name' => 'Kevin Rodrigo',
            'email' => 'kevin@proserve.demo',
            'phone' => '+94 70 678 9012',
            'job_title' => 'QA Engineer',
            'department' => 'Development',
            'salary' => 90000,
            'joined_date' => date('Y-m-d', strtotime('-4 months')),
            'status' => 'INACTIVE',
            'employment_type' => 'Contract',
            'address' => 'Kurunegala, Sri Lanka',
            'notes' => 'Previously handled application testing.',
        ],
    ];
}

if (!isset($_SESSION['proserve_employee_counter'])) {
    $_SESSION['proserve_employee_counter'] = 7;
}

/*
|--------------------------------------------------------------------------
| Notifications and redirects
|--------------------------------------------------------------------------
*/

function employeeRedirect(string $message, string $type = 'success'): void
{
    $_SESSION['proserve_employees_notice'] = [
        'message' => $message,
        'type' => $type,
    ];

    header('Location: employees.php');
    exit;
}

/*
|--------------------------------------------------------------------------
| Handle form submissions
|--------------------------------------------------------------------------
*/

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $submittedToken = (string) ($_POST['csrf_token'] ?? '');

    if (!hash_equals(
        (string) $_SESSION['proserve_employees_csrf'],
        $submittedToken
    )) {
        employeeRedirect(
            'Your session token is invalid. Please try again.',
            'danger'
        );
    }

    $employees = $_SESSION['proserve_employees'];
    $action = (string) ($_POST['action'] ?? '');

    if ($action === 'save') {
        $employeeId = trim((string) ($_POST['employee_id'] ?? ''));
        $isEditing = $employeeId !== '';

        $name = trim((string) ($_POST['name'] ?? ''));
        $email = strtolower(trim((string) ($_POST['email'] ?? '')));
        $phone = trim((string) ($_POST['phone'] ?? ''));
        $jobTitle = trim((string) ($_POST['job_title'] ?? ''));
        $department = trim((string) ($_POST['department'] ?? ''));
        $salaryInput = trim((string) ($_POST['salary'] ?? ''));
        $joinedDate = trim((string) ($_POST['joined_date'] ?? ''));
        $status = strtoupper(trim((string) ($_POST['status'] ?? 'ACTIVE')));
        $employmentType = trim((string) ($_POST['employment_type'] ?? 'Full-time'));
        $address = trim((string) ($_POST['address'] ?? ''));
        $notes = trim((string) ($_POST['notes'] ?? ''));

        $allowedStatuses = ['ACTIVE', 'ON LEAVE', 'INACTIVE'];
        $allowedEmploymentTypes = ['Full-time', 'Part-time', 'Contract', 'Internship'];

        if (
            $name === '' ||
            $email === '' ||
            $jobTitle === '' ||
            $department === '' ||
            $salaryInput === '' ||
            $joinedDate === ''
        ) {
            employeeRedirect(
                'Please complete all required fields.',
                'danger'
            );
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            employeeRedirect('Please enter a valid email address.', 'danger');
        }

        if (!is_numeric($salaryInput) || (float) $salaryInput < 0) {
            employeeRedirect('Please enter a valid non-negative salary.', 'danger');
        }

        $dateObject = DateTime::createFromFormat('Y-m-d', $joinedDate);

        if (!$dateObject || $dateObject->format('Y-m-d') !== $joinedDate) {
            employeeRedirect('Please enter a valid joining date.', 'danger');
        }

        if (!in_array($status, $allowedStatuses, true)) {
            $status = 'ACTIVE';
        }

        if (!in_array($employmentType, $allowedEmploymentTypes, true)) {
            $employmentType = 'Full-time';
        }

        foreach ($employees as $existing) {
            if (
                strtolower((string) ($existing['email'] ?? '')) === $email &&
                ($existing['employee_id'] ?? '') !== $employeeId
            ) {
                employeeRedirect(
                    'Another employee already uses this email address.',
                    'danger'
                );
            }
        }

        $record = [
            'employee_id' => $employeeId,
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'job_title' => $jobTitle,
            'department' => $department,
            'salary' => round((float) $salaryInput, 2),
            'joined_date' => $joinedDate,
            'status' => $status,
            'employment_type' => $employmentType,
            'address' => $address,
            'notes' => $notes,
        ];

        if ($isEditing) {
            $found = false;

            foreach ($employees as $index => $existing) {
                if (($existing['employee_id'] ?? '') === $employeeId) {
                    $employees[$index] = $record;
                    $found = true;
                    break;
                }
            }

            if (!$found) {
                employeeRedirect('Employee record was not found.', 'danger');
            }

            $_SESSION['proserve_employees'] = $employees;
            employeeRedirect('Employee details updated successfully.');
        } else {
            $number = (int) $_SESSION['proserve_employee_counter'];

            do {
                $newId = 'EMP-' . str_pad((string) $number, 3, '0', STR_PAD_LEFT);
                $number++;
                $idExists = false;

                foreach ($employees as $existing) {
                    if (($existing['employee_id'] ?? '') === $newId) {
                        $idExists = true;
                        break;
                    }
                }
            } while ($idExists);

            $_SESSION['proserve_employee_counter'] = $number;
            $record['employee_id'] = $newId;

            $employees[] = $record;
            $_SESSION['proserve_employees'] = $employees;

            employeeRedirect('New employee added successfully.');
        }
    }

    if ($action === 'toggle_status') {
        $employeeId = (string) ($_POST['employee_id'] ?? '');
        $found = false;

        foreach ($employees as $index => $employee) {
            if (($employee['employee_id'] ?? '') === $employeeId) {
                $currentStatus = strtoupper((string) ($employee['status'] ?? 'ACTIVE'));

                $employees[$index]['status'] =
                    $currentStatus === 'ACTIVE' ? 'INACTIVE' : 'ACTIVE';

                $found = true;
                break;
            }
        }

        if (!$found) {
            employeeRedirect('Employee record was not found.', 'danger');
        }

        $_SESSION['proserve_employees'] = $employees;
        employeeRedirect('Employee status updated successfully.');
    }

    if ($action === 'delete') {
        $employeeId = (string) ($_POST['employee_id'] ?? '');
        $found = false;

        foreach ($employees as $index => $employee) {
            if (($employee['employee_id'] ?? '') === $employeeId) {
                array_splice($employees, $index, 1);
                $found = true;
                break;
            }
        }

        if (!$found) {
            employeeRedirect('Employee record was not found.', 'danger');
        }

        $_SESSION['proserve_employees'] = array_values($employees);
        employeeRedirect('Employee record deleted successfully.');
    }

    employeeRedirect('Unknown action. Please try again.', 'danger');
}

/*
|--------------------------------------------------------------------------
| Read and filter employees
|--------------------------------------------------------------------------
*/

$employees = $_SESSION['proserve_employees'];

$search = trim((string) ($_GET['search'] ?? ''));
$departmentFilter = trim((string) ($_GET['department'] ?? ''));
$statusFilter = strtoupper(trim((string) ($_GET['status'] ?? '')));
$sort = (string) ($_GET['sort'] ?? 'newest');

$departments = [
    'Management',
    'Development',
    'Design',
    'Marketing',
    'Customer Support',
    'Finance',
    'Human Resources',
    'Operations',
    'General',
];

$filteredEmployees = array_values(array_filter(
    $employees,
    function ($employee) use ($search, $departmentFilter, $statusFilter) {
        $searchable = implode(' ', [
            $employee['employee_id'] ?? '',
            $employee['name'] ?? '',
            $employee['email'] ?? '',
            $employee['job_title'] ?? '',
            $employee['department'] ?? '',
            $employee['phone'] ?? '',
        ]);

        if (
            $search !== '' &&
            stripos($searchable, $search) === false
        ) {
            return false;
        }

        if (
            $departmentFilter !== '' &&
            ($employee['department'] ?? '') !== $departmentFilter
        ) {
            return false;
        }

        if (
            $statusFilter !== '' &&
            strtoupper((string) ($employee['status'] ?? '')) !== $statusFilter
        ) {
            return false;
        }

        return true;
    }
));

if ($sort === 'name') {
    usort($filteredEmployees, fn($a, $b) =>
        strcasecmp($a['name'] ?? '', $b['name'] ?? '')
    );
} elseif ($sort === 'salary_high') {
    usort($filteredEmployees, fn($a, $b) =>
        (float) ($b['salary'] ?? 0) <=> (float) ($a['salary'] ?? 0)
    );
} elseif ($sort === 'oldest') {
    usort($filteredEmployees, fn($a, $b) =>
        strcmp($a['joined_date'] ?? '', $b['joined_date'] ?? '')
    );
} else {
    usort($filteredEmployees, fn($a, $b) =>
        strcmp($b['joined_date'] ?? '', $a['joined_date'] ?? '')
    );
}

/*
|--------------------------------------------------------------------------
| Dashboard statistics
|--------------------------------------------------------------------------
*/

$totalEmployees = count($employees);
$activeEmployees = 0;
$employeesOnLeave = 0;
$inactiveEmployees = 0;
$totalMonthlyPayroll = 0;

foreach ($employees as $employee) {
    $status = strtoupper((string) ($employee['status'] ?? ''));

    if ($status === 'ACTIVE') {
        $activeEmployees++;
        $totalMonthlyPayroll += (float) ($employee['salary'] ?? 0);
    } elseif ($status === 'ON LEAVE') {
        $employeesOnLeave++;
    } elseif ($status === 'INACTIVE') {
        $inactiveEmployees++;
    }
}

$notice = $_SESSION['proserve_employees_notice'] ?? null;
unset($_SESSION['proserve_employees_notice']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Employee Management | ProServe CRM</title>

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
            --white: #ffffff;
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

        .btn-primary {
            background: var(--primary);
            border-color: var(--primary);
        }

        .btn-primary:hover {
            background: var(--primary-dark);
            border-color: var(--primary-dark);
        }

        .btn-add {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 11px 17px;
            border-radius: 9px;
            font-weight: 700;
            white-space: nowrap;
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

        .stat-icon.green {
            background: #e5f8ef;
            color: #13945c;
        }

        .stat-icon.orange {
            background: #fff3df;
            color: #d88712;
        }

        .stat-icon.purple {
            background: #f1eaff;
            color: #8254d9;
        }

        .stat-label {
            margin-bottom: 5px;
            color: var(--muted);
            font-size: 12px;
        }

        .stat-value {
            color: #182943;
            font-size: 23px;
            font-weight: 800;
            letter-spacing: -0.5px;
            overflow-wrap: anywhere;
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
            min-height: 90px;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #8eb0ff;
        }

        .table-responsive {
            border-radius: 0 0 14px 14px;
        }

        .employee-table {
            min-width: 1000px;
            margin: 0;
            vertical-align: middle;
        }

        .employee-table thead th {
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

        .employee-table tbody td {
            padding: 16px 18px;
            border-color: #eef1f6;
            color: #44516a;
            font-size: 12px;
        }

        .employee-table tbody tr:hover {
            background: #fbfcff;
        }

        .employee-info {
            display: flex;
            align-items: center;
            gap: 11px;
            min-width: 190px;
        }

        .employee-avatar {
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

        .employee-name {
            color: #1d2d46;
            font-weight: 800;
            font-size: 12px;
        }

        .employee-sub {
            margin-top: 4px;
            color: #8a96a8;
            font-size: 10px;
        }

        .job-title {
            color: #2d3a50;
            font-weight: 700;
        }

        .department-pill {
            display: inline-block;
            padding: 6px 9px;
            border-radius: 6px;
            background: #f0f4fa;
            color: #56647b;
            font-size: 10px;
            font-weight: 700;
            white-space: nowrap;
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

        .status-active {
            background: #e6f8ee;
            color: #14804a;
        }

        .status-leave {
            background: #fff3df;
            color: #a96709;
        }

        .status-inactive {
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
            width: 31px;
            height: 31px;
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
                align-items: stretch;
            }

            .page-heading h1 {
                font-size: 23px;
            }

            .btn-add {
                width: 100%;
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
                font-size: 18px;
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

        <a href="employees.php" class="nav-link active">
            <i class="bi bi-person-badge"></i> Employees
        </a>

        <a href="enquiries.php" class="nav-link">
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
            <div class="breadcrumb-text">ProServe / Business Management / Employees</div>
            <h2 class="topbar-title">Employee Management</h2>
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
                <h1>Employee Management</h1>
                <p>Manage your team, employee details, departments and employment status.</p>
            </div>

            <button
                type="button"
                class="btn btn-primary btn-add"
                data-bs-toggle="modal"
                data-bs-target="#employeeModal"
                onclick="prepareAddEmployee()"
            >
                <i class="bi bi-person-plus-fill"></i>
                Add Employee
            </button>
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
                    <i class="bi bi-people-fill"></i>
                </div>
                <div>
                    <div class="stat-label">Total Employees</div>
                    <div class="stat-value"><?= number_format($totalEmployees) ?></div>
                    <div class="stat-foot">All employee records</div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon green">
                    <i class="bi bi-person-check-fill"></i>
                </div>
                <div>
                    <div class="stat-label">Active Employees</div>
                    <div class="stat-value"><?= number_format($activeEmployees) ?></div>
                    <div class="stat-foot">Currently active</div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon orange">
                    <i class="bi bi-calendar2-week-fill"></i>
                </div>
                <div>
                    <div class="stat-label">On Leave</div>
                    <div class="stat-value"><?= number_format($employeesOnLeave) ?></div>
                    <div class="stat-foot">Employees on leave</div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon purple">
                    <i class="bi bi-wallet2"></i>
                </div>
                <div>
                    <div class="stat-label">Active Monthly Payroll</div>
                    <div class="stat-value" style="font-size:19px;">
                        <?= e(employeeMoney($totalMonthlyPayroll)) ?>
                    </div>
                    <div class="stat-foot">Based on active employee salaries</div>
                </div>
            </div>

        </section>

        <!-- Demo notice -->
        <div class="demo-note mb-4">
            <i class="bi bi-info-circle-fill fs-5"></i>
            <div>
                <strong>Portfolio demonstration:</strong>
                Additions, edits, status changes and deletions are stored in the current PHP session.
                This page does not connect to a real employee database or process payroll payments.
            </div>
        </div>

        <!-- Search and filters -->
        <section class="panel">
            <div class="panel-header">
                <div>
                    <h2><i class="bi bi-funnel me-2 text-primary"></i>Search & Filter Employees</h2>
                    <p>Find employees by name, contact details, job title or department.</p>
                </div>
            </div>

            <div class="filter-body">
                <form method="GET" action="employees.php">
                    <div class="row g-3 align-items-end">

                        <div class="col-lg-4 col-md-6">
                            <label for="search" class="form-label">Search Employees</label>
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
                                    placeholder="Name, email, ID, job title..."
                                >
                            </div>
                        </div>

                        <div class="col-lg-2 col-md-6">
                            <label for="department" class="form-label">Department</label>
                            <select name="department" id="department" class="form-select">
                                <option value="">All Departments</option>
                                <?php foreach ($departments as $department): ?>
                                    <option
                                        value="<?= e($department) ?>"
                                        <?= $departmentFilter === $department ? 'selected' : '' ?>
                                    >
                                        <?= e($department) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-lg-2 col-md-6">
                            <label for="status" class="form-label">Employment Status</label>
                            <select name="status" id="status" class="form-select">
                                <option value="">All Statuses</option>
                                <option value="ACTIVE" <?= $statusFilter === 'ACTIVE' ? 'selected' : '' ?>>Active</option>
                                <option value="ON LEAVE" <?= $statusFilter === 'ON LEAVE' ? 'selected' : '' ?>>On Leave</option>
                                <option value="INACTIVE" <?= $statusFilter === 'INACTIVE' ? 'selected' : '' ?>>Inactive</option>
                            </select>
                        </div>

                        <div class="col-lg-2 col-md-6">
                            <label for="sort" class="form-label">Sort By</label>
                            <select name="sort" id="sort" class="form-select">
                                <option value="newest" <?= $sort === 'newest' ? 'selected' : '' ?>>Newest Joined</option>
                                <option value="oldest" <?= $sort === 'oldest' ? 'selected' : '' ?>>Oldest Joined</option>
                                <option value="name" <?= $sort === 'name' ? 'selected' : '' ?>>Employee Name</option>
                                <option value="salary_high" <?= $sort === 'salary_high' ? 'selected' : '' ?>>Highest Salary</option>
                            </select>
                        </div>

                        <div class="col-lg-2 col-md-12 d-flex gap-2">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="bi bi-search me-1"></i> Search
                            </button>
                            <a href="employees.php" class="btn btn-light border" title="Clear filters">
                                <i class="bi bi-arrow-counterclockwise"></i>
                            </a>
                        </div>

                    </div>
                </form>
            </div>
        </section>

        <!-- Employee table -->
        <section class="panel">

            <div class="panel-header">
                <div>
                    <h2>Employee Directory</h2>
                    <p>Review employee profiles and manage team records.</p>
                </div>

                <span class="badge rounded-pill text-bg-light border px-3 py-2">
                    <?= count($filteredEmployees) ?> result<?= count($filteredEmployees) === 1 ? '' : 's' ?>
                </span>
            </div>

            <div class="table-responsive">
                <table class="table employee-table">
                    <thead>
                    <tr>
                        <th>Employee</th>
                        <th>Job Title</th>
                        <th>Department</th>
                        <th>Contact</th>
                        <th>Monthly Salary</th>
                        <th>Joined Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                    </thead>

                    <tbody>
                    <?php if (empty($filteredEmployees)): ?>

                        <tr>
                            <td colspan="8" class="empty-state">
                                <i class="bi bi-people"></i>
                                <strong>No employees found</strong>
                                <span>Try changing your search criteria or add a new employee.</span>
                            </td>
                        </tr>

                    <?php else: ?>

                        <?php foreach ($filteredEmployees as $employee): ?>
                            <?php
                                $employeeStatus = strtoupper((string) ($employee['status'] ?? 'INACTIVE'));
                                $joinedDateFormatted = !empty($employee['joined_date'])
                                    ? date('d M Y', strtotime($employee['joined_date']))
                                    : '—';
                            ?>

                            <tr>
                                <td>
                                    <div class="employee-info">
                                        <div class="employee-avatar">
                                            <?= e(employeeInitials(employeeName($employee))) ?>
                                        </div>

                                        <div>
                                            <div class="employee-name">
                                                <?= e(employeeName($employee)) ?>
                                            </div>
                                            <div class="employee-sub">
                                                <?= e(employeeReference($employee)) ?>
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <td>
                                    <div class="job-title">
                                        <?= e($employee['job_title'] ?? '—') ?>
                                    </div>
                                    <div class="employee-sub">
                                        <?= e($employee['employment_type'] ?? 'Full-time') ?>
                                    </div>
                                </td>

                                <td>
                                    <span class="department-pill">
                                        <?= e(employeeDepartment($employee)) ?>
                                    </span>
                                </td>

                                <td>
                                    <div class="fw-semibold" style="font-size:11px;">
                                        <?= e($employee['email'] ?? '—') ?>
                                    </div>
                                    <div class="employee-sub">
                                        <?= e($employee['phone'] ?? 'No phone number') ?>
                                    </div>
                                </td>

                                <td>
                                    <div class="fw-bold" style="color:#263751;">
                                        <?= e(employeeMoney($employee['salary'] ?? 0)) ?>
                                    </div>
                                </td>

                                <td><?= e($joinedDateFormatted) ?></td>

                                <td>
                                    <span class="status-pill <?= e(employeeStatusClass($employeeStatus)) ?>">
                                        <?= e($employeeStatus) ?>
                                    </span>
                                </td>

                                <td>
                                    <div class="action-group">

                                        <button
                                            type="button"
                                            class="action-btn"
                                            title="View employee details"
                                            data-bs-toggle="modal"
                                            data-bs-target="#employeeViewModal"
                                            data-employee="<?= e(json_encode($employee, JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_TAG | JSON_HEX_AMP)) ?>"
                                            onclick="viewEmployeeFromButton(this)"
                                        >
                                            <i class="bi bi-eye"></i>
                                        </button>

                                        <button
                                            type="button"
                                            class="action-btn"
                                            title="Edit employee"
                                            data-employee="<?= e(json_encode($employee, JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_TAG | JSON_HEX_AMP)) ?>"
                                            onclick="editEmployeeFromButton(this)"
                                        >
                                            <i class="bi bi-pencil-square"></i>
                                        </button>

                                        <form method="POST" action="employees.php" class="d-inline">
                                            <input type="hidden" name="csrf_token" value="<?= e($csrfToken) ?>">
                                            <input type="hidden" name="action" value="toggle_status">
                                            <input type="hidden" name="employee_id" value="<?= e($employee['employee_id'] ?? '') ?>">

                                            <button
                                                type="submit"
                                                class="action-btn"
                                                title="<?= $employeeStatus === 'ACTIVE' ? 'Deactivate employee' : 'Activate employee' ?>"
                                                onclick="return confirm('<?= $employeeStatus === 'ACTIVE' ? 'Deactivate' : 'Activate' ?> this employee?');"
                                            >
                                                <i class="bi <?= $employeeStatus === 'ACTIVE' ? 'bi-person-dash' : 'bi-person-check' ?>"></i>
                                            </button>
                                        </form>

                                        <form method="POST" action="employees.php" class="d-inline">
                                            <input type="hidden" name="csrf_token" value="<?= e($csrfToken) ?>">
                                            <input type="hidden" name="action" value="delete">
                                            <input type="hidden" name="employee_id" value="<?= e($employee['employee_id'] ?? '') ?>">

                                            <button
                                                type="submit"
                                                class="action-btn danger"
                                                title="Delete employee"
                                                onclick="return confirm('Are you sure you want to delete this employee record?');"
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
                    Showing <?= count($filteredEmployees) ?> of <?= $totalEmployees ?> employee records
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

<!-- Add/Edit Employee Modal -->
<div class="modal fade" id="employeeModal" tabindex="-1" aria-labelledby="employeeModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">

            <form method="POST" action="employees.php" id="employeeForm">
                <input type="hidden" name="csrf_token" value="<?= e($csrfToken) ?>">
                <input type="hidden" name="action" value="save">
                <input type="hidden" name="employee_id" id="employee_id">

                <div class="modal-header">
                    <div>
                        <h5 class="modal-title" id="employeeModalTitle">Add New Employee</h5>
                        <div class="text-secondary mt-1" style="font-size:11px;">
                            Enter the employee's professional and employment details.
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="row g-3">

                        <div class="col-md-6">
                            <label for="name" class="form-label">Full Name *</label>
                            <input type="text" class="form-control" id="name" name="name" maxlength="120" required>
                        </div>

                        <div class="col-md-6">
                            <label for="email" class="form-label">Email Address *</label>
                            <input type="email" class="form-control" id="email" name="email" maxlength="150" required>
                        </div>

                        <div class="col-md-6">
                            <label for="phone" class="form-label">Phone Number</label>
                            <input type="text" class="form-control" id="phone" name="phone" maxlength="40" placeholder="+94 77 123 4567">
                        </div>

                        <div class="col-md-6">
                            <label for="job_title" class="form-label">Job Title *</label>
                            <input type="text" class="form-control" id="job_title" name="job_title" maxlength="100" placeholder="e.g. Software Engineer" required>
                        </div>

                        <div class="col-md-6">
                            <label for="departmentInput" class="form-label">Department *</label>
                            <input
                                type="text"
                                class="form-control"
                                id="departmentInput"
                                name="department"
                                list="departmentOptions"
                                maxlength="100"
                                placeholder="Select or enter department"
                                required
                            >
                            <datalist id="departmentOptions">
                                <?php foreach ($departments as $department): ?>
                                    <option value="<?= e($department) ?>">
                                <?php endforeach; ?>
                            </datalist>
                        </div>

                        <div class="col-md-6">
                            <label for="salary" class="form-label">Monthly Salary (LKR) *</label>
                            <input type="number" class="form-control" id="salary" name="salary" min="0" max="100000000" step="0.01" required>
                        </div>

                        <div class="col-md-6">
                            <label for="joined_date" class="form-label">Joining Date *</label>
                            <input type="date" class="form-control" id="joined_date" name="joined_date" required>
                        </div>

                        <div class="col-md-6">
                            <label for="employment_type" class="form-label">Employment Type</label>
                            <select class="form-select" id="employment_type" name="employment_type">
                                <option value="Full-time">Full-time</option>
                                <option value="Part-time">Part-time</option>
                                <option value="Contract">Contract</option>
                                <option value="Internship">Internship</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="employee_status" class="form-label">Employment Status</label>
                            <select class="form-select" id="employee_status" name="status">
                                <option value="ACTIVE">Active</option>
                                <option value="ON LEAVE">On Leave</option>
                                <option value="INACTIVE">Inactive</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="address" class="form-label">Address / Location</label>
                            <input type="text" class="form-control" id="address" name="address" maxlength="200" placeholder="City or address">
                        </div>

                        <div class="col-12">
                            <label for="notes" class="form-label">Employee Notes</label>
                            <textarea class="form-control" id="notes" name="notes" maxlength="1000" placeholder="Responsibilities, skills or other notes..."></textarea>
                        </div>

                        <div class="col-12">
                            <div class="demo-note">
                                <i class="bi bi-info-circle"></i>
                                <div>
                                    Fields marked with * are required. Salary information is for demonstration purposes only.
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary px-4" id="saveEmployeeButton">
                        <i class="bi bi-check2-circle me-1"></i> Save Employee
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

<!-- View Employee Modal -->
<div class="modal fade" id="employeeViewModal" tabindex="-1" aria-labelledby="employeeViewModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <div>
                    <h5 class="modal-title" id="employeeViewModalTitle">Employee Profile</h5>
                    <div class="text-secondary mt-1" style="font-size:11px;">Employee information and employment details.</div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div class="d-flex align-items-center gap-3 mb-4">
                    <div class="employee-avatar" id="viewInitials" style="width:58px;height:58px;font-size:18px;">EM</div>
                    <div>
                        <h4 class="mb-1 fw-bold" id="viewName">Employee Name</h4>
                        <div class="text-secondary" id="viewJobTitle">Job Title</div>
                        <div class="mt-2">
                            <span class="status-pill status-active" id="viewStatus">ACTIVE</span>
                        </div>
                    </div>
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="text-secondary mb-1" style="font-size:11px;">Employee ID</div>
                        <div class="fw-bold" id="viewEmployeeId">—</div>
                    </div>

                    <div class="col-md-6">
                        <div class="text-secondary mb-1" style="font-size:11px;">Department</div>
                        <div class="fw-bold" id="viewDepartment">—</div>
                    </div>

                    <div class="col-md-6">
                        <div class="text-secondary mb-1" style="font-size:11px;">Email Address</div>
                        <div class="fw-semibold text-break" id="viewEmail">—</div>
                    </div>

                    <div class="col-md-6">
                        <div class="text-secondary mb-1" style="font-size:11px;">Phone Number</div>
                        <div class="fw-semibold" id="viewPhone">—</div>
                    </div>

                    <div class="col-md-6">
                        <div class="text-secondary mb-1" style="font-size:11px;">Monthly Salary</div>
                        <div class="fw-bold text-primary" id="viewSalary">—</div>
                    </div>

                    <div class="col-md-6">
                        <div class="text-secondary mb-1" style="font-size:11px;">Joining Date</div>
                        <div class="fw-semibold" id="viewJoinedDate">—</div>
                    </div>

                    <div class="col-md-6">
                        <div class="text-secondary mb-1" style="font-size:11px;">Employment Type</div>
                        <div class="fw-semibold" id="viewEmploymentType">—</div>
                    </div>

                    <div class="col-md-6">
                        <div class="text-secondary mb-1" style="font-size:11px;">Address / Location</div>
                        <div class="fw-semibold" id="viewAddress">—</div>
                    </div>

                    <div class="col-12">
                        <div class="text-secondary mb-1" style="font-size:11px;">Employee Notes</div>
                        <div class="p-3 rounded-3" style="background:#f7f9fc;line-height:1.7;" id="viewNotes">No notes available.</div>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Close</button>
            </div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    const employeeModalElement = document.getElementById('employeeModal');
    const employeeForm = document.getElementById('employeeForm');

    function prepareAddEmployee() {
        employeeForm.reset();

        document.getElementById('employee_id').value = '';
        document.getElementById('employeeModalTitle').textContent = 'Add New Employee';
        document.getElementById('saveEmployeeButton').innerHTML =
            '<i class="bi bi-check2-circle me-1"></i> Save Employee';

        document.getElementById('employee_status').value = 'ACTIVE';
        document.getElementById('employment_type').value = 'Full-time';
        document.getElementById('joined_date').value =
            new Date().toLocaleDateString('en-CA');
    }

    function editEmployeeFromButton(button) {
        let employee;

        try {
            employee = JSON.parse(button.dataset.employee);
        } catch (error) {
            alert('Unable to load this employee record.');
            return;
        }

        employeeForm.reset();

        document.getElementById('employee_id').value = employee.employee_id || '';
        document.getElementById('name').value = employee.name || '';
        document.getElementById('email').value = employee.email || '';
        document.getElementById('phone').value = employee.phone || '';
        document.getElementById('job_title').value = employee.job_title || '';
        document.getElementById('departmentInput').value = employee.department || '';
        document.getElementById('salary').value = employee.salary ?? 0;
        document.getElementById('joined_date').value = employee.joined_date || '';
        document.getElementById('employment_type').value =
            employee.employment_type || 'Full-time';
        document.getElementById('employee_status').value =
            employee.status || 'ACTIVE';
        document.getElementById('address').value = employee.address || '';
        document.getElementById('notes').value = employee.notes || '';

        document.getElementById('employeeModalTitle').textContent = 'Edit Employee Details';
        document.getElementById('saveEmployeeButton').innerHTML =
            '<i class="bi bi-check2-circle me-1"></i> Update Employee';

        bootstrap.Modal.getOrCreateInstance(employeeModalElement).show();
    }

    function viewEmployeeFromButton(button) {
        let employee;

        try {
            employee = JSON.parse(button.dataset.employee);
        } catch (error) {
            alert('Unable to load this employee record.');
            return;
        }

        const setText = (id, value) => {
            document.getElementById(id).textContent =
                value === null || value === undefined || value === ''
                    ? '—'
                    : String(value);
        };

        const initials = (name) => {
            return String(name || 'Employee')
                .trim()
                .split(/\s+/)
                .slice(0, 2)
                .map(part => part.charAt(0).toUpperCase())
                .join('');
        };

        setText('viewInitials', initials(employee.name));
        setText('viewName', employee.name);
        setText('viewJobTitle', employee.job_title);
        setText('viewEmployeeId', employee.employee_id);
        setText('viewDepartment', employee.department);
        setText('viewEmail', employee.email);
        setText('viewPhone', employee.phone);
        setText('viewEmploymentType', employee.employment_type);
        setText('viewAddress', employee.address);
        setText('viewNotes', employee.notes || 'No notes available.');

        const salary = Number(employee.salary || 0).toLocaleString('en-LK', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });

        setText('viewSalary', 'LKR ' + salary);

        let formattedDate = '—';

        if (employee.joined_date) {
            const date = new Date(employee.joined_date + 'T00:00:00');

            if (!Number.isNaN(date.getTime())) {
                formattedDate = date.toLocaleDateString('en-GB', {
                    day: '2-digit',
                    month: 'short',
                    year: 'numeric'
                });
            }
        }

        setText('viewJoinedDate', formattedDate);

        const status = String(employee.status || 'INACTIVE').toUpperCase();
        const statusElement = document.getElementById('viewStatus');

        statusElement.textContent = status;
        statusElement.className = 'status-pill';

        if (status === 'ACTIVE') {
            statusElement.classList.add('status-active');
        } else if (status === 'ON LEAVE') {
            statusElement.classList.add('status-leave');
        } else {
            statusElement.classList.add('status-inactive');
        }
    }
</script>

</body>
</html>