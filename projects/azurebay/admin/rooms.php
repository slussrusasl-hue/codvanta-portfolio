
<?php
session_start();

date_default_timezone_set('Asia/Colombo');

/*
|--------------------------------------------------------------------------
| Azure Bay Resort - Room Management
|--------------------------------------------------------------------------
| Demonstration features:
| 1. Add rooms
| 2. Edit room details
| 3. Change room prices
| 4. Manage room types and capacity
| 5. Update room availability/status
| 6. Delete rooms
|
| All changes are stored in the PHP session for demonstration purposes.
|--------------------------------------------------------------------------
*/

function e($value)
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function money($amount)
{
    return '$' . number_format((float) $amount, 2);
}

function roomImage($image)
{
    $allowedImages = [
        'deluxe-ocean.jpg',
        'garden-room.jpg',
        'beach-suite.jpg',
        'family-suite.jpg',
        'presidential-villa.jpg',
        'horizon-suite.jpg',
        'resort-pool.jpg'
    ];

    if (!in_array($image, $allowedImages, true)) {
        return 'resort-pool.jpg';
    }

    return $image;
}

function redirectWithMessage($message, $type = 'success')
{
    $_SESSION['azurebay_room_flash'] = [
        'message' => $message,
        'type' => $type
    ];

    header('Location: rooms.php');
    exit;
}

/*
|--------------------------------------------------------------------------
| Initialize Sample Rooms
|--------------------------------------------------------------------------
*/

if (!isset($_SESSION['azurebay_admin_rooms'])) {
    $_SESSION['azurebay_admin_rooms'] = [
        1 => [
            'id' => 1,
            'name' => 'Deluxe Ocean View',
            'type' => 'Deluxe Room',
            'number' => 'A-101',
            'price' => 180,
            'capacity' => 2,
            'beds' => '1 King Bed',
            'size' => 32,
            'floor' => '1st Floor',
            'image' => 'deluxe-ocean.jpg',
            'description' => 'An elegant room with panoramic ocean views, a private balcony, and refined coastal interiors.',
            'amenities' => ['Wi-Fi', 'Air Conditioning', 'Ocean View', 'Mini Bar'],
            'status' => 'Available',
            'featured' => true
        ],
        2 => [
            'id' => 2,
            'name' => 'Garden Serenity Room',
            'type' => 'Deluxe Room',
            'number' => 'A-102',
            'price' => 150,
            'capacity' => 2,
            'beds' => '1 Queen Bed',
            'size' => 28,
            'floor' => '1st Floor',
            'image' => 'garden-room.jpg',
            'description' => 'A peaceful retreat surrounded by tropical greenery and natural light.',
            'amenities' => ['Wi-Fi', 'Air Conditioning', 'Garden View', 'Tea & Coffee'],
            'status' => 'Available',
            'featured' => false
        ],
        3 => [
            'id' => 3,
            'name' => 'Premium Beach Suite',
            'type' => 'Suite',
            'number' => 'B-201',
            'price' => 290,
            'capacity' => 3,
            'beds' => '1 King Bed + Sofa Bed',
            'size' => 48,
            'floor' => '2nd Floor',
            'image' => 'beach-suite.jpg',
            'description' => 'A spacious suite offering luxurious interiors, a lounge area, and beautiful beachfront scenery.',
            'amenities' => ['Wi-Fi', 'Air Conditioning', 'Ocean View', 'Living Area', 'Mini Bar'],
            'status' => 'Occupied',
            'featured' => true
        ],
        4 => [
            'id' => 4,
            'name' => 'Family Coastal Suite',
            'type' => 'Family Suite',
            'number' => 'B-205',
            'price' => 340,
            'capacity' => 5,
            'beds' => '1 King Bed + 2 Single Beds',
            'size' => 65,
            'floor' => '2nd Floor',
            'image' => 'family-suite.jpg',
            'description' => 'A comfortable family suite with generous space, practical amenities, and coastal-inspired decor.',
            'amenities' => ['Wi-Fi', 'Air Conditioning', 'Family Lounge', 'Mini Bar'],
            'status' => 'Available',
            'featured' => false
        ],
        5 => [
            'id' => 5,
            'name' => 'Azure Presidential Villa',
            'type' => 'Villa',
            'number' => 'V-001',
            'price' => 450,
            'capacity' => 4,
            'beds' => '2 King Beds',
            'size' => 110,
            'floor' => 'Private Villa',
            'image' => 'presidential-villa.jpg',
            'description' => 'An exclusive villa with premium furnishings, expansive private space, and a luxury resort experience.',
            'amenities' => ['Wi-Fi', 'Private Pool', 'Air Conditioning', 'Ocean View', 'Living Area'],
            'status' => 'Maintenance',
            'featured' => true
        ],
        6 => [
            'id' => 6,
            'name' => 'Horizon Ocean Suite',
            'type' => 'Suite',
            'number' => 'B-208',
            'price' => 320,
            'capacity' => 3,
            'beds' => '1 King Bed + Sofa Bed',
            'size' => 52,
            'floor' => '2nd Floor',
            'image' => 'horizon-suite.jpg',
            'description' => 'A contemporary ocean-facing suite designed for guests seeking comfort and uninterrupted coastal views.',
            'amenities' => ['Wi-Fi', 'Air Conditioning', 'Ocean View', 'Balcony'],
            'status' => 'Available',
            'featured' => false
        ]
    ];

    $_SESSION['azurebay_next_room_id'] = 7;
}

