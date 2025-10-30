<?php
// Настройки подключения к базе данных
$serverName = "localhost";
$connectionOptions = array(
    "Database" => "TSZH_DB",
    "Uid" => "your_username",
    "PWD" => "your_password"
);

// Создание подключения
$conn = sqlsrv_connect($serverName, $connectionOptions);

// Проверка подключения
if (!$conn) {
    die("Ошибка подключения: " . print_r(sqlsrv_errors(), true));
}

// Функция для безопасного выполнения запросов
function executeQuery($sql, $params = array()) {
    global $conn;
    $stmt = sqlsrv_query($conn, $sql, $params);
    
    if ($stmt === false) {
        die("Ошибка запроса: " . print_r(sqlsrv_errors(), true));
    }
    
    return $stmt;
}

// Функция для получения данных в виде массива
function getData($sql, $params = array()) {
    $stmt = executeQuery($sql, $params);
    $data = array();
    
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        $data[] = $row;
    }
    
    return $data;
}
?>