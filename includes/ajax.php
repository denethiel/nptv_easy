<?php 
class nptv_ajax {

	private function _ajax_return( $response = true ){
        echo json_encode( $response );
        exit;
    }

    private function _url_check( $url ){
        $headers = @get_headers($url);
        return is_array($headers) ? preg_match('/^HTTP\\/\\d+\\.\\d+\\s+2\\d\\d\\s+.*$/',$headers[0]) : false;
    }

    public function agregar_noticia($url, $cat_id, $tags){
    	global $options;
    	if($this->_url_check($url)){
    		//Diario del Istmo
    		$dom = new DOMDOCument('1.0','UTF-8');
    		$internalErrors = libxml_use_internal_errors(true);
			$dom->validateOnParse = true;
			$dom->loadHTML(file_get_contents($url));
			libxml_use_internal_errors($internalErrors);
			if(strpos($url,'diariodelistmo')){
				try{
					
				}catch(){
					
				}
			}
    	}
    }

}
 ?>
