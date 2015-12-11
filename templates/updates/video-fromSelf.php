<form method='post' action="javascript:deleteStatus('{siteurl}stream/deleteStatus/{statusID}')">
    <label style="font-weight:bold;">You shared a video on {statusprofile_name}'s profile: {statusupdate}</label>
    <input type='submit' id='deleteStatus' name='deleteStatus' value='Delete'/>
</form><br/>
<iframe width="400" height="250" src="https://www.youtube.com/embed/{statusvideo_id}" frameborder="0" allowfullscreen></iframe>
<!--<object width="400" height="250">
    <param name="movie"
           value="http://www.youtube.com/v/{statusvideo_id}&amp;hl=en_GB&amp;fs=1?rel=0&amp;border=1"></param>
    <param name="allowFullScreen" value="true"></param>
    <param name="allowscriptaccess" value="always"></param>
    <embed src="http://www.youtube.com/v/{statusvideo_id}&amp;hl=en_GB&amp;fs=1?rel=0&amp;border=1"
           type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="400"
           height="250"></embed>
</object>-->
<p class="postedtime">Posted {statusfriendly_time}</p>