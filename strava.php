<?php
/**
 * Template Name: Strava
 *
 * @package WordPress
 * @subpackage Twenty_Fourteen
 * @since Twenty Fourteen 1.0
 */
get_header();?>

<div class="wrap">
	<div id="primary" class="content-area">
		<main id="main" class="site-main container" role="main">

			<?php
			while ( have_posts() ) : the_post();

				get_template_part( 'template-parts/page/content', 'page' );

				// If comments are open or we have at least one comment, load up the comment template.
				

            endwhile; // End of the loop.
//             $user = wp_get_current_user();
// // print_r ($user); 
// $user_id = $user->ID;
// // $strava_id = 1326693;
// // add_user_meta( $user_id, 'strava_id', $strava_id);
// echo $user->strava_id;
//  $server = "http://" . $_SERVER['SERVER_NAME']; 
// require_once( $_SERVER['CONTEXT_DOCUMENT_ROOT'] . '/wordpress/' . 'wp-load.php' );
// $membersArray = get_users( 'role=subscriber' );
?>
<div class="row">
<div class="col">
<?php // Array of WP_User objects.
    // foreach ( $membersArray as $ciclista ){
    //   echo '<p>'.( $ciclista->nickname ).': '.$ciclista->strava_id.'</p>';
    // print_r($ciclista->strava_id);
    // } 

			?>
</div>
</div>
<?php $baseurl = $_SERVER['SERVER_NAME'];

 echo $baseurl;
if ($baseurl=='localhost') {
    $baseurl='http://'.$baseurl.'/wordpress';
    // echo $baseurl;
} elseif ($baseurl=='www.nelsonizquierdo.com.ve') {
    $baseurl='https://'.$baseurl;
}
?>
<p><?php echo $baseurl;?></p>
<a href="http://www.strava.com/oauth/authorize?client_id=24077&redirect_uri=<?php echo $baseurl;?>/autorizar/&response_type=code&approval_prompt=auto&scope=public&state">Link</a>

		</main><!-- #main -->
	</div><!-- #primary -->
</div><!-- .wrap -->

<script>
$.post( "https://www.strava.com/oauth/token?client_id=24077&client_secret=5b8452e1e057081f0f189f6ce0aa806c0b0ddadb&code=73303d27437b22472cf6fffb6abb25a5fc817a16", function( data ) {
    $( "#strava" ).html( data );  
    console.log(data.athlete.username);
    var pic_url = data.athlete.profile;
    $('.profile').attr('src',pic_url);
    $('.username').text(data.athlete.username);

    var id = data.athlete.id;
    console.log(id);
    $.get( "https://www.strava.com/api/v3/athletes/"+id+"/stats?access_token=1ee5269fa44de9a7709e0ee1798efc1aab4a9ae6", function( data ) {
        console.log(data);
        var km_totales = data.all_ride_totals.distance;
        console.log(km_totales);
        $('.km').text(km_totales/1000);
    });

});




</script>
<?php get_footer();
