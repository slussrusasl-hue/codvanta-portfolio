
<?php
session_start();
date_default_timezone_set('Asia/Colombo');

/*
|--------------------------------------------------------------------------
| ProServe Admin Dashboard
|--------------------------------------------------------------------------
| Session-based demonstration dashboard.
| No database is required.
*/

function e($value): string
{
    return htmlspecialchars((string)($value ?? ''), ENT_QUOTES, 'UTF-8');
}

/*
|--------------------------------------------------------------------------
| Demo administrator authentication
|--------------------------------------------------------------------------
| This is a portfolio demonstration, not production authentication.
| Admin pages can share the same session flag.
*/

if (!isset($_SESSION['proserve_admin_logged_in'])) {
    $_SESSION['proserve_admin_logged_in'] = true;
}

$adminName = $_SESSION['proserve_admin_name'] ?? 'Administrator';

/*
|--------------------------------------------------------------------------
| Initialize demonstration data
|--------------------------------------------------------------------------
| Other admin pages can update these shared session arrays.
*/

if (!isset($_SESSION['proserve_customers'])) {
    $_SESSION['proserve_customers'] = [
        [
            'id' => 1,
            'name' => 'Daniel Fernando',
            'email' => 'daniel@example.com',
            'company' => 'DF Solutions',
            'phone' => '+94 77 123 4567',
            'status' => 'ACTIVE',
            'created_at' => date('Y-m-d H:i:s', strtotime('-12 days')),
        ],
        [
            'id' => 2,
            'name' => 'Sarah Perera',
            'email' => 'sarah@example.com',
            'company' => 'Perera Enterprises',
            'phone' => '+94 71 234 5678',
            'status' => 'ACTIVE',
            'created_at' => date('Y-m-d H:i:s', strtotime('-9 days')),
        ],
        [
            'id' => 3,
            'name' => 'Michael Silva',
            'email' => 'michael@example.com',
            'company' => 'Silva Holdings',
            'phone' => '+94 76 345 6789',
            'status' => 'ACTIVE',
            'created_at' => date('Y-m-d H:i:s', strtotime('-6 days')),
        ],
        [
            'id' => 4,
            'name' => 'Amelia Jayawardena',
            'email' => 'amelia@example.com',
            'company' => 'AJ Creative',
            'phone' => '+94 72 456 7890',
            'status' => 'ACTIVE',
            'created_at' => date('Y-m-d H:i:s', strtotime('-3 days')),
        ],
    ];
}

if (!isset($_SESSION['proserve_leads'])) {
    $_SESSION['proserve_leads'] = [
        [
            'id' => 1,
            'name' => 'Nuwan Technologies',
            'company' => 'Nuwan Technologies',
            'service' => 'Website Development',
            'value' => 2500,
            'status' => 'NEW LEAD',
            'created_at' => date('Y-m-d H:i:s', strtotime('-8 days')),
        ],
        [
            'id' => 2,
            'name' => 'GreenLife Services',
            'company' => 'GreenLife Services',
            'service' => 'Business Management System',
            'value' => 4800,
            'status' => 'CONTACTED',
            'created_at' => date('Y-m-d H:i:s', strtotime('-7 days')),
        ],
        [
            'id' => 3,
            'name' => 'Ocean View Resort',
            'company' => 'Ocean View Resort',
            'service' => 'Booking Platform',
            'value' => 6500,
            'status' => 'QUOTATION',
            'created_at' => date('Y-m-d H:i:s', strtotime('-5 days')),
        ],
        [
            'id' => 4,
            'name' => 'Prime Electricals',
            'company' => 'Prime Electricals',
            'service' => 'CRM Development',
            'value' => 3200,
            'status' => 'NEGOTIATION',
            'created_at' => date('Y-m-d H:i:s', strtotime('-3 days')),
        ],
        [
            'id' => 5,
            'name' => 'BrightPath Academy',
            'company' => 'BrightPath Academy',
            'service' => 'Website Development',
            'value' => 1800,
            'status' => 'WON',
            'created_at' => date('Y-m-d H:i:s', strtotime('-1 day')),
        ],
    ];
}

if (!isset($_SESSION['proserve_appointments'])) {
    $_SESSION['proserve_appointments'] = [
        [
            'reference' => 'PS-APT-1001',
            'full_name' => 'Daniel Fernando',
            'name' => 'Daniel Fernando',
            'email' => 'daniel@example.com',
            'service' => 'Website Development Consultation',
            'appointment_date' => date('Y-m-d', strtotime('+1 day')),
            'appointment_time' => '10:00',
            'status' => 'PENDING CONFIRMATION',
            'created_at' => date('Y-m-d H:i:s', strtotime('-2 days')),
        ],
        [
            'reference' => 'PS-APT-1002',
            'full_name' => 'Sarah Perera',
            'name' => 'Sarah Perera',
            'email' => 'sarah@example.com',
            'service' => 'CRM Consultation',
            'appointment_date' => date('Y-m-d', strtotime('+2 days')),
            'appointment_time' => '14:00',
            'status' => 'CONFIRMED',
            'created_at' => date('Y-m-d H:i:s', strtotime('-1 day')),
        ],
        [
            'reference' => 'PS-APT-1003',
            'full_name' => 'Michael Silva',
            'name' => 'Michael Silva',
            'email' => 'michael@example.com',
            'service' => 'IT Support Consultation',
            'appointment_date' => date('Y-m-d', strtotime('+3 days')),
            'appointment_time' => '11:30',
            'status' => 'PENDING',
            'created_at' => date('Y-m-d H:i:s'),
        ],
    ];
}

