<?php
/**
 * Simple wrapper to query RUC information using a public API.
 * Uses https://api.apis.net.pe/v1/ruc?numero={RUC}
 */
class SunatRuc
{
    public static function consultar(string $ruc): array
    {
        $ruc = trim($ruc);
        if ($ruc === '' || !ctype_digit($ruc)) return [];
        $url = 'https://api.apis.net.pe/v1/ruc?numero=' . urlencode($ruc);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Accept: application/json'
        ]);

        $resp = curl_exec($ch);
        $http = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $err = curl_error($ch);
        curl_close($ch);

        if ($resp === false || $http !== 200) {
            return [
                'error' => true,
                'message' => 'No se pudo consultar el servicio externo'.($err?': '.$err:'').' (HTTP '.$http.')'
            ];
        }

        $data = json_decode($resp, true);
        if ($data === null) {
            return [
                'error' => true,
                'message' => 'Respuesta inválida del servicio RUC'
            ];
        }
        return $data;
    }
}

?>