/*
|--------------------------------------------------------------------------
| Handle Form Actions
|--------------------------------------------------------------------------
*/

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'save_room') {
        $id = filter_var($_POST['room_id'] ?? 0, FILTER_VALIDATE_INT);
        $id = $id === false ? 0 : (int) $id;

        $name = trim($_POST['name'] ?? '');
        $type = trim($_POST['type'] ?? '');
        $number = trim($_POST['number'] ?? '');
        $price = filter_var($_POST['price'] ?? null, FILTER_VALIDATE_FLOAT);
        $capacity = filter_var($_POST['capacity'] ?? null, FILTER_VALIDATE_INT);
        $beds = trim($_POST['beds'] ?? '');
        $size = filter_var($_POST['size'] ?? null, FILTER_VALIDATE_FLOAT);
        $floor = trim($_POST['floor'] ?? '');
        $image = roomImage($_POST['image'] ?? 'resort-pool.jpg');
        $description = trim($_POST['description'] ?? '');
        $status = trim($_POST['status'] ?? 'Available');
        $featured = isset($_POST['featured']);

        $allowedTypes = [
            'Deluxe Room',
            'Suite',
            'Family Suite',
            'Villa'
        ];

        $allowedStatuses = [
            'Available',
            'Occupied',
            'Maintenance',
            'Cleaning'
        ];

        $validPrice = $price !== false && $price !== null && $price > 0;
        $validCapacity = $capacity !== false && $capacity !== null
            && $capacity >= 1 && $capacity <= 20;
        $validSize = $size !== false && $size !== null
            && $size >= 1 && $size <= 1000;

        if (
            $name === '' ||
            $number === '' ||
            $beds === '' ||
            $floor === '' ||
            $description === '' ||
            !in_array($type, $allowedTypes, true) ||
            !in_array($status, $allowedStatuses, true) ||
            !$validPrice ||
            !$validCapacity ||
            !$validSize
        ) {
            redirectWithMessage(
                'Please complete all fields with valid values.',
                'danger'
            );
        }

        if (strlen($name) > 100 || strlen($number) > 20 ||
            strlen($beds) > 100 || strlen($floor) > 50 ||
            strlen($description) > 1000) {
            redirectWithMessage(
                'Some fields exceed the permitted length.',
                'danger'
            );
        }

        $rooms = $_SESSION['azurebay_admin_rooms'];

        foreach ($rooms as $existingId => $existingRoom) {
            if (
                strtoupper($existingRoom['number']) === strtoupper($number)
                && (int) $existingId !== $id
            ) {
                redirectWithMessage(
                    'That room number is already in use. Please enter a unique room number.',
                    'danger'
                );
            }
        }

        if ($id > 0 && !isset($rooms[$id])) {
            redirectWithMessage(
                'The selected room could not be found.',
                'danger'
            );
        }

        if ($id === 0) {
            $id = (int) ($_SESSION['azurebay_next_room_id'] ?? 7);
            $_SESSION['azurebay_next_room_id'] = $id + 1;
        }

        $amenities = $_POST['amenities'] ?? [];

        if (!is_array($amenities)) {
            $amenities = [];
        }

        $allowedAmenities = [
            'Wi-Fi',
            'Air Conditioning',
            'Ocean View',
            'Garden View',
            'Mini Bar',
            'Tea & Coffee',
            'Living Area',
            'Balcony',
            'Private Pool',
            'Family Lounge'
        ];

        $amenities = array_values(array_intersect(
            $allowedAmenities,
            array_map('strval', $amenities)
        ));

        $_SESSION['azurebay_admin_rooms'][$id] = [
            'id' => $id,
            'name' => $name,
            'type' => $type,
            'number' => strtoupper($number),
            'price' => round((float) $price, 2),
            'capacity' => (int) $capacity,
            'beds' => $beds,
            'size' => (float) $size,
            'floor' => $floor,
            'image' => $image,
            'description' => $description,
            'amenities' => $amenities,
            'status' => $status,
            'featured' => $featured
        ];

        redirectWithMessage(
            $id > 0 && isset($_POST['room_id']) && (int) $_POST['room_id'] > 0
                ? 'Room details updated successfully.'
                : 'New room added successfully.'
        );
    }

    if ($action === 'quick_update') {
        $id = filter_var($_POST['room_id'] ?? null, FILTER_VALIDATE_INT);
        $id = $id === false ? 0 : (int) $id;

        $price = filter_var($_POST['price'] ?? null, FILTER_VALIDATE_FLOAT);
        $status = trim($_POST['status'] ?? '');

        $allowedStatuses = [
            'Available',
            'Occupied',
            'Maintenance',
            'Cleaning'
        ];

        if (
            !isset($_SESSION['azurebay_admin_rooms'][$id]) ||
            $price === false ||
            $price === null ||
            $price <= 0 ||
            $price > 100000 ||
            !in_array($status, $allowedStatuses, true)
        ) {
            redirectWithMessage(
                'Please provide a valid room price and status.',
                'danger'
            );
        }

        $_SESSION['azurebay_admin_rooms'][$id]['price'] =
            round((float) $price, 2);

        $_SESSION['azurebay_admin_rooms'][$id]['status'] = $status;

        redirectWithMessage(
            'Room price and availability status updated successfully.'
        );
    }

    if ($action === 'delete_room') {
        $id = filter_var($_POST['room_id'] ?? null, FILTER_VALIDATE_INT);
        $id = $id === false ? 0 : (int) $id;

        if (!isset($_SESSION['azurebay_admin_rooms'][$id])) {
            redirectWithMessage('The selected room was not found.', 'danger');
        }

        unset($_SESSION['azurebay_admin_rooms'][$id]);

        redirectWithMessage(
            'Room removed from the demonstration successfully.'
        );
    }
}

/*
|--------------------------------------------------------------------------
| Read Session Data
|--------------------------------------------------------------------------
*/

$flash = $_SESSION['azurebay_room_flash'] ?? null;
unset($_SESSION['azurebay_room_flash']);

$rooms = $_SESSION['azurebay_admin_rooms'];

/*
|--------------------------------------------------------------------------
| Filters and Search
|--------------------------------------------------------------------------
*/

$search = trim($_GET['search'] ?? '');
$typeFilter = trim($_GET['type'] ?? '');
$statusFilter = trim($_GET['status'] ?? '');
$sort = trim($_GET['sort'] ?? 'room_number');

$validTypes = [
    'Deluxe Room',
    'Suite',
    'Family Suite',
    'Villa'
];

$validStatuses = [
    'Available',
    'Occupied',
    'Maintenance',
    'Cleaning'
];

if (!in_array($typeFilter, $validTypes, true)) {
    $typeFilter = '';
}

if (!in_array($statusFilter, $validStatuses, true)) {
    $statusFilter = '';
}

$filteredRooms = array_filter(
    $rooms,
    function ($room) use ($search, $typeFilter, $statusFilter) {
        $matchesSearch = $search === '' ||
            stripos($room['name'], $search) !== false ||
            stripos($room['number'], $search) !== false ||
            stripos($room['type'], $search) !== false;

        $matchesType = $typeFilter === '' ||
            $room['type'] === $typeFilter;

        $matchesStatus = $statusFilter === '' ||
            $room['status'] === $statusFilter;

        return $matchesSearch && $matchesType && $matchesStatus;
    }
);

switch ($sort) {
    case 'price_low':
        uasort($filteredRooms, function ($a, $b) {
            return $a['price'] <=> $b['price'];
        });
        break;

    case 'price_high':
        uasort($filteredRooms, function ($a, $b) {
            return $b['price'] <=> $a['price'];
        });
        break;

    case 'capacity':
        uasort($filteredRooms, function ($a, $b) {
            return $b['capacity'] <=> $a['capacity'];
        });
        break;

    default:
        uasort($filteredRooms, function ($a, $b) {
            return strnatcasecmp($a['number'], $b['number']);
        });
        break;
}

