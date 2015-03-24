<?php

/*

Plugin Name: Programação DJs

Plugin URI: http://plugin-wp.net/

Description: Exiba a programação e o DJ do momento

Author: Anderson Makiyama

Version: 1.0

Author URI: http://plugin-wp.net/

*/

date_default_timezone_set("America/Sao_Paulo");

class Anderson_Makiyama_Programacao_Djs{


	const CLASS_NAME = 'Anderson_Makiyama_Programacao_Djs';

	public static $CLASS_NAME = self::CLASS_NAME;

	const PLUGIN_ID = 9;

	public static $PLUGIN_ID = self::PLUGIN_ID;

	const PLUGIN_NAME = 'Programação DJs';

	public static $PLUGIN_NAME = self::PLUGIN_NAME;

	const PLUGIN_PAGE = 'http://plugin-wp.net';

	public static $PLUGIN_PAGE = self::PLUGIN_PAGE;

	const PLUGIN_VERSION = '1.0';

	public static $PLUGIN_VERSION = self::PLUGIN_VERSION;
	
	const AUTHOR_SITE = 'plugin-wp.net';

	public $plugin_basename;

	public $plugin_path;

	public $plugin_url;

	

	public function get_static_var($var) {

        return self::$$var;

    }

	public static function get_site_url(){
		
		$url_site = get_bloginfo("siteurl");
		$url_site_array = explode("/",$url_site);
		$end = end($url_site_array);
		
		if($end != "/") $url_site.= "/";	
		
		return $url_site;
	}
	
	public function utf8_urldecode($str) {
        return html_entity_decode(preg_replace("/%u([0-9a-f]{3,4})/i", "&#x\\1;", urldecode($str)), null, 'UTF-8');
	}
	
	public function activation(){
		global $wpdb;
		
			
		$table = $wpdb->prefix . self::CLASS_NAME . "_djs";

		//Cria Tabela DJs
		$sql = "CREATE TABLE $table (
		  id_dj int(11) NOT NULL AUTO_INCREMENT,
		  nome_dj tinytext NOT NULL,
		  foto_dj tinytext NOT NULL,
		  link_dj tinytext NOT NULL,
		  facebook_dj tinytext NOT NULL,
		  twitter_dj tinytext NOT NULL,
		  instagram_dj tinytext NOT NULL,
		  youtube_dj tinytext NOT NULL,
		  UNIQUE KEY id_dj (id_dj)
		);";
		
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		
		dbDelta( $sql );

