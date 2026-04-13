<?php

$guruFiles = glob('resources/views/guru/laporan/*.blade.php');
$pegawaiFiles = glob('resources/views/pegawai/laporan/*.blade.php');

$files = array_merge($guruFiles ?: [], $pegawaiFiles ?: []);

$guruColors = ['#059669', '#34D399', 'rgba(5,150,105,0.3)', '#ECFDF5', '#047857', '#A7F3D0'];
$pegawaiColors = ['#7C3AED', '#A78BFA', 'rgba(124,58,237,0.3)', '#F5F3FF', '#6D28D9', '#DDD6FE'];

$blueColors = ['#2563EB', '#60A5FA', 'rgba(37,99,235,0.3)', '#EFF6FF', '#1D4ED8', '#BFDBFE'];

foreach ($files as $f) {
    if (is_file($f)) {
        $c = file_get_contents($f);
        
        $c = str_replace($guruColors, $blueColors, $c);
        $c = str_replace($pegawaiColors, $blueColors, $c);
        
        // Custom guru specific class replacement
        $c = str_replace('btn-guru-primary', 'btn-primary', $c);
        $c = str_replace('text-guru-primary', 'text-primary', $c);
        $c = str_replace('bg-guru-light', 'bg-primary-light', $c);

        file_put_contents($f, $c);
        echo "Updated: $f\n";
    }
}
