<form method='post' action="javascript:deleteStatus('{siteurl}stream/deletestatus/{statusID}')">
    <label style="font-weight:bold;">{statusprofile_name} shared a link: <a
            href='{statusURL}'>{statusupdate}</a></label>
    <input type='submit' id='deleteStatus' name='deleteStatus' value='Delete'/>
</form>
<p class="postedtime">Posted {statusfriendly_time}</p>