<div class="col-12">
    <h2 class="text-center">Images</h2>
    <form action="<?php App::config('url') ?>management/addImage" method="POST"  enctype="multipart/form-data">
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
                <td class="img"><img src="<?php echo App::config('url') . 'uploads/' . $image->imgLocation ?>" alt="img"></td>
                <?php if (Session::getInstance()->getUser()->id === $image->user): ?>
                <td>
                    <a class="btn btn-danger" href="<?php App::config('url') ?>management/removeimage/<?php echo $image->id ?>">
                        Remove
                    </a>
                </td>
                <?php endif; ?>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

