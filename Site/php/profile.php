<?php
session_start();
include 'database.php';

// Получаем данные текущего пользователя
$userId = $_SESSION['user_id'];
$sql = "SELECT * FROM Users WHERE UserID = ?";
$user = getData($sql, array($userId));

// Если пользователь - собственник, получаем его имущество
if ($_SESSION['user_role'] == 'owner') {
    $propertySql = "SELECT p.*, o.FullName 
                   FROM Property p 
                   JOIN Owners o ON p.OwnerID = o.OwnerID 
                   WHERE o.UserID = ?";
    $properties = getData($propertySql, array($userId));
    
    // Получаем платежи и начисления
    $financeSql = "SELECT 'Начисление' as Type, Amount, Date as OperationDate 
                   FROM Accruals WHERE PropertyID IN (SELECT PropertyID FROM Property WHERE OwnerID = ?)
                   UNION ALL 
                   SELECT 'Платеж' as Type, Amount, PaymentDate as OperationDate 
                   FROM Payments WHERE PropertyID IN (SELECT PropertyID FROM Property WHERE OwnerID = ?)
                   ORDER BY OperationDate DESC";
    $finances = getData($financeSql, array($userId, $userId));
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Личный кабинет</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <!-- Вставить header отсюда ↑ -->

    <div class="container">
        <div class="profile-header">
            <img src="icons/user.svg" alt="Профиль" class="profile-icon">
            <h2>Личный кабинет</h2>
        </div>

        <div class="profile-info">
            <h3>Ваши данные</h3>
            <div class="info-card">
                <p><strong>ФИО:</strong> <?php echo $user[0]['FullName']; ?></p>
                <p><strong>Должность:</strong> <?php echo $_SESSION['user_role']; ?></p>
                <p><strong>Логин:</strong> <?php echo $user[0]['Login']; ?></p>
            </div>
        </div>

        <?php if ($_SESSION['user_role'] == 'owner' && !empty($properties)): ?>
        <div class="profile-section">
            <h3>Ваша собственность</h3>
            <div class="property-list">
                <?php foreach ($properties as $property): ?>
                <div class="property-card">
                    <h4><?php echo htmlspecialchars($property['Address']); ?></h4>
                    <p>Площадь: <?php echo $property['Area']; ?> м²</p>
                    <p>Тип: <?php echo $property['Type']; ?></p>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="profile-section">
            <h3>Финансовая история</h3>
            <table>
                <thead>
                    <tr>
                        <th>Тип операции</th>
                        <th>Сумма</th>
                        <th>Дата</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($finances as $finance): ?>
                    <tr>
                        <td style="color: <?php echo $finance['Type'] == 'Платеж' ? '#27ae60' : '#e74c3c'; ?>">
                            <?php echo $finance['Type']; ?>
                        </td>
                        <td><?php echo number_format($finance['Amount'], 2); ?> руб</td>
                        <td><?php echo $finance['OperationDate']; ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>
    </div>
</body>
</html>