
<?php
session_start();

date_default_timezone_set('Asia/Colombo');

/*
|--------------------------------------------------------------------------
| Azure Bay Resort - Reports & Analytics
|--------------------------------------------------------------------------
| Standalone demonstration page.
| Sample report data is generated locally without a database.
*/

$today = date('Y-m-d');

$rangeOptions = [
    '7d'  => 'Last 7 Days',
    '30d' => 'Last 30 Days',
    '90d' => 'Last 90 Days',
    '1y'  => 'Last 12 Months',
    'custom' => 'Custom Date Range'
];

$range = $_GET['range'] ?? '30d';

if (!array_key_exists($range, $rangeOptions)) {
    $range = '30d';
}

$startDate = $today;
$endDate = $today;

switch ($range) {
    case '7d':
        $startDate = date('Y-m-d', strtotime('-6 days', strtotime($today)));
        break;

    case '30d':
        $startDate = date('Y-m-d', strtotime('-29 days', strtotime($today)));
        break;

    case '90d':
        $startDate = date('Y-m-d', strtotime('-89 days', strtotime($today)));
        break;

    case '1y':
        $startDate = date('Y-m-d', strtotime('-11 months', strtotime(date('Y-m-01'))));
        break;

    case 'custom':
        $startDate = $_GET['start_date'] ?? date('Y-m-d', strtotime('-29 days'));
        $endDate = $_GET['end_date'] ?? $today;

        $startObject = DateTime::createFromFormat('!Y-m-d', $startDate);
        $endObject = DateTime::createFromFormat('!Y-m-d', $endDate);

        if (
            !$startObject ||
            !$endObject ||
            $startObject->format('Y-m-d') !== $startDate ||
            $endObject->format('Y-m-d') !== $endDate ||
            $startDate > $endDate ||
            $endDate > $today
        ) {
            $startDate = date('Y-m-d', strtotime('-29 days'));
            $endDate = $today;
        }
        break;
}

if ($range !== 'custom') {
    $endDate = $today;
}

$startTimestamp = strtotime($startDate);
$endTimestamp = strtotime($endDate);

$daysInRange = (int)floor(
    ($endTimestamp - $startTimestamp) / 86400
) + 1;

$daysInRange = max(1, $daysInRange);

