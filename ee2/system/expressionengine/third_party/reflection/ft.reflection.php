<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once PATH_THIRD.'reflection/config.php';

class Reflection_ft extends EE_Fieldtype {

	var $info = array(
		'name'		=> REFLECTION_NAME,
		'version'	=> REFLECTION_VERSION
	);
	
	var $mode_options = array(
	  'clike'  => 'C like',
	  'clojure' => 'Clojure',
	  'coffeescript' => 'Coffeescript',
	  'css'    => 'CSS',
	  'diff'   => 'diff',
	  'gfm'  => 'gfm',
	  'groovy' => 'Groovy',
	  'haskel' => 'Haskel',
	  'htmlmixed'  => 'HTML Mixed',
	  'javascript'    => 'Javascript',
	  'lua'   => 'LUA',
	  'markdown' => 'Markdown',
	  'ntriples' => 'ntriples',
	  'pascal' => 'pascal',
	  'perl' => 'perl',
	  'php' => 'PHP',
	  'plsql'  => 'PLSQL',
	  'python'    => 'Python',
	  'r' => 'r',
	  'rpm' => 'rpm',
	  'rst'   => 'RST',
	  'ruby' => 'Ruby',
	  'rust' => 'Rust',
	  'scheme' => 'Scheme',
	  'smalltalk'  => 'Smalltalk',
	  'sparql'    => 'SPARQL',
	  'stex'   => 'STEX',
	  'tiddlywiki' => 'tiddlywiki',
	  'xml' => 'XML',
	  'xmlpure' =>'xmlpure',
	  'yaml' => 'YAML'
	);                
	
	var $theme_options = array(
		'cobalt' => 'Cobalt',
	  'default'  => 'Default',
	  'eclipse' => 'Eclipse',
	  'elegant'    => 'Elegant',
	  'monokai' => 'Monokai',
	  'neat'   => 'Neat',
	  'night' => 'Night',
	  'rubyblue' => 'Rubyblue'
	);
		
	function __construct()
	{
		
	  if (version_compare(APP_VER, '2.1.4', '>')) { parent::__construct(); } else { parent::EE_Fieldtype(); } 

		
		//build theme url path
	  $theme_folder_url = $this->EE->config->item('theme_folder_url');
		if (substr($theme_folder_url, -1) != '/') $theme_folder_url .= '/';
		$this->theme_url = $theme_folder_url.'third_party/reflection/';
		
		//prep-cache
		if (! isset($this->EE->session->cache['reflection']))
		{
			$this->EE->session->cache['reflection'] = array('includes' => array());
		}
		$this->cache =& $this->EE->session->cache['reflection'];
					
	}
	
	/**
	 * Display Field In the Publish Form
	 */
	function display_field($data)
	{
		  	  
		if(! in_array('main_includes', $this->cache['includes'])){
			$this->cache['includes']['main_includes'] = true;
			$this->EE->cp->add_to_head('<link rel="stylesheet" type="text/css" href="'.$this->theme_url.'lib/codemirror.css" />');
		  $this->EE->cp->add_to_foot('<script type="text/javascript" src="'.$this->theme_url.'lib/codemirror.js"></script>');
		  $this->EE->cp->add_to_foot('<script type="text/javascript" src="'.$this->theme_url.'javascript/reflection.js"></script>');
		}
	  
	  $this->_include_theme_css($this->settings['theme']);
	  $this->_include_mode_js($this->settings['mode']);
	  	  
		return form_textarea(array(
			'name'	=> $this->field_name,
			'id'	=> $this->field_id,
			'value'	=> $data,
			'class' => 'codemirror',
			'theme' => $this->settings['theme'],
			'mode' => $this->settings['mode']
		));
	}
	
	function display_cell($data)
	{
		
		if(! in_array('main_includes', $this->cache['includes'])){
			$this->cache['includes']['main_includes'] = true;
			$this->EE->cp->add_to_head('<link rel="stylesheet" type="text/css" href="'.$this->theme_url.'lib/codemirror.css" />');
		  $this->EE->cp->add_to_foot('<script type="text/javascript" src="'.$this->theme_url.'lib/codemirror.js"></script>');
		  $this->EE->cp->add_to_foot('<script type="text/javascript" src="'.$this->theme_url.'javascript/reflection.js"></script>');
		}
				
		$this->_include_theme_css($this->settings['theme']);
	  $this->_include_mode_js($this->settings['mode']);
			
		return form_textarea(array(
			'name'	=> $this->cell_name,
			'id'	=> 'test',
			'value'	=> $data,
			'class' => 'codemirror matrix-textarea',
			'theme' => $this->settings['theme'],
			'mode' => $this->settings['mode']
		));
	}
	
