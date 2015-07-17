<? 
if($_SERVER['REMOTE_ADDR'] != 'x.x.x.x') die;
function conf_dash($command, $ip, $key){
	$conf = "/home/dash/data/$ip/dash.conf";
	$file = file($conf);
	
	if($command == 'add'){
		if(count($file) == 6){
			$file[6] = "\nmasternode=1";
			$file[7] = "\nmasternodeprivkey=$key";
			file_put_contents($conf, $file, LOCK_EX);
		}
	}
	
	if($command == 'remove'){
		if(count($file) == 8){
			$file[5] = trim($file[5]);
			unset($file[7]);
			unset($file[6]);
			file_put_contents($conf, $file, LOCK_EX);
		}
	}
}

function dash_restart($ip){
	shell_exec("dash-cli -datadir=/home/dash/data/$ip stop > /dev/null 2>/dev/null &");
	sleep(10);
	shell_exec("dashd -datadir=/home/dash/data/$ip -daemon > /dev/null 2>/dev/null &");
}

if(empty($_GET['ip']) || empty($_GET['do'])) die('empty');
if(preg_match('/[^0-9-.]/', $_GET['ip']) || !filter_var($_GET['ip'], FILTER_VALIDATE_IP)) die('wrong_ip');
$ip = $_GET['ip'];

switch($_GET['do']){
	default: echo "no"; break;
	case 'restart': dash_restart($ip); break;
	case 'setup': 
		if(empty($_GET['key'])) die('empty_key');
		if(preg_match('/[^0-9a-zA-z]/', $_GET['key'])) die('wrong_key');
		conf_dash('remove', $ip, NULL);
		conf_dash('add', $ip, $_GET['key']);
		dash_restart($ip);
	break;
	case 'log':
		if(!is_dir("/var/www/$ip")) mkdir("/var/www/$ip", 0755);
		shell_exec("tar -czf /var/www/$ip/debug.tar.gz -C /home/dash/data/$ip/ debug.log");
	break;
}