function e($value)
{
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

function money($amount)
{
    return '$' . number_format((float)$amount, 2);
}

function percentageChange($current, $previous)
{
    if ($previous == 0) {
        return $current > 0 ? 100 : 0;
    }

    return round((($current - $previous) / $previous) * 100, 1);
}

function signedPercentage($value)
{
    return ($value > 0 ? '+' : '') . number_format($value, 1) . '%';
}

function csvSafe($value)
{
    $value = (string)$value;

    // Prevent spreadsheet formula injection in exported text fields.
    if (preg_match('/^[\s]*[=+\-@]/', $value)) {
        return "'" . $value;
    }

    return $value;
}

/*
|--------------------------------------------------------------------------
| Demo Room Inventory
|--------------------------------------------------------------------------
*/

$rooms = [
    [
        'number' => 'A-101',
        'name' => 'Deluxe Ocean View',
        'category' => 'Deluxe Room',
        'price' => 180,
        'capacity' => 2
    ],
    [
        'number' => 'A-102',
        'name' => 'Garden Serenity Room',
        'category' => 'Deluxe Room',
        'price' => 150,
        'capacity' => 2
    ],
    [
        'number' => 'B-201',
        'name' => 'Premium Beach Suite',
        'category' => 'Suite',
        'price' => 290,
        'capacity' => 3
    ],
    [
        'number' => 'B-205',
        'name' => 'Family Coastal Suite',
        'category' => 'Family Suite',
        'price' => 340,
        'capacity' => 5
    ],
    [
        'number' => 'V-001',
        'name' => 'Azure Presidential Villa',
        'category' => 'Villa',
        'price' => 450,
        'capacity' => 4
    ],
    [
        'number' => 'B-208',
        'name' => 'Horizon Ocean Suite',
        'category' => 'Suite',
        'price' => 320,
        'capacity' => 3
    ],
];

/*
|--------------------------------------------------------------------------
| Generate Reproducible Demonstration Data
|--------------------------------------------------------------------------
| The same dates always produce the same sample figures.
| Replace this section with database queries for a live system.
*/

$dailyData = [];

for ($i = 0; $i < $daysInRange; $i++) {
    $date = date('Y-m-d', strtotime("+$i days", $startTimestamp));

    $dayNumber = (int)date('z', strtotime($date));
    $monthNumber = (int)date('n', strtotime($date));
    $weekDay = (int)date('N', strtotime($date));

    $seasonFactor = 1 + (sin($monthNumber * 0.75) * 0.13);
    $weekendFactor = ($weekDay >= 5) ? 1.18 : 1.0;

    $reservations = max(
        1,
        (int)round(
            (3 + (($dayNumber * 7) % 8)) *
            $seasonFactor *
            $weekendFactor
        )
    );

    $completed = max(
        0,
        (int)round($reservations * (0.58 + (($dayNumber % 4) * 0.06)))
    );

    $cancelled = max(
        0,
        (int)round($reservations * (0.06 + (($dayNumber % 3) * 0.02)))
    );

    $pending = max(0, $reservations - $completed - $cancelled);

    $averageRoomRate = 190 + (($dayNumber * 13) % 105);

    $roomRevenue = $completed * $averageRoomRate;

    $otherRevenue = $completed * (18 + (($dayNumber % 5) * 9));

    $discounts = round($roomRevenue * (0.025 + (($dayNumber % 3) * 0.005)), 2);

    $grossRevenue = $roomRevenue + $otherRevenue;

    $netRevenue = max(0, $grossRevenue - $discounts);

    $paymentsReceived = round($netRevenue * (0.84 + (($dayNumber % 4) * 0.04)), 2);

    $occupancy = min(
        100,
        max(
            15,
            (int)round(
                38 +
                (($dayNumber * 11) % 43) *
                $seasonFactor *
                ($weekendFactor > 1 ? 1.05 : 1)
            )
        )
    );

    $dailyData[$date] = [
        'date' => $date,
        'reservations' => $reservations,
        'completed' => $completed,
        'cancelled' => $cancelled,
        'pending' => $pending,
        'gross_revenue' => round($grossRevenue, 2),
        'discounts' => $discounts,
        'net_revenue' => round($netRevenue, 2),
        'payments_received' => $paymentsReceived,
        'occupancy' => $occupancy
    ];
}

/*
|--------------------------------------------------------------------------
| Aggregate Selected Period
|--------------------------------------------------------------------------
*/

$totalReservations = 0;
$completedReservations = 0;
$cancelledReservations = 0;
$pendingReservations = 0;

$grossRevenue = 0;
$totalDiscounts = 0;
$netRevenue = 0;
$paymentsReceived = 0;

$occupancySum = 0;
$peakOccupancy = 0;
$occupancyDays = 0;

foreach ($dailyData as $row) {
    $totalReservations += $row['reservations'];
    $completedReservations += $row['completed'];
    $cancelledReservations += $row['cancelled'];
    $pendingReservations += $row['pending'];

    $grossRevenue += $row['gross_revenue'];
    $totalDiscounts += $row['discounts'];
    $netRevenue += $row['net_revenue'];
    $paymentsReceived += $row['payments_received'];

    $occupancySum += $row['occupancy'];
    $peakOccupancy = max($peakOccupancy, $row['occupancy']);
    $occupancyDays++;
}

$averageOccupancy = $occupancyDays > 0
    ? round($occupancySum / $occupancyDays, 1)
    : 0;

$averageDailyRevenue = $daysInRange > 0
    ? $netRevenue / $daysInRange
    : 0;

$averageBookingValue = $completedReservations > 0
    ? $netRevenue / $completedReservations
    : 0;

$completionRate = $totalReservations > 0
    ? round(($completedReservations / $totalReservations) * 100, 1)
    : 0;

$cancellationRate = $totalReservations > 0
    ? round(($cancelledReservations / $totalReservations) * 100, 1)
    : 0;

$collectionRate = $netRevenue > 0
    ? round(($paymentsReceived / $netRevenue) * 100, 1)
    : 0;

/*
|--------------------------------------------------------------------------
| Previous Period Comparison
|--------------------------------------------------------------------------
*/

$previousEnd = date('Y-m-d', strtotime('-1 day', $startTimestamp));

$previousStart = date(
    'Y-m-d',
    strtotime('-' . ($daysInRange - 1) . ' days', strtotime($previousEnd))
);

$previousRevenue = 0;
$previousReservations = 0;
$previousOccupancy = 0;

for ($i = 0; $i < $daysInRange; $i++) {
    $date = date('Y-m-d', strtotime("+$i days", strtotime($previousStart)));

    $dayNumber = (int)date('z', strtotime($date));
    $monthNumber = (int)date('n', strtotime($date));
    $weekDay = (int)date('N', strtotime($date));

    $seasonFactor = 1 + (sin($monthNumber * 0.75) * 0.13);
    $weekendFactor = ($weekDay >= 5) ? 1.18 : 1.0;

    $reservations = max(
        1,
        (int)round(
            (3 + (($dayNumber * 7) % 8)) *
            $seasonFactor *
            $weekendFactor
        )
    );

    $completed = max(
        0,
        (int)round($reservations * (0.58 + (($dayNumber % 4) * 0.06)))
    );

    $averageRoomRate = 190 + (($dayNumber * 13) % 105);
    $otherRevenue = $completed * (18 + (($dayNumber % 5) * 9));
    $gross = ($completed * $averageRoomRate) + $otherRevenue;
    $discount = round($gross * (0.025 + (($dayNumber % 3) * 0.005)), 2);

    $previousRevenue += max(0, $gross - $discount);
    $previousReservations += $reservations;

    $previousOccupancy += min(
        100,
        max(
            15,
            (int)round(
                38 +
                (($dayNumber * 11) % 43) *
                $seasonFactor *
                ($weekendFactor > 1 ? 1.05 : 1)
            )
        )
    );
}

$previousAverageOccupancy = $daysInRange > 0
    ? $previousOccupancy / $daysInRange
    : 0;

$revenueChange = percentageChange($netRevenue, $previousRevenue);

$reservationChange = percentageChange(
    $totalReservations,
    $previousReservations
);

$occupancyChange = $averageOccupancy - $previousAverageOccupancy;

/*
|--------------------------------------------------------------------------
| Revenue and Reservation Chart Buckets
|--------------------------------------------------------------------------
| Display up to 12 chart points to keep longer reports readable.
*/

$bucketCount = min(12, $daysInRange);
$buckets = [];

for ($i = 0; $i < $bucketCount; $i++) {
    $buckets[] = [
        'revenue' => 0,
        'reservations' => 0,
        'occupancy_sum' => 0,
        'days' => 0,
        'start' => null,
        'end' => null
    ];
}

foreach ($dailyData as $date => $row) {
    $dayOffset = (int)floor(
        (strtotime($date) - $startTimestamp) / 86400
    );

    $bucketIndex = min(
        $bucketCount - 1,
        (int)floor($dayOffset * $bucketCount / $daysInRange)
    );

    $buckets[$bucketIndex]['revenue'] += $row['net_revenue'];
    $buckets[$bucketIndex]['reservations'] += $row['reservations'];
    $buckets[$bucketIndex]['occupancy_sum'] += $row['occupancy'];
    $buckets[$bucketIndex]['days']++;

    if ($buckets[$bucketIndex]['start'] === null) {
        $buckets[$bucketIndex]['start'] = $date;
    }

    $buckets[$bucketIndex]['end'] = $date;
}

$chartLabels = [];
$chartRevenue = [];
$chartReservations = [];
$chartOccupancy = [];

foreach ($buckets as $bucket) {
    if ($bucket['start'] === null) {
        continue;
    }

    if ($daysInRange <= 14) {
        $label = date('d M', strtotime($bucket['start']));
    } else {
        $label = date('d M', strtotime($bucket['start']));
        if ($bucket['start'] !== $bucket['end']) {
            $label .= ' - ' . date('d M', strtotime($bucket['end']));
        }
    }

    $chartLabels[] = $label;
    $chartRevenue[] = round($bucket['revenue'], 2);
    $chartReservations[] = $bucket['reservations'];

    $chartOccupancy[] = $bucket['days'] > 0
        ? round($bucket['occupancy_sum'] / $bucket['days'], 1)
        : 0;
}

/*
|--------------------------------------------------------------------------
| Room Performance
|--------------------------------------------------------------------------
*/

$roomPerformance = [];

foreach ($rooms as $index => $room) {
    $roomBookings = 0;
    $roomRevenue = 0;
    $roomOccupiedDays = 0;

    foreach ($dailyData as $date => $row) {
        $dayNumber = (int)date('z', strtotime($date));

        // Different sample booking activity for each room.
        $activity = (($dayNumber + (($index + 1) * 3)) % 7);

        if ($activity <= 3) {
            $roomBookings++;
            $roomRevenue += $room['price'];
        }

        if ($activity <= 2) {
            $roomOccupiedDays++;
        }
    }

    $roomOccupancy = $daysInRange > 0
        ? round(($roomOccupiedDays / $daysInRange) * 100, 1)
        : 0;

    $roomPerformance[] = [
        'number' => $room['number'],
        'name' => $room['name'],
        'category' => $room['category'],
        'price' => $room['price'],
        'bookings' => $roomBookings,
        'revenue' => $roomRevenue,
        'occupancy' => $roomOccupancy
    ];
}

usort($roomPerformance, function ($a, $b) {
    return $b['revenue'] <=> $a['revenue'];
});

$bestRoom = $roomPerformance[0] ?? null;

/*
|--------------------------------------------------------------------------
| Room Category Summary
|--------------------------------------------------------------------------
*/

$categorySummary = [];

foreach ($roomPerformance as $room) {
    $category = $room['category'];

    if (!isset($categorySummary[$category])) {
        $categorySummary[$category] = [
            'category' => $category,
            'rooms' => 0,
            'bookings' => 0,
            'revenue' => 0,
            'occupancy_sum' => 0
        ];
    }

    $categorySummary[$category]['rooms']++;
    $categorySummary[$category]['bookings'] += $room['bookings'];
    $categorySummary[$category]['revenue'] += $room['revenue'];
    $categorySummary[$category]['occupancy_sum'] += $room['occupancy'];
}

foreach ($categorySummary as &$categoryRow) {
    $categoryRow['occupancy'] = $categoryRow['rooms'] > 0
        ? round($categoryRow['occupancy_sum'] / $categoryRow['rooms'], 1)
        : 0;
}
unset($categoryRow);

uasort($categorySummary, function ($a, $b) {
    return $b['revenue'] <=> $a['revenue'];
});

/*
|--------------------------------------------------------------------------
| Payment Summary
|--------------------------------------------------------------------------
*/

$pendingPayments = max(0, $netRevenue - $paymentsReceived);

$paymentMethods = [
    [
        'name' => 'Card Payments',
        'percentage' => 52,
        'color' => '#197b91'
    ],
    [
        'name' => 'Bank Transfers',
        'percentage' => 28,
        'color' => '#c9a45c'
    ],
    [
        'name' => 'Cash Payments',
        'percentage' => 20,
        'color' => '#23855b'
    ],
];

$paymentMethodAmounts = [];

foreach ($paymentMethods as $method) {
    $paymentMethodAmounts[] = [
        'name' => $method['name'],
        'percentage' => $method['percentage'],
        'amount' => round(
            $paymentsReceived * ($method['percentage'] / 100),
            2
        ),
        'color' => $method['color']
    ];
}

/*
|--------------------------------------------------------------------------
| CSV Export
|--------------------------------------------------------------------------
*/

if (isset($_GET['export']) && $_GET['export'] === 'csv') {
    header('Content-Type: text/csv; charset=utf-8');
    header(
        'Content-Disposition: attachment; filename="azure_bay_report_' .
        $startDate . '_to_' . $endDate . '.csv"'
    );

    $output = fopen('php://output', 'w');

    // UTF-8 BOM helps spreadsheet applications display the file correctly.
    fwrite($output, "\xEF\xBB\xBF");

    fputcsv($output, ['Azure Bay Resort - Management Report']);
    fputcsv($output, ['Report Start', $startDate]);
    fputcsv($output, ['Report End', $endDate]);
    fputcsv($output, ['Data Type', 'Date / Room', 'Metric', 'Value']);

    fputcsv($output, [
        'Summary',
        'Total',
        'Reservations',
        $totalReservations
    ]);

    fputcsv($output, [
        'Summary',
        'Total',
        'Completed Reservations',
        $completedReservations
    ]);

    fputcsv($output, [
        'Summary',
        'Total',
        'Cancelled Reservations',
        $cancelledReservations
    ]);

    fputcsv($output, [
        'Summary',
        'Total',
        'Gross Revenue',
        number_format($grossRevenue, 2, '.', '')
    ]);

    fputcsv($output, [
        'Summary',
        'Total',
        'Discounts',
        number_format($totalDiscounts, 2, '.', '')
    ]);

    fputcsv($output, [
        'Summary',
        'Total',
        'Net Revenue',
        number_format($netRevenue, 2, '.', '')
    ]);

    fputcsv($output, [
        'Summary',
        'Total',
        'Payments Received',
        number_format($paymentsReceived, 2, '.', '')
    ]);

    fputcsv($output, [
        'Summary',
        'Total',
        'Average Occupancy (%)',
        $averageOccupancy
    ]);

    fputcsv($output, []);
    fputcsv($output, [
        'Daily / Bucket Data',
        'Start Date',
        'Reservations',
        'Gross Revenue',
        'Discounts',
        'Net Revenue',
        'Payments Received',
        'Occupancy (%)'
    ]);

    foreach ($dailyData as $row) {
        fputcsv($output, [
            'Daily',
            $row['date'],
            $row['reservations'],
            number_format($row['gross_revenue'], 2, '.', ''),
            number_format($row['discounts'], 2, '.', ''),
            number_format($row['net_revenue'], 2, '.', ''),
            number_format($row['payments_received'], 2, '.', ''),
            $row['occupancy']
        ]);
    }

    fputcsv($output, []);
    fputcsv($output, [
        'Room Performance',
        'Room Number',
        'Room Name',
        'Category',
        'Nightly Rate',
        'Sample Bookings',
        'Sample Revenue',
        'Sample Occupancy (%)'
    ]);

    foreach ($roomPerformance as $room) {
        fputcsv($output, [
            'Room',
            csvSafe($room['number']),
            csvSafe($room['name']),
            csvSafe($room['category']),
            number_format($room['price'], 2, '.', ''),
            $room['bookings'],
            number_format($room['revenue'], 2, '.', ''),
            $room['occupancy']
        ]);
    }

    fclose($output);
    exit;
}

$exportParams = http_build_query([
    'range' => $range,
    'start_date' => $startDate,
    'end_date' => $endDate,
    'export' => 'csv'
]);

$periodLabel = date('d M Y', strtotime($startDate)) .
    ' – ' . date('d M Y', strtotime($endDate));

$chartData = [
    'labels' => $chartLabels,
    'revenue' => $chartRevenue,
    'reservations' => $chartReservations,
    'occupancy' => $chartOccupancy
];

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Reports & Analytics | Azure Bay Resort Admin</title>

    <link rel="stylesheet" href="../../../assets/bootstrap/bootstrap.min.css">

    <style>
        :root {
            --navy: #123247;
            --navy-dark: #0b2536;
            --ocean: #197b91;
            --ocean-light: #eaf5f7;
            --gold: #c9a45c;
            --cream: #fbf8f1;
            --text: #34495e;
            --muted: #81909d;
            --border: #e6edf0;
            --green: #23855b;
            --red: #c84d50;
            --orange: #d18a25;
            --purple: #7857ad;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: #f4f7f9;
            color: var(--text);
            font-family: "Trebuchet MS", Arial, sans-serif;
            font-size: 14px;
        }

        a {
            text-decoration: none;
        }

        .sidebar {
            width: 250px;
            position: fixed;
            inset: 0 auto 0 0;
            z-index: 1000;
            overflow-y: auto;
            background: var(--navy-dark);
            color: #fff;
            padding: 25px 17px;
        }

        .brand {
            padding: 0 12px 27px;
            border-bottom: 1px solid rgba(255,255,255,.12);
            margin-bottom: 25px;
        }

        .brand-mark {
            width: 43px;
            height: 43px;
            border-radius: 14px;
            background: var(--gold);
            display: inline-flex;
            justify-content: center;
            align-items: center;
            color: var(--navy-dark);
            font-size: 23px;
            margin-right: 10px;
            vertical-align: middle;
        }

        .brand-name {
            display: inline-block;
            vertical-align: middle;
            font-size: 19px;
            font-weight: bold;
            line-height: 1.2;
        }

        .brand-name small {
            display: block;
            color: #b8c8d1;
            font-size: 10px;
            font-weight: normal;
            letter-spacing: 2px;
            margin-top: 5px;
        }

        .menu-label {
            font-size: 10px;
            color: #8299a7;
            text-transform: uppercase;
            letter-spacing: 1.8px;
            padding: 0 13px;
            margin: 22px 0 9px;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 13px;
            border-radius: 9px;
            margin: 4px 0;
            color: #c8d5dc;
            font-size: 13px;
            transition: .2s;
        }

        .sidebar-link:hover,
        .sidebar-link.active {
            color: #fff;
            background: rgba(201,164,92,.18);
        }

        .sidebar-link.active {
            border-left: 3px solid var(--gold);
            padding-left: 10px;
        }

        .sidebar-link .menu-icon {
            font-size: 17px;
            width: 22px;
            text-align: center;
        }

        .sidebar-footer {
            margin: 30px 5px 0;
            padding: 16px;
            border: 1px solid rgba(255,255,255,.12);
            border-radius: 12px;
            color: #bdcbd3;
            font-size: 11px;
            line-height: 1.7;
        }

        .main {
            margin-left: 250px;
            min-height: 100vh;
        }

        .topbar {
            min-height: 78px;
            background: #fff;
            border-bottom: 1px solid var(--border);
            padding: 17px 30px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 15px;
        }

        .topbar small {
            color: var(--muted);
        }

        .admin-avatar {
            width: 39px;
            height: 39px;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            background: var(--ocean-light);
            color: var(--ocean);
            font-weight: bold;
        }

        .content {
            padding: 29px;
            max-width: 1650px;
            margin: auto;
        }

        .page-title {
            font-size: 27px;
            color: var(--navy);
            font-weight: bold;
            margin-bottom: 7px;
        }

        .page-subtitle {
            color: var(--muted);
            margin-bottom: 25px;
        }

        .gold-text {
            color: var(--gold);
        }

        .panel {
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 15px;
            padding: 22px;
            margin-bottom: 23px;
            box-shadow: 0 4px 15px rgba(25,54,72,.025);
        }

        .panel-title {
            font-size: 17px;
            color: var(--navy);
            font-weight: bold;
            margin-bottom: 5px;
        }

        .panel-subtitle {
            color: var(--muted);
            font-size: 12px;
        }

        .stat-card {
            border-radius: 14px;
            background: #fff;
            border: 1px solid var(--border);
            padding: 19px;
            height: 100%;
            box-shadow: 0 4px 15px rgba(25,54,72,.025);
        }

        .stat-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 10px;
            margin-bottom: 14px;
        }

        .stat-icon {
            width: 43px;
            height: 43px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
            font-size: 20px;
            flex-shrink: 0;
        }

        .icon-blue {
            color: var(--ocean);
            background: #eaf5f7;
        }

        .icon-green {
            color: var(--green);
            background: #e8f6ee;
        }

        .icon-orange {
            color: var(--orange);
            background: #fff4df;
        }

        .icon-gold {
            color: #9b762f;
            background: #f9f1df;
        }

        .stat-number {
            font-size: 27px;
            font-weight: bold;
            color: var(--navy);
            line-height: 1.2;
            overflow-wrap: anywhere;
        }

        .stat-label {
            color: var(--muted);
            font-size: 12px;
            margin-top: 7px;
        }

        .stat-foot {
            display: flex;
            align-items: center;
            gap: 5px;
            margin-top: 13px;
            font-size: 10px;
            flex-wrap: wrap;
        }

        .trend-up {
            color: var(--green);
            background: #e8f6ee;
            padding: 4px 7px;
            border-radius: 20px;
            font-weight: bold;
        }

        .trend-down {
            color: var(--red);
            background: #fff0ef;
            padding: 4px 7px;
            border-radius: 20px;
            font-weight: bold;
        }

        .trend-neutral {
            color: var(--muted);
            background: #f0f3f5;
            padding: 4px 7px;
            border-radius: 20px;
            font-weight: bold;
        }

        .btn-ocean {
            background: var(--navy);
            color: #fff;
            border: 1px solid var(--navy);
            border-radius: 8px;
            padding: 10px 16px;
            font-size: 12px;
            font-weight: bold;
        }

        .btn-ocean:hover {
            background: var(--ocean);
            color: #fff;
            border-color: var(--ocean);
        }

        .btn-gold {
            background: var(--gold);
            color: var(--navy-dark);
            border: 1px solid var(--gold);
            border-radius: 8px;
            padding: 10px 15px;
            font-size: 12px;
            font-weight: bold;
        }

        .btn-gold:hover {
            background: #b78f43;
            color: #fff;
        }

        .btn-light-custom {
            border: 1px solid var(--border);
            color: var(--text);
            background: #fff;
            border-radius: 8px;
            padding: 9px 13px;
            font-size: 12px;
        }

        .btn-light-custom:hover {
            background: var(--cream);
            border-color: var(--gold);
        }

        .form-label {
            font-size: 12px;
            font-weight: bold;
            color: var(--navy);
            margin-bottom: 7px;
        }

        .form-control,
        .form-select {
            border: 1px solid #dfe7eb;
            border-radius: 8px;
            font-size: 12px;
            min-height: 42px;
            color: var(--text);
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--ocean);
            box-shadow: 0 0 0 .2rem rgba(25,123,145,.1);
        }

        .date-label {
            display: inline-flex;
            align-items: center;
            gap: 9px;
            background: var(--cream);
            border: 1px solid #eee2c8;
            color: var(--navy);
            border-radius: 10px;
            padding: 11px 14px;
            font-size: 12px;
            font-weight: bold;
        }

        .report-table {
            vertical-align: middle;
            margin-bottom: 0;
        }

        .report-table thead th {
            background: #f7fafb;
            color: #6e818d;
            border-bottom: 1px solid var(--border);
            padding: 14px 13px;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: .6px;
            white-space: nowrap;
        }

        .report-table tbody td {
            padding: 14px 13px;
            border-color: #edf1f3;
            font-size: 12px;
        }

        .room-name {
            font-weight: bold;
            color: var(--navy);
            margin-bottom: 4px;
        }

        .room-number {
            font-size: 11px;
            color: var(--muted);
        }

        .rank-number {
            width: 30px;
            height: 30px;
            display: inline-flex;
            justify-content: center;
            align-items: center;
            border-radius: 9px;
            background: var(--ocean-light);
            color: var(--ocean);
            font-weight: bold;
            font-size: 11px;
        }

        .progress {
            height: 7px;
            border-radius: 20px;
            background: #edf1f3;
        }

        .progress-bar {
            border-radius: 20px;
            background: var(--ocean);
        }

        .chart-wrap {
            position: relative;
            height: 300px;
            width: 100%;
        }

        .chart-wrap canvas {
            display: block;
            width: 100%;
            height: 100%;
        }

        .chart-legend {
            display: flex;
            gap: 18px;
            flex-wrap: wrap;
            margin-top: 16px;
            font-size: 11px;
            color: var(--muted);
        }

        .legend-item {
            display: inline-flex;
            align-items: center;
            gap: 7px;
        }

        .legend-dot {
            width: 9px;
            height: 9px;
            border-radius: 50%;
        }

        .payment-row {
            margin-bottom: 22px;
        }

        .payment-row:last-child {
            margin-bottom: 0;
        }

        .payment-bar {
            height: 9px;
            border-radius: 20px;
            background: #edf1f3;
            overflow: hidden;
            margin-top: 9px;
        }

        .payment-bar span {
            display: block;
            height: 100%;
            border-radius: 20px;
        }

        .highlight-box {
            background: var(--cream);
            border: 1px solid #eee2c8;
            border-radius: 12px;
            padding: 17px;
        }

        .highlight-value {
            font-size: 22px;
            font-weight: bold;
            color: var(--navy);
        }

        .small-muted {
            font-size: 11px;
            color: var(--muted);
        }

        .footer {
            padding: 20px 30px;
            color: var(--muted);
            font-size: 11px;
            border-top: 1px solid var(--border);
            background: #fff;
        }

        .empty-state {
            padding: 30px 10px;
            text-align: center;
            color: var(--muted);
        }

        @media (max-width: 1100px) {
            .sidebar {
                width: 215px;
            }

            .main {
                margin-left: 215px;
            }

            .content {
                padding: 22px;
            }

            .stat-number {
                font-size: 23px;
            }
        }

        @media (max-width: 767px) {
            .sidebar {
                position: relative;
                width: 100%;
                height: auto;
                padding: 15px;
            }

            .brand {
                padding-bottom: 15px;
                margin-bottom: 12px;
            }

            .menu-label,
            .sidebar-footer {
                display: none;
            }

            .sidebar nav {
                display: flex;
                flex-wrap: wrap;
                gap: 5px;
            }

            .sidebar-link {
                padding: 9px 10px;
                margin: 0;
                font-size: 11px;
            }

            .sidebar-link .menu-icon {
                width: auto;
            }

            .main {
                margin-left: 0;
            }

            .topbar {
                padding: 15px 18px;
            }

            .content {
                padding: 18px 12px;
            }

            .page-title {
                font-size: 23px;
            }

            .panel {
                padding: 16px;
            }

            .chart-wrap {
                height: 250px;
            }

            .footer {
                padding: 18px;
            }
        }

        @media print {
            body {
                background: #fff;
            }

            .sidebar,
            .topbar,
            .no-print,
            .footer {
                display: none !important;
            }

            .main {
                margin: 0;
            }

            .content {
                padding: 0;
                max-width: none;
            }

            .panel,
            .stat-card {
                box-shadow: none;
                break-inside: avoid;
            }

            .chart-wrap {
                height: 250px;
            }
        }
    </style>
