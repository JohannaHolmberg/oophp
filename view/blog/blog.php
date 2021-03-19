<?php require "header.php"; ?>
<?php
if (!$resultset) {
    return;
}
?>

<article>

<?php foreach ($resultset as $row) : ?>
<section>
    <header>
        <h1><a href="?route=blog/<?= escBlog($row->slug) ?>"><?= escBlog($row->title) ?></a></h1>
        <p><i>Published: <time datetime="<?= escBlog($row->published_iso8601) ?>" pubdate><?= escBlog($row->published) ?></time></i></p>
    </header>
    <?= escBlog($row->data) ?>
</section>
<?php endforeach; ?>

</article>
