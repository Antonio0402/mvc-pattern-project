<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>500 Error Page</title>
</head>

<body>
    <h1>An error occurred</h1>
    <?php
if ($displayErrors) {
    ?>
    <pre><?php echo (string)$exception; ?></pre><?php
}
    ?>
</body>

</html>