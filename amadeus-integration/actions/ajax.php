<?php
add_action( 'wp_ajax_search_hotels', 'search_hotels_callback' );
add_action( 'wp_ajax_nopriv_search_hotels', 'search_hotels_callback' );
function search_hotels_callback(){
	
	
    $api = new Google_places();
    $items = $api->make_search( $_POST['s'] );
	?>

	<div class="hotels-list" style="margin-top: 25px;">

		<?php foreach( $items as $item ) { ?>

			<?php
			$details = $item['details'];	
			$photos = $details['photos'];
			?>

			<div class="row hotel-item" style="border: 1px solid #f6f6f6; padding: 15px; padding-left: 5px; margin-bottom: 25px;">
				<div class="col photo">
					<img src="<?php echo $photos[0]; ?>" style="width: 220px;" />
				</div>
				<div class="col title">
					<h3><?php echo $item['name']; ?></h3>
					<!--<p class="rating">Rating: <b><?php echo $item['rating']; ?></b></p>-->
					<p class="address">Address: 
						<b>
							<a href="<?php echo $details['url']; ?>" target="_blank">
								<?php echo $item['formatted_address']; ?>
							</a>
						</b>
					</p>
				</div>
				<div class="col summary">
					<p class="short-summary">
						<?php echo $details['editorial_summary']['overview']; ?>
					</p>
					<p class="phone-number">
						<?php echo $details['international_phone_number']; ?>
					</p>
					<p class="rating">
						Rating is <b><?php echo $item['rating']; ?></b> of <?php echo $details['user_ratings_total']; ?> reviews
					</p>
				</div>
				<div class="col quote">
					<input 
					type="submit" 
					value="Request Quote"
					id="request-quote"
					data-url="<?php echo $details['url']; ?>"
					data-name="<?php echo $item['name']; ?>"
					/>
				</div>
			</div>

		<?php } ?>

	</div>

	<?php
	wp_die();
}


add_action( 'wp_enqueue_scripts', 'myajax_data', 99 );
function myajax_data(){

	wp_localize_script( 'leonardo-script', 'myajax',
		array(
			'url' => admin_url('admin-ajax.php')
		)
	);

}