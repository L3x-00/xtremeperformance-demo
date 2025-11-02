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
            // Mensajes más amables para el usuario final
            if (in_array($http, [404, 422])) {
                return [
                    'error' => true,
                    'message' => 'El RUC no existe o es inválido. Verifica que tenga 11 dígitos.'
                ];
            }
            return [
                'error' => true,
                'message' => 'No se pudo consultar el servicio de RUC en este momento. Intenta nuevamente más tarde.',
                'debug'   => ($err ? ('cURL: '.$err) : null),
                'status'  => $http
            ];
        }

        $data = json_decode($resp, true);
        if ($data === null) {
            return [
                'error' => true,
                'message' => 'No pudimos procesar la respuesta del servicio RUC. Intenta nuevamente.'
            ];
        }
        return $data;
    }
}

?>
