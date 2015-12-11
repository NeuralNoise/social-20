<h2>Add an update</h2>
<form action="{siteurl}stream/addStatus/" method="post" enctype="multipart/form-data">
    <textarea id="status" name="status"></textarea>
    <input type="radio" name="status_type" id="status_checker_update" class="status_checker" value="update"/>Update
    <input type="radio" name="status_type" id="status_checker_video" class="status_checker" value="video"/>Video
    <input type="radio" name="status_type" id="status_checker_image" class="status_checker" value="image"/>Image
    <input type="radio" name="status_type" id="status_checker_link" class="status_checker" value="link"/>Link
    <br/>

    <div class="video_input  extra_field">
        <label for="video_url" class="">YouTube URL</label>
        <input type="text" id="" name="video_url" class=""/><br/>
    </div>
    <div class="image_input  extra_field">
        <label for="image_file" class="">Upload image</label>
        <input type="file" id="" name="image_file" class=""/><br/>
    </div>
    <div class="link_input  extra_field">
        <label for="link_url" class="">Link</label>
        <input type="text" id="" name="link_url" class=""/>
        <label for="link_description" class="">Description</label>
        <input type="text" id="" name="link_description" class=""/><br/>
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
<h1>There are no updates in your network</h1>