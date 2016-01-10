<!-- START stream -->
<li class='stream' id='stream-id-{status_id}'>{stream-{status_id}}
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
</li>
<!-- END stream -->