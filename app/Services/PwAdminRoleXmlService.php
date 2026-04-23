<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Fetches the same character XML that pwAdmin {@code rolexml.jsp} shows
 * ({@code XmlRole.toXMLByteArray} / {@code XmlRole.getRoleFromDB}) via a
 * local Tomcat JSP (api_rolexml_xml.jsp). PHP cannot generate this XML bit-identically
 * because it is produced by the PW Java API.
 */
class PwAdminRoleXmlService
{
    public function __construct(
        private ?string $baseUrl = null,
        private ?string $apiToken = null,
    ) {
        $this->baseUrl = $baseUrl ?? rtrim(config('pw-api.pwadmin_url', 'http://127.0.0.1:8080/pwAdmin/'), '/');
        $this->apiToken = $apiToken ?? (string) config('pw-api.pwadmin_api_token', 'pw_panel_sync_2026');
    }

    /**
     * @return array{0: string|null, 1: string|null} [xml, errorMessage]
     */
    public function fetchRoleXmlWithError(int $roleId): array
    {
        $url = $this->baseUrl . '/api_rolexml_xml.jsp?token=' . urlencode($this->apiToken) . '&ident=' . $roleId;

        try {
            $res = Http::timeout(45)->get($url);
            if ($res->status() === 403) {
                return [null, 'Tomcat menolak (403). Pastikan JSP di-deploy, token cocok, dan request dari host yang diizinkan (biasanya localhost).'];
            }
            if ($res->status() === 400) {
                return [null, 'Request tidak valid (400).'];
            }
            if (!$res->ok()) {
                return [null, 'HTTP ' . $res->status() . ' saat memanggil api_rolexml_xml.jsp.'];
            }
            $body = $res->body();
            if ($body === '' || $body === null) {
                return [null, 'Jawaban kosong dari Tomcat.'];
            }
            if (str_starts_with(ltrim($body), '<!--')) {
                return [null, 'Server mengembalikan error marker. Cek id role dan gamedbd.'];
            }
            return [trim($body), null];
        } catch (\Throwable $e) {
            Log::warning('PwAdminRoleXmlService: ' . $e->getMessage());
            return [null, 'Gagal hubungi Tomcat: ' . $e->getMessage()];
        }
    }

    /**
     * @return array{0: bool, 1: string|null} [ok, errorMessage]
     */
    public function saveRoleXmlWithError(int $roleId, string $xml): array
    {
        $url = $this->baseUrl . '/api_rolexml_save.jsp?token=' . urlencode($this->apiToken);

        try {
            $res = Http::timeout(120)->asForm()->post($url, [
                'ident' => (string) $roleId,
                'xml'   => $xml,
            ]);

            if ($res->status() === 403) {
                return [false, 'Tomcat menolak (403). Pastikan api_rolexml_save.jsp di-deploy, token benar, dan host diizinkan.'];
            }
            if (! $res->ok()) {
                $msg = trim($res->body()) ?: 'HTTP ' . $res->status();

                return [false, $msg];
            }
            if (trim($res->body()) === 'OK') {
                return [true, null];
            }

            return [false, trim($res->body()) ?: 'Respons Tomcat tidak dikenal.'];
        } catch (\Throwable $e) {
            Log::warning('PwAdminRoleXmlService::save: ' . $e->getMessage());

            return [false, 'Gagal hubungi Tomcat: ' . $e->getMessage()];
        }
    }
}
