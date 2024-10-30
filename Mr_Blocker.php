<?php
/*
Plugin Name: Mr Blocker
Plugin URI: http://mrblocker.extraviews.co
Description: It is a very simple tool to block the annoying traffic , Just check the boxes then block the annoying/suspicious traffic coming from unwanted countries .
Author: Mohamed Al-Lawati
Version: 1.2
*/
/////////////
function mbp_headers(){
if(!headers_sent() == true){
session_start();
ob_start();
}
}
mbp_headers();
if ( ! defined( 'ABSPATH' ) ) exit; 

function mbp_main_page() {

    add_menu_page(
        __( 'Mr Blocker', 'textdomain' ),
        'Mr Blocker',
        'manage_options',
        'Mr_Blocker.php',
        'mbp_Content',
        plugins_url('IMG/MR_Blocker.png',__FILE__ ),
        6
    );
}
add_action( 'admin_menu', 'mbp_main_page' );

function mbp_Content(){
    include('mbp_countries.php');
if( current_user_can('administrator') ) {

$detect_head = '';

$detect_head .= '<div class="detect_head">';
$detect_head .= 'Mr Blocker';
$detect_head .= '</div>';

$detect_head .= '<h4 class="note_detect_head"> Note .. You will have a normal access to your dashboard if you blocked your country ,  But you cannot visit the website</h4>';

$detect_head .= '<div class="detect_container">';
$detect_head .= '<h1 class="detect_head_message">Choose the countries you want to block</h1>';
$detect_head .= '<form method="post" class="detect_form">';

echo $detect_head;
require('detect_countries_form.php');
echo'<div>';
echo'</br></br>';
echo '<input type="submit" class="detect_submit" name="block" value="Block Now / Save">';
?>
<form method="POST">
<input type="submit" class="detect_submit" name="remove_all" value="Remove All">
</form>
<?php
    $url_refresh = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

if(isset($_POST['remove_all'])){
    unlink(ABSPATH.'/blocked.txt');
    header('Location:'.$url_refresh.'');
}

echo'</div>';
echo'</form>';
echo'</div>';

if(isset($_POST['block'])){
unlink(ABSPATH.'/blocked.txt');
if ( ! isset( $_POST['block_nonce_field'] ) 
    || ! wp_verify_nonce( $_POST['block_nonce_field'], 'block_action' ) 
) {
die( 'Failed security check' );
}else{
    foreach($countryList as $mbp_country_item){
if(isset($_POST[$mbp_country_item])){
$mbp_string_with_comma_unsanitized = sanitize_text_field(filter_var($_POST[$mbp_country_item]));
//Remove unwanted tags before being saved .

$string_with_comma = wp_kses( $mbp_string_with_comma_unsanitized );

file_put_contents(ABSPATH.'/blocked.txt', $string_with_comma . PHP_EOL, FILE_APPEND);
echo 'Data saved';

}}


header('Location:'.$url_refresh.'');

}}
}
}
/// -- Now We Hook into The Whole Website To get the ip addresses of the users/visitors -- \\\
$remote  = $_SERVER['REMOTE_ADDR'];
if(file_exists(ABSPATH.'/blocked.txt')){
$mbp_fp=fopen(ABSPATH.'blocked.txt', 'r');
while (!feof($mbp_fp))
{
    $mbp_line=fgets($mbp_fp);

    //process line however you like
    $mbp_line=trim($mbp_line);

    //add to array
    $mbp_blocked[]=$mbp_line;
}
fclose($mbp_fp);


}else{
$mbp_blocked[] = '';
}
// if the visitor country that we will get from ipinfo is in array (block_now) it will redirect the visitor to somewhere .

$url = 'http://ipinfo.io/'.$remote.'/json';
$request =   wp_remote_get($url);
// Get the body of the response
$response = wp_remote_retrieve_body( $request );
// Decode the json
$details = json_decode( $response ); 

$code = $details->country;
 function mbp_code_to_country( $code ){

    $code = strtoupper($code);

       require('mbp_countries.php');

    if( !$countryList[$code] ) return $code;
    else return $countryList[$code];
    }
     $mbp_block_now =  mbp_code_to_country( $code );
if(in_array($mbp_block_now, $mbp_blocked)){
$mbp_url = 'http://mrblocker.extraviews.co/blocked.php';
if ( ! is_admin() && ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) ) {
header("Location: $mbp_url");
}

}
function mbp_stylesheet() 
{
    wp_enqueue_style( 'myCSS', plugins_url( 'style.css', __FILE__ ) );
}
add_action('admin_print_styles', 'mbp_stylesheet');
?>