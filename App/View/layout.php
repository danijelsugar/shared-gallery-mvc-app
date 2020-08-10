<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Shared gallery</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" 
    integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="<?php echo App::config('url') ?>assets/css/custom.css">
    
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
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
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