<?php
/**
 * Helper para enviar eventos a Pusher
 */
class PusherHelper
{
    private static $app_id = '2125569'; // Reemplaza con tu app_id de Pusher (número)
    private static $key = '24883b4239d5fad125df'; // Tu key
    private static $secret = '478d0d46be99a655faba'; // Reemplaza con tu secret de Pusher
    private static $cluster = 'mt1'; // Cambiado a estándar

    public static function trigger($channel, $event, $data)
    {
        $url = "https://api.pusherapp.com/apps/" . self::$app_id . "/events";

        $body = json_encode([
            'name' => $event,
            'channels' => [$channel],
            'data' => json_encode($data)
        ]);

        $auth_timestamp = time();
        $auth_version = '1.0';

        $body_md5 = md5($body, true);
        $string_to_sign = "POST\n/apps/" . self::$app_id . "/events\nauth_key=" . self::$key . "&auth_timestamp=" . $auth_timestamp . "&auth_version=" . $auth_version . "&body_md5=" . bin2hex($body_md5);

        $auth_signature = hash_hmac('sha256', $string_to_sign, self::$secret);

        $query = http_build_query([
            'auth_key' => self::$key,
            'auth_timestamp' => $auth_timestamp,
            'auth_version' => $auth_version,
            'body_md5' => bin2hex($body_md5),
            'auth_signature' => $auth_signature
        ]);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url . '?' . $query);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json'
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        error_log("Pusher trigger: URL=$url, Data=" . json_encode($body) . ", Response=$response, Code=$http_code");

        if ($http_code == 200) {
            return true;
        } else {
            error_log("Pusher error: HTTP $http_code, Response: $response");
            return false;
        }
    }
}