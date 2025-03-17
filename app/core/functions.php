<?php

/**
 * System Fonksiyonları
 */
function APP($key = '')
{
	global $APP;

	if(!empty($key))
	{
		return !empty($APP[$key]) ? $APP[$key] : null;
	}else{

		return $APP;
	}

	return null;
}
/** Plugin Path */
function plugin_id():string
{
	$called_from = debug_backtrace();
	$ikey = array_search(__FUNCTION__, array_column($called_from, 'function'));
	$path = get_plugin_dir(debug_backtrace()[$ikey]['file']) . 'config.json';

	$json = json_decode(file_get_contents($path));
	return $json->plugin_settings->id ?? '';
}

function plugin_path(string $path = '')
{
	$called_from = debug_backtrace();
	$key = array_search(__FUNCTION__, array_column($called_from, 'function'));
	return get_plugin_dir(debug_backtrace()[$key]['file']) . $path;
}

function plugin_http_path(string $path = '')
{
	$called_from = debug_backtrace();
	$key = array_search(__FUNCTION__, array_column($called_from, 'function'));
	return ROOT . DS . get_plugin_dir(debug_backtrace()[$key]['file']) . $path;
}

function get_plugin_dir(string $filepath):string
{
	$path = "";
	$basename = basename($filepath);
	$path = str_replace($basename, "", $filepath);

	if(strstr($path, DS.'plugins'.DS))
	{
		$parts = explode(DS.'plugins'.DS,$path);
		$parts = explode(DS, $parts[1]);
		$path = 'plugins'.DS.$parts[0].DS;
	}
	return $path;
}

function get_plugin_folders()
{
	global $APP;

	if (empty($APP['all_plugin_folders'])) 
	{
		$plugins_folder = 'plugins/';
		$res = [];
		$folders = scandir($plugins_folder);
		foreach ($folders as $folder) {
			if($folder != '.' && $folder != '..' && is_dir($plugins_folder . $folder))
				$res[] = $folder;
		}
		
		$APP['all_plugin_folders'] = $res;
		return $res;
	}

	return $APP['all_plugin_folders'];
}

/** Session User */
function user_can($permission):bool
{
	if(empty($permission)) return true;

	$ses = new \App\Core\Models\Session;
	
	if($permission == 'logged_in')
	{
		if($ses->is_logged_in())
			return true;

		return false;
	}

	if($permission == 'not_logged_in')
	{
		if(!$ses->is_logged_in())
			return true;

		return false;
	}
	
	if($ses->is_admin())
		return true;

	global $APP;

	if (!isset($APP['user_permissions'])) {
        $APP['user_permissions'] = [];
    }

	$APP['user_permissions'] = \App\Core\Helpers\ActionFilterHelper::doFilter('user_permissions',$APP['user_permissions']);

	if(in_array('all', $APP['user_permissions']))
		return true;
	
	if(in_array($permission, $APP['user_permissions']))
		return true;

	return false;
}
/** Language */
function __lang($langCode)
{
    global $lang, $langDev;
    $lowerLangCode = strtolower($langCode); // Gelen dil kodunu küçük harfe çevir

    if (isset($langDev[$lowerLangCode])) {
        return $langDev[$lowerLangCode];
    } elseif (isset($lang[$lowerLangCode])) {
        return $lang[$lowerLangCode];
    } else {
        return "Çeviri yok: $lowerLangCode"; // Tanımlanmamış bir çeviri için hata mesajı
    }
}

/** Image */
function get_image(string $path = '', string $type = 'post')
{
	if(file_exists($path))
		return ROOT . '/' . $path;
	
	if($type == 'post')
		return ROOT . '/public/assets/images/no_image.jpg';

	if($type == 'male')
		return ROOT . '/public/assets/images/user_male.jpg';

	if($type == 'female')
		return ROOT . '/public/assets/images/user_female.jpg';

	return ROOT . '/public/assets/images/no_image.jpg';
}

function addCookie($name, $value, $expire = 0, $path = '/', $domain = '', $secure = false, $httponly = true) {
    setcookie($name, $value, $expire, $path, $domain, $secure, $httponly);
}

function permissionControlValidation($permission)
{
	if (!user_can($permission))
		return jsonResponse(401, "error", __lang("unauthorized_access"));

	return false;
}

function permissionFail()
{
	$data = '<div class="alert alert-danger text-center">'.__lang("access_denied_your_dont_have_permission_for_this_action").'</div>';
	return $data;
}