</head>

<body>

<!-- SIDEBAR -->
<aside class="sidebar">
    <div class="brand">
        <span class="brand-mark">AB</span>
        <span class="brand-name">
            AZURE BAY
            <small>RESORT ADMINISTRATION</small>
        </span>
    </div>

    <nav>
        <div class="menu-label">Main Menu</div>

        <a href="index.php" class="sidebar-link">
            <span class="menu-icon">▦</span> Dashboard
        </a>

        <a href="rooms.php" class="sidebar-link">
            <span class="menu-icon">⌂</span> Room Management
        </a>

        <a href="availability.php" class="sidebar-link">
            <span class="menu-icon">▦</span> Room Availability
        </a>

        <a href="reservations.php" class="sidebar-link">
            <span class="menu-icon">▣</span> Reservations
        </a>

        <a href="customers.php" class="sidebar-link">
            <span class="menu-icon">♙</span> Customers
        </a>

        <div class="menu-label">Hotel Operations</div>

        <a href="checkin_checkout.php" class="sidebar-link">
            <span class="menu-icon">↔</span> Check-in / Check-out
        </a>

        <a href="payments.php" class="sidebar-link">
            <span class="menu-icon">$</span> Payments
        </a>

        <a href="discounts.php" class="sidebar-link">
            <span class="menu-icon">%</span> Discounts & Offers
        </a>

        <a href="guest_services.php" class="sidebar-link">
            <span class="menu-icon">♧</span> Guest Services
        </a>

        <div class="menu-label">Analytics</div>

        <a href="reports.php" class="sidebar-link active">
            <span class="menu-icon">▤</span> Revenue Reports
        </a>

        <a href="../index.php" class="sidebar-link">
            <span class="menu-icon">↗</span> View Resort Website
        </a>
    </nav>

    <div class="sidebar-footer">
        <span class="gold-text">●</span>
        <strong class="text-white">Administrator Demo</strong>
        <br>
        Reports and analytics for the Azure Bay Resort demonstration.
    </div>
