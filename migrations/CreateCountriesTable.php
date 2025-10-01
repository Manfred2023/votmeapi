<?php
/*
 *   VotMe Api
 *   Copyright (c) 2025 FredCode Sarl. All rights reserved.
 *
 *   Created by: Manfred MOUKATE
 *   Contact: hello@votme.cm +237 699 888 709
 *   First created: 01/10/2025, 19:17
 *   Last modified: 01/10/2025, 19:17
 *
 */

class CreateCountriesTable
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Exécuter la migration
     */
    public function up(): void
    {
        $sql = "
            CREATE TABLE IF NOT EXISTS countries (
                id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(255) NOT NULL,
                code VARCHAR(3) NOT NULL UNIQUE,
                continent ENUM('Africa', 'Antarctica', 'Asia', 'Europe', 'North America', 'Oceania', 'South America') NOT NULL,
                population BIGINT UNSIGNED NOT NULL DEFAULT 0,
                capital VARCHAR(255) NULL,
                currency VARCHAR(50) NULL,
                languages JSON NULL,
                flag_url VARCHAR(500) NULL,
                is_active BOOLEAN DEFAULT TRUE,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP,
                INDEX idx_code (code),
                INDEX idx_continent (continent),
                INDEX idx_is_active (is_active),
                INDEX idx_name (name)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ";

        $this->pdo->exec($sql);
        echo "  ✓ Table 'countries' créée\n";
    }

    /**
     * Annuler la migration
     */
    public function down(): void
    {
        $sql = "DROP TABLE IF EXISTS countries";
        $this->pdo->exec($sql);
        echo "  ✓ Table 'countries' supprimée\n";
    }
}