if (!isset($_SESSION['proserve_quotes'])) {
    $_SESSION['proserve_quotes'] = [
        [
            'reference' => 'PS-QT-2026-014',
            'quote_reference' => 'PS-QT-2026-014',
            'name' => 'Daniel Fernando',
            'email' => 'daniel@example.com',
            'service' => 'Website Development',
            'amount' => 850,
            'status' => 'QUOTE SENT',
            'created_at' => date('Y-m-d H:i:s', strtotime('-5 days')),
        ],
        [
            'reference' => 'PS-QT-2026-015',
            'quote_reference' => 'PS-QT-2026-015',
            'name' => 'Sarah Perera',
            'email' => 'sarah@example.com',
            'service' => 'Business Management System',
            'amount' => 1200,
            'status' => 'UNDER REVIEW',
            'created_at' => date('Y-m-d H:i:s', strtotime('-3 days')),
        ],
        [
            'reference' => 'PS-QT-2026-016',
            'quote_reference' => 'PS-QT-2026-016',
            'name' => 'Michael Silva',
            'email' => 'michael@example.com',
            'service' => 'Website Maintenance',
            'amount' => 150,
            'status' => 'PENDING',
            'created_at' => date('Y-m-d H:i:s', strtotime('-1 day')),
        ],
    ];
}

if (!isset($_SESSION['proserve_invoices'])) {
    $_SESSION['proserve_invoices'] = [
        [
            'invoice_number' => 'PS-INV-2026-001',
            'reference' => 'PS-INV-2026-001',
            'email' => 'daniel@example.com',
            'service' => 'Website Development',
            'amount' => 850,
            'currency' => 'USD',
            'status' => 'PAID',
            'issue_date' => date('Y-m-d', strtotime('-15 days')),
            'due_date' => date('Y-m-d', strtotime('-5 days')),
            'created_at' => date('Y-m-d H:i:s', strtotime('-15 days')),
        ],
        [
            'invoice_number' => 'PS-INV-2026-002',
            'reference' => 'PS-INV-2026-002',
            'email' => 'sarah@example.com',
            'service' => 'Business Management System',
            'amount' => 1200,
            'currency' => 'USD',
            'status' => 'PENDING',
            'issue_date' => date('Y-m-d', strtotime('-4 days')),
            'due_date' => date('Y-m-d', strtotime('+10 days')),
            'created_at' => date('Y-m-d H:i:s', strtotime('-4 days')),
        ],
        [
            'invoice_number' => 'PS-INV-2026-003',
            'reference' => 'PS-INV-2026-003',
            'email' => 'michael@example.com',
            'service' => 'Website Maintenance',
            'amount' => 150,
            'currency' => 'USD',
            'status' => 'PAID',
            'issue_date' => date('Y-m-d', strtotime('-2 days')),
            'due_date' => date('Y-m-d', strtotime('+12 days')),
            'created_at' => date('Y-m-d H:i:s', strtotime('-2 days')),
        ],
    ];
}

if (!isset($_SESSION['proserve_enquiries'])) {
    $_SESSION['proserve_enquiries'] = [
        [
            'reference' => 'PS-ENQ-1001',
            'name' => 'Amelia Jayawardena',
            'email' => 'amelia@example.com',
            'subject' => 'E-commerce website inquiry',
            'message' => 'Interested in developing an online store.',
            'status' => 'NEW LEAD',
            'created_at' => date('Y-m-d H:i:s', strtotime('-1 day')),
        ],
        [
            'reference' => 'PS-ENQ-1002',
            'name' => 'Kasun Peris',
            'email' => 'kasun@example.com',
            'subject' => 'CRM software consultation',
            'message' => 'Would like to discuss a CRM solution.',
            'status' => 'CONTACTED',
            'created_at' => date('Y-m-d H:i:s', strtotime('-2 days')),
        ],
    ];
}

if (!isset($_SESSION['proserve_services'])) {
    $_SESSION['proserve_services'] = [
        ['id' => 1, 'name' => 'Website Development', 'status' => 'ACTIVE'],
        ['id' => 2, 'name' => 'Business Management Systems', 'status' => 'ACTIVE'],
        ['id' => 3, 'name' => 'E-commerce Development', 'status' => 'ACTIVE'],
        ['id' => 4, 'name' => 'Database Development', 'status' => 'ACTIVE'],
        ['id' => 5, 'name' => 'API Integration', 'status' => 'ACTIVE'],
        ['id' => 6, 'name' => 'Website Maintenance', 'status' => 'ACTIVE'],
    ];
}

if (!isset($_SESSION['proserve_employees'])) {
    $_SESSION['proserve_employees'] = [
        [
            'id' => 1,
            'name' => 'Alex Perera',
            'role' => 'Web Developer',
            'status' => 'ACTIVE',
        ],
        [
            'id' => 2,
            'name' => 'Emily Silva',
            'role' => 'Project Manager',
            'status' => 'ACTIVE',
        ],
        [
            'id' => 3,
            'name' => 'Ryan Fernando',
            'role' => 'Software Engineer',
            'status' => 'ACTIVE',
        ],
    ];
}

/*
|--------------------------------------------------------------------------
| Normalize dashboard datasets
|--------------------------------------------------------------------------
*/

$customers = array_values(array_filter(
    $_SESSION['proserve_customers'],
    'is_array'
));

$leads = array_values(array_filter(
    $_SESSION['proserve_leads'],
    'is_array'
));

$appointments = array_values(array_filter(
    $_SESSION['proserve_appointments'],
    'is_array'
));

$quotes = array_values(array_filter(
    $_SESSION['proserve_quotes'],
    'is_array'
));

$invoices = array_values(array_filter(
    $_SESSION['proserve_invoices'],
    'is_array'
));

$enquiries = array_values(array_filter(
    $_SESSION['proserve_enquiries'],
    'is_array'
));

$services = array_values(array_filter(
    $_SESSION['proserve_services'],
    'is_array'
));

$employees = array_values(array_filter(
    $_SESSION['proserve_employees'],
    'is_array'
));

/*
|--------------------------------------------------------------------------
| KPI calculations
|--------------------------------------------------------------------------
*/

$totalCustomers = count($customers);
$totalLeads = count($leads);
$totalAppointments = count($appointments);
$totalQuotes = count($quotes);
$totalInvoices = count($invoices);
$totalEmployees = count($employees);
$totalServices = count($services);

