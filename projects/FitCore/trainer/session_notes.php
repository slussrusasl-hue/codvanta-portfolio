
<?php
session_start();

/*
|--------------------------------------------------------------------------
| FitCore - Trainer Session Notes
|--------------------------------------------------------------------------
| Demonstration features:
| - Record a member's training session notes
| - Record workout performance and progress
| - Add recommendations and follow-up actions
| - Search and filter session notes
| - Edit and delete notes
|
| Notes are stored in the current PHP session only.
| No database connection is required.
|--------------------------------------------------------------------------
*/

$trainerName = "Alex Morgan";
$trainerSpecialty = "Strength & Conditioning";

/*
|--------------------------------------------------------------------------
| Sample Members
|--------------------------------------------------------------------------
*/

$members = [
    [
        "id" => "FCM-1001",
        "name" => "Olivia Bennett",
        "membership" => "Premium"
    ],
    [
        "id" => "FCM-1002",
        "name" => "Ethan Brooks",
        "membership" => "Pro"
    ],
    [
        "id" => "FCM-1003",
        "name" => "Sophia Mitchell",
        "membership" => "Premium"
    ],
    [
        "id" => "FCM-1004",
        "name" => "Noah Wilson",
        "membership" => "Basic"
    ],
    [
        "id" => "FCM-1005",
        "name" => "Ava Thompson",
        "membership" => "Pro"
    ],
    [
        "id" => "FCM-1006",
        "name" => "Liam Anderson",
        "membership" => "Basic"
    ],
    [
        "id" => "FCM-1007",
        "name" => "Isabella Clark",
        "membership" => "Premium"
    ],
    [
        "id" => "FCM-1008",
        "name" => "James Parker",
        "membership" => "Pro"
    ]
];

/*
|--------------------------------------------------------------------------
| Training Activities
|--------------------------------------------------------------------------
*/

$activities = [
    "Strength Training",
    "Muscle Building",
    "Weight Loss Training",
    "Cardio Training",
    "HIIT Workout",
    "Functional Fitness",
    "Stretching & Recovery",
    "Personal Training"
];

$progressOptions = [
    "Excellent",
    "Good",
    "Average",
    "Needs Improvement"
];

/*
|--------------------------------------------------------------------------
| Sample Historical Notes
|--------------------------------------------------------------------------
*/

if (
    !isset($_SESSION["fitcore_session_notes"]) ||
    !is_array($_SESSION["fitcore_session_notes"])
) {
    $_SESSION["fitcore_session_notes"] = [
        [
            "id" => "FN-1001",
            "member_id" => "FCM-1001",
            "member_name" => "Olivia Bennett",
            "activity" => "Strength Training",
            "session_date" => date("Y-m-d", strtotime("-2 days")),
            "duration" => 60,
            "progress" => "Excellent",
            "performance" => "Completed 3 sets of squats and lunges with improved form. Increased resistance slightly.",
            "recommendations" => "Continue progressive overload and maintain proper form. Include a 10-minute warm-up.",
            "follow_up" => "Increase lower-body resistance if form remains consistent.",
            "created_at" => date("Y-m-d H:i:s", strtotime("-2 days")),
            "updated_at" => date("Y-m-d H:i:s", strtotime("-2 days"))
        ],
        [
            "id" => "FN-1002",
            "member_id" => "FCM-1002",
            "member_name" => "Ethan Brooks",
            "activity" => "Weight Loss Training",
            "session_date" => date("Y-m-d", strtotime("-3 days")),
            "duration" => 45,
            "progress" => "Good",
            "performance" => "Completed the cardio circuit and all planned intervals. Recovery between sets is improving.",
            "recommendations" => "Maintain a consistent training routine and gradually increase cardio duration.",
            "follow_up" => "Review endurance and workout intensity at the next session.",
            "created_at" => date("Y-m-d H:i:s", strtotime("-3 days")),
            "updated_at" => date("Y-m-d H:i:s", strtotime("-3 days"))
        ],
        [
            "id" => "FN-1003",
            "member_id" => "FCM-1003",
            "member_name" => "Sophia Mitchell",
            "activity" => "Stretching & Recovery",
            "session_date" => date("Y-m-d", strtotime("-5 days")),
            "duration" => 40,
            "progress" => "Good",
            "performance" => "Completed the mobility routine and demonstrated improved range of motion during the final exercises.",
            "recommendations" => "Continue gentle mobility exercises and avoid forcing movements beyond a comfortable range.",
            "follow_up" => "Review flexibility progress during the next session.",
            "created_at" => date("Y-m-d H:i:s", strtotime("-5 days")),
            "updated_at" => date("Y-m-d H:i:s", strtotime("-5 days"))
        ],
        [
            "id" => "FN-1004",
            "member_id" => "FCM-1005",
            "member_name" => "Ava Thompson",
            "activity" => "Muscle Building",
            "session_date" => date("Y-m-d", strtotime("-7 days")),
            "duration" => 55,
            "progress" => "Average",
            "performance" => "Completed most planned upper-body exercises. Needed additional rest between the final sets.",
            "recommendations" => "Focus on controlled repetitions, sufficient recovery and consistent technique.",
            "follow_up" => "Review exercise tolerance before increasing resistance.",
            "created_at" => date("Y-m-d H:i:s", strtotime("-7 days")),
            "updated_at" => date("Y-m-d H:i:s", strtotime("-7 days"))
        ]
    ];
}

/*
|--------------------------------------------------------------------------
| CSRF Token
|--------------------------------------------------------------------------
*/

if (empty($_SESSION["fitcore_notes_csrf"])) {
    $_SESSION["fitcore_notes_csrf"] = bin2hex(random_bytes(32));
}

/*
|--------------------------------------------------------------------------
| Helper Functions
|--------------------------------------------------------------------------
*/

function escNote($value)
{
    return htmlspecialchars((string)$value, ENT_QUOTES, "UTF-8");
}