/*
|--------------------------------------------------------------------------
| Dashboard Counts
|--------------------------------------------------------------------------
*/

$totalRooms = count($rooms);
$availableRooms = 0;
$occupiedRooms = 0;
$maintenanceRooms = 0;
$totalNightlyRevenue = 0;

foreach ($rooms as $room) {
    if ($room['status'] === 'Available') {
        $availableRooms++;
    }

    if ($room['status'] === 'Occupied') {
        $occupiedRooms++;
        $totalNightlyRevenue += (float) $room['price'];
    }

    if ($room['status'] === 'Maintenance') {
        $maintenanceRooms++;
    }
}

$editingRoom = null;

if (isset($_GET['edit'])) {
    $editId = filter_var($_GET['edit'], FILTER_VALIDATE_INT);
    $editId = $editId === false ? 0 : (int) $editId;

    if (isset($rooms[$editId])) {
        $editingRoom = $rooms[$editId];
    }
}

$defaultRoom = [
    'id' => 0,
    'name' => '',
    'type' => 'Deluxe Room',
    'number' => '',
    'price' => 180,
    'capacity' => 2,
    'beds' => '1 King Bed',
    'size' => 32,
    'floor' => '1st Floor',
    'image' => 'deluxe-ocean.jpg',
    'description' => '',
    'amenities' => ['Wi-Fi', 'Air Conditioning'],
    'status' => 'Available',
    'featured' => false
];

