<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../db.php';

requireAdmin();

$pdo = getDb();
$stmt = $pdo->query("SELECT id, name, shop_name, mobile, city, service_type, document_path, created_at FROM vendors ORDER BY created_at DESC");
$vendors = $stmt->fetchAll(PDO::FETCH_ASSOC);

$total = count($vendors);
$weekAgo = date('Y-m-d H:i:s', strtotime('-7 days'));
$newThisWeek = 0;
foreach ($vendors as $v) {
    if ($v['created_at'] >= $weekAgo) $newThisWeek++;
}

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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard – RAHVEER Admin</title>
    <script src="https://cdn.tailwindcss.com/3.4.17"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: { navy: '#0a1628', 'navy-light': '#1a2d4a', 'bharat-orange': '#ff6b35', 'bharat-orange-dark': '#e55a2b', 'safety-green': '#22c55e', 'safety-green-dark': '#16a34a' },
                    fontFamily: { poppins: ['Poppins', 'sans-serif'] }
                }
            }
        };
    </script>
    <style>
        * { font-family: 'Poppins', sans-serif; }
        @keyframes fadeUp { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes pulse-soft { 0%, 100% { opacity: 1; } 50% { opacity: 0.85; } }
        .animate-fade-up { animation: fadeUp 0.4s ease-out forwards; }
        .animate-fade-up-delay-1 { animation: fadeUp 0.4s ease-out 0.05s forwards; opacity: 0; }
        .animate-fade-up-delay-2 { animation: fadeUp 0.4s ease-out 0.1s forwards; opacity: 0; }
        .animate-fade-up-delay-3 { animation: fadeUp 0.4s ease-out 0.15s forwards; opacity: 0; }
        .card-hover { transition: transform 0.2s ease, box-shadow 0.2s ease; }
        .card-hover:hover { transform: translateY(-2px); box-shadow: 0 10px 25px -5px rgba(0,0,0,0.08), 0 8px 10px -6px rgba(0,0,0,0.05); }
        .row-hover { transition: background-color 0.15s ease; }
        .btn-interactive:active { transform: scale(0.98); }
        @media (max-width: 1023px) {
            .vendor-card { animation: fadeUp 0.35s ease-out forwards; }
        }
    </style>
</head>
<body class="min-h-screen bg-gray-100">
    <header class="bg-navy text-white shadow-lg sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-wrap items-center justify-between gap-3 h-16 sm:h-14">
                <div class="flex items-center gap-3 min-w-0">
                    <img src="../images/rahveer-logo.png" alt="RAHVEER" class="h-10 w-auto object-contain flex-shrink-0">
                    <span class="font-bold text-white truncate">RAHVEER Admin</span>
                </div>
                <div class="flex items-center gap-2 sm:gap-4 flex-shrink-0">
                    <a href="../index.html" class="text-gray-300 hover:text-white text-sm py-2 px-3 rounded-lg hover:bg-white/10 transition-colors touch-manipulation">View site</a>
                    <span class="text-gray-400 text-sm hidden sm:inline py-2"><?php echo htmlspecialchars(getAdminUsername()); ?></span>
                    <a href="dashboard.php" class="text-gray-300 hover:text-white text-sm py-2 px-3 rounded-lg hover:bg-white/10 transition-colors touch-manipulation">Refresh</a>
                    <a href="logout.php" class="bg-bharat-orange hover:bg-bharat-orange-dark px-4 py-2.5 rounded-xl text-sm font-semibold transition-all shadow-md hover:shadow-lg active:scale-[0.98] touch-manipulation">Logout</a>
                </div>
            </div>
        </div>
    </header>
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-5 mb-6 sm:mb-8">
            <div class="animate-fade-up card-hover bg-white rounded-2xl p-5 sm:p-6 shadow border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm font-medium">Total partners</p>
                        <p class="text-2xl sm:text-3xl font-bold text-navy mt-1"><?php echo $total; ?></p>
                    </div>
                    <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-2xl bg-bharat-orange/10 flex items-center justify-center">
                        <svg class="w-6 h-6 sm:w-7 sm:h-7 text-bharat-orange" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                </div>
            </div>
            <div class="animate-fade-up-delay-1 card-hover bg-white rounded-2xl p-5 sm:p-6 shadow border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm font-medium">New this week</p>
                        <p class="text-2xl sm:text-3xl font-bold text-navy mt-1"><?php echo $newThisWeek; ?></p>
                    </div>
                    <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-2xl bg-safety-green/10 flex items-center justify-center">
                        <svg class="w-6 h-6 sm:w-7 sm:h-7 text-safety-green" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                </div>
            </div>
            <div class="animate-fade-up-delay-2 card-hover bg-white rounded-2xl p-5 sm:p-6 shadow border border-gray-100 sm:col-span-2 lg:col-span-1">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm font-medium">Export data</p>
                        <p class="text-sm text-gray-600 mt-1">Download Excel (CSV)</p>
                    </div>
                    <a href="export.php" class="btn-interactive inline-flex items-center gap-2 bg-safety-green hover:bg-safety-green-dark text-white px-4 py-2.5 rounded-xl font-semibold text-sm transition-all shadow-md hover:shadow-lg active:scale-[0.98] touch-manipulation">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        Export
                    </a>
                </div>
            </div>
        </div>

        <div class="animate-fade-up-delay-3">
            <h2 class="text-lg sm:text-xl font-bold text-navy mb-4">Partner list</h2>

            <div class="hidden lg:block bg-white rounded-2xl shadow border border-gray-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wide">#</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wide">Name</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wide">Shop / Business</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wide">Mobile</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wide">City</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wide">Service</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wide">Document</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wide">Registered</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            <?php foreach ($vendors as $i => $v): ?>
                            <tr class="row-hover hover:bg-orange-50/50">
                                <td class="px-4 py-3 text-sm text-gray-500"><?php echo $i + 1; ?></td>
                                <td class="px-4 py-3 text-sm font-medium text-navy"><?php echo htmlspecialchars($v['name']); ?></td>
                                <td class="px-4 py-3 text-sm text-gray-700"><?php echo htmlspecialchars($v['shop_name']); ?></td>
                                <td class="px-4 py-3 text-sm">
                                    <a href="tel:+91<?php echo htmlspecialchars($v['mobile']); ?>" class="text-bharat-orange hover:text-bharat-orange-dark font-medium hover:underline"><?php echo htmlspecialchars($v['mobile']); ?></a>
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-700"><?php echo htmlspecialchars($v['city']); ?></td>
                                <td class="px-4 py-3 text-sm"><span class="inline-flex px-2 py-0.5 rounded-lg bg-gray-100 text-gray-700"><?php echo htmlspecialchars($serviceLabels[$v['service_type']] ?? $v['service_type']); ?></span></td>
                                <td class="px-4 py-3 text-sm">
                                    <?php if (!empty($v['document_path'])): ?>
                                    <a href="view_document.php?f=<?php echo urlencode(basename($v['document_path'])); ?>" target="_blank" rel="noopener" class="text-bharat-orange hover:underline font-medium">View</a>
                                    <?php else: ?>
                                    <span class="text-gray-400">—</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-500"><?php echo htmlspecialchars(date('d M Y, H:i', strtotime($v['created_at']))); ?></td>
                            </tr>
                            <?php endforeach; ?>
                            <?php if (count($vendors) === 0): ?>
                            <tr>
                                <td colspan="8" class="px-4 py-12 text-center text-gray-500">No registrations yet.</td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="lg:hidden space-y-4">
                <?php if (count($vendors) === 0): ?>
                <div class="bg-white rounded-2xl p-8 text-center text-gray-500 shadow border border-gray-100">No registrations yet.</div>
                <?php else: ?>
                <?php foreach ($vendors as $i => $v): 
                    $serviceLabel = $serviceLabels[$v['service_type']] ?? $v['service_type'];
                ?>
                <div class="vendor-card card-hover bg-white rounded-2xl p-5 shadow border border-gray-100" style="animation-delay: <?php echo min($i * 0.03, 0.5); ?>s">
                    <div class="flex items-start justify-between gap-3 mb-3">
                        <span class="text-xs font-semibold text-gray-400">#<?php echo $i + 1; ?></span>
                        <span class="text-xs text-gray-500"><?php echo htmlspecialchars(date('d M Y', strtotime($v['created_at']))); ?></span>
                    </div>
                    <p class="font-semibold text-navy text-base mb-1"><?php echo htmlspecialchars($v['name']); ?></p>
                    <p class="text-gray-600 text-sm mb-3"><?php echo htmlspecialchars($v['shop_name']); ?></p>
                    <div class="flex flex-wrap gap-2 mb-3">
                        <span class="inline-flex px-2.5 py-1 rounded-lg bg-gray-100 text-gray-700 text-xs font-medium"><?php echo htmlspecialchars($serviceLabel); ?></span>
                        <span class="inline-flex px-2.5 py-1 rounded-lg bg-gray-100 text-gray-600 text-xs"><?php echo htmlspecialchars($v['city']); ?></span>
                    </div>
                    <div class="flex flex-wrap items-center gap-3 pt-3 border-t border-gray-100">
                        <a href="tel:+91<?php echo htmlspecialchars($v['mobile']); ?>" class="inline-flex items-center gap-1.5 text-bharat-orange font-semibold text-sm hover:underline touch-manipulation">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/></svg>
                            <?php echo htmlspecialchars($v['mobile']); ?>
                        </a>
                        <?php if (!empty($v['document_path'])): ?>
                        <a href="view_document.php?f=<?php echo urlencode(basename($v['document_path'])); ?>" target="_blank" rel="noopener" class="inline-flex items-center gap-1.5 text-gray-600 text-sm hover:text-bharat-orange touch-manipulation">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            Document
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </main>
</body>
</html>
