
<?php
session_start();

/*
|--------------------------------------------------------------------------
| Azure Bay Resort - Customer Management
|--------------------------------------------------------------------------
| Demo features:
| - View and search registered customers
| - Filter by account status and booking activity
| - Edit guest contact information
| - Activate or suspend customer accounts
| - View customer reservation history
|
| This demonstration uses PHP sessions, not MySQL.
*/

if (!isset($_SESSION['azurebay_admin_customers'])) {
    $_SESSION['azurebay_admin_customers'] = [
        [
            'id' => 2001,
            'customer_code' => 'ABG-2001',
            'name' => 'Olivia Bennett',
            'email' => 'olivia.bennett@example.com',
            'phone' => '+1 202 555 0141',
            'country' => 'United States',
            'address' => 'California, United States',
            'registered_at' => date('Y-m-d', strtotime('-120 days')),
            'status' => 'Active',
            'notes' => 'Prefers ocean-view accommodation.'
        ],
        [
            'id' => 2002,
            'customer_code' => 'ABG-2002',
            'name' => 'James Anderson',
            'email' => 'james.anderson@example.com',
            'phone' => '+1 202 555 0172',
            'country' => 'United Kingdom',
            'address' => 'London, United Kingdom',
            'registered_at' => date('Y-m-d', strtotime('-95 days')),
            'status' => 'Active',
            'notes' => ''
        ],
        [
            'id' => 2003,
            'customer_code' => 'ABG-2003',
            'name' => 'Sophia Williams',
            'email' => 'sophia.williams@example.com',
            'phone' => '+1 202 555 0195',
            'country' => 'Australia',
            'address' => 'Sydney, Australia',
            'registered_at' => date('Y-m-d', strtotime('-80 days')),
            'status' => 'Active',
            'notes' => 'Family guest.'
        ],
        [
            'id' => 2004,
            'customer_code' => 'ABG-2004',
            'name' => 'William Taylor',
            'email' => 'william.taylor@example.com',
            'phone' => '+1 202 555 0123',
            'country' => 'Canada',
            'address' => 'Toronto, Canada',
            'registered_at' => date('Y-m-d', strtotime('-60 days')),
            'status' => 'Active',
            'notes' => ''
        ],
        [
            'id' => 2005,
            'customer_code' => 'ABG-2005',
            'name' => 'Isabella Martinez',
            'email' => 'isabella.martinez@example.com',
            'phone' => '+1 202 555 0168',
            'country' => 'Spain',
            'address' => 'Madrid, Spain',
            'registered_at' => date('Y-m-d', strtotime('-45 days')),
            'status' => 'Active',
            'notes' => 'Returning guest.'
        ],
        [
            'id' => 2006,
            'customer_code' => 'ABG-2006',
            'name' => 'Noah Wilson',
            'email' => 'noah.wilson@example.com',
            'phone' => '+1 202 555 0184',
            'country' => 'United States',
            'address' => 'Florida, United States',
            'registered_at' => date('Y-m-d', strtotime('-30 days')),
            'status' => 'Suspended',
            'notes' => 'Account suspended for demonstration purposes.'
        ],
        [
            'id' => 2007,
            'customer_code' => 'ABG-2007',
            'name' => 'Emma Thompson',
            'email' => 'emma.thompson@example.com',
            'phone' => '+1 202 555 0117',
            'country' => 'New Zealand',
            'address' => 'Auckland, New Zealand',
            'registered_at' => date('Y-m-d', strtotime('-15 days')),
            'status' => 'Active',
            'notes' => ''
        ],
        [
            'id' => 2008,
            'customer_code' => 'ABG-2008',
            'name' => 'Daniel Cooper',
            'email' => 'daniel.cooper@example.com',
            'phone' => '+1 202 555 0109',
            'country' => 'Germany',
            'address' => 'Berlin, Germany',
            'registered_at' => date('Y-m-d', strtotime('-7 days')),
            'status' => 'Active',
            'notes' => ''
        ]
    ];
}

/*
|--------------------------------------------------------------------------
| Helpers
|--------------------------------------------------------------------------
*/

