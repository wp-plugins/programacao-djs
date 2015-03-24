<?php
global $anderson_makiyama, $user_level;

get_currentuserinfo();

if ($user_level < 10) { //Limita acesso para somente administradores

	return;

}	

?>
<div class="wrap">
<div class="icon32"><img src='<?php echo plugins_url('/images/icon-32.png', dirname(__FILE__))?>' /></div>
        
<h2>Código para Exibir o Locutor / DJ e o Programa no Site</h2>
    
  		<table width="100%"><tr>
        <td style="vertical-align:top">

        <div class="metabox-holder">         
		<div class="postbox" >
        	<h3>1. Coloque o Código abaixo nos Posts e Páginas do Blog</h3>
        
        	<div class="inside">
            
                <p>
                <textarea cols="20" rows="2" style="font-size:24px;" onclick="javascript:this.select();" readonly="readonly">[programacaodj]</textarea>
                </p>
                
                <?php echo do_shortcode('[programacaodj]');?>

			</div>
            
        	<h3>2. Ou direto no PHP, use o seguinte:</h3>
        
        	<div class="inside">
            
                <p>
                <textarea cols="20" rows="2" style="font-size:24px;" onclick="javascript:this.select();" readonly="readonly"> &lt;?php echo do_shortcode('[programacaodj]');?&gt;</textarea>
                </p>
                
               

			</div>
            
           <h3>3. Você também pode arrastar o Widget "Programação DJ" para um Sidebar do seu Tema</h3> 
                        
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
        "order": [],
		"paging": false
    } );
});
</script>