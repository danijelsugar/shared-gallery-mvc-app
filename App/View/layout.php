<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Shared gallery</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" 
    integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="<?php echo App::config('url') ?>assets/custom.css">
    
    <nav>
        <ul class="nav nav-tabs justify-content-center">
            <li class="nav-item">
                <a class="nav-link" href="<?php echo App::config('url'); ?>">Home</a>
            </li>
            <?php if (Session::getInstance()->isLoggedIn() || Session::getInstance()->cookieLoggin()): ?>
            <li class="nav-item">
                <a class="nav-link" href="<?php echo App::config('url') ?>management">Management</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?php echo App::config('url') ?>account">My account</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?php echo App::config('url') ?>login/logout">Logout</a>
            </li>
            <?php else: ?>
            <li class="nav-item">
                <a class="nav-link" href="<?php echo App::config('url') ?>login">Login</a>
            </li>
            <?php endif; ?>
        </ul>
    </nav>
    <?php 
        $messages = Session::getInstance()->getMessage();
    ?>    
    <div class="container">
        <?php if (isset($messages)): ?>
            <?php foreach ($messages as $message): ?>
                <div class="alert alert-<?php echo $message['type'] ?> text-center" role="alert">
                    <?php echo $message['body'] ?>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
        <div class="row">
            <?php echo $content; ?>
        </div>
        
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
    // A $( document ).ready() block.
    $( document ).ready(function() {
        $("#numberOfImages").click(function (e) {
            e.preventDefault();
            
            $.ajax({
                type: "POST",
                url: "<?php echo App::config('url') ?>home/numberofimages",
                success: function (data) {
                    let jsonData = jQuery.parseJSON(data);
                    $("#imgCount").text("Total number of images: " + jsonData);
                }
            })
        })
    });
        
    </script>

    



</body>
</html>