<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

session_start();

$client_id = ' this is rest api key ';
$client_secret = ' this is client secret key ';
$domain = 'http://localhost:8000';
$redirect_uri = $domain . '/api.php?action=redirect';
$token_uri = 'https://kauth.kakao.com/oauth/token';
$api_host = 'https://kapi.kakao.com';

function call($method, $uri, $params = [], $headers = []) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $uri);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
    if ($method === 'POST') {
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, !empty($params) ? http_build_query($params) : '');
    }
    
    if (!empty($headers)) {
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    }
    
    $response = curl_exec($ch);
    curl_close($ch);
    
    return json_decode($response, true);
}

$action = $_GET['action'] ?? '';

switch ($action) {
    case 'authorize':
        $scope = $_GET['scope'] ?? '';
        $scopeParam = $scope ? "&scope=" . $scope : "";
        header("Location: https://kauth.kakao.com/oauth/authorize?client_id={$client_id}&redirect_uri={$redirect_uri}&response_type=code{$scopeParam}");
        exit;

    case 'redirect':
        $params = [
            'grant_type' => 'authorization_code',
            'client_id' => $client_id,
            'redirect_uri' => $redirect_uri,
            'client_secret' => $client_secret,
            'code' => $_GET['code']
        ];
        
        $headers = ['Content-Type: application/x-www-form-urlencoded'];
        $response = call('POST', $token_uri, $params, $headers);
        
        if (isset($response['access_token'])) {
            $_SESSION['key'] = $response['access_token'];
            header("Location: {$domain}/index.html?login=success");
        }
        exit;

    case 'profile':
        $uri = $api_host . "/v2/user/me";
        $headers = [
            'Content-Type: application/x-www-form-urlencoded',
            'Authorization: Bearer ' . $_SESSION['key']
        ];
        $response = call('POST', $uri, [], $headers);
        header('Content-Type: application/json');
        echo json_encode($response);
        exit;

    case 'friends':
        $uri = $api_host . "/v1/api/talk/friends";
        $headers = ['Authorization: Bearer ' . $_SESSION['key']];
        $response = call('GET', $uri, [], $headers);
        header('Content-Type: application/json');
        echo json_encode($response);
        exit;

    case 'message':
        $uri = $api_host . "/v2/api/talk/memo/default/send";
        $template_object = [
            'object_type' => 'text',
            'text' => 'Hello, world!',
            'link' => [
                'web_url' => 'https://developers.kakao.com',
                'mobile_web_url' => 'https://developers.kakao.com'
            ]
        ];
        
        $params = ['template_object' => json_encode($template_object)];
        $headers = [
            'Content-Type: application/x-www-form-urlencoded',
            'Authorization: Bearer ' . $_SESSION['key']
        ];
        
        $response = call('POST', $uri, $params, $headers);
        header('Content-Type: application/json');
        echo json_encode($response);
        exit;

    case 'friend-message':
        $uri = $api_host . "/v1/api/talk/friends/message/default/send";
        $uuid = $_GET['uuid'] ?? '';
        
        $template_object = [
            'object_type' => 'text',
            'text' => 'Hello, world!',
            'link' => [
                'web_url' => 'https://developers.kakao.com',
                'mobile_web_url' => 'https://developers.kakao.com'
            ]
        ];
        
        $params = [
            'receiver_uuids' => "[$uuid]",
            'template_object' => json_encode($template_object)
        ];
        
        $headers = [
            'Content-Type: application/x-www-form-urlencoded',
            'Authorization: Bearer ' . $_SESSION['key']
        ];
        
        $response = call('POST', $uri, $params, $headers);
        header('Content-Type: application/json');
        echo json_encode($response);
        exit;

    case 'logout':
        $uri = $api_host . "/v1/user/logout";
        $headers = ['Authorization: Bearer ' . $_SESSION['key']];
        $response = call('POST', $uri, [], $headers);
        session_destroy();
        header('Content-Type: application/json');
        echo json_encode($response);
        exit;

    case 'unlink':
        $uri = $api_host . "/v1/user/unlink";
        $headers = ['Authorization: Bearer ' . $_SESSION['key']];
        $response = call('POST', $uri, [], $headers);
        session_destroy();
        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
} 