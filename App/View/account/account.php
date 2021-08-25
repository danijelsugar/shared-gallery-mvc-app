<div class="col-12">
    <div class="jumbotron jumbotron-background-color">
        <h1 class="display-4 text-center">Edit your profile</h1>
        <form action="<?php echo $url ?>edit-profile" method="POST">
            <div class="form-group">
                <label for="firstName">First name</label>
                <input type="text" name="firstName" id="firstName" class="form-control" 
                value="<?php echo htmlspecialchars($user->firstName) ?>">
            </div>
            <div class="form-group">
                <label for="lastName">Last name</label>
                <input type="text" name="lastName" id="lastName" class="form-control" 
                value="<?php echo htmlspecialchars($user->lastName) ?>">
            </div>
            <div class="form-group">
                <label for="userName">Username</label>
                <input type="text" name="userName" id="userName" class="form-control" 
                value="<?php echo htmlspecialchars($user->userName) ?>">
            </div>
            <div class="form-group">
                <label for="address">Address</label>
                <input type="text" name="address" id="address" class="form-control" 
                value="<?php echo htmlspecialchars($user->address)  ?>">
            </div>
            <div class="form-group">
                <input type="hidden" name="id" id="id" value="<?php echo $user->id ?>">
            </div>
            <div class="form-group">
                <input type="submit" name="edit" value="Edit" class="btn btn-primary btn-lg btn-block">
            </div>
        </form>
    </div>
</div>
<div class="col-12">
    <div class="jumbotron jumbotron-background-color">
        <h1 class="display-4 text-center">Change password</h1>
        <form action="<?php echo $url?>change-password" method="POST">
            <div class="form-group">
                <label for="currentPassword">Current password</label>
                <input type="password" name="currentPassword" id="currentPassword" class="form-control">
            </div>
            <div class="form-group">
                <label for="newPassword">New password</label>
                <input type="password" name="newPassword" id="newPassword" class="form-control">
            </div>
            <div class="form-group">
                <label for="repeatNewPassword">Repeat new password</label>
                <input type="password" name="repeatNewPassword" id="repeatNewPassword" class="form-control">
            </div>
            <div class="form-group">
                <input type="hidden" name="id" id="id" value="<?php echo $user->id ?>">
            </div>
            <div class="form-group">
                <input type="submit" name="changePassword" value="Change" class="btn btn-primary btn-lg btn-block">
            </div>
        </form>
    </div>
</div>
