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


$table = $wpdb->prefix . self::CLASS_NAME . "_djs";


if (isset($_POST['submit'])) {
	
	if(!wp_verify_nonce( $_POST[self::CLASS_NAME], 'add' ) ){
		
		print 'Sorry, your nonce did not verify.';
		exit;

	}
	
	
	$wpdb->query( $wpdb->prepare( 
		"
		INSERT INTO $table
		(nome_dj, foto_dj, link_dj, facebook_dj,twitter_dj, instagram_dj, youtube_dj)
		VALUES ( %s, %s, %s, %s, %s, %s, %s)
		", 
		$_POST["nome_dj"], 
		$_POST["foto_dj"], 
		$_POST["link_dj"],
		$_POST["facebook_dj"],
		$_POST["twitter_dj"],
		$_POST["instagram_dj"],
		$_POST["youtube_dj"]
	) );	

		//$wpdb->show_errors(); 
		//$wpdb->print_error();
		
			
	echo '<div id="message" class="updated">';
	echo '<p><strong>Locutor/DJ Cadastrado com Sucesso!</strong></p>';
	echo '</div>';
		

	
}


if(isset($_GET["del"])){

	$table_programacao = $wpdb->prefix . self::CLASS_NAME . "_programacao";

	//Exclui as programações do DJ
	$wpdb->query( $wpdb->prepare( 
		"
			DELETE FROM $table_programacao
			WHERE id_dj = %d
		", 
		$_GET["del"]
	) );
	
		
	$wpdb->query( $wpdb->prepare( 
		"
			DELETE FROM $table
			WHERE id_dj = %d
		", 
		$_GET["del"]
	) );
	
	echo '<div id="message" class="updated">';
	echo '<p><strong>Locutor/DJ Excluído com Sucesso!</strong></p>';
	echo '</div>';		
	
}

 
$djs = $wpdb->get_results( 
	"SELECT * FROM $table
	ORDER BY nome_dj DESC
	", ARRAY_A );


$admin_url = get_admin_url();
$admin_url.= 'admin.php?page=' . self::CLASS_NAME . "";


?>
<div class="wrap">
<div class="icon32"><img src='<?php echo plugins_url('/images/icon-32.png', dirname(__FILE__))?>' /></div>
 
    
  		<table width="100%"><tr>
        <td style="vertical-align:top">
      
  		<form action="<?php echo $admin_url?>" method="post">
        
				<?php
                 wp_nonce_field('add',self::CLASS_NAME);
				?>
        <div class="metabox-holder">         

		<div class="postbox" >

			
        	<div class="inside">
                
                <h3>Cadastre Novos Locutores/DJs                </h3>
                <p>
                  
                  <span class="subtitulos">Nome:</span>
                  <input type="text" name="nome_dj" class="regular-text" value="" id="nome_dj" required="required" />              

              </p> 
              
              <p>
              
    <div>
    <label for="image_url" class="subtitulos">Foto/Imagem</label>
    <input type="text" name="foto_dj" id="image_url" class="regular-text">
    <input type="button" name="upload-btn" id="upload-btn" class="button-secondary" value="Upload Image">

</div>

              </p>
                 <p>
                  
                  <span class="subtitulos">Site do DJ:</span>
                  <input type="text" name="link_dj" class="regular-text" value="" id="facebook_dj" />              

              </p>  
                 <p>
                  
                  <span class="subtitulos">Facebook:</span>
                  <input type="text" name="facebook_dj" class="regular-text" value="" id="facebook_dj" />              

              </p> 
                <p>
                  
                  <span class="subtitulos">Twitter:</span>
                  <input type="text" name="twitter_dj" class="regular-text" value="" id="twitter_dj" />              

              </p> 
                <p>
                  
                  <span class="subtitulos">Instagram:</span>
                  <input type="text" name="instagram_dj" class="regular-text" value="" id="instagram_dj" />              

              </p>                                           
                <p>
                  
                  <span class="subtitulos">Youtube:</span>
                  <input type="text" name="youtube_dj" class="regular-text" value="" id="youtube_dj" />              

              </p>                 
                                                                                             
                <p>
<input type="submit" name="submit" value="Cadastrar" class="myButton" />
                

				</p>



			</div>

		</div>

        </div>
 		</form>
        
        <div class="metabox-holder">         
		<div class="postbox" >
        	
        
        	<div class="inside">
            
            <h3>Locutores / Djs já Cadastrados</h3>
            
                <p>
                
                <table id="listagem-tables" class="display" cellspacing="0" width="100%">
                <thead>
				<tr>
				<th>Foto / Imagem</th> 
                <th>
				Nome do Locutor/DJ
                </th>                      
                <th>
                </th>        
				</tr>  
                </thead>         
                <tbody>                         
                <?php
					
					
					
					foreach($djs as $dj){
						
						$foto = trim($dj["foto_dj"]);
						$foto = empty($foto)?'':"<img width='108' height='134' src='". $foto ."'>";
						
						echo "<tr>";
						echo "<td class='center'>";
						echo $foto;
						echo "</td>";
						echo "<td class='center'>";
						echo $dj["nome_dj"];
						echo "</td>";						
	
						echo "<td class='center'>";
						echo "<a href='". $admin_url ."&ed=". $dj["id_dj"] ."' class='button-secondary'>Editar</a>";
						echo "&nbsp;&nbsp;<a href='javascript:my_confirm(\"Atenção, todas as programações do Locutor/DJ serão Excluídas. \nQuer Excluir Mesmo Assim?\",\"". $admin_url ."&del=". $dj["id_dj"] ."\");' class='button-secondary' onclick='javascript:my_confirm(\"Atenção, todas as programações do Locutor/DJ serão Excluídas. \nQuer Excluir Mesmo Assim?\",\"". $admin_url ."&del=". $dj["id_dj"] ."\");return false;'>Excluir</a>"; 
						echo "</td>";
						echo "</tr>";
					}
				?>
                </tbody>
                <tfoot> 
				<tr>
				<th>Foto/Imagem</th> 
                <th>
				Nome do Locutor/DJ
                </th>    
                <th>
                </th>                
				</tr>  
                </tfoot>                 
                </table>
                
                </p>

			</div>
		</div>
        </div>
 
   		</td>
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