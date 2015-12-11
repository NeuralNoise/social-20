<p><strong>{poster_name}</strong>: posted an video "{update}"</p>
<iframe width="400" height="250" src="https://www.youtube.com/embed/{video_id}" frameborder="0" allowfullscreen></iframe>
<!--<object width="400" height="250">
    <param name="movie" value="http://www.youtube.com/v/{video_id}&amp;hl=en_GB&amp;fs=1?rel=0&amp;border=1"></param>
    <param name="allowFullScreen" value="true"></param>
    <param name="allowscriptaccess" value="always"></param>
    <embed src="http://www.youtube.com/v/{video_id}&amp;hl=en_GB&amp;fs=1?rel=0&amp;border=1"
           type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="400"
           height="250"></embed>
</object>-->
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
</form>