/** SEO FUNC */
function SEO($entered): string
{
    $Content = trim($entered);
    $Change = array("ç", "Ç", "ğ", "Ğ", "ı", "İ", "ö", "Ö", "ş", "Ş", "ü", "Ü", "а", "б", "в", "г", "д", "е", "ё", "ж", "з", "и", "й", "к", "л", "м", "н", "о", "п", "р", "с", "т", "у", "ф", "х", "ц", "ч", "ш", "щ", "ъ", "ы", "ь", "э", "ю", "я");
    $Changes = array("c", "C", "g", "G", "i", "I", "o", "O", "s", "S", "u", "U", "a", "b", "v", "g", "d", "e", "e", "zh", "z", "i", "y", "k", "l", "m", "n", "o", "p", "r", "s", "t", "u", "f", "kh", "ts", "ch", "sh", "sht", "", "y", "", "e", "yu", "ya");
    $Content = str_replace($Change, $Changes, $Content);
    $Content = mb_strtolower($Content, "UTF-8");
    $Content = html_entity_decode($Content);
    $Content = str_replace("&", "-", $Content);
    $Content = str_replace("amp", "-", $Content);
    $Content = preg_replace("/[^a-z0-9-]/", "-", $Content);
    $Content = preg_replace("/-+/", "-", $Content);
    return trim($Content, "-");
}
/** OTHER FUNC */
function dd($data)
{
	echo "<pre><div style='margin:1px;background-color:#444;color:white;padding:5px 10px'>";
	print_r($data);
	echo "</div></pre>";
}

function jsonResponse($statusCode,$status, $message, $data = []) {
    $response = array(
		"statusCode" => $statusCode,
        "status" => $status,
        "message" => $message,
    );

	if ($data != null) {
		$response["data"] = $data;
	}

	header("Content-Type: application/json; charset=utf-8");
	http_response_code($statusCode);
    return json_encode($response, JSON_UNESCAPED_UNICODE);
	//exit;
}

function validateWebAddress($web_address) {
    $pattern = '/^(http:\/\/www\.|https:\/\/www\.|http:\/\/|https:\/\/)?[a-zA-Z0-9]+([\-\.]{1}[a-zA-Z0-9]+)*\.[a-zA-Z]{2,5}(:[0-9]{1,5})?(\/.*)?$/';
    
    if (preg_match($pattern, $web_address)) {
        return true;
    } else {
        return false;
    }
}
function validateEmail(string $email): bool
{ return filter_var($email, FILTER_VALIDATE_EMAIL) !== false; }

function generateRandomPassword($length) 
{
    $characters = '123456789abcdefghijklmnopqrstuvwxyz';
    $charactersLength = strlen($characters);
    $randomPassword = '';
    
    for ($i = 0; $i < $length; $i++) {
        $randomPassword .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomPassword;
}

function NewImageNameGenerate()
{
    $response = substr(md5(uniqid(time())),0,25);
    return $response;
}

function Verot(
    $image,
    $image_name,
    $file_path,
    $allowed = ["image/*"],
    $image_x = null,
    $image_y = null,
    $remove_file_name = null,
){
    $data = new \Verot\Upload\Upload($image);
    if($data->uploaded){
        $data->file_overwrite = true;
        if ($image_x !== null && $image_y !== null) {
            $data->image_resize = true;
            $data->image_x = $image_x;
            $data->image_y = $image_y;
        }
        $data->file_new_name_body = $image_name;
        $data->allowed = $allowed;
        $data->process(realpath('.').$file_path);
        if ($data->processed) {
           $data->clean();
           if ($remove_file_name != null && $file_path != null) {
               unlink(realpath('.').$file_path.$remove_file_name);
           }
        }
    }
}


function uploadFile($name)
{
	return \App\Core\Models\Upload::getInstance($name);
}

function turkishDateFormate($isoDate) {
    // Zaman dilimi ayarla
    date_default_timezone_set('Europe/Istanbul');

    // Ay ve gün isimleri
    $aylar = [
        1 => 'Ocak', 'Şubat', 'Mart', 'Nisan', 'Mayıs', 'Haziran',
        'Temmuz', 'Ağustos', 'Eylül', 'Ekim', 'Kasım', 'Aralık'
    ];

    // Tarihi DateTime nesnesine çevir
    $dateTime = new DateTime($isoDate);

    // Ay ve yıl bilgilerini al
    $gun = $dateTime->format('d');
    $ay = $aylar[(int)$dateTime->format('m')];
    $yil = $dateTime->format('Y');
    $saat = $dateTime->format('H:i');

    // Format oluştur
    return "$gun $ay $yil, $saat";
}
