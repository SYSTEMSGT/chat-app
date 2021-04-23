<?php

function escapeStrings(string $value): string {
    return addslashes(trim($value));
}

function sendWebLog(int $response_code, array $values) {
    header('Content-type: application/json; charset=UTF-8');
    http_response_code($response_code);
    $arr['code'] = $response_code;
    $final_data = array_merge($arr, $values);
    echo json_encode($final_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    die();
}