</aside>

<!-- MAIN -->
<main class="main">

    <header class="topbar">
        <div>
            <div class="fw-bold" style="color:var(--navy);">
                Reports & Analytics
            </div>
            <small>Resort performance and financial overview</small>
        </div>

        <div class="d-flex align-items-center gap-3">
            <div class="text-end d-none d-sm-block">
                <div class="fw-bold" style="font-size:12px;color:var(--navy);">
                    Resort Administrator
                </div>
                <small>Management Reports</small>
            </div>

            <div class="admin-avatar">AB</div>
        </div>
    </header>

    <div class="content">

        <!-- HEADING -->
        <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4">
            <div>
                <div class="page-title">
                    Reports & <span class="gold-text">Analytics</span>
                </div>

                <p class="page-subtitle mb-0">
                    Understand resort revenue, occupancy, bookings and room performance.
                </p>
            </div>

            <div class="d-flex gap-2 flex-wrap no-print">
                <button type="button"
                        class="btn-light-custom"
                        onclick="window.print()">
                    Print Report
                </button>

                <a href="reports.php?<?= e($exportParams) ?>"
                   class="btn-gold">
                    Export CSV
                </a>
            </div>
        </div>

        <!-- FILTERS -->
        <section class="panel no-print">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
                <div>
                    <div class="panel-title">Report Filters</div>
                    <div class="panel-subtitle">
                        Select a period to update the displayed report.
                    </div>
                </div>

                <span class="date-label">
                    <?= e($periodLabel) ?>
                </span>
            </div>

            <form method="GET" action="reports.php" id="reportFilterForm">
                <div class="row g-3 align-items-end">

                    <div class="col-md-4 col-lg-3">
                        <label class="form-label" for="range">Reporting Period</label>

                        <select class="form-select" name="range" id="range">
                            <?php foreach ($rangeOptions as $key => $label): ?>
                                <option value="<?= e($key) ?>"
                                    <?= $range === $key ? 'selected' : '' ?>>
                                    <?= e($label) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-3 col-lg-3 custom-date-field">
                        <label class="form-label" for="start_date">Start Date</label>

                        <input type="date"
                               class="form-control"
                               name="start_date"
                               id="start_date"
                               value="<?= e($startDate) ?>"
                               max="<?= e($today) ?>">
                    </div>

                    <div class="col-md-3 col-lg-3 custom-date-field">
                        <label class="form-label" for="end_date">End Date</label>

                        <input type="date"
                               class="form-control"
                               name="end_date"
                               id="end_date"
                               value="<?= e($endDate) ?>"
                               max="<?= e($today) ?>">
                    </div>

                    <div class="col-md-2 col-lg-3">
                        <button type="submit" class="btn-ocean w-100">
                            Apply Filters
                        </button>
                    </div>

                </div>

                <div id="dateError"
                     class="text-danger mt-2"
                     style="font-size:11px;display:none;">
                    Please select a valid date range.
                </div>
            </form>
        </section>

        <!-- SUMMARY CARDS -->
        <div class="row g-3 mb-4">

            <div class="col-6 col-xl-3">
                <div class="stat-card">
                    <div class="stat-top">
                        <div class="stat-icon icon-blue">$</div>
                        <span class="small-muted">NET REVENUE</span>
                    </div>

                    <div class="stat-number"><?= money($netRevenue) ?></div>
                    <div class="stat-label">Revenue after discounts</div>

                    <?php
                    $revenueTrendClass = $revenueChange > 0
                        ? 'trend-up'
                        : ($revenueChange < 0 ? 'trend-down' : 'trend-neutral');
                    ?>

                    <div class="stat-foot">
                        <span class="<?= e($revenueTrendClass) ?>">
                            <?= e(signedPercentage($revenueChange)) ?>
                        </span>
                        <span class="text-muted">vs previous period</span>
                    </div>
                </div>
            </div>

            <div class="col-6 col-xl-3">
                <div class="stat-card">
                    <div class="stat-top">
                        <div class="stat-icon icon-green">▣</div>
                        <span class="small-muted">RESERVATIONS</span>
                    </div>

                    <div class="stat-number">
                        <?= number_format($totalReservations) ?>
                    </div>

                    <div class="stat-label">Reservations in selected period</div>

                    <?php
                    $reservationTrendClass = $reservationChange > 0
                        ? 'trend-up'
                        : ($reservationChange < 0 ? 'trend-down' : 'trend-neutral');
                    ?>

                    <div class="stat-foot">
                        <span class="<?= e($reservationTrendClass) ?>">
                            <?= e(signedPercentage($reservationChange)) ?>
                        </span>
                        <span class="text-muted">vs previous period</span>
                    </div>
                </div>
            </div>

            <div class="col-6 col-xl-3">
                <div class="stat-card">
                    <div class="stat-top">
                        <div class="stat-icon icon-orange">◷</div>
                        <span class="small-muted">AVG. OCCUPANCY</span>
                    </div>

                    <div class="stat-number">
                        <?= number_format($averageOccupancy, 1) ?>%
                    </div>

                    <div class="stat-label">Average daily room occupancy</div>

                    <div class="stat-foot">
                        <span class="<?= $occupancyChange >= 0 ? 'trend-up' : 'trend-down' ?>">
                            <?= $occupancyChange > 0 ? '+' : '' ?>
                            <?= number_format($occupancyChange, 1) ?> pp
                        </span>
                        <span class="text-muted">vs previous period</span>
                    </div>
                </div>
            </div>

            <div class="col-6 col-xl-3">
                <div class="stat-card">
                    <div class="stat-top">
                        <div class="stat-icon icon-gold">✓</div>
                        <span class="small-muted">COLLECTED</span>
                    </div>

                    <div class="stat-number">
                        <?= money($paymentsReceived) ?>
                    </div>

                    <div class="stat-label">Sample payments received</div>

                    <div class="stat-foot">
                        <span class="trend-neutral">
                            <?= number_format($collectionRate, 1) ?>%
                        </span>
                        <span class="text-muted">of net revenue</span>
                    </div>
                </div>
            </div>

        </div>

        <!-- REVENUE CHART -->
        <section class="panel">
            <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4">
                <div>
                    <div class="panel-title">Revenue Performance</div>
                    <div class="panel-subtitle">
                        Revenue trend across <?= (int)$bucketCount ?> reporting interval(s).
                    </div>
                </div>

                <div class="text-end">
                    <div class="small-muted">TOTAL NET REVENUE</div>
                    <div class="fw-bold fs-4" style="color:var(--navy);">
                        <?= money($netRevenue) ?>
                    </div>
                </div>
            </div>

            <div class="chart-wrap">
                <canvas id="revenueChart"
                        role="img"
                        aria-label="Revenue performance chart"></canvas>
            </div>

            <div class="chart-legend">
                <span class="legend-item">
                    <span class="legend-dot" style="background:var(--ocean);"></span>
                    Net revenue
                </span>

                <span class="legend-item">
                    <span class="legend-dot" style="background:var(--gold);"></span>
                    Revenue trend
                </span>
            </div>
        </section>

        <!-- BOOKING CHART AND FINANCIAL SUMMARY -->
        <div class="row g-3 mb-4">

            <div class="col-xl-7">
                <section class="panel h-100 mb-0">
                    <div class="panel-title">Reservation Activity</div>

                    <div class="panel-subtitle mb-4">
                        Reservation volume across the selected period.
                    </div>

                    <div class="chart-wrap">
                        <canvas id="reservationChart"
                                role="img"
                                aria-label="Reservation activity chart"></canvas>
                    </div>

                    <div class="chart-legend">
                        <span class="legend-item">
                            <span class="legend-dot" style="background:var(--ocean);"></span>
                            Reservations
                        </span>
                    </div>
                </section>
            </div>

            <div class="col-xl-5">
                <section class="panel h-100 mb-0">
                    <div class="panel-title">Financial Summary</div>

                    <div class="panel-subtitle mb-4">
                        Revenue and collection breakdown.
                    </div>

                    <div class="d-flex justify-content-between align-items-center py-3 border-bottom">
                        <span class="small-muted">Gross revenue</span>
                        <strong style="color:var(--navy);">
                            <?= money($grossRevenue) ?>
                        </strong>
                    </div>

                    <div class="d-flex justify-content-between align-items-center py-3 border-bottom">
                        <span class="small-muted">Discounts applied</span>
                        <strong style="color:var(--red);">
                            −<?= money($totalDiscounts) ?>
                        </strong>
                    </div>

                    <div class="d-flex justify-content-between align-items-center py-3 border-bottom">
                        <span class="small-muted">Net revenue</span>
                        <strong style="color:var(--ocean);">
                            <?= money($netRevenue) ?>
                        </strong>
                    </div>

                    <div class="d-flex justify-content-between align-items-center py-3 border-bottom">
                        <span class="small-muted">Payments received</span>
                        <strong style="color:var(--green);">
                            <?= money($paymentsReceived) ?>
                        </strong>
                    </div>

                    <div class="d-flex justify-content-between align-items-center py-3">
                        <span class="small-muted">Estimated outstanding</span>
                        <strong style="color:var(--orange);">
                            <?= money($pendingPayments) ?>
                        </strong>
                    </div>

                    <div class="highlight-box mt-3">
                        <div class="small-muted mb-1">
                            AVERAGE DAILY REVENUE
                        </div>

                        <div class="highlight-value">
                            <?= money($averageDailyRevenue) ?>
                        </div>
                    </div>
                </section>
            </div>

        </div>

        <!-- OCCUPANCY AND RESERVATION STATUS -->
        <div class="row g-3 mb-4">

            <div class="col-xl-6">
                <section class="panel h-100 mb-0">
                    <div class="panel-title">Occupancy Trend</div>

                    <div class="panel-subtitle mb-4">
                        Average occupancy percentage across each reporting interval.
                    </div>

                    <div class="chart-wrap">
                        <canvas id="occupancyChart"
                                role="img"
                                aria-label="Occupancy trend chart"></canvas>
                    </div>

                    <div class="highlight-box mt-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="small-muted">PEAK SAMPLE OCCUPANCY</div>
                                <div class="highlight-value">
                                    <?= $peakOccupancy ?>%
                                </div>
                            </div>

                            <div class="text-end">
                                <div class="small-muted">AVERAGE OCCUPANCY</div>
                                <div class="highlight-value">
                                    <?= number_format($averageOccupancy, 1) ?>%
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>

            <div class="col-xl-6">
                <section class="panel h-100 mb-0">
                    <div class="panel-title">Reservation Status Summary</div>

                    <div class="panel-subtitle mb-4">
                        Demonstration breakdown for the selected period.
                    </div>

                    <?php
                    $reservationStatuses = [
                        [
                            'label' => 'Completed',
                            'count' => $completedReservations,
                            'color' => '#23855b'
                        ],
                        [
                            'label' => 'Pending',
                            'count' => $pendingReservations,
                            'color' => '#d18a25'
                        ],
                        [
                            'label' => 'Cancelled',
                            'count' => $cancelledReservations,
                            'color' => '#c84d50'
                        ]
                    ];
                    ?>

                    <?php foreach ($reservationStatuses as $item): ?>
                        <?php
                        $statusPercentage = $totalReservations > 0
                            ? round(($item['count'] / $totalReservations) * 100, 1)
                            : 0;
                        ?>

                        <div class="mb-4">
                            <div class="d-flex justify-content-between mb-2">
                                <span style="font-size:12px;font-weight:bold;color:var(--navy);">
                                    <?= e($item['label']) ?>
                                </span>

                                <span class="small-muted">
                                    <?= number_format($item['count']) ?>
                                    (<?= number_format($statusPercentage, 1) ?>%)
                                </span>
                            </div>

                            <div class="progress">
                                <div class="progress-bar"
                                     style="width:<?= $statusPercentage ?>%;background:<?= e($item['color']) ?>;">
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>

                    <div class="row g-3 mt-2">
                        <div class="col-6">
                            <div class="highlight-box">
                                <div class="small-muted">COMPLETION RATE</div>
                                <div class="highlight-value">
                                    <?= number_format($completionRate, 1) ?>%
                                </div>
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="highlight-box">
                                <div class="small-muted">CANCELLATION RATE</div>
                                <div class="highlight-value">
                                    <?= number_format($cancellationRate, 1) ?>%
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>

        </div>

        <!-- PAYMENT METHODS -->
        <section class="panel">
            <div class="panel-title">Payment Method Analysis</div>

            <div class="panel-subtitle mb-4">
                Sample allocation of collected payments by payment method.
            </div>

            <div class="row g-4">
                <?php foreach ($paymentMethodAmounts as $method): ?>
                    <div class="col-md-4">
                        <div class="payment-row">
                            <div class="d-flex justify-content-between align-items-center gap-2">
                                <span style="font-size:12px;font-weight:bold;color:var(--navy);">
                                    <?= e($method['name']) ?>
                                </span>

                                <span style="font-size:12px;color:var(--navy);font-weight:bold;">
                                    <?= money($method['amount']) ?>
                                </span>
                            </div>

                            <div class="payment-bar">
                                <span style="width:<?= (int)$method['percentage'] ?>%;background:<?= e($method['color']) ?>;"></span>
                            </div>

                            <div class="small-muted mt-2">
                                <?= (int)$method['percentage'] ?>% of collected payments
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>

        <!-- ROOM PERFORMANCE -->
        <section class="panel">
            <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4">
                <div>
                    <div class="panel-title">Room Performance Report</div>

                    <div class="panel-subtitle">
                        Compare sample booking activity, revenue and occupancy by room.
                    </div>
                </div>

                <?php if ($bestRoom): ?>
                    <div class="highlight-box">
                        <div class="small-muted">TOP ROOM BY SAMPLE REVENUE</div>
                        <div class="fw-bold" style="color:var(--navy);">
                            <?= e($bestRoom['name']) ?>
                        </div>
                        <div class="small-muted">
                            <?= money($bestRoom['revenue']) ?> sample revenue
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <div class="table-responsive">
                <table class="table report-table">
                    <thead>
                    <tr>
                        <th>Rank</th>
                        <th>Room</th>
                        <th>Category</th>
                        <th>Nightly Rate</th>
                        <th>Sample Bookings</th>
                        <th>Sample Revenue</th>
                        <th>Sample Occupancy</th>
                    </tr>
                    </thead>

                    <tbody>
                    <?php foreach ($roomPerformance as $index => $room): ?>
                        <tr>
                            <td>
                                <span class="rank-number">
                                    <?= $index + 1 ?>
                                </span>
                            </td>

                            <td>
                                <div class="room-name">
                                    <?= e($room['name']) ?>
                                </div>

                                <div class="room-number">
                                    Room <?= e($room['number']) ?>
                                </div>
                            </td>

                            <td><?= e($room['category']) ?></td>

                            <td><?= money($room['price']) ?></td>

                            <td>
                                <strong style="color:var(--navy);">
                                    <?= number_format($room['bookings']) ?>
                                </strong>
                            </td>

                            <td>
                                <strong style="color:var(--ocean);">
                                    <?= money($room['revenue']) ?>
                                </strong>
                            </td>

                            <td style="min-width:140px;">
                                <div class="d-flex justify-content-between mb-2">
                                    <span style="font-size:11px;font-weight:bold;">
                                        <?= number_format($room['occupancy'], 1) ?>%
                                    </span>
                                </div>

                                <div class="progress">
                                    <div class="progress-bar"
                                         style="width:<?= min(100, $room['occupancy']) ?>%;"></div>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="small-muted mt-3">
                Room revenue and occupancy are illustrative estimates generated
                for this demo, not actual financial transactions or reservation records.
            </div>
        </section>

        <!-- CATEGORY PERFORMANCE -->
        <section class="panel">
            <div class="panel-title">Room Category Performance</div>

            <div class="panel-subtitle mb-4">
                Compare the sample performance of each room category.
            </div>

            <div class="table-responsive">
                <table class="table report-table">
                    <thead>
                    <tr>
                        <th>Room Category</th>
                        <th>Rooms</th>
                        <th>Sample Bookings</th>
                        <th>Sample Revenue</th>
                        <th>Average Sample Occupancy</th>
                    </tr>
                    </thead>

                    <tbody>
                    <?php foreach ($categorySummary as $category): ?>
                        <tr>
                            <td>
                                <strong style="color:var(--navy);">
                                    <?= e($category['category']) ?>
                                </strong>
                            </td>

                            <td><?= (int)$category['rooms'] ?></td>

                            <td><?= number_format($category['bookings']) ?></td>

                            <td>
                                <strong style="color:var(--ocean);">
                                    <?= money($category['revenue']) ?>
                                </strong>
                            </td>

                            <td>
                                <?= number_format($category['occupancy'], 1) ?>%
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </section>

        <!-- REPORT NOTES -->
        <section class="panel">
            <div class="d-flex gap-3 align-items-start">
                <div class="stat-icon icon-blue flex-shrink-0">i</div>

                <div>
                    <div class="panel-title">About This Report</div>

                    <p class="panel-subtitle mb-2">
                        This page is intended to demonstrate resort management
                        reporting features for the Azure Bay Resort portfolio project.
                    </p>

                    <ul class="mb-0 ps-3"
                        style="font-size:12px;line-height:2;color:var(--text);">
                        <li>Revenue figures are generated sample values and do not represent actual resort income.</li>
                        <li>Occupancy, room performance, reservation statuses and payment methods are illustrative.</li>
                        <li>Date filters, charts, printing and CSV export work locally.</li>
                        <li>In a live application, reports should use database records from reservations, payments, discounts and room inventory.</li>
                        <li>Actual financial reports should distinguish bookings made, stays completed, revenue earned, payments received and outstanding balances.</li>
                    </ul>
                </div>
            </div>
        </section>

    </div>

    <footer class="footer d-flex justify-content-between flex-wrap gap-2">
        <span>© <?= date('Y') ?> Azure Bay Resort. All rights reserved.</span>
        <span>Reports & Analytics · Administrator Demo</span>
    </footer>