$formRoom = $editingRoom ?? $defaultRoom;

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Room Management | Azure Bay Resort Admin</title>

    <link rel="stylesheet" href="../../../assets/bootstrap/bootstrap.min.css">

    <style>
        :root {
            --ocean: #073b4c;
            --ocean-dark: #052c39;
            --ocean-light: #0b6573;
            --gold: #c9a45c;
            --gold-light: #ead7a8;
            --cream: #fbf8f1;
            --page: #f5f7f8;
            --white: #fff;
            --text: #273940;
            --muted: #7a898e;
            --border: #e5eaec;
            --green: #198754;
            --red: #c94c4c;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: var(--page);
            color: var(--text);
            font-family: "Trebuchet MS", Arial, sans-serif;
            font-size: 14px;
        }

        a {
            text-decoration: none;
        }

        .sidebar {
            position: fixed;
            inset: 0 auto 0 0;
            z-index: 1040;
            width: 258px;
            overflow-y: auto;
            background: var(--ocean-dark);
            color: #fff;
            transition: transform .25s ease;
        }

        .sidebar-brand {
            display: flex;
            align-items: center;
            gap: 10px;
            min-height: 83px;
            padding: 20px;
            border-bottom: 1px solid rgba(255,255,255,.12);
            color: #fff;
        }

        .brand-mark {
            display: flex;
            align-items: center;
            justify-content: center;
            flex: 0 0 39px;
            width: 39px;
            height: 39px;
            border-radius: 50%;
            background: var(--gold);
            font-size: 21px;
            font-weight: 800;
        }

        .brand-name {
            font-size: 17px;
            font-weight: 800;
            letter-spacing: .6px;
        }

        .brand-caption {
            margin-top: 3px;
            color: #b9cbd0;
            font-size: 10px;
            letter-spacing: 1.5px;
        }

        .sidebar-label {
            padding: 24px 22px 10px;
            color: #8da9af;
            font-size: 10px;
            font-weight: 800;
            letter-spacing: 1.8px;
            text-transform: uppercase;
        }

        .sidebar-menu {
            padding: 0 12px;
            list-style: none;
        }

        .sidebar-menu li {
            margin-bottom: 5px;
        }

        .sidebar-menu a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 13px;
            border-radius: 6px;
            color: #c7d6d9;
            font-size: 13px;
            transition: .2s ease;
        }

        .sidebar-menu a:hover {
            background: rgba(255,255,255,.08);
            color: #fff;
        }

        .sidebar-menu a.active {
            background: var(--gold);
            color: #fff;
            font-weight: 800;
        }

        .menu-icon {
            display: inline-flex;
            justify-content: center;
            width: 21px;
            font-size: 17px;
        }

        .sidebar-bottom {
            margin: 25px 12px 20px;
            padding: 17px;
            border: 1px solid rgba(255,255,255,.14);
            border-radius: 8px;
            background: rgba(255,255,255,.04);
        }

        .sidebar-bottom p {
            margin-bottom: 12px;
            color: #c1d0d3;
            font-size: 12px;
            line-height: 1.7;
        }

        .sidebar-bottom a {
            color: var(--gold-light);
            font-size: 12px;
            font-weight: 800;
        }

        .main-content {
            min-height: 100vh;
            margin-left: 258px;
        }

        .topbar {
            position: sticky;
            top: 0;
            z-index: 1020;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 15px;
            min-height: 83px;
            padding: 14px 30px;
            border-bottom: 1px solid var(--border);
            background: rgba(255,255,255,.97);
        }

        .topbar-left {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .topbar h1 {
            margin: 0;
            color: var(--ocean);
            font-size: 22px;
            font-weight: 800;
        }

        .topbar-subtitle {
            margin-top: 5px;
            color: var(--muted);
            font-size: 12px;
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .date-display {
            color: var(--muted);
            font-size: 12px;
            text-align: right;
        }

        .admin-profile {
            display: flex;
            align-items: center;
            gap: 10px;
            padding-left: 15px;
            border-left: 1px solid var(--border);
        }

        .avatar {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #f3e8cf;
            color: var(--ocean);
            font-weight: 800;
        }

        .profile-name {
            color: var(--ocean);
            font-size: 12px;
            font-weight: 800;
        }

        .profile-role {
            margin-top: 4px;
            color: var(--muted);
            font-size: 11px;
        }

        .mobile-menu-btn {
            display: none;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border: 1px solid var(--border);
            border-radius: 7px;
            background: #fff;
            color: var(--ocean);
            font-size: 19px;
        }

        .content-area {
            padding: 28px 30px 40px;
        }

        .breadcrumb-line {
            margin-bottom: 18px;
            color: var(--muted);
            font-size: 12px;
        }

        .breadcrumb-line a {
            color: var(--ocean-light);
        }

        .page-heading {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 18px;
            margin-bottom: 25px;
        }

        .page-heading h2 {
            margin: 0 0 8px;
            color: var(--ocean);
            font-size: 25px;
            font-weight: 800;
        }

        .page-heading p {
            margin: 0;
            color: var(--muted);
            font-size: 12px;
            line-height: 1.7;
        }

        .btn-gold {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 7px;
            padding: 11px 17px;
            border: 1px solid var(--gold);
            border-radius: 5px;
            background: var(--gold);
            color: #fff;
            font-size: 12px;
            font-weight: 800;
            white-space: nowrap;
        }

        .btn-gold:hover {
            border-color: #b38c43;
            background: #b38c43;
            color: #fff;
        }

        .btn-outline-ocean {
            padding: 9px 13px;
            border: 1px solid #cad6d9;
            border-radius: 5px;
            background: #fff;
            color: var(--ocean);
            font-size: 11px;
            font-weight: 800;
        }

        .btn-outline-ocean:hover {
            border-color: var(--ocean);
            background: var(--ocean);
            color: #fff;
        }

        .stat-card {
            height: 100%;
            padding: 19px;
            border: 1px solid var(--border);
            border-radius: 9px;
            background: #fff;
        }

        .stat-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 15px;
        }

        .stat-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 42px;
            height: 42px;
            border-radius: 9px;
            background: #eef5f6;
            color: var(--ocean-light);
            font-size: 21px;
        }

        .stat-icon.gold {
            background: #f7f0df;
            color: #a7833c;
        }

        .stat-icon.green {
            background: #e7f5ed;
            color: var(--green);
        }

        .stat-icon.red {
            background: #fbeaea;
            color: var(--red);
        }

        .stat-label {
            margin-bottom: 6px;
            color: var(--muted);
            font-size: 12px;
        }

        .stat-value {
            color: var(--ocean);
            font-size: 26px;
            font-weight: 800;
        }

        .stat-footer {
            margin-top: 8px;
            color: #96a2a5;
            font-size: 11px;
        }

        .panel {
            border: 1px solid var(--border);
            border-radius: 9px;
            background: #fff;
        }

        .panel-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 21px 23px;
            border-bottom: 1px solid var(--border);
        }

        .panel-header h3 {
            margin: 0;
            color: var(--ocean);
            font-size: 16px;
            font-weight: 800;
        }

        .panel-header p {
            margin: 6px 0 0;
            color: var(--muted);
            font-size: 11px;
        }

        .panel-body {
            padding: 23px;
        }

        .form-label {
            margin-bottom: 7px;
            color: #51636a;
            font-size: 11px;
            font-weight: 800;
        }

        .form-control,
        .form-select {
            min-height: 42px;
            border-color: #dfe6e8;
            border-radius: 5px;
            color: var(--text);
            font-size: 12px;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--gold);
            box-shadow: 0 0 0 .2rem rgba(201,164,92,.15);
        }

        textarea.form-control {
            min-height: 100px;
            resize: vertical;
        }

        .amenity-option {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            margin: 0 8px 10px 0;
            padding: 8px 10px;
            border: 1px solid var(--border);
            border-radius: 5px;
            background: #fff;
            color: #5f7076;
            font-size: 11px;
        }

        .amenity-option input {
            accent-color: var(--ocean);
        }

        .featured-check {
            display: flex;
            align-items: center;
            gap: 8px;
            color: var(--text);
            font-size: 12px;
        }

        .featured-check input {
            accent-color: var(--gold);
        }

        .filter-area {
            display: grid;
            grid-template-columns: minmax(180px, 1.7fr) repeat(3, minmax(135px, 1fr)) auto;
            gap: 11px;
            padding: 19px 22px;
            border-bottom: 1px solid var(--border);
        }

        .filter-area .form-control,
        .filter-area .form-select {
            min-height: 40px;
        }

        .filter-buttons {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .room-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 18px;
            padding: 22px;
        }

        .room-card {
            min-width: 0;
            overflow: hidden;
            border: 1px solid var(--border);
            border-radius: 8px;
            background: #fff;
            transition: .2s ease;
        }

        .room-card:hover {
            border-color: var(--gold-light);
            box-shadow: 0 7px 20px rgba(7,59,76,.06);
            transform: translateY(-2px);
        }

        .room-image-wrap {
            position: relative;
            height: 175px;
            overflow: hidden;
            background: #eaf0f1;
        }

        .room-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform .3s ease;
        }

        .room-card:hover .room-image {
            transform: scale(1.04);
        }

        .room-number {
            position: absolute;
            top: 12px;
            left: 12px;
            padding: 6px 9px;
            border-radius: 4px;
            background: rgba(5,44,57,.9);
            color: #fff;
            font-size: 10px;
            font-weight: 800;
        }

        .room-featured {
            position: absolute;
            top: 12px;
            right: 12px;
            padding: 6px 9px;
            border-radius: 4px;
            background: var(--gold);
            color: #fff;
            font-size: 10px;
            font-weight: 800;
        }

        .room-card-body {
            padding: 17px;
        }

        .room-type-label {
            margin-bottom: 7px;
            color: #a7833c;
            font-size: 10px;
            font-weight: 800;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .room-name {
            margin-bottom: 8px;
            color: var(--ocean);
            font-size: 16px;
            font-weight: 800;
            line-height: 1.4;
        }

        .room-description {
            display: -webkit-box;
            min-height: 36px;
            margin-bottom: 13px;
            overflow: hidden;
            color: var(--muted);
            font-size: 11px;
            line-height: 1.7;
            -webkit-box-orient: vertical;
            -webkit-line-clamp: 2;
        }

        .room-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            margin-bottom: 14px;
            color: #687a80;
            font-size: 10px;
        }

        .room-meta span {
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .room-price-row {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: 10px;
            padding-top: 13px;
            border-top: 1px solid #edf0f1;
        }

        .room-price {
            color: var(--ocean);
            font-size: 22px;
            font-weight: 800;
        }

        .room-price small {
            color: var(--muted);
            font-size: 10px;
            font-weight: 400;
        }

        .room-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 7px;
            margin-top: 14px;
        }

        .btn-small {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 7px 10px;
            border: 1px solid var(--border);
            border-radius: 4px;
            background: #fff;
            color: var(--ocean);
            font-size: 10px;
            font-weight: 800;
        }

        .btn-small:hover {
            border-color: var(--ocean);
            background: var(--ocean);
            color: #fff;
        }

        .btn-small.danger {
            border-color: #f0d5d5;
            color: var(--red);
        }

        .btn-small.danger:hover {
            border-color: var(--red);
            background: var(--red);
            color: #fff;
        }

        .status-badge {
            display: inline-block;
            padding: 6px 9px;
            border-radius: 20px;
            font-size: 10px;
            font-weight: 800;
            white-space: nowrap;
        }

        .status-available {
            background: #e7f5ed;
            color: #198754;
        }

        .status-occupied {
            background: #e8f0fc;
            color: #315d9b;
        }

        .status-maintenance {
            background: #fbeaea;
            color: #b83e3e;
        }

        .status-cleaning {
            background: #fff2d9;
            color: #9b6b11;
        }

        .empty-state {
            padding: 60px 20px;
            text-align: center;
        }

        .empty-state-icon {
            margin-bottom: 14px;
            color: #b7c5c8;
            font-size: 42px;
        }

        .empty-state h4 {
            color: var(--ocean);
            font-size: 17px;
            font-weight: 800;
        }

        .empty-state p {
            color: var(--muted);
            font-size: 12px;
        }

        .demo-notice {
            margin-top: 24px;
            padding: 14px 16px;
            border-left: 3px solid var(--gold);
            border-radius: 4px;
            background: #fffdf7;
            color: #7a7e78;
            font-size: 11px;
            line-height: 1.8;
        }

        .footer {
            padding: 0 30px 22px;
            color: #97a4a7;
            font-size: 11px;
        }

        .sidebar-overlay {
            display: none;
        }

        @media (max-width: 1399px) {
            .room-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .filter-area {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .filter-area .search-field {
                grid-column: span 2;
            }

            .filter-buttons {
                grid-column: span 2;
            }
        }

        @media (max-width: 991px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.open {
                transform: translateX(0);
            }

            .sidebar-overlay.show {
                position: fixed;
                inset: 0;
                z-index: 1030;
                display: block;
                background: rgba(0,0,0,.4);
            }

            .main-content {
                margin-left: 0;
            }

            .mobile-menu-btn {
                display: inline-flex;
            }

            .content-area {
                padding-right: 20px;
                padding-left: 20px;
            }

            .topbar {
                padding-right: 20px;
                padding-left: 20px;
            }
        }

        @media (max-width: 575px) {
            .topbar {
                min-height: 72px;
                padding: 12px 14px;
            }

            .topbar h1 {
                font-size: 18px;
            }

            .topbar-subtitle,
            .date-display,
            .profile-details {
                display: none;
            }

            .content-area {
                padding: 18px 14px 28px;
            }

            .page-heading {
                align-items: flex-start;
                flex-direction: column;
            }

            .page-heading h2 {
                font-size: 22px;
            }

            .room-grid {
                grid-template-columns: 1fr;
                padding: 14px;
            }

            .filter-area {
                grid-template-columns: 1fr;
                padding: 15px;
            }

            .filter-area .search-field,
            .filter-buttons {
                grid-column: auto;
            }

            .filter-buttons {
                flex-wrap: wrap;
            }

            .panel-body {
                padding: 17px;
            }

            .footer {
                padding: 0 14px 20px;
            }
        }
    </style>
</head>

<body>

<!-- Sidebar Overlay -->
<div class="sidebar-overlay" id="sidebarOverlay"></div>

<!-- Sidebar -->
<aside class="sidebar" id="adminSidebar">

    <a href="index.php" class="sidebar-brand">
        <span class="brand-mark">A</span>

        <span>
            <span class="brand-name d-block">AZURE BAY</span>
            <span class="brand-caption d-block">RESORT ADMINISTRATION</span>
        </span>
    </a>

    <div class="sidebar-label">Main Menu</div>

    <ul class="sidebar-menu">
        <li>
            <a href="index.php">
                <span class="menu-icon">▦</span>
                Dashboard
            </a>
        </li>

        <li>
            <a href="rooms.php" class="active">
                <span class="menu-icon">▤</span>
                Room Management
            </a>
        </li>

        <li>
            <a href="reservations.php">
                <span class="menu-icon">▣</span>
                Reservations
            </a>
        </li>

        <li>
            <a href="customers.php">
                <span class="menu-icon">♙</span>
                Customers
            </a>
        </li>

        <li>
            <a href="guest_services.php">
                <span class="menu-icon">⇄</span>
                Check-in / Check-out
            </a>
        </li>
    </ul>

    <div class="sidebar-label">Financial Management</div>

    <ul class="sidebar-menu">
        <li>
            <a href="payments.php">
                <span class="menu-icon">＄</span>
                Payments
            </a>
        </li>

        <li>
            <a href="discounts.php">
                <span class="menu-icon">％</span>
                Discounts
            </a>
        </li>

        <li>
            <a href="reports.php">
                <span class="menu-icon">▥</span>
                Reports & Analytics
            </a>
        </li>
    </ul>

    <div class="sidebar-label">Room Operations</div>

    <ul class="sidebar-menu">
        <li>
            <a href="availability.php">
                <span class="menu-icon">◷</span>
                Room Availability
            </a>
        </li>
    </ul>

    <div class="sidebar-label">Website</div>

    <ul class="sidebar-menu">
        <li>
            <a href="../index.php" target="_blank" rel="noopener">
                <span class="menu-icon">↗</span>
                Customer Website
            </a>
        </li>

        <li>
            <a href="../azurebay_demo.php">
                <span class="menu-icon">⌂</span>
                Demo Selection
            </a>
        </li>
    </ul>

    <div class="sidebar-bottom">
        <p>
            Manage room details, prices, and room statuses
            using this demonstration.
        </p>

        <a href="../azurebay_demo.php">Return to Demo Portal &rarr;</a>
    </div>

</aside>


<!-- Main Content -->
<main class="main-content">

    <!-- Top Navigation -->
    <header class="topbar">

        <div class="topbar-left">

            <button type="button"
                    class="mobile-menu-btn"
                    id="menuToggle"
                    aria-label="Open navigation menu">
                ☰
            </button>

            <div>
                <h1>Room Management</h1>

                <div class="topbar-subtitle">
                    Manage room inventory, pricing, and availability.
                </div>
            </div>

        </div>

        <div class="topbar-right">

            <div class="date-display">
                <strong><?= e(date('l, d F Y')) ?></strong>
                <div class="mt-1">Sri Lanka Standard Time</div>
            </div>

            <div class="admin-profile">
                <div class="avatar">AD</div>

                <div class="profile-details">
                    <div class="profile-name">Resort Administrator</div>
                    <div class="profile-role">Administrator Demo</div>
                </div>
            </div>

        </div>

    </header>


    <div class="content-area">

        <div class="breadcrumb-line">
            <a href="index.php">Dashboard</a>
            <span class="mx-2">/</span>
            <strong>Room Management</strong>
        </div>


        <!-- Page Heading -->
        <div class="page-heading">

            <div>
                <h2>Room Inventory</h2>

                <p>
                    Add rooms, update nightly rates, manage room statuses,
                    and maintain the resort's room catalogue.
                </p>
            </div>

            <a href="rooms.php#roomForm" class="btn-gold">
                <span>＋</span> Add New Room
            </a>

        </div>


        <!-- Flash Message -->
        <?php if ($flash): ?>
            <div class="alert alert-<?= e($flash['type']) ?> alert-dismissible fade show"
                 role="alert">

                <?= e($flash['message']) ?>

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="alert"
                        aria-label="Close"></button>
            </div>
        <?php endif; ?>


        <!-- Statistics -->
        <div class="row g-3 mb-4">

            <div class="col-6 col-xl-3">
                <div class="stat-card">

                    <div class="stat-top">
                        <div class="stat-icon">▤</div>
                    </div>

                    <div class="stat-label">Total Rooms</div>

                    <div class="stat-value">
                        <?= number_format($totalRooms) ?>
                    </div>

                    <div class="stat-footer">
                        Rooms in the sample inventory
                    </div>

                </div>
            </div>

            <div class="col-6 col-xl-3">
                <div class="stat-card">

                    <div class="stat-top">
                        <div class="stat-icon green">✓</div>
                    </div>

                    <div class="stat-label">Available Rooms</div>

                    <div class="stat-value">
                        <?= number_format($availableRooms) ?>
                    </div>

                    <div class="stat-footer">
                        Ready for potential bookings
                    </div>

                </div>
            </div>

            <div class="col-6 col-xl-3">
                <div class="stat-card">

                    <div class="stat-top">
                        <div class="stat-icon gold">♙</div>
                    </div>

                    <div class="stat-label">Occupied Rooms</div>

                    <div class="stat-value">
                        <?= number_format($occupiedRooms) ?>
                    </div>

                    <div class="stat-footer">
                        Currently marked occupied
                    </div>

                </div>
            </div>

            <div class="col-6 col-xl-3">
                <div class="stat-card">

                    <div class="stat-top">
                        <div class="stat-icon red">⚒</div>
                    </div>

                    <div class="stat-label">Under Maintenance</div>

                    <div class="stat-value">
                        <?= number_format($maintenanceRooms) ?>
                    </div>

                    <div class="stat-footer">
                        Not ready for new guests
                    </div>

                </div>
            </div>

        </div>


        <!-- Add / Edit Room Form -->
        <section class="panel mb-4" id="roomForm">

            <div class="panel-header">

                <div>
                    <h3>
                        <?= $editingRoom ? 'Edit Room Details' : 'Add a New Room' ?>
                    </h3>

                    <p>
                        <?= $editingRoom
                            ? 'Update the selected room information below.'
                            : 'Enter the details of the room you want to add to the catalogue.' ?>
                    </p>
                </div>

                <?php if ($editingRoom): ?>
                    <a href="rooms.php" class="btn-outline-ocean">
                        Cancel Editing
                    </a>
                <?php endif; ?>

            </div>

            <div class="panel-body">

                <form method="POST" action="rooms.php#roomForm">

                    <input type="hidden" name="action" value="save_room">

                    <input type="hidden"
                           name="room_id"
                           value="<?= e($formRoom['id']) ?>">

                    <div class="row g-3">

                        <div class="col-md-6 col-xl-4">
                            <label class="form-label" for="name">
                                Room Name *
                            </label>

                            <input type="text"
                                   class="form-control"
                                   id="name"
                                   name="name"
                                   maxlength="100"
                                   required
                                   placeholder="e.g. Deluxe Ocean View"
                                   value="<?= e($formRoom['name']) ?>">
                        </div>

                        <div class="col-md-6 col-xl-4">
                            <label class="form-label" for="number">
                                Room Number *
                            </label>

                            <input type="text"
                                   class="form-control"
                                   id="number"
                                   name="number"
                                   maxlength="20"
                                   required
                                   placeholder="e.g. A-101"
                                   value="<?= e($formRoom['number']) ?>">
                        </div>

                        <div class="col-md-6 col-xl-4">
                            <label class="form-label" for="type">
                                Room Type *
                            </label>

                            <select class="form-select"
                                    id="type"
                                    name="type"
                                    required>

                                <?php foreach ($validTypes as $type): ?>
                                    <option value="<?= e($type) ?>"
                                        <?= $formRoom['type'] === $type ? 'selected' : '' ?>>
                                        <?= e($type) ?>
                                    </option>
                                <?php endforeach; ?>

                            </select>
                        </div>

                        <div class="col-md-6 col-xl-3">
                            <label class="form-label" for="price">
                                Price per Night (USD) *
                            </label>

                            <input type="number"
                                   class="form-control"
                                   id="price"
                                   name="price"
                                   min="1"
                                   max="100000"
                                   step="0.01"
                                   required
                                   value="<?= e($formRoom['price']) ?>">
                        </div>

                        <div class="col-md-6 col-xl-3">
                            <label class="form-label" for="capacity">
                                Guest Capacity *
                            </label>

                            <input type="number"
                                   class="form-control"
                                   id="capacity"
                                   name="capacity"
                                   min="1"
                                   max="20"
                                   required
                                   value="<?= e($formRoom['capacity']) ?>">
                        </div>

                        <div class="col-md-6 col-xl-3">
                            <label class="form-label" for="beds">
                                Bed Configuration *
                            </label>

                            <input type="text"
                                   class="form-control"
                                   id="beds"
                                   name="beds"
                                   maxlength="100"
                                   required
                                   placeholder="e.g. 1 King Bed"
                                   value="<?= e($formRoom['beds']) ?>">
                        </div>

                        <div class="col-md-6 col-xl-3">
                            <label class="form-label" for="size">
                                Room Size (m²) *
                            </label>

                            <input type="number"
                                   class="form-control"
                                   id="size"
                                   name="size"
                                   min="1"
                                   max="1000"
                                   step="0.5"
                                   required
                                   value="<?= e($formRoom['size']) ?>">
                        </div>

                        <div class="col-md-6 col-xl-4">
                            <label class="form-label" for="floor">
                                Floor / Location *
                            </label>

                            <input type="text"
                                   class="form-control"
                                   id="floor"
                                   name="floor"
                                   maxlength="50"
                                   required
                                   placeholder="e.g. 2nd Floor"
                                   value="<?= e($formRoom['floor']) ?>">
                        </div>

                        <div class="col-md-6 col-xl-4">
                            <label class="form-label" for="image">
                                Room Image
                            </label>

                            <select class="form-select"
                                    id="image"
                                    name="image">

                                <option value="deluxe-ocean.jpg"
                                    <?= $formRoom['image'] === 'deluxe-ocean.jpg' ? 'selected' : '' ?>>
                                    Deluxe Ocean View
                                </option>

                                <option value="garden-room.jpg"
                                    <?= $formRoom['image'] === 'garden-room.jpg' ? 'selected' : '' ?>>
                                    Garden Serenity Room
                                </option>

                                <option value="beach-suite.jpg"
                                    <?= $formRoom['image'] === 'beach-suite.jpg' ? 'selected' : '' ?>>
                                    Premium Beach Suite
                                </option>

                                <option value="family-suite.jpg"
                                    <?= $formRoom['image'] === 'family-suite.jpg' ? 'selected' : '' ?>>
                                    Family Coastal Suite
                                </option>

                                <option value="presidential-villa.jpg"
                                    <?= $formRoom['image'] === 'presidential-villa.jpg' ? 'selected' : '' ?>>
                                    Azure Presidential Villa
                                </option>

                                <option value="horizon-suite.jpg"
                                    <?= $formRoom['image'] === 'horizon-suite.jpg' ? 'selected' : '' ?>>
                                    Horizon Ocean Suite
                                </option>

                                <option value="resort-pool.jpg"
                                    <?= $formRoom['image'] === 'resort-pool.jpg' ? 'selected' : '' ?>>
                                    Resort Pool
                                </option>

                            </select>
                        </div>

                        <div class="col-md-6 col-xl-4">
                            <label class="form-label" for="status">
                                Room Status *
                            </label>

                            <select class="form-select"
                                    id="status"
                                    name="status"
                                    required>

                                <?php foreach ($validStatuses as $status): ?>
                                    <option value="<?= e($status) ?>"
                                        <?= $formRoom['status'] === $status ? 'selected' : '' ?>>
                                        <?= e($status) ?>
                                    </option>
                                <?php endforeach; ?>

                            </select>
                        </div>

                        <div class="col-12">
                            <label class="form-label" for="description">
                                Room Description *
                            </label>

                            <textarea class="form-control"
                                      id="description"
                                      name="description"
                                      maxlength="1000"
                                      required
                                      placeholder="Describe the room, view, and guest experience..."><?= e($formRoom['description']) ?></textarea>
                        </div>

                        <div class="col-12">
                            <label class="form-label d-block">
                                Room Amenities
                            </label>

                            <?php
                                $allAmenities = [
                                    'Wi-Fi',
                                    'Air Conditioning',
                                    'Ocean View',
                                    'Garden View',
                                    'Mini Bar',
                                    'Tea & Coffee',
                                    'Living Area',
                                    'Balcony',
                                    'Private Pool',
                                    'Family Lounge'
                                ];
                            ?>

                            <?php foreach ($allAmenities as $amenity): ?>
                                <label class="amenity-option">
                                    <input type="checkbox"
                                           name="amenities[]"
                                           value="<?= e($amenity) ?>"
                                        <?= in_array($amenity, $formRoom['amenities'], true)
                                            ? 'checked' : '' ?>>

                                    <?= e($amenity) ?>
                                </label>
                            <?php endforeach; ?>
                        </div>

                        <div class="col-12">
                            <label class="featured-check">
                                <input type="checkbox"
                                       name="featured"
                                       value="1"
                                    <?= !empty($formRoom['featured']) ? 'checked' : '' ?>>

                                Feature this room in the demonstration catalogue
                            </label>
                        </div>

                        <div class="col-12">
                            <hr style="border-color:var(--border);">

                            <div class="d-flex flex-wrap gap-2">

                                <button type="submit" class="btn-gold">
                                    <?= $editingRoom
                                        ? 'Save Room Changes'
                                        : '＋ Add Room' ?>
                                </button>

                                <button type="reset"
                                        class="btn-outline-ocean">
                                    Reset Form
                                </button>

                                <?php if ($editingRoom): ?>
                                    <a href="rooms.php"
                                       class="btn-outline-ocean">
                                        Cancel
                                    </a>
                                <?php endif; ?>

                            </div>
                        </div>

                    </div>

                </form>

            </div>

        </section>


        <!-- Room Catalogue -->
        <section class="panel">

            <div class="panel-header">

                <div>
                    <h3>Room Catalogue</h3>

                    <p>
                        <?= count($filteredRooms) ?>
                        room<?= count($filteredRooms) === 1 ? '' : 's' ?>
                        match your current filters.
                    </p>
                </div>

                <span class="status-badge status-available">
                    <?= $totalRooms ?> Total Rooms
                </span>

            </div>


            <!-- Search and Filters -->
            <form method="GET" action="rooms.php" class="filter-area">

                <div class="search-field">
                    <input type="search"
                           class="form-control"
                           name="search"
                           placeholder="Search room name or number..."
                           value="<?= e($search) ?>">
                </div>

                <div>
                    <select class="form-select"
                            name="type"
                            aria-label="Filter by room type">

                        <option value="">All Room Types</option>

                        <?php foreach ($validTypes as $type): ?>
                            <option value="<?= e($type) ?>"
                                <?= $typeFilter === $type ? 'selected' : '' ?>>
                                <?= e($type) ?>
                            </option>
                        <?php endforeach; ?>

                    </select>
                </div>

                <div>
                    <select class="form-select"
                            name="status"
                            aria-label="Filter by status">

                        <option value="">All Statuses</option>

                        <?php foreach ($validStatuses as $status): ?>
                            <option value="<?= e($status) ?>"
                                <?= $statusFilter === $status ? 'selected' : '' ?>>
                                <?= e($status) ?>
                            </option>
                        <?php endforeach; ?>

                    </select>
                </div>

                <div>
                    <select class="form-select"
                            name="sort"
                            aria-label="Sort rooms">

                        <option value="room_number"
                            <?= $sort === 'room_number' ? 'selected' : '' ?>>
                            Room Number
                        </option>

                        <option value="price_low"
                            <?= $sort === 'price_low' ? 'selected' : '' ?>>
                            Price: Low to High
                        </option>

                        <option value="price_high"
                            <?= $sort === 'price_high' ? 'selected' : '' ?>>
                            Price: High to Low
                        </option>

                        <option value="capacity"
                            <?= $sort === 'capacity' ? 'selected' : '' ?>>
                            Guest Capacity
                        </option>

                    </select>
                </div>

                <div class="filter-buttons">

                    <button type="submit" class="btn-gold">
                        Apply
                    </button>

                    <a href="rooms.php" class="btn-outline-ocean">
                        Reset
                    </a>

                </div>

            </form>


            <!-- Room Cards -->
            <?php if (count($filteredRooms) > 0): ?>

                <div class="room-grid">

                    <?php foreach ($filteredRooms as $room): ?>

                        <?php
                            $statusClass = [
                                'Available' => 'status-available',
                                'Occupied' => 'status-occupied',
                                'Maintenance' => 'status-maintenance',
                                'Cleaning' => 'status-cleaning'
                            ][$room['status']] ?? 'status-cleaning';
                        ?>

                        <article class="room-card">

                            <div class="room-image-wrap">

                                <img
                                    src="../assets/images/<?= e(roomImage($room['image'])) ?>"
                                    alt="<?= e($room['name']) ?>"
                                    class="room-image"
                                    loading="lazy"
                                    onerror="this.onerror=null;this.src='../assets/images/resort-pool.jpg';"
                                >

                                <span class="room-number">
                                    <?= e($room['number']) ?>
                                </span>

                                <?php if (!empty($room['featured'])): ?>
                                    <span class="room-featured">
                                        FEATURED
                                    </span>
                                <?php endif; ?>

                            </div>

                            <div class="room-card-body">

                                <div class="d-flex align-items-center
                                            justify-content-between gap-2 mb-2">

                                    <div class="room-type-label mb-0">
                                        <?= e($room['type']) ?>
                                    </div>

                                    <span class="status-badge <?= e($statusClass) ?>">
                                        <?= e($room['status']) ?>
                                    </span>

                                </div>

                                <h4 class="room-name">
                                    <?= e($room['name']) ?>
                                </h4>

                                <p class="room-description">
                                    <?= e($room['description']) ?>
                                </p>

                                <div class="room-meta">

                                    <span>
                                        ♙ <?= (int) $room['capacity'] ?>
                                        Guests
                                    </span>

                                    <span>
                                        ▧ <?= e($room['size']) ?> m²
                                    </span>

                                    <span>
                                        ▤ <?= e($room['beds']) ?>
                                    </span>

                                </div>

                                <?php if (!empty($room['amenities'])): ?>
                                    <div class="d-flex flex-wrap gap-1 mb-3">

                                        <?php foreach (array_slice($room['amenities'], 0, 3) as $amenity): ?>
                                            <span class="badge text-bg-light border"
                                                  style="font-size:9px;font-weight:400;color:#687a80 !important;">
                                                <?= e($amenity) ?>
                                            </span>
                                        <?php endforeach; ?>

                                        <?php if (count($room['amenities']) > 3): ?>
                                            <span class="badge text-bg-light border"
                                                  style="font-size:9px;font-weight:400;color:#687a80 !important;">
                                                +<?= count($room['amenities']) - 3 ?> more
                                            </span>
                                        <?php endif; ?>

                                    </div>
                                <?php endif; ?>

                                <div class="room-price-row">

                                    <div>
                                        <div class="room-price">
                                            <?= e(money($room['price'])) ?>
                                        </div>

                                        <small class="text-secondary">
                                            per night
                                        </small>
                                    </div>

                                    <a href="rooms.php?edit=<?= (int) $room['id'] ?>#roomForm"
                                       class="btn-small">
                                        Edit Details
                                    </a>

                                </div>


                                <!-- Quick Price and Status Update -->
                                <details class="mt-3">

                                    <summary
                                        style="cursor:pointer;color:var(--ocean-light);font-size:11px;font-weight:800;">
                                        Quick Price / Status Update
                                    </summary>

                                    <form method="POST"
                                          action="rooms.php"
                                          class="mt-3">

                                        <input type="hidden"
                                               name="action"
                                               value="quick_update">

                                        <input type="hidden"
                                               name="room_id"
                                               value="<?= (int) $room['id'] ?>">

                                        <div class="mb-2">
                                            <label class="form-label">
                                                Nightly Price (USD)
                                            </label>

                                            <input type="number"
                                                   class="form-control"
                                                   name="price"
                                                   min="1"
                                                   max="100000"
                                                   step="0.01"
                                                   required
                                                   value="<?= e($room['price']) ?>">
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">
                                                Room Status
                                            </label>

                                            <select class="form-select"
                                                    name="status"
                                                    required>

                                                <?php foreach ($validStatuses as $status): ?>
                                                    <option value="<?= e($status) ?>"
                                                        <?= $room['status'] === $status ? 'selected' : '' ?>>
                                                        <?= e($status) ?>
                                                    </option>
                                                <?php endforeach; ?>

                                            </select>
                                        </div>

                                        <button type="submit"
                                                class="btn-gold w-100">
                                            Save Price and Status
                                        </button>

                                    </form>

                                </details>


                                <!-- Delete Room -->
                                <div class="room-actions">

                                    <form method="POST"
                                          action="rooms.php"
                                          onsubmit="return confirm('Are you sure you want to remove this room from the demonstration catalogue?');">

                                        <input type="hidden"
                                               name="action"
                                               value="delete_room">

                                        <input type="hidden"
                                               name="room_id"
                                               value="<?= (int) $room['id'] ?>">

                                        <button type="submit"
                                                class="btn-small danger">
                                            Delete Room
                                        </button>

                                    </form>

                                </div>

                            </div>

                        </article>

                    <?php endforeach; ?>

                </div>

            <?php else: ?>

                <div class="empty-state">

                    <div class="empty-state-icon">⌕</div>

                    <h4>No Rooms Found</h4>

                    <p>
                        No rooms match your search or selected filters.
                        Try different search terms or reset the filters.
                    </p>

                    <a href="rooms.php" class="btn-gold">
                        Show All Rooms
                    </a>

                </div>

            <?php endif; ?>

        </section>


        <!-- Demonstration Notice -->
        <div class="demo-notice">
            <strong>Demonstration notice:</strong>
            Room additions, edits, price changes, status updates, and
            deletions are stored in the current PHP session. They are not
            saved to a database and may be reset when the session expires.
            This page does not automatically update the customer-facing
            room catalogue or check existing reservations before changing
            room status.
        </div>

    </div>


    <!-- Footer -->
    <footer class="footer d-flex flex-column flex-md-row
                   justify-content-between gap-2">

        <span>
            &copy; <?= date('Y') ?> Azure Bay Resort · Admin Demo
        </span>

        <span>
            Developed for the Codvanta portfolio
        </span>

    </footer>

</main>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>


<script>
    // Responsive sidebar
    const sidebar = document.getElementById('adminSidebar');
    const overlay = document.getElementById('sidebarOverlay');
    const menuToggle = document.getElementById('menuToggle');

    function closeSidebar() {
        sidebar.classList.remove('open');
        overlay.classList.remove('show');
    }

    if (menuToggle) {
        menuToggle.addEventListener('click', function () {
            sidebar.classList.toggle('open');
            overlay.classList.toggle('show');
        });
    }

    overlay.addEventListener('click', closeSidebar);

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') {
            closeSidebar();
        }
    });

    // Validate the room price before submission.
    document.querySelectorAll('input[name="price"]').forEach(function (input) {
        input.addEventListener('change', function () {
            if (Number(this.value) <= 0) {
                this.setCustomValidity('Please enter a price greater than zero.');
            } else {
                this.setCustomValidity('');
            }
        });
    });
</script>

</body>
</html>