<?php
header('Content-Type: application/json');
header('X-Content-Type-Options: nosniff');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/db.php';

function trimStr(?string $s): string {
    return $s === null ? '' : trim($s);
}

function respond(bool $success, string $message, array $extra = []): void {
    echo json_encode(array_merge(['success' => $success, 'message' => $message], $extra));
    exit;
}

$name = trimStr($_POST['name'] ?? '');
$shop_name = trimStr($_POST['shop_name'] ?? '');
$mobile = preg_replace('/\D/', '', $_POST['mobile'] ?? '');
$city = trimStr($_POST['city'] ?? '');
$service_type = trimStr($_POST['service_type'] ?? '');
$terms = !empty($_POST['terms']);

$allowed_services = ['mechanic', 'wheel_alignment', 'tyre_dealer', 'driver', 'medical_support', 'legal_advisor', 'loading_service', 'other'];
if (strlen($name) < 2 || strlen($name) > 100) {
    respond(false, 'Please enter a valid full name.');
}
if (strlen($shop_name) < 2 || strlen($shop_name) > 200) {
    respond(false, 'Please enter a valid shop or business name.');
}
if (strlen($mobile) !== 10) {
    respond(false, 'Please enter a valid 10-digit mobile number.');
}
if (strlen($city) < 2 || strlen($city) > 100) {
    respond(false, 'Please enter a valid city.');
}
if ($service_type === '' || !in_array($service_type, $allowed_services, true)) {
    respond(false, 'Please select a valid service type.');
}
if (!$terms) {
    respond(false, 'You must accept the terms and privacy policy.');
}

$document_path = null;
if (!empty($_FILES['document']['tmp_name']) && is_uploaded_file($_FILES['document']['tmp_name'])) {
    $file = $_FILES['document'];
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);
    if (!in_array($mime, ALLOWED_MIME, true)) {
        respond(false, 'Only JPG and PNG files are allowed.');
    }
    if ($file['size'] > UPLOAD_MAX_BYTES) {
        respond(false, 'File must be 5 MB or less.');
    }
    if (!is_dir(UPLOAD_DIR)) {
        mkdir(UPLOAD_DIR, 0750, true);
    }
    $ext = $mime === 'image/png' ? 'png' : 'jpg';
    $basename = bin2hex(random_bytes(8)) . '_' . time() . '.' . $ext;
    $target = UPLOAD_DIR . '/' . $basename;
    if (move_uploaded_file($file['tmp_name'], $target)) {
        $document_path = 'uploads/' . $basename;
    }
}

try {
    $pdo = getDb();
    $stmt = $pdo->prepare("INSERT INTO vendors (name, shop_name, mobile, city, service_type, document_path) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$name, $shop_name, $mobile, $city, $service_type, $document_path]);
    respond(true, 'Registration submitted successfully. We will contact you within 24 hours.');
} catch (PDOException $e) {
    if ($e->getCode() == 23000) {
        respond(false, 'This mobile number is already registered.');
    }
    respond(false, 'Something went wrong. Please try again.');
}