</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>


<script>
/*
|--------------------------------------------------------------------------
| Filter Form
|--------------------------------------------------------------------------
*/

const rangeSelect = document.getElementById('range');
const startInput = document.getElementById('start_date');
const endInput = document.getElementById('end_date');
const filterForm = document.getElementById('reportFilterForm');
const dateError = document.getElementById('dateError');

function updateDateFields() {
    const isCustom = rangeSelect.value === 'custom';

    document.querySelectorAll('.custom-date-field').forEach(function (field) {
        field.style.display = isCustom ? '' : 'none';
    });

    startInput.required = isCustom;
    endInput.required = isCustom;
}

rangeSelect.addEventListener('change', updateDateFields);

filterForm.addEventListener('submit', function (event) {
    dateError.style.display = 'none';

    if (rangeSelect.value === 'custom') {
        if (
            !startInput.value ||
            !endInput.value ||
            startInput.value > endInput.value ||
            endInput.value > '<?= e($today) ?>'
        ) {
            event.preventDefault();
            dateError.style.display = 'block';
        }
    }
});

updateDateFields();

/*
|--------------------------------------------------------------------------
| Local Canvas Chart Renderer
|--------------------------------------------------------------------------
| No external chart library or CDN is required.
*/

const reportData = <?= json_encode(
    $chartData,
    JSON_HEX_TAG |
    JSON_HEX_APOS |
    JSON_HEX_QUOT |
    JSON_HEX_AMP
) ?>;

