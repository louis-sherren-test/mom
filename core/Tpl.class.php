<?php
class libTpl {

    private $complie = true;
    private $var = array();
    private $tpl = array();
    private $ext = 'html';
    private $tpl_cache = "tpl_cache";
    private $tpl_file = "tpl";

    public function assign($variable, $value=null) {
        if (is_array($variable)) {
            $this->var += $variable;
        } else {
            $this->var[$variable] = $value;
        }
	}

	public function assignex($variable)
	{
		if(!is_array($variable))
			return false;
		foreach($variable as $k=>$v)
		{
			$this->assign($k,$v);
		}
	}

    public function draw($tpl_name, $return_string = FALSE) {
		
    	if(ErrorHandler::hasError() && DEBUG_MODE == 1)
       	{
       		$this->assign('errors',ErrorHandler::$error);
			$this->assign('exceptions',ErrorHandler::$exception);
			$this->assign('lefts',ErrorHandler::$left);
			$this->assign('notices',ErrorHandler::$notice);
			$this->assign('warnings',ErrorHandler::$warning);
			$this->complie('test');
			ob_start();
        	extract($this->var);
        	include C('SAE_FILE_PREFIX').$this->tpl['complied_filename'];
        	$complie_code = ob_get_contents();
        	ob_end_clean();
	       	if ($return_string) {
	            return $complie_code;
	        } else {
	        	header("Content-type: text/html; charset=utf-8");
	            echo $complie_code;
	        }
       		exit;
		}
		$this->complie($tpl_name); //To compile the code
        //--------------------
        //get the output buffer
        //--------------------
        ob_start();
        extract($this->var);
        include C('SAE_FILE_PREFIX').$this->tpl['complied_filename'];
        $complie_code = ob_get_contents();
        ob_end_clean();
		
       	
        //--------------------
        // output the buffer
        //-------------------
        if ($return_string) {
            return $complie_code;
		} else {
			header("Content-type: text/html; charset=utf-8");
            echo $complie_code;
        }
    }

