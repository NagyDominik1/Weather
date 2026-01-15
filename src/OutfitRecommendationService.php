<?php
class OutfitRecommendationService
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    /**
     * Aktuális hőmérséklet alapján ad outfit javaslatot
     */
    public function getRecommendationByTemperature(float $temp): string
    {
        $stmt = $this->db->prepare("
            SELECT recommendation 
            FROM outfit_recommendations 
            WHERE :temp BETWEEN temp_min AND temp_max
            LIMIT 1
        ");
        $stmt->execute(['temp' => $temp]);
        $rec = $stmt->fetchColumn();

        return $rec ?? 'Nincs ajánlás erre az időjárásra.';
    }
}
