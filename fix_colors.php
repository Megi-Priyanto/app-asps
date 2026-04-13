<?php
$files = array_merge(glob('resources/views/guru/laporan/*.blade.php'), glob('resources/views/pegawai/laporan/*.blade.php'));

foreach ($files as $f) {
    $c = file_get_contents($f);
    
    // Replace remaining guru light green/dark green
    $c = str_replace('#F0FDF4', '#EFF6FF', $c);
    $c = str_replace('#065F46', '#1D4ED8', $c);
    
    // Replace remaining pegawai light purple/dark purple
    $c = str_replace('#F5F3FF', '#EFF6FF', $c);
    $c = str_replace('#4C1D95', '#1D4ED8', $c);

    // But now we broke the "Selesai" and "alert-success" and "Feedback" green blocks.
    // Restore them exactly as Siswa has them.
    
    // 1. Alert success
    $c = str_replace('.alert-success { background: #EFF6FF; color: #1D4ED8; }', '.alert-success { background: #ECFDF5; color: #059669; }', $c);
    
    // 2. Feedback Success block
    $c = str_replace('background:#EFF6FF; color:#1D4ED8; padding:12px 16px', 'background:#ECFDF5; color:#059669; padding:12px 16px', $c);
    
    // 3. Selesai Status Status Laporan block
    // in show.blade.php: <span style="background:#EFF6FF; color:#2563EB; font-size:13px; font-weight:600; padding:6px 14px; border-radius:8px;">
    // Wait, the Selesai status in show.blade.php of Siswa uses what? 
    // Wait, let's just make it background:#ECFDF5; color:#059669; for Selesai
    $c = str_replace('background:#EFF6FF; color:#2563EB; font-size:13px; font-weight:600; padding:6px 14px; border-radius:8px;">
                            <i class="bi bi-check-circle-fill me-2"></i>Selesai', 'background:#ECFDF5; color:#059669; font-size:13px; font-weight:600; padding:6px 14px; border-radius:8px;">
                            <i class="bi bi-check-circle-fill me-2"></i>Selesai', $c);
    
    file_put_contents($f, $c);
    echo "Fixed: $f\n";
}
