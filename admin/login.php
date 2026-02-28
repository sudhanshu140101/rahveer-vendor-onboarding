<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!empty($_SESSION[ADMIN_SESSION_NAME])) {
    header('Location: dashboard.php');
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    if ($username === '' || $password === '') {
        $error = 'Enter username and password.';
    } else {
        try {
            $pdo = getDb();
            $stmt = $pdo->prepare("SELECT id, username, password_hash FROM admins WHERE username = ? LIMIT 1");
            $stmt->execute([$username]);
            $admin = $stmt->fetch();
            if ($admin && password_verify($password, $admin['password_hash'])) {
                $_SESSION[ADMIN_SESSION_NAME] = $admin['username'];
                header('Location: dashboard.php');
                exit;
            }
        } catch (PDOException $e) {
            $error = 'Login failed. Try again.';
        }
        if ($error === '') {
            $error = 'Invalid username or password.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login – RAHVEER</title>
    <script src="https://cdn.tailwindcss.com/3.4.17"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: { navy: '#0a1628', 'bharat-orange': '#ff6b35', 'bharat-orange-dark': '#e55a2b' },
                    fontFamily: { poppins: ['Poppins', 'sans-serif'] }
                }
            }
        };
    </script>
    <style>* { font-family: 'Poppins', sans-serif; }</style>
</head>
<body class="min-h-screen bg-gray-100 flex items-center justify-center p-4 sm:p-6">
    <div class="w-full max-w-md bg-white rounded-2xl shadow-xl p-6 sm:p-8">
        <div class="flex items-center gap-3 mb-8">
            <img src="../images/rahveer-logo.png" alt="RAHVEER" class="h-12 w-auto object-contain">
            <div>
                <h1 class="text-xl font-bold text-navy">RAHVEER Admin</h1>
                <p class="text-gray-500 text-sm">Zero Accident Bharat</p>
            </div>
        </div>
        <h2 class="text-lg font-semibold text-navy mb-6">Sign in</h2>
        <?php if ($error !== ''): ?>
        <p class="mb-4 p-3 bg-red-50 text-red-700 rounded-lg text-sm"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>
        <form method="post" action="login.php" class="space-y-4">
            <div>
                <label for="username" class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                <input type="text" id="username" name="username" required autocomplete="username" value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>"
                    class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-bharat-orange focus:ring-0 transition-colors">
            </div>
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                <input type="password" id="password" name="password" required autocomplete="current-password"
                    class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-bharat-orange focus:ring-0 transition-colors">
            </div>
            <button type="submit" class="w-full bg-bharat-orange hover:bg-bharat-orange-dark text-white font-semibold py-3.5 rounded-xl transition-all shadow-md hover:shadow-lg active:scale-[0.99] touch-manipulation">Sign in</button>
        </form>
    </div>
</body>
</html>
