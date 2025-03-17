<?php
namespace App\Core\Models;

use \App\Core\Models\Session;

defined('ROOT') or die("Direct script access denied");

class Security
{
    private static $session;

	/**
	 * Örnek Kullanım
	 * $key = "my_token_key";
	 * $token = Security::generateToken($key);
	 * echo "Oluşturulan Token: " . $token . PHP_EOL;
	 * @param  string $key [description]
	 * @return [type]      [description]
	 */
    public static function generateToken(string $key): string
    {
        $token = bin2hex(random_bytes(32));
        self::getSession()->set($key, $token);
        return $token;
    }

    public static function verifyToken(string $key, string $token): bool
    {
        $session = self::getSession();
        $isTokenValid = $session->get($key) === $token;

        if ($isTokenValid) {
            $session->remove($key);
        }

        return $isTokenValid;
    }

    private static function getSession(): Session
    {
        if (!isset(self::$session)) {
            self::$session = new Session();
        }
        return self::$session;
    }

    public static function csrf(string $sesKey = 'csrf', int $hours = 1):string
	{
		$key = '';
		$session = self::getSession();
		$key = $sesKey . "_" . hash('sha256', time() . rand(0,99));
		$expires = time() + ((60*60)*$hours);

		$session->set($sesKey,[
			'key'=> $key,
			'expires'=>$expires
		]);

		return "<input type='hidden' value='$key' name='$sesKey' />";
	}

	public static function csrf_verify(array $post, string $sesKey = 'csrf'):mixed
	{
		if(empty($post[$sesKey]))
			return false;

		$session = self::getSession();
		$data 	 = $session->get($sesKey);
		if(is_array($data))
		{
			if($data['key'] !== $post[$sesKey])
				return false;

			if($data['expires'] > time())
				return true;

			$session->remove($sesKey);
			
		}

		return false;
	}

	public static function SecurityCode($entered)
	{
	    $entered = trim($entered);
	    $entered = stripslashes($entered);
	    $entered = htmlspecialchars($entered, ENT_QUOTES | ENT_HTML5, 'UTF-8');
	    $entered = filter_var($entered, FILTER_SANITIZE_SPECIAL_CHARS);
	    $entered = str_replace("'", "&#39;", $entered);
	    $entered = str_replace('"', "&quot;", $entered);
	    $entered = str_replace('`', "&#96;", $entered);
	    $entered = preg_replace("/<script\b[^>]*>(.*?)<\/script>/is", "", $entered);
	    $entered = preg_replace("/<\s*iframe\s*[^>]*>/i", "", $entered);
	    $entered = preg_replace("/<\s*frame\s*[^>]*>/i", "", $entered);
	    $entered = preg_replace("/<\s*object\s*[^>]*>/i", "", $entered);
	    $entered = preg_replace('/\\\\u([0-9a-fA-F]{4})/', '&#x$1;', $entered);
	    return $entered;
	}

	public static function securityPassword($value): string
	{
	    $password       = sha1($value);
	    $ultraPassword  = mb_substr(md5($password),0,32);
	    return mb_substr(sha1($ultraPassword),0,32);
	}

	public static function uniqIdGen($param)
	{
		return uniqid($param.'_', true);
	}
}