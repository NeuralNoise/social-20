<!-- START message -->
{message-{message_id}}

<!-- START replies-{message_id} -->
{reply} by {replyFrom}
<!-- END replies-{message_id} -->
<form action='{siteurl}messages/add/{status_id}' method='POST'>
    <label for='msg_text'>Add a Reply:</label>
    <input type='text' id='msg_text' name='msg_text'/>
    <input type='submit' id='msg_submit' name='msg_submit'/>
</form>
<!-- END message -->