$activeCustomers = 0;
foreach ($customers as $customer) {
    if (strtoupper($customer['status'] ?? 'ACTIVE') === 'ACTIVE') {
        $activeCustomers++;
    }
}

$pendingAppointments = 0;
$confirmedAppointments = 0;
$completedAppointments = 0;

foreach ($appointments as $appointment) {
    $status = strtoupper(trim((string)($appointment['status'] ?? 'PENDING')));

    if (in_array($status, ['PENDING', 'PENDING CONFIRMATION', 'REQUESTED'], true)) {
        $pendingAppointments++;
    } elseif (in_array($status, ['CONFIRMED', 'APPROVED', 'SCHEDULED'], true)) {
        $confirmedAppointments++;
    } elseif (in_array($status, ['COMPLETED', 'FINISHED'], true)) {
        $completedAppointments++;
    }
}

$leadStages = [
    'NEW LEAD' => 0,
    'CONTACTED' => 0,
    'QUOTATION' => 0,
    'NEGOTIATION' => 0,
    'WON' => 0,
];

foreach ($leads as $lead) {
    $status = strtoupper(trim((string)($lead['status'] ?? 'NEW LEAD')));

    if (isset($leadStages[$status])) {
        $leadStages[$status]++;
    } elseif (in_array($status, ['QUOTE', 'QUOTATION SENT'], true)) {
        $leadStages['QUOTATION']++;
    } elseif (in_array($status, ['CLOSED WON', 'CONVERTED'], true)) {
        $leadStages['WON']++;
    }
}

$paidRevenue = 0.0;
$pendingInvoiceValue = 0.0;
$paidInvoiceCount = 0;
$unpaidInvoiceCount = 0;

foreach ($invoices as $invoice) {
    $amount = (float)($invoice['amount'] ?? $invoice['total'] ?? 0);
    $tax = (float)($invoice['tax'] ?? 0);
    $discount = (float)($invoice['discount'] ?? 0);
    $total = max(0, $amount + $tax - $discount);

    $status = strtoupper(trim((string)($invoice['status'] ?? 'PENDING')));

    if (in_array($status, ['PAID', 'COMPLETED'], true)) {
        $paidRevenue += $total;
        $paidInvoiceCount++;
    } elseif (!in_array($status, ['CANCELLED', 'VOID'], true)) {
        $pendingInvoiceValue += $total;
        $unpaidInvoiceCount++;
    }
}

$conversionRate = $totalLeads > 0
    ? round(($leadStages['WON'] / $totalLeads) * 100, 1)
    : 0;

$activeServices = 0;
foreach ($services as $service) {
    if (strtoupper($service['status'] ?? 'ACTIVE') === 'ACTIVE') {
        $activeServices++;
    }
}

$newEnquiries = 0;
foreach ($enquiries as $enquiry) {
    $status = strtoupper(trim((string)($enquiry['status'] ?? 'NEW LEAD')));

    if (in_array($status, ['NEW', 'NEW LEAD', 'PENDING'], true)) {
        $newEnquiries++;
    }
}

/*
|--------------------------------------------------------------------------
| Recent activity
|--------------------------------------------------------------------------
*/

$activity = [];

foreach ($appointments as $appointment) {
    $activity[] = [
        'type' => 'Appointment',
        'icon' => 'calendar-check',
        'color' => 'blue',
        'title' => 'Appointment request',
        'description' => ($appointment['full_name'] ?? $appointment['name'] ?? 'Customer')
            . ' — ' . ($appointment['service'] ?? 'Service consultation'),
        'date' => $appointment['created_at'] ?? '',
        'status' => $appointment['status'] ?? 'PENDING',
        'link' => 'appointments.php',
    ];
}

foreach ($quotes as $quote) {
    $activity[] = [
        'type' => 'Quotation',
        'icon' => 'file-earmark-text',
        'color' => 'purple',
        'title' => 'Quotation activity',
        'description' => ($quote['name'] ?? $quote['customer_name'] ?? $quote['email'] ?? 'Customer')
            . ' — ' . ($quote['service'] ?? $quote['service_name'] ?? 'Service'),
        'date' => $quote['created_at'] ?? $quote['date'] ?? '',
        'status' => $quote['status'] ?? 'PENDING',
        'link' => 'quotations.php',
    ];
}

foreach ($enquiries as $enquiry) {
    $activity[] = [
        'type' => 'Enquiry',
        'icon' => 'envelope',
        'color' => 'orange',
        'title' => 'Customer enquiry',
        'description' => ($enquiry['name'] ?? 'Customer')
            . ' — ' . ($enquiry['subject'] ?? 'General enquiry'),
        'date' => $enquiry['created_at'] ?? '',
        'status' => $enquiry['status'] ?? 'NEW LEAD',
        'link' => 'enquiries.php',
    ];
}

usort($activity, function ($a, $b) {
    $dateA = strtotime($a['date'] ?: '2000-01-01') ?: 0;
    $dateB = strtotime($b['date'] ?: '2000-01-01') ?: 0;

    return $dateB <=> $dateA;
});

$recentActivity = array_slice($activity, 0, 6);

/*
|--------------------------------------------------------------------------
| Revenue chart data
|--------------------------------------------------------------------------
| Demo chart: paid invoice revenue grouped into the last six months.
|--------------------------------------------------------------------------
*/

$revenueByMonth = [];

for ($i = 5; $i >= 0; $i--) {
    $timestamp = strtotime(date('Y-m-01') . " -$i months");
    $key = date('Y-m', $timestamp);

    $revenueByMonth[$key] = [
        'label' => date('M', $timestamp),
        'revenue' => 0,
    ];
}

foreach ($invoices as $invoice) {
    $status = strtoupper(trim((string)($invoice['status'] ?? 'PENDING')));

    if (!in_array($status, ['PAID', 'COMPLETED'], true)) {
        continue;
    }

    $date = $invoice['paid_date']
        ?? $invoice['issue_date']
        ?? $invoice['created_at']
        ?? '';

    $timestamp = $date !== '' ? strtotime($date) : false;

    if ($timestamp === false) {
        continue;
    }

    $key = date('Y-m', $timestamp);

    if (isset($revenueByMonth[$key])) {
        $amount = (float)($invoice['amount'] ?? $invoice['total'] ?? 0);
        $amount += (float)($invoice['tax'] ?? 0);
        $amount -= (float)($invoice['discount'] ?? 0);

        $revenueByMonth[$key]['revenue'] += max(0, $amount);
    }
}

