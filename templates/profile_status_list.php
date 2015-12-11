<div style="position: fixed; top: 80px; left: 20px; text-align:center; padding-top: 5px;">
    <img src="{siteurl}uploads/profile/{profile_pic}"/>
</div>
<div style="padding: 5px;">
    <h2>{profile_name}</h2>

    <h2>Friends</h2>
    <ul>
        <!-- START profile_friends_sample -->
        <li><a href="{siteurl}profile/view/{ID}">{users_name}</a></li>
        <!-- END profile_friends_sample -->
        <li><a href="{siteurl}relationships/all/{profile_id}">View all</a></li>
        <li><a href="{siteurl}relationships/mutual/{profile_id}">View mutual friends</a></li>
    </ul>
</div>

{status_update}
<div id="content"><h1>What {profile_name} has been upto recently:</h1>
    <!-- {status_update_message} -->
    <!-- START updates -->
    {update-{ID}}
    <!-- END updates -->
</div>