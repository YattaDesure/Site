<?php
include 'database.php';

// Получаем список собственников из базы
$sql = "SELECT 
            o.FullName, 
            p.Address, 
            p.Area, 
            o.PhoneNumber,
            (SELECT SUM(Amount) FROM Accruals WHERE PropertyID = p.PropertyID) - 
            (SELECT SUM(Amount) FROM Payments WHERE PropertyID = p.PropertyID) as Debt
        FROM Owners o
        JOIN Property p ON o.OwnerID = p.OwnerID
        ORDER BY p.Address";

$owners = getData($sql);
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Список собственников</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="header">
        <h1>ТСЖ - Список собственников</h1>
        <nav>
            <a href="index.html">Главная</a>
            <a href="owners.php">Собственники</a>
            <a href="payments.php">Платежи</a>
        </nav>
    </div>

    <div class="container">
        <h2>Список собственников</h2>
        
        <div class="form-group">
            <input type="text" id="searchInput" placeholder="Поиск по ФИО или адресу..." onkeyup="searchOwners()">
        </div>

        <table id="ownersTable">
            <thead>
                <tr>
                    <th>ФИО</th>
                    <th>Адрес</th>
                    <th>Площадь (м²)</th>
                    <th>Телефон</th>
                    <th>Задолженность</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($owners as $owner): ?>
                <tr>
                    <td><?php echo htmlspecialchars($owner['FullName']); ?></td>
                    <td><?php echo htmlspecialchars($owner['Address']); ?></td>
                    <td><?php echo htmlspecialchars($owner['Area']); ?></td>
                    <td><?php echo htmlspecialchars($owner['PhoneNumber']); ?></td>
                    <td style="color: <?php echo ($owner['Debt'] > 0) ? 'red' : 'green'; ?>">
                        <?php echo number_format($owner['Debt'], 2); ?> руб
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script src="script.js"></script>
</body>
</html>