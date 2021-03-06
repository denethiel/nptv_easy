<?php 


	
function url_check( $url ){
        $headers = @get_headers($url);
        return is_array($headers) ? preg_match('/^HTTP\\/\\d+\\.\\d+\\s+2\\d\\d\\s+.*$/',$headers[0]) : false;
}

//Solo funciona con el dominio XEU
function buscar_enlaces($url){

 		$links = array();
		$dom = new DOMDocument('1.0','UTF-8');
 		$internalErrors = libxml_use_internal_errors(true);
 		$dom->validateOnParse = true;
		$dom->loadHTML(file_get_contents($url));
		libxml_use_internal_errors($internalErrors);
		$xpath = new DOMXPath($dom);
		$elements = $xpath->query("//span[@class='titulonota']");
 		foreach($elements as $element){
       		$link = 'http://www.xeu.com.mx/' . $element->firstChild->getAttribute('href');
       		array_push($links, $link);
  		}
 		return $links;
 	
}

function get_news($cat){

	//Deberia ser siempre nacional si no se manda la categoria.
	$noticias = array();
	$url = '';
	switch ($cat) {
		case 'nacional':
			$url = 'http://www.xeu.com.mx/nacional.cfm';
			break;
		case 'estatal':
			$url = 'http://www.xeu.com.mx/estado.cfm';
			break;

		case 'internacional':
			$url = 'http://www.xeu.com.mx/internacional.cfm';
			break;
		
		default:
			# code...
			break;

	}
	$list_of_urls = buscar_enlaces($url);
	foreach ($list_of_urls as $new_url) {
		$noticias[] = _get_news_data($new_url);
	}

	return $noticias;

}

function _get_news_data($url){
		$dom = new DOMDocument('1.0','UTF-8');
    	$internalErrors = libxml_use_internal_errors(true);
		$dom->validateOnParse = true;
		$dom->loadHTML(file_get_contents($url));
		libxml_use_internal_errors($internalErrors);
		if(strpos($url, 'xeu')){
				try{
					$xpath = new DOMXPath($dom);
					$titulo_node = $xpath->query("//div[@class='fgtitulo']");
					//var_dump($titulo_node);

					$titulo = utf8_decode($titulo_node[0]->nodeValue);

					$text_node = $xpath->query("//div[@class='fatrece']");
					$text = $text_node[0]->textContent;
					//var_dump($text_node);

					$imagen_node = $xpath->query("//img[@class='imgpadding']");
					$imagen = $imagen_node[0]->getAttribute('src');
					
					if( is_string($titulo) && is_string($imagen) && is_string($text)){
						// WP_Query arguments
						$args = array(
							's'                      => $titulo,
						);

						// The Query
						$query = new WP_Query( $args );
						$nptv_nota = array(
							'error' => false,
							'titulo' => $titulo,
							'imagen' => $imagen,
							'texto'  => $text,
							'error' => false,
						);

						// The Loop
						if ( $query->have_posts() ) {
							$nptv_nota += ['publish' => true];
						} else {
							// no posts found
							$nptv_nota += ['publish' => false];
						}

						// Restore original Post Data
						wp_reset_postdata();
						
					}else{
						throw new Exception('Error al parsear la web');
					}
				}catch(Exception $e){
					$nptv_nota = array(
						'error' => true,
						'message' => $e->getMessage());
				}
			}
		return $nptv_nota;
	
}

function add_new($titulo, $imagen, $texto, $cat_id, $tags){
	$user_id = get_current_user_id();
	$post = array(
					'post_title' => $titulo,
					'post_content' => $texto,
					'post_status' => 'publish',
					'post_author' => $user_id,
					'post_category' => array($cat_id),
					'tags_input' => $tags
				);
	$post_id = wp_insert_post($post);
	$tmp = download_url( $imagen, 500 );
	$desc = "Imagen-" . date('F.m.d.y.H.i.s') . "";
	$file_array = array();
	preg_match('/[^\?]+\.(jpg|jpe|jpeg|gif|png)/i', $imagen, $matches);
	$file_array['name'] = basename($matches[0]);
	$file_array['tmp_name'] = $tmp;
	// If error storing temporarily, unlink
	if ( is_wp_error( $tmp ) ) {
		@unlink($file_array['tmp_name']);
		$file_array['tmp_name'] = '';
	}

	// do the validation and storage stuff
	$id = media_handle_sideload( $file_array, $post_id, $desc );

	// If error storing permanently, unlink
	if ( is_wp_error($id) ) {
		@unlink($file_array['tmp_name']);
		return $id;
	}
	$src = wp_get_attachment_url( $id );
	//var_dump($src);
	set_post_thumbnail( $post_id, $id );
	if(!is_wp_error($post_id)){
		return array(
			'error' => false,
			'post_id' => $post_id
		);
	}else{
		return array(
			'error' => true,
			'msg' => $post_id->get_error_message()
		);
	}
}

