<?PHP
	error_reporting(0);

	function get_dir_content($path)
	{
		global $ftp_stream, $url_folder, $this_file_size, $this_file_name, $case_sensative_ext, $show_folder_size_ftp, $view_mode, $exclude_ext, $exclude, $listing_mode;
		
		
		$dh  = opendir($path);
			
		while (false !== ($item = readdir($dh)))
			$content[] = $item;
			
		if(empty($content))
			return $content;
			
		$media_detected = '';
		$images_detected = '';
				
		foreach($content as $key => $val)
		{
			if(!in_array($val, $exclude))
			{
				$item_path = $path.$val;
					
				if(is_dir($item_path))
				{
					$folders['name'][] = $val;
					$folders['date'][] = date("d F Y", filectime($item_path));
					$folders['link'][] = (empty($path)) ? $val : substr($path,2).$val;
				}
				else
				{
					$file_size = filesize($item_path);
	
					if(!($val == $this_file_name && $this_file_size == $file_size))//Exclude the main index file specifically
					{
						$file_ext = strrchr($val, ".");
							
						if($case_sensative_ext == 0) $file_ext = strtolower($file_ext);
							
						if(!in_array($file_ext, $exclude_ext))
						{
							$files['name'][] = $val;
							$files['size'][] = $file_size;
							$files['link'][] = $path.rawurlencode($val);
							$files['date'][] = date("d F Y", filectime($item_path));
							if($images_detected == '')
								$images_detected = (in_array(strtolower($file_ext), array('.jpeg', '.jpg', '.png', '.gif'))) ? 1 : 0;
							if($media_detected == '')
								$media_detected = (strtolower($file_ext) == '.mp3') ? 1 : 0;
						}
					}		
				}
			}
		}
		return @array('folders' => $folders, 'files' => $files, 'images_detected' => $images_detected, 'media_detected' => $media_detected);
	}
	
	function letter_size($byte_size)
	{
		$file_size = $byte_size/1024;
		if($file_size >=  1048576)
		$file_size = sprintf("%01.2f", $file_size/1048576)." GB";
		elseif ($file_size >=  1024)
		$file_size = sprintf("%01.2f", $file_size/1024)." MB";
		else
		$file_size = sprintf("%01.1f", $file_size)." KB";
		return $file_size;
	}
	
	function display_file_json($dir) {
		/* echo "<hr/>".$dir."<hr/>"; */
		if($case_sensative_ext == 0)
		foreach($exclude_ext as $key => $val)
			$exclude_ext[$key] = strtolower($val);
	
		$folders = array();
		$files = array();
		
		$dir_content = get_dir_content($dir);
		
		$folders['name'] = $dir_content['folders']['name'];
		$folders['date'] = $dir_content['folders']['date'];
		$folders['link'] = $dir_content['folders']['link'];
		
		$files['name'] = $dir_content['files']['name'];
		$files['size'] = $dir_content['files']['size'];
		$files['date'] = $dir_content['files']['date'];
		$files['link'] = $dir_content['files']['link'];
		
		$images_detected = $dir_content['images_detected'];
		$media_detected = $dir_content['media_detected'];
		
		if(!empty($folders['name']))
		{
			natcasesort($folders['name']);
			$folders_sorted = $folders['name'];			
		}
		else
			$folders_sorted = array();
			
		if(!empty($files['name']))
		{
			natcasesort($files['name']);
			$files_sorted = $files['name'];
		}
		else
			$files_sorted = array();
	
		if(!empty($url_folder))
		{
			$folders_in_url = explode("/", $url_folder);
			$folders_in_url_count = count($folders_in_url);
				
			$temp = "";
			for($j=0;$j<$folders_in_url_count-1;$j++)
				$temp .= "/".$folders_in_url[$j];
			$temp = substr($temp, 1);
		}
	 
		echo '{"folders":[';
		
		$count = 0;
		foreach($folders_sorted as $key => $val)
		{
			echo '{"name":"'.$folders['name'][$key].'",';
			echo '"folderuri":"'.base64_encode($folders['link'][$key]).'",';
			echo '"folderuri_nobase":"'.$folders['link'][$key].'",';
			echo '"size":"'.letter_size($folders['size'][$key]).'",';
			echo '"date":"'.$folders['date'][$key].'"}';
			$count++;
			if($count < count($folders_sorted)) echo ',';
		}
		echo '],"files":[';
		
		$count = 0;
		foreach($files_sorted as $key => $val)
		{
			echo '{"name":"'.$files['name'][$key].'",';
			echo '"link":"'.substr($files['link'][$key],1).'",';
			echo '"size":"'.letter_size($files['size'][$key]).'",';
			echo '"date":"'.$files['date'][$key].'"}';
			$count++;
			if($count < count($files_sorted)) echo ',';
		}
		
		echo ']}';
	}
?>