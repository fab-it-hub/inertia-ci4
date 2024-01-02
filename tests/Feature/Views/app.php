<?php

namespace Tests\Feature\Views;

use Inertia\Inertia;

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title inertia>Document</title>
</head>

<body>
    <?= Inertia::init($page); ?>
</body>

</html>