<?php
class Upload
{
	private $cache;
	private $type;
	private $config;
	private $name;

	public function __construct($config,$name)
	{
		$this->config = $config;
		$this->name = $name;
	}

	public function doUpload()
	{
		$realType = strtolower(strrchr($_FILES[$this->name]['name'],'.'));
		if(!in_array($realType,$this->config['type']))
		{
			return -1;
		}
		$size = 1024 * $this->config['size'];
		if($_FILES[$this->name]['size'] > $size)
		{
			return -2;
		}
		$tmp_file = $_FILES[$this->name]['name'];
		$fileName = rand(1,10000).time().strrchr($tmp_file,'.');
		if(ON_SAE == 0)
		{
			$result = move_uploaded_file($_FILES[$this->name]['tmp_name'],$this->config['path'] . $fileName);
		}
		else
		{
			$stor = new SaeStorage();
			return $stor->upload('upload',$fileName,$_FILES[$this->name]['tmp_name']);
		}
		if(!$result)
		{
			return -3;
		}
		return DOCUMENT_ROOT . $this->config['path'] . $fileName;
	}	
}


?>
