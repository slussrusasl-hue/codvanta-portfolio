
<?php
session_start();
date_default_timezone_set('Asia/Colombo');

function e($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

// Check whether the customer is logged in
if (empty($_SESSION['proserve_customer'])) {
    $_SESSION['proserve_profile_notice'] = [
        'type' => 'warning',
        'message' => 'Please log in to access your profile.'
    ];

    header('Location: login.php');
    exit;
}

// Initialize customer profile using the existing login session
if (!isset($_SESSION['proserve_profile'])) {
    $customer = $_SESSION['proserve_customer'];

    $_SESSION['proserve_profile'] = [
        'name' => $customer['name'] ?? '',
        'email' => $customer['email'] ?? '',
        'phone' => $customer['phone'] ?? '',
        'company' => $customer['company'] ?? '',
        'job_title' => $customer['job_title'] ?? '',
        'country' => $customer['country'] ?? 'Sri Lanka',
        'address' => $customer['address'] ?? '',
        'bio' => $customer['bio'] ?? '',
        'updated_at' => ''
    ];
}

if (!isset($_SESSION['proserve_profile_csrf'])) {
    $_SESSION['proserve_profile_csrf'] = bin2hex(random_bytes(32));
}

$profile = $_SESSION['proserve_profile'];
$errors = [];
$success = '';
$notice = $_SESSION['proserve_profile_notice'] ?? null;
unset($_SESSION['proserve_profile_notice']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['csrf_token'] ?? '';

    if (!is_string($token) ||
        !hash_equals($_SESSION['proserve_profile_csrf'], $token)) {
        $errors[] = 'Your session verification failed. Please refresh the page and try again.';
    } else {
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $company = trim($_POST['company'] ?? '');
        $jobTitle = trim($_POST['job_title'] ?? '');
        $country = trim($_POST['country'] ?? '');
        $address = trim($_POST['address'] ?? '');
        $bio = trim($_POST['bio'] ?? '');

        if ($name === '') {
            $errors[] = 'Full name is required.';
        } elseif (mb_strlen($name) > 100) {
            $errors[] = 'Full name cannot exceed 100 characters.';
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Please enter a valid email address.';
        } elseif (mb_strlen($email) > 150) {
            $errors[] = 'Email address is too long.';
        }

        if (mb_strlen($phone) > 30) {
            $errors[] = 'Phone number cannot exceed 30 characters.';
        }

        if (mb_strlen($company) > 120) {
            $errors[] = 'Company name cannot exceed 120 characters.';
        }

        if (mb_strlen($jobTitle) > 100) {
            $errors[] = 'Job title cannot exceed 100 characters.';
        }

        if ($country === '') {
            $errors[] = 'Please select your country.';
        }

        if (mb_strlen($address) > 250) {
            $errors[] = 'Address cannot exceed 250 characters.';
        }

        if (mb_strlen($bio) > 500) {
            $errors[] = 'About you cannot exceed 500 characters.';
        }

        if (empty($errors)) {
            $profile = [
                'name' => $name,
                'email' => $email,
                'phone' => $phone,
                'company' => $company,
                'job_title' => $jobTitle,
                'country' => $country,
                'address' => $address,
                'bio' => $bio,
                'updated_at' => date('Y-m-d H:i:s')
            ];

            $_SESSION['proserve_profile'] = $profile;

            // Keep common identity fields synchronized with the login session.
            $_SESSION['proserve_customer']['name'] = $name;
            $_SESSION['proserve_customer']['email'] = $email;

            if ($phone !== '') {
                $_SESSION['proserve_customer']['phone'] = $phone;
            }

            if ($company !== '') {
                $_SESSION['proserve_customer']['company'] = $company;
            }

            $_SESSION['proserve_profile_csrf'] = bin2hex(random_bytes(32));

            $_SESSION['proserve_profile_notice'] = [
                'type' => 'success',
                'message' => 'Your profile has been updated successfully.'
            ];

            header('Location: profile.php');
            exit;
        }

        // Preserve submitted values if validation fails.
        $profile = [
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'company' => $company,
            'job_title' => $jobTitle,
            'country' => $country,
            'address' => $address,
            'bio' => $bio,
            'updated_at' => $_SESSION['proserve_profile']['updated_at'] ?? ''
        ];
    }
}

$initials = '';
$nameParts = preg_split('/\s+/', trim($profile['name']));

foreach (array_slice($nameParts ?: [], 0, 2) as $part) {
    if ($part !== '') {
        $initials .= mb_strtoupper(mb_substr($part, 0, 1));
    }
}

if ($initials === '') {
    $initials = 'PC';
}

$updatedAt = $profile['updated_at'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile | ProServe</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap"
          rel="stylesheet">

    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        :root {
            --primary: #2563eb;
            --primary-dark: #1d4ed8;
            --navy: #10233f;
            --muted: #64748b;
            --background: #f4f7fb;
            --border: #e2e8f0;
            --success: #16a34a;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: 'Inter', 'Trebuchet MS', sans-serif;
            background: var(--background);
            color: #1e293b;
        }

        a {
            text-decoration: none;
        }

        .navbar {
            background: #fff;
            border-bottom: 1px solid var(--border);
        }

        .navbar-brand {
            font-size: 1.5rem;
            font-weight: 800;
            letter-spacing: -1px;
            color: var(--navy);
        }

        .brand-highlight {
            color: var(--primary);
        }

        .navbar .nav-link {
            color: #475569;
            font-weight: 600;
            font-size: .92rem;
            padding: .7rem .8rem;
        }

        .navbar .nav-link:hover,
        .navbar .nav-link.active {
            color: var(--primary);
        }

        .page-header {
            padding: 35px 0 25px;
        }

        .eyebrow {
            color: var(--primary);
            font-size: .78rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1.5px;
        }

        .page-title {
            font-size: clamp(1.8rem, 4vw, 2.5rem);
            font-weight: 800;
            letter-spacing: -1.2px;
            color: var(--navy);
            margin: 8px 0;
        }

        .page-description {
            color: var(--muted);
            line-height: 1.7;
            margin-bottom: 0;
        }

        .profile-card,
        .info-card {
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 18px;
            box-shadow: 0 5px 22px rgba(15, 23, 42, .035);
        }

        .profile-card {
            overflow: hidden;
        }

        .profile-banner {
            height: 120px;
            background: linear-gradient(120deg, #1d4ed8, #60a5fa);
            position: relative;
        }

        .profile-banner::after {
            content: '';
            position: absolute;
            width: 180px;
            height: 180px;
            right: 12%;
            top: -95px;
            border: 25px solid rgba(255,255,255,.13);
            border-radius: 50%;
        }

        .profile-content {
            padding: 0 26px 26px;
        }

        .avatar {
            width: 88px;
            height: 88px;
            margin-top: -44px;
            position: relative;
            z-index: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #fff;
            color: var(--primary);
            border: 5px solid #fff;
            border-radius: 50%;
            box-shadow: 0 3px 15px rgba(15,23,42,.12);
            font-size: 1.8rem;
            font-weight: 800;
        }

        .profile-name {
            font-size: 1.25rem;
            font-weight: 800;
            color: var(--navy);
            margin: 13px 0 4px;
            overflow-wrap: anywhere;
        }

        .profile-email {
            font-size: .88rem;
            color: var(--muted);
            overflow-wrap: anywhere;
        }

        .verified-label {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 6px 10px;
            border-radius: 30px;
            background: #eff6ff;
            color: #1d4ed8;
            font-size: .72rem;
            font-weight: 700;
        }

        .profile-divider {
            border-top: 1px solid var(--border);
            margin: 22px 0;
        }

        .side-link {
            display: flex;
            align-items: center;
            gap: 11px;
            padding: 12px 14px;
            margin-bottom: 5px;
            color: #475569;
            font-size: .9rem;
            font-weight: 600;
            border-radius: 10px;
            transition: .2s;
        }

        .side-link i {
            font-size: 1.05rem;
        }

        .side-link:hover,
        .side-link.active {
            background: #eff6ff;
            color: var(--primary);
        }

        .section-card {
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 18px;
            padding: 27px;
            margin-bottom: 22px;
            box-shadow: 0 5px 22px rgba(15, 23, 42, .035);
        }

        .section-title {
            color: var(--navy);
            font-size: 1.08rem;
            font-weight: 800;
            margin-bottom: 6px;
        }

        .section-subtitle {
            color: var(--muted);
            font-size: .87rem;
            line-height: 1.6;
            margin-bottom: 23px;
        }

        .form-label {
            font-size: .84rem;
            font-weight: 700;
            color: #334155;
            margin-bottom: 8px;
        }

        .form-control,
        .form-select {
            min-height: 46px;
            border: 1px solid #dbe3ee;
            border-radius: 10px;
            font-size: .9rem;
            box-shadow: none !important;
        }

        textarea.form-control {
            min-height: 110px;
            resize: vertical;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--primary);
        }

        .btn-primary-custom {
            background: var(--primary);
            border: 1px solid var(--primary);
            color: #fff;
            padding: 11px 20px;
            border-radius: 10px;
            font-weight: 700;
            font-size: .9rem;
        }

        .btn-primary-custom:hover {
            background: var(--primary-dark);
            border-color: var(--primary-dark);
            color: #fff;
        }

        .btn-light-custom {
            background: #fff;
            border: 1px solid var(--border);
            color: #475569;
            padding: 11px 18px;
            border-radius: 10px;
            font-weight: 700;
            font-size: .9rem;
        }

        .btn-light-custom:hover {
            background: #f8fafc;
        }

        .info-card {
            padding: 22px;
            margin-bottom: 20px;
        }

        .info-icon {
            width: 43px;
            height: 43px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
            background: #eff6ff;
            color: var(--primary);
            font-size: 1.2rem;
            margin-bottom: 14px;
        }

        .info-card h6 {
            color: var(--navy);
            font-weight: 800;
            margin-bottom: 7px;
        }

        .info-card p {
            color: var(--muted);
            font-size: .85rem;
            line-height: 1.65;
            margin-bottom: 0;
        }

        .last-updated {
            font-size: .78rem;
            color: var(--muted);
            margin-top: 15px;
        }

        footer {
            margin-top: 35px;
            background: #fff;
            border-top: 1px solid var(--border);
            padding: 24px 0;
            color: var(--muted);
            font-size: .82rem;
        }

        footer strong {
            color: var(--navy);
        }

        @media (max-width: 767px) {
            .page-header {
                padding-top: 25px;
            }

            .section-card {
                padding: 20px;
            }

            .profile-content {
                padding: 0 20px 22px;
            }
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg sticky-top">
    <div class="container">
        <a class="navbar-brand" href="index.php">
            Pro<span class="brand-highlight">Serve</span>
        </a>

        <button class="navbar-toggler" type="button"
                data-bs-toggle="collapse" data-bs-target="#mainNavbar"
                aria-controls="mainNavbar" aria-expanded="false"
                aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="mainNavbar">
            <ul class="navbar-nav ms-auto align-items-lg-center">
                <li class="nav-item">
                    <a class="nav-link" href="index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="services.php">Services</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="request_quote.php">Request a Quote</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="book_appointment.php">Book Appointment</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="my_appointments.php">My Appointments</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="profile.php">My Profile</a>
                </li>
                <li class="nav-item ms-lg-2">
                    <a class="btn btn-primary-custom" href="contact.php">
                        Contact Us
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<main class="container">
    <header class="page-header">
        <div class="eyebrow">Customer account</div>
        <h1 class="page-title">My Profile</h1>
        <p class="page-description">
            Manage your personal information and keep your contact details up to date.
        </p>
    </header>

    <?php if ($notice): ?>
        <div class="alert alert-<?= e($notice['type'] ?? 'info') ?> alert-dismissible fade show"
             role="alert">
            <?= e($notice['message'] ?? '') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"
                    aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger" role="alert">
            <strong>Please correct the following:</strong>
            <ul class="mb-0 mt-2">
                <?php foreach ($errors as $error): ?>
                    <li><?= e($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <div class="row g-4">
        <aside class="col-lg-4">
            <div class="profile-card mb-4">
                <div class="profile-banner"></div>

                <div class="profile-content">
                    <div class="avatar"><?= e($initials) ?></div>

                    <h2 class="profile-name"><?= e($profile['name']) ?></h2>
                    <div class="profile-email"><?= e($profile['email']) ?></div>

                    <div class="mt-3">
                        <span class="verified-label">
                            <i class="bi bi-person-check-fill"></i>
                            Customer Account
                        </span>
                    </div>

                    <div class="profile-divider"></div>

                    <a href="profile.php" class="side-link active">
                        <i class="bi bi-person"></i>
                        Personal Information
                    </a>

                    <a href="my_appointments.php" class="side-link">
                        <i class="bi bi-calendar-check"></i>
                        My Appointments
                    </a>

                    <a href="request_quote.php" class="side-link">
                        <i class="bi bi-file-earmark-text"></i>
                        Request a Quote
                    </a>

                    <a href="contact.php" class="side-link">
                        <i class="bi bi-headset"></i>
                        Contact Support
                    </a>

                    <div class="profile-divider"></div>

                    <p class="text-secondary small mb-0">
                        <i class="bi bi-shield-check me-1"></i>
                        Keep your information accurate so ProServe can contact you
                        about your service requests.
                    </p>

                    <?php if ($updatedAt !== ''): ?>
                        <div class="last-updated">
                            Last updated:
                            <?= e(date('d M Y, h:i A', strtotime($updatedAt))) ?>
                        </div>
                    <?php else: ?>
                        <div class="last-updated">
                            You have not updated this profile yet.
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="info-card">
                <div class="info-icon">
                    <i class="bi bi-lock"></i>
                </div>
                <h6>Your information</h6>
                <p>
                    This demonstration stores profile information in your current
                    PHP session. It does not permanently save data to a database.
                </p>
            </div>
        </aside>

        <section class="col-lg-8">
            <form action="profile.php" method="POST">
                <input type="hidden" name="csrf_token"
                       value="<?= e($_SESSION['proserve_profile_csrf']) ?>">

                <div class="section-card">
                    <h3 class="section-title">
                        <i class="bi bi-person-vcard text-primary me-2"></i>
                        Personal Information
                    </h3>
                    <p class="section-subtitle">
                        Update the personal details associated with your customer account.
                    </p>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="name" class="form-label">Full Name *</label>
                            <input type="text" class="form-control" id="name"
                                   name="name" maxlength="100" required
                                   autocomplete="name"
                                   value="<?= e($profile['name']) ?>"
                                   placeholder="Enter your full name">
                        </div>

                        <div class="col-md-6">
                            <label for="email" class="form-label">Email Address *</label>
                            <input type="email" class="form-control" id="email"
                                   name="email" maxlength="150" required
                                   autocomplete="email"
                                   value="<?= e($profile['email']) ?>"
                                   placeholder="you@example.com">
                            <div class="form-text">
                                Demo only: changing this does not change a database login.
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label for="phone" class="form-label">Phone Number</label>
                            <input type="tel" class="form-control" id="phone"
                                   name="phone" maxlength="30"
                                   autocomplete="tel"
                                   value="<?= e($profile['phone']) ?>"
                                   placeholder="+94 7X XXX XXXX">
                        </div>

                        <div class="col-md-6">
                            <label for="country" class="form-label">Country *</label>
                            <select class="form-select" id="country" name="country" required>
                                <?php
                                $countries = [
                                    'Sri Lanka', 'Australia', 'Canada', 'India',
                                    'United Kingdom', 'United States', 'New Zealand',
                                    'Singapore', 'United Arab Emirates', 'Other'
                                ];
                                if (!in_array($profile['country'], $countries, true)) {
                                    $countries[] = $profile['country'];
                                }
                                foreach ($countries as $countryOption):
                                ?>
                                    <option value="<?= e($countryOption) ?>"
                                        <?= $profile['country'] === $countryOption ? 'selected' : '' ?>>
                                        <?= e($countryOption) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="section-card">
                    <h3 class="section-title">
                        <i class="bi bi-building text-primary me-2"></i>
                        Company Information
                    </h3>
                    <p class="section-subtitle">
                        Add your business details to help us understand your service requirements.
                    </p>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="company" class="form-label">Company Name</label>
                            <input type="text" class="form-control" id="company"
                                   name="company" maxlength="120"
                                   autocomplete="organization"
                                   value="<?= e($profile['company']) ?>"
                                   placeholder="Your company name">
                        </div>

                        <div class="col-md-6">
                            <label for="job_title" class="form-label">Job Title</label>
                            <input type="text" class="form-control" id="job_title"
                                   name="job_title" maxlength="100"
                                   autocomplete="organization-title"
                                   value="<?= e($profile['job_title']) ?>"
                                   placeholder="e.g. Business Owner">
                        </div>

                        <div class="col-12">
                            <label for="address" class="form-label">
                                Business or Contact Address
                            </label>
                            <input type="text" class="form-control" id="address"
                                   name="address" maxlength="250"
                                   autocomplete="street-address"
                                   value="<?= e($profile['address']) ?>"
                                   placeholder="Enter your address">
                        </div>

                        <div class="col-12">
                            <label for="bio" class="form-label">About You or Your Business</label>
                            <textarea class="form-control" id="bio" name="bio"
                                      maxlength="500"
                                      placeholder="Briefly describe your business or the services you are interested in..."><?= e($profile['bio']) ?></textarea>
                            <div class="d-flex justify-content-between mt-2">
                                <span class="form-text">Optional information.</span>
                                <span class="form-text">
                                    <span id="bioCount"><?= mb_strlen($profile['bio']) ?></span>/500
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex flex-column flex-sm-row justify-content-end gap-2 mb-4">
                    <button type="reset" class="btn btn-light-custom">
                        <i class="bi bi-arrow-counterclockwise me-1"></i>
                        Reset Form
                    </button>

                    <button type="submit" class="btn btn-primary-custom">
                        <i class="bi bi-check-circle me-1"></i>
                        Save Changes
                    </button>
                </div>
            </form>
        </section>
    </div>
</main>

<footer>
    <div class="container d-flex flex-column flex-md-row justify-content-between
                align-items-center gap-2">
        <div>
            <strong>ProServe</strong> &mdash; CRM &amp; Service Management System
        </div>
        <div>Portfolio demonstration by <strong>Codvanta</strong>.</div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    const bioField = document.getElementById('bio');
    const bioCount = document.getElementById('bioCount');

    if (bioField && bioCount) {
        bioField.addEventListener('input', function () {
            bioCount.textContent = this.value.length;
        });
    }
</script>

</body>
</html>