function customerEscape($value)
{
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

function customerRedirect($message, $type = 'success')
{
    $_SESSION['azurebay_customer_flash'] = [
        'message' => $message,
        'type' => $type
    ];

    header('Location: customers.php');
    exit;
}

function customerFormatDate($date)
{
    if (!$date || strtotime($date) === false) {
        return '—';
    }

    return date('d M Y', strtotime($date));
}

function customerStatusClass($status)
{
    return $status === 'Active' ? 'account-active' : 'account-suspended';
}

/*
|--------------------------------------------------------------------------
| Process Customer Actions
|--------------------------------------------------------------------------
*/

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $customerId = isset($_POST['customer_id'])
        ? (int)$_POST['customer_id']
        : 0;

    $customerIndex = null;

    foreach ($_SESSION['azurebay_admin_customers'] as $index => $customer) {
        if ((int)$customer['id'] === $customerId) {
            $customerIndex = $index;
            break;
        }
    }

    if ($customerIndex === null) {
        customerRedirect('Customer record was not found.', 'danger');
    }

    if ($action === 'update_customer') {
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $country = trim($_POST['country'] ?? '');
        $address = trim($_POST['address'] ?? '');
        $notes = trim($_POST['notes'] ?? '');

        if ($name === '' || strlen($name) < 2) {
            customerRedirect('Please enter a valid customer name.', 'danger');
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            customerRedirect('Please enter a valid email address.', 'danger');
        }

        if ($phone === '' || strlen($phone) > 40) {
            customerRedirect('Please enter a valid contact number.', 'danger');
        }

        if ($country === '') {
            customerRedirect('Please enter the customer country.', 'danger');
        }

        if (
            strlen($name) > 120 ||
            strlen($email) > 190 ||
            strlen($country) > 100 ||
            strlen($address) > 255 ||
            strlen($notes) > 1000
        ) {
            customerRedirect('One or more fields exceed the permitted length.', 'danger');
        }

        foreach ($_SESSION['azurebay_admin_customers'] as $index => $existingCustomer) {
            if (
                $index !== $customerIndex &&
                strcasecmp($existingCustomer['email'], $email) === 0
            ) {
                customerRedirect(
                    'Another customer already uses this email address.',
                    'danger'
                );
            }
        }

        $_SESSION['azurebay_admin_customers'][$customerIndex]['name'] = $name;
        $_SESSION['azurebay_admin_customers'][$customerIndex]['email'] = $email;
        $_SESSION['azurebay_admin_customers'][$customerIndex]['phone'] = $phone;
        $_SESSION['azurebay_admin_customers'][$customerIndex]['country'] = $country;
        $_SESSION['azurebay_admin_customers'][$customerIndex]['address'] = $address;
        $_SESSION['azurebay_admin_customers'][$customerIndex]['notes'] = $notes;

        customerRedirect('Customer details updated successfully.');
    }

    if ($action === 'toggle_status') {
        $newStatus = $_POST['new_status'] ?? '';

        if (!in_array($newStatus, ['Active', 'Suspended'], true)) {
            customerRedirect('Invalid customer account status.', 'danger');
        }

        $_SESSION['azurebay_admin_customers'][$customerIndex]['status'] = $newStatus;

        customerRedirect(
            'Customer account ' .
            strtolower($newStatus) .
            ' successfully.'
        );
    }

    customerRedirect('Unknown customer management action.', 'danger');
}

/*
|--------------------------------------------------------------------------
| Flash Message
|--------------------------------------------------------------------------
*/

$flash = $_SESSION['azurebay_customer_flash'] ?? null;
unset($_SESSION['azurebay_customer_flash']);

/*
|--------------------------------------------------------------------------
| Search and Filtering
|--------------------------------------------------------------------------
*/

$search = trim($_GET['search'] ?? '');
$statusFilter = $_GET['status'] ?? '';
$bookingFilter = $_GET['booking_activity'] ?? '';
$countryFilter = $_GET['country'] ?? '';
$sort = $_GET['sort'] ?? 'newest';

$customers = $_SESSION['azurebay_admin_customers'];

/*
|--------------------------------------------------------------------------
| Reservation History
|--------------------------------------------------------------------------
| If reservations.php has already initialized its sample reservations,
| use those records. Otherwise use a fallback dataset for this page.
|--------------------------------------------------------------------------
*/