function drawLineChart(canvasId, values, options) {
    const canvas = document.getElementById(canvasId);

    if (!canvas || !canvas.getContext) {
        return;
    }

    const context = canvas.getContext('2d');

    const dpr = window.devicePixelRatio || 1;
    const rect = canvas.getBoundingClientRect();

    if (rect.width <= 0 || rect.height <= 0) {
        return;
    }

    canvas.width = Math.round(rect.width * dpr);
    canvas.height = Math.round(rect.height * dpr);

    context.setTransform(dpr, 0, 0, dpr, 0, 0);

    const width = rect.width;
    const height = rect.height;

    context.clearRect(0, 0, width, height);

    const padding = {
        left: options.leftPadding || 55,
        right: 18,
        top: 22,
        bottom: 48
    };

    const plotWidth = width - padding.left - padding.right;
    const plotHeight = height - padding.top - padding.bottom;

    if (plotWidth <= 0 || plotHeight <= 0) {
        return;
    }

    const numericValues = values.map(function (value) {
        return Number(value) || 0;
    });

    const maxValue = options.maxValue !== undefined
        ? options.maxValue
        : Math.max(1, ...numericValues) * 1.15;

    const minValue = options.minValue || 0;
    const valueRange = Math.max(1, maxValue - minValue);

    context.font = '10px Trebuchet MS, Arial';
    context.textBaseline = 'middle';

    // Horizontal grid lines and labels.
    for (let i = 0; i <= 4; i++) {
        const fraction = i / 4;
        const y = padding.top + plotHeight * fraction;
        const value = maxValue - (valueRange * fraction);

        context.beginPath();
        context.strokeStyle = '#e9eff2';
        context.lineWidth = 1;
        context.moveTo(padding.left, y);
        context.lineTo(width - padding.right, y);
        context.stroke();

        context.fillStyle = '#81909d';
        context.textAlign = 'right';

        let label = options.formatY
            ? options.formatY(value)
            : Math.round(value).toString();

        context.fillText(label, padding.left - 9, y);
    }

    if (numericValues.length === 0) {
        context.fillStyle = '#81909d';
        context.textAlign = 'center';
        context.fillText(
            'No chart data available',
            padding.left + plotWidth / 2,
            padding.top + plotHeight / 2
        );
        return;
    }

    const points = numericValues.map(function (value, index) {
        const x = numericValues.length === 1
            ? padding.left + plotWidth / 2
            : padding.left + (index / (numericValues.length - 1)) * plotWidth;

        const y = padding.top +
            ((maxValue - value) / valueRange) * plotHeight;

        return {x: x, y: y, value: value};
    });

    // Fill area beneath the line.
    context.beginPath();
    context.moveTo(points[0].x, padding.top + plotHeight);

    points.forEach(function (point) {
        context.lineTo(point.x, point.y);
    });

    context.lineTo(points[points.length - 1].x, padding.top + plotHeight);
    context.closePath();

    context.fillStyle = options.fillColor || 'rgba(25,123,145,.10)';
    context.fill();

    // Draw line.
    context.beginPath();

    points.forEach(function (point, index) {
        if (index === 0) {
            context.moveTo(point.x, point.y);
        } else {
            context.lineTo(point.x, point.y);
        }
    });

    context.strokeStyle = options.color || '#197b91';
    context.lineWidth = 2.5;
    context.lineJoin = 'round';
    context.lineCap = 'round';
    context.stroke();

    // Points.
    points.forEach(function (point) {
        context.beginPath();
        context.arc(point.x, point.y, 3.5, 0, Math.PI * 2);
        context.fillStyle = options.pointColor || '#c9a45c';
        context.fill();

        context.beginPath();
        context.arc(point.x, point.y, 1.5, 0, Math.PI * 2);
        context.fillStyle = '#ffffff';
        context.fill();
    });

    // X-axis labels. Keep labels readable for larger date ranges.
    const labels = reportData.labels || [];
    const maxLabels = Math.min(labels.length, 7);
    const labelIndexes = new Set();

    if (labels.length === 1) {
        labelIndexes.add(0);
    } else {
        for (let i = 0; i < maxLabels; i++) {
            labelIndexes.add(
                Math.round(i * (labels.length - 1) / (maxLabels - 1))
            );
        }
    }

    context.fillStyle = '#81909d';
    context.textAlign = 'center';
    context.textBaseline = 'top';

    labelIndexes.forEach(function (index) {
        const point = points[index];

        if (!point) {
            return;
        }

        context.fillText(
            labels[index],
            point.x,
            padding.top + plotHeight + 13
        );
    });
}

