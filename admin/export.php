<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../db.php';

requireAdmin();

$pdo = getDb();
$stmt = $pdo->query("SELECT id, name, shop_name, mobile, city, service_type, document_path, created_at FROM vendors ORDER BY created_at ASC");
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

$serviceLabels = [
    'mechanic' => 'Mechanic',
    'wheel_alignment' => 'Wheel Alignment',
    'tyre_dealer' => 'Tyre Dealer',
    'driver' => 'Driver',
    'medical_support' => 'Medical Support',
    'legal_advisor' => 'Legal Advisor',
    'loading_service' => 'Loading Service',
    'other' => 'Other',
];

$filename = 'rahveer_partners_' . date('Y-m-d_His') . '.csv';
header('Content-Type: text/csv; charset=UTF-8');
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');

$out = fopen('php://output', 'w');
fprintf($out, "\xEF\xBB\xBF");

fputcsv($out, ['#', 'Name', 'Shop / Business', 'Mobile', 'City', 'Service Type', 'Document', 'Registered At']);

foreach ($rows as $i => $v) {
    $service = $serviceLabels[$v['service_type']] ?? $v['service_type'];
    $doc = $v['document_path'] !== null && $v['document_path'] !== '' ? $v['document_path'] : '—';
    $created = date('Y-m-d H:i:s', strtotime($v['created_at']));
    fputcsv($out, [$i + 1, $v['name'], $v['shop_name'], $v['mobile'], $v['city'], $service, $doc, $created]);
}

fclose($out);
exit;