		//Cria Tabela Programas
		$table = $wpdb->prefix . self::CLASS_NAME . "_programas";
		$sql = "CREATE TABLE $table (
		  id_programa int(11) NOT NULL AUTO_INCREMENT,
		  nome_programa tinytext NOT NULL,
		  UNIQUE KEY id_programa (id_programa)
		);";
		
		dbDelta( $sql );


		$table = $wpdb->prefix . self::CLASS_NAME . "_programacao";

		//Cria Tabela Programação
		$sql = "CREATE TABLE $table (
		  id_programacao int(11) NOT NULL AUTO_INCREMENT,
		  dia_da_semana int(11) NOT NULL,
		  id_programa int(11) NOT NULL,
		  id_dj int(11) NOT NULL,
		  hora_inicio time NOT NULL,
		  hora_fim time NOT NULL,
		  UNIQUE KEY id_programacao (id_programacao)
		);";
		
		dbDelta( $sql );
		
			
	}
	
	public function Anderson_Makiyama_Programacao_Djs(){ //__construct()

		$this->plugin_basename = plugin_basename(__FILE__);

		$this->plugin_path = dirname(__FILE__) . "/";

		$this->plugin_url = WP_PLUGIN_URL . "/" . basename(dirname(__FILE__)) . "/";


		//load_plugin_textdomain( self::CLASS_NAME, false, strtolower(str_replace(" ","-",self::PLUGIN_NAME)) . '/lang' );


	}
	
	
	public function settings_link($links) { 

		global $anderson_makiyama;

		$settings_link = '<a href="options-general.php?page='. self::CLASS_NAME .'">Locutores / DJs</a>'; 

		array_unshift($links, $settings_link); 

		return $links; 

	}	



	public function options(){


		global $anderson_makiyama, $user_level;

		get_currentuserinfo();

		if (function_exists('add_options_page')) { //Adiciona pagina na seção Configurações

			add_options_page(self::PLUGIN_NAME, self::PLUGIN_NAME, 1, self::CLASS_NAME, array(self::CLASS_NAME,'app_config_page'));

		}

		if (function_exists('add_submenu_page')){ //Adiciona pagina na seção plugins

			add_submenu_page( "plugins.php",self::PLUGIN_NAME,self::PLUGIN_NAME,1, self::CLASS_NAME, array(self::CLASS_NAME,'locutores_djs_page'));			  

		}

  		 add_menu_page(self::PLUGIN_NAME, self::PLUGIN_NAME,1, self::CLASS_NAME,array(self::CLASS_NAME,'locutores_djs_page'), plugins_url('/images/icon.png', __FILE__));

		 //add_submenu_page(self::CLASS_NAME,self::PLUGIN_NAME, 'Locutores / DJs',1, self::CLASS_NAME . "_Locutores_Djs", array(self::CLASS_NAME,'locutores_djs_page'));
		 
		 add_submenu_page(self::CLASS_NAME,self::PLUGIN_NAME, 'Programas',1, self::CLASS_NAME . "_Programas", array(self::CLASS_NAME,'programas_page'));
		 
		 add_submenu_page(self::CLASS_NAME,self::PLUGIN_NAME, 'Programação',1, self::CLASS_NAME . "_Programacao", array(self::CLASS_NAME,'programacao_page'));
		 		 
		 add_submenu_page(self::CLASS_NAME, self::PLUGIN_NAME,'Código Exibição',1, self::CLASS_NAME . "_Codigo", array(self::CLASS_NAME,'codigo_page'));
		 
		 global $submenu;
		 if ( isset( $submenu[self::CLASS_NAME] ) )
			$submenu[self::CLASS_NAME][0][0] = 'Locutores / DJS';

	}	

	
	public function programacao_page(){

		include("templates/programacao.php");

	}
	
	public function programas_page(){

		if(isset($_GET["ed"])){
			include("templates/editar-programa.php");
		}else{
			include("templates/programas.php");
		}
	}
	
	public function codigo_page(){

		include("templates/codigo.php");

	}		

	public function locutores_djs_page(){
		
		if(isset($_GET["ed"])){
			include("templates/editar-locutores-djs.php");
		}else{
			include("templates/locutores-djs.php");
		}
	}	
	
	
	public static function make_data($data, $anoConta,$mesConta,$diaConta){


	   $ano = substr($data,0,4);

	   $mes = substr($data,5,2);

	   $dia = substr($data,8,2);

	   return date('Y-m-d',mktime (0, 0, 0, $mes+($mesConta), $dia+($diaConta), $ano+($anoConta)));	

	}

	
	public function admin_estilos($hook) {
		
		if(strpos($hook,self::CLASS_NAME) === false) return;
		
		wp_register_style(self::CLASS_NAME . '_admin', plugins_url('css/admin.css', __FILE__), array(), '1.0.0', 'all');
		wp_enqueue_style(self::CLASS_NAME . '_admin');
	 
		wp_register_style(self::CLASS_NAME . '_admin_dataTable', plugins_url('css/jquery.dataTables.css', __FILE__), array(), '1.0.0', 'all');
		wp_enqueue_style(self::CLASS_NAME . '_admin_dataTable');
			 
	 
	}

	
	public function admin_js($hook) {
		
		global $anderson_makiyama;
		
		if(strpos($hook,self::CLASS_NAME) === false ) return;
			 
		wp_enqueue_media();
		
		wp_enqueue_script( self::CLASS_NAME . "_js_admin_datatable", $anderson_makiyama[self::PLUGIN_ID]->plugin_url . 'js/jquery.dataTables.js', array('jquery') );
		wp_enqueue_script( self::CLASS_NAME . "_js_admin_main", $anderson_makiyama[self::PLUGIN_ID]->plugin_url . 'js/admin.js', array('jquery') );
	 
	}
	
	public static function str_replace_first($search, $replace, $subject) {
    
		$pos = strpos($subject, $search);
		if ($pos !== false) {
			$subject = substr_replace($subject, $replace, $pos, strlen($search));
		}
		return $subject;
	
	}
	

	public static function get_data_array($data,$part=''){


	   $data_ = array();

	   $data_["ano"] = substr($data,0,4);

	   $data_["mes"] = substr($data,5,2);

	   $data_["dia"] = substr($data,8,2);

	   if(empty($part))return $data_;

	   return $data_[$part];

	}
	
	public function get_programacao($atts){
		
		global $anderson_makiyama, $wpdb;

		$table = $wpdb->prefix . self::CLASS_NAME . "_programacao";
		$table_djs = $wpdb->prefix . self::CLASS_NAME . "_djs";
		$table_progs = $wpdb->prefix . self::CLASS_NAME . "_programas";

		$dia = jddayofweek ( cal_to_jd(CAL_GREGORIAN, date("m"),date("d"), date("Y")) , 0 ); 
		$dia++;
		
		$hora = date("H:i");
		
		$prog = $wpdb->get_row( 
			"SELECT tdjs.*, tprogs.nome_programa, DATE_FORMAT(tpro.hora_inicio,'%H:%i') as hora_inicio, DATE_FORMAT(tpro.hora_fim,'%H:%i') as hora_fim FROM $table tpro
			INNER JOIN $table_djs tdjs
			ON tdjs.id_dj = tpro.id_dj
			INNER JOIN $table_progs tprogs
			ON tprogs.id_programa = tpro.id_programa
			WHERE tpro.dia_da_semana=".$dia."
			AND '". $hora .":00' between tpro.hora_inicio AND tpro.hora_fim",
			 ARRAY_A );
		
		if(!$prog) return;
		
		$facebook = !empty($prog["facebook_dj"])?"<a href='". $prog["facebook_dj"] ."' target='_blank' rel='nofollow'><img src='". $anderson_makiyama[self::PLUGIN_ID]->plugin_url ."images/facebook.png' style='padding:0px;margin:0px;border:0px;'></a>":'';
		$twitter = !empty($prog["twitter_dj"])?"<a href='". $prog["twitter_dj"] ."' target='_blank' rel='nofollow'><img src='". $anderson_makiyama[self::PLUGIN_ID]->plugin_url ."images/twitter.png' style='padding:0px;margin:0px;border:0px;'></a>":'';
		$instagram = !empty($prog["instagram_dj"])?"<a href='". $prog["instagam_dj"] ."' target='_blank' rel='nofollow'><img src='". $anderson_makiyama[self::PLUGIN_ID]->plugin_url ."images/instagram.png' style='padding:0px;margin:0px;border:0px;'></a>":'';
		$youtube = !empty($prog["youtube_dj"])?"<a href='". $prog["youtube_dj"] ."' target='_blank' rel='nofollow'><img src='". $anderson_makiyama[self::PLUGIN_ID]->plugin_url ."images/youtube.png' style='padding:0px;margin:0px;border:0px;'></a>":'';
		
		$redes = array();
		if(!empty($facebook)) $redes[] = $facebook;
		if(!empty($twitter)) $redes[] = $twitter;
		if(!empty($instagram)) $redes[] = $instagram;
		if(!empty($youtube)) $redes[] = $youtube;
		
		for($i=0;$i<4;$i++){
			if(!isset($redes[$i])) $redes[$i] = '';	
		}
		
		$foto = !empty($prog["link_dj"])?"<a href='". $prog["link_dj"] ."' target='_blank' rel='nofollow'><img style='padding:0px;margin:0px;border:0px;' width='108' height='134' src='". $prog["foto_dj"] ."'></a>":"<img style='padding:0px;margin:0px;border:0px;' width='108' height='134' src='". $prog["foto_dj"] ."'>";
				
		$display = "<div style='position:relative;width:284px;height:185px;padding:0;margin:0;border:0;'>
		<div style='position:absolute;top:10px;left:120px;font-family:Tahoma;font-weight:bold;'>". $prog["nome_programa"] ."</div>
		<div style='position:absolute;top:31px;left:120px;font-size:10px;font-family:Tahoma;font-weight:bold;'>Das ". $prog["hora_inicio"]." às ". $prog["hora_fim"]."</div>
		<div style='position:absolute;top:30px;left:230px;'><img src='". $anderson_makiyama[self::PLUGIN_ID]->plugin_url ."images/speaker.gif' width='50' style='padding:0px;margin:0px;border:0px;'></div>
		<div style='position:absolute;top:70px;left:120px;font-family:Tahoma;font-weight:bold;color:#FF0000;'>".$prog["nome_dj"]."</div>
		<div style='position:absolute;top:3px;left:4px;font-family:arial;'>". $foto ."</div>
		<div style='position:absolute;top:112px;left:1px;font-family:arial;'><img style='padding:0px;margin:0px;border:0px;' src='". $anderson_makiyama[self::PLUGIN_ID]->plugin_url ."images/noar.png'></div>
		<div style='position:absolute;top:102px;left:114px;font-family:arial;'>". $redes[0] ."</div>
		<div style='position:absolute;top:100px;left:136px;font-family:arial;'>". $redes[1] ."</div>
		<div style='position:absolute;top:98px;left:160px;font-family:arial;'>". $redes[2] ."</div>
		<div style='position:absolute;top:96px;left:184px;font-family:arial;'>". $redes[3] ."</div>
		<img src='". $anderson_makiyama[self::PLUGIN_ID]->plugin_url ."images/fundo.png' style='padding:0px;margin:0px;border:0px;'>
		</div>"; 	
		
		return $display;
 
	}
		


}