    /**
     * To complie the template
     * @param type $tplname 
     */
    public function complie($tpl_name) {
        $filename = ROOT_PATH . '/' . $this->tpl_file . '/' . $tpl_name . '.' . $this->ext;
        $cachefilename = ROOT_PATH . '/' . $this->tpl_cache . '/' . $tpl_name . '.' . $this->ext;
        $this->tpl['complied_filename'] = $cachefilename;

        if ((!is_writeable(ROOT_PATH . '/' . $this->tpl_cache) && ON_SAE == 0)) {
			throw new libTpl_Exception('Cache directory ' . ROOT_PATH . '/' . $this->tpl_cache . 'doesn\'t have write permission. Set write permission or set RAINTPL_CHECK_TEMPLATE_UPDATE to false.');
	
        }
	

        if (!is_readable(ROOT_PATH . '/' . $this->tpl_file)) {
            throw new libTpl_Exception('Cache directory ' . ROOT_PATH . '/' . $this->tpl_file . 'doesn\'t have write permission. Set write permission or set RAINTPL_CHECK_TEMPLATE_UPDATE to false.');
        }

       /* if (!is_dir($newfile = substr($cachefilename, 0, strripos($cachefilename, '/')))) {
            mkdir($newfile, 0755, true);
	   }*/

        //if  the file doesn't modifyed ,that we don't need to complie it
        if(!$this->complie){
            if (filemtime($filename) < filemtime($cachefilename)) {
                return true;
            }
        }
        
    	$contents = file_get_contents($filename);		
		$module_regexp = array(
		'mod' => '(\{module=".*"\})',
		);
		$reg_string = '/' . implode('|', $module_regexp) . '/'; 
		//$contents = '';
		$template_code_mode = preg_split($reg_string, $contents, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);
		$contents = '';
		while($module_code = array_shift($template_code_mode))
		{
			if(preg_match('/\{module="(.*)"\}/',$module_code,$module_c))
			{
				$contents .= file_get_contents(ROOT_PATH . '/tpl/' . $module_c[1] . '.html');
			}
			else
			{
				$contents .= $module_code;  
			}
		}

        //end
        //$contents = file_get_contents($filename);
        $looplevel = 0;
        //tag list
        $tag_regexp = array(
            'if' => '(\{if=".*?"\})',
            'if_close' => '(\{\/if\})',
            'else' => '(\{else\})',
            'elseif' => '(\{elseif=".*?"\})',
            'loop' => '(\{loop=".*"\})',
            'loop_close' => '(\{\/loop\})',
            'template_info' => '(\{template_info\})',
            'include' => '(\{include=".*?"\})',
        );

        $reg_string = '/' . implode('|', $tag_regexp) . '/'; 
        //split the code with the reg_string
        $template_code = preg_split($reg_string, $contents, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);

        //start to complie the html code to phpcode
        $complied_code = "";
        while ($code = array_shift($template_code)) {

            //if
            if (preg_match('/\{if="(.*?)"\}/', $code, $phpcode)) {

                //tag
                $tag = $phpcode[0];
                //if code
                $condintions = $this->changevar($phpcode[1], $looplevel);

                $complied_code .= "<?php if( $condintions ){ ?>";

                //end of if close the php
            } else if (preg_match('/\{\/if\}/', $code, $phpcode)) {

                $complied_code .= "<?php } ?>";
            } else if (preg_match('/\{else\}/', $code, $phpcode)) {

                $complied_code .= "<?php }else{ ?>";
            } else if (preg_match('/\{elseif="(.*?)"\}/', $code, $phpcode)) {

                //tag
                $tag = $phpcode[0];

                //condition
                $condintions = $phpcode[1];

                $complied_code .= "<?php }else if( $condintions ){ ?> ";
            } else if (preg_match('/\{template_info\}/', $code, $phpcode)) {

                //show all vars
                $complied_code .= '<?php echo "<pre>"; print_r( $this->var ); echo "</pre>"; ?>';
            } else if (preg_match('/\{loop="(.*?)"\}/', $code, $phpcode)) {

                $looplevel++; 
                //tag 
                $tag = $phpcode[0];

                //condition
                $tmplevel = $looplevel == 1 ? $looplevel : $looplevel - 1;

                $var = $this->changevar($phpcode[1], $tmplevel);


                $value = '$value' . $looplevel;

                $key = '$key' . $looplevel;

                $complied_code .= "<?php if(isset($var)&&is_array($var)&&sizeof($var)) foreach($var as $key=>$value){?>";
            } else if (preg_match('/\{\/loop\}/', $code, $phpcode)) {

                $looplevel--;
                $complied_code .= "<?php } ?>";
            }else if(preg_match('/\{include="(.*)"\}/',$code,$phpcode)){
                
                $complied_code .= "<?php  ?>";
            } else {

                $complied_code .= $this->changelast($code, $looplevel);
            }
        }
        //end complie
        //save file
        file_put_contents(C('SAE_FILE_PREFIX').$cachefilename, $complied_code);
    }

    public function changevar($phpcode, $looplevel) {
    	
        $html = null;
        if ($looplevel > 0) {
            $phpcode = str_replace('$value', '$value' . $looplevel, $phpcode);
            $phpcode = str_replace('$key', '$key' . $looplevel, $phpcode);
            $first = true;
            $var_array = preg_split('/\./', $phpcode);
            foreach ($var_array as $value) {
                if ($first) {
                    $html .= $value;
                    $first = false;
                } else {
                    $html .= "['" . $value . "']";
                }
            }
        } else {
            $var_array = preg_split('/\./', $phpcode);
            $first = true;
            foreach ($var_array as $value) {
                if ($first) {
                    $html .= $value;
                    $first = false;
                } else {
                    $html .= "['" . $value . "']";
                }
            }
        }
        return $html;
    }

    public function changelast($code, $looplevel) {

        $result_code = null;

        $var_array = preg_split('/(\{\$.*?\})/', $code, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);

        while ($html = array_shift($var_array)) {
            if (preg_match('/\{(\$.*)\}/', $html, $phpcode)) {
                $var = $this->changevar($phpcode[1], $looplevel);
                $result_code .= "<?php echo isset($var) ? $var : \"\"; ?>";
            } else {
                $result_code .= "$html";
            }
        }
        return $result_code;
    }

}

class libTpl_Exception extends Exception {

    private $templateFile = '';

    public function getTemplateFile() {
        return $this->templateFile;
    }

    public function setTemplateFile($templateFile) {
        $this->templateFile = (string) $templateFile;
        return $this;
    }

}

?>
