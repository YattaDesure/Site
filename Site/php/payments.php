<?php
include 'database.php';

// Обработка формы платежа
if ($_POST['add_payment']) {
    $propertyId = $_POST['property_id'];
    $amount = $_POST['amount'];
    $paymentDate = date('Y-m-d');
    
    $sql = "INSERT INTO Payments (PropertyID, Amount, PaymentDate) VALUES (?, ?, ?)";
    executeQuery($sql, array($propertyId, $amount, $paymentDate));
    
    echo "<script>alert('Платеж успешно добавлен!');</script>";
}

// Получаем список собственников для выпадающего списка
$ownersSql = "SELECT p.PropertyID, o.FullName, p.Address 
              FROM Property p 
              JOIN Owners o ON p.OwnerID = o.OwnerID 
              ORDER BY o.FullName";
$properties = getData($ownersSql);
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Внесение платежей</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="header">
        <h1>ТСЖ - Внесение платежей</h1>
        <nav>
            <a href="index.html">Главная</a>
            <a href="owners.php">Собственники</a>
            <a href="payments.php">Платежи</a>
        </nav>
    </div>

    <div class="container">
        <h2>Внесение платежа</h2>
        
        <form method="POST">
            <div class="form-group">
                <label for="property_id">Собственник:</label>
                <select name="property_id" id="property_id" required>
                    <option value="">Выберите собственника</option>
                    <?php foreach ($properties as $property): ?>
                    <option value="<?php echo $property['PropertyID']; ?>">
                        <?php echo htmlspecialchars($property['FullName'] . ' - ' . $property['Address']); ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="form-group">
                <label for="amount">Сумма платежа:</label>
                <input type="number" step="0.01" name="amount" id="amount" required>
            </div>
            
            <button type="submit" name="add_payment" value="1">Внести платеж</button>
        </form>
    </div>
</body>
</html>