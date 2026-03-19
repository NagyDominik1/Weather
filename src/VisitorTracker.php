<?php

use Detection\MobileDetect;

class VisitorTracker
{
    private $db;
    private $detect;

    public function __construct($db)
    {
        $this->db = $db;
        $this->detect = new MobileDetect();
    }

    /**
     * Látogató tracking - IP + eszköz információk mentése
     */
    public function trackVisitor()
    {
        try {
            // IP cím lekérése
            $ip = $this->getClientIP();

            // Ellenőrizzük hogy az elmúlt 1 órában volt-e már tracking
            $stmt = $this->db->prepare("
                SELECT id FROM visitor_tracking 
                WHERE ip_address = ? 
                AND visited_at > DATE_SUB(NOW(), INTERVAL 1 HOUR)
                LIMIT 1
            ");
            $stmt->execute([$ip]);

            if ($stmt->fetch()) {
                // Már volt tracking az elmúlt 1 órában, kihagyjuk
                return false;
            }

            // IP információk lekérése API-ból
            $ipData = $this->getIPInfo($ip);

            // Eszköz információk
            $deviceType = $this->getDeviceType();
            $browser = $this->getBrowser();
            $os = $this->getOS();

            // Adatbázisba mentés
            $stmt = $this->db->prepare("
                INSERT INTO visitor_tracking 
                (ip_address, user_agent, device_type, browser, os, is_mobile, 
                 country, city, timezone, region, latitude, longitude) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");

            $stmt->execute([
                $ip,
                $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown',
                $deviceType,
                $browser,
                $os,
                $this->detect->isMobile() ? 1 : 0,
                $ipData['country'] ?? 'Unknown',
                $ipData['city'] ?? 'Unknown',
                $ipData['timezone'] ?? 'Unknown',
                $ipData['region'] ?? 'Unknown',
                $ipData['latitude'] ?? null,
                $ipData['longitude'] ?? null
            ]);

            return true;

        } catch (Exception $e) {
            error_log("Visitor tracking hiba: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Valódi IP cím lekérése (proxy-k figyelembevételével)
     */
    private function getClientIP()
    {
        $ipHeaders = [
            'HTTP_CF_CONNECTING_IP', // Cloudflare
            'HTTP_X_FORWARDED_FOR',
            'HTTP_X_REAL_IP',
            'REMOTE_ADDR'
        ];

        foreach ($ipHeaders as $header) {
            if (!empty($_SERVER[$header])) {
                $ip = $_SERVER[$header];

                // Ha vesszővel elválasztott lista (proxy chain)
                if (strpos($ip, ',') !== false) {
                    $ips = explode(',', $ip);
                    $ip = trim($ips[0]);
                }

                // Validálás
                if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
                    return $ip;
                }
            }
        }

        return $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
    }

    /**
     * IP információk lekérése külső API-ból
     */
    private function getIPInfo($ip)
    {
        // Localhost vagy privát IP esetén nem kérünk le adatot
        if ($ip === '127.0.0.1' || $ip === '::1' || strpos($ip, '192.168.') === 0) {
            return [
                'country' => 'Localhost',
                'city' => 'Local',
                'timezone' => 'UTC',
                'region' => 'Local',
                'latitude' => null,
                'longitude' => null
            ];
        }

        try {
            // ipapi.co API használata (ingyenes, nincs API key)
            $url = "https://ipapi.co/{$ip}/json/";

            $context = stream_context_create([
                'http' => [
                    'timeout' => 3,
                    'user_agent' => 'WeatherBase/1.0'
                ]
            ]);

            $response = @file_get_contents($url, false, $context);

            if ($response === false) {
                throw new Exception("API hívás sikertelen");
            }

            $data = json_decode($response, true);

            return [
                'country' => $data['country_name'] ?? 'Unknown',
                'city' => $data['city'] ?? 'Unknown',
                'timezone' => $data['timezone'] ?? 'Unknown',
                'region' => $data['region'] ?? 'Unknown',
                'latitude' => $data['latitude'] ?? null,
                'longitude' => $data['longitude'] ?? null
            ];

        } catch (Exception $e) {
            error_log("IP API hiba: " . $e->getMessage());
            return [
                'country' => 'Unknown',
                'city' => 'Unknown',
                'timezone' => 'Unknown',
                'region' => 'Unknown',
                'latitude' => null,
                'longitude' => null
            ];
        }
    }

    /**
     * Eszköz típus meghatározása
     */
    private function getDeviceType()
    {
        if ($this->detect->isTablet()) {
            return 'Tablet';
        } elseif ($this->detect->isMobile()) {
            return 'Mobile';
        } else {
            return 'Desktop';
        }
    }

    /**
     * Böngésző meghatározása
     */
    private function getBrowser()
    {
        $browsers = [
            'Chrome', 'Firefox', 'Safari', 'Edge',
            'Opera', 'IE', 'UCBrowser', 'Samsung'
        ];

        foreach ($browsers as $browser) {
            if ($this->detect->version($browser)) {
                return $browser . ' ' . $this->detect->version($browser);
            }
        }

        return 'Other';
    }

    /**
     * Operációs rendszer meghatározása
     */
    private function getOS()
    {
        $osList = [
            'iOS', 'AndroidOS', 'Windows', 'Mac', 'Linux', 'Ubuntu'
        ];

        foreach ($osList as $os) {
            if ($this->detect->version($os)) {
                return $os . ' ' . $this->detect->version($os);
            }
        }

        return 'Other';
    }

    /**
     * Összes látogató lekérése (admin felülethez)
     */
    public function getAllVisitors($limit = 100)
    {
        // Biztonsági cast integer-re
        $limit = (int)$limit;

        // LIMIT-et direkt beillesztjük (biztonságos, mert integer)
        $stmt = $this->db->query("
        SELECT * FROM visitor_tracking 
        ORDER BY visited_at DESC 
        LIMIT {$limit}
    ");

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Statisztikák
     */
    public function getStats()
    {
        $stats = [];

        // Összes látogatás
        $stmt = $this->db->query("SELECT COUNT(*) as total FROM visitor_tracking");
        $stats['total_visits'] = $stmt->fetchColumn();

        // Mobil vs Desktop
        $stmt = $this->db->query("
            SELECT 
                SUM(is_mobile = 1) as mobile,
                SUM(is_mobile = 0) as desktop
            FROM visitor_tracking
        ");
        $stats['device_breakdown'] = $stmt->fetch(PDO::FETCH_ASSOC);

        // Top 5 ország
        $stmt = $this->db->query("
            SELECT country, COUNT(*) as count 
            FROM visitor_tracking 
            WHERE country != 'Unknown'
            GROUP BY country 
            ORDER BY count DESC 
            LIMIT 5
        ");
        $stats['top_countries'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $stats;
    }
}