function drawBarChart(canvasId, values, options) {
    const canvas = document.getElementById(canvasId);

    if (!canvas || !canvas.getContext) {
        return;
    }

    const context = canvas.getContext('2d');
    const dpr = window.devicePixelRatio || 1;
    const rect = canvas.getBoundingClientRect();

    if (rect.width <= 0 || rect.height <= 0) {
        return;
    }

    canvas.width = Math.round(rect.width * dpr);
    canvas.height = Math.round(rect.height * dpr);

    context.setTransform(dpr, 0, 0, dpr, 0, 0);

    const width = rect.width;
    const height = rect.height;

    context.clearRect(0, 0, width, height);

    const padding = {
        left: options.leftPadding || 48,
        right: 15,
        top: 22,
        bottom: 48
    };

    const plotWidth = width - padding.left - padding.right;
    const plotHeight = height - padding.top - padding.bottom;

    if (plotWidth <= 0 || plotHeight <= 0) {
        return;
    }

    const numericValues = values.map(function (value) {
        return Number(value) || 0;
    });

    const maxValue = options.maxValue !== undefined
        ? options.maxValue
        : Math.max(1, ...numericValues) * 1.15;

    const labels = reportData.labels || [];
    const slotWidth = plotWidth / Math.max(1, numericValues.length);
    const barWidth = Math.min(36, slotWidth * 0.58);

    context.font = '10px Trebuchet MS, Arial';
    context.textBaseline = 'middle';

    for (let i = 0; i <= 4; i++) {
        const fraction = i / 4;
        const y = padding.top + plotHeight * fraction;
        const value = maxValue * (1 - fraction);

        context.beginPath();
        context.strokeStyle = '#e9eff2';
        context.lineWidth = 1;
        context.moveTo(padding.left, y);
        context.lineTo(width - padding.right, y);
        context.stroke();

        context.fillStyle = '#81909d';
        context.textAlign = 'right';

        const label = options.formatY
            ? options.formatY(value)
            : Math.round(value).toString();

        context.fillText(label, padding.left - 8, y);
    }

    numericValues.forEach(function (value, index) {
        const x = padding.left + index * slotWidth +
            (slotWidth - barWidth) / 2;

        const barHeight = Math.max(
            0,
            (value / Math.max(1, maxValue)) * plotHeight
        );

        const y = padding.top + plotHeight - barHeight;

        context.fillStyle = options.color || '#197b91';

        const radius = Math.min(5, barWidth / 2);

        context.beginPath();
        context.moveTo(x, y + radius);
        context.arcTo(x, y, x + radius, y, radius);
        context.arcTo(x + barWidth, y, x + barWidth, y + radius, radius);
        context.lineTo(x + barWidth, padding.top + plotHeight);
        context.lineTo(x, padding.top + plotHeight);
        context.closePath();
        context.fill();
    });

    const maxLabels = Math.min(labels.length, 7);
    const labelIndexes = new Set();

    if (labels.length === 1) {
        labelIndexes.add(0);
    } else if (maxLabels > 1) {
        for (let i = 0; i < maxLabels; i++) {
            labelIndexes.add(
                Math.round(i * (labels.length - 1) / (maxLabels - 1))
            );
        }
    }

    context.fillStyle = '#81909d';
    context.textAlign = 'center';
    context.textBaseline = 'top';

    labelIndexes.forEach(function (index) {
        const x = padding.left + index * slotWidth + slotWidth / 2;

        context.fillText(
            labels[index],
            x,
            padding.top + plotHeight + 13
        );
    });
}

