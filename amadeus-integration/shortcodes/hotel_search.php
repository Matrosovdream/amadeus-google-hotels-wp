<?php
add_shortcode('hotel_search', 'hotel_search_func');
function hotel_search_func() {

    ob_start();
    ?>

    <div class="hotel-search-result"></div>

    <div id="ex1" class="modal">
        <?php
        echo do_shortcode('[gravityform id="18" title="true" description="true" ajax="true"]');
        ?>
        <!--
        <a href="#" rel="modal:close">Close</a>
        -->
    </div>

    <!-- Link to open the modal -->
    <p><a href="#ex1" class="open-window" rel="modal:open" style="display: none;">Open Modal</a></p>
    <a href="ya.ru"></a>

    <style>
        .blocker {
            z-index: 1000;
        }
        .jquery-modal .modal {
            height: auto!important;
            overflow: unset!important;
        }
        .hotels-preloader {
            text-align:center; 
            margin: 5px 0; 
            font-size: 40px;
            font-weight: bold;
            color: #fff;
        }

    </style>    

    <?php
    return ob_get_clean();

}