<?php
/**
 * Template Name: Authorize Strava
 *
 * @package WordPress
 * @subpackage Twenty_Fourteen
 * @since Twenty Fourteen 1.0
 */
get_header();?>

<div class="wrap">
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

        <p>llevas <span class="km"></span> Km recorridos</p>
            <form action="" method="post">
                <input type="text" name="nombre" class="nombre">
                <input type="number " name="stravaid" class="stravaid">
                <input type="text" name="username" class="username">
                <input type="text" name="authcode" class="authcode" style="display:none;">
                <input type="submit" name="SubmitButton">
            </form>

			<?php
if(isset($_POST['SubmitButton'])){ //check if form was submitted
    $username = $_POST['username']; //get input text
    $nombre = $_POST['nombre']; //get input text
    $stravaid = $_POST['stravaid']; //get input text
    $authcode = $_POST['authcode']; //get input text
    
    $userdata = array(
        'user_login'  =>  $username,
        'display_name'    =>  $nombre,
        'user_pass'   =>  NULL  // When creating an user, `user_pass` is expected.
    );
    $user_id = wp_insert_user( $userdata ) ;
    
    //On success
    if ( ! is_wp_error( $user_id ) ) {
        // echo "User created : ". $user_id;
        add_user_meta( $user_id, 'strava_id', $stravaid);
        add_user_meta( $user_id, 'authcode', $authcode);
        echo '<div id="message" class="success"><p>Usuario Creado</p></div>';
    } else {
        $error_string = $user_id->get_error_message();
       echo '<div id="message" class="error"><p>' . $error_string . '</p></div>';
    }
  }    
			?>


		</main><!-- #main -->
	</div><!-- #primary -->
</div><!-- .wrap -->

<script>


$(document).ready(function(){
    var getUrlParameter = function getUrlParameter(sParam) {
        var sPageURL = decodeURIComponent(window.location.search.substring(1)),
            sURLVariables = sPageURL.split('&'),
            sParameterName,
            i;

        for (i = 0; i < sURLVariables.length; i++) {
            sParameterName = sURLVariables[i].split('=');

            if (sParameterName[0] === sParam) {
                return sParameterName[1] === undefined ? true : sParameterName[1];
            }
        }
    };

    var code = getUrlParameter('code');
    console.log(code);

    $.post( "https://www.strava.com/oauth/token?client_id=24077&client_secret=5b8452e1e057081f0f189f6ce0aa806c0b0ddadb&code="+code+"", function( data ) {
        console.log(data);
        var nombre = data.athlete.firstname+' '+data.athlete.lastname;
        $('.nombre').val(nombre);
        var stravaid = data.athlete.id;
        $('.stravaid').val(stravaid);
        var username = data.athlete.username;
        $('.username').val(username);
        var pic_url = data.athlete.profile;
        $('.profile').attr('src',pic_url);
        $('.authcode').val(code);
        // $('.username').val(data.athlete.username);

        var id = data.athlete.id;
        console.log(id);
        $.get( "https://www.strava.com/api/v3/athletes/"+id+"/stats?access_token=1ee5269fa44de9a7709e0ee1798efc1aab4a9ae6", function( data ) {
            console.log(data);
            var km_totales = data.all_ride_totals.distance;
            console.log(km_totales);
            $('.km').text(km_totales/1000);
        });

    });


});
</script>
<?php get_footer();
