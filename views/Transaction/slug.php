<?php

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Transaction</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            text-align: center;
        }

        table tr th,
        table tr td {
            padding: 5px;
            border: 1px #eee solid;
        }
    </style>
</head>

<body>
    <?php if (! empty($currentTransaction)): ?>
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Check #</th>
                <th>Description</th>
                <th>Amount</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><?= formatDate($currentTransaction['date']) ?></td>
                <td><?= $currentTransaction['checkNumber'] ?></td>
                <td><?= $currentTransaction['description'] ?></td>
                <td>
                    <?php if ($currentTransaction['amount'] < 0): ?>
                    <span style="color: red;">
                        <?= formatDollarAmount($currentTransaction['amount']) ?>
                    </span>
                    <?php elseif ($currentTransaction['amount'] > 0): ?>
                    <span style="color: green;">
                        <?= formatDollarAmount($currentTransaction['amount']) ?>
                    </span>
                    <?php else: ?>
                    <?= formatDollarAmount($currentTransaction['amount']) ?>
                    <?php endif ?>
                </td>
            </tr>
        </tbody>
    </table>
    <?php else: ?>
    <p>Transaction not found.</p>
    <?php endif ?>
</body>

</html>