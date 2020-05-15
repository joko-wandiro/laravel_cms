<div id="section-posts">
    <?php
    if (!count($posts)) {
        echo trans('main.no.posts');
    } else {
        foreach ($posts as $post) {
            $url = url('news/' . $post['url']);
            $categoryUrl = category_url($post['category']);
            $categoryLink = '<a href="' . $categoryUrl . '">' . $post['category'] . '</a>';
            ?>
            <div class="post">
                <h2 class="title"><a href="<?php echo $url; ?>"><?php echo $post['title']; ?></a></h2>
                <p class="description"><?php echo sprintf(trans('main.post.description'), $categoryLink, date('D jS F Y g:i A', strtotime($post['published_at']))); ?></p>
                <p class="short-content"><?php echo nl2br(get_word($post['content'], 40)); ?></p>
            </div>
            <?php
        }
        ?>
        <?php
        $pagination = $posts->links();
        $currentUrl = request()->getUri();
        $currentUrl = ( $currentUrl == $baseUrl . '/' ) ? $baseUrl : $currentUrl;
        $pattern = '#' . preg_quote($currentUrl) . '\?page=(\d+)#';
        $replacement = $replacementUrl . '/' . '$1';
        $pagination = preg_replace($pattern, $replacement, $pagination);
        echo $pagination;
    }
    ?>
</div>