<?php
namespace App\Core\Models\GoogleDrive;

use Google\Client;
use Google\Service\Drive;
use Google\Service\Exception;

class GoogleAuth
{
    private $client;
    private $tokenPath = TOKEN_PATH;
    private $credentialsPath = CREDENTIALS_PATH;

    public function __construct()
    {
        $this->client = new Client();
        $this->client->setApplicationName('Google Drive API PHP');
        $this->client->setScopes(Drive::DRIVE);
        $this->client->setAuthConfig($this->credentialsPath);
        $this->client->setAccessType('offline');
        $this->client->setPrompt('select_account consent');
        $this->client->setRedirectUri(REDIRECT_URL);

    }

    /**
     * Yetkilendirme işlemi
     */
    public function authenticate()
    {
        // Token dosyasını kontrol et
        if (file_exists($this->tokenPath)) {
            $this->setAccessTokenFromFile();
        } else {
            $this->redirectToAuthUrl();
        }

        // Token süresini kontrol et ve yenile
        if ($this->client->isAccessTokenExpired()) {
            if ($this->client->getRefreshToken()) {
                $this->refreshAccessToken();
            } else {
                $this->redirectToAuthUrl();
            }
        }
    }

    /**
     * Token dosyasından erişim token'ını ayarlar
     */
    private function setAccessTokenFromFile()
    {
        $accessToken = json_decode(file_get_contents($this->tokenPath), true);
        $this->client->setAccessToken($accessToken);
    }

    /**
     * Kullanıcıyı yetkilendirme URL'sine yönlendirir
     */
    private function redirectToAuthUrl($web = false)
    {
        $authUrl = $this->client->createAuthUrl();
        if (!$web) {
            header('Location: ' . filter_var($authUrl, FILTER_SANITIZE_URL));
        } else {
            $response = [
                'redirect' => filter_var($authUrl, FILTER_SANITIZE_URL),
                'message' => 'Authorization required. Redirect to this URL to authenticate.'
            ];
            header('Content-Type: application/json');
            echo json_encode($response);
        }
        exit;
    }

    public function fetchAccessTokenWithAuthCode($code)
    {
        $accessToken = $this->client->fetchAccessTokenWithAuthCode($code);
        if (array_key_exists('error', $accessToken)) {
            throw new Exception('Error fetching access token: ' . $accessToken['error']);
        }

        $this->saveAccessToken($accessToken);
    }

    private function refreshAccessToken()
    {
        $this->client->fetchAccessTokenWithRefreshToken($this->client->getRefreshToken());
        $this->saveAccessToken($this->client->getAccessToken());
    }

    private function saveAccessToken($accessToken)
    {
        file_put_contents($this->tokenPath, json_encode($accessToken));
    }

    
    public function hasValidSession()
    {
        // Eğer token dosyası yoksa oturum geçersizdir
        if (!file_exists($this->tokenPath)) {
            return false;
        }

        // Token dosyasını yükle
        $accessToken = json_decode(file_get_contents($this->tokenPath), true);

        // Token dosyası bozuksa veya geçersizse oturum geçersizdir
        if (!isset($accessToken['access_token'])) {
            return false;
        }

        // Token'ı Google Client'e yükle ve süresini kontrol et
        $this->client->setAccessToken($accessToken);
        return !$this->client->isAccessTokenExpired();
    }
    
    public function getClient()
    {
        return $this->client;
    }

}
