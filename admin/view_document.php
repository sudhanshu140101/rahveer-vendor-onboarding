<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../config.php';

requireAdmin();

$f = isset($_GET['f']) ? (string) $_GET['f'] : '';
$f = basename($f);
if ($f === '' || preg_match('/[\/\\\\]/', $f)) {
    http_response_code(400);
    exit('Invalid request');
}

$path = rtrim(UPLOAD_DIR, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $f;
$realBase = realpath(UPLOAD_DIR);
if ($realBase === false || !is_dir($realBase)) {
    http_response_code(404);
    exit('Not found');
}
$realPath = realpath($path);
if ($realPath === false || !is_file($realPath) || strpos($realPath, $realBase) !== 0) {
    http_response_code(404);
    exit('Not found');
}

$ext = strtolower(pathinfo($f, PATHINFO_EXTENSION));
$mimes = ['jpg' => 'image/jpeg', 'jpeg' => 'image/jpeg', 'png' => 'image/png'];
$mime = $mimes[$ext] ?? 'application/octet-stream';

header('Content-Type: ' . $mime);
header('Content-Disposition: inline; filename="' . basename($f) . '"');
header('Cache-Control: private, max-age=3600');
readfile($realPath);
exit;
