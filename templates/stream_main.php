<h2>Add an update</h2>
<form id='newStatus' action="javascript:addStatus('{siteurl}stream/addStatus/', 'newStatus')" method="post"
      enctype="multipart/form-data">
    <textarea id="status" name="status"></textarea>
    <input type="radio" name="status_type" id="status_checker_update" class="status_checker" value="update"/>Update
    <input type="radio" name="status_type" id="status_checker_video" class="status_checker" value="video"/>Video
    <input type="radio" name="status_type" id="status_checker_image" class="status_checker" value="image"/>Image
    <input type="radio" name="status_type" id="status_checker_link" class="status_checker" value="link"/>Link
    <br/>

    <div class="video_input  extra_field">
        <label for="video_url" class="">YouTube URL</label>
        <input type="text" id="video_url" name="video_url" class=""/><br/>
    </div>
    <div class="image_input  extra_field">
        <label for="image_file" class="">Upload image</label>
        <input type="file" id="image_file" name="image_file" class=""/><br/>
    </div>
    <div class="link_input  extra_field">
        <label for="link_url" class="">Link</label>
        <input type="text" id="" name="link_url" class=""/>
        <label for="link_description" class="">Description</label>
        <input type="text" id="link_description" name="link_description" class=""/><br/>
    </div>
    <input type="submit" id="updatestatus" name="updatestatus" value="Update"/>
</form>
<script type="text/javascript">
    $(function () {
        $('.extra_field').hide();
        $("input[name='status_type']").change(function () {
            $('.extra_field').hide();
            $('.' + $("input[name='status_type']:checked").val() + '_input').show();
        });
    });
</script>

<h1>Updates in your network</h1>
<ul class='stream'>
    <!-- START stream -->
    <li class='stream'>{stream-{status_id}}</li>
<span class='{status_id}' id='{rate_status_{status_id}}'>
<canvas class='rateCanvas1' width='32' height='18' onmousemove="hoverRate('{rate_status_{status_id}}', 1)"
        onClick="rateSend('status', '{siteurl}stream/rate/{status_id}', {status_id}, 1)"
        onmouseout="defaultRate('{rate_status_{status_id}}')">
    Your browser doesn't support canvas
</canvas>
<canvas class='rateCanvas2' width='32' height='18' onmousemove="hoverRate('{rate_status_{status_id}}', 2)"
        onClick="rateSend('status', '{siteurl}stream/rate/{status_id}', {status_id}, 2)"
        onmouseout="defaultRate('{rate_status_{status_id}}')"></canvas>
<canvas class='rateCanvas3' width='32' height='18' onmousemove="hoverRate('{rate_status_{status_id}}', 3)"
        onClick="rateSend('status', '{siteurl}stream/rate/{status_id}', {status_id}, 3)"
        onmouseout="defaultRate('{rate_status_{status_id}}')"></canvas>
<canvas class='rateCanvas4' width='32' height='18' onmousemove="hoverRate('{rate_status_{status_id}}', 4)"
        onClick="rateSend('status', '{siteurl}stream/rate/{status_id}', {status_id}, 4)"
        onmouseout="defaultRate('{rate_status_{status_id}}')"></canvas>
<canvas class='rateCanvas5' width='32' height='18' onmousemove="hoverRate('{rate_status_{status_id}}', 5)"
        onClick="rateSend('status', '{siteurl}stream/rate/{status_id}', {status_id}, 5)"
        onmouseout="defaultRate('{rate_status_{status_id}}')"></canvas>
</span>
    <br/><u>Comments:</u>
    <!-- START comments-{status_id} -->
    <form method='post' action="javascript:deleteComment('{siteurl}stream/deletecomment/{status_id}', '{comment_id}')">
        <label for='del_comment'>&nbsp;{comment} by <a href='{siteurl}profile/view/{user_id}'>{commenter}</a></label>
        <input type='hidden' id='del_comment' name='del_comment' value='{comment_id}'/>
        <input type='submit' id='deleteComment' name='deleteComment' value='Delete'/>

        <div class='{comment_id}' id='{rate_comment_{comment_id}}'>
            <canvas class='rateCanvas1' width='32' height='18' onmousemove="hoverRate('{rate_comment_{comment_id}}', 1)"
                    onClick="rateSend('comment', '{siteurl}stream/rate/{comment_id}', {comment_id}, 1)"
                    onmouseout="defaultRate('{rate_comment_{comment_id}}')">
                Your browser doesn't support canvas
            </canvas>
            <canvas class='rateCanvas2' width='32' height='18' onmousemove="hoverRate('{rate_comment_{comment_id}}', 2)"
                    onClick="rateSend('comment', '{siteurl}stream/rate/{comment_id}', {comment_id}, 2)"
                    onmouseout="defaultRate('{rate_comment_{comment_id}}')"></canvas>
            <canvas class='rateCanvas3' width='32' height='18' onmousemove="hoverRate('{rate_comment_{comment_id}}', 3)"
                    onClick="rateSend('comment', '{siteurl}stream/rate/{comment_id}', {comment_id}, 3)"
                    onmouseout="defaultRate('{rate_comment_{comment_id}}')"></canvas>
            <canvas class='rateCanvas4' width='32' height='18' onmousemove="hoverRate('{rate_comment_{comment_id}}', 4)"
                    onClick="rateSend('comment', '{siteurl}stream/rate/{comment_id}', {comment_id}, 4)"
                    onmouseout="defaultRate('{rate_comment_{comment_id}}')"></canvas>
            <canvas class='rateCanvas5' width='32' height='18' onmousemove="hoverRate('{rate_comment_{comment_id}}', 5)"
                    onClick="rateSend('comment', '{siteurl}stream/rate/{comment_id}', {comment_id}, 5)"
                    onmouseout="defaultRate('{rate_comment_{comment_id}}')"></canvas>
        </div>
    </form>
    <!-- END comments-{status_id} -->
    <form method='post' action="javascript:addComment('{siteurl}stream/addcomment/{status_id}', '{status_id}')">
        <label>Add a comment:</label>
        <input type='text' id='usr_comment_{status_id}' name='usr_comment_{status_id}'/>
        <input type='submit' id='addComment' name='addComment' value='Comment'/>
    </form>
    <br/>
    <!-- END stream -->
    <a id='moreStream' href="javascript:moreStatus('{siteurl}stream/more/{offset}', 'moreStream')">View More</a>
</ul>
</div>