function getMemberById($members, $memberId)
{
    foreach ($members as $member) {
        if ($member["id"] === $memberId) {
            return $member;
        }
    }

    return null;
}

function validNoteDate($date)
{
    if (!is_string($date)) {
        return false;
    }

    $parsed = DateTimeImmutable::createFromFormat("!Y-m-d", $date);
    $dateErrors = DateTimeImmutable::getLastErrors();

    return $parsed !== false
        && (
            $dateErrors === false ||
            (
                $dateErrors["warning_count"] === 0 &&
                $dateErrors["error_count"] === 0
            )
        )
        && $parsed->format("Y-m-d") === $date;
}

function progressBadgeClass($progress)
{
    switch ($progress) {
        case "Excellent":
            return "excellent";

        case "Good":
            return "good";

        case "Average":
            return "average";

        default:
            return "improve";
    }
}

/*
|--------------------------------------------------------------------------
| Form Defaults
|--------------------------------------------------------------------------
*/

$errors = [];
$successMessage = "";
$editingNote = null;

$formAction = "save_note";
$formId = "";
$formMember = "";
$formActivity = "Strength Training";
$formDate = date("Y-m-d");
$formDuration = "60";
$formProgress = "Good";
$formPerformance = "";
$formRecommendations = "";
$formFollowUp = "";

/*
|--------------------------------------------------------------------------
| Handle POST Requests
|--------------------------------------------------------------------------
*/

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $submittedToken = $_POST["csrf_token"] ?? "";

    if (
        !is_string($submittedToken) ||
        !hash_equals($_SESSION["fitcore_notes_csrf"], $submittedToken)
    ) {
        $errors[] = "Your session has expired. Refresh the page and try again.";
    } else {
        $action = $_POST["action"] ?? "";

        /*
        |------------------------------------------------------------------
        | Delete a Note
        |------------------------------------------------------------------
        */

        if ($action === "delete_note") {
            $noteId = $_POST["note_id"] ?? "";
            $deleted = false;

            if (is_string($noteId) && $noteId !== "") {
                foreach ($_SESSION["fitcore_session_notes"] as $index => $note) {
                    if ($note["id"] === $noteId) {
                        array_splice(
                            $_SESSION["fitcore_session_notes"],
                            $index,
                            1
                        );

                        $deleted = true;
                        break;
                    }
                }
            }

            if ($deleted) {
                $_SESSION["fitcore_notes_flash"] =
                    "Session note deleted successfully.";

                header("Location: session_notes.php");
                exit;
            }

            $errors[] = "The selected session note could not be found.";
        }

        /*
        |------------------------------------------------------------------
        | Save or Update a Note
        |------------------------------------------------------------------
        */

        elseif ($action === "save_note") {
            $formId = trim($_POST["note_id"] ?? "");
            $formMember = trim($_POST["member_id"] ?? "");
            $formActivity = trim($_POST["activity"] ?? "");
            $formDate = trim($_POST["session_date"] ?? "");
            $formDuration = trim($_POST["duration"] ?? "");
            $formProgress = trim($_POST["progress"] ?? "");
            $formPerformance = trim($_POST["performance"] ?? "");
            $formRecommendations = trim($_POST["recommendations"] ?? "");
            $formFollowUp = trim($_POST["follow_up"] ?? "");

            $formAction = $formId !== "" ? "save_note" : "save_note";

            $member = getMemberById($members, $formMember);

            if ($member === null) {
                $errors[] = "Please select a valid member.";
            }

            if (!in_array($formActivity, $activities, true)) {
                $errors[] = "Please select a valid training activity.";
            }

            if (!validNoteDate($formDate)) {
                $errors[] = "Please enter a valid session date.";
            } elseif ($formDate > date("Y-m-d")) {
                $errors[] = "The session date cannot be in the future.";
            }

            $durationValue = filter_var(
                $formDuration,
                FILTER_VALIDATE_INT
            );

            if (
                $durationValue === false ||
                $durationValue < 10 ||
                $durationValue > 240
            ) {
                $errors[] = "Duration must be between 10 and 240 minutes.";
            }

            if (!in_array($formProgress, $progressOptions, true)) {
                $errors[] = "Please select a valid progress rating.";
            }

            if (trim($formPerformance) === "") {
                $errors[] = "Please enter the session performance details.";
            } elseif (strlen($formPerformance) > 2000) {
                $errors[] = "Performance details cannot exceed 2,000 characters.";
            }

            if (trim($formRecommendations) === "") {
                $errors[] = "Please enter recommendations for the member.";
            } elseif (strlen($formRecommendations) > 2000) {
                $errors[] = "Recommendations cannot exceed 2,000 characters.";
            }

            if (strlen($formFollowUp) > 1000) {
                $errors[] = "Follow-up notes cannot exceed 1,000 characters.";
            }

            /*
            | Confirm that an edited note exists.
            */

            $existingIndex = null;

            if ($formId !== "") {
                foreach ($_SESSION["fitcore_session_notes"] as $index => $note) {
                    if ($note["id"] === $formId) {
                        $existingIndex = $index;
                        break;
                    }
                }

                if ($existingIndex === null) {
                    $errors[] = "The note you are editing no longer exists.";
                }
            }

            if (empty($errors)) {
                $now = date("Y-m-d H:i:s");

                if ($formId !== "") {
                    $oldNote = $_SESSION["fitcore_session_notes"][$existingIndex];

                    $_SESSION["fitcore_session_notes"][$existingIndex] = [
                        "id" => $oldNote["id"],
                        "member_id" => $member["id"],
                        "member_name" => $member["name"],
                        "activity" => $formActivity,
                        "session_date" => $formDate,
                        "duration" => (int)$durationValue,
                        "progress" => $formProgress,
                        "performance" => trim($formPerformance),
                        "recommendations" => trim($formRecommendations),
                        "follow_up" => trim($formFollowUp),
                        "created_at" => $oldNote["created_at"],
                        "updated_at" => $now
                    ];

                    $_SESSION["fitcore_notes_flash"] =
                        "Session note updated successfully.";
                } else {
                    $newId = "FN-" . strtoupper(bin2hex(random_bytes(3)));

                    $_SESSION["fitcore_session_notes"][] = [
                        "id" => $newId,
                        "member_id" => $member["id"],
                        "member_name" => $member["name"],
                        "activity" => $formActivity,
                        "session_date" => $formDate,
                        "duration" => (int)$durationValue,
                        "progress" => $formProgress,
                        "performance" => trim($formPerformance),
                        "recommendations" => trim($formRecommendations),
                        "follow_up" => trim($formFollowUp),
                        "created_at" => $now,
                        "updated_at" => $now
                    ];

                    $_SESSION["fitcore_notes_flash"] =
                        "Session note saved successfully.";
                }

                header("Location: session_notes.php");
                exit;
            }
        } else {
            $errors[] = "Invalid request. Please try again.";
        }
    }
}

