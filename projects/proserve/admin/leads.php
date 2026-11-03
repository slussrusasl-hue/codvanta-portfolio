
<?php
session_start();
date_default_timezone_set('Asia/Colombo');

function e($value): string
{
    return htmlspecialchars((string)($value ?? ''), ENT_QUOTES, 'UTF-8');
}

$pipelineStages = [
    'NEW LEAD',
    'CONTACTED',
    'QUOTATION',
    'NEGOTIATION',
    'WON'
];

$stageClasses = [
    'NEW LEAD' => 'stage-new',
    'CONTACTED' => 'stage-contacted',
    'QUOTATION' => 'stage-quotation',
    'NEGOTIATION' => 'stage-negotiation',
    'WON' => 'stage-won',
    'LOST' => 'stage-lost'
];

function leadStageClass(string $stage, array $classes): string
{
    return $classes[strtoupper($stage)] ?? 'stage-new';
}

function nextLeadId(array $leads): int
{
    $ids = array_map(
        fn($lead) => (int)($lead['id'] ?? 0),
        $leads
    );

    return $ids ? max($ids) + 1 : 1;
}

/*
|--------------------------------------------------------------------------
| Demo administrator access
|--------------------------------------------------------------------------
| Demonstration only. Replace with a real authenticated admin role check
| before deploying this application publicly.
*/

if (!isset($_SESSION['proserve_admin_logged_in'])) {
    $_SESSION['proserve_admin_logged_in'] = true;
}

$adminName = $_SESSION['proserve_admin_name'] ?? 'Administrator';

/*
|--------------------------------------------------------------------------
| Sample lead records
|--------------------------------------------------------------------------
*/

if (!isset($_SESSION['proserve_leads'])) {
    $_SESSION['proserve_leads'] = [
        [
            'id' => 1,
            'reference' => 'LD-20261001-101',
            'name' => 'Daniel Fernando',
            'email' => 'daniel@example.com',
            'phone' => '+94 77 123 4567',
            'company' => 'DF Solutions',
            'service' => 'Website Development',
            'source' => 'Website',
            'stage' => 'NEW LEAD',
            'priority' => 'HIGH',
            'value' => 85000,
            'follow_up' => date('Y-m-d', strtotime('+1 day')),
            'notes' => 'Interested in a new corporate website.',
            'created_at' => date('Y-m-d H:i:s', strtotime('-8 days'))
        ],
        [
            'id' => 2,
            'reference' => 'LD-20261002-102',
            'name' => 'Sarah Perera',
            'email' => 'sarah@example.com',
            'phone' => '+94 71 234 5678',
            'company' => 'Perera Enterprises',
            'service' => 'CRM Development',
            'source' => 'Referral',
            'stage' => 'CONTACTED',
            'priority' => 'HIGH',
            'value' => 150000,
            'follow_up' => date('Y-m-d', strtotime('+2 days')),
            'notes' => 'Initial discussion completed. Waiting for requirements.',
            'created_at' => date('Y-m-d H:i:s', strtotime('-7 days'))
        ],
        [
            'id' => 3,
            'reference' => 'LD-20261003-103',
            'name' => 'Michael Silva',
            'email' => 'michael@example.com',
            'phone' => '+94 76 345 6789',
            'company' => 'Silva Holdings',
            'service' => 'Business Management System',
            'source' => 'Social Media',
            'stage' => 'QUOTATION',
            'priority' => 'MEDIUM',
            'value' => 225000,
            'follow_up' => date('Y-m-d', strtotime('+3 days')),
            'notes' => 'Quotation prepared. Follow up for feedback.',
            'created_at' => date('Y-m-d H:i:s', strtotime('-6 days'))
        ],
        [
            'id' => 4,
            'reference' => 'LD-20261004-104',
            'name' => 'Amelia Jayawardena',
            'email' => 'amelia@example.com',
            'phone' => '+94 72 456 7890',
            'company' => 'AJ Creative',
            'service' => 'E-commerce Development',
            'source' => 'Website',
            'stage' => 'NEGOTIATION',
            'priority' => 'HIGH',
            'value' => 320000,
            'follow_up' => date('Y-m-d'),
            'notes' => 'Discussing final project scope and pricing.',
            'created_at' => date('Y-m-d H:i:s', strtotime('-5 days'))
        ],
        [
            'id' => 5,
            'reference' => 'LD-20261005-105',
            'name' => 'Oliver Perera',
            'email' => 'oliver@example.com',
            'phone' => '+94 75 567 8901',
            'company' => 'OP Technologies',
            'service' => 'API Integration',
            'source' => 'Referral',
            'stage' => 'WON',
            'priority' => 'MEDIUM',
            'value' => 65000,
            'follow_up' => '',
            'notes' => 'Client accepted the proposal.',
            'created_at' => date('Y-m-d H:i:s', strtotime('-4 days'))
        ],
        [
            'id' => 6,
            'reference' => 'LD-20261006-106',
            'name' => 'Emma Rodrigo',
            'email' => 'emma@example.com',
            'phone' => '+94 70 678 9012',
            'company' => 'ER Consulting',
            'service' => 'Digital Marketing',
            'source' => 'Email',
            'stage' => 'LOST',
            'priority' => 'LOW',
            'value' => 45000,
            'follow_up' => '',
            'notes' => 'Client postponed the project indefinitely.',
            'created_at' => date('Y-m-d H:i:s', strtotime('-3 days'))
        ]
    ];
}

$leads = &$_SESSION['proserve_leads'];

if (!isset($_SESSION['proserve_leads_csrf'])) {
    $_SESSION['proserve_leads_csrf'] = bin2hex(random_bytes(32));
}

$csrfToken = $_SESSION['proserve_leads_csrf'];

$message = '';
$messageType = 'success';
$editingLead = null;

