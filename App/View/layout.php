<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Shared gallery</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>
<body>
    
    <nav>
        <ul class="nav nav-tabs justify-content-center">
            <li class="nav-item">
                <a class="nav-link" href="<?php echo App::config('url'); ?>">Home</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?php echo App::config('url') ?>login">Login</a>
            </li>
        </ul>
    </nav>
        
    <div class="container">
        
        <div class="row">
            <?php echo $content; ?>
        </div>
        
    </div>



</body>
</html>