<div style="position: fixed; top: 80px; left: 20px; text-align:center; padding-top: 5px;">
    <img src="{siteurl}uploads/profile/{p_photo}"/>
</div>
<div style="padding: 5px;">
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
</div>

<span id="content"><h1>Edit Profile</h1>
					<form action="{siteurl}profile/edit/{p_user_id}" method="post" enctype="multipart/form-data">
                        <label for="name">Name</label><br/>
                        <input type="text" id="name" name="name" value="{p_name}"/><br/>
                        <label for="profile_pic">Photograph</label> <br/>
                        <input type="file" id="profile_pic" name="profile_pic"/>
                        <br/>
                        <label for="bio">Biography</label><br/>
                        <textarea id="bio" name="bio" cols="40" rows="6">{p_bio}</textarea><br/>
                        <label for="dob">Date of Birth</label><br/>
                        <input type="text" id="dob" class="selectdate" name="dob" value="{p_dob}"/><br/>
                        <label for="gender">Gender</label><br/>
                        <select id="gender" name="gender">
                            <option value="">Please select</option>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                        </select>
                        <br/>
                        <input type="submit" id="" name="" value="Save profile"/>
                    </form>
    </div>