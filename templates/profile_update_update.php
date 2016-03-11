<div id="content-child"><strong>{poster_name}</strong>: {update}
<p>Comments:</p>
<!-- START comments-{ID} -->
<form method='post' action='{siteurl}profile/deletecomment/{ID}'>
    <label for='usr_comment'>&nbsp;{comment} by <a href='{siteurl}profile/view/{user_id}'>{commenter}</a></label>
    <input type='hidden' id='del_comment' name='del_comment' value='{comment}'/>
    <input type='hidden' id='returnto' name='returnto' value='{referrer}'/>
    <input type='submit' id='deleteComment' name='deleteComment' value='Delete'/>
</form><!-- END comments-{ID} -->
<form method='post' action='{siteurl}profile/addcomment/{ID}'>
    <label for='usr_comment'>Add a comment:</label>
    <input type='text' id='usr_comment' name='usr_comment'/>
    <input type='hidden' id='returnto' name='returnto' value='{referrer}'/>
    <input type='submit' id='addComment' name='addComment' value='Comment'/>
</form></div>