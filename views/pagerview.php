<div class="pagenation">
    <ul>
        <?php 
        //2ページ移行だったら「一番前へ」を表示
        if ( $current_page > 2) echo '<li class="prev"><a href="' . $base_url . '/post/search' . $pg .'1' . $path . '">&laquo;</a></li>';
        //最初のページ以外だったら「前へ」を表示
        if ( $current_page > 1) echo '<li class="prev"><a href="'. $base_url . '/post/search' . $pg . ($current_page-1) . $path . '">&lsaquo;</a></li>';
        
        for ($i=$loop_start; $i<=$loop_end; $i++) {
        
            if ($i > 0 && $total_page >= $i) {
        
                if($i == $current_page) echo '<li class="now_page">';
        
                else echo '<li>';
                echo '<a href="'. $base_url . '/post/search' . $pg . $i . $path . '">' . $i . '</a>';
                echo '</li>';
        
            }
        
        }
        
        //最後のページ以外だったら「次へ」を表示
        if ( $current_page < $total_page) echo '<li class="next"><a href="'. $base_url . '/post/search' .$pg . ($current_page+1) . $path . '">&rsaquo;</a></li>';
        
        //最後から２ページ前だったら「一番最後へ」を表示
        if ( $current_page < $total_page - 1) echo '<li class="next"><a href="'. $base_url . '/post/search' .$pg . $total_page . $path . '">&raquo;</a></li>';
        
        ?>
    </ul>
</div>