$chartLabels = array_column(array_values($revenueByMonth), 'label');
$chartRevenue = array_map(
    fn($item) => round((float)$item['revenue'], 2),
    array_values($revenueByMonth)
);

$maxRevenue = max($chartRevenue ?: [0]);
$chartMax = max(100, ceil($maxRevenue / 100) * 100);

function dashboardStatusClass(string $status): string
{
    return match (strtoupper(trim($status))) {
        'PAID', 'COMPLETED', 'WON', 'ACTIVE', 'CONFIRMED', 'APPROVED' =>
            'badge-green',

        'OVERDUE', 'REJECTED', 'CANCELLED' =>
            'badge-red',

        'NEW', 'NEW LEAD', 'PENDING', 'PENDING CONFIRMATION',
        'UNDER REVIEW', 'REQUESTED' =>
            'badge-orange',

        'CONTACTED', 'QUOTATION', 'QUOTE SENT', 'NEGOTIATION',
        'SCHEDULED', 'PROCESSING' =>
            'badge-blue',

        default => 'badge-gray',
    };
}

function dashboardDate($value): string
{
    if (!$value || strtotime((string)$value) === false) {
        return '—';
    }

    return date('d M Y', strtotime((string)$value));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Admin Dashboard | ProServe</title>

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

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>

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

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: var(--background);
            color: #26364a;
            font-family: 'Inter', 'Trebuchet MS', sans-serif;
            font-size: 13px;
        }

        a {
            text-decoration: none;
        }

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
            color: #fff;
            font-size: 20px;
        }

        .brand span {
            color: var(--primary);
        }

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

        .main-area {
            min-height: 100vh;
            margin-left: var(--sidebar-width);
        }

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
            background: rgba(255, 255, 255, .96);
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

        .welcome-title {
            color: var(--navy);
            font-size: 25px;
            font-weight: 800;
            letter-spacing: -.8px;
        }

        .muted {
            color: var(--muted);
        }

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
            position: relative;
            height: 100%;
            padding: 20px;
            overflow: hidden;
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 14px;
            box-shadow: 0 4px 18px rgba(16, 35, 63, .025);
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

        .blue {
            background: #eaf1ff;
            color: #2563eb;
        }

        .green {
            background: #e6f8ef;
            color: #16985a;
        }

        .orange {
            background: #fff2df;
            color: #ca7a15;
        }

        .purple {
            background: #f1eaff;
            color: #805ad5;
        }

        .red {
            background: #ffe8e8;
            color: #dc3545;
        }

        .metric-label {
            margin-top: 18px;
            color: var(--muted);
            font-size: 11px;
            font-weight: 600;
        }

        .metric-value {
            margin-top: 5px;
            color: var(--navy);
            font-size: 27px;
            font-weight: 800;
            letter-spacing: -1px;
        }

        .metric-note {
            margin-top: 8px;
            color: var(--muted);
            font-size: 10px;
        }

        .metric-note strong {
            color: #16985a;
        }

        .panel {
            height: 100%;
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 14px;
            box-shadow: 0 4px 18px rgba(16, 35, 63, .025);
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

        .panel-body {
            padding: 20px 21px;
        }

        .panel-link {
            color: var(--primary);
            font-size: 11px;
            font-weight: 700;
            white-space: nowrap;
        }

        .panel-link:hover {
            color: var(--primary-dark);
        }

        .chart-container {
            position: relative;
            height: 265px;
        }

        .pipeline-stage {
            margin-bottom: 18px;
        }

        .pipeline-stage:last-child {
            margin-bottom: 0;
        }

        .pipeline-heading {
            display: flex;
            justify-content: space-between;
            gap: 10px;
            margin-bottom: 8px;
            color: #526176;
            font-size: 11px;
            font-weight: 600;
        }

        .pipeline-count {
            color: var(--navy);
            font-weight: 800;
        }

        .progress {
            height: 7px;
            background: #edf1f7;
            border-radius: 20px;
        }

        .progress-bar {
            border-radius: 20px;
        }

        .table {
            margin-bottom: 0;
            vertical-align: middle;
        }

        .table thead th {
            padding: 13px 16px;
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
            padding: 14px 16px;
            border-bottom: 1px solid #edf1f7;
            font-size: 11px;
        }

        .table tbody tr:last-child td {
            border-bottom: 0;
        }

        .person-name {
            color: var(--navy);
            font-weight: 700;
        }

        .person-subtitle {
            margin-top: 4px;
            color: var(--muted);
            font-size: 10px;
        }

        .badge-status {
            display: inline-block;
            padding: 6px 9px;
            border-radius: 30px;
            font-size: 9px;
            font-weight: 800;
            white-space: nowrap;
        }

        .badge-green {
            background: #e5f8ed;
            color: #168044;
        }

        .badge-orange {
            background: #fff3dc;
            color: #a16207;
        }

        .badge-red {
            background: #ffe7e7;
            color: #c62828;
        }

        .badge-blue {
            background: #e8f0ff;
            color: #2458c7;
        }

        .badge-gray {
            background: #edf1f5;
            color: #5f6b7a;
        }

        .activity-item {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 13px 0;
            border-bottom: 1px solid #edf1f7;
        }

        .activity-item:first-child {
            padding-top: 0;
        }

        .activity-item:last-child {
            padding-bottom: 0;
            border-bottom: 0;
        }

        .activity-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            width: 35px;
            height: 35px;
            border-radius: 10px;
            font-size: 15px;
        }

        .activity-title {
            color: var(--navy);
            font-size: 11px;
            font-weight: 800;
        }

        .activity-description {
            margin-top: 4px;
            color: #65748a;
            font-size: 10px;
            line-height: 1.5;
        }

        .activity-date {
            margin-top: 6px;
            color: #97a3b3;
            font-size: 9px;
        }

        .quick-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 13px;
            border: 1px solid var(--border);
            border-radius: 11px;
            color: #4c5c72;
            transition: .2s;
        }

        .quick-link:hover {
            border-color: #bfd3ff;
            background: #f7faff;
            color: var(--primary);
            transform: translateY(-1px);
        }

        .quick-link-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            width: 36px;
            height: 36px;
            border-radius: 10px;
            background: #edf3ff;
            color: var(--primary);
            font-size: 17px;
        }

        .quick-link-title {
            color: var(--navy);
            font-size: 11px;
            font-weight: 800;
        }

        .quick-link-description {
            margin-top: 4px;
            color: var(--muted);
            font-size: 9px;
        }

        .quick-link-arrow {
            margin-left: auto;
            color: #9aa8ba;
        }

        .stat-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 12px 0;
            border-bottom: 1px solid #edf1f7;
        }

        .stat-row:last-child {
            border-bottom: 0;
        }

        .stat-label {
            color: #66758a;
            font-size: 11px;
        }

        .stat-value {
            color: var(--navy);
            font-size: 12px;
            font-weight: 800;
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
            background: #fff;
            color: var(--navy);
        }

        .sidebar-backdrop {
            display: none;
        }

        @media (max-width: 1199px) {
            :root {
                --sidebar-width: 230px;
            }

            .page-content {
                padding: 23px 22px 30px;
            }

            .topbar {
                padding: 13px 22px;
            }

            .metric-card {
                padding: 17px;
            }

            .metric-value {
                font-size: 24px;
            }
        }

        @media (max-width: 991px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.open {
                transform: translateX(0);
            }

            .main-area {
                margin-left: 0;
            }

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
                background: rgba(16, 35, 63, .35);
            }
        }

        @media (max-width: 575px) {
            .topbar {
                padding: 12px 15px;
            }

            .page-content {
                padding: 20px 14px 28px;
            }

            .welcome-title {
                font-size: 22px;
            }

            .topbar-date {
                display: none;
            }

            .metric-card {
                padding: 15px;
            }

            .metric-icon {
                width: 36px;
                height: 36px;
                font-size: 17px;
            }

            .metric-value {
                font-size: 22px;
            }

            .panel-header,
            .panel-body {
                padding-left: 15px;
                padding-right: 15px;
            }

            .footer {
                padding: 18px 15px;
            }
        }
    </style>
