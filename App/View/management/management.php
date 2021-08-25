<div class="col-12">
    <h2 class="text-center">Images</h2>
    <form action="<?php $url?>add-image" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <input type="file" name="image" id="image" class="form-control">
        </div>
        <div class="form-group">
            <input type="submit" name="addImage" class="btn btn-primary btn-block btn-extended" value="Add image">
        </div>
    </form>
    <table class="table">
        <thead>
            <tr>
                <th>Username</th>
                <th>Email</th>
                <th>Address</th>
                <th>Image</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($images as $image): ?>
                <tr>
                    <td><?php echo htmlspecialchars($image->userName) ?></td>
                    <td><?php echo htmlspecialchars($image->email) ?></td>
                    <td><?php echo htmlspecialchars($image->address) ?></td>
                    <td><img class="img" src="<?php echo $url . 'uploads/' . $image->imgLocation ?>" alt="img"></td>
                    <?php if ($sess->getUser()->id === $image->user): ?>
                        <td>
                            <form action="/remove-image" method="POST">
                                <div class="form-group">
                                    <input type="hidden" name="id" value="<?php echo $image->id ?>">
                                </div>
                                <div class="form-group">
                                    <input type="submit" name="removeImage" class="btn btn-danger" value="Remove image" >
                                </div>
                            </form>
                        </td>
                    <?php endif;?>
                </tr>
            <?php endforeach;?>
        </tbody>
    </table>
    <?php require_once BP . 'App/View/inc/pagination.php' ?>
</div>