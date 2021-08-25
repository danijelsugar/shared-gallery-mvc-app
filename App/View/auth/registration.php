<div class="card col-md-6 offset-3">
    <div class="card-body">
        <form action="<?php $url ?>registration" method="post">
            <h3>Register</h3>
            <div class="form-group">
                <label for="firstName">Fristname</label>
                <input type="text" name="firstName" id="firstName" class="form-control">
            </div>
            <div class="form-group">
                <label for="lastName">Lastname</label>
                <input type="text" name="lastName" id="lastName" class="form-control">
            </div>
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" name="username" id="username" class="form-control">
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" class="form-control">
            </div>
            <div class="form-group">
                <label for="address">Address</label>
                <input type="text" name="address" id="address" class="form-control">
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" class="form-control">
            </div>
            <div class="form-group">
                <label for="rpassword">Repeat password</label>
                <input type="password" name="rpassword" id="rpassword" class="form-control">
            </div>
            <div>
                <input type="submit" name="register" value="Register" class="btn btn-info btn-md">
            </div>
        </form>
    </div>
</div>