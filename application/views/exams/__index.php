<div id="main-content" class="inlineBlock">
    <section class="scrolling-wrapper">
        <?php
        foreach ($exams as $key => $value) {
            echo '<div class="card">
            <div class=".card-image-container">
                <img src="' . URL . 'public/img/welcome-poster-spectrum-brush-strokes-260nw-1146069941.jpg" />
            </div>
            <h2> subject: ' . $value->subject->name . '</h2>
        </div>';
        }

        ?>
    </section>
</div>
</div>