if (!isset($_SESSION['azurebay_admin_reservations'])) {
    $_SESSION['azurebay_admin_reservations'] = [
        [
            'id' => 1001,
            'reference' => 'ABR-2026-1001',
            'guest' => 'Olivia Bennett',
            'email' => 'olivia.bennett@example.com',
            'room' => 'Deluxe Ocean View',
            'room_number' => 'A-101',
            'check_in' => date('Y-m-d', strtotime('+2 days')),
            'check_out' => date('Y-m-d', strtotime('+5 days')),
            'guests' => 2,
            'total' => 540,
            'booking_status' => 'Confirmed',
            'payment_status' => 'Paid',
            'payment_method' => 'Credit Card',
            'created_at' => date('Y-m-d', strtotime('-4 days'))
        ],
        [
            'id' => 1002,
            'reference' => 'ABR-2026-1002',
            'guest' => 'James Anderson',
            'email' => 'james.anderson@example.com',
            'room' => 'Premium Beach Suite',
            'room_number' => 'B-201',
            'check_in' => date('Y-m-d', strtotime('+1 day')),
            'check_out' => date('Y-m-d', strtotime('+4 days')),
            'guests' => 2,
            'total' => 870,
            'booking_status' => 'Pending',
            'payment_status' => 'Pending',
            'payment_method' => 'Pay at Hotel',
            'created_at' => date('Y-m-d', strtotime('-2 days'))
        ],
        [
            'id' => 1003,
            'reference' => 'ABR-2026-1003',
            'guest' => 'Sophia Williams',
            'email' => 'sophia.williams@example.com',
            'room' => 'Family Coastal Suite',
            'room_number' => 'B-205',
            'check_in' => date('Y-m-d', strtotime('-2 days')),
            'check_out' => date('Y-m-d', strtotime('+1 day')),
            'guests' => 4,
            'total' => 1020,
            'booking_status' => 'Checked In',
            'payment_status' => 'Paid',
            'payment_method' => 'Credit Card',
            'created_at' => date('Y-m-d', strtotime('-10 days'))
        ],
        [
            'id' => 1004,
            'reference' => 'ABR-2026-1004',
            'guest' => 'William Taylor',
            'email' => 'william.taylor@example.com',
            'room' => 'Garden Serenity Room',
            'room_number' => 'A-102',
            'check_in' => date('Y-m-d', strtotime('-8 days')),
            'check_out' => date('Y-m-d', strtotime('-5 days')),
            'guests' => 2,
            'total' => 450,
            'booking_status' => 'Checked Out',
            'payment_status' => 'Paid',
            'payment_method' => 'Cash',
            'created_at' => date('Y-m-d', strtotime('-20 days'))
        ],
        [
            'id' => 1005,
            'reference' => 'ABR-2026-1005',
            'guest' => 'Isabella Martinez',
            'email' => 'isabella.martinez@example.com',
            'room' => 'Horizon Ocean Suite',
            'room_number' => 'B-208',
            'check_in' => date('Y-m-d', strtotime('+7 days')),
            'check_out' => date('Y-m-d', strtotime('+10 days')),
            'guests' => 2,
            'total' => 960,
            'booking_status' => 'Confirmed',
            'payment_status' => 'Partially Paid',
            'payment_method' => 'Bank Transfer',
            'created_at' => date('Y-m-d', strtotime('-1 day'))
        ],
        [
            'id' => 1006,
            'reference' => 'ABR-2026-1006',
            'guest' => 'Noah Wilson',
            'email' => 'noah.wilson@example.com',
            'room' => 'Azure Presidential Villa',
            'room_number' => 'V-001',
            'check_in' => date('Y-m-d', strtotime('+14 days')),
            'check_out' => date('Y-m-d', strtotime('+17 days')),
            'guests' => 3,
            'total' => 1350,
            'booking_status' => 'Cancelled',
            'payment_status' => 'Refunded',
            'payment_method' => 'Credit Card',
            'created_at' => date('Y-m-d', strtotime('-6 days'))
        ],
        [
            'id' => 1007,
            'reference' => 'ABR-2026-1007',
            'guest' => 'Emma Thompson',
            'email' => 'emma.thompson@example.com',
            'room' => 'Deluxe Ocean View',
            'room_number' => 'A-101',
            'check_in' => date('Y-m-d', strtotime('+20 days')),
            'check_out' => date('Y-m-d', strtotime('+23 days')),
            'guests' => 2,
            'total' => 540,
            'booking_status' => 'Pending',
            'payment_status' => 'Pending',
            'payment_method' => 'Pay at Hotel',
            'created_at' => date('Y-m-d')
        ]
    ];
}

$reservations = $_SESSION['azurebay_admin_reservations'];

/*
|--------------------------------------------------------------------------
| Build Customer Booking Statistics
|--------------------------------------------------------------------------
*/

$bookingStats = [];

foreach ($reservations as $reservation) {
    $emailKey = strtolower(trim($reservation['email'] ?? ''));

    if ($emailKey === '') {
        continue;
    }

    if (!isset($bookingStats[$emailKey])) {
        $bookingStats[$emailKey] = [
            'count' => 0,
            'total' => 0,
            'paid_total' => 0,
            'last_booking' => '',
            'history' => []
        ];
    }

    $bookingStats[$emailKey]['count']++;

    if (($reservation['booking_status'] ?? '') !== 'Cancelled') {
        $bookingStats[$emailKey]['total'] += (float)($reservation['total'] ?? 0);

        if (($reservation['payment_status'] ?? '') === 'Paid') {
            $bookingStats[$emailKey]['paid_total'] += (float)($reservation['total'] ?? 0);
        }
    }

    $bookingDate = $reservation['created_at'] ?? '';

    if (
        $bookingDate !== '' &&
        (
            $bookingStats[$emailKey]['last_booking'] === '' ||
            $bookingDate > $bookingStats[$emailKey]['last_booking']
        )
    ) {
        $bookingStats[$emailKey]['last_booking'] = $bookingDate;
    }

    $bookingStats[$emailKey]['history'][] = $reservation;
}

$customerBookingCount = function ($customer) use ($bookingStats) {
    $key = strtolower(trim($customer['email']));

    return $bookingStats[$key]['count'] ?? 0;
};

