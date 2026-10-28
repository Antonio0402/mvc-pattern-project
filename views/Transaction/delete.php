<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css">
    <title>Delete Transaction</title>
</head>
<body>
    <h1>Are you sure deleting this Transaction</h1>
    <form action="/transactions/<?php echo $id ?>/destroy" method="post">
        <button type="button">Delete</button>
    </form>
    <a href="/transaction/<?php  echo $slug; ?>">Cancel</a>
</body>
</html>