if(!isset($anderson_makiyama)) $anderson_makiyama = array();

$anderson_makiyama_indice = Anderson_Makiyama_Programacao_Djs::PLUGIN_ID;

$anderson_makiyama[$anderson_makiyama_indice] = new Anderson_Makiyama_Programacao_Djs();

add_filter("plugin_action_links_". $anderson_makiyama[$anderson_makiyama_indice]->plugin_basename, array($anderson_makiyama[$anderson_makiyama_indice]->get_static_var('CLASS_NAME'), 'settings_link') );

add_filter("admin_menu", array($anderson_makiyama[$anderson_makiyama_indice]->get_static_var('CLASS_NAME'), 'options'),30);

register_activation_hook( __FILE__, array($anderson_makiyama[$anderson_makiyama_indice]->get_static_var('CLASS_NAME'), 'activation') );

add_action( 'admin_enqueue_scripts', array($anderson_makiyama[$anderson_makiyama_indice]->get_static_var('CLASS_NAME'), 'admin_estilos') );

add_action( 'admin_enqueue_scripts', array($anderson_makiyama[$anderson_makiyama_indice]->get_static_var('CLASS_NAME'), 'admin_js') );


add_shortcode('programacaodj', array($anderson_makiyama[$anderson_makiyama_indice]->get_static_var('CLASS_NAME'), 'get_programacao'));

class Anderson_Makiyama_Programacao_Djs_W extends WP_Widget {

	// constructor
	function Anderson_Makiyama_Programacao_Djs_W() {
		parent::WP_Widget(false, $name = 'Programação Djs' );

	}

	function form($instance) {
		echo "<h2>Programação DJs / Locutores</h2>";
	}
	
	// widget display
	function widget($args, $instance) {
	   extract( $args );
	   
	   // these are the widget options
	   $title = apply_filters('widget_title', $instance['title']);
	   $text = do_shortcode('[programacaodj]');
	  
	   echo $before_widget;
	   // Display the widget
	   echo '<div class="widget-text wp_widget_plugin_box">';
	
	   // Check if title is set
	   //if ( $title ) {
		 // echo $before_title . $title . $after_title;
	  // }
	
	   // Check if text is set
	   if( $text ) {
		  echo '<p class="wp_widget_plugin_text">'.$text.'</p>';
	   }

	   echo '</div>';
	   echo $after_widget;
	}
	
}

// register widget
add_action('widgets_init', create_function('', 'return register_widget("Anderson_Makiyama_Programacao_Djs_W");'));
?>