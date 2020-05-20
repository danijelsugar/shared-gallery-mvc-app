<a class="btn btn-primary" href="<?php echo App::config("url") ?>home/show">Broj slika</a>
<?php
foreach ($posts as $post) {
    echo $post;
}

