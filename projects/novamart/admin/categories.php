
<?php
session_start();

/*
|--------------------------------------------------------------------------
| NovaMart - Category Management
|--------------------------------------------------------------------------
| Demonstration version:
| - Categories are stored in the PHP session.
| - Products are sample demonstration data.
| - No database is required.
*/

function e($value)
{
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

/*
|--------------------------------------------------------------------------
| Initialize demonstration products
|--------------------------------------------------------------------------
| These sample products are used to calculate product counts.
| The category names match the NovaMart shop categories.
*/

if (!isset($_SESSION['novamart_admin_products'])) {
    $_SESSION['novamart_admin_products'] = [
        [
            'id' => 1,
            'name' => 'Wireless Bluetooth Earbuds',
            'category' => 'Electronics',
            'price' => 4500,
            'stock' => 25
        ],
        [
            'id' => 2,
            'name' => 'Smart Watch Pro',
            'category' => 'Electronics',
            'price' => 8500,
            'stock' => 18
        ],
        [
            'id' => 3,
            'name' => 'Gaming Mouse',
            'category' => 'Gaming',
            'price' => 3500,
            'stock' => 30
        ],
        [
            'id' => 4,
            'name' => 'Laptop Backpack',
            'category' => 'Accessories',
            'price' => 4200,
            'stock' => 12
        ],
        [
            'id' => 5,
            'name' => 'Wireless Keyboard',
            'category' => 'Computing',
            'price' => 5500,
            'stock' => 15
        ],
        [
            'id' => 6,
            'name' => 'Portable Blender',
            'category' => 'Home Appliances',
            'price' => 6800,
            'stock' => 8
        ],
        [
            'id' => 7,
            'name' => 'Casual T-Shirt',
            'category' => 'Fashion',
            'price' => 2500,
            'stock' => 40
        ],
        [
            'id' => 8,
            'name' => 'Face Care Set',
            'category' => 'Beauty',
            'price' => 3200,
            'stock' => 14
        ],
        [
            'id' => 9,
            'name' => 'Gaming Headset',
            'category' => 'Gaming',
            'price' => 7200,
            'stock' => 10
        ],
        [
            'id' => 10,
            'name' => 'Building Blocks Set',
            'category' => 'Toys & Games',
            'price' => 2800,
            'stock' => 20
        ],
        [
            'id' => 11,
            'name' => 'USB-C Hub',
            'category' => 'Accessories',
            'price' => 3000,
            'stock' => 22
        ],
        [
            'id' => 12,
            'name' => 'Laptop Stand',
            'category' => 'Computing',
            'price' => 4000,
            'stock' => 16
        ]
    ];
}

/*
|--------------------------------------------------------------------------
| Initialize categories
|--------------------------------------------------------------------------
*/

if (!isset($_SESSION['novamart_admin_categories'])) {
    $_SESSION['novamart_admin_categories'] = [
        [
            'id' => 1,
            'name' => 'Electronics',
            'description' => 'Electronic devices and smart gadgets.',
            'status' => 'Active'
        ],
        [
            'id' => 2,
            'name' => 'Computing',
            'description' => 'Computers and computer accessories.',
            'status' => 'Active'
        ],
        [
            'id' => 3,
            'name' => 'Accessories',
            'description' => 'Useful everyday and technology accessories.',
            'status' => 'Active'
        ],
        [
            'id' => 4,
            'name' => 'Home Appliances',
            'description' => 'Products for modern homes.',
            'status' => 'Active'
        ],
        [
            'id' => 5,
            'name' => 'Fashion',
            'description' => 'Clothing and fashion products.',
            'status' => 'Active'
        ],
        [
            'id' => 6,
            'name' => 'Beauty',
            'description' => 'Beauty and personal care products.',
            'status' => 'Active'
        ],
        [
            'id' => 7,
            'name' => 'Gaming',
            'description' => 'Gaming equipment and accessories.',
            'status' => 'Active'
        ],
        [
            'id' => 8,
            'name' => 'Toys & Games',
            'description' => 'Toys, games, and entertainment products.',
            'status' => 'Active'
        ],
        [
            'id' => 9,
            'name' => 'Sports & Fitness',
            'description' => 'Sports equipment and fitness products.',
            'status' => 'Active'
        ],
        [
            'id' => 10,
            'name' => 'Office Supplies',
            'description' => 'Office stationery and workplace essentials.',
            'status' => 'Active'
        ]
    ];
}

$categories = &$_SESSION['novamart_admin_categories'];
$products = &$_SESSION['novamart_admin_products'];

$message = '';
$messageType = 'success';

/*
|--------------------------------------------------------------------------
| Count products by category
|--------------------------------------------------------------------------
*/

function getCategoryProductCount($categoryName, $products)
{
    $count = 0;

    foreach ($products as $product) {
        if (
            isset($product['category']) &&
            strcasecmp($product['category'], $categoryName) === 0
        ) {
            $count++;
        }
    }

    return $count;
}

/*
|--------------------------------------------------------------------------
| Handle category actions
|--------------------------------------------------------------------------
*/

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $action = $_POST['action'] ?? '';

    /*
    |--------------------------------------------------------------------------
    | Add category
    |--------------------------------------------------------------------------
    */

    if ($action === 'add_category') {

        $name = trim($_POST['name'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $status = $_POST['status'] ?? 'Active';

        if ($name === '') {
            $message = 'Please enter a category name.';
            $messageType = 'danger';

        } elseif (!in_array($status, ['Active', 'Inactive'], true)) {
            $message = 'Please select a valid category status.';
            $messageType = 'danger';

        } else {

            $duplicate = false;

            foreach ($categories as $category) {
                if (strcasecmp($category['name'], $name) === 0) {
                    $duplicate = true;
                    break;
                }
            }

            if ($duplicate) {
                $message = 'A category with this name already exists.';
                $messageType = 'danger';

            } else {

                $newId = 1;

                foreach ($categories as $category) {
                    $newId = max($newId, (int)$category['id'] + 1);
                }

                $categories[] = [
                    'id' => $newId,
                    'name' => $name,
                    'description' => $description,
                    'status' => $status
                ];

                $message = 'Category added successfully.';
            }
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Edit category
    |--------------------------------------------------------------------------
    */

    elseif ($action === 'edit_category') {

        $id = (int)($_POST['category_id'] ?? 0);
        $name = trim($_POST['name'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $status = $_POST['status'] ?? 'Active';

        if ($name === '') {
            $message = 'Category name cannot be empty.';
            $messageType = 'danger';

        } elseif (!in_array($status, ['Active', 'Inactive'], true)) {
            $message = 'Please select a valid category status.';
            $messageType = 'danger';

        } else {

            $found = false;
            $duplicate = false;
            $oldName = '';

            foreach ($categories as $category) {

                if (
                    (int)$category['id'] !== $id &&
                    strcasecmp($category['name'], $name) === 0
                ) {
                    $duplicate = true;
                    break;
                }
            }

            if ($duplicate) {
                $message = 'Another category already uses this name.';
                $messageType = 'danger';

            } else {

                foreach ($categories as &$category) {

                    if ((int)$category['id'] === $id) {

                        $found = true;
                        $oldName = $category['name'];

                        $category['name'] = $name;
                        $category['description'] = $description;
                        $category['status'] = $status;

                        break;
                    }
                }

                unset($category);

                if ($found) {

                    /*
                     * Keep product category assignments synchronized
                     * when a category name changes.
                     */

                    if (strcasecmp($oldName, $name) !== 0) {

                        foreach ($products as &$product) {

                            if (
                                isset($product['category']) &&
                                strcasecmp(
                                    $product['category'],
                                    $oldName
                                ) === 0
                            ) {
                                $product['category'] = $name;
                            }
                        }

                        unset($product);
                    }

                    $message = 'Category updated successfully.';

                } else {
                    $message = 'The selected category could not be found.';
                    $messageType = 'danger';
                }
            }
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Delete unused category
    |--------------------------------------------------------------------------
    */

    elseif ($action === 'delete_category') {

        $id = (int)($_POST['category_id'] ?? 0);
        $foundIndex = null;

        foreach ($categories as $index => $category) {
            if ((int)$category['id'] === $id) {
                $foundIndex = $index;
                break;
            }
        }

        if ($foundIndex === null) {

            $message = 'Category not found.';
            $messageType = 'danger';

        } else {

            $categoryName = $categories[$foundIndex]['name'];
            $productCount = getCategoryProductCount(
                $categoryName,
                $products
            );

            if ($productCount > 0) {

                $message = 'Cannot delete "' . $categoryName .
                    '" because it has ' . $productCount .
                    ' assigned product(s).';
                $messageType = 'danger';

            } else {

                array_splice($categories, $foundIndex, 1);

                $message = 'Unused category "' .
                    $categoryName . '" deleted successfully.';
            }
        }
    }
}

/*
|--------------------------------------------------------------------------
| Search and filter
|--------------------------------------------------------------------------
*/

$search = trim($_GET['search'] ?? '');
$statusFilter = $_GET['status'] ?? 'All';
$sort = $_GET['sort'] ?? 'name_asc';

$validStatuses = ['All', 'Active', 'Inactive'];

if (!in_array($statusFilter, $validStatuses, true)) {
    $statusFilter = 'All';
}

$validSorts = ['name_asc', 'name_desc', 'products_desc'];

if (!in_array($sort, $validSorts, true)) {
    $sort = 'name_asc';
}

$filteredCategories = [];

foreach ($categories as $category) {

    $matchesSearch =
        $search === '' ||
        stripos($category['name'], $search) !== false ||
        stripos($category['description'], $search) !== false;

    $matchesStatus =
        $statusFilter === 'All' ||
        $category['status'] === $statusFilter;

    if ($matchesSearch && $matchesStatus) {
        $filteredCategories[] = $category;
    }
}

usort($filteredCategories, function ($a, $b) use ($sort, $products) {

    if ($sort === 'name_desc') {
        return strcasecmp($b['name'], $a['name']);
    }

    if ($sort === 'products_desc') {
        return getCategoryProductCount($b['name'], $products)
            <=> getCategoryProductCount($a['name'], $products);
    }

    return strcasecmp($a['name'], $b['name']);
});

/*
|--------------------------------------------------------------------------
| Summary statistics
|--------------------------------------------------------------------------
*/

$totalCategories = count($categories);
$activeCategories = 0;
$inactiveCategories = 0;
$unusedCategories = 0;

foreach ($categories as $category) {

    if ($category['status'] === 'Active') {
        $activeCategories++;
    } else {
        $inactiveCategories++;
    }

    if (getCategoryProductCount($category['name'], $products) === 0) {
        $unusedCategories++;
    }
}

$totalProducts = count($products);

$categoryIcons = [
    'Electronics' => 'bi-cpu',
    'Computing' => 'bi-laptop',
    'Accessories' => 'bi-headphones',
    'Home Appliances' => 'bi-house-door',
    'Fashion' => 'bi-bag',
    'Beauty' => 'bi-stars',
    'Gaming' => 'bi-controller',
    'Toys & Games' => 'bi-puzzle',
    'Sports & Fitness' => 'bi-bicycle',
    'Office Supplies' => 'bi-briefcase'
];

$categoryColors = [
    'Electronics' => 'blue',
    'Computing' => 'purple',
    'Accessories' => 'orange',
    'Home Appliances' => 'green',
    'Fashion' => 'pink',
    'Beauty' => 'purple',
    'Gaming' => 'orange',
    'Toys & Games' => 'green',
    'Sports & Fitness' => 'blue',
    'Office Supplies' => 'pink'
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Category Management | NovaMart Admin</title>

    <link rel="stylesheet" href="../../../assets/bootstrap/bootstrap.min.css">
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        :root {
            --navy: #142747;
            --navy-dark: #0e1c33;
            --blue: #315efb;
            --page-bg: #f4f7fc;
            --text-main: #24324b;
            --text-muted: #7a879c;
            --border: #e8edf5;
            --white: #ffffff;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: var(--page-bg);
            color: var(--text-main);
            font-family: "Trebuchet MS", Arial, sans-serif;
            font-size: 14px;
        }

        a {
            text-decoration: none;
        }

        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            width: 250px;
            background: var(--navy);
            color: #fff;
            padding: 25px 17px;
            overflow-y: auto;
            z-index: 1000;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 11px;
            padding: 0 12px 29px;
            color: #fff;
        }

        .brand-icon {
            width: 43px;
            height: 43px;
            border-radius: 13px;
            display: grid;
            place-items: center;
            background: var(--blue);
            font-size: 22px;
        }

        .brand-name {
            font-size: 22px;
            font-weight: 800;
            letter-spacing: -.6px;
        }

        .brand small {
            display: block;
            color: #aebbd0;
            font-size: 10px;
            letter-spacing: 1.5px;
            margin-top: 2px;
        }

        .nav-label {
            color: #8fa1bd;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.4px;
            padding: 13px 13px 9px;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 13px;
            padding: 12px 14px;
            border-radius: 9px;
            margin-bottom: 5px;
            color: #c5d0e1;
            transition: .2s;
            font-size: 13px;
        }

        .sidebar-link i {
            font-size: 17px;
            width: 21px;
            text-align: center;
        }

        .sidebar-link:hover,
        .sidebar-link.active {
            color: #fff;
            background: #29466f;
        }

        .sidebar-link.active {
            box-shadow: inset 3px 0 #7090ff;
        }

        .sidebar-bottom {
            border-top: 1px solid #344864;
            padding: 18px 12px 0;
            margin-top: 30px;
            color: #aebbd0;
            font-size: 11px;
            line-height: 1.7;
        }

        .main {
            margin-left: 250px;
            min-height: 100vh;
        }

        .topbar {
            background: #fff;
            min-height: 76px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 15px;
            padding: 15px 32px;
            border-bottom: 1px solid var(--border);
        }

        .breadcrumb-text {
            color: var(--text-muted);
            font-size: 12px;
            margin-bottom: 4px;
        }

        .breadcrumb-text span {
            color: var(--text-main);
        }

        .page-title {
            font-size: 23px;
            font-weight: 800;
            margin: 0;
            letter-spacing: -.5px;
        }

        .admin-profile {
            display: flex;
            align-items: center;
            gap: 11px;
        }

        .admin-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #e9efff;
            color: var(--blue);
            display: grid;
            place-items: center;
            font-weight: 800;
            font-size: 14px;
        }

        .admin-name {
            font-size: 12px;
            font-weight: 800;
        }

        .admin-role {
            font-size: 11px;
            color: var(--text-muted);
            margin-top: 2px;
        }

        .content {
            padding: 29px 32px 40px;
            max-width: 1700px;
            margin: auto;
        }

        .page-intro {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 20px;
            margin-bottom: 25px;
        }

        .page-intro h2 {
            font-size: 19px;
            font-weight: 800;
            margin-bottom: 7px;
        }

        .page-intro p {
            color: var(--text-muted);
            margin: 0;
            line-height: 1.7;
        }

        .btn-primary-custom {
            background: var(--blue);
            border: 1px solid var(--blue);
            color: #fff;
            padding: 11px 17px;
            border-radius: 9px;
            font-weight: 700;
            white-space: nowrap;
        }

        .btn-primary-custom:hover {
            background: #234be0;
            color: #fff;
        }

        .stat-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 17px;
            margin-bottom: 25px;
        }

        .stat-card {
            background: #fff;
            padding: 21px;
            border: 1px solid var(--border);
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            box-shadow: 0 3px 12px rgba(28, 47, 80, .025);
        }

        .stat-label {
            color: var(--text-muted);
            font-size: 12px;
            margin-bottom: 9px;
        }

        .stat-number {
            font-size: 28px;
            font-weight: 800;
            letter-spacing: -.8px;
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 13px;
            display: grid;
            place-items: center;
            font-size: 22px;
            flex-shrink: 0;
        }

        .icon-blue {
            color: #315efb;
            background: #eaf0ff;
        }

        .icon-green {
            color: #129568;
            background: #e5f8f0;
        }

        .icon-orange {
            color: #d97706;
            background: #fff2df;
        }

        .icon-purple {
            color: #7c3aed;
            background: #f1eaff;
        }

        .panel {
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 14px;
            box-shadow: 0 3px 12px rgba(28, 47, 80, .025);
            overflow: hidden;
            margin-bottom: 24px;
        }

        .panel-header {
            padding: 21px 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 15px;
            border-bottom: 1px solid var(--border);
        }

        .panel-header h3 {
            font-size: 15px;
            font-weight: 800;
            margin: 0 0 5px;
        }

        .panel-header p {
            color: var(--text-muted);
            font-size: 12px;
            margin: 0;
        }

        .panel-body {
            padding: 23px 24px;
        }

        .filter-row {
            display: grid;
            grid-template-columns: minmax(180px, 1.5fr) minmax(140px, .8fr) minmax(160px, .9fr) auto;
            gap: 12px;
            align-items: end;
        }

        .form-label {
            font-size: 12px;
            font-weight: 700;
            margin-bottom: 7px;
            color: #45536b;
        }

        .form-control,
        .form-select {
            border: 1px solid #dfe6f0;
            border-radius: 9px;
            min-height: 43px;
            font-size: 13px;
            box-shadow: none !important;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #8aa5ff;
        }

        .btn-filter {
            background: var(--navy);
            color: #fff;
            border: 0;
            border-radius: 9px;
            min-height: 43px;
            padding: 0 19px;
            font-weight: 700;
        }

        .btn-filter:hover {
            background: #263f67;
            color: #fff;
        }

        .category-table {
            width: 100%;
            border-collapse: collapse;
        }

        .category-table th {
            padding: 14px 20px;
            color: #7b879b;
            background: #f8faff;
            text-align: left;
            font-size: 10px;
            letter-spacing: .8px;
            text-transform: uppercase;
            font-weight: 800;
            white-space: nowrap;
            border-bottom: 1px solid var(--border);
        }

        .category-table td {
            padding: 17px 20px;
            border-bottom: 1px solid #eef1f6;
            vertical-align: middle;
            font-size: 12px;
        }

        .category-table tbody tr:last-child td {
            border-bottom: 0;
        }

        .category-table tbody tr:hover {
            background: #fbfcff;
        }

        .category-name {
            display: flex;
            align-items: center;
            gap: 12px;
            min-width: 180px;
        }

        .category-icon {
            width: 43px;
            height: 43px;
            border-radius: 12px;
            display: grid;
            place-items: center;
            font-size: 19px;
            flex-shrink: 0;
        }

        .color-blue {
            background: #eaf0ff;
            color: #315efb;
        }

        .color-purple {
            background: #f1eaff;
            color: #7c3aed;
        }

        .color-orange {
            background: #fff2df;
            color: #d97706;
        }

        .color-green {
            background: #e5f8f0;
            color: #129568;
        }

        .color-pink {
            background: #ffe9f1;
            color: #d63384;
        }

        .category-name strong {
            display: block;
            font-size: 13px;
            font-weight: 800;
            margin-bottom: 4px;
        }

        .category-name small {
            color: var(--text-muted);
            font-size: 10px;
        }

        .description {
            color: #68768d;
            max-width: 270px;
            line-height: 1.6;
        }

        .count-pill {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            background: #edf2ff;
            color: #315efb;
            padding: 7px 11px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 800;
            white-space: nowrap;
        }

        .count-pill.zero {
            background: #fff2df;
            color: #b96b08;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 10px;
            border-radius: 20px;
            font-size: 10px;
            font-weight: 800;
        }

        .status-active {
            background: #e5f8f0;
            color: #13865e;
        }

        .status-inactive {
            background: #edf0f5;
            color: #657187;
        }

        .action-group {
            display: flex;
            gap: 7px;
            align-items: center;
        }

        .btn-action {
            width: 34px;
            height: 34px;
            border: 1px solid #e2e8f1;
            border-radius: 8px;
            display: inline-grid;
            place-items: center;
            background: #fff;
            color: #596780;
            transition: .2s;
        }

        .btn-action:hover {
            background: #edf2ff;
            color: var(--blue);
            border-color: #cdd8ff;
        }

        .btn-action.delete:hover {
            background: #fff0f0;
            color: #d92d20;
            border-color: #ffd0d0;
        }

        .btn-action:disabled {
            opacity: .35;
            cursor: not-allowed;
        }

        .empty-state {
            text-align: center;
            padding: 55px 20px;
        }

        .empty-state i {
            font-size: 38px;
            color: #9aa8bf;
        }

        .empty-state h4 {
            font-size: 15px;
            font-weight: 800;
            margin: 14px 0 7px;
        }

        .empty-state p {
            color: var(--text-muted);
            font-size: 12px;
        }

        .info-note {
            display: flex;
            gap: 12px;
            align-items: flex-start;
            background: #edf4ff;
            border: 1px solid #d8e6ff;
            color: #35527f;
            padding: 15px 17px;
            border-radius: 11px;
            font-size: 12px;
            line-height: 1.7;
            margin-bottom: 24px;
        }

        .info-note i {
            font-size: 18px;
            margin-top: 1px;
        }

        .footer {
            color: #98a3b5;
            font-size: 11px;
            text-align: center;
            padding: 8px 0 22px;
        }

        .modal-content {
            border: 0;
            border-radius: 16px;
            overflow: hidden;
        }

        .modal-header {
            padding: 22px 24px;
            border-bottom: 1px solid var(--border);
        }

        .modal-title {
            font-size: 17px;
            font-weight: 800;
        }

        .modal-body {
            padding: 24px;
        }

        .modal-footer {
            padding: 17px 24px;
            border-top: 1px solid var(--border);
        }

        .btn-light-custom {
            background: #f1f4f9;
            color: #475467;
            border: 0;
            border-radius: 9px;
            padding: 10px 17px;
            font-weight: 700;
        }

        .btn-save {
            background: var(--blue);
            color: #fff;
            border: 0;
            border-radius: 9px;
            padding: 10px 18px;
            font-weight: 700;
        }

        .btn-save:hover {
            color: #fff;
            background: #234be0;
        }

        .alert {
            border-radius: 10px;
            font-size: 13px;
        }

        @media (max-width: 1200px) {
            .stat-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .filter-row {
                grid-template-columns: 1fr 1fr;
            }

            .category-table th,
            .category-table td {
                padding: 14px;
            }
        }

        @media (max-width: 900px) {
            .sidebar {
                width: 76px;
                padding: 22px 10px;
            }

            .brand {
                justify-content: center;
                padding: 0 0 27px;
            }

            .brand-text,
            .nav-label,
            .sidebar-link span,
            .sidebar-bottom {
                display: none;
            }

            .sidebar-link {
                justify-content: center;
                padding: 13px 8px;
            }

            .sidebar-link i {
                font-size: 19px;
            }

            .main {
                margin-left: 76px;
            }

            .content {
                padding: 24px 20px;
            }

            .topbar {
                padding: 15px 20px;
            }
        }

        @media (max-width: 650px) {
            .topbar {
                padding: 14px;
            }

            .page-title {
                font-size: 19px;
            }

            .admin-profile .admin-info {
                display: none;
            }

            .content {
                padding: 20px 13px;
            }

            .page-intro {
                flex-direction: column;
            }

            .stat-grid {
                grid-template-columns: 1fr 1fr;
                gap: 10px;
            }

            .stat-card {
                padding: 15px;
            }

            .stat-number {
                font-size: 23px;
            }

            .stat-icon {
                width: 39px;
                height: 39px;
                font-size: 18px;
            }

            .filter-row {
                grid-template-columns: 1fr;
            }

            .panel-header,
            .panel-body {
                padding: 17px;
            }

            .table-responsive {
                overflow-x: auto;
            }

            .category-table {
                min-width: 850px;
            }
        }
    </style>
</head>

<body>

<!-- SIDEBAR -->
<aside class="sidebar">

    <a href="index.php" class="brand">
        <div class="brand-icon">
            <i class="bi bi-bag-check-fill"></i>
        </div>

        <div class="brand-text">
            <div class="brand-name">NovaMart</div>
            <small>ADMIN PANEL</small>
        </div>
    </a>

    <div class="nav-label">Main Menu</div>

    <a href="index.php" class="sidebar-link">
        <i class="bi bi-grid-1x2-fill"></i>
        <span>Dashboard</span>
    </a>

    <a href="products.php" class="sidebar-link">
        <i class="bi bi-box-seam"></i>
        <span>Product Management</span>
    </a>

    <a href="categories.php" class="sidebar-link active">
        <i class="bi bi-folder2-open"></i>
        <span>Categories</span>
    </a>

    <a href="inventory.php" class="sidebar-link">
        <i class="bi bi-clipboard-data"></i>
        <span>Inventory</span>
    </a>

    <a href="orders.php" class="sidebar-link">
        <i class="bi bi-receipt"></i>
        <span>Orders</span>
    </a>

    <a href="customers.php" class="sidebar-link">
        <i class="bi bi-people"></i>
        <span>Customers</span>
    </a>

    <a href="reports.php" class="sidebar-link">
        <i class="bi bi-bar-chart-line"></i>
        <span>Sales Reports</span>
    </a>

    <div class="nav-label">Store</div>

    <a href="../index.php" class="sidebar-link">
        <i class="bi bi-shop"></i>
        <span>View Store</span>
    </a>

    <div class="sidebar-bottom">
        <div><i class="bi bi-shield-check me-1"></i> Admin workspace</div>
        <div>NovaMart Demo v1.0</div>
    </div>

</aside>

<!-- MAIN CONTENT -->
<main class="main">

    <!-- TOP BAR -->
    <header class="topbar">

        <div>
            <div class="breadcrumb-text">
                Admin Panel / <span>Category Management</span>
            </div>
            <h1 class="page-title">Category Management</h1>
        </div>

        <div class="admin-profile">
            <div class="admin-avatar">AD</div>
            <div class="admin-info">
                <div class="admin-name">Store Administrator</div>
                <div class="admin-role">Super Admin</div>
            </div>
        </div>

    </header>

    <div class="content">

        <!-- PAGE INTRO -->
        <section class="page-intro">

            <div>
                <h2>Organize your products</h2>
                <p>
                    Create and maintain product categories to keep your
                    NovaMart store organized and easy to browse.
                </p>
            </div>

            <button type="button"
                    class="btn btn-primary-custom"
                    data-bs-toggle="modal"
                    data-bs-target="#categoryModal"
                    onclick="openAddModal()">
                <i class="bi bi-plus-lg me-2"></i>
                Add Category
            </button>

        </section>

        <!-- FEEDBACK MESSAGE -->
        <?php if ($message !== ''): ?>
            <div class="alert alert-<?= e($messageType) ?> alert-dismissible fade show"
                 role="alert">
                <i class="bi bi-info-circle-fill me-2"></i>
                <?= e($message) ?>

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="alert"
                        aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <!-- SUMMARY CARDS -->
        <section class="stat-grid">

            <div class="stat-card">
                <div>
                    <div class="stat-label">Total Categories</div>
                    <div class="stat-number">
                        <?= number_format($totalCategories) ?>
                    </div>
                </div>
                <div class="stat-icon icon-blue">
                    <i class="bi bi-folder-fill"></i>
                </div>
            </div>

            <div class="stat-card">
                <div>
                    <div class="stat-label">Active Categories</div>
                    <div class="stat-number">
                        <?= number_format($activeCategories) ?>
                    </div>
                </div>
                <div class="stat-icon icon-green">
                    <i class="bi bi-check-circle-fill"></i>
                </div>
            </div>

            <div class="stat-card">
                <div>
                    <div class="stat-label">Inactive Categories</div>
                    <div class="stat-number">
                        <?= number_format($inactiveCategories) ?>
                    </div>
                </div>
                <div class="stat-icon icon-purple">
                    <i class="bi bi-folder-minus"></i>
                </div>
            </div>

            <div class="stat-card">
                <div>
                    <div class="stat-label">Unused Categories</div>
                    <div class="stat-number">
                        <?= number_format($unusedCategories) ?>
                    </div>
                </div>
                <div class="stat-icon icon-orange">
                    <i class="bi bi-folder-x"></i>
                </div>
            </div>

        </section>

        <!-- INFORMATION -->
        <div class="info-note">
            <i class="bi bi-info-circle-fill"></i>
            <div>
                <strong>Category management tip</strong><br>
                A category can only be deleted when it has no assigned
                products. If a category contains products, edit its name
                instead or reassign its products before deleting it.
                Inactive categories remain available in this administrator
                demonstration but should be excluded from the public shop.
            </div>
        </div>

        <!-- CATEGORY LIST PANEL -->
        <section class="panel">

            <div class="panel-header">
                <div>
                    <h3>All Product Categories</h3>
                    <p>
                        Review category details, product counts, and status.
                    </p>
                </div>

                <span class="count-pill">
                    <i class="bi bi-folder2-open"></i>
                    <?= count($filteredCategories) ?> shown
                </span>
            </div>

            <div class="panel-body">

                <!-- SEARCH AND FILTER -->
                <form method="GET" action="categories.php"
                      class="filter-row mb-4">

                    <div>
                        <label class="form-label">Search Categories</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white"
                                  style="border-color:#dfe6f0;border-radius:9px 0 0 9px;">
                                <i class="bi bi-search text-secondary"></i>
                            </span>

                            <input type="text"
                                   name="search"
                                   class="form-control"
                                   style="border-radius:0 9px 9px 0;"
                                   placeholder="Search by category name..."
                                   value="<?= e($search) ?>">
                        </div>
                    </div>

                    <div>
                        <label class="form-label">Category Status</label>
                        <select name="status" class="form-select">
                            <option value="All"
                                <?= $statusFilter === 'All' ? 'selected' : '' ?>>
                                All Statuses
                            </option>
                            <option value="Active"
                                <?= $statusFilter === 'Active' ? 'selected' : '' ?>>
                                Active
                            </option>
                            <option value="Inactive"
                                <?= $statusFilter === 'Inactive' ? 'selected' : '' ?>>
                                Inactive
                            </option>
                        </select>
                    </div>

                    <div>
                        <label class="form-label">Sort By</label>
                        <select name="sort" class="form-select">
                            <option value="name_asc"
                                <?= $sort === 'name_asc' ? 'selected' : '' ?>>
                                Name: A to Z
                            </option>
                            <option value="name_desc"
                                <?= $sort === 'name_desc' ? 'selected' : '' ?>>
                                Name: Z to A
                            </option>
                            <option value="products_desc"
                                <?= $sort === 'products_desc' ? 'selected' : '' ?>>
                                Most Products
                            </option>
                        </select>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-filter">
                            <i class="bi bi-funnel me-1"></i> Filter
                        </button>

                        <a href="categories.php"
                           class="btn btn-light-custom d-flex align-items-center">
                            Reset
                        </a>
                    </div>

                </form>

                <!-- TABLE -->
                <div class="table-responsive">

                    <table class="category-table">

                        <thead>
                        <tr>
                            <th>Category</th>
                            <th>Description</th>
                            <th>Products</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                        </thead>

                        <tbody>

                        <?php if (count($filteredCategories) > 0): ?>

                            <?php foreach ($filteredCategories as $category): ?>

                                <?php
                                $productCount = getCategoryProductCount(
                                    $category['name'],
                                    $products
                                );

                                $icon = $categoryIcons[$category['name']]
                                    ?? 'bi-folder';

                                $color = $categoryColors[$category['name']]
                                    ?? 'blue';
                                ?>

                                <tr>

                                    <td>
                                        <div class="category-name">

                                            <div class="category-icon color-<?= e($color) ?>">
                                                <i class="bi <?= e($icon) ?>"></i>
                                            </div>

                                            <div>
                                                <strong>
                                                    <?= e($category['name']) ?>
                                                </strong>
                                                <small>
                                                    Category ID:
                                                    #<?= (int)$category['id'] ?>
                                                </small>
                                            </div>

                                        </div>
                                    </td>

                                    <td>
                                        <div class="description">
                                            <?= e(
                                                $category['description'] !== ''
                                                    ? $category['description']
                                                    : 'No description provided.'
                                            ) ?>
                                        </div>
                                    </td>

                                    <td>
                                        <span class="count-pill <?= $productCount === 0 ? 'zero' : '' ?>">
                                            <i class="bi bi-box-seam"></i>
                                            <?= $productCount ?>
                                            <?= $productCount === 1 ? 'Product' : 'Products' ?>
                                        </span>
                                    </td>

                                    <td>
                                        <?php if ($category['status'] === 'Active'): ?>

                                            <span class="status-badge status-active">
                                                <i class="bi bi-check-circle-fill"></i>
                                                Active
                                            </span>

                                        <?php else: ?>

                                            <span class="status-badge status-inactive">
                                                <i class="bi bi-pause-circle-fill"></i>
                                                Inactive
                                            </span>

                                        <?php endif; ?>
                                    </td>

                                    <td>
                                        <div class="action-group">

                                            <!-- EDIT -->
                                            <button type="button"
                                                    class="btn-action"
                                                    title="Edit category"
                                                    aria-label="Edit category"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#categoryModal"
                                                    data-id="<?= (int)$category['id'] ?>"
                                                    data-name="<?= e($category['name']) ?>"
                                                    data-description="<?= e($category['description']) ?>"
                                                    data-status="<?= e($category['status']) ?>"
                                                    onclick="openEditModal(this)">

                                                <i class="bi bi-pencil-square"></i>
                                            </button>

                                            <!-- DELETE -->
                                            <form method="POST"
                                                  action="categories.php"
                                                  onsubmit="return confirmDelete(
                                                      '<?= e(addslashes($category['name'])) ?>',
                                                      <?= $productCount ?>
                                                  );">

                                                <input type="hidden"
                                                       name="action"
                                                       value="delete_category">

                                                <input type="hidden"
                                                       name="category_id"
                                                       value="<?= (int)$category['id'] ?>">

                                                <button type="submit"
                                                        class="btn-action delete"
                                                        title="<?= $productCount > 0 ? 'Cannot delete a category with products' : 'Delete unused category' ?>"
                                                        aria-label="Delete category"
                                                        <?= $productCount > 0 ? 'disabled' : '' ?>>

                                                    <i class="bi bi-trash3"></i>
                                                </button>

                                            </form>

                                        </div>
                                    </td>

                                </tr>

                            <?php endforeach; ?>

                        <?php else: ?>

                            <tr>
                                <td colspan="5">

                                    <div class="empty-state">
                                        <i class="bi bi-folder2-open"></i>

                                        <h4>No Categories Found</h4>

                                        <p>
                                            Try another search term or adjust
                                            the selected filters.
                                        </p>

                                        <a href="categories.php"
                                           class="btn btn-primary-custom">
                                            Clear Filters
                                        </a>
                                    </div>

                                </td>
                            </tr>

                        <?php endif; ?>

                        </tbody>

                    </table>

                </div>

            </div>

        </section>

        <div class="footer">
            © <?= date('Y') ?> NovaMart Admin Panel.
            Demonstration interface for Codvanta portfolio.
        </div>

    </div>

</main>

<!-- ADD / EDIT CATEGORY MODAL -->
<div class="modal fade"
     id="categoryModal"
     tabindex="-1"
     aria-labelledby="categoryModalTitle"
     aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content">

            <form method="POST" action="categories.php">

                <div class="modal-header">

                    <div>
                        <h2 class="modal-title" id="categoryModalTitle">
                            Add New Category
                        </h2>

                        <p class="text-secondary mb-0 mt-1"
                           style="font-size:12px;">
                            Enter the category information below.
                        </p>
                    </div>

                    <button type="button"
                            class="btn-close"
                            data-bs-dismiss="modal"
                            aria-label="Close"></button>

                </div>

                <div class="modal-body">

                    <input type="hidden"
                           name="action"
                           id="formAction"
                           value="add_category">

                    <input type="hidden"
                           name="category_id"
                           id="categoryId"
                           value="">

                    <div class="mb-3">
                        <label for="categoryName" class="form-label">
                            Category Name <span class="text-danger">*</span>
                        </label>

                        <input type="text"
                               class="form-control"
                               name="name"
                               id="categoryName"
                               maxlength="80"
                               placeholder="e.g. Mobile Accessories"
                               required>
                    </div>

                    <div class="mb-3">
                        <label for="categoryDescription" class="form-label">
                            Description
                        </label>

                        <textarea class="form-control"
                                  name="description"
                                  id="categoryDescription"
                                  rows="3"
                                  maxlength="300"
                                  placeholder="Briefly describe this category..."></textarea>
                    </div>

                    <div>
                        <label for="categoryStatus" class="form-label">
                            Category Status
                        </label>

                        <select name="status"
                                id="categoryStatus"
                                class="form-select">

                            <option value="Active">Active</option>
                            <option value="Inactive">Inactive</option>

                        </select>
                    </div>

                    <div class="mt-3"
                         style="font-size:11px;color:#7a879c;">
                        <i class="bi bi-info-circle me-1"></i>
                        Category names must be unique.
                    </div>

                </div>

                <div class="modal-footer">

                    <button type="button"
                            class="btn btn-light-custom"
                            data-bs-dismiss="modal">
                        Cancel
                    </button>

                    <button type="submit" class="btn btn-save">
                        <i class="bi bi-check2 me-1"></i>
                        <span id="saveButtonText">Add Category</span>
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>


<script>
    function openAddModal() {
        document.getElementById('categoryModalTitle').textContent =
            'Add New Category';

        document.getElementById('formAction').value = 'add_category';
        document.getElementById('categoryId').value = '';
        document.getElementById('categoryName').value = '';
        document.getElementById('categoryDescription').value = '';
        document.getElementById('categoryStatus').value = 'Active';
        document.getElementById('saveButtonText').textContent =
            'Add Category';
    }

    function openEditModal(button) {
        document.getElementById('categoryModalTitle').textContent =
            'Edit Category';

        document.getElementById('formAction').value = 'edit_category';

        document.getElementById('categoryId').value =
            button.dataset.id || '';

        document.getElementById('categoryName').value =
            button.dataset.name || '';

        document.getElementById('categoryDescription').value =
            button.dataset.description || '';

        document.getElementById('categoryStatus').value =
            button.dataset.status || 'Active';

        document.getElementById('saveButtonText').textContent =
            'Save Changes';
    }

    function confirmDelete(name, productCount) {
        if (productCount > 0) {
            alert(
                'This category still contains ' +
                productCount +
                ' product(s). Reassign those products before deleting it.'
            );

            return false;
        }

        return confirm(
            'Are you sure you want to delete the unused category "' +
            name +
            '"? This action cannot be undone.'
        );
    }
</script>

</body>
</html>