/*
|--------------------------------------------------------------------------
| Process lead actions
|--------------------------------------------------------------------------
*/

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $postedToken = $_POST['csrf_token'] ?? '';

    if (
        !is_string($postedToken) ||
        !hash_equals($csrfToken, $postedToken)
    ) {
        $message = 'Your session token is invalid. Refresh the page and try again.';
        $messageType = 'danger';
    } else {
        $action = $_POST['action'] ?? '';

        if ($action === 'save') {
            $id = filter_var($_POST['id'] ?? '', FILTER_VALIDATE_INT);

            $name = trim((string)($_POST['name'] ?? ''));
            $email = strtolower(trim((string)($_POST['email'] ?? '')));
            $phone = trim((string)($_POST['phone'] ?? ''));
            $company = trim((string)($_POST['company'] ?? ''));
            $service = trim((string)($_POST['service'] ?? ''));
            $source = trim((string)($_POST['source'] ?? 'Website'));
            $stage = strtoupper(trim((string)($_POST['stage'] ?? 'NEW LEAD')));
            $priority = strtoupper(trim((string)($_POST['priority'] ?? 'MEDIUM')));
            $valueInput = trim((string)($_POST['value'] ?? '0'));
            $followUp = trim((string)($_POST['follow_up'] ?? ''));
            $notes = trim((string)($_POST['notes'] ?? ''));

            $validStages = array_merge($pipelineStages, ['LOST']);
            $validPriorities = ['LOW', 'MEDIUM', 'HIGH'];
            $validSources = [
                'Website',
                'Referral',
                'Social Media',
                'Email',
                'Phone',
                'Other'
            ];

            $value = filter_var($valueInput, FILTER_VALIDATE_FLOAT);

            $validFollowUp = $followUp === '' ||
                (
                    preg_match('/^\d{4}-\d{2}-\d{2}$/', $followUp) &&
                    checkdate(
                        (int)substr($followUp, 5, 2),
                        (int)substr($followUp, 8, 2),
                        (int)substr($followUp, 0, 4)
                    )
                );

            if ($name === '' || mb_strlen($name) > 100) {
                $message = 'Enter a lead name with no more than 100 characters.';
                $messageType = 'danger';
            } elseif (
                !filter_var($email, FILTER_VALIDATE_EMAIL) ||
                strlen($email) > 190
            ) {
                $message = 'Enter a valid email address.';
                $messageType = 'danger';
            } elseif (
                strlen($phone) > 40 ||
                mb_strlen($company) > 150 ||
                mb_strlen($service) > 150 ||
                mb_strlen($notes) > 2000
            ) {
                $message = 'One or more fields exceed the allowed length.';
                $messageType = 'danger';
            } elseif (
                !in_array($stage, $validStages, true) ||
                !in_array($priority, $validPriorities, true) ||
                !in_array($source, $validSources, true)
            ) {
                $message = 'Select valid lead stage, priority, and source values.';
                $messageType = 'danger';
            } elseif (
                $value === false ||
                !is_finite((float)$value) ||
                $value < 0 ||
                $value > 999999999999
            ) {
                $message = 'Enter a valid non-negative estimated deal value.';
                $messageType = 'danger';
            } elseif (!$validFollowUp) {
                $message = 'Select a valid follow-up date.';
                $messageType = 'danger';
            } else {
                $duplicate = false;

                foreach ($leads as $existing) {
                    if (
                        strtolower((string)($existing['email'] ?? '')) === $email &&
                        (int)($existing['id'] ?? 0) !== (int)($id ?: 0)
                    ) {
                        $duplicate = true;
                        break;
                    }
                }

                if ($duplicate) {
                    $message = 'A lead with this email address already exists.';
                    $messageType = 'danger';
                } elseif ($id) {
                    $found = false;

                    foreach ($leads as &$lead) {
                        if ((int)($lead['id'] ?? 0) === (int)$id) {
                            $lead['name'] = $name;
                            $lead['email'] = $email;
                            $lead['phone'] = $phone;
                            $lead['company'] = $company;
                            $lead['service'] = $service;
                            $lead['source'] = $source;
                            $lead['stage'] = $stage;
                            $lead['priority'] = $priority;
                            $lead['value'] = (float)$value;
                            $lead['follow_up'] = $followUp;
                            $lead['notes'] = $notes;
                            $lead['updated_at'] = date('Y-m-d H:i:s');

                            $found = true;
                            break;
                        }
                    }
                    unset($lead);

                    if ($found) {
                        $message = 'Lead information updated successfully.';
                    } else {
                        $message = 'The selected lead could not be found.';
                        $messageType = 'danger';
                    }
                } else {
                    $newId = nextLeadId($leads);

                    $leads[] = [
                        'id' => $newId,
                        'reference' => 'LD-' . date('Ymd') . '-' . str_pad(
                            (string)$newId,
                            3,
                            '0',
                            STR_PAD_LEFT
                        ),
                        'name' => $name,
                        'email' => $email,
                        'phone' => $phone,
                        'company' => $company,
                        'service' => $service,
                        'source' => $source,
                        'stage' => $stage,
                        'priority' => $priority,
                        'value' => (float)$value,
                        'follow_up' => $followUp,
                        'notes' => $notes,
                        'created_at' => date('Y-m-d H:i:s')
                    ];

                    $message = 'New lead added successfully.';
                }
            }
        } elseif ($action === 'update_stage') {
            $id = filter_var($_POST['id'] ?? '', FILTER_VALIDATE_INT);
            $newStage = strtoupper(trim((string)($_POST['stage'] ?? '')));

            if (!in_array($newStage, array_merge($pipelineStages, ['LOST']), true)) {
                $message = 'Select a valid pipeline stage.';
                $messageType = 'danger';
            } else {
                $found = false;

                foreach ($leads as &$lead) {
                    if ((int)($lead['id'] ?? 0) === (int)($id ?: 0)) {
                        $lead['stage'] = $newStage;
                        $lead['updated_at'] = date('Y-m-d H:i:s');
                        $found = true;
                        break;
                    }
                }
                unset($lead);

                if ($found) {
                    $message = 'Lead moved to ' . $newStage . ' successfully.';
                } else {
                    $message = 'The selected lead could not be found.';
                    $messageType = 'danger';
                }
            }
        } elseif ($action === 'delete') {
            $id = filter_var($_POST['id'] ?? '', FILTER_VALIDATE_INT);
            $found = false;

            foreach ($leads as $index => $lead) {
                if ((int)($lead['id'] ?? 0) === (int)($id ?: 0)) {
                    array_splice($leads, $index, 1);
                    $found = true;
                    break;
                }
            }

            if ($found) {
                $message = 'Lead deleted from the demonstration.';
            } else {
                $message = 'The selected lead could not be found.';
                $messageType = 'danger';
            }
        }
    }

    $_SESSION['proserve_leads_flash'] = [
        'message' => $message,
        'type' => $messageType
    ];

    header('Location: leads.php');
    exit;
}

/*
|--------------------------------------------------------------------------
| Flash messages
|--------------------------------------------------------------------------
*/

