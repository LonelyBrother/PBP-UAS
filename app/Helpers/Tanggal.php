<?php
use Carbon\Carbon;

if (!function_exists('dateID')) {
    function dateID($tanggal) {
        if (empty($tanggal) || $tanggal == '0000-00-00 00:00:00') {
            return '-';
        }
        try {
            $date = Carbon::parse($tanggal);
            return $date->format('d-m-Y');  // Hanya tanggal: dd-mm-yyyy
        } catch (Exception $e) {
            return '-';
        }
    }
}

// Optional: Keep your original for full names if needed
if (!function_exists('dateIDFull')) {
    function dateIDFull($tanggal) {
        if (empty($tanggal) || $tanggal == '0000-00-00 00:00:00') {
            return '-';
        }
        $bulan = [
            1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
        ];
        try {
            $date = Carbon::parse($tanggal);
            $day = $date->day;
            $month = $date->month;
            $year = $date->year;
            return $day . ' ' . ($bulan[$month] ?? 'Unknown') . ' ' . $year;
        } catch (Exception $e) {
            return '-';
        }
    }
}
?>