function agregar_noticia($url, $cat_id, $tags){
    	global $options;
    	if(url_check($url)){
    		//Diario del Istmo
    		$dom = new DOMDocument('1.0','UTF-8');
    		$internalErrors = libxml_use_internal_errors(true);
			$dom->validateOnParse = true;
			$dom->loadHTML(file_get_contents($url));
			libxml_use_internal_errors($internalErrors);
			if(strpos($url,'diariodelistmo')){
				try{
					$titulo_node = $dom->getElementById('tituloNotaInt');
					$titulo = utf8_decode($titulo_node->nodeValue);
					$images = $dom->getElementsByTagName('center');
					$img_url = $images[0]->firstChild->getAttribute('src');
					$imagen = 'http://www.diariodelistmo.com/' . $img_url;
					$text_node = $dom->getElementById('texto');
					$text = utf8_decode($text_node->textContent);
					if( is_string($titulo) && is_string($imagen) && is_string($text)){
						$nptv_nota = array(
							'titulo' => $titulo,
							'imagen' => $imagen,
							'texto'  => $text,
							'error' => false,
						);
					}else{
						throw new Exception('Error al parsear la web');
					}
				}catch(Exception $e){
					$nptv_nota = array(
						'error' => true,
						'message' => $e->getMessage());
				}
			}elseif(strpos($url, 'xeu')){
				try{
					$xpath = new DOMXPath($dom);
					$titulo_node = $xpath->query("//div[@class='fgtitulo']");
					//var_dump($titulo_node);

					$titulo = utf8_decode($titulo_node[0]->nodeValue);

					$text_node = $xpath->query("//div[@class='fatrece']");
					$text = $text_node[0]->textContent;
					//var_dump($text_node);

					$imagen_node = $xpath->query("//img[@class='imgpadding']");
					$imagen = $imagen_node[0]->getAttribute('src');
					if( is_string($titulo) && is_string($imagen) && is_string($text)){
						$nptv_nota = array(
							'error' => false,
							'titulo' => $titulo,
							'imagen' => $imagen,
							'texto'  => $text,
							'error' => false,
						);
					}else{
						throw new Exception('Error al parsear la web');
					}
				}catch(Exception $e){
					$nptv_nota = array(
						'error' => true,
						'message' => $e->getMessage());
				}
			}
			if(!$nptv_nota['error']){
				$user_id = get_current_user_id();
				$post = array(
					'post_title' => $titulo,
					'post_content' => $text,
					'post_status' => 'publish',
					'post_author' => $user_id,
					'post_category' => array($cat_id),
					'tags_input' => $tags
				);

				$post_id = wp_insert_post($post);
				$tmp = download_url( $imagen, 500 );
				$desc = "Imagen-" . date('F.m.d.y.H.i.s') . "";
				$file_array = array();
				preg_match('/[^\?]+\.(jpg|jpe|jpeg|gif|png)/i', $imagen, $matches);
				$file_array['name'] = basename($matches[0]);
				$file_array['tmp_name'] = $tmp;
				// If error storing temporarily, unlink
				if ( is_wp_error( $tmp ) ) {
					@unlink($file_array['tmp_name']);
					$file_array['tmp_name'] = '';
				}

				// do the validation and storage stuff
				$id = media_handle_sideload( $file_array, $post_id, $desc );

				// If error storing permanently, unlink
				if ( is_wp_error($id) ) {
					@unlink($file_array['tmp_name']);
					return $id;
				}
				$src = wp_get_attachment_url( $id );
				//var_dump($src);
				set_post_thumbnail( $post_id, $id );
				$options['nptv_news'] = $nptv_nota;
				$options['last_updated'] = time();
		 		update_option( 'nptv_control', $options );
				return $nptv_nota;
			}else{
				return $nptv_nota;
			}
    	}
    }


 ?>