if (isset($_SESSION['proserve_leads_flash'])) {
    $flash = $_SESSION['proserve_leads_flash'];
    $message = $flash['message'] ?? '';
    $messageType = $flash['type'] ?? 'success';

    unset($_SESSION['proserve_leads_flash']);
}

/*
|--------------------------------------------------------------------------
| Edit selection
|--------------------------------------------------------------------------
*/

$editId = filter_input(INPUT_GET, 'edit', FILTER_VALIDATE_INT);

if ($editId) {
    foreach ($leads as $lead) {
        if ((int)($lead['id'] ?? 0) === $editId) {
            $editingLead = $lead;
            break;
        }
    }

    if (!$editingLead) {
        $message = 'The selected lead could not be found.';
        $messageType = 'warning';
    }
}

$showForm = $editingLead !== null ||
    (($_GET['add'] ?? '') === '1');

$formLead = $editingLead ?? [
    'id' => '',
    'name' => '',
    'email' => '',
    'phone' => '',
    'company' => '',
    'service' => '',
    'source' => 'Website',
    'stage' => 'NEW LEAD',
    'priority' => 'MEDIUM',
    'value' => 0,
    'follow_up' => '',
    'notes' => ''
];

/*
|--------------------------------------------------------------------------
| Search and filtering
|--------------------------------------------------------------------------
*/

$search = trim((string)($_GET['search'] ?? ''));
$stageFilter = strtoupper(trim((string)($_GET['stage'] ?? 'ALL')));
$priorityFilter = strtoupper(trim((string)($_GET['priority'] ?? 'ALL')));

if (!in_array($stageFilter, array_merge(['ALL'], $pipelineStages, ['LOST']), true)) {
    $stageFilter = 'ALL';
}

if (!in_array($priorityFilter, ['ALL', 'LOW', 'MEDIUM', 'HIGH'], true)) {
    $priorityFilter = 'ALL';
}

$filteredLeads = array_values(array_filter(
    $leads,
    function ($lead) use ($search, $stageFilter, $priorityFilter) {
        $stage = strtoupper($lead['stage'] ?? 'NEW LEAD');
        $priority = strtoupper($lead['priority'] ?? 'MEDIUM');

        if ($stageFilter !== 'ALL' && $stage !== $stageFilter) {
            return false;
        }

        if ($priorityFilter !== 'ALL' && $priority !== $priorityFilter) {
            return false;
        }

        if ($search !== '') {
            $haystack = implode(' ', [
                $lead['reference'] ?? '',
                $lead['name'] ?? '',
                $lead['email'] ?? '',
                $lead['company'] ?? '',
                $lead['service'] ?? '',
                $lead['phone'] ?? ''
            ]);

            if (stripos($haystack, $search) === false) {
                return false;
            }
        }

        return true;
    }
));

usort($filteredLeads, function ($a, $b) {
    return (int)($b['id'] ?? 0) <=> (int)($a['id'] ?? 0);
});

/*
|--------------------------------------------------------------------------
| Pipeline metrics
|--------------------------------------------------------------------------
*/

$totalLeads = count($leads);
$openLeads = 0;
$wonLeads = 0;
$lostLeads = 0;
$pipelineValue = 0;
$wonValue = 0;
$stageCounts = array_fill_keys(array_merge($pipelineStages, ['LOST']), 0);

foreach ($leads as $lead) {
    $stage = strtoupper($lead['stage'] ?? 'NEW LEAD');
    $value = (float)($lead['value'] ?? 0);

    if (isset($stageCounts[$stage])) {
        $stageCounts[$stage]++;
    }

    if ($stage === 'WON') {
        $wonLeads++;
        $wonValue += $value;
    } elseif ($stage === 'LOST') {
        $lostLeads++;
    } else {
        $openLeads++;
        $pipelineValue += $value;
    }
}

$priorityClasses = [
    'LOW' => 'priority-low',
    'MEDIUM' => 'priority-medium',
    'HIGH' => 'priority-high'
];

$services = [
    'Website Development',
    'Business Management System',
    'CRM Development',
    'E-commerce Development',
    'Database Development',
    'API Integration',
    'Website Maintenance',
    'Digital Marketing',
    'IT Consulting',
    'Cleaning Services',
    'Plumbing Services',
    'Electrical Services',
    'Repair Services',
    'Construction Services',
    'Other'
];

