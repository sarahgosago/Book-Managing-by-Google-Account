<div class="container">
    <h2> Comments </h2>

    <div class="col-md-3" id="comment-views">
        <?php
        foreach ($comment_results as $comment) {
            $comment_line = $comment['comment'] .
                $comment_line .= '<br>' . $comment['comment_id'] . '<br> <br>';

            echo $comment_line;
        }
        ?>
    </div>