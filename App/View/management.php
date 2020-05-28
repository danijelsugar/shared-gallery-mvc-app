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
                <th>Image</th>
            </tr>
        </thead>
    </table>
</div>

