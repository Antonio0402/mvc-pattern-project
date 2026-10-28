<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css">
    <title>New Transaction</title>
</head>
<body>
    <h1>New Transaction</h1>
    <?php if (!empty($errors)): ?>
        <ul>
            <?php foreach ($errors as $field => $message): ?>
                <li>
                    <strong><?= htmlspecialchars((string) $field, ENT_QUOTES, 'UTF-8') ?>:</strong>
                    <?= htmlspecialchars((string) $message, ENT_QUOTES, 'UTF-8') ?>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
    <form action="/transactions/insert" method="post">
    <?php require "form.php"; ?>
    </form>
</body>
</html>