</head>

<body>

<div class="sidebar-backdrop" id="sidebarBackdrop"></div>

<aside class="sidebar" id="sidebar">

    <a href="index.php" class="brand">
        <span class="brand-icon">
            <i class="bi bi-grid-1x2-fill"></i>
        </span>

        <span style="color:#10233f">Pro<span>Serve</span></span>
    </a>

    <div class="sidebar-label">Main Menu</div>

    <a href="index.php" class="sidebar-link active">
        <i class="bi bi-speedometer2"></i>
        Dashboard
    </a>

    <a href="customers.php" class="sidebar-link">
        <i class="bi bi-people"></i>
        Customers
        <span class="ms-auto badge rounded-pill text-bg-light">
            <?= $totalCustomers ?>
        </span>
    </a>

    <a href="leads.php" class="sidebar-link">
        <i class="bi bi-funnel"></i>
        Lead Management
    </a>

    <a href="appointments.php" class="sidebar-link">
        <i class="bi bi-calendar-check"></i>
        Appointments

        <?php if ($pendingAppointments > 0): ?>
            <span class="ms-auto badge rounded-pill text-bg-warning">
                <?= $pendingAppointments ?>
            </span>
        <?php endif; ?>
    </a>

    <div class="sidebar-label">Business Management</div>

    <a href="services.php" class="sidebar-link">
        <i class="bi bi-grid"></i>
        Services
    </a>

    <a href="quotations.php" class="sidebar-link">
        <i class="bi bi-file-earmark-text"></i>
        Quotations
    </a>

    <a href="invoices.php" class="sidebar-link">
        <i class="bi bi-receipt"></i>
        Invoices
    </a>

    <a href="employees.php" class="sidebar-link">
        <i class="bi bi-person-workspace"></i>
        Employees
    </a>

    <div class="sidebar-label">Analytics & Support</div>

    <a href="reports.php" class="sidebar-link">
        <i class="bi bi-bar-chart-line"></i>
        Reports
    </a>

    <a href="enquiries.php" class="sidebar-link">
        <i class="bi bi-envelope"></i>
        Contact Enquiries

        <?php if ($newEnquiries > 0): ?>
            <span class="ms-auto badge rounded-pill text-bg-primary">
                <?= $newEnquiries ?>
            </span>
        <?php endif; ?>
    </a>

    <a href="settings.php" class="sidebar-link">
        <i class="bi bi-gear"></i>
        Settings
    </a>

    <div class="sidebar-bottom">
        <a href="../index.php" class="sidebar-link">
            <i class="bi bi-globe2"></i>
            View Customer Website
        </a>

        <a href="logout.php" class="sidebar-link">
            <i class="bi bi-box-arrow-right"></i>
            Logout
        </a>

        <div class="admin-profile">
            <div class="avatar">
                <?= e(strtoupper(substr($adminName, 0, 1))) ?>
            </div>

            <div>
                <div class="admin-name"><?= e($adminName) ?></div>
                <div class="admin-role">System Administrator</div>
            </div>

            <i class="bi bi-three-dots ms-auto muted"></i>
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
                <div class="topbar-title">Administrator Dashboard</div>
                <div class="topbar-subtitle">
                    Monitor your business performance and daily activities.
                </div>
            </div>
        </div>

        <div class="d-flex align-items-center gap-3">
            <div class="topbar-date">
                <i class="bi bi-calendar3 text-primary"></i>
                <?= date('D, d M Y') ?>
            </div>

            <div class="avatar">
                <?= e(strtoupper(substr($adminName, 0, 1))) ?>
            </div>
        </div>
    </header>

    <main class="page-content">

        <section class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
            <div>
                <div class="welcome-title">
                    Welcome back, <?= e($adminName) ?>!
                </div>

                <p class="muted mb-0 mt-2">
                    Here's what's happening with your business today.
                </p>
            </div>

            <div class="d-flex flex-wrap gap-2">
                <a href="reports.php" class="btn btn-outline-primary btn-sm px-3 py-2">
                    <i class="bi bi-bar-chart-line me-1"></i>
                    View Reports
                </a>

                <a href="appointments.php" class="btn btn-primary btn-sm px-3 py-2">
                    <i class="bi bi-calendar-plus me-1"></i>
                    Manage Appointments
                </a>
            </div>
        </section>

        <!-- KPI Cards -->
        <section class="row g-3 mb-4">

            <div class="col-6 col-xl-3">
                <div class="metric-card">
                    <div class="metric-top">
                        <div class="metric-icon blue">
                            <i class="bi bi-people"></i>
                        </div>

                        <span class="badge-status badge-green">
                            <?= $activeCustomers ?> Active
                        </span>
                    </div>

                    <div class="metric-label">Total Customers</div>
                    <div class="metric-value"><?= number_format($totalCustomers) ?></div>

                    <div class="metric-note">
                        Registered customer records
                    </div>
                </div>
            </div>

            <div class="col-6 col-xl-3">
                <div class="metric-card">
                    <div class="metric-top">
                        <div class="metric-icon purple">
                            <i class="bi bi-funnel"></i>
                        </div>

                        <span class="badge-status badge-blue">
                            <?= e((string)$conversionRate) ?>% Conversion
                        </span>
                    </div>

                    <div class="metric-label">Sales Leads</div>
                    <div class="metric-value"><?= number_format($totalLeads) ?></div>

                    <div class="metric-note">
                        <?= $leadStages['WON'] ?> converted lead(s)
                    </div>
                </div>
            </div>

            <div class="col-6 col-xl-3">
                <div class="metric-card">
                    <div class="metric-top">
                        <div class="metric-icon orange">
                            <i class="bi bi-calendar-check"></i>
                        </div>

                        <span class="badge-status badge-orange">
                            <?= $pendingAppointments ?> Pending
                        </span>
                    </div>

                    <div class="metric-label">Appointments</div>
                    <div class="metric-value"><?= number_format($totalAppointments) ?></div>

                    <div class="metric-note">
                        <?= $confirmedAppointments ?> confirmed appointment(s)
                    </div>
                </div>
            </div>

            <div class="col-6 col-xl-3">
                <div class="metric-card">
                    <div class="metric-top">
                        <div class="metric-icon green">
                            <i class="bi bi-currency-dollar"></i>
                        </div>

                        <span class="badge-status badge-green">
                            <?= $paidInvoiceCount ?> Paid
                        </span>
                    </div>

                    <div class="metric-label">Recorded Revenue</div>
                    <div class="metric-value" style="font-size:24px">
                        $<?= number_format($paidRevenue, 2) ?>
                    </div>

                    <div class="metric-note">
                        Paid invoices in the demo records
                    </div>
                </div>
            </div>

        </section>

        <!-- Revenue Chart and Sales Pipeline -->
        <section class="row g-3 mb-4">

            <div class="col-xl-8">
                <div class="panel">
                    <div class="panel-header">
                        <div>
                            <h2 class="panel-title">Revenue Overview</h2>
                            <div class="panel-subtitle">
                                Recorded paid invoice revenue over the last six months
                            </div>
                        </div>

                        <span class="badge-status badge-blue">
                            <i class="bi bi-graph-up-arrow me-1"></i>
                            Revenue
                        </span>
                    </div>

                    <div class="panel-body">
                        <div class="chart-container">
                            <canvas id="revenueChart"></canvas>
                        </div>

                        <div class="d-flex flex-wrap gap-4 mt-3">
                            <div>
                                <div class="small muted mb-1">Paid Revenue</div>
                                <div class="fw-bold fs-5 text-dark">
                                    $<?= number_format($paidRevenue, 2) ?>
                                </div>
                            </div>

                            <div>
                                <div class="small muted mb-1">Outstanding Invoice Value</div>
                                <div class="fw-bold fs-5 text-dark">
                                    $<?= number_format($pendingInvoiceValue, 2) ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-4">
                <div class="panel">
                    <div class="panel-header">
                        <div>
                            <h2 class="panel-title">Sales Pipeline</h2>
                            <div class="panel-subtitle">
                                Lead distribution by stage
                            </div>
                        </div>

                        <a href="leads.php" class="panel-link">
                            Manage <i class="bi bi-arrow-up-right"></i>
                        </a>
                    </div>

                    <div class="panel-body">
                        <?php
                        $stageColors = [
                            'NEW LEAD' => '#94a3b8',
                            'CONTACTED' => '#3b82f6',
                            'QUOTATION' => '#8b5cf6',
                            'NEGOTIATION' => '#f59e0b',
                            'WON' => '#16a34a',
                        ];

                        foreach ($leadStages as $stage => $count):
                            $percentage = $totalLeads > 0
                                ? ($count / $totalLeads) * 100
                                : 0;
                        ?>
                            <div class="pipeline-stage">
                                <div class="pipeline-heading">
                                    <span><?= e(ucwords(strtolower($stage))) ?></span>

                                    <span class="pipeline-count">
                                        <?= $count ?>
                                    </span>
                                </div>

                                <div class="progress">
                                    <div
                                        class="progress-bar"
                                        role="progressbar"
                                        style="width: <?= e((string)$percentage) ?>%; background: <?= e($stageColors[$stage]) ?>"
                                        aria-valuenow="<?= e((string)$percentage) ?>"
                                        aria-valuemin="0"
                                        aria-valuemax="100"
                                    ></div>
                                </div>
                            </div>
                        <?php endforeach; ?>

                        <div class="mt-4 pt-3 border-top">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="muted">Overall conversion rate</span>

                                <strong class="text-success">
                                    <?= e((string)$conversionRate) ?>%
                                </strong>
                            </div>

                            <div class="small muted mt-2">
                                Based on leads currently marked as WON.
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </section>

        <!-- Appointments and Recent Activity -->
        <section class="row g-3 mb-4">

            <div class="col-xl-7">
                <div class="panel">
                    <div class="panel-header">
                        <div>
                            <h2 class="panel-title">Recent Appointments</h2>
                            <div class="panel-subtitle">
                                Latest appointment requests and schedules
                            </div>
                        </div>

                        <a href="appointments.php" class="panel-link">
                            View All <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>

                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>Customer</th>
                                <th>Service</th>
                                <th>Date</th>
                                <th>Status</th>
                            </tr>
                            </thead>

                            <tbody>
                            <?php
                            $recentAppointments = $appointments;

                            usort($recentAppointments, function ($a, $b) {
                                $dateA = strtotime($a['created_at'] ?? '') ?: 0;
                                $dateB = strtotime($b['created_at'] ?? '') ?: 0;

                                return $dateB <=> $dateA;
                            });

                            $recentAppointments = array_slice($recentAppointments, 0, 5);
                            ?>

                            <?php if (empty($recentAppointments)): ?>
                                <tr>
                                    <td colspan="4" class="text-center py-4 muted">
                                        No appointments recorded yet.
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($recentAppointments as $appointment): ?>
                                    <?php
                                    $appointmentName = $appointment['full_name']
                                        ?? $appointment['name']
                                        ?? 'Customer';

                                    $appointmentStatus = strtoupper(
                                        $appointment['status'] ?? 'PENDING'
                                    );
                                    ?>

                                    <tr>
                                        <td>
                                            <div class="person-name">
                                                <?= e($appointmentName) ?>
                                            </div>

                                            <div class="person-subtitle">
                                                <?= e($appointment['email'] ?? '') ?>
                                            </div>
                                        </td>

                                        <td>
                                            <?= e($appointment['service'] ?? 'Consultation') ?>
                                        </td>

                                        <td class="text-nowrap">
                                            <?= e(dashboardDate(
                                                $appointment['appointment_date']
                                                ?? $appointment['date']
                                                ?? ''
                                            )) ?>
                                        </td>

                                        <td>
                                            <span class="badge-status <?= e(dashboardStatusClass($appointmentStatus)) ?>">
                                                <?= e($appointmentStatus) ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-xl-5">
                <div class="panel">
                    <div class="panel-header">
                        <div>
                            <h2 class="panel-title">Recent Activity</h2>
                            <div class="panel-subtitle">
                                Latest activity across the system
                            </div>
                        </div>

                        <span class="badge-status badge-blue">
                            <?= count($activity) ?> Records
                        </span>
                    </div>

                    <div class="panel-body">
                        <?php if (empty($recentActivity)): ?>
                            <div class="text-center muted py-4">
                                No recent activity.
                            </div>
                        <?php else: ?>
                            <?php foreach ($recentActivity as $item): ?>
                                <div class="activity-item">
                                    <div class="activity-icon <?= e($item['color']) ?>">
                                        <i class="bi bi-<?= e($item['icon']) ?>"></i>
                                    </div>

                                    <div class="flex-grow-1">
                                        <div class="activity-title">
                                            <?= e($item['title']) ?>
                                        </div>

                                        <div class="activity-description">
                                            <?= e($item['description']) ?>
                                        </div>

                                        <div class="activity-date">
                                            <i class="bi bi-clock me-1"></i>
                                            <?= e(
                                                $item['date'] && strtotime($item['date'])
                                                    ? date('d M Y, h:i A', strtotime($item['date']))
                                                    : 'Date not available'
                                            ) ?>
                                        </div>
                                    </div>

                                    <a
                                        href="<?= e($item['link']) ?>"
                                        class="panel-link"
                                        aria-label="View activity"
                                    >
                                        <i class="bi bi-arrow-up-right"></i>
                                    </a>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

        </section>

        <!-- Quick Actions and Business Summary -->
        <section class="row g-3">

            <div class="col-xl-7">
                <div class="panel">
                    <div class="panel-header">
                        <div>
                            <h2 class="panel-title">Quick Actions</h2>
                            <div class="panel-subtitle">
                                Jump directly to common administration tasks
                            </div>
                        </div>
                    </div>

                    <div class="panel-body">
                        <div class="row g-3">

                            <div class="col-md-6">
                                <a href="customers.php" class="quick-link">
                                    <span class="quick-link-icon">
                                        <i class="bi bi-person-plus"></i>
                                    </span>

                                    <span>
                                        <span class="quick-link-title d-block">
                                            Manage Customers
                                        </span>

                                        <span class="quick-link-description d-block">
                                            View and update customer records
                                        </span>
                                    </span>

                                    <i class="bi bi-chevron-right quick-link-arrow"></i>
                                </a>
                            </div>

                            <div class="col-md-6">
                                <a href="leads.php" class="quick-link">
                                    <span class="quick-link-icon">
                                        <i class="bi bi-funnel"></i>
                                    </span>

                                    <span>
                                        <span class="quick-link-title d-block">
                                            Manage Sales Leads
                                        </span>

                                        <span class="quick-link-description d-block">
                                            Track prospects through the pipeline
                                        </span>
                                    </span>

                                    <i class="bi bi-chevron-right quick-link-arrow"></i>
                                </a>
                            </div>

                            <div class="col-md-6">
                                <a href="quotations.php" class="quick-link">
                                    <span class="quick-link-icon">
                                        <i class="bi bi-file-earmark-text"></i>
                                    </span>

                                    <span>
                                        <span class="quick-link-title d-block">
                                            Prepare Quotations
                                        </span>

                                        <span class="quick-link-description d-block">
                                            Review requests and issue quotations
                                        </span>
                                    </span>

                                    <i class="bi bi-chevron-right quick-link-arrow"></i>
                                </a>
                            </div>

                            <div class="col-md-6">
                                <a href="invoices.php" class="quick-link">
                                    <span class="quick-link-icon">
                                        <i class="bi bi-receipt"></i>
                                    </span>

                                    <span>
                                        <span class="quick-link-title d-block">
                                            Manage Invoices
                                        </span>

                                        <span class="quick-link-description d-block">
                                            Issue and track customer invoices
                                        </span>
                                    </span>

                                    <i class="bi bi-chevron-right quick-link-arrow"></i>
                                </a>
                            </div>

                            <div class="col-md-6">
                                <a href="employees.php" class="quick-link">
                                    <span class="quick-link-icon">
                                        <i class="bi bi-person-workspace"></i>
                                    </span>

                                    <span>
                                        <span class="quick-link-title d-block">
                                            Manage Employees
                                        </span>

                                        <span class="quick-link-description d-block">
                                            Maintain the team and assignments
                                        </span>
                                    </span>

                                    <i class="bi bi-chevron-right quick-link-arrow"></i>
                                </a>
                            </div>

                            <div class="col-md-6">
                                <a href="reports.php" class="quick-link">
                                    <span class="quick-link-icon">
                                        <i class="bi bi-graph-up"></i>
                                    </span>

                                    <span>
                                        <span class="quick-link-title d-block">
                                            Generate Reports
                                        </span>

                                        <span class="quick-link-description d-block">
                                            Review sales and business performance
                                        </span>
                                    </span>

                                    <i class="bi bi-chevron-right quick-link-arrow"></i>
                                </a>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-5">
                <div class="panel">
                    <div class="panel-header">
                        <div>
                            <h2 class="panel-title">Business Summary</h2>
                            <div class="panel-subtitle">
                                Overview of key operational records
                            </div>
                        </div>

                        <i class="bi bi-pie-chart text-primary fs-5"></i>
                    </div>

                    <div class="panel-body">

                        <div class="stat-row">
                            <span class="stat-label">
                                <i class="bi bi-file-earmark-text me-2 text-primary"></i>
                                Total Quotations
                            </span>

                            <span class="stat-value"><?= $totalQuotes ?></span>
                        </div>

                        <div class="stat-row">
                            <span class="stat-label">
                                <i class="bi bi-receipt me-2 text-success"></i>
                                Total Invoices
                            </span>

                            <span class="stat-value"><?= $totalInvoices ?></span>
                        </div>

                        <div class="stat-row">
                            <span class="stat-label">
                                <i class="bi bi-cash-stack me-2 text-warning"></i>
                                Outstanding Invoice Value
                            </span>

                            <span class="stat-value">
                                $<?= number_format($pendingInvoiceValue, 2) ?>
                            </span>
                        </div>

                        <div class="stat-row">
                            <span class="stat-label">
                                <i class="bi bi-people me-2 text-primary"></i>
                                Employees
                            </span>

                            <span class="stat-value"><?= $totalEmployees ?></span>
                        </div>

                        <div class="stat-row">
                            <span class="stat-label">
                                <i class="bi bi-grid me-2 text-primary"></i>
                                Active Services
                            </span>

                            <span class="stat-value">
                                <?= $activeServices ?> / <?= $totalServices ?>
                            </span>
                        </div>

                        <div class="stat-row">
                            <span class="stat-label">
                                <i class="bi bi-envelope me-2 text-danger"></i>
                                New Contact Enquiries
                            </span>

                            <span class="stat-value"><?= $newEnquiries ?></span>
                        </div>

                        <div class="mt-4">
                            <a href="reports.php" class="btn btn-outline-primary w-100">
                                <i class="bi bi-bar-chart-line me-2"></i>
                                View Detailed Reports
                            </a>
                        </div>

                    </div>
                </div>
            </div>

        </section>

        <div class="alert alert-info border-0 mt-4 mb-0" role="note">
            <i class="bi bi-info-circle me-2"></i>

            <strong>Demonstration mode:</strong>
            This dashboard uses PHP session data and sample business records.
            Revenue and performance metrics are illustrative, not real financial records.
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // Mobile sidebar
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

    // Revenue chart
    const revenueLabels = <?= json_encode($chartLabels) ?>;
    const revenueData = <?= json_encode($chartRevenue) ?>;

    const revenueCanvas = document.getElementById('revenueChart');

    new Chart(revenueCanvas, {
        type: 'line',

        data: {
            labels: revenueLabels,

            datasets: [{
                label: 'Paid revenue (USD)',
                data: revenueData,

                borderColor: '#2563eb',
                backgroundColor: 'rgba(37, 99, 235, 0.10)',
                borderWidth: 3,

                fill: true,
                tension: 0.38,

                pointRadius: 4,
                pointHoverRadius: 6,
                pointBackgroundColor: '#2563eb',
                pointBorderColor: '#ffffff',
                pointBorderWidth: 2
            }]
        },

        options: {
            responsive: true,
            maintainAspectRatio: false,

            interaction: {
                intersect: false,
                mode: 'index'
            },

            plugins: {
                legend: {
                    display: false
                },

                tooltip: {
                    backgroundColor: '#10233f',
                    padding: 12,

                    callbacks: {
                        label: function (context) {
                            return ' Revenue: $' +
                                Number(context.parsed.y).toLocaleString(
                                    'en-US',
                                    {
                                        minimumFractionDigits: 2,
                                        maximumFractionDigits: 2
                                    }
                                );
                        }
                    }
                }
            },

            scales: {
                x: {
                    grid: {
                        display: false
                    },

                    border: {
                        display: false
                    },

                    ticks: {
                        color: '#8290a4',
                        font: {
                            family: 'Inter',
                            size: 10
                        }
                    }
                },

                y: {
                    beginAtZero: true,

                    grid: {
                        color: '#edf1f7',
                        drawTicks: false
                    },

                    border: {
                        display: false,
                        dash: [4, 4]
                    },

                    ticks: {
                        color: '#8290a4',
                        padding: 10,

                        callback: function (value) {
                            return '$' + Number(value).toLocaleString();
                        },

                        font: {
                            family: 'Inter',
                            size: 10
                        }
                    }
                }
            }
        }
    });
</script>

</body>
</html>