/*
|--------------------------------------------------------------------------
| Flash Message
|--------------------------------------------------------------------------
*/

if (!empty($_SESSION["fitcore_notes_flash"])) {
    $successMessage = $_SESSION["fitcore_notes_flash"];
    unset($_SESSION["fitcore_notes_flash"]);
}

/*
|--------------------------------------------------------------------------
| Edit Note from GET Request
|--------------------------------------------------------------------------
*/

$editId = $_GET["edit"] ?? "";

if ($_SERVER["REQUEST_METHOD"] !== "POST" && $editId !== "") {
    foreach ($_SESSION["fitcore_session_notes"] as $note) {
        if ($note["id"] === $editId) {
            $editingNote = $note;

            $formId = $note["id"];
            $formMember = $note["member_id"];
            $formActivity = $note["activity"];
            $formDate = $note["session_date"];
            $formDuration = (string)$note["duration"];
            $formProgress = $note["progress"];
            $formPerformance = $note["performance"];
            $formRecommendations = $note["recommendations"];
            $formFollowUp = $note["follow_up"];

            break;
        }
    }

    if ($editingNote === null) {
        $errors[] = "The requested session note could not be found.";
    }
}

/*
|--------------------------------------------------------------------------
| Statistics
|--------------------------------------------------------------------------
*/

$notes = $_SESSION["fitcore_session_notes"];

usort($notes, function ($a, $b) {
    $dateCompare = strcmp($b["session_date"], $a["session_date"]);

    if ($dateCompare !== 0) {
        return $dateCompare;
    }

    return strcmp($b["updated_at"], $a["updated_at"]);
});

$totalNotes = count($notes);
$uniqueMembers = count(array_unique(array_column($notes, "member_id")));

$excellentNotes = count(array_filter($notes, function ($note) {
    return $note["progress"] === "Excellent";
}));

$totalMinutes = array_sum(array_column($notes, "duration"));

/*
|--------------------------------------------------------------------------
| Search and Filters
|--------------------------------------------------------------------------
*/

$search = trim($_GET["search"] ?? "");
$filterMember = $_GET["member"] ?? "all";
$filterProgress = $_GET["progress"] ?? "all";
$filterActivity = $_GET["activity"] ?? "all";

$validMemberIds = array_merge(
    ["all"],
    array_column($members, "id")
);

$validProgressFilters = array_merge(["all"], $progressOptions);
$validActivityFilters = array_merge(["all"], $activities);

if (!in_array($filterMember, $validMemberIds, true)) {
    $filterMember = "all";
}

if (!in_array($filterProgress, $validProgressFilters, true)) {
    $filterProgress = "all";
}

if (!in_array($filterActivity, $validActivityFilters, true)) {
    $filterActivity = "all";
}

$filteredNotes = array_filter($notes, function ($note) use (
    $search,
    $filterMember,
    $filterProgress,
    $filterActivity
) {
    $searchable = strtolower(
        $note["id"] . " " .
        $note["member_name"] . " " .
        $note["member_id"] . " " .
        $note["activity"] . " " .
        $note["performance"] . " " .
        $note["recommendations"]
    );

    $matchesSearch = $search === "" ||
        strpos($searchable, strtolower($search)) !== false;

    $matchesMember = $filterMember === "all" ||
        $note["member_id"] === $filterMember;

    $matchesProgress = $filterProgress === "all" ||
        $note["progress"] === $filterProgress;

    $matchesActivity = $filterActivity === "all" ||
        $note["activity"] === $filterActivity;

    return $matchesSearch &&
        $matchesMember &&
        $matchesProgress &&
        $matchesActivity;
});

