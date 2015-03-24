<?php
global $anderson_makiyama, $user_level, $wpdb;

get_currentuserinfo();

if ($user_level < 10) { //Limita acesso para somente administradores

	return;

}	

$_POST      = array_map( 'stripslashes_deep', $_POST );
$_GET       = array_map( 'stripslashes_deep', $_GET );
$_COOKIE    = array_map( 'stripslashes_deep', $_COOKIE );
$_REQUEST   = array_map( 'stripslashes_deep', $_REQUEST );

$_GET["ed"] = (int)$_GET["ed"]; 


$table = $wpdb->prefix . self::CLASS_NAME . "_programas";


if (isset($_POST['submit'])) {
	
	if(!wp_verify_nonce( $_POST[self::CLASS_NAME], 'add' ) ){
		
		print 'Sorry, your nonce did not verify.';
		exit;

	}
	
	
	$wpdb->query( $wpdb->prepare( 
		"
		UPDATE $table
		SET nome_programa = %s
		WHERE id_programa = %d
		", 
		$_POST["nome_programa"],
		$_GET["ed"]
	) );	
		
			
	echo '<div id="message" class="updated">';
	echo '<p><strong>Programa Atualizado com Sucesso!</strong></p>';
	echo '</div>';
			
}

 
$prog = $wpdb->get_row( $wpdb->prepare(
	"SELECT * FROM $table
	WHERE id_programa = %d"
	,
	$_GET["ed"]
	), ARRAY_A );


$admin_url = get_admin_url();
$admin_url.= 'admin.php?page=' . self::CLASS_NAME . "_Programas";


?>
<div class="wrap">
<div class="icon32"><img src='<?php echo plugins_url('/images/icon-32.png', dirname(__FILE__))?>' /></div>
 
    
  		<table width="100%"><tr>
        <td style="vertical-align:top">
      
  		<form action="<?php echo $admin_url?>&ed=<?php echo $_GET["ed"]?>" method="post">
        
				<?php
                 wp_nonce_field('add',self::CLASS_NAME);
				?>
        <div class="metabox-holder">         

		<div class="postbox" >

			
        	<div class="inside">
                
                <h3>Edite o  Programa               </h3>
                <p>
                  
                  <span class="subtitulos">Nome:</span>
                  <input type="text" name="nome_programa" class="regular-text" value="<?php echo $prog["nome_programa"]?>" id="nome_dj" required="required" />              

              </p> 
              
              <p>
                <input type="submit" name="submit" value="Salvar" class="myButton" />
				


			</div>

		</div>

        </div>
 		</form></td>
        <td width="320" style="vertical-align:top; width:320px">

        
        <div class="metabox-holder">

		<div class="postbox" >
    

             <h3 style="font-size:24px; text-transform:uppercase;color:#F00;">Você é um Afiliado?</h3>

            

             <h3>Conheça o Melhor Gerenciador de Links: <a href="http://hotplus.net.br/plugin-hotlinks-plus/" target="_blank">Hot Links</a></h3>


        	<div class="inside">

                <p>

                <a href="http://hotplus.net.br/plugin-hotlinks-plus/" target="_blank"><img src="<?php echo $anderson_makiyama[self::PLUGIN_ID]->plugin_url?>images/hotplus.jpg" ></a>

				</p>
				

			</div>

 
 		</div>
        </div>
        
        <div class="metabox-holder">

		<div class="postbox" >
    

             <h3 style="font-size:24px; text-transform:uppercase;color:#F00;">Pague 1, Leve 87!</h3>

            

             <h3>Super Pack de Temas Premium: <a href="http://plugin-wp.net/elegantthemes" target="_blank">Elegant Themes</a></h3>


        	<div class="inside">

                <p>

                <a href="http://plugin-wp.net/elegantthemes" target="_blank"><img src="<?php echo $anderson_makiyama[self::PLUGIN_ID]->plugin_url?>images/elegantthemes.jpg" ></a>

				</p>
				

			</div>

 
 		</div>
        </div>
              

       </td>
       </tr>
       </table>


<hr />


<table class="author">
<tr>
<td>
<img src="<?php echo $anderson_makiyama[self::PLUGIN_ID]->plugin_url?>images/anderson-makiyama.png" />
</td>
<td>
<ul>
<li>Autor: <strong>Anderson Makiyama</strong>
</li>
<li>Email: <a href="mailto:andersonmaki@gmail.com" target="_blank">andersonmaki@gmail.com</a>
</li>
<li>Página do Plugin: <a href="<?php echo self::PLUGIN_PAGE?>" target="_blank"><?php echo self::PLUGIN_PAGE?></a>
</li>
</ul>
</td>
</tr>
</table>


</div>
<script>

jQuery(document).ready(function($) {
     $('#listagem-tables').dataTable( {
        "order": []
    } );
});
</script>
<script type="text/javascript">
jQuery(document).ready(function($){
    $('#upload-btn').click(function(e) {
        e.preventDefault();
        var image = wp.media({ 
            title: 'Upload Image',
            // mutiple: true if you want to upload multiple files at once
            multiple: false
        }).open()
        .on('select', function(e){
            // This will return the selected image from the Media Uploader, the result is an object
            var uploaded_image = image.state().get('selection').first();
            // We convert uploaded_image to a JSON object to make accessing it easier
            // Output to the console uploaded_image
            console.log(uploaded_image);
            var image_url = uploaded_image.toJSON().url;
            // Let's assign the url value to the input field
            $('#image_url').val(image_url);
        });
    });
});
</script>