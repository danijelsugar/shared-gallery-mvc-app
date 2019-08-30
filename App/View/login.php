<div class="card col-md-6 offset-3">
  <div class="card-body">
        <form action="<?php echo App::config('url') ?>login/authorize" method="post">
            <h3 class="text-center text-info">Login</h3>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" class="form-control">
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" class="form-control">
            </div>
            <div class="form-group">
            <label for="remeber">Remeber me</label>
            <input type="checkbox" name="remember" id="remember">
            </div>
            <div class="form-group">
                <input type="submit" name="login" class="btn btn-info btn-md" value="Login">
            </div>
            <div class="form-group">
                <p>Don't have an accout make one <a href="<?php echo App::config('url') ?>registration">here</a></p>
            </div>
        </form>
  </div>
</div>