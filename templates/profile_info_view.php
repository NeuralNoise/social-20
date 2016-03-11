<span id='frame2'><!-- style="position: fixed; top: 80px; left: 20px; text-align:center; padding-top: 5px;" -->
						<br/><br/><img src="{siteurl}uploads/profile/{p_photo}"/>
					</span>
<div id="profile_view" style="padding: 5px;">
    <h2>Friends</h2>
    <ul>
        <!-- START profile_friends_sample -->
        <li><a href="{siteurl}profile/view/{ID}">{users_name}</a></li>
        <!-- END profile_friends_sample -->
        <li><a href="{siteurl}relationships/all/{p_user_id}">View all</a></li>
        <li><a href="{siteurl}relationships/mutual/{p_user_id}">View mutual friends</a></li>
    </ul>
    <h2>Rest of the profile</h2>
    <ul>
        <li><a href="{siteurl}profile/statuses/{p_user_id}">Status updates</a></li>
    </ul>
{subscribe}
{edit}
<h2>About {p_name}:</h2>
<p>{p_bio}</p>
<h2>My Details</h2>
<table>
    <tr>
        <th>DOB</th>
        <td>{p_dob}</td>
    </tr>
    <tr>
        <th>Location</th>
        <td>{p_location}</td>
    </tr>
    <tr>
        <th>Gender</th>
        <td>{p_gender}</td>
    </tr>

</table>
</div>