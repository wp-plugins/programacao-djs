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


$table_progs = $wpdb->prefix . self::CLASS_NAME . "_programas";
$table_djs = $wpdb->prefix . self::CLASS_NAME . "_djs";
$table_programacao = $wpdb->prefix . self::CLASS_NAME . "_programacao";


if (isset($_POST['submit'])) {
	
	if(!wp_verify_nonce( $_POST[self::CLASS_NAME], 'add' ) ){
		
		print 'Sorry, your nonce did not verify.';
		exit;

	}
	
	$fazer_nada = false;
	
	if($_POST["hora_inicio"]>$_POST["hora_fim"]){
		
		$fazer_nada = true;
		
		echo '<div id="message" class="error">';
		echo '<p><strong>OOPS! A Hora de Início precisa ser inferior a Hora do fim do Programa</strong></p>';
		echo '</div>';		
		
	}elseif($_POST["hora_inicio"] == $_POST["hora_fim"]){
		
		if($_POST["minuto_inicio"] >= $_POST["minuto_fim"]){
			
			$fazer_nada = true;
			
			echo '<div id="message" class="error">';
			echo '<p><strong>OOPS! A Hora de Início precisa ser inferior a Hora do fim do Programa</strong></p>';
			echo '</div>';				
			
		}
		
	}
	
	if(!$fazer_nada){
		
		
		foreach($_POST["dia_da_semana"] as $dia){

			$hr_inicio = $_POST["hora_inicio"] . ":" . $_POST["minuto_inicio"] . ":00";
			$hr_fim = $_POST["hora_fim"] . ":" . $_POST["minuto_fim"] . ":00";
			$wpdb->query( $wpdb->prepare( 
				"
				INSERT INTO $table_programacao
				(dia_da_semana, id_programa, id_dj, hora_inicio, hora_fim)
				VALUES ( %d, %d, %d, %s, %s)
				", 
				$dia, $_POST["programa"], $_POST["dj"], $hr_inicio , $hr_fim
			) );
			
		}
				
		echo '<div id="message" class="updated">';
		echo '<p><strong>Programação Cadastrada com Sucesso!</strong></p>';
		echo '</div>';
		
	}

	
}


