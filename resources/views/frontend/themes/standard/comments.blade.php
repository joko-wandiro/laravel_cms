<?php
if (count($comments)) {
    ?>
    <div id="comments">
        <?php
        foreach ($comments as $comment) {
            ?>
            <div class="comment">
                <div class="row">
                    <div class="col-sm-6">
                        <p>
                            <img src="<?php echo get_gravatar($comment['email'], 50); ?>" alt="gravatar" />
                            <span class="c-name"><?php echo $comment['name']; ?></span>
                        </p>
                    </div>
                    <div class="col-sm-6">
                        <p class="c-date"><?php echo date('d F Y \a\t g:i A', strtotime($comment['created_at'])); ?></p>
                    </div>
                </div>
                <p class="c-comment"><?php echo nl2br($comment['comment']); ?></p>
            </div>
            <?php
        }
        ?>
    </div>
    <?php
}
?>