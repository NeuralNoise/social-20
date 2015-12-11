<form method='post' action="javascript:deleteStatus('{siteurl}stream/deletestatus/{statusID}')">
    <label style="font-weight:bold;">{statusposter_name} shared a link: <a href='{statusURL}'>{statusupdate}</a> on your
        profile</label>
    <input type='submit' id='deleteStatus' name='deleteStatus' value='Delete'/>
</form>
<p class="postedtime">Posted {statusfriendly_time}</p>