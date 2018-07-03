<form id="login" class="login" accept-charset="utf-8" method="post" name="login" action="/navarik/">
    <div style="display:none;">
        <input type="hidden" value="POST" name="_method"></input>
    </div>
    <p class="field">
        <input id="user_name" type="text"  placeholder="Username or email"   name="user_name"></input>
        <i class="fa fa-user">
            
        </i></p>
        <p class="field">
            <input id="user_pass" type="password" placeholder="Password" onblur="this.value=!this.value?'password':this.value;" onfocus="this.select()" onclick="this.value='';" name="user_pass"></input>
            <i class="fa fa-lock"></i>
        </p>
        <div class="submit">
            <input id="check_submit" type="submit" value="Login" name="check_submit"></input>
        </div>
</form>