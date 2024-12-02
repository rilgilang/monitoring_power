<?php 
function deviceChecker($id){
    $device = [
        1 => 'Route Utama',
        2 => 'BTS 1',
        3 => 'BTS 2',
    ];

    return $device[$id];
}
?>