<?php 

require_once 'src/Google_Client.php';
require_once 'src/contrib/Google_Oauth2Service.php';

class Google {
    public $client;
    public $oauth;
    public function __construct()
    {
        $this->client = new Google_Client();
        $this->client->setApplicationName(GOOGLE_APP_NAME);
        $this->client->setClientId(GOOGLE_CLIENT_ID);
        $this->client->setClientSecret(GOOGLE_CLIENT_SECRET);
        $this->client->setRedirectUri(GOOGLE_CLIENT_REDIRECT);
        $this->client->setDeveloperKey(GOOGLE_CLIENT_DEV_KEY);
        $this->client->setScopes('https://www.googleapis.com/auth/userinfo.email https://www.googleapis.com/auth/userinfo.profile');
        $this->oauth = new Google_Oauth2Service($this->client);
    }
    public function createUrl()
    {
        $authUrl = $this->client->createAuthUrl();
        return $authUrl;
    }
    public function hasCode()
    {
        $this->client->authenticate();
        $_SESSION['token'] = $this->client->getAccessToken();
        $redirect = $this->client->getRedirectUri();
        return $redirect;
    }
    public function hasToken()
    {
        $this->client->setAccessToken($_SESSION['token']);
        $activities = $this->oauth->userinfo->get();
        $email = filter_var($activities['email'], FILTER_SANITIZE_EMAIL);
        $_SESSION['valid_email'] = $email;
        $_SESSION['test'] = $this->client;
        $_SESSION['activity'] = time();
        return $activities;
    }
    public function getToken()
    {
        return $this->client->getAccessToken();

    }
    public function logoutGoogle()
    {
        unset($_SESSION['token']);
        $this->client->revokeToken();
        return true;
    }

}