$filteredCustomers = array_filter(
    $customers,
    function ($customer) use (
        $search,
        $statusFilter,
        $bookingFilter,
        $countryFilter,
        $customerBookingCount
    ) {
        $searchable = strtolower(
            $customer['customer_code'] . ' ' .
            $customer['name'] . ' ' .
            $customer['email'] . ' ' .
            $customer['phone'] . ' ' .
            $customer['country']
        );

        if ($search !== '' && strpos($searchable, strtolower($search)) === false) {
            return false;
        }

        if (
            $statusFilter !== '' &&
            $customer['status'] !== $statusFilter
        ) {
            return false;
        }

        if (
            $countryFilter !== '' &&
            $customer['country'] !== $countryFilter
        ) {
            return false;
        }

        $count = $customerBookingCount($customer);

        if ($bookingFilter === 'with_bookings' && $count === 0) {
            return false;
        }

        if ($bookingFilter === 'no_bookings' && $count > 0) {
            return false;
        }

        return true;
    }
);

usort($filteredCustomers, function ($a, $b) use ($sort, $customerBookingCount) {
    if ($sort === 'oldest') {
        return strcmp($a['registered_at'], $b['registered_at']);
    }

    if ($sort === 'name_asc') {
        return strcasecmp($a['name'], $b['name']);
    }

    if ($sort === 'most_bookings') {
        return $customerBookingCount($b) <=> $customerBookingCount($a);
    }

    return strcmp($b['registered_at'], $a['registered_at']);
});

/*
|--------------------------------------------------------------------------
| Customer Statistics
|--------------------------------------------------------------------------
*/

$totalCustomers = count($customers);
$activeCustomers = 0;
$suspendedCustomers = 0;
$returningCustomers = 0;
$totalCustomerBookingValue = 0;

foreach ($customers as $customer) {
    if ($customer['status'] === 'Active') {
        $activeCustomers++;
    } else {
        $suspendedCustomers++;
    }

    $key = strtolower(trim($customer['email']));
    $count = $bookingStats[$key]['count'] ?? 0;

    if ($count > 1) {
        $returningCustomers++;
    }

    $totalCustomerBookingValue += $bookingStats[$key]['total'] ?? 0;
}

$countries = [];

foreach ($customers as $customer) {
    if (!empty($customer['country'])) {
        $countries[] = $customer['country'];
    }
}