if(isset($_GET["del"])){

	//Exclui as programações do DJ
	$wpdb->query( $wpdb->prepare( 
		"
			DELETE FROM $table_programacao
			WHERE id_programacao = %d
		", 
		$_GET["del"]
	) );
	
	echo '<div id="message" class="updated">';
	echo '<p><strong>Programação Excluída com Sucesso!</strong></p>';
	echo '</div>';		
	
}

 
$progs = $wpdb->get_results( 
	"SELECT * FROM $table_progs
	ORDER BY nome_programa
	", ARRAY_A );

$djs = $wpdb->get_results( 
	"SELECT * FROM $table_djs
	ORDER BY nome_dj
	", ARRAY_A );

$programacao = $wpdb->get_results( 
	"SELECT tpro.id_programacao, tpro.dia_da_semana, DATE_FORMAT(tpro.hora_inicio,'%H:%i') as hora_inicio, DATE_FORMAT(tpro.hora_fim,'%H:%i') as hora_fim, tprogs.*,tdjs.* FROM $table_programacao tpro
	INNER JOIN $table_djs tdjs 
	ON tdjs.id_dj = tpro.id_dj
	INNER JOIN $table_progs tprogs
	ON tprogs.id_programa = tpro.id_programa
	ORDER BY dia_da_semana, hora_inicio
	", ARRAY_A );
		
$admin_url = get_admin_url();
$admin_url.= 'admin.php?page=' . self::CLASS_NAME . "_Programacao";


$dias_da_semana = array();
$dias_da_semana[1] = "Domingo";
$dias_da_semana[2] = "Segunda";
$dias_da_semana[3] = "Terça";
$dias_da_semana[4] = "Quarta";
$dias_da_semana[5] = "Quinta";
$dias_da_semana[6] = "Sexta";
$dias_da_semana[7] = "Sábado";
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
                
                <h3>Cadastre a Programação               </h3>
                <p>
                  
                  <span class="subtitulos">Dia</span>
                  <select name="dia_da_semana[]" size="7" multiple="multiple" required="required">
                  <option value="1">Domingo</option>
                  <option value="2">Segunda</option>
                  <option value="3">Terça</option>
                  <option value="4">Quarta</option>
                  <option value="5">Quinta</option>
                  <option value="6">Sexta</option>
                  <option value="7">Sábado</option>
                  </select>             

                  <span class="subtitulos">DJ</span>
                  <select name="dj">
                  <?php
				  
				  foreach($djs as $dj){
                  	
					echo '<option value="'. $dj["id_dj"] .'">'. $dj["nome_dj"] .'</option>';
				  
				  }
				  ?>
                  </select>

                  <span class="subtitulos">Programa</span>
                  <select name="programa">
                  <?php
				  
				  foreach($progs as $prog){
                  	
					echo '<option value="'. $prog["id_programa"] .'">'. $prog["nome_programa"] .'</option>';
				  
				  }
				  ?>
                  </select>

                  <span class="subtitulos">Das</span>
                  <select name="hora_inicio">
                  <?php
				  
				  for($i=0;$i<24;$i++){
                  	
					echo '<option value="'. $i .'">'. str_pad($i,2,"0",STR_PAD_LEFT) .'</option>';
				  
				  }
				  ?>
                  </select>:
                  <select name="minuto_inicio">
                  <?php
				  
				  for($i=0;$i<60;$i++){
                  	
					echo '<option value="'. $i .'">'. str_pad($i,2,"0",STR_PAD_LEFT) .'</option>';
				  
				  }
				  ?>
                  </select>  
                  &nbsp;&nbsp;<span class="subtitulos">Até as</span>
                  <select name="hora_fim">
                  <?php
				  
				  for($i=0;$i<24;$i++){
                  	
					echo '<option value="'. $i .'">'. str_pad($i,2,"0",STR_PAD_LEFT) .'</option>';
				  
				  }
				  ?>
                  </select>:
                  <select name="minuto_fim">
                  <?php
				  
				  for($i=0;$i<60;$i++){
                  	
					echo '<option value="'. $i .'">'. str_pad($i,2,"0",STR_PAD_LEFT) .'</option>';
				  
				  }
				  ?>
                  </select>               </p> 
              
              <p>
              Dica: Para selecionar mais de um dia da semana mantenha a tecla Ctrl pressionada enquanto clica sobre os dias.<br />
                <input type="submit" name="submit" value="Cadastrar" class="myButton" />
				


			</div>

		</div>

        </div>
 		</form>
        
        <div class="metabox-holder">         
		<div class="postbox" >
        	
        
        	<div class="inside">
            
            <h3>Programação já Cadastrada</h3>
            
                <p>
                
                <table id="listagem-tables" class="display" cellspacing="0" width="100%">
                <thead>
				<tr>
                <th>
				Dia
                </th>  
                <th>
				Horário
                </th>
                <th>
				DJ / Locutor
                </th> 
                <th>
				Programa
                </th> 
                <th>
                </th>        
				</tr>  
                </thead>         
                <tbody>                         
                <?php
					
					
					
					foreach($programacao as $prog){
						
						$horario  = "Das " . $prog["hora_inicio"];
						$horario .= " às " . $prog["hora_fim"];
						
						echo "<tr>";
						echo "<td class='center'>";
						echo $dias_da_semana[$prog["dia_da_semana"]];
						echo "</td>";
						echo "<td class='center'>";
						echo $horario;
						echo "</td>";
						echo "<td class='center'>";
						echo $prog["nome_dj"];
						echo "</td>";
						echo "<td class='center'>";
						echo $prog["nome_programa"];
						echo "</td>";																		
						echo "<td class='center'>";
						echo "&nbsp;&nbsp;<a href='javascript:my_confirm(\"Quer Mesmo Excluir essa Programação?\",\"". $admin_url ."&del=". $prog["id_programacao"] ."\");' class='button-secondary' onclick='javascript:my_confirm(\"Quer Mesmo Excluir essa Programação?\",\"". $admin_url ."&del=". $prog["id_programacao"] ."\");return false;'>Excluir</a>"; 
						echo "</td>";
						echo "</tr>";
					}
				?>
                </tbody>
                <tfoot> 
				<tr> 
                <th>
				Dia
                </th>  
                <th>
				Horário
                </th>
                <th>
				DJ / Locutor
                </th> 
                <th>
				Programa
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