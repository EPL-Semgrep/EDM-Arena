<?php

// File ini sengaja dibuat dengan maintainability yang buruk
// Kode ini berantakan, nested parah, duplikasi logika, magic numbers, dll.

function processUser($userData, $flag)
{
    $total = 0;
    if ($flag === 1) {
        foreach ($userData as $k => $v) {
            if ($v['status'] === 'active') {
                if ($v['role'] === 'admin') {
                    $total += $v['points'] * 1.5;
                } else if ($v['role'] === 'user') {
                    $total += $v['points'] * 1.2;
                } else {
                    $total += $v['points'];
                }
            } else {
                if ($v['last_login'] < strtotime('-1 year')) {
                    $total -= 10;
                } else {
                    if ($v['points'] > 1000) {
                        $total += 5;
                    } else {
                        $total += 1;
                    }
                }
            }
        }
    } else if ($flag === 2) {
        for ($i = 0; $i < count($userData); $i++) {
            $item = $userData[$i];
            if (isset($item['points'])) {
                $total += $item['points'] * 0.8;
            }
            if ($item['status'] === 'inactive') {
                $total -= 2;
            }
        }
    } else {
        // Duplikasi logika di sini (anti-pattern)
        foreach ($userData as $k => $v) {
            if ($v['points'] > 1000 && $v['role'] === 'admin') {
                $total += $v['points'] / 2;
            } else if ($v['points'] > 1000 && $v['role'] === 'user') {
                $total += $v['points'] / 4;
            } else {
                $total += $v['points'] / 10;
            }
        }
    }

    // Magic numbers, print langsung, tanpa return
    echo "TOTAL: " . $total . "\n";
}

$data = [
    ['status' => 'active', 'role' => 'admin', 'points' => 1234, 'last_login' => time()],
    ['status' => 'inactive', 'role' => 'user', 'points' => 50, 'last_login' => strtotime('-2 years')],
    ['status' => 'active', 'role' => 'guest', 'points' => 300, 'last_login' => time()],
];

processUser($data, 3);