$countries = array_values(array_unique($countries));
sort($countries);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Customer Management | Azure Bay Resort</title>

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
            width: 250px;
            padding: 28px 18px;
            overflow-y: auto;
            background: var(--ocean-dark);
            color: white;
            z-index: 1000;
            transition: transform .25s ease;
        }

        .brand {
            padding: 0 10px 28px;
            border-bottom: 1px solid rgba(255,255,255,.12);
            margin-bottom: 24px;
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
            color: white;
            background: rgba(255,255,255,.12);
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
            margin-bottom: 28px;
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

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 18px;
            margin-bottom: 25px;
        }

        .stat-card {
            position: relative;
            overflow: hidden;
            padding: 22px;
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
            margin-bottom: 16px;
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
            margin-bottom: 25px;
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
            padding: 22px 24px;
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
            padding: 23px 24px;
        }

        .form-label {
            margin-bottom: 7px;
            color: #52616a;
            font-size: 12px;
            font-weight: bold;
        }

        .form-control,
        .form-select {
            min-height: 43px;
            border-color: #dfe5e8;
            border-radius: 8px;
            font-size: 13px;
        }

        textarea.form-control {
            min-height: 90px;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--gold);
            box-shadow: 0 0 0 .2rem rgba(201,164,92,.14);
        }

        .btn-ocean {
            min-height: 42px;
            padding: 10px 18px;
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

        .customer-table {
            width: 100%;
            min-width: 1050px;
            margin: 0;
            border-collapse: separate;
            border-spacing: 0;
        }

        .customer-table thead th {
            padding: 15px 17px;
            border-bottom: 1px solid var(--border);
            background: #f8fafb;
            color: #74828a;
            font-size: 10px;
            font-weight: bold;
            letter-spacing: .7px;
            text-transform: uppercase;
            white-space: nowrap;
        }

        .customer-table tbody td {
            padding: 17px;
            border-bottom: 1px solid #edf0f1;
            vertical-align: middle;
            font-size: 12px;
        }

        .customer-table tbody tr:last-child td {
            border-bottom: 0;
        }

        .customer-table tbody tr:hover {
            background: #fcfdfd;
        }

        .customer-cell {
            display: flex;
            align-items: center;
            gap: 12px;
            min-width: 210px;
        }

        .customer-avatar {
            display: grid;
            flex: 0 0 43px;
            width: 43px;
            height: 43px;
            place-items: center;
            border: 1px solid #e4ecef;
            border-radius: 50%;
            background: var(--ocean-light);
            color: var(--ocean);
            font-size: 14px;
            font-weight: bold;
        }

        .customer-name {
            margin-bottom: 4px;
            color: var(--ocean-dark);
            font-weight: bold;
        }

        .customer-code {
            color: #9a7a3c;
            font-size: 10px;
            font-weight: bold;
        }

        .customer-contact {
            color: #71818a;
            font-size: 11px;
            line-height: 1.8;
            overflow-wrap: anywhere;
        }

        .account-badge {
            display: inline-block;
            padding: 6px 10px;
            border-radius: 20px;
            font-size: 10px;
            font-weight: bold;
            white-space: nowrap;
        }

        .account-active {
            background: #e4f5ec;
            color: #23774c;
        }

        .account-suspended {
            background: #fde8e7;
            color: #b33d37;
        }

        .booking-count {
            display: inline-grid;
            min-width: 30px;
            height: 30px;
            place-items: center;
            padding: 0 8px;
            border-radius: 9px;
            background: var(--ocean-light);
            color: var(--ocean);
            font-size: 12px;
            font-weight: bold;
        }

        .booking-value {
            color: var(--ocean-dark);
            font-size: 13px;
            font-weight: bold;
            white-space: nowrap;
        }

        .muted-small {
            margin-top: 4px;
            color: #849199;
            font-size: 10px;
        }

        .action-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
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
            padding: 9px 15px;
            font-size: 12px;
        }

        .dropdown-item:active {
            background: var(--ocean);
        }

        .result-count {
            color: var(--muted);
            font-size: 12px;
        }

        .empty-state {
            padding: 55px 20px;
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

        .history-table {
            width: 100%;
            min-width: 600px;
            border-collapse: collapse;
        }

        .history-table th {
            padding: 11px;
            background: #f6f8f9;
            color: #71818a;
            font-size: 10px;
            text-transform: uppercase;
        }

        .history-table td {
            padding: 12px 11px;
            border-bottom: 1px solid #edf0f1;
            font-size: 11px;
            vertical-align: middle;
        }

        .history-table tr:last-child td {
            border-bottom: 0;
        }

        .mobile-menu {
            display: none;
            border: 1px solid var(--border);
            border-radius: 8px;
            background: white;
            color: var(--ocean);
            padding: 8px 12px;
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

            .topbar {
                gap: 12px;
            }

            .page-title {
                font-size: 24px;
            }

            .filter-actions {
                display: flex;
                flex-wrap: wrap;
                gap: 8px;
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

    <a href="customers.php" class="active">
        <span class="nav-icon">♙</span> Customers
    </a>

    <a href="guest_services.php">
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
                <div class="eyebrow">Guest Relationship Management</div>
                <h1 class="page-title">Customer Management</h1>
                <div class="page-subtitle">
                    Manage guest profiles, account status and reservation history.
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
        Customer details and account-status changes are stored in the current
        PHP session. This page does not create or modify permanent database records.
    </div>

    <?php if ($flash): ?>
        <div class="alert alert-<?= customerEscape($flash['type']) ?> alert-dismissible fade show"
             role="alert">
            <?= customerEscape($flash['message']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"
                    aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- Statistics -->
    <section class="stats-grid">

        <div class="stat-card">
            <div class="stat-icon">♙</div>
            <div class="stat-label">Registered Customers</div>
            <div class="stat-value"><?= number_format($totalCustomers) ?></div>
            <div class="stat-note">All guest profiles</div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">✓</div>
            <div class="stat-label">Active Accounts</div>
            <div class="stat-value"><?= number_format($activeCustomers) ?></div>
            <div class="stat-note">Accounts currently enabled</div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">↻</div>
            <div class="stat-label">Returning Guests</div>
            <div class="stat-value"><?= number_format($returningCustomers) ?></div>
            <div class="stat-note">Customers with multiple bookings</div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">$</div>
            <div class="stat-label">Booking Value</div>
            <div class="stat-value">$<?= number_format($totalCustomerBookingValue, 2) ?></div>
            <div class="stat-note">Non-cancelled reservation totals</div>
        </div>

    </section>

    <!-- Search and Filters -->
    <section class="panel">

        <div class="panel-header">
            <div>
                <h2 class="panel-title">Search Customer Directory</h2>
                <div class="panel-description">
                    Find guest accounts using contact details or customer ID.
                </div>
            </div>
        </div>

        <div class="panel-body">
            <form method="GET" action="customers.php">
                <div class="row g-3">

                    <div class="col-lg-3 col-md-6">
                        <label for="search" class="form-label">
                            Search Customers
                        </label>
                        <input
                            type="text"
                            class="form-control"
                            id="search"
                            name="search"
                            value="<?= customerEscape($search) ?>"
                            placeholder="Name, email, phone or ID..."
                        >
                    </div>

                    <div class="col-lg-2 col-md-6">
                        <label for="status" class="form-label">
                            Account Status
                        </label>
                        <select class="form-select" id="status" name="status">
                            <option value="">All accounts</option>
                            <option value="Active"
                                <?= $statusFilter === 'Active' ? 'selected' : '' ?>>
                                Active
                            </option>
                            <option value="Suspended"
                                <?= $statusFilter === 'Suspended' ? 'selected' : '' ?>>
                                Suspended
                            </option>
                        </select>
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <label for="booking_activity" class="form-label">
                            Booking Activity
                        </label>
                        <select class="form-select" id="booking_activity"
                                name="booking_activity">
                            <option value="">All customers</option>
                            <option value="with_bookings"
                                <?= $bookingFilter === 'with_bookings' ? 'selected' : '' ?>>
                                With reservations
                            </option>
                            <option value="no_bookings"
                                <?= $bookingFilter === 'no_bookings' ? 'selected' : '' ?>>
                                Without reservations
                            </option>
                        </select>
                    </div>

                    <div class="col-lg-2 col-md-6">
                        <label for="country" class="form-label">Country</label>
                        <select class="form-select" id="country" name="country">
                            <option value="">All countries</option>
                            <?php foreach ($countries as $country): ?>
                                <option value="<?= customerEscape($country) ?>"
                                    <?= $countryFilter === $country ? 'selected' : '' ?>>
                                    <?= customerEscape($country) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-lg-2 col-md-6">
                        <label for="sort" class="form-label">Sort By</label>
                        <select class="form-select" id="sort" name="sort">
                            <option value="newest"
                                <?= $sort === 'newest' ? 'selected' : '' ?>>
                                Newest members
                            </option>
                            <option value="oldest"
                                <?= $sort === 'oldest' ? 'selected' : '' ?>>
                                Oldest members
                            </option>
                            <option value="name_asc"
                                <?= $sort === 'name_asc' ? 'selected' : '' ?>>
                                Name A–Z
                            </option>
                            <option value="most_bookings"
                                <?= $sort === 'most_bookings' ? 'selected' : '' ?>>
                                Most bookings
                            </option>
                        </select>
                    </div>

                    <div class="col-12 filter-actions">
                        <button type="submit" class="btn btn-ocean">
                            Search Customers
                        </button>

                        <a href="customers.php" class="btn-reset">
                            Reset Filters
                        </a>
                    </div>

                </div>
            </form>
        </div>

    </section>

    <!-- Customer Directory -->
    <section class="panel">

        <div class="panel-header">
            <div>
                <h2 class="panel-title">Registered Guest Directory</h2>
                <div class="panel-description">
                    View profiles, booking activity and customer account controls.
                </div>
            </div>

            <span class="result-count">
                Showing <?= number_format(count($filteredCustomers)) ?>
                of <?= number_format($totalCustomers) ?>
            </span>
        </div>

        <?php if (empty($filteredCustomers)): ?>

            <div class="empty-state">
                <div class="empty-icon">⌕</div>
                <div class="empty-title">No customers found</div>
                <div class="empty-text">
                    Try another search term or adjust the selected filters.
                </div>

                <a href="customers.php" class="btn btn-ocean mt-3">
                    View All Customers
                </a>
            </div>

        <?php else: ?>

            <div class="table-wrap">
                <table class="customer-table">

                    <thead>
                        <tr>
                            <th>Customer</th>
                            <th>Contact Information</th>
                            <th>Country</th>
                            <th>Registered</th>
                            <th>Bookings</th>
                            <th>Booking Value</th>
                            <th>Account Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                    <?php foreach ($filteredCustomers as $customer): ?>

                        <?php
                            $id = (int)$customer['id'];
                            $emailKey = strtolower(trim($customer['email']));
                            $stats = $bookingStats[$emailKey] ?? [
                                'count' => 0,
                                'total' => 0,
                                'paid_total' => 0,
                                'last_booking' => '',
                                'history' => []
                            ];

                            $initials = '';
                            $nameParts = preg_split('/\s+/', trim($customer['name']));

                            foreach (array_slice($nameParts, 0, 2) as $part) {
                                $initials .= strtoupper(substr($part, 0, 1));
                            }
                        ?>

                        <tr>
                            <td>
                                <div class="customer-cell">
                                    <div class="customer-avatar">
                                        <?= customerEscape($initials ?: 'G') ?>
                                    </div>

                                    <div>
                                        <div class="customer-name">
                                            <?= customerEscape($customer['name']) ?>
                                        </div>

                                        <div class="customer-code">
                                            <?= customerEscape($customer['customer_code']) ?>
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <td>
                                <div class="customer-contact">
                                    <?= customerEscape($customer['email']) ?><br>
                                    <?= customerEscape($customer['phone']) ?>
                                </div>
                            </td>

                            <td>
                                <?= customerEscape($customer['country']) ?>
                            </td>

                            <td>
                                <div>
                                    <?= customerEscape(customerFormatDate($customer['registered_at'])) ?>
                                </div>
                                <div class="muted-small">
                                    Member since registration
                                </div>
                            </td>

                            <td>
                                <span class="booking-count">
                                    <?= (int)$stats['count'] ?>
                                </span>
                            </td>

                            <td>
                                <div class="booking-value">
                                    $<?= number_format((float)$stats['total'], 2) ?>
                                </div>
                                <div class="muted-small">
                                    Excludes cancelled bookings
                                </div>
                            </td>

                            <td>
                                <span class="account-badge <?= customerEscape(customerStatusClass($customer['status'])) ?>">
                                    <?= customerEscape($customer['status']) ?>
                                </span>
                            </td>

                            <td>
                                <div class="dropdown">
                                    <button
                                        class="action-button dropdown-toggle"
                                        type="button"
                                        data-bs-toggle="dropdown"
                                        aria-expanded="false">
                                        Manage
                                    </button>

                                    <ul class="dropdown-menu dropdown-menu-end">

                                        <li>
                                            <button
                                                class="dropdown-item"
                                                type="button"
                                                data-bs-toggle="modal"
                                                data-bs-target="#profileModal"
                                                data-id="<?= $id ?>"
                                                data-code="<?= customerEscape($customer['customer_code']) ?>"
                                                data-name="<?= customerEscape($customer['name']) ?>"
                                                data-email="<?= customerEscape($customer['email']) ?>"
                                                data-phone="<?= customerEscape($customer['phone']) ?>"
                                                data-country="<?= customerEscape($customer['country']) ?>"
                                                data-address="<?= customerEscape($customer['address']) ?>"
                                                data-notes="<?= customerEscape($customer['notes']) ?>"
                                                data-status="<?= customerEscape($customer['status']) ?>">
                                                View / Edit Profile
                                            </button>
                                        </li>

                                        <li>
                                            <button
                                                class="dropdown-item"
                                                type="button"
                                                data-bs-toggle="modal"
                                                data-bs-target="#historyModal"
                                                data-customer-name="<?= customerEscape($customer['name']) ?>"
                                                data-customer-code="<?= customerEscape($customer['customer_code']) ?>"
                                                data-history="<?= customerEscape(json_encode($stats['history'])) ?>">
                                                View Booking History
                                            </button>
                                        </li>

                                        <li><hr class="dropdown-divider"></li>

                                        <?php if ($customer['status'] === 'Active'): ?>
                                            <li>
                                                <form method="POST" action="customers.php"
                                                      onsubmit="return confirm('Suspend this customer account?');">
                                                    <input type="hidden" name="action"
                                                           value="toggle_status">
                                                    <input type="hidden" name="customer_id"
                                                           value="<?= $id ?>">
                                                    <input type="hidden" name="new_status"
                                                           value="Suspended">

                                                    <button class="dropdown-item text-danger"
                                                            type="submit">
                                                        Suspend Account
                                                    </button>
                                                </form>
                                            </li>
                                        <?php else: ?>
                                            <li>
                                                <form method="POST" action="customers.php"
                                                      onsubmit="return confirm('Reactivate this customer account?');">
                                                    <input type="hidden" name="action"
                                                           value="toggle_status">
                                                    <input type="hidden" name="customer_id"
                                                           value="<?= $id ?>">
                                                    <input type="hidden" name="new_status"
                                                           value="Active">

                                                    <button class="dropdown-item text-success"
                                                            type="submit">
                                                        Activate Account
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
        Azure Bay Resort · Customer Management · Demonstration Environment
    </div>

</main>

<!-- View/Edit Customer Modal -->
<div class="modal fade" id="profileModal" tabindex="-1"
     aria-labelledby="profileModalLabel" aria-hidden="true">

    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">

            <div class="modal-header">
                <div>
                    <h2 class="modal-title" id="profileModalLabel">
                        Customer Profile
                    </h2>
                    <div class="small text-white-50 mt-1" id="profileCustomerCode"></div>
                </div>

                <button type="button" class="btn-close"
                        data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form method="POST" action="customers.php" id="editCustomerForm">

                <div class="modal-body">

                    <input type="hidden" name="action" value="update_customer">
                    <input type="hidden" name="customer_id" id="editCustomerId">

                    <div class="row g-3">

                        <div class="col-md-6">
                            <label for="editName" class="form-label">
                                Full Name
                            </label>
                            <input type="text" class="form-control" id="editName"
                                   name="name" maxlength="120" required>
                        </div>

                        <div class="col-md-6">
                            <label for="editEmail" class="form-label">
                                Email Address
                            </label>
                            <input type="email" class="form-control" id="editEmail"
                                   name="email" maxlength="190" required>
                        </div>

                        <div class="col-md-6">
                            <label for="editPhone" class="form-label">
                                Contact Number
                            </label>
                            <input type="text" class="form-control" id="editPhone"
                                   name="phone" maxlength="40" required>
                        </div>

                        <div class="col-md-6">
                            <label for="editCountry" class="form-label">
                                Country
                            </label>
                            <input type="text" class="form-control" id="editCountry"
                                   name="country" maxlength="100" required>
                        </div>

                        <div class="col-12">
                            <label for="editAddress" class="form-label">
                                Address
                            </label>
                            <input type="text" class="form-control" id="editAddress"
                                   name="address" maxlength="255">
                        </div>

                        <div class="col-12">
                            <label for="editNotes" class="form-label">
                                Administrative Notes
                            </label>
                            <textarea class="form-control" id="editNotes"
                                      name="notes" maxlength="1000"
                                      placeholder="Optional internal notes about this guest..."></textarea>
                        </div>

                        <div class="col-12">
                            <div class="detail-box">
                                <div class="detail-label">Account Status</div>
                                <div class="detail-value" id="editStatusDisplay"></div>
                                <div class="muted-small">
                                    Use the Manage menu to activate or suspend this account.
                                </div>
                            </div>
                        </div>

                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn-reset"
                            data-bs-dismiss="modal">
                        Cancel
                    </button>

                    <button type="submit" class="btn btn-ocean">
                        Save Customer Details
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>

<!-- Booking History Modal -->
<div class="modal fade" id="historyModal" tabindex="-1"
     aria-labelledby="historyModalLabel" aria-hidden="true">

    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">

            <div class="modal-header">
                <div>
                    <h2 class="modal-title" id="historyModalLabel">
                        Customer Booking History
                    </h2>
                    <div class="small text-white-50 mt-1" id="historyCustomerCode"></div>
                </div>

                <button type="button" class="btn-close"
                        data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">

                <h6 class="fw-bold mb-3" style="color: var(--ocean-dark);"
                    id="historyCustomerName"></h6>

                <div id="historySummary" class="mb-3"></div>

                <div class="table-wrap">
                    <table class="history-table">
                        <thead>
                            <tr>
                                <th>Reference</th>
                                <th>Room</th>
                                <th>Check-in</th>
                                <th>Check-out</th>
                                <th>Total</th>
                                <th>Booking Status</th>
                                <th>Payment Status</th>
                            </tr>
                        </thead>

                        <tbody id="historyTableBody"></tbody>
                    </table>
                </div>

                <div id="historyEmpty" class="empty-state d-none">
                    <div class="empty-title">No booking history</div>
                    <div class="empty-text">
                        This customer does not have any recorded reservations.
                    </div>
                </div>

                <div class="d-flex justify-content-end mt-4">
                    <button type="button" class="btn btn-ocean"
                            data-bs-dismiss="modal">
                        Close History
                    </button>
                </div>

            </div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>


<script>
    // Mobile sidebar
    document.getElementById('menuToggle').addEventListener('click', function () {
        document.getElementById('sidebar').classList.toggle('show');
    });

    // Populate the customer profile editing modal.
    const profileModal = document.getElementById('profileModal');

    profileModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        const data = button.dataset;

        document.getElementById('editCustomerId').value = data.id || '';
        document.getElementById('profileCustomerCode').textContent =
            data.code || '';

        document.getElementById('editName').value = data.name || '';
        document.getElementById('editEmail').value = data.email || '';
        document.getElementById('editPhone').value = data.phone || '';
        document.getElementById('editCountry').value = data.country || '';
        document.getElementById('editAddress').value = data.address || '';
        document.getElementById('editNotes').value = data.notes || '';

        document.getElementById('editStatusDisplay').textContent =
            data.status || 'Unknown';
    });

    // Populate customer booking history.
    const historyModal = document.getElementById('historyModal');

    historyModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        const data = button.dataset;

        document.getElementById('historyCustomerName').textContent =
            data.customerName || 'Customer';

        document.getElementById('historyCustomerCode').textContent =
            data.customerCode || '';

        const tbody = document.getElementById('historyTableBody');
        const emptyState = document.getElementById('historyEmpty');
        const summary = document.getElementById('historySummary');

        tbody.replaceChildren();
        summary.replaceChildren();

        let history = [];

        try {
            history = JSON.parse(data.history || '[]');
            if (!Array.isArray(history)) {
                history = [];
            }
        } catch (error) {
            history = [];
        }

        if (history.length === 0) {
            emptyState.classList.remove('d-none');
            summary.textContent = 'No reservations recorded for this customer.';
            return;
        }

        emptyState.classList.add('d-none');

        const summaryText = document.createElement('div');
        summaryText.className = 'detail-box';

        const total = history.reduce(function (sum, booking) {
            if (booking.booking_status !== 'Cancelled') {
                return sum + Number(booking.total || 0);
            }
            return sum;
        }, 0);

        summaryText.textContent =
            history.length + ' reservation(s) recorded · ' +
            'Non-cancelled booking value: $' + total.toFixed(2);

        summary.appendChild(summaryText);

        history.sort(function (a, b) {
            return String(b.created_at || '').localeCompare(
                String(a.created_at || '')
            );
        });

        history.forEach(function (booking) {
            const row = document.createElement('tr');

            const values = [
                booking.reference || '—',
                (booking.room || '—') + ' (' + (booking.room_number || '—') + ')',
                booking.check_in || '—',
                booking.check_out || '—',
                '$' + Number(booking.total || 0).toFixed(2),
                booking.booking_status || '—',
                booking.payment_status || '—'
            ];

            values.forEach(function (value) {
                const cell = document.createElement('td');
                cell.textContent = value;
                row.appendChild(cell);
            });

            tbody.appendChild(row);
        });
    });
</script>

</body>
</html>