	/**
	 * Display Field Settings
	 */
	function display_settings($data)
	{
		$rows = $this->_field_settings($data);

		foreach ($rows as $row)
		{
			$this->EE->table->add_row($row[0], $row[1]);
		}
	}
	
	/**
	 * Display Cell Settings
	 */
	function display_cell_settings( $data )
	{
		// merge in default field settings
    $data = array_merge(
     array(
       'mode' => 'htmlmixed',
       'theme'   => 'default'
     ),
     $data
    );
		                        
		return array(
			//Mode
			array(
				'Editor Mode',
				form_dropdown('mode', $this->mode_options, $data['mode'])
			),

  		//Theme
  		array(
  			'Editor Theme',
  			form_dropdown('theme', $this->theme_options, $data['theme'])
  		));
	}
	
	/**
	 * Field Settings
	 */
	private function _field_settings($data, $attr = '')
	{
		// merge in default field settings
    $data = array_merge(
     array(
       'mode' => 'htmlmixed',
       'theme'   => 'default'
     ),
     $data
    );
		               
		return array(
			//Mode
			array(
				'Editor Mode',
				form_dropdown('reflection[mode]', $this->mode_options, $data['mode'])
			),

  		//Theme
  		array(
  			'Editor Theme',
  			form_dropdown('reflection[theme]', $this->theme_options, $data['theme'])
  		)
  		
		);
	}
	
	/**
	 * Save Field Settings
	 */
	function save_settings($settings)
	{
		$settings = $this->EE->input->post('reflection');

		// cross the T's
		$settings['field_type'] = 'reflection';

		return $settings;
	}
	
	/**
	 * Replace the field tag on the front end
	 */
	function replace_tag($data, $params = array(), $tagdata = FALSE)
	{
	  //must return the string to replace the tag
	  return $data;
	}
	
	/**
	* Include Theme CSS
	*/
	private function _include_theme_css($theme)
	{
		
		if(! isset($this->cache['includes']['theme'])){
			$this->cache['includes']['theme'] = array();
		}
		
		if(! in_array($theme, $this->cache['includes']['theme']))  {
			$this->cache['includes']['theme'][] = $theme;
			$this->EE->cp->add_to_head('<link rel="stylesheet" type="text/css" href="'.$this->theme_url.'theme/'.$theme.'.css" />');
		}
	
	}
	
	/**
	* Include Mode JS
	*/
	private function _include_mode_js($mode)
	{
		
		if(! isset($this->cache['includes']['mode'])){
			$this->cache['includes']['mode'] = array();
		}
				
		if (! in_array($mode, $this->cache['includes']['mode']))
		{
			
			if($mode == 'htmlmixed')
	    { 
				$this->cache['includes']['mode'][] = 'xml';
				$this->cache['includes']['mode'][] = 'javascript';
				$this->cache['includes']['mode'][] = 'css';
				$this->cache['includes']['mode'][] = 'htmlmixed';
		
			  $this->EE->cp->add_to_foot('<script type="text/javascript" src="'.$this->theme_url.'mode/xml/xml.js"></script>');
			  $this->EE->cp->add_to_foot('<script type="text/javascript" src="'.$this->theme_url.'mode/javascript/javascript.js"></script>');
			  $this->EE->cp->add_to_foot('<script type="text/javascript" src="'.$this->theme_url.'mode/css/css.js"></script>');
			  $this->EE->cp->add_to_foot('<script type="text/javascript" src="'.$this->theme_url.'mode/htmlmixed/htmlmixed.js"></script>');
	    }
	    else
	    {
				$this->cache['includes']['mode'][] = $mode;	
	     	$this->EE->cp->add_to_foot('<script type="text/javascript" src="'.$this->theme_url.'mode/'.$mode.'/'.$mode.'.js"></script>');
			}
		
		}
	     
	}
	
}
// END Reflection_ft class

/* End of file ft.reflection.php */
/* Location: ./system/expressionengine/third_party/reflection/ft.reflection.php */