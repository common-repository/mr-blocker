<?php
if ( ! defined( 'ABSPATH' ) ) exit; 
if( current_user_can('administrator') ) {
    // true if user is not admin

       require('mbp_countries.php');
if(file_exists(ABSPATH.'blocked.txt')){
$mbp_fp=fopen(ABSPATH.'blocked.txt', 'r');
while (!feof($mbp_fp))
{
    $mbp_line=fgets($mbp_fp);

    //process line however you like
    $mbp_line=trim($mbp_line);

    //add to array
    $detect_get[]=$mbp_line;
}
fclose($mbp_fp);


}else{
    $detect_get[] = '';
}
echo'<input type="text" id="myInput" onkeyup="myFunction()" placeholder="Search for Country..">';
echo'<table id="myTable">';
echo'  <tr class="header">
    <th style="width:34%;">Select</th>
    <th style="width:40%;">Country</th>
  </tr>';
foreach($countryList as $key => $country){
    $country1 = str_replace("_"," ",$country);
if(in_array($country1,$detect_get))
{
    $status = 'checked';
}else{
    $status='';
}
echo'<tr><td><input type="checkbox" class="detect_check" name="'.$country.'" value="'.$country1.'" '.$status.'></td><td><span class="detect_country_name"> '.$country1.'&nbsp; &nbsp;<img src="http://www.geognos.com/api/en/countries/flag/'.$key.'.png" alt="'.$key.'" width="18" height="12"></span>&nbsp;&nbsp;&nbsp;&nbsp;</td></tr>';
}
echo'</table>';
wp_nonce_field( 'block_action', 'block_nonce_field' );
echo'</ul>';
echo'</div>';
}


?>
<style>

#myInput {
    background-image: url("<?php echo plugins_url('IMG/search-icon.png',__FILE__ ) ?>");
    background-size: contain;
    background-repeat: no-repeat; /* Do not repeat the icon image */
    width: 100%; /* Full-width */
    font-size: 16px; /* Increase font-size */
    padding: 12px 20px 12px 40px; /* Add some padding */
    border: 1px solid #ddd; /* Add a grey border */
    margin-bottom: 12px; /* Add some space below the input */
}

#myTable {
    border-collapse: collapse; /* Collapse borders */
    width: 100%; /* Full-width */
    border: 1px solid #ddd; /* Add a grey border */
    font-size: 18px; /* Increase font-size */
}

#myTable th, #myTable td {
    text-align: left; /* Left-align text */
    padding: 12px; /* Add padding */
}

#myTable tr {
    /* Add a bottom border to all table rows */
    border-bottom: 1px solid #ddd; 
}

#myTable tr.header, #myTable tr:hover {
    /* Add a grey background color to the table header and on hover */
    background-color: #333;
}
</style>

<script>
function myFunction() {
  // Declare variables 
  var input, filter, table, tr, td, i;
  input = document.getElementById("myInput");
  filter = input.value.toUpperCase();
  table = document.getElementById("myTable");
  tr = table.getElementsByTagName("tr");

  // Loop through all table rows, and hide those who don't match the search query
  for (i = 0; i < tr.length; i++) {
    td = tr[i].getElementsByTagName("td")[0];
    if (td) {
      if (td.innerHTML.toUpperCase().indexOf(filter) > -1) {
        tr[i].style.display = "";
      } else {
        tr[i].style.display = "none";
      }
    } 
  }
}
</script>