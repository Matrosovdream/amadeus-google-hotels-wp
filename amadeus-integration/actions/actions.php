<?php
function add_stylesheet_to_head() {

    $google_key = 'AIzaSyBzEn_H1qqz4T1DhTC1l_IPG5wPJxWTZT4';

    // Bootstrap
    echo '<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">';

    // Main JS
    echo '<script src="'.PW_PLUGIN_DIR.'inc/js.js?time='.time().'" crossorigin="anonymous"></script>';

    echo "
    <!-- Remember to include jQuery :) -->
    <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.0.0/jquery.min.js'></script>

    <script src='https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&libraries=places&key={$google_key}'></script>

    <!-- jQuery Modal -->
    <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.js'></script>
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.css' />
    ";
	
}
add_action( 'wp_head', 'add_stylesheet_to_head' );


// Monday integration
add_action( 'gform_after_submission', 'set_post_content', 10, 2 );
function set_post_content( $entry, $form ) {

    $data['name'] = $entry[4];
    $data['mail'] = $entry[8];
    $data['phone'] = $entry[15].$entry[7];
    $data['country_code'] = $entry[15];
    $data['message'] = $entry[10];
    $data['start_date'] = '';
    $data['end_date'] = '';
    $data['hotel_name'] = $entry[13];
    $data['hotel_url'] = $entry[14];

    if( trim($entry[11]) != '' ) {
        $data['start_date'] = $entry[11];
    }
    if( trim($entry[12]) != '' ) {
        $data['end_date'] = $entry[12];
    }

    $monday = new Monday_integration;
    $monday->query( $data );

}