
<?php
session_start();

/*
|--------------------------------------------------------------------------
| FitCore Admin - Reports & Analytics
|--------------------------------------------------------------------------
| This is a demonstration page using PHP session data and sample records.
| No database connection or real payment gateway is required.
|--------------------------------------------------------------------------
*/

date_default_timezone_set('Asia/Colombo');

function reportEscape($value): string
{
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

function reportMoney($amount): string
{
    return '$' . number_format((float)$amount, 2);
}

/*
|--------------------------------------------------------------------------
| Demo report period
|--------------------------------------------------------------------------
*/

$allowedPeriods = ['7', '30', '90', '180', '365'];

$period = (string)($_GET['period'] ?? '30');

if (!in_array($period, $allowedPeriods, true)) {
    $period = '30';
}

$periodDays = (int)$period;
$endDate = new DateTimeImmutable('today');
$startDate = $endDate->modify('-' . ($periodDays - 1) . ' days');

$startDateString = $startDate->format('Y-m-d');
$endDateString = $endDate->format('Y-m-d');

/*
|--------------------------------------------------------------------------
| Sample members
|--------------------------------------------------------------------------
*/

$members = [
    [
        'id' => 'FCM-1001',
        'name' => 'Olivia Bennett',
        'plan' => 'Premium',
        'status' => 'Active',
        'joined' => date('Y-m-d', strtotime('-12 days'))
    ],
    [
        'id' => 'FCM-1002',
        'name' => 'Ethan Brooks',
        'plan' => 'Pro',
        'status' => 'Active',
        'joined' => date('Y-m-d', strtotime('-20 days'))
    ],
    [
        'id' => 'FCM-1003',
        'name' => 'Sophia Mitchell',
        'plan' => 'Basic',
        'status' => 'Active',
        'joined' => date('Y-m-d', strtotime('-45 days'))
    ],
    [
        'id' => 'FCM-1004',
        'name' => 'Noah Wilson',
        'plan' => 'Premium',
        'status' => 'Active',
        'joined' => date('Y-m-d', strtotime('-65 days'))
    ],
    [
        'id' => 'FCM-1005',
        'name' => 'Ava Thompson',
        'plan' => 'Pro',
        'status' => 'Active',
        'joined' => date('Y-m-d', strtotime('-8 days'))
    ],
    [
        'id' => 'FCM-1006',
        'name' => 'Liam Anderson',
        'plan' => 'Basic',
        'status' => 'Suspended',
        'joined' => date('Y-m-d', strtotime('-100 days'))
    ],
    [
        'id' => 'FCM-1007',
        'name' => 'Isabella Clark',
        'plan' => 'Premium',
        'status' => 'Active',
        'joined' => date('Y-m-d', strtotime('-5 days'))
    ],
    [
        'id' => 'FCM-1008',
        'name' => 'James Parker',
        'plan' => 'Pro',
        'status' => 'Active',
        'joined' => date('Y-m-d', strtotime('-32 days'))
    ],
    [
        'id' => 'FCM-1009',
        'name' => 'Mia Robinson',
        'plan' => 'Basic',
        'status' => 'Expired',
        'joined' => date('Y-m-d', strtotime('-160 days'))
    ],
    [
        'id' => 'FCM-1010',
        'name' => 'Lucas Martin',
        'plan' => 'Premium',
        'status' => 'Expired',
        'joined' => date('Y-m-d', strtotime('-220 days'))
    ],
    [
        'id' => 'FCM-1011',
        'name' => 'Charlotte Davis',
        'plan' => 'Pro',
        'status' => 'Active',
        'joined' => date('Y-m-d', strtotime('-16 days'))
    ],
    [
        'id' => 'FCM-1012',
        'name' => 'Benjamin Harris',
        'plan' => 'Basic',
        'status' => 'Suspended',
        'joined' => date('Y-m-d', strtotime('-80 days'))
    ]
];

/*
|--------------------------------------------------------------------------
| Membership plan prices
|--------------------------------------------------------------------------
*/

$planPrices = [
    'Basic' => 20,
    'Pro' => 40,
    'Premium' => 70
];

/*
|--------------------------------------------------------------------------
| Sample payments
|--------------------------------------------------------------------------
*/

$payments = [
    [
        'id' => 'FCP-2026-1001',
        'member_id' => 'FCM-1001',
        'member' => 'Olivia Bennett',
        'plan' => 'Premium',
        'amount' => 70,
        'method' => 'Credit Card',
        'status' => 'Completed',
        'date' => date('Y-m-d', strtotime('-1 day'))
    ],
    [
        'id' => 'FCP-2026-1002',
        'member_id' => 'FCM-1002',
        'member' => 'Ethan Brooks',
        'plan' => 'Pro',
        'amount' => 40,
        'method' => 'Debit Card',
        'status' => 'Completed',
        'date' => date('Y-m-d', strtotime('-2 days'))
    ],
    [
        'id' => 'FCP-2026-1003',
        'member_id' => 'FCM-1003',
        'member' => 'Sophia Mitchell',
        'plan' => 'Basic',
        'amount' => 20,
        'method' => 'Cash',
        'status' => 'Pending',
        'date' => date('Y-m-d', strtotime('-3 days'))
    ],
    [
        'id' => 'FCP-2026-1004',
        'member_id' => 'FCM-1004',
        'member' => 'Noah Wilson',
        'plan' => 'Premium',
        'amount' => 70,
        'method' => 'Credit Card',
        'status' => 'Completed',
        'date' => date('Y-m-d', strtotime('-5 days'))
    ],
    [
        'id' => 'FCP-2026-1005',
        'member_id' => 'FCM-1005',
        'member' => 'Ava Thompson',
        'plan' => 'Pro',
        'amount' => 40,
        'method' => 'Bank Transfer',
        'status' => 'Pending',
        'date' => date('Y-m-d', strtotime('-8 days'))
    ],
    [
        'id' => 'FCP-2026-1006',
        'member_id' => 'FCM-1006',
        'member' => 'Liam Anderson',
        'plan' => 'Basic',
        'amount' => 20,
        'method' => 'Debit Card',
        'status' => 'Failed',
        'date' => date('Y-m-d', strtotime('-10 days'))
    ],
    [
        'id' => 'FCP-2026-1007',
        'member_id' => 'FCM-1007',
        'member' => 'Isabella Clark',
        'plan' => 'Premium',
        'amount' => 70,
        'method' => 'Credit Card',
        'status' => 'Completed',
        'date' => date('Y-m-d', strtotime('-15 days'))
    ],
    [
        'id' => 'FCP-2026-1008',
        'member_id' => 'FCM-1008',
        'member' => 'James Parker',
        'plan' => 'Pro',
        'amount' => 40,
        'method' => 'Cash',
        'status' => 'Completed',
        'date' => date('Y-m-d', strtotime('-22 days'))
    ],
    [
        'id' => 'FCP-2026-1009',
        'member_id' => 'FCM-1009',
        'member' => 'Mia Robinson',
        'plan' => 'Basic',
        'amount' => 20,
        'method' => 'Debit Card',
        'status' => 'Refunded',
        'date' => date('Y-m-d', strtotime('-35 days'))
    ],
    [
        'id' => 'FCP-2026-1010',
        'member_id' => 'FCM-1010',
        'member' => 'Lucas Martin',
        'plan' => 'Premium',
        'amount' => 70,
        'method' => 'Bank Transfer',
        'status' => 'Failed',
        'date' => date('Y-m-d', strtotime('-50 days'))
    ],
    [
        'id' => 'FCP-2026-1011',
        'member_id' => 'FCM-1011',
        'member' => 'Charlotte Davis',
        'plan' => 'Pro',
        'amount' => 40,
        'method' => 'Credit Card',
        'status' => 'Completed',
        'date' => date('Y-m-d', strtotime('-70 days'))
    ],
    [
        'id' => 'FCP-2026-1012',
        'member_id' => 'FCM-1012',
        'member' => 'Benjamin Harris',
        'plan' => 'Basic',
        'amount' => 20,
        'method' => 'Cash',
        'status' => 'Pending',
        'date' => date('Y-m-d', strtotime('-100 days'))
    ]
];

/*
|--------------------------------------------------------------------------
| Sample attendance records
|--------------------------------------------------------------------------
*/

$attendance = [
    ['member_id' => 'FCM-1001', 'member' => 'Olivia Bennett', 'class' => 'Strength & Conditioning', 'trainer' => 'Alex Morgan', 'date' => date('Y-m-d', strtotime('-1 day')), 'status' => 'Present'],
    ['member_id' => 'FCM-1002', 'member' => 'Ethan Brooks', 'class' => 'Morning Yoga Flow', 'trainer' => 'Sarah Williams', 'date' => date('Y-m-d', strtotime('-1 day')), 'status' => 'Present'],
    ['member_id' => 'FCM-1003', 'member' => 'Sophia Mitchell', 'class' => 'HIIT Burn', 'trainer' => 'Daniel Carter', 'date' => date('Y-m-d', strtotime('-2 days')), 'status' => 'Absent'],
    ['member_id' => 'FCM-1004', 'member' => 'Noah Wilson', 'class' => 'Pilates Fundamentals', 'trainer' => 'Sarah Williams', 'date' => date('Y-m-d', strtotime('-2 days')), 'status' => 'Late'],
    ['member_id' => 'FCM-1005', 'member' => 'Ava Thompson', 'class' => 'Power Lifting', 'trainer' => 'Alex Morgan', 'date' => date('Y-m-d', strtotime('-3 days')), 'status' => 'Present'],
    ['member_id' => 'FCM-1006', 'member' => 'Liam Anderson', 'class' => 'Boxing Fitness', 'trainer' => 'Daniel Carter', 'date' => date('Y-m-d', strtotime('-3 days')), 'status' => 'Absent'],
    ['member_id' => 'FCM-1007', 'member' => 'Isabella Clark', 'class' => 'Strength & Conditioning', 'trainer' => 'Alex Morgan', 'date' => date('Y-m-d', strtotime('-4 days')), 'status' => 'Present'],
    ['member_id' => 'FCM-1008', 'member' => 'James Parker', 'class' => 'Morning Yoga Flow', 'trainer' => 'Sarah Williams', 'date' => date('Y-m-d', strtotime('-4 days')), 'status' => 'Late'],
    ['member_id' => 'FCM-1009', 'member' => 'Mia Robinson', 'class' => 'HIIT Burn', 'trainer' => 'Daniel Carter', 'date' => date('Y-m-d', strtotime('-8 days')), 'status' => 'Absent'],
    ['member_id' => 'FCM-1010', 'member' => 'Lucas Martin', 'class' => 'Functional Fitness', 'trainer' => 'Alex Morgan', 'date' => date('Y-m-d', strtotime('-10 days')), 'status' => 'Present'],
    ['member_id' => 'FCM-1011', 'member' => 'Charlotte Davis', 'class' => 'Power Lifting', 'trainer' => 'Alex Morgan', 'date' => date('Y-m-d', strtotime('-15 days')), 'status' => 'Late'],
    ['member_id' => 'FCM-1012', 'member' => 'Benjamin Harris', 'class' => 'Boxing Fitness', 'trainer' => 'Daniel Carter', 'date' => date('Y-m-d', strtotime('-20 days')), 'status' => 'Absent'],
    ['member_id' => 'FCM-1001', 'member' => 'Olivia Bennett', 'class' => 'Strength & Conditioning', 'trainer' => 'Alex Morgan', 'date' => date('Y-m-d', strtotime('-25 days')), 'status' => 'Present'],
    ['member_id' => 'FCM-1002', 'member' => 'Ethan Brooks', 'class' => 'Morning Yoga Flow', 'trainer' => 'Sarah Williams', 'date' => date('Y-m-d', strtotime('-30 days')), 'status' => 'Absent'],
    ['member_id' => 'FCM-1004', 'member' => 'Noah Wilson', 'class' => 'Pilates Fundamentals', 'trainer' => 'Sarah Williams', 'date' => date('Y-m-d', strtotime('-40 days')), 'status' => 'Present'],
    ['member_id' => 'FCM-1005', 'member' => 'Ava Thompson', 'class' => 'Power Lifting', 'trainer' => 'Alex Morgan', 'date' => date('Y-m-d', strtotime('-55 days')), 'status' => 'Present'],
    ['member_id' => 'FCM-1007', 'member' => 'Isabella Clark', 'class' => 'HIIT Burn', 'trainer' => 'Daniel Carter', 'date' => date('Y-m-d', strtotime('-75 days')), 'status' => 'Late'],
    ['member_id' => 'FCM-1008', 'member' => 'James Parker', 'class' => 'Morning Yoga Flow', 'trainer' => 'Sarah Williams', 'date' => date('Y-m-d', strtotime('-100 days')), 'status' => 'Present'],
    ['member_id' => 'FCM-1011', 'member' => 'Charlotte Davis', 'class' => 'Power Lifting', 'trainer' => 'Alex Morgan', 'date' => date('Y-m-d', strtotime('-150 days')), 'status' => 'Absent'],
    ['member_id' => 'FCM-1010', 'member' => 'Lucas Martin', 'class' => 'Functional Fitness', 'trainer' => 'Alex Morgan', 'date' => date('Y-m-d', strtotime('-250 days')), 'status' => 'Present']
];

/*
|--------------------------------------------------------------------------
| Sample trainer session data
|--------------------------------------------------------------------------
*/

$trainerSessions = [
    ['trainer' => 'Alex Morgan', 'sessions' => 24, 'attended' => 21],
    ['trainer' => 'Sarah Williams', 'sessions' => 20, 'attended' => 19],
    ['trainer' => 'Daniel Carter', 'sessions' => 18, 'attended' => 15]
];

/*
|--------------------------------------------------------------------------
| Filter report data by selected period
|--------------------------------------------------------------------------
*/

$periodPayments = array_values(array_filter(
    $payments,
    fn($payment) => $payment['date'] >= $startDateString
        && $payment['date'] <= $endDateString
));

$periodAttendance = array_values(array_filter(
    $attendance,
    fn($record) => $record['date'] >= $startDateString
        && $record['date'] <= $endDateString
));

$periodMembers = array_values(array_filter(
    $members,
    fn($member) => $member['joined'] >= $startDateString
        && $member['joined'] <= $endDateString
));

/*
|--------------------------------------------------------------------------
| Calculate summary statistics
|--------------------------------------------------------------------------
*/

$totalMembers = count($members);

$activeMembers = count(array_filter(
    $members,
    fn($member) => $member['status'] === 'Active'
));

$expiredMembers = count(array_filter(
    $members,
    fn($member) => $member['status'] === 'Expired'
));

$suspendedMembers = count(array_filter(
    $members,
    fn($member) => $member['status'] === 'Suspended'
));

$newMembers = count($periodMembers);

$completedRevenue = 0;
$pendingAmount = 0;
$failedAmount = 0;
$refundedAmount = 0;

$paymentStatusCounts = [
    'Completed' => 0,
    'Pending' => 0,
    'Failed' => 0,
    'Refunded' => 0
];

$paymentStatusAmounts = [
    'Completed' => 0,
    'Pending' => 0,
    'Failed' => 0,
    'Refunded' => 0
];

foreach ($periodPayments as $payment) {
    $status = $payment['status'];
    $amount = (float)$payment['amount'];

    if (isset($paymentStatusCounts[$status])) {
        $paymentStatusCounts[$status]++;
        $paymentStatusAmounts[$status] += $amount;
    }

    if ($status === 'Completed') {
        $completedRevenue += $amount;
    } elseif ($status === 'Pending') {
        $pendingAmount += $amount;
    } elseif ($status === 'Failed') {
        $failedAmount += $amount;
    } elseif ($status === 'Refunded') {
        $refundedAmount += $amount;
    }
}

$presentCount = 0;
$lateCount = 0;
$absentCount = 0;

foreach ($periodAttendance as $record) {
    if ($record['status'] === 'Present') {
        $presentCount++;
    } elseif ($record['status'] === 'Late') {
        $lateCount++;
    } elseif ($record['status'] === 'Absent') {
        $absentCount++;
    }
}

$attendanceTotal = count($periodAttendance);
$attendedTotal = $presentCount + $lateCount;

$attendanceRate = $attendanceTotal > 0
    ? round(($attendedTotal / $attendanceTotal) * 100)
    : 0;

/*
|--------------------------------------------------------------------------
| Membership plan distribution
|--------------------------------------------------------------------------
*/

$planDistribution = [
    'Basic' => 0,
    'Pro' => 0,
    'Premium' => 0
];

foreach ($members as $member) {
    if (isset($planDistribution[$member['plan']])) {
        $planDistribution[$member['plan']]++;
    }
}

/*
|--------------------------------------------------------------------------
| Class attendance summary for selected period
|--------------------------------------------------------------------------
*/

$classSummary = [];

foreach ($periodAttendance as $record) {
    $className = $record['class'];

    if (!isset($classSummary[$className])) {
        $classSummary[$className] = [
            'total' => 0,
            'present' => 0,
            'late' => 0,
            'absent' => 0
        ];
    }

    $classSummary[$className]['total']++;

    if ($record['status'] === 'Present') {
        $classSummary[$className]['present']++;
    } elseif ($record['status'] === 'Late') {
        $classSummary[$className]['late']++;
    } elseif ($record['status'] === 'Absent') {
        $classSummary[$className]['absent']++;
    }
}

uasort($classSummary, function ($a, $b) {
    return $b['total'] <=> $a['total'];
});

/*
|--------------------------------------------------------------------------
| Revenue chart data
|--------------------------------------------------------------------------
*/

$revenueChart = [];

if ($periodDays <= 7) {
    $chartDays = 7;
} elseif ($periodDays <= 30) {
    $chartDays = 30;
} elseif ($periodDays <= 90) {
    $chartDays = 90;
} elseif ($periodDays <= 180) {
    $chartDays = 180;
} else {
    $chartDays = 365;
}

$bucketCount = $chartDays <= 30 ? 7 : 6;

if ($chartDays <= 30) {
    // Group daily payments into seven consecutive buckets.
    $bucketSize = (int)ceil($chartDays / $bucketCount);

    for ($i = 0; $i < $bucketCount; $i++) {
        $bucketStart = $startDate->modify('+' . ($i * $bucketSize) . ' days');

        if ($i === $bucketCount - 1) {
            $bucketEnd = $endDate;
        } else {
            $bucketEnd = $bucketStart->modify('+' . ($bucketSize - 1) . ' days');

            if ($bucketEnd > $endDate) {
                $bucketEnd = $endDate;
            }
        }

        $label = $bucketStart->format('d M');
        $amount = 0;

        foreach ($periodPayments as $payment) {
            if (
                $payment['status'] === 'Completed' &&
                $payment['date'] >= $bucketStart->format('Y-m-d') &&
                $payment['date'] <= $bucketEnd->format('Y-m-d')
            ) {
                $amount += (float)$payment['amount'];
            }
        }

        $revenueChart[] = [
            'label' => $label,
            'amount' => $amount
        ];
    }
} else {
    // Group longer periods into six approximately equal time buckets.
    $bucketSize = (int)ceil($chartDays / $bucketCount);

    for ($i = 0; $i < $bucketCount; $i++) {
        $bucketStart = $startDate->modify('+' . ($i * $bucketSize) . ' days');

        if ($bucketStart > $endDate) {
            break;
        }

        $bucketEnd = $bucketStart->modify('+' . ($bucketSize - 1) . ' days');

        if ($bucketEnd > $endDate) {
            $bucketEnd = $endDate;
        }

        $label = $bucketStart->format('d M');
        $amount = 0;

        foreach ($periodPayments as $payment) {
            if (
                $payment['status'] === 'Completed' &&
                $payment['date'] >= $bucketStart->format('Y-m-d') &&
                $payment['date'] <= $bucketEnd->format('Y-m-d')
            ) {
                $amount += (float)$payment['amount'];
            }
        }

        $revenueChart[] = [
            'label' => $label,
            'amount' => $amount
        ];
    }
}

$maxRevenue = 0;

foreach ($revenueChart as $point) {
    $maxRevenue = max($maxRevenue, $point['amount']);
}

$maxRevenue = max($maxRevenue, 1);

/*
|--------------------------------------------------------------------------
| CSV export
|--------------------------------------------------------------------------
*/

if (isset($_GET['export']) && $_GET['export'] === 'csv') {
    $filename = 'fitcore-report-' . $periodDays . '-days-' . date('Ymd') . '.csv';

    header('Content-Type: text/csv; charset=UTF-8');
    header('Content-Disposition: attachment; filename="' . $filename . '"');

    $output = fopen('php://output', 'w');

    // UTF-8 BOM helps spreadsheet software identify UTF-8.
    fwrite($output, "\xEF\xBB\xBF");

    fputcsv($output, ['FitCore Gym - Report']);
    fputcsv($output, ['Report period', $startDateString . ' to ' . $endDateString]);
    fputcsv($output, []);
    fputcsv($output, ['Summary Metric', 'Value']);
    fputcsv($output, ['Total members', $totalMembers]);
    fputcsv($output, ['Active members', $activeMembers]);
    fputcsv($output, ['New members in period', $newMembers]);
    fputcsv($output, ['Completed payment revenue', number_format($completedRevenue, 2, '.', '')]);
    fputcsv($output, ['Pending payments', number_format($pendingAmount, 2, '.', '')]);
    fputcsv($output, ['Failed payments', number_format($failedAmount, 2, '.', '')]);
    fputcsv($output, ['Refunded payments', number_format($refundedAmount, 2, '.', '')]);
    fputcsv($output, ['Attendance rate (%)', $attendanceRate]);
    fputcsv($output, []);

    fputcsv($output, [
        'Transaction ID',
        'Member ID',
        'Member',
        'Plan',
        'Amount',
        'Payment Method',
        'Payment Status',
        'Date'
    ]);

    foreach ($periodPayments as $payment) {
        fputcsv($output, [
            $payment['id'],
            $payment['member_id'],
            $payment['member'],
            $payment['plan'],
            number_format((float)$payment['amount'], 2, '.', ''),
            $payment['method'],
            $payment['status'],
            $payment['date']
        ]);
    }

    fputcsv($output, []);
    fputcsv($output, ['Attendance Records']);
    fputcsv($output, [
        'Member ID',
        'Member',
        'Class',
        'Trainer',
        'Date',
        'Status'
    ]);

    foreach ($periodAttendance as $record) {
        fputcsv($output, [
            $record['member_id'],
            $record['member'],
            $record['class'],
            $record['trainer'],
            $record['date'],
            $record['status']
        ]);
    }

    fclose($output);
    exit;
}

/*
|--------------------------------------------------------------------------
| Revenue chart maximum for rendering
|--------------------------------------------------------------------------
*/

$planTotal = array_sum($planDistribution);

$planPercentages = [];

foreach ($planDistribution as $plan => $count) {
    $planPercentages[$plan] = $planTotal > 0
        ? round(($count / $planTotal) * 100)
        : 0;
}

$periodLabel = match ($period) {
    '7' => 'Last 7 days',
    '30' => 'Last 30 days',
    '90' => 'Last 3 months',
    '180' => 'Last 6 months',
    '365' => 'Last 12 months',
    default => 'Last 30 days'
};
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Reports & Analytics | FitCore Admin</title>

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
            margin-bottom: 28px;
            flex-wrap: wrap;
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

        .filter-panel {
            padding: 18px 20px;
            margin-bottom: 22px;
            background: var(--fc-card);
            border: 1px solid var(--fc-border);
            border-radius: 13px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 18px;
            flex-wrap: wrap;
        }

        .filter-label {
            display: block;
            font-size: 11px;
            color: var(--fc-muted);
            margin-bottom: 7px;
        }

        .form-select {
            min-height: 42px;
            min-width: 180px;
            background-color: #151515;
            color: #eee;
            border: 1px solid #393939;
            border-radius: 8px;
            font-size: 13px;
        }

        .form-select:focus {
            background-color: #151515;
            color: #fff;
            border-color: var(--fc-orange);
            box-shadow: 0 0 0 .2rem rgba(255, 107, 44, .12);
        }

        .form-select option {
            background: #1b1b1b;
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
            gap: 10px;
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
            flex-shrink: 0;
        }

        .stat-value {
            font-size: 27px;
            font-weight: 800;
            margin-top: 5px;
            overflow-wrap: anywhere;
        }

        .stat-note {
            color: var(--fc-muted);
            font-size: 11px;
            margin-top: 7px;
        }

        .panel {
            height: 100%;
            background: var(--fc-card);
            border: 1px solid var(--fc-border);
            border-radius: 13px;
            overflow: hidden;
        }

        .panel-header {
            padding: 21px 22px;
            border-bottom: 1px solid var(--fc-border);
        }

        .panel-header h5 {
            margin: 0;
            font-size: 15px;
            font-weight: 800;
        }

        .panel-header p {
            color: var(--fc-muted);
            font-size: 12px;
            margin: 6px 0 0;
        }

        .panel-body {
            padding: 22px;
        }

        .revenue-total {
            font-size: 29px;
            font-weight: 800;
            margin: 2px 0 5px;
        }

        .revenue-subtitle {
            color: var(--fc-muted);
            font-size: 11px;
        }

        .chart {
            display: flex;
            align-items: stretch;
            gap: 12px;
            height: 215px;
            margin-top: 28px;
            padding-top: 10px;
        }

        .chart-column {
            display: flex;
            flex: 1;
            min-width: 0;
            flex-direction: column;
            align-items: center;
            justify-content: flex-end;
            gap: 10px;
        }

        .chart-value {
            font-size: 10px;
            color: #ccc;
            white-space: nowrap;
        }

        .chart-bar-wrap {
            height: 145px;
            width: 100%;
            display: flex;
            align-items: flex-end;
            justify-content: center;
        }

        .chart-bar {
            width: min(100%, 36px);
            min-height: 3px;
            border-radius: 7px 7px 2px 2px;
            background: linear-gradient(180deg, #ff8a53, #ff6b2c);
            transition: height .3s ease;
        }

        .chart-label {
            font-size: 10px;
            color: var(--fc-muted);
            white-space: nowrap;
        }

        .legend {
            display: flex;
            align-items: center;
            gap: 8px;
            color: var(--fc-muted);
            font-size: 11px;
        }

        .legend-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            display: inline-block;
        }

        .plan-row {
            margin-bottom: 22px;
        }

        .plan-row:last-child {
            margin-bottom: 0;
        }

        .plan-row-top {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            font-size: 12px;
            margin-bottom: 9px;
        }

        .plan-row-top strong {
            font-weight: 700;
        }

        .plan-row-top span {
            color: var(--fc-muted);
        }

        .progress {
            height: 8px;
            background: #343434;
            border-radius: 20px;
        }

        .progress-bar {
            border-radius: 20px;
        }

        .plan-basic {
            background: var(--fc-blue);
        }

        .plan-pro {
            background: var(--fc-orange);
        }

        .plan-premium {
            background: var(--fc-green);
        }

        .status-line {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 19px;
        }

        .status-line:last-child {
            margin-bottom: 0;
        }

        .status-left {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 12px;
        }

        .status-dot {
            width: 9px;
            height: 9px;
            border-radius: 50%;
        }

        .status-right {
            text-align: right;
        }

        .status-right strong {
            display: block;
            font-size: 13px;
        }

        .status-right small {
            color: var(--fc-muted);
            font-size: 10px;
        }

        .table {
            --bs-table-bg: transparent;
            --bs-table-color: #ddd;
            --bs-table-border-color: var(--fc-border);
            margin: 0;
            vertical-align: middle;
        }

        .table thead th {
            color: #929292;
            background: #191919;
            font-size: 10px;
            letter-spacing: .7px;
            text-transform: uppercase;
            font-weight: 800;
            padding: 14px 16px;
            border-bottom: 1px solid var(--fc-border);
            white-space: nowrap;
        }

        .table tbody td {
            padding: 14px 16px;
            font-size: 12px;
        }

        .table tbody tr:hover td {
            background: rgba(255,255,255,.018);
        }

        .badge-status {
            display: inline-block;
            padding: 6px 9px;
            border-radius: 20px;
            font-size: 10px;
            font-weight: 800;
        }

        .badge-completed,
        .badge-present,
        .badge-active {
            background: rgba(54, 201, 143, .12);
            color: var(--fc-green);
        }

        .badge-pending,
        .badge-late {
            background: rgba(243, 189, 79, .12);
            color: var(--fc-yellow);
        }

        .badge-failed,
        .badge-absent {
            background: rgba(255, 104, 104, .12);
            color: var(--fc-red);
        }

        .badge-refunded {
            background: rgba(104, 168, 255, .12);
            color: var(--fc-blue);
        }

        .summary-stat {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            padding: 14px 0;
            border-bottom: 1px solid var(--fc-border);
        }

        .summary-stat:last-child {
            border-bottom: 0;
        }

        .summary-stat span {
            color: var(--fc-muted);
            font-size: 12px;
        }

        .summary-stat strong {
            font-size: 13px;
        }

        .summary-stat .accent {
            color: var(--fc-orange);
        }

        .report-note {
            color: var(--fc-muted);
            font-size: 11px;
            line-height: 1.7;
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

            .stat-value {
                font-size: 24px;
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
            }

            .admin-profile {
                display: none;
            }

            .filter-panel {
                padding: 15px;
            }

            .filter-panel form {
                width: 100%;
            }

            .filter-panel .form-select {
                width: 100%;
            }

            .filter-actions {
                width: 100%;
                display: grid !important;
                grid-template-columns: 1fr;
            }

            .stat-card {
                padding: 16px;
            }

            .stat-value {
                font-size: 22px;
            }

            .panel-body {
                padding: 17px;
            }

            .chart {
                gap: 5px;
            }

            .chart-value,
            .chart-label {
                font-size: 9px;
            }
        }

        @media print {
            .sidebar,
            .sidebar-overlay,
            .sidebar-toggle,
            .no-print {
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
            .filter-panel {
                background: #fff;
                color: #111;
                border-color: #ddd;
                break-inside: avoid;
            }

            .panel-header,
            .table thead th,
            .table tbody td {
                color: #111;
                background: #fff;
            }

            .chart-value,
            .chart-label,
            .stat-label,
            .stat-note,
            .panel-header p,
            .report-note {
                color: #444;
            }

            .row {
                break-inside: avoid;
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
        <a href="attendance.php" class="nav-link">
            <i class="fas fa-clipboard-check"></i> Attendance
        </a>
        <a href="reports.php" class="nav-link active">
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
                <h1 class="page-title">Reports & Analytics</h1>
                <div class="page-subtitle">
                    Understand gym performance through membership, revenue, and attendance reports.
                </div>
            </div>
        </div>

        <div class="d-flex align-items-center gap-3 no-print">
            <div class="admin-profile">
                <div class="admin-avatar">AD</div>
                <div>
                    <strong>Administrator</strong>
                    <small>System Manager</small>
                </div>
            </div>

            <button type="button" class="btn btn-outline-light"
                    onclick="window.print()">
                <i class="fas fa-print me-1"></i> Print
            </button>
        </div>
    </header>

    <section class="filter-panel no-print">
        <div>
            <div class="fw-bold mb-1">Report Period</div>
            <div class="page-subtitle mt-0">
                <?php echo reportEscape($startDate->format('d M Y')); ?>
                – <?php echo reportEscape($endDate->format('d M Y')); ?>
            </div>
        </div>

        <div class="d-flex align-items-end gap-3 flex-wrap">
            <form method="GET" action="reports.php"
                  class="d-flex align-items-end gap-2 flex-wrap">

                <div>
                    <label class="filter-label" for="period">Select Period</label>
                    <select class="form-select" name="period" id="period"
                            onchange="this.form.submit()">
                        <option value="7" <?php echo $period === '7' ? 'selected' : ''; ?>>
                            Last 7 days
                        </option>
                        <option value="30" <?php echo $period === '30' ? 'selected' : ''; ?>>
                            Last 30 days
                        </option>
                        <option value="90" <?php echo $period === '90' ? 'selected' : ''; ?>>
                            Last 3 months
                        </option>
                        <option value="180" <?php echo $period === '180' ? 'selected' : ''; ?>>
                            Last 6 months
                        </option>
                        <option value="365" <?php echo $period === '365' ? 'selected' : ''; ?>>
                            Last 12 months
                        </option>
                    </select>
                </div>
            </form>

            <div class="filter-actions d-flex gap-2">
                <a href="reports.php?period=<?php echo reportEscape($period); ?>&export=csv"
                   class="btn btn-orange">
                    <i class="fas fa-file-csv me-1"></i> Export CSV
                </a>
            </div>
        </div>
    </section>

    <section class="row g-3 mb-4">
        <div class="col-6 col-xl-3">
            <div class="stat-card">
                <div class="stat-top">
                    <span class="stat-label">Total Members</span>
                    <div class="stat-icon"><i class="fas fa-users"></i></div>
                </div>
                <div class="stat-value"><?php echo $totalMembers; ?></div>
                <div class="stat-note">
                    <?php echo $activeMembers; ?> currently active
                </div>
            </div>
        </div>

        <div class="col-6 col-xl-3">
            <div class="stat-card">
                <div class="stat-top">
                    <span class="stat-label">Period Revenue</span>
                    <div class="stat-icon"><i class="fas fa-dollar-sign"></i></div>
                </div>
                <div class="stat-value"><?php echo reportEscape(reportMoney($completedRevenue)); ?></div>
                <div class="stat-note">Completed payments only</div>
            </div>
        </div>

        <div class="col-6 col-xl-3">
            <div class="stat-card">
                <div class="stat-top">
                    <span class="stat-label">New Members</span>
                    <div class="stat-icon"><i class="fas fa-user-plus"></i></div>
                </div>
                <div class="stat-value"><?php echo $newMembers; ?></div>
                <div class="stat-note">
                    Joined within selected period
                </div>
            </div>
        </div>

        <div class="col-6 col-xl-3">
            <div class="stat-card">
                <div class="stat-top">
                    <span class="stat-label">Attendance Rate</span>
                    <div class="stat-icon"><i class="fas fa-clipboard-check"></i></div>
                </div>
                <div class="stat-value"><?php echo $attendanceRate; ?>%</div>
                <div class="stat-note">
                    <?php echo $attendedTotal; ?> attended or arrived late
                    out of <?php echo $attendanceTotal; ?> records
                </div>
            </div>
        </div>
    </section>

    <section class="row g-3 mb-4">
        <div class="col-xl-8">
            <div class="panel">
                <div class="panel-header">
                    <div class="d-flex justify-content-between align-items-start gap-3 flex-wrap">
                        <div>
                            <h5>
                                <i class="fas fa-chart-line me-2"
                                   style="color: var(--fc-orange);"></i>
                                Revenue Overview
                            </h5>
                            <p>Completed payment revenue during <?php echo reportEscape($periodLabel); ?>.</p>
                        </div>

                        <div class="legend">
                            <span class="legend-dot"
                                  style="background: var(--fc-orange);"></span>
                            Completed revenue
                        </div>
                    </div>
                </div>

                <div class="panel-body">
                    <div class="revenue-total">
                        <?php echo reportEscape(reportMoney($completedRevenue)); ?>
                    </div>
                    <div class="revenue-subtitle">
                        Revenue is calculated from completed demo transactions.
                    </div>

                    <div class="chart" role="img"
                         aria-label="Bar chart showing completed revenue by period bucket">
                        <?php foreach ($revenueChart as $point): ?>
                            <?php
                            $barHeight = $maxRevenue > 0
                                ? (int)round(($point['amount'] / $maxRevenue) * 100)
                                : 0;

                            if ($point['amount'] > 0) {
                                $barHeight = max(5, $barHeight);
                            }
                            ?>

                            <div class="chart-column">
                                <div class="chart-value">
                                    <?php echo reportEscape('$' . number_format($point['amount'], 0)); ?>
                                </div>

                                <div class="chart-bar-wrap">
                                    <div class="chart-bar"
                                         title="<?php echo reportEscape($point['label'] . ': ' . reportMoney($point['amount'])); ?>"
                                         style="height: <?php echo $barHeight; ?>%;"
                                         aria-label="<?php echo reportEscape($point['label'] . ': ' . reportMoney($point['amount'])); ?>">
                                    </div>
                                </div>

                                <div class="chart-label">
                                    <?php echo reportEscape($point['label']); ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4">
            <div class="panel">
                <div class="panel-header">
                    <h5>
                        <i class="fas fa-wallet me-2"
                           style="color: var(--fc-orange);"></i>
                        Payment Summary
                    </h5>
                    <p>Transaction status within the selected period.</p>
                </div>

                <div class="panel-body">
                    <?php
                    $paymentStatusStyles = [
                        'Completed' => ['color' => 'var(--fc-green)', 'class' => 'badge-completed'],
                        'Pending' => ['color' => 'var(--fc-yellow)', 'class' => 'badge-pending'],
                        'Failed' => ['color' => 'var(--fc-red)', 'class' => 'badge-failed'],
                        'Refunded' => ['color' => 'var(--fc-blue)', 'class' => 'badge-refunded']
                    ];
                    ?>

                    <?php foreach ($paymentStatusCounts as $status => $count): ?>
                        <div class="status-line">
                            <div class="status-left">
                                <span class="status-dot"
                                      style="background: <?php echo $paymentStatusStyles[$status]['color']; ?>;"></span>
                                <span><?php echo reportEscape($status); ?></span>
                            </div>

                            <div class="status-right">
                                <strong><?php echo $count; ?> transaction(s)</strong>
                                <small><?php echo reportEscape(reportMoney($paymentStatusAmounts[$status])); ?></small>
                            </div>
                        </div>
                    <?php endforeach; ?>

                    <hr style="border-color: var(--fc-border); margin: 22px 0;">

                    <div class="summary-stat">
                        <span>Completed Revenue</span>
                        <strong class="accent">
                            <?php echo reportEscape(reportMoney($completedRevenue)); ?>
                        </strong>
                    </div>

                    <div class="summary-stat">
                        <span>Pending Amount</span>
                        <strong><?php echo reportEscape(reportMoney($pendingAmount)); ?></strong>
                    </div>

                    <div class="summary-stat">
                        <span>Refunded Amount</span>
                        <strong><?php echo reportEscape(reportMoney($refundedAmount)); ?></strong>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="row g-3 mb-4">
        <div class="col-lg-6">
            <div class="panel">
                <div class="panel-header">
                    <h5>
                        <i class="fas fa-id-card me-2"
                           style="color: var(--fc-orange);"></i>
                        Membership Plan Distribution
                    </h5>
                    <p>Current members grouped by membership plan.</p>
                </div>

                <div class="panel-body">
                    <?php
                    $planStyles = [
                        'Basic' => ['class' => 'plan-basic', 'price' => 20],
                        'Pro' => ['class' => 'plan-pro', 'price' => 40],
                        'Premium' => ['class' => 'plan-premium', 'price' => 70]
                    ];
                    ?>

                    <?php foreach ($planDistribution as $plan => $count): ?>
                        <div class="plan-row">
                            <div class="plan-row-top">
                                <strong><?php echo reportEscape($plan); ?></strong>
                                <span>
                                    <?php echo $count; ?> member(s)
                                    · <?php echo $planPercentages[$plan]; ?>%
                                </span>
                            </div>

                            <div class="progress">
                                <div class="progress-bar <?php echo reportEscape($planStyles[$plan]['class']); ?>"
                                     style="width: <?php echo $planPercentages[$plan]; ?>%;">
                                </div>
                            </div>

                            <div class="d-flex justify-content-between mt-2">
                                <span class="report-note">
                                    <?php echo reportEscape(reportMoney($planStyles[$plan]['price'])); ?> / month
                                </span>
                                <span class="report-note">
                                    Estimated plan value:
                                    <?php echo reportEscape(reportMoney($count * $planStyles[$plan]['price'])); ?>
                                </span>
                            </div>
                        </div>
                    <?php endforeach; ?>

                    <hr style="border-color: var(--fc-border); margin: 24px 0;">

                    <div class="summary-stat">
                        <span>Active Members</span>
                        <strong style="color: var(--fc-green);"><?php echo $activeMembers; ?></strong>
                    </div>

                    <div class="summary-stat">
                        <span>Expired Memberships</span>
                        <strong><?php echo $expiredMembers; ?></strong>
                    </div>

                    <div class="summary-stat">
                        <span>Suspended Accounts</span>
                        <strong><?php echo $suspendedMembers; ?></strong>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="panel">
                <div class="panel-header">
                    <h5>
                        <i class="fas fa-clipboard-check me-2"
                           style="color: var(--fc-orange);"></i>
                        Attendance Overview
                    </h5>
                    <p>Member participation during <?php echo reportEscape($periodLabel); ?>.</p>
                </div>

                <div class="panel-body">
                    <div class="d-flex justify-content-between align-items-end mb-3">
                        <div>
                            <div class="report-note">Attendance rate</div>
                            <div class="revenue-total"><?php echo $attendanceRate; ?>%</div>
                        </div>
                        <div class="text-end">
                            <div class="report-note">Total records</div>
                            <strong><?php echo $attendanceTotal; ?></strong>
                        </div>
                    </div>

                    <div class="progress mb-4" style="height: 10px;">
                        <div class="progress-bar"
                             style="width: <?php echo $attendanceRate; ?>%; background: var(--fc-green);">
                        </div>
                    </div>

                    <div class="status-line">
                        <div class="status-left">
                            <span class="status-dot"
                                  style="background: var(--fc-green);"></span>
                            <span>Present</span>
                        </div>
                        <div class="status-right">
                            <strong><?php echo $presentCount; ?></strong>
                            <small>
                                <?php echo $attendanceTotal ? round(($presentCount / $attendanceTotal) * 100) : 0; ?>%
                            </small>
                        </div>
                    </div>

                    <div class="status-line">
                        <div class="status-left">
                            <span class="status-dot"
                                  style="background: var(--fc-yellow);"></span>
                            <span>Late</span>
                        </div>
                        <div class="status-right">
                            <strong><?php echo $lateCount; ?></strong>
                            <small>
                                <?php echo $attendanceTotal ? round(($lateCount / $attendanceTotal) * 100) : 0; ?>%
                            </small>
                        </div>
                    </div>

                    <div class="status-line">
                        <div class="status-left">
                            <span class="status-dot"
                                  style="background: var(--fc-red);"></span>
                            <span>Absent</span>
                        </div>
                        <div class="status-right">
                            <strong><?php echo $absentCount; ?></strong>
                            <small>
                                <?php echo $attendanceTotal ? round(($absentCount / $attendanceTotal) * 100) : 0; ?>%
                            </small>
                        </div>
                    </div>

                    <div class="report-note mt-4">
                        Attendance rate includes members marked Present or Late.
                        Absent records are excluded from the attended count.
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="row g-3 mb-4">
        <div class="col-xl-7">
            <div class="panel">
                <div class="panel-header">
                    <h5>
                        <i class="fas fa-dumbbell me-2"
                           style="color: var(--fc-orange);"></i>
                        Class Performance
                    </h5>
                    <p>Attendance totals by class within the selected period.</p>
                </div>

                <?php if (count($classSummary) > 0): ?>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>Class</th>
                                <th>Records</th>
                                <th>Present</th>
                                <th>Late</th>
                                <th>Absent</th>
                                <th>Rate</th>
                            </tr>
                            </thead>

                            <tbody>
                            <?php foreach ($classSummary as $className => $summary): ?>
                                <?php
                                $classRate = $summary['total'] > 0
                                    ? round(
                                        (($summary['present'] + $summary['late']) /
                                        $summary['total']) * 100
                                    )
                                    : 0;
                                ?>

                                <tr>
                                    <td>
                                        <strong><?php echo reportEscape($className); ?></strong>
                                    </td>
                                    <td><?php echo $summary['total']; ?></td>
                                    <td>
                                        <span style="color: var(--fc-green);">
                                            <?php echo $summary['present']; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span style="color: var(--fc-yellow);">
                                            <?php echo $summary['late']; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span style="color: var(--fc-red);">
                                            <?php echo $summary['absent']; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <strong style="color: var(--fc-orange);">
                                            <?php echo $classRate; ?>%
                                        </strong>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="panel-body report-note">
                        No attendance records are available for this period.
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="col-xl-5">
            <div class="panel">
                <div class="panel-header">
                    <h5>
                        <i class="fas fa-user-tie me-2"
                           style="color: var(--fc-orange);"></i>
                        Trainer Performance
                    </h5>
                    <p>Sample trainer session completion statistics.</p>
                </div>

                <div class="panel-body">
                    <?php foreach ($trainerSessions as $trainer): ?>
                        <?php
                        $trainerRate = $trainer['sessions'] > 0
                            ? round(($trainer['attended'] / $trainer['sessions']) * 100)
                            : 0;
                        ?>

                        <div class="plan-row">
                            <div class="plan-row-top">
                                <strong><?php echo reportEscape($trainer['trainer']); ?></strong>
                                <span><?php echo $trainerRate; ?>% completion</span>
                            </div>

                            <div class="progress">
                                <div class="progress-bar"
                                     style="width: <?php echo $trainerRate; ?>%; background: var(--fc-orange);">
                                </div>
                            </div>

                            <div class="d-flex justify-content-between mt-2">
                                <span class="report-note">
                                    <?php echo $trainer['attended']; ?> completed sessions
                                </span>
                                <span class="report-note">
                                    <?php echo $trainer['sessions']; ?> scheduled
                                </span>
                            </div>
                        </div>
                    <?php endforeach; ?>

                    <div class="report-note mt-4">
                        Trainer performance values are sample data for this portfolio demonstration.
                        They are not calculated from a live trainer booking database.
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="panel mb-4">
        <div class="panel-header d-flex justify-content-between align-items-center gap-3 flex-wrap">
            <div>
                <h5>
                    <i class="fas fa-receipt me-2"
                       style="color: var(--fc-orange);"></i>
                    Recent Payment Transactions
                </h5>
                <p>Transactions recorded within the selected reporting period.</p>
            </div>

            <a href="payments.php" class="btn btn-outline-light btn-sm no-print">
                View Payments <i class="fas fa-arrow-right ms-1"></i>
            </a>
        </div>

        <?php if (count($periodPayments) > 0): ?>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                    <tr>
                        <th>Transaction</th>
                        <th>Member</th>
                        <th>Plan</th>
                        <th>Amount</th>
                        <th>Method</th>
                        <th>Date</th>
                        <th>Status</th>
                    </tr>
                    </thead>

                    <tbody>
                    <?php
                    $sortedPayments = $periodPayments;

                    usort($sortedPayments, function ($a, $b) {
                        return strcmp($b['date'], $a['date']);
                    });

                    foreach (array_slice($sortedPayments, 0, 8) as $payment):
                        $statusClass = match ($payment['status']) {
                            'Completed' => 'badge-completed',
                            'Pending' => 'badge-pending',
                            'Failed' => 'badge-failed',
                            'Refunded' => 'badge-refunded',
                            default => ''
                        };
                    ?>

                        <tr>
                            <td>
                                <strong><?php echo reportEscape($payment['id']); ?></strong>
                            </td>
                            <td>
                                <div><?php echo reportEscape($payment['member']); ?></div>
                                <div class="report-note">
                                    <?php echo reportEscape($payment['member_id']); ?>
                                </div>
                            </td>
                            <td><?php echo reportEscape($payment['plan']); ?></td>
                            <td>
                                <strong><?php echo reportEscape(reportMoney($payment['amount'])); ?></strong>
                            </td>
                            <td><?php echo reportEscape($payment['method']); ?></td>
                            <td><?php echo reportEscape(date('d M Y', strtotime($payment['date']))); ?></td>
                            <td>
                                <span class="badge-status <?php echo reportEscape($statusClass); ?>">
                                    <?php echo reportEscape($payment['status']); ?>
                                </span>
                            </td>
                        </tr>

                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="panel-body report-note">
                No transactions were recorded in the selected period.
            </div>
        <?php endif; ?>
    </section>

    <div class="text-center report-note pb-3">
        FitCore Administration Demo &copy; <?php echo date('Y'); ?>.
        All reports are generated from sample data and are intended for demonstration only.
    </div>

</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>


<script>
    // Responsive sidebar navigation.
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