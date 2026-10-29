<?php echo form_open('auth/user_login', array('method' => 'post', 'name' => 'login')); ?>
<div class="form-group">
    <label>Email</label>
    <input style="background-color: transparent; color: #FFF" maxlength="55" class="form-control" name="email" id="email" placeholder="john@example.com" type="text">
</div>
<div class="form-group">
    <label>Password</label>
    <input style="background-color: transparent; color: #FFF" maxlength="44" class="form-control" name="password" id="password" placeholder="Password" type="password">
</div>

<input style="float:right;" class="btn btn-primary" value="Sign In" type="submit">
<?php echo form_close(); ?>