function renderReportCharts() {
    drawLineChart(
        'revenueChart',
        reportData.revenue,
        {
            color: '#197b91',
            pointColor: '#c9a45c',
            fillColor: 'rgba(25,123,145,.10)',
            leftPadding: 75,
            formatY: function (value) {
                if (value >= 1000000) {
                    return '$' + (value / 1000000).toFixed(1) + 'm';
                }

                if (value >= 1000) {
                    return '$' + (value / 1000).toFixed(1) + 'k';
                }

                return '$' + Math.round(value);
            }
        }
    );

    drawBarChart(
        'reservationChart',
        reportData.reservations,
        {
            color: '#197b91',
            leftPadding: 45,
            formatY: function (value) {
                return Math.round(value).toString();
            }
        }
    );

    drawLineChart(
        'occupancyChart',
        reportData.occupancy,
        {
            color: '#c9a45c',
            pointColor: '#197b91',
            fillColor: 'rgba(201,164,92,.12)',
            leftPadding: 48,
            maxValue: 100,
            formatY: function (value) {
                return Math.round(value) + '%';
            }
        }
    );
}

renderReportCharts();

let resizeTimer;

window.addEventListener('resize', function () {
    clearTimeout(resizeTimer);

    resizeTimer = setTimeout(function () {
        renderReportCharts();
    }, 150);
});

window.addEventListener('beforeprint', renderReportCharts);
</script>

</body>
</html>