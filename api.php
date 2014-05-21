<?PHP
	$exclude = array('.','..','.ftpquota','.htaccess', '.htpasswd', 'folder.gif', 'api.php', 'libs', 'index.html', 'index.php');
	$dir_to_browse = "./";
	$view_mode = '0';
	
	include('libs/finder-view-0.1/finder-view-0.1.php');
		
	if(!empty($_GET['callback'])) {
		$callback = $_GET['callback'];
	}
	
	if(!empty($_GET['folder'])) {
		$url_folder = base64_decode(trim($_GET['folder']));
		$dir_to_browse .= $url_folder."/";
	}
	
	if(!empty($_GET['mode'])) {
		$view_mode = $_GET['mode'];
	}
	
	$this_file_name = basename($_SERVER['PHP_SELF']);
	$this_file_size = filesize($this_file_name);
	
	$dir_list_to_browse = array();
	
	if(!empty($url_folder))
	{
		$folders_in_url = explode("/", $url_folder);
		$folders_in_url_count = count($folders_in_url);
		if($folders_in_url_count < 5)
			array_push($dir_list_to_browse, "");
		for($i=0;$i<$folders_in_url_count;$i++) {
			$temp = "/";
			for($j=0;$j<$i+1;$j++) {
				$temp .= "/".$folders_in_url[$j];
			}
			$temp = substr($temp, 1);
			array_push($dir_list_to_browse, $temp);
		}
	}

	if(!empty($_GET['callback']))
		echo $callback.'(';

	if($view_mode=='0') { 
		echo '[';
		if(count($dir_list_to_browse)==0) {
			$dir_to_browse = './';
			display_file_json($dir_to_browse);	
		} else if(count($dir_list_to_browse)<=5){
			for($i=0;$i<count($dir_list_to_browse);$i++) {
				if($dir_list_to_browse[$i]=="")
					$dir_to_browse = "./";
				else
					$dir_to_browse = "./".substr($dir_list_to_browse[$i],1)."/";
  				display_file_json($dir_to_browse);
				if($i < count($dir_list_to_browse)-1)
					echo ',';
			}
		} else {
			for($i=count($dir_list_to_browse)-5;$i<count($dir_list_to_browse);$i++) {
				if($dir_list_to_browse[$i]=="")
					$dir_to_browse = "./";
				else
					$dir_to_browse = "./".substr($dir_list_to_browse[$i],1)."/";
  				display_file_json($dir_to_browse);
				if($i < count($dir_list_to_browse)-1)
					echo ',';
			}
		}
		echo ']';
	} else if($view_mode=='1') {
		$dir_to_browse = "./";
		if(!empty($_GET['folder'])) {
			$url_folder = base64_decode(trim($_GET['folder']));
			$dir_to_browse .= $url_folder."/";
		}
		echo $dir_to_browse.'<hr/>';
		display_file_json($dir_to_browse);
	}
		
	if(!empty($_GET['callback']))
		echo ')';
?>