$sources = ['Website', 'Referral', 'Social Media', 'Email', 'Phone', 'Other'];
$priorities = ['LOW', 'MEDIUM', 'HIGH'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Lead Management | ProServe Admin</title>

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

        * { box-sizing: border-box; }

        body {
            margin: 0;
            background: var(--background);
            color: #26364a;
            font-family: 'Inter', 'Trebuchet MS', sans-serif;
            font-size: 13px;
        }

        a { text-decoration: none; }

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
            color: white;
            font-size: 20px;
        }

        .brand-word { color: var(--navy); }
        .brand-word span { color: var(--primary); }

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
            background: rgba(255,255,255,.96);
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

        .page-title {
            color: var(--navy);
            font-size: 25px;
            font-weight: 800;
            letter-spacing: -.8px;
        }

        .muted { color: var(--muted); }

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
            height: 100%;
            padding: 20px;
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 14px;
            box-shadow: 0 4px 18px rgba(16,35,63,.025);
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

        .icon-blue { background: #eaf1ff; color: #2563eb; }
        .icon-green { background: #e6f8ef; color: #16985a; }
        .icon-orange { background: #fff2df; color: #ca7a15; }
        .icon-purple { background: #f1eaff; color: #8055d8; }

        .metric-label {
            margin-top: 16px;
            color: var(--muted);
            font-size: 11px;
            font-weight: 600;
        }

        .metric-value {
            margin-top: 5px;
            color: var(--navy);
            font-size: 26px;
            font-weight: 800;
            overflow-wrap: anywhere;
        }

        .metric-note {
            margin-top: 5px;
            color: var(--muted);
            font-size: 10px;
        }

        .panel {
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 14px;
            box-shadow: 0 4px 18px rgba(16,35,63,.025);
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

        .panel-body { padding: 21px; }

        .pipeline-grid {
            display: grid;
            grid-template-columns: repeat(5, minmax(0, 1fr));
            gap: 12px;
        }

        .pipeline-stage {
            padding: 15px 13px;
            border: 1px solid var(--border);
            border-radius: 12px;
            background: #fbfcff;
        }

        .pipeline-stage-title {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 5px;
            color: #64748b;
            font-size: 9px;
            font-weight: 800;
            letter-spacing: .3px;
        }

        .pipeline-stage-count {
            margin-top: 13px;
            color: var(--navy);
            font-size: 24px;
            font-weight: 800;
        }

        .pipeline-bar {
            height: 5px;
            margin-top: 12px;
            border-radius: 20px;
            background: #e9eef7;
            overflow: hidden;
        }

        .pipeline-bar span {
            display: block;
            height: 100%;
            border-radius: inherit;
        }

        .bar-new { background: #64748b; }
        .bar-contacted { background: #0d9488; }
        .bar-quotation { background: #2563eb; }
        .bar-negotiation { background: #d97706; }
        .bar-won { background: #16a34a; }

        .stage-badge, .priority-badge {
            display: inline-block;
            padding: 6px 8px;
            border-radius: 7px;
            font-size: 9px;
            font-weight: 800;
            white-space: nowrap;
        }

        .stage-new { background: #edf1f5; color: #586679; }
        .stage-contacted { background: #dff7f2; color: #087d6c; }
        .stage-quotation { background: #e6eeff; color: #2456c5; }
        .stage-negotiation { background: #fff0d9; color: #a45b00; }
        .stage-won { background: #dcfce7; color: #15803d; }
        .stage-lost { background: #fee2e2; color: #b91c1c; }

        .priority-low { background: #edf1f5; color: #64748b; }
        .priority-medium { background: #fff3dc; color: #a16207; }
        .priority-high { background: #fee2e2; color: #b91c1c; }

        .table {
            margin-bottom: 0;
            vertical-align: middle;
        }

        .table thead th {
            padding: 14px 16px;
            background: #f8faff;
            color: #8290a4;
            font-size: 9px;
            font-weight: 800;
            letter-spacing: .5px;
            text-transform: uppercase;
            white-space: nowrap;
            border-bottom: 1px solid var(--border);
        }

        .table tbody td {
            padding: 15px 16px;
            border-bottom: 1px solid #edf1f7;
            font-size: 11px;
        }

        .table tbody tr:last-child td { border-bottom: 0; }

        .lead-avatar {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 39px;
            height: 39px;
            flex-shrink: 0;
            border-radius: 12px;
            background: #eaf1ff;
            color: var(--primary);
            font-weight: 800;
        }

        .lead-name {
            color: var(--navy);
            font-size: 11px;
            font-weight: 800;
        }

        .lead-sub {
            margin-top: 4px;
            color: var(--muted);
            font-size: 10px;
        }

        .action-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 31px;
            height: 31px;
            border: 1px solid var(--border);
            border-radius: 8px;
            background: white;
            color: #65748a;
        }

        .action-btn:hover {
            background: #f1f5ff;
            color: var(--primary);
            border-color: #c9d9ff;
        }

        .action-btn.delete:hover {
            background: #fff0f0;
            color: #dc3545;
            border-color: #ffd0d0;
        }

        .form-label {
            color: #435269;
            font-size: 11px;
            font-weight: 700;
        }

        .form-control, .form-select {
            min-height: 42px;
            border-color: #dfe6ef;
            border-radius: 9px;
            font-size: 12px;
        }

        textarea.form-control { min-height: 100px; }

        .form-control:focus, .form-select:focus {
            border-color: #8db0ff;
            box-shadow: 0 0 0 .2rem rgba(37,99,235,.1);
        }

        .filter-panel {
            padding: 17px 20px;
            border-bottom: 1px solid var(--border);
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
            background: white;
            color: var(--navy);
        }

        .sidebar-backdrop { display: none; }

        @media (max-width: 1300px) {
            .pipeline-grid { grid-template-columns: repeat(3, minmax(0, 1fr)); }
        }

        @media (max-width: 1199px) {
            :root { --sidebar-width: 230px; }
            .page-content { padding: 23px 22px 30px; }
            .topbar { padding: 13px 22px; }
        }

        @media (max-width: 991px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.open { transform: translateX(0); }
            .main-area { margin-left: 0; }

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
                background: rgba(16,35,63,.35);
            }
        }

        @media (max-width: 575px) {
            .topbar { padding: 12px 15px; }
            .page-content { padding: 20px 14px 28px; }
            .page-title { font-size: 22px; }
            .topbar-date { display: none; }
            .metric-card { padding: 15px; }
            .metric-value { font-size: 22px; }
            .panel-header, .panel-body { padding: 16px; }
            .pipeline-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
            .footer { padding: 18px 15px; }
        }
    </style>
</head>

<body>

<div class="sidebar-backdrop" id="sidebarBackdrop"></div>

<aside class="sidebar" id="sidebar">
    <a href="index.php" class="brand">
        <span class="brand-icon"><i class="bi bi-grid-1x2-fill"></i></span>
        <span class="brand-word">Pro<span>Serve</span></span>
    </a>

    <div class="sidebar-label">Main Menu</div>

    <a href="index.php" class="sidebar-link">
        <i class="bi bi-speedometer2"></i> Dashboard
    </a>

    <a href="customers.php" class="sidebar-link">
        <i class="bi bi-people"></i> Customers
    </a>

    <a href="leads.php" class="sidebar-link active">
        <i class="bi bi-funnel"></i> Lead Management
    </a>

    <a href="appointments.php" class="sidebar-link">
        <i class="bi bi-calendar-check"></i> Appointments
    </a>

    <div class="sidebar-label">Business Management</div>

    <a href="services.php" class="sidebar-link">
        <i class="bi bi-grid"></i> Services
    </a>

    <a href="quotations.php" class="sidebar-link">
        <i class="bi bi-file-earmark-text"></i> Quotations
    </a>

    <a href="invoices.php" class="sidebar-link">
        <i class="bi bi-receipt"></i> Invoices
    </a>

    <a href="employees.php" class="sidebar-link">
        <i class="bi bi-person-workspace"></i> Employees
    </a>

    <div class="sidebar-label">Analytics & Support</div>

    <a href="reports.php" class="sidebar-link">
        <i class="bi bi-bar-chart-line"></i> Reports
    </a>

    <a href="enquiries.php" class="sidebar-link">
        <i class="bi bi-envelope"></i> Contact Enquiries
    </a>

    <a href="settings.php" class="sidebar-link">
        <i class="bi bi-gear"></i> Settings
    </a>

    <div class="sidebar-bottom">
        <a href="../index.php" class="sidebar-link">
            <i class="bi bi-globe2"></i> View Customer Website
        </a>

        <a href="../logout.php" class="sidebar-link">
            <i class="bi bi-box-arrow-right"></i> Logout
        </a>

        <div class="admin-profile">
            <div class="avatar"><?= e(strtoupper(substr($adminName, 0, 1))) ?></div>
            <div>
                <div class="admin-name"><?= e($adminName) ?></div>
                <div class="admin-role">System Administrator</div>
            </div>
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
                <div class="topbar-title">Lead Management</div>
                <div class="topbar-subtitle">
                    Track opportunities and manage your sales pipeline.
                </div>
            </div>
        </div>

        <div class="d-flex align-items-center gap-3">
            <div class="topbar-date">
                <i class="bi bi-calendar3 text-primary"></i>
                <?= date('D, d M Y') ?>
            </div>
            <div class="avatar"><?= e(strtoupper(substr($adminName, 0, 1))) ?></div>
        </div>
    </header>

    <main class="page-content">

        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
            <div>
                <h1 class="page-title mb-2">Lead Management</h1>
                <p class="muted mb-0">
                    Convert potential customers into successful business opportunities.
                </p>
            </div>

            <a href="leads.php?add=1" class="btn btn-primary px-3 py-2">
                <i class="bi bi-plus-circle me-2"></i>
                Add New Lead
            </a>
        </div>

        <?php if ($message !== ''): ?>
            <div
                class="alert alert-<?= e($messageType) ?> alert-dismissible fade show border-0 shadow-sm"
                role="alert"
            >
                <i class="bi bi-info-circle me-2"></i>
                <?= e($message) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- Summary metrics -->
        <section class="row g-3 mb-4">

            <div class="col-6 col-xl-3">
                <div class="metric-card">
                    <div class="metric-top">
                        <div class="metric-icon icon-blue">
                            <i class="bi bi-funnel"></i>
                        </div>
                    </div>
                    <div class="metric-label">Total Leads</div>
                    <div class="metric-value"><?= number_format($totalLeads) ?></div>
                    <div class="metric-note">All recorded opportunities</div>
                </div>
            </div>

            <div class="col-6 col-xl-3">
                <div class="metric-card">
                    <div class="metric-top">
                        <div class="metric-icon icon-orange">
                            <i class="bi bi-hourglass-split"></i>
                        </div>
                    </div>
                    <div class="metric-label">Open Leads</div>
                    <div class="metric-value"><?= number_format($openLeads) ?></div>
                    <div class="metric-note">Opportunities still in progress</div>
                </div>
            </div>

            <div class="col-6 col-xl-3">
                <div class="metric-card">
                    <div class="metric-top">
                        <div class="metric-icon icon-green">
                            <i class="bi bi-trophy"></i>
                        </div>
                    </div>
                    <div class="metric-label">Deals Won</div>
                    <div class="metric-value"><?= number_format($wonLeads) ?></div>
                    <div class="metric-note">Successfully converted leads</div>
                </div>
            </div>

            <div class="col-6 col-xl-3">
                <div class="metric-card">
                    <div class="metric-top">
                        <div class="metric-icon icon-purple">
                            <i class="bi bi-currency-dollar"></i>
                        </div>
                    </div>
                    <div class="metric-label">Open Pipeline Value</div>
                    <div class="metric-value" style="font-size:21px;">
                        Rs. <?= number_format($pipelineValue, 0) ?>
                    </div>
                    <div class="metric-note">Estimated value excluding won/lost deals</div>
                </div>
            </div>

        </section>

        <!-- Visual sales pipeline -->
        <section class="panel mb-4">
            <div class="panel-header">
                <div>
                    <h2 class="panel-title">Sales Pipeline Overview</h2>
                    <div class="panel-subtitle">
                        Number of leads currently at each stage
                    </div>
                </div>

                <span class="stage-badge stage-won">
                    <i class="bi bi-check-circle me-1"></i>
                    Won value: Rs. <?= number_format($wonValue, 0) ?>
                </span>
            </div>

            <div class="panel-body">
                <div class="pipeline-grid">
                    <?php
                    $pipelinePresentation = [
                        ['stage' => 'NEW LEAD', 'label' => 'NEW LEAD', 'bar' => 'bar-new'],
                        ['stage' => 'CONTACTED', 'label' => 'CONTACTED', 'bar' => 'bar-contacted'],
                        ['stage' => 'QUOTATION', 'label' => 'QUOTATION', 'bar' => 'bar-quotation'],
                        ['stage' => 'NEGOTIATION', 'label' => 'NEGOTIATION', 'bar' => 'bar-negotiation'],
                        ['stage' => 'WON', 'label' => 'WON', 'bar' => 'bar-won']
                    ];
                    ?>

                    <?php foreach ($pipelinePresentation as $item): ?>
                        <?php
                        $count = $stageCounts[$item['stage']];
                        $percentage = $totalLeads > 0
                            ? ($count / $totalLeads) * 100
                            : 0;
                        ?>

                        <a
                            href="leads.php?stage=<?= urlencode($item['stage']) ?>"
                            class="pipeline-stage text-decoration-none"
                        >
                            <div class="pipeline-stage-title">
                                <span><?= e($item['label']) ?></span>
                                <i class="bi bi-arrow-up-right"></i>
                            </div>

                            <div class="pipeline-stage-count">
                                <?= number_format($count) ?>
                            </div>

                            <div class="pipeline-bar">
                                <span
                                    class="<?= e($item['bar']) ?>"
                                    style="width:<?= e($percentage) ?>%;"
                                ></span>
                            </div>

                            <div class="muted mt-2" style="font-size:9px;">
                                <?= number_format($percentage, 1) ?>% of all leads
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>

        <!-- Add / edit lead form -->
        <?php if ($showForm): ?>
            <section class="panel mb-4" id="leadForm">
                <div class="panel-header">
                    <div>
                        <h2 class="panel-title">
                            <?= $editingLead ? 'Edit Lead' : 'Create New Lead' ?>
                        </h2>
                        <div class="panel-subtitle">
                            Record customer details and track the potential opportunity.
                        </div>
                    </div>

                    <a href="leads.php" class="action-btn" aria-label="Close form">
                        <i class="bi bi-x-lg"></i>
                    </a>
                </div>

                <div class="panel-body">
                    <form method="POST" action="leads.php" class="row g-3">
                        <input type="hidden" name="csrf_token" value="<?= e($csrfToken) ?>">
                        <input type="hidden" name="action" value="save">
                        <input type="hidden" name="id" value="<?= e($formLead['id']) ?>">

                        <div class="col-md-6">
                            <label for="name" class="form-label">Contact Person *</label>
                            <input
                                type="text"
                                class="form-control"
                                id="name"
                                name="name"
                                maxlength="100"
                                required
                                value="<?= e($formLead['name']) ?>"
                                placeholder="Enter customer name"
                            >
                        </div>

                        <div class="col-md-6">
                            <label for="email" class="form-label">Email Address *</label>
                            <input
                                type="email"
                                class="form-control"
                                id="email"
                                name="email"
                                maxlength="190"
                                required
                                value="<?= e($formLead['email']) ?>"
                                placeholder="customer@example.com"
                            >
                        </div>

                        <div class="col-md-6">
                            <label for="phone" class="form-label">Phone Number</label>
                            <input
                                type="tel"
                                class="form-control"
                                id="phone"
                                name="phone"
                                maxlength="40"
                                value="<?= e($formLead['phone']) ?>"
                                placeholder="+94 77 123 4567"
                            >
                        </div>

                        <div class="col-md-6">
                            <label for="company" class="form-label">Company / Business</label>
                            <input
                                type="text"
                                class="form-control"
                                id="company"
                                name="company"
                                maxlength="150"
                                value="<?= e($formLead['company']) ?>"
                                placeholder="Company name"
                            >
                        </div>

                        <div class="col-md-6">
                            <label for="service" class="form-label">Interested Service *</label>
                            <select class="form-select" id="service" name="service" required>
                                <option value="">Select a service</option>
                                <?php foreach ($services as $service): ?>
                                    <option
                                        value="<?= e($service) ?>"
                                        <?= ($formLead['service'] ?? '') === $service ? 'selected' : '' ?>
                                    >
                                        <?= e($service) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="source" class="form-label">Lead Source</label>
                            <select class="form-select" id="source" name="source" required>
                                <?php foreach ($sources as $source): ?>
                                    <option
                                        value="<?= e($source) ?>"
                                        <?= ($formLead['source'] ?? 'Website') === $source ? 'selected' : '' ?>
                                    >
                                        <?= e($source) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="stage" class="form-label">Pipeline Stage</label>
                            <select class="form-select" id="stage" name="stage" required>
                                <?php foreach (array_merge($pipelineStages, ['LOST']) as $stage): ?>
                                    <option
                                        value="<?= e($stage) ?>"
                                        <?= strtoupper($formLead['stage'] ?? 'NEW LEAD') === $stage ? 'selected' : '' ?>
                                    >
                                        <?= e($stage) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="priority" class="form-label">Priority</label>
                            <select class="form-select" id="priority" name="priority" required>
                                <?php foreach ($priorities as $priority): ?>
                                    <option
                                        value="<?= e($priority) ?>"
                                        <?= strtoupper($formLead['priority'] ?? 'MEDIUM') === $priority ? 'selected' : '' ?>
                                    >
                                        <?= e($priority) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="value" class="form-label">Estimated Deal Value (LKR)</label>
                            <input
                                type="number"
                                class="form-control"
                                id="value"
                                name="value"
                                min="0"
                                max="999999999999"
                                step="0.01"
                                required
                                value="<?= e($formLead['value'] ?? 0) ?>"
                            >
                        </div>

                        <div class="col-md-6">
                            <label for="follow_up" class="form-label">Next Follow-up Date</label>
                            <input
                                type="date"
                                class="form-control"
                                id="follow_up"
                                name="follow_up"
                                value="<?= e($formLead['follow_up'] ?? '') ?>"
                            >
                        </div>

                        <div class="col-12">
                            <label for="notes" class="form-label">Notes / Requirements</label>
                            <textarea
                                class="form-control"
                                id="notes"
                                name="notes"
                                maxlength="2000"
                                rows="3"
                                placeholder="Enter customer requirements, discussion notes, or next steps..."
                            ><?= e($formLead['notes'] ?? '') ?></textarea>
                        </div>

                        <div class="col-12 d-flex flex-wrap justify-content-end gap-2 pt-2">
                            <a href="leads.php" class="btn btn-light border px-4">
                                Cancel
                            </a>

                            <button type="submit" class="btn btn-primary px-4">
                                <i class="bi bi-check2-circle me-2"></i>
                                <?= $editingLead ? 'Save Changes' : 'Create Lead' ?>
                            </button>
                        </div>
                    </form>
                </div>
            </section>
        <?php endif; ?>

        <!-- Lead directory -->
        <section class="panel">
            <div class="panel-header">
                <div>
                    <h2 class="panel-title">Lead Directory</h2>
                    <div class="panel-subtitle">
                        <?= count($filteredLeads) ?> record(s) matching your filters
                    </div>
                </div>

                <span class="stage-badge stage-quotation">
                    <i class="bi bi-kanban me-1"></i> Sales Pipeline
                </span>
            </div>

            <div class="filter-panel">
                <form method="GET" action="leads.php" class="row g-3 align-items-end">

                    <div class="col-lg-5">
                        <label for="search" class="form-label">Search Leads</label>

                        <div class="input-group">
                            <span class="input-group-text bg-white">
                                <i class="bi bi-search text-muted"></i>
                            </span>

                            <input
                                type="search"
                                class="form-control"
                                id="search"
                                name="search"
                                value="<?= e($search) ?>"
                                placeholder="Name, reference, email, company..."
                            >
                        </div>
                    </div>

                    <div class="col-sm-6 col-lg-3">
                        <label for="stageFilter" class="form-label">Pipeline Stage</label>
                        <select class="form-select" id="stageFilter" name="stage">
                            <option value="ALL" <?= $stageFilter === 'ALL' ? 'selected' : '' ?>>
                                All Stages
                            </option>

                            <?php foreach (array_merge($pipelineStages, ['LOST']) as $stage): ?>
                                <option
                                    value="<?= e($stage) ?>"
                                    <?= $stageFilter === $stage ? 'selected' : '' ?>
                                >
                                    <?= e($stage) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-sm-6 col-lg-2">
                        <label for="priorityFilter" class="form-label">Priority</label>
                        <select class="form-select" id="priorityFilter" name="priority">
                            <option value="ALL" <?= $priorityFilter === 'ALL' ? 'selected' : '' ?>>
                                All Priorities
                            </option>

                            <?php foreach ($priorities as $priority): ?>
                                <option
                                    value="<?= e($priority) ?>"
                                    <?= $priorityFilter === $priority ? 'selected' : '' ?>
                                >
                                    <?= e($priority) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-lg-2 d-flex gap-2">
                        <button type="submit" class="btn btn-primary flex-grow-1">
                            <i class="bi bi-search me-1"></i> Filter
                        </button>

                        <a href="leads.php" class="btn btn-light border" aria-label="Reset filters">
                            <i class="bi bi-arrow-counterclockwise"></i>
                        </a>
                    </div>
                </form>
            </div>

            <div class="table-responsive">
                <table class="table">
                    <thead>
                    <tr>
                        <th>Lead / Reference</th>
                        <th>Service</th>
                        <th>Deal Value</th>
                        <th>Priority</th>
                        <th>Follow-up</th>
                        <th>Pipeline Stage</th>
                        <th class="text-end">Actions</th>
                    </tr>
                    </thead>

                    <tbody>
                    <?php if (empty($filteredLeads)): ?>
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <i class="bi bi-funnel fs-2 text-secondary"></i>
                                <div class="fw-bold text-dark mt-2">No leads found</div>
                                <div class="muted mt-1">
                                    Try changing your filters or add a new lead.
                                </div>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($filteredLeads as $lead): ?>
                            <?php
                            $name = $lead['name'] ?? 'Unnamed Lead';
                            $stage = strtoupper($lead['stage'] ?? 'NEW LEAD');
                            $priority = strtoupper($lead['priority'] ?? 'MEDIUM');
                            ?>

                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="lead-avatar">
                                            <?= e(strtoupper(substr($name, 0, 1))) ?>
                                        </div>

                                        <div>
                                            <div class="lead-name"><?= e($name) ?></div>
                                            <div class="lead-sub">
                                                <?= e($lead['reference'] ?? '') ?>
                                            </div>
                                            <div class="lead-sub">
                                                <?= e($lead['company'] ?: 'Individual customer') ?>
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <td>
                                    <div class="fw-semibold text-dark">
                                        <?= e($lead['service'] ?? '—') ?>
                                    </div>
                                    <div class="lead-sub">
                                        <i class="bi bi-person me-1"></i>
                                        <?= e($lead['email'] ?? '') ?>
                                    </div>
                                </td>

                                <td class="text-nowrap">
                                    <div class="fw-bold text-dark">
                                        Rs. <?= number_format((float)($lead['value'] ?? 0), 2) ?>
                                    </div>
                                </td>

                                <td>
                                    <span class="priority-badge <?= e($priorityClasses[$priority] ?? 'priority-medium') ?>">
                                        <?= e($priority) ?>
                                    </span>
                                </td>

                                <td class="text-nowrap">
                                    <?php if (!empty($lead['follow_up']) && strtotime($lead['follow_up'])): ?>
                                        <?php
                                        $followDate = strtotime($lead['follow_up']);
                                        $isOverdue = $followDate < strtotime(date('Y-m-d')) &&
                                            !in_array($stage, ['WON', 'LOST'], true);
                                        ?>
                                        <span class="<?= $isOverdue ? 'text-danger fw-bold' : 'text-dark' ?>">
                                            <?= e(date('d M Y', $followDate)) ?>
                                        </span>

                                        <?php if ($isOverdue): ?>
                                            <div class="lead-sub text-danger">
                                                <i class="bi bi-exclamation-circle me-1"></i> Overdue
                                            </div>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <span class="muted">Not scheduled</span>
                                    <?php endif; ?>
                                </td>

                                <td>
                                    <span class="stage-badge <?= e(leadStageClass($stage, $stageClasses)) ?>">
                                        <?= e($stage) ?>
                                    </span>
                                </td>

                                <td>
                                    <div class="d-flex justify-content-end gap-1">
                                        <button
                                            type="button"
                                            class="action-btn"
                                            title="View lead details"
                                            aria-label="View lead details"
                                            data-bs-toggle="modal"
                                            data-bs-target="#viewLeadModal"
                                            data-lead="<?= e(json_encode($lead, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP)) ?>"
                                        >
                                            <i class="bi bi-eye"></i>
                                        </button>

                                        <a
                                            href="leads.php?edit=<?= (int)$lead['id'] ?>"
                                            class="action-btn"
                                            title="Edit lead"
                                            aria-label="Edit lead"
                                        >
                                            <i class="bi bi-pencil-square"></i>
                                        </a>

                                        <button
                                            type="button"
                                            class="action-btn"
                                            title="Change pipeline stage"
                                            aria-label="Change pipeline stage"
                                            data-bs-toggle="modal"
                                            data-bs-target="#stageModal"
                                            data-id="<?= (int)$lead['id'] ?>"
                                            data-name="<?= e($name) ?>"
                                            data-stage="<?= e($stage) ?>"
                                        >
                                            <i class="bi bi-arrow-left-right"></i>
                                        </button>

                                        <button
                                            type="button"
                                            class="action-btn delete"
                                            title="Delete lead"
                                            aria-label="Delete lead"
                                            data-bs-toggle="modal"
                                            data-bs-target="#deleteLeadModal"
                                            data-id="<?= (int)$lead['id'] ?>"
                                            data-name="<?= e($name) ?>"
                                        >
                                            <i class="bi bi-trash3"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2 px-4 py-3 border-top">
                <span class="muted small">
                    Showing <?= count($filteredLeads) ?> of <?= $totalLeads ?> lead(s)
                </span>

                <span class="muted small">
                    Won deals: <?= number_format($wonLeads) ?>
                    &nbsp;|&nbsp;
                    Lost deals: <?= number_format($lostLeads) ?>
                </span>
            </div>
        </section>

        <div class="alert alert-info border-0 mt-4 mb-0">
            <i class="bi bi-info-circle me-2"></i>
            <strong>Demonstration mode:</strong>
            Lead records are stored in PHP sessions. Estimated values are illustrative,
            not confirmed revenue.
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

<!-- View lead modal -->
<div class="modal fade" id="viewLeadModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0">
                <div>
                    <h5 class="modal-title fw-bold" id="viewLeadName">Lead Details</h5>
                    <div class="muted small" id="viewLeadReference"></div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body pt-0">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="muted small mb-1">Email Address</div>
                        <div class="fw-semibold" id="viewLeadEmail"></div>
                    </div>

                    <div class="col-md-6">
                        <div class="muted small mb-1">Phone Number</div>
                        <div class="fw-semibold" id="viewLeadPhone"></div>
                    </div>

                    <div class="col-md-6">
                        <div class="muted small mb-1">Company</div>
                        <div class="fw-semibold" id="viewLeadCompany"></div>
                    </div>

                    <div class="col-md-6">
                        <div class="muted small mb-1">Interested Service</div>
                        <div class="fw-semibold" id="viewLeadService"></div>
                    </div>

                    <div class="col-md-6">
                        <div class="muted small mb-1">Lead Source</div>
                        <div class="fw-semibold" id="viewLeadSource"></div>
                    </div>

                    <div class="col-md-6">
                        <div class="muted small mb-1">Estimated Deal Value</div>
                        <div class="fw-bold text-primary" id="viewLeadValue"></div>
                    </div>

                    <div class="col-md-6">
                        <div class="muted small mb-1">Pipeline Stage</div>
                        <div id="viewLeadStage"></div>
                    </div>

                    <div class="col-md-6">
                        <div class="muted small mb-1">Priority</div>
                        <div id="viewLeadPriority"></div>
                    </div>

                    <div class="col-md-6">
                        <div class="muted small mb-1">Follow-up Date</div>
                        <div class="fw-semibold" id="viewLeadFollowUp"></div>
                    </div>

                    <div class="col-md-6">
                        <div class="muted small mb-1">Created At</div>
                        <div class="fw-semibold" id="viewLeadCreated"></div>
                    </div>

                    <div class="col-12">
                        <div class="muted small mb-1">Notes / Requirements</div>
                        <div class="p-3 rounded-3 bg-light" id="viewLeadNotes" style="white-space:pre-wrap;"></div>
                    </div>
                </div>
            </div>

            <div class="modal-footer border-0">
                <button type="button" class="btn btn-light border" data-bs-dismiss="modal">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Change stage modal -->
<div class="modal fade" id="stageModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <form method="POST" action="leads.php">
                <input type="hidden" name="csrf_token" value="<?= e($csrfToken) ?>">
                <input type="hidden" name="action" value="update_stage">
                <input type="hidden" name="id" id="stageLeadId">

                <div class="modal-header border-0">
                    <h5 class="modal-title fw-bold">Update Pipeline Stage</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <p class="muted">
                        Update the current stage for
                        <strong id="stageLeadName"></strong>.
                    </p>

                    <label for="newStage" class="form-label">Select New Stage</label>

                    <select class="form-select" id="newStage" name="stage" required>
                        <?php foreach (array_merge($pipelineStages, ['LOST']) as $stage): ?>
                            <option value="<?= e($stage) ?>"><?= e($stage) ?></option>
                        <?php endforeach; ?>
                    </select>

                    <div class="small muted mt-2">
                        Choose WON when the deal is successfully accepted, or LOST when
                        the opportunity does not proceed.
                    </div>
                </div>

                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light border" data-bs-dismiss="modal">
                        Cancel
                    </button>
                    <button type="submit" class="btn btn-primary">
                        Save Stage
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete confirmation modal -->
<div class="modal fade" id="deleteLeadModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <form method="POST" action="leads.php">
                <input type="hidden" name="csrf_token" value="<?= e($csrfToken) ?>">
                <input type="hidden" name="action" value="delete">
                <input type="hidden" name="id" id="deleteLeadId">

                <div class="modal-header border-0">
                    <h5 class="modal-title fw-bold">Delete Lead</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    Are you sure you want to delete
                    <strong id="deleteLeadName"></strong>?

                    <p class="small text-danger mt-2 mb-0">
                        This removes the lead from the current demonstration session.
                    </p>
                </div>

                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light border" data-bs-dismiss="modal">
                        Cancel
                    </button>
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-trash3 me-1"></i> Delete Lead
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // Responsive sidebar
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
        if (window.innerWidth > 991) closeSidebar();
    });

    // Populate the stage update modal
    document.getElementById('stageModal').addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;

        document.getElementById('stageLeadId').value = button.dataset.id;
        document.getElementById('stageLeadName').textContent = button.dataset.name;
        document.getElementById('newStage').value = button.dataset.stage;
    });

    // Populate the delete confirmation modal
    document.getElementById('deleteLeadModal').addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;

        document.getElementById('deleteLeadId').value = button.dataset.id;
        document.getElementById('deleteLeadName').textContent = button.dataset.name;
    });

    // Safely display lead details
    document.getElementById('viewLeadModal').addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        let lead;

        try {
            lead = JSON.parse(button.dataset.lead);
        } catch (error) {
            return;
        }

        const setText = (id, value) => {
            document.getElementById(id).textContent = value || 'Not provided';
        };

        setText('viewLeadName', lead.name);
        setText('viewLeadReference', lead.reference);
        setText('viewLeadEmail', lead.email);
        setText('viewLeadPhone', lead.phone);
        setText('viewLeadCompany', lead.company);
        setText('viewLeadService', lead.service);
        setText('viewLeadSource', lead.source);
        setText('viewLeadValue', 'Rs. ' + Number(lead.value || 0).toLocaleString('en-LK', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        }));
        setText('viewLeadFollowUp', lead.follow_up || 'Not scheduled');
        setText('viewLeadCreated', lead.created_at || 'Not available');
        setText('viewLeadNotes', lead.notes);

        const stage = document.getElementById('viewLeadStage');
        stage.replaceChildren();

        const stageBadge = document.createElement('span');
        stageBadge.className = 'stage-badge ' + ({
            'NEW LEAD': 'stage-new',
            'CONTACTED': 'stage-contacted',
            'QUOTATION': 'stage-quotation',
            'NEGOTIATION': 'stage-negotiation',
            'WON': 'stage-won',
            'LOST': 'stage-lost'
        }[String(lead.stage || '').toUpperCase()] || 'stage-new');

        stageBadge.textContent = lead.stage || 'NEW LEAD';
        stage.appendChild(stageBadge);

        const priority = document.getElementById('viewLeadPriority');
        priority.replaceChildren();

        const priorityBadge = document.createElement('span');
        priorityBadge.className = 'priority-badge ' + ({
            'LOW': 'priority-low',
            'MEDIUM': 'priority-medium',
            'HIGH': 'priority-high'
        }[String(lead.priority || '').toUpperCase()] || 'priority-medium');

        priorityBadge.textContent = lead.priority || 'MEDIUM';
        priority.appendChild(priorityBadge);
    });
</script>

</body>
</html>
