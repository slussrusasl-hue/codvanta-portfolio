
<?php
session_start();

/*
|--------------------------------------------------------------------------
| ProServe CRM - Admin Logout
|--------------------------------------------------------------------------
| Ends the current administrator session and redirects to the admin login
| page. This demo uses PHP sessions and does not require a database.
|--------------------------------------------------------------------------
*/

date_default_timezone_set('Asia/Colombo');

// Remove administrator-specific session variables.
unset(
    $_SESSION['proserve_admin'],
    $_SESSION['proserve_admin_id'],
    $_SESSION['proserve_admin_name'],
    $_SESSION['proserve_admin_email'],
    $_SESSION['proserve_admin_role']
);

// Clear all remaining session data.
$_SESSION = [];

// Remove the session cookie, if cookies are enabled.
if (ini_get('session.use_cookies')) {
    $cookieParams = session_get_cookie_params();

    setcookie(
        session_name(),
        '',
        time() - 42000,
        $cookieParams['path'],
        $cookieParams['domain'],
        $cookieParams['secure'],
        $cookieParams['httponly']
    );
}

// Destroy the current session.
session_destroy();

// Start a fresh session to display the logout message.
session_start();
$_SESSION['proserve_admin_logout_message'] =
    'You have been logged out successfully.';

// Redirect to the administrator login page.
header('Location: login.php');
exit;
?>