$filteredCount = count($filteredNotes);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Session Notes | FitCore Trainer</title>

    <link rel="stylesheet" href="../../../assets/bootstrap/bootstrap.min.css">

    <style>
        :root {
            --fc-bg: #101010;
            --fc-sidebar: #151515;
            --fc-card: #191919;
            --fc-orange: #ff6b2c;
            --fc-orange-hover: #ff824d;
            --fc-text: #f7f7f7;
            --fc-muted: #a2a2a2;
            --fc-border: #303030;
            --fc-green: #43d99b;
            --fc-red: #ff7272;
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
            position: fixed;
            inset: 0 auto 0 0;
            background: var(--fc-sidebar);
            border-right: 1px solid var(--fc-border);
            padding: 25px 17px;
            overflow-y: auto;
            z-index: 1000;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 11px;
            color: var(--fc-text);
            font-size: 23px;
            font-weight: 800;
            padding: 0 10px;
            margin-bottom: 36px;
        }

        .brand-icon {
            width: 42px;
            height: 42px;
            background: var(--fc-orange);
            color: #111;
            display: grid;
            place-items: center;
            border-radius: 12px;
            font-size: 23px;
        }

        .brand span:last-child span {
            color: var(--fc-orange);
        }

        .sidebar-label {
            color: #777;
            font-size: 10px;
            font-weight: 800;
            letter-spacing: 2px;
            padding: 0 12px;
            margin: 23px 0 10px;
            text-transform: uppercase;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 13px;
            padding: 12px 13px;
            border-radius: 9px;
            color: #aaa;
            font-size: 13px;
            margin-bottom: 5px;
            transition: .2s;
        }

        .sidebar-link:hover,
        .sidebar-link.active {
            background: rgba(255, 107, 44, .12);
            color: var(--fc-orange);
        }

        .sidebar-link.active {
            border-left: 3px solid var(--fc-orange);
            padding-left: 10px;
        }

        .sidebar-icon {
            width: 20px;
            text-align: center;
            font-size: 16px;
        }

        .trainer-mini {
            display: flex;
            align-items: center;
            gap: 11px;
            padding: 15px 10px;
            border-top: 1px solid var(--fc-border);
            margin-top: 28px;
        }

        .trainer-avatar {
            width: 39px;
            height: 39px;
            display: grid;
            place-items: center;
            border-radius: 50%;
            background: #333;
            color: var(--fc-orange);
            font-weight: 800;
        }

        .trainer-mini strong {
            display: block;
            font-size: 12px;
        }

        .trainer-mini small {
            color: var(--fc-muted);
            font-size: 10px;
        }

        .main-content {
            margin-left: 250px;
            padding: 30px 34px 45px;
            min-height: 100vh;
        }

        .mobile-topbar {
            display: none;
        }

        .topbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            margin-bottom: 28px;
        }

        .breadcrumb-text {
            color: var(--fc-muted);
            font-size: 12px;
            margin-bottom: 7px;
        }

        .breadcrumb-text span {
            color: var(--fc-orange);
        }

        .page-title {
            font-size: clamp(25px, 3vw, 32px);
            font-weight: 800;
            margin: 0;
        }

        .page-subtitle {
            color: var(--fc-muted);
            font-size: 13px;
            margin-top: 7px;
            line-height: 1.6;
        }

        .btn-orange {
            background: var(--fc-orange);
            border: 1px solid var(--fc-orange);
            color: #111;
            font-size: 12px;
            font-weight: 800;
            padding: 11px 17px;
            border-radius: 8px;
        }

        .btn-orange:hover {
            background: var(--fc-orange-hover);
            border-color: var(--fc-orange-hover);
            color: #111;
        }

        .btn-outline {
            background: transparent;
            border: 1px solid #444;
            color: #ddd;
            font-size: 11px;
            font-weight: 700;
            padding: 8px 12px;
            border-radius: 7px;
        }

        .btn-outline:hover {
            border-color: var(--fc-orange);
            color: var(--fc-orange);
        }

        .btn-danger-outline {
            background: transparent;
            border: 1px solid #603737;
            color: #ff9292;
            font-size: 11px;
            font-weight: 700;
            padding: 8px 12px;
            border-radius: 7px;
        }

        .btn-danger-outline:hover {
            border-color: var(--fc-red);
            color: var(--fc-red);
        }

        .stat-card {
            height: 100%;
            background: var(--fc-card);
            border: 1px solid var(--fc-border);
            border-radius: 13px;
            padding: 21px;
        }

        .stat-icon {
            width: 39px;
            height: 39px;
            display: grid;
            place-items: center;
            background: rgba(255, 107, 44, .12);
            color: var(--fc-orange);
            border-radius: 10px;
            font-size: 19px;
            margin-bottom: 17px;
        }

        .stat-label {
            color: var(--fc-muted);
            font-size: 12px;
            margin-bottom: 8px;
        }

        .stat-value {
            font-size: 29px;
            font-weight: 800;
        }

        .stat-note {
            color: #818181;
            font-size: 10px;
            margin-top: 7px;
        }

        .section-card {
            background: var(--fc-card);
            border: 1px solid var(--fc-border);
            border-radius: 14px;
            padding: 24px;
            margin-top: 24px;
        }

        .section-heading {
            font-size: 17px;
            font-weight: 800;
            margin: 0;
        }

        .section-description {
            color: var(--fc-muted);
            font-size: 12px;
            margin-top: 7px;
            line-height: 1.6;
        }

        .form-label {
            color: #d6d6d6;
            font-size: 12px;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .form-control,
        .form-select {
            background-color: #111;
            color: #eee;
            border: 1px solid #383838;
            border-radius: 8px;
            font-size: 12px;
            min-height: 43px;
        }

        textarea.form-control {
            min-height: 105px;
            resize: vertical;
        }

        .form-control::placeholder {
            color: #777;
        }

        .form-control:focus,
        .form-select:focus {
            background-color: #111;
            color: #fff;
            border-color: var(--fc-orange);
            box-shadow: 0 0 0 .2rem rgba(255, 107, 44, .12);
        }

        .form-select option {
            background: #191919;
            color: #fff;
        }

        input[type="date"]::-webkit-calendar-picker-indicator {
            filter: invert(1);
            cursor: pointer;
        }

        .form-hint {
            display: block;
            color: #858585;
            font-size: 10px;
            margin-top: 7px;
        }

        .member-preview {
            display: none;
            background: #222;
            border: 1px solid #383838;
            border-radius: 9px;
            padding: 12px;
            margin-top: 12px;
        }

        .member-preview strong {
            font-size: 12px;
        }

        .member-preview small {
            display: block;
            color: var(--fc-muted);
            margin-top: 5px;
            font-size: 11px;
        }

        .alert {
            border-radius: 9px;
            font-size: 12px;
        }

        .alert-success {
            background: rgba(67, 217, 155, .1);
            color: var(--fc-green);
            border: 1px solid rgba(67, 217, 155, .25);
        }

        .alert-danger {
            background: rgba(255, 114, 114, .1);
            color: #ff9292;
            border: 1px solid rgba(255, 114, 114, .25);
        }

        .alert ul {
            margin-bottom: 0;
        }

        .note-filters {
            border-bottom: 1px solid var(--fc-border);
            padding-bottom: 20px;
            margin-bottom: 20px;
        }

        .note-card {
            background: #202020;
            border: 1px solid #303030;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 15px;
        }

        .note-card:last-child {
            margin-bottom: 0;
        }

        .note-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 15px;
            flex-wrap: wrap;
            margin-bottom: 16px;
        }

        .note-member {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .note-avatar {
            width: 43px;
            height: 43px;
            border-radius: 50%;
            display: grid;
            place-items: center;
            background: rgba(255, 107, 44, .12);
            color: var(--fc-orange);
            font-size: 14px;
            font-weight: 800;
            flex-shrink: 0;
        }

        .note-member h3 {
            font-size: 13px;
            font-weight: 800;
            margin: 0 0 5px;
        }

        .note-member small {
            color: var(--fc-muted);
            font-size: 10px;
        }

        .progress-badge {
            display: inline-block;
            border-radius: 6px;
            padding: 6px 9px;
            font-size: 10px;
            font-weight: 800;
        }

        .progress-badge.excellent {
            color: var(--fc-green);
            background: rgba(67, 217, 155, .12);
        }

        .progress-badge.good {
            color: #8dc9ff;
            background: rgba(81, 167, 255, .12);
        }

        .progress-badge.average {
            color: #ffc36b;
            background: rgba(255, 184, 77, .12);
        }

        .progress-badge.improve {
            color: #ff9292;
            background: rgba(255, 114, 114, .12);
        }

        .note-meta {
            color: var(--fc-muted);
            font-size: 11px;
            line-height: 1.8;
            margin-top: 8px;
        }

        .note-reference {
            color: #777;
            font-size: 10px;
            margin-top: 6px;
        }

        .note-content {
            border-top: 1px solid #333;
            padding-top: 16px;
        }

        .note-content h4 {
            color: var(--fc-orange);
            font-size: 11px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .7px;
            margin-bottom: 8px;
        }

        .note-content p {
            color: #c7c7c7;
            font-size: 12px;
            line-height: 1.8;
            margin-bottom: 17px;
            overflow-wrap: anywhere;
        }

        .note-content p:last-child {
            margin-bottom: 0;
        }

        .note-actions {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .empty-state {
            padding: 45px 15px;
            text-align: center;
            color: var(--fc-muted);
        }

        .empty-icon {
            font-size: 35px;
            margin-bottom: 12px;
        }

        .demo-notice {
            background: rgba(255, 107, 44, .06);
            border: 1px solid rgba(255, 107, 44, .2);
            border-radius: 10px;
            padding: 15px;
            color: #d6b09f;
            font-size: 11px;
            line-height: 1.8;
            margin-top: 22px;
        }

        .footer {
            margin-top: 35px;
            padding-top: 20px;
            border-top: 1px solid var(--fc-border);
            color: #777;
            font-size: 11px;
            display: flex;
            justify-content: space-between;
            gap: 15px;
            flex-wrap: wrap;
        }

        .footer a {
            color: var(--fc-orange);
        }

        @media (max-width: 1100px) {
            .sidebar {
                width: 220px;
            }

            .main-content {
                margin-left: 220px;
                padding: 25px 22px 40px;
            }
        }

        @media (max-width: 767px) {
            .sidebar {
                display: none;
            }

            .main-content {
                margin-left: 0;
                padding: 18px 14px 30px;
            }

            .mobile-topbar {
                display: flex;
                justify-content: space-between;
                align-items: center;
                background: var(--fc-sidebar);
                border: 1px solid var(--fc-border);
                border-radius: 10px;
                padding: 13px;
                margin-bottom: 22px;
            }

            .mobile-brand {
                font-weight: 800;
                color: #fff;
            }

            .mobile-brand span {
                color: var(--fc-orange);
            }

            .mobile-menu {
                background: transparent;
                border: 1px solid #383838;
                border-radius: 7px;
                padding: 6px 10px;
                color: #fff;
            }

            .topbar {
                align-items: flex-start;
                flex-direction: column;
            }

            .topbar .btn-orange {
                width: 100%;
            }

            .section-card {
                padding: 17px;
            }

            .note-card {
                padding: 15px;
            }

            .footer {
                flex-direction: column;
            }
        }
    </style>
</head>

<body>

<!-- Sidebar -->
<aside class="sidebar">
    <a href="dashboard.php" class="brand">
        <img src="../../images/fitcore/fitcore_main.png" width="60px" height="60px">
        <span>FIT<span>CORE</span></span>
    </a>

    <div class="sidebar-label">Trainer Workspace</div>

    <a href="dashboard.php" class="sidebar-link">
        <span class="sidebar-icon">▦</span>
        Dashboard
    </a>

    <a href="appointments.php" class="sidebar-link">
        <span class="sidebar-icon">▣</span>
        Appointments
    </a>

    <a href="availability.php" class="sidebar-link">
        <span class="sidebar-icon">◷</span>
        My Availability
    </a>

    <a href="schedule_session.php" class="sidebar-link">
        <span class="sidebar-icon">＋</span>
        Schedule Session
    </a>

    <a href="members.php" class="sidebar-link">
        <span class="sidebar-icon">♙</span>
        Members
    </a>

    <a href="session_notes.php" class="sidebar-link active">
        <span class="sidebar-icon">▤</span>
        Session Notes
    </a>

    <div class="sidebar-label">Quick Links</div>

    <a href="../customer/index.php" class="sidebar-link">
        <span class="sidebar-icon">⌂</span>
        Member Website
    </a>

    <a href="../fitcore_demo.php" class="sidebar-link">
        <span class="sidebar-icon">↩</span>
        Demo Selection
    </a>

    <a href="../index.php" class="sidebar-link">
        <span class="sidebar-icon">🌐</span>
        Codvanta Home
    </a>

    <div class="trainer-mini">
        <div class="trainer-avatar">AM</div>
        <div>
            <strong><?= escNote($trainerName) ?></strong>
            <small><?= escNote($trainerSpecialty) ?></small>
        </div>
    </div>
</aside>

<!-- Main Content -->
<main class="main-content">

    <!-- Mobile Navigation -->
    <div class="mobile-topbar">
        <a href="dashboard.php" class="mobile-brand">
            ⚡ FIT<span>CORE</span>
        </a>

        <button
            class="mobile-menu"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#mobileNavigation"
            aria-expanded="false"
            aria-controls="mobileNavigation"
        >
            ☰ Menu
        </button>
    </div>

    <div class="collapse mb-4" id="mobileNavigation">
        <div class="section-card mt-0">
            <a href="dashboard.php" class="sidebar-link">▦ Dashboard</a>
            <a href="appointments.php" class="sidebar-link">▣ Appointments</a>
            <a href="availability.php" class="sidebar-link">◷ My Availability</a>
            <a href="schedule_session.php" class="sidebar-link">＋ Schedule Session</a>
            <a href="members.php" class="sidebar-link">♙ Members</a>
            <a href="session_notes.php" class="sidebar-link active">▤ Session Notes</a>
            <a href="../customer/index.php" class="sidebar-link">⌂ Member Website</a>
            <a href="../fitcore_demo.php" class="sidebar-link">↩ Demo Selection</a>
        </div>
    </div>

    <!-- Heading -->
    <div class="topbar">
        <div>
            <div class="breadcrumb-text">
                Trainer Workspace / <span>Session Notes</span>
            </div>

            <h1 class="page-title">Training Session Notes</h1>

            <p class="page-subtitle">
                Record workout results, track member progress and prepare recommendations
                for future training sessions.
            </p>
        </div>

        <a href="#noteForm" class="btn btn-orange">
            + Add Session Note
        </a>
    </div>

    <!-- Flash Messages -->
    <?php if ($successMessage !== ""): ?>
        <div class="alert alert-success mb-4">
            ✓ <?= escNote($successMessage) ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger mb-4">
            <strong>Please check the following:</strong>
            <ul class="mt-2">
                <?php foreach ($errors as $error): ?>
                    <li><?= escNote($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <!-- Statistics -->
    <div class="row g-3">

        <div class="col-6 col-xl-3">
            <div class="stat-card">
                <div class="stat-icon">▤</div>
                <div class="stat-label">Total Notes</div>
                <div class="stat-value"><?= $totalNotes ?></div>
                <div class="stat-note">Saved sample records</div>
            </div>
        </div>

        <div class="col-6 col-xl-3">
            <div class="stat-card">
                <div class="stat-icon">♙</div>
                <div class="stat-label">Members Tracked</div>
                <div class="stat-value"><?= $uniqueMembers ?></div>
                <div class="stat-note">Members with recorded notes</div>
            </div>
        </div>

        <div class="col-6 col-xl-3">
            <div class="stat-card">
                <div class="stat-icon">★</div>
                <div class="stat-label">Excellent Progress</div>
                <div class="stat-value"><?= $excellentNotes ?></div>
                <div class="stat-note">Notes rated Excellent</div>
            </div>
        </div>

        <div class="col-6 col-xl-3">
            <div class="stat-card">
                <div class="stat-icon">◷</div>
                <div class="stat-label">Training Minutes</div>
                <div class="stat-value"><?= number_format($totalMinutes) ?></div>
                <div class="stat-note">Combined duration in saved notes</div>
            </div>
        </div>

    </div>

    <!-- Add / Edit Note Form -->
    <section class="section-card" id="noteForm">

        <div class="d-flex justify-content-between align-items-start gap-3 flex-wrap mb-4">
            <div>
                <h2 class="section-heading">
                    <?= $formId !== "" ? "Edit Session Note" : "Record a Training Session" ?>
                </h2>

                <p class="section-description">
                    Record the member's performance and your recommendations after training.
                </p>
            </div>

            <?php if ($formId !== ""): ?>
                <a href="session_notes.php" class="btn btn-outline">
                    Cancel Editing
                </a>
            <?php endif; ?>
        </div>

        <form method="POST" action="session_notes.php" id="sessionNoteForm">

            <input
                type="hidden"
                name="csrf_token"
                value="<?= escNote($_SESSION["fitcore_notes_csrf"]) ?>"
            >

            <input type="hidden" name="action" value="save_note">
            <input type="hidden" name="note_id" value="<?= escNote($formId) ?>">

            <div class="row g-4">

                <div class="col-12 col-md-6 col-xl-4">
                    <label for="member_id" class="form-label">Member *</label>

                    <select name="member_id" id="member_id" class="form-select" required>
                        <option value="">Select member</option>

                        <?php foreach ($members as $member): ?>
                            <option
                                value="<?= escNote($member["id"]) ?>"
                                data-name="<?= escNote($member["name"]) ?>"
                                data-membership="<?= escNote($member["membership"]) ?>"
                                <?= $formMember === $member["id"] ? "selected" : "" ?>
                            >
                                <?= escNote($member["name"]) ?>
                                (<?= escNote($member["id"]) ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <div class="member-preview" id="memberPreview">
                        <strong id="previewName"></strong>
                        <small id="previewMembership"></small>
                    </div>
                </div>

                <div class="col-12 col-md-6 col-xl-4">
                    <label for="activity" class="form-label">Training Activity *</label>

                    <select name="activity" id="activity" class="form-select" required>
                        <?php foreach ($activities as $activity): ?>
                            <option
                                value="<?= escNote($activity) ?>"
                                <?= $formActivity === $activity ? "selected" : "" ?>
                            >
                                <?= escNote($activity) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-12 col-md-6 col-xl-4">
                    <label for="session_date" class="form-label">Session Date *</label>

                    <input
                        type="date"
                        name="session_date"
                        id="session_date"
                        class="form-control"
                        max="<?= date("Y-m-d") ?>"
                        value="<?= escNote($formDate) ?>"
                        required
                    >
                </div>

                <div class="col-12 col-md-6">
                    <label for="duration" class="form-label">Session Duration (minutes) *</label>

                    <input
                        type="number"
                        name="duration"
                        id="duration"
                        class="form-control"
                        min="10"
                        max="240"
                        step="5"
                        value="<?= escNote($formDuration) ?>"
                        required
                    >

                    <span class="form-hint">Enter a duration between 10 and 240 minutes.</span>
                </div>

                <div class="col-12 col-md-6">
                    <label for="progress" class="form-label">Member Progress *</label>

                    <select name="progress" id="progress" class="form-select" required>
                        <?php foreach ($progressOptions as $option): ?>
                            <option
                                value="<?= escNote($option) ?>"
                                <?= $formProgress === $option ? "selected" : "" ?>
                            >
                                <?= escNote($option) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-12">
                    <label for="performance" class="form-label">
                        Workout Performance *
                    </label>

                    <textarea
                        name="performance"
                        id="performance"
                        class="form-control"
                        maxlength="2000"
                        placeholder="Describe the exercises completed, performance, technique, weights or repetitions..."
                        required
                    ><?= escNote($formPerformance) ?></textarea>

                    <div class="d-flex justify-content-between flex-wrap gap-2">
                        <span class="form-hint">
                            Record the member's performance during this session.
                        </span>

                        <span class="form-hint" id="performanceCount">0 / 2000</span>
                    </div>
                </div>

                <div class="col-12">
                    <label for="recommendations" class="form-label">
                        Trainer Recommendations *
                    </label>

                    <textarea
                        name="recommendations"
                        id="recommendations"
                        class="form-control"
                        maxlength="2000"
                        placeholder="Record exercise adjustments, recovery advice or goals for the next session..."
                        required
                    ><?= escNote($formRecommendations) ?></textarea>

                    <div class="d-flex justify-content-between flex-wrap gap-2">
                        <span class="form-hint">
                            Add useful recommendations for future training.
                        </span>

                        <span class="form-hint" id="recommendationsCount">0 / 2000</span>
                    </div>
                </div>

                <div class="col-12">
                    <label for="follow_up" class="form-label">
                        Follow-up Actions
                    </label>

                    <textarea
                        name="follow_up"
                        id="follow_up"
                        class="form-control"
                        maxlength="1000"
                        placeholder="Example: Review squat form next week or reassess endurance in the next session..."
                    ><?= escNote($formFollowUp) ?></textarea>

                    <div class="d-flex justify-content-between flex-wrap gap-2">
                        <span class="form-hint">
                            Optional reminders for the next training session.
                        </span>

                        <span class="form-hint" id="followUpCount">0 / 1000</span>
                    </div>
                </div>

                <div class="col-12 d-flex gap-2 flex-wrap">
                    <button type="submit" class="btn btn-orange">
                        ✓ <?= $formId !== "" ? "Update Session Note" : "Save Session Note" ?>
                    </button>

                    <button type="reset" class="btn btn-outline" id="resetNoteForm">
                        Reset Form
                    </button>

                    <?php if ($formId !== ""): ?>
                        <a href="session_notes.php" class="btn btn-outline">
                            Cancel
                        </a>
                    <?php endif; ?>
                </div>

            </div>
        </form>
    </section>

    <!-- Notes Directory -->
    <section class="section-card">

        <div class="d-flex justify-content-between align-items-start gap-3 flex-wrap mb-4">
            <div>
                <h2 class="section-heading">Session History</h2>
                <p class="section-description">
                    Search existing notes, review progress and update training records.
                </p>
            </div>

            <span class="progress-badge good">
                <?= $filteredCount ?> records
            </span>
        </div>

        <!-- Search Filters -->
        <form method="GET" action="session_notes.php" class="note-filters">
            <div class="row g-3 align-items-end">

                <div class="col-12 col-md-6 col-xl-3">
                    <label for="search" class="form-label">Search Notes</label>

                    <input
                        type="search"
                        name="search"
                        id="search"
                        class="form-control"
                        placeholder="Member, activity, notes..."
                        value="<?= escNote($search) ?>"
                    >
                </div>

                <div class="col-12 col-md-6 col-xl-3">
                    <label for="filter_member" class="form-label">Member</label>

                    <select name="member" id="filter_member" class="form-select">
                        <option value="all">All Members</option>

                        <?php foreach ($members as $member): ?>
                            <option
                                value="<?= escNote($member["id"]) ?>"
                                <?= $filterMember === $member["id"] ? "selected" : "" ?>
                            >
                                <?= escNote($member["name"]) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-12 col-md-6 col-xl-3">
                    <label for="filter_activity" class="form-label">Activity</label>

                    <select name="activity" id="filter_activity" class="form-select">
                        <option value="all">All Activities</option>

                        <?php foreach ($activities as $activity): ?>
                            <option
                                value="<?= escNote($activity) ?>"
                                <?= $filterActivity === $activity ? "selected" : "" ?>
                            >
                                <?= escNote($activity) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-12 col-md-6 col-xl-3">
                    <label for="filter_progress" class="form-label">Progress</label>

                    <select name="progress" id="filter_progress" class="form-select">
                        <option value="all">All Ratings</option>

                        <?php foreach ($progressOptions as $option): ?>
                            <option
                                value="<?= escNote($option) ?>"
                                <?= $filterProgress === $option ? "selected" : "" ?>
                            >
                                <?= escNote($option) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-12 d-flex gap-2 flex-wrap">
                    <button type="submit" class="btn btn-orange">
                        Search Records
                    </button>

                    <a href="session_notes.php" class="btn btn-outline">
                        Clear Filters
                    </a>
                </div>

            </div>
        </form>

        <!-- Note Cards -->
        <?php if (!empty($filteredNotes)): ?>

            <?php foreach ($filteredNotes as $note): ?>
                <?php
                $initials = "";

                $nameParts = preg_split(
                    "/\s+/",
                    trim($note["member_name"])
                );

                foreach (array_slice($nameParts, 0, 2) as $part) {
                    $initials .= strtoupper(substr($part, 0, 1));
                }
                ?>

                <article class="note-card">

                    <div class="note-header">

                        <div>
                            <div class="note-member">
                                <div class="note-avatar">
                                    <?= escNote($initials) ?>
                                </div>

                                <div>
                                    <h3><?= escNote($note["member_name"]) ?></h3>

                                    <small>
                                        <?= escNote($note["member_id"]) ?>
                                        · <?= escNote($note["activity"]) ?>
                                    </small>
                                </div>
                            </div>

                            <div class="note-meta">
                                <div>
                                    <strong>Session date:</strong>
                                    <?= escNote(date("d M Y", strtotime($note["session_date"]))) ?>
                                </div>

                                <div>
                                    <strong>Duration:</strong>
                                    <?= (int)$note["duration"] ?> minutes
                                </div>

                                <div>
                                    <strong>Last updated:</strong>
                                    <?= escNote(date("d M Y, h:i A", strtotime($note["updated_at"]))) ?>
                                </div>

                                <div class="note-reference">
                                    Reference: <?= escNote($note["id"]) ?>
                                </div>
                            </div>
                        </div>

                        <div class="note-actions">
                            <span class="progress-badge <?= progressBadgeClass($note["progress"]) ?>">
                                <?= escNote($note["progress"]) ?> Progress
                            </span>

                            <a
                                href="session_notes.php?edit=<?= urlencode($note["id"]) ?>#noteForm"
                                class="btn btn-outline"
                            >
                                Edit
                            </a>

                            <form
                                method="POST"
                                action="session_notes.php"
                                onsubmit="return confirm('Delete this session note? This cannot be undone in the current demo session.');"
                            >
                                <input
                                    type="hidden"
                                    name="csrf_token"
                                    value="<?= escNote($_SESSION["fitcore_notes_csrf"]) ?>"
                                >

                                <input type="hidden" name="action" value="delete_note">

                                <input
                                    type="hidden"
                                    name="note_id"
                                    value="<?= escNote($note["id"]) ?>"
                                >

                                <button type="submit" class="btn btn-danger-outline">
                                    Delete
                                </button>
                            </form>
                        </div>

                    </div>

                    <div class="note-content">

                        <h4>Workout Performance</h4>
                        <p><?= nl2br(escNote($note["performance"])) ?></p>

                        <h4>Trainer Recommendations</h4>
                        <p><?= nl2br(escNote($note["recommendations"])) ?></p>

                        <?php if (trim($note["follow_up"]) !== ""): ?>
                            <h4>Follow-up Actions</h4>
                            <p><?= nl2br(escNote($note["follow_up"])) ?></p>
                        <?php endif; ?>

                    </div>

                </article>

            <?php endforeach; ?>

        <?php else: ?>

            <div class="empty-state">
                <div class="empty-icon">▤</div>

                <h3 class="section-heading mb-2">No Session Notes Found</h3>

                <p class="section-description">
                    No records match your search. Try different filters or create a new note.
                </p>

                <a href="session_notes.php" class="btn btn-orange mt-3">
                    Clear Filters
                </a>
            </div>

        <?php endif; ?>

    </section>

    <!-- Demo Notice -->
    <div class="demo-notice">
        <strong>Demonstration notice:</strong>
        These notes are stored in PHP session data and are not saved to a database.
        Sample historical notes are provided to demonstrate the interface. This page
        does not update customer accounts, medical records, or the appointment schedule.
        Avoid entering real or sensitive personal information into this demo.
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div>
            © <?= date("Y") ?> FitCore Fitness Platform
        </div>

        <div>
            Designed by <a href="../index.php">Codvanta</a>
            · Turning Vision into Digital Reality.
        </div>
    </footer>

</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>


<script>
    // Display selected member information.
    const memberSelect = document.getElementById("member_id");
    const memberPreview = document.getElementById("memberPreview");
    const previewName = document.getElementById("previewName");
    const previewMembership = document.getElementById("previewMembership");

    function updateMemberPreview() {
        const selectedOption = memberSelect.options[memberSelect.selectedIndex];

        if (selectedOption && selectedOption.value !== "") {
            previewName.textContent = selectedOption.dataset.name || "";
            previewMembership.textContent =
                (selectedOption.dataset.membership || "") + " Membership";

            memberPreview.style.display = "block";
        } else {
            memberPreview.style.display = "none";
        }
    }

    memberSelect.addEventListener("change", updateMemberPreview);
    updateMemberPreview();

    // Character counters.
    function setupCounter(fieldId, counterId, maximum) {
        const field = document.getElementById(fieldId);
        const counter = document.getElementById(counterId);

        function update() {
            counter.textContent = field.value.length + " / " + maximum;
        }

        field.addEventListener("input", update);
        update();
    }

    setupCounter("performance", "performanceCount", 2000);
    setupCounter("recommendations", "recommendationsCount", 2000);
    setupCounter("follow_up", "followUpCount", 1000);

    // Automatically submit note filters when a dropdown changes.
    document.querySelectorAll(
        "#filter_member, #filter_activity, #filter_progress"
    ).forEach(function (field) {
        field.addEventListener("change", function () {
            field.form.submit();
        });
    });

    // Reset the member preview and character counters.
    document.getElementById("resetNoteForm").addEventListener("click", function () {
        setTimeout(function () {
            updateMemberPreview();

            setupCounter("performance", "performanceCount", 2000);
            setupCounter("recommendations", "recommendationsCount", 2000);
            setupCounter("follow_up", "followUpCount", 1000);
        }, 0);
    });
</script>

</body>
</html>