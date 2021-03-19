<article>
    <header>
        <h1><?= escBlog($content->title) ?></h1>
        <p><i>Published: <time datetime="<?= esc($content->published_iso8601) ?>" pubdate><?= esc($content->published) ?></time></i></p>
    </header>
    <?= $text ?>

</article>
