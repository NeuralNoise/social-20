{error}
<form action='{siteurl}authenticate/register' method='POST'>
    <label for='reg_username'>Username</label><br/>
    <input type='text' id='reg_user' name='reg_user'/><br/>
    <label for='reg_pass'>Password</label><br/>
    <input type='password' id='reg_pass' name='reg_pass'/><br/>
    <label for='reg_pass_confirm'>Confirm Password</label><br/>
    <input type='password' id='reg_pass_confirm' name='reg_pass_confirm'/><br/>
    <label for='fName'>First Name</label><br/>
    <input type='text' id='reg_fName' name='reg_fName'/><br/>
    <label for='lName'>Last Name</label><br/>
    <input type='text' id='reg_lName' name='reg_lName'/><br/>
    <label for='email'>Email Address</label><br/>
    <input type='text' id='reg_email' name='reg_email'/><br/>
    <label for='reg_gender'>Gender</label><br/>
    <select id='reg_gender' name='reg_gender'>
        <option value='male'>Male</option>
        <option value='female'>Female</option>
    </select><br/>
    <label for='reg_dob'>Date Of Birth (DD/MM/YYYY)</label><br/>
    <input type='text' id='reg_dob' name='reg_dob'/><br/>
    <label for='reg_loc'>Country</label><br/>
    <select id='reg_loc' name='reg_loc'>
        <option value='India'>India</option>
    </select><br/>
    <label for='reg_terms'>Do you accept our <a href=''>terms and conditions</a>?</label><br/>
    <input type='checkbox' id='reg_terms' name='reg_terms' value='1'/> <br/>
    <input type="hidden" id="referer" name="referer" value={referer}/>
    <input type="submit" id="process_registration" name="process_registration" value="Create an account"/>
</form>