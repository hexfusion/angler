<?PHP




class SugarSecure{
	var $results = array();
	function display(){
		echo '<table>';
		foreach($this->results as $result){
			echo '<tr><td>' . nl2br($result) . '</td></tr>';
		}
		echo '</table>';
	}
	
	function save($file=''){
		$fp = fopen($file, 'a');
		foreach($this->results as $result){
			fwrite($fp , $result);
		}
		fclose($fp);
	}
	
	function scan($path= '.', $ext = '.php'){
		$dir = dir($path);
		while($entry = $dir->read()){
			if(is_dir($path . '/' . $entry) && $entry != '.' && $entry != '..'){
				$this->scan($path .'/' . $entry);	
			}
			if(is_file($path . '/'. $entry) && substr($entry, strlen($entry) - strlen($ext), strlen($ext)) == $ext){
				$contents = file_get_contents($path .'/'. $entry);	
				$this->scanContents($contents, $path .'/'. $entry);
			}
		}
	}
	
	function scanContents($contents){
		return;	
	}
	
	
}

class ScanFileIncludes extends SugarSecure{
	function scanContents($contents, $file){
		$results = array();
		$found = '';
		/*preg_match_all("'(require_once\([^\)]*\\$[^\)]*\))'si", $contents, $results, PREG_SET_ORDER);
		foreach($results as $result){
			
			$found .= "\n" . $result[0];	
		}
		$results = array();
		preg_match_all("'include_once\([^\)]*\\$[^\)]*\)'si", $contents, $results, PREG_SET_ORDER);
		foreach($results as $result){
			$found .= "\n" . $result[0];	
		}
		*/
		$results = array();
		preg_match_all("'require\([^\)]*\\$[^\)]*\)'si", $contents, $results, PREG_SET_ORDER);
		foreach($results as $result){
			$found .= "\n" . $result[0];	
		}
		$results = array();
		preg_match_all("'include\([^\)]*\\$[^\)]*\)'si", $contents, $results, PREG_SET_ORDER);
		foreach($results as $result){
			$found .= "\n" . $result[0];	
		}
		$results = array();
		preg_match_all("'require_once\([^\)]*\\$[^\)]*\)'si", $contents, $results, PREG_SET_ORDER);
		foreach($results as $result){
			$found .= "\n" . $result[0];	
		}
		$results = array();
		preg_match_all("'fopen\([^\)]*\\$[^\)]*\)'si", $contents, $results, PREG_SET_ORDER);
		foreach($results as $result){
			$found .= "\n" . $result[0];	
		}
		$results = array();
		preg_match_all("'file_get_contents\([^\)]*\\$[^\)]*\)'si", $contents, $results, PREG_SET_ORDER);
		foreach($results as $result){
			$found .= "\n" . $result[0];	
		}
		if(!empty($found)){
			$this->results[] = $file . $found."\n\n";	
		}
		
	}
	
	
}
	


class SugarSecureManager{
	var $scanners = array();
	function registerScan($class){
		$this->scanners[] = new $class();
	}
	
	function scan(){
		
		while($scanner = current($this->scanners)){
			$scanner->scan();
			$scanner = next($this->scanners);
		}
		reset($this->scanners);	
	}
	
	function display(){
		
		while($scanner = current($this->scanners)){
			echo 'Scan Results: ';
			$scanner->display();
			$scanner = next($this->scanners);
		}
		reset($this->scanners);	
	}
	
	function save(){
		//reset($this->scanners);	
		$name = 'SugarSecure'. time() . '.txt';
		while($this->scanners  = next($this->scanners)){
			$scanner->save($name);
		}
	}
	
}
$secure = new SugarSecureManager();
$secure->registerScan('ScanFileIncludes');
$secure->scan();
$secure->display();
