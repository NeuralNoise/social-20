{error}
<form action='{siteurl}authenticate/login' method='POST'>
    <label for='log_user'>Username</label><br/>
    <input type='text' id='log_user' name='log_user'/><br/>
    <label for='log_pass'>Password</label><br/>
    <input type='password' id='log_pass' name='log_pass'/><br/>
    <input type="hidden" id="referer" name="referer" value={referer}/>
    <input type='submit' id='login' name='login' value='Submit'/>
</form>
<a href='{siteurl}authenticate/password'>Forgot Password</a>
<a href='{siteurl}authenticate/username'>Forgot Username</a>