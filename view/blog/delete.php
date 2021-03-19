<form method="post">
    <fieldset>
    <legend>Delete</legend>

    <input type="hidden" name="contentId" value="<?= escBlog($content->id) ?>"/>
    <?= $content->title ?>
    <p>
        <label>Title:<br>
            <input type="text" name="contentTitle" value="<?= escBlog($content->title) ?>" readonly/>
        </label>
    </p>

    <p>
        <button type="submit" name="doDelete"><i class="fa fa-trash-o" aria-hidden="true"></i> Delete</button>
    </p>
    </fieldset>
</form>
