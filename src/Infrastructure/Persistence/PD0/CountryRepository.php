<?php
/*
 *   VotMe Api
 *   Copyright (c) 2025 FredCode Sarl. All rights reserved.
 *
 *   Created by: Manfred MOUKATE
 *   Contact: hello@votme.cm +237 699 888 709
 *   First created: 01/10/2025, 18:18
 *   Last modified: 01/10/2025, 18:18
 *
 */
class CountryRepository implements CountryRepositoryInterface
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * @param Country $country
     * @return Country
     */
    public function save(Country $country): Country
    {
        $stmt = $this->pdo->prepare(
            "INSERT INTO countries (guid, name, alpha2Code, alpha3Code, dialCode, isActive, createdAt,updatedAt) 
             VALUES (:guid, :name, :alpha2Code, :alpha3Code, :dialCode, :isActive, :createdAt, :updatedAt)"
        );

        $data = $country->toArray();
        $stmt->execute([':guid' => $data['guid'], ':name' => $data['name'], ':alpha2Code' => $data['alpha2Code'], ':alpha3Code' => $data['alpha3Code'], ':dialCode' => $data['dialCode'], ':isActive' => $data['isActive'] ? 1 : 0, ':createdAt' => $data['createdAt'], ':updatedAt' => $data['updatedAt']]);

        $id = (int) $this->pdo->lastInsertId();

        return new Country(
            $id,
            $data['guid'],
            $data['name'],
            $data['alpha2Code'],
            $data['alpha3Code'],
            $data['dialCode'],
            $data['isActive'],
            $data['createdAt'] ,
            $data['updatedAt']
        );
    }

    /**
     * @param string $alpha3
     * @return Country|null
     */
    public function findByAlpha3(string $alpha3): ?Country
    {
        $stmt = $this->pdo->prepare("SELECT * FROM countries WHERE alpha3 = :alpha3");
        $stmt->execute([':alpha3' => $alpha3]);
        $data = $stmt->fetch();

        return $data ? $this->hydrate($data) : null;
    }

    /**
     * @param int $page
     * @param int $limit
     * @return array
     */
    public function findAll(int $page = 1, int $limit = 10): array
    {
        $offset = ($page - 1) * $limit;

        $stmt = $this->pdo->prepare(
            "SELECT * FROM countries ORDER BY name ASC LIMIT :limit OFFSET :offset"
        );
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return array_map(fn($data) => $this->hydrate($data), $stmt->fetchAll());
    }

    /**
     * @param string $guid
     * @return Country|null
     */
    public function findByGuid(string $guid): ?Country
    {
        $stmt = $this->pdo->prepare("SELECT * FROM countries WHERE guid = :guid");
        $stmt->execute([':guid' => strtoupper($guid)]);
        $data = $stmt->fetch();

        return $data ? $this->hydrate($data) : null;
    }

    /**
     * @param Country $country
     * @return bool
     */
    public function update(Country $country): bool
    {
        $stmt = $this->pdo->prepare(
            "UPDATE countries 
             SET guid = :guid, name = :name, alpha2Code = :alpha2Code, 
                 alpha3Code = :alpha3Code, dialCode = :dialCode, 
                 isActive = :isActive, createdAt = :createdAt, updatedAt = :updatedAt
             WHERE id = :id"
        );

        $data = $country->toArray();
        return $stmt->execute([
            ':id' => $data['id'],
            ':guid' => $data['guid'],
            ':name' => $data['name'],
            ':alpha2Code' => $data['alpha2Code'],
            ':alpha3Code' => $data['alpha3Code'],
            ':dialCode' => $data['dialCode'],
            ':isActive' => $data['isActive'] ? 1 : 0,
            ':updatedAt' => date('Y-m-d H:i:s')
        ]);
    }

    /**
     * @param int $id
     * @return bool
     */
    public function delete(string $guid): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM countries WHERE guid = :guid");
        return $stmt->execute([':guid' => $guid]);
    }

    /**
     * @return int
     */
    public function count(): int
    {
        $stmt = $this->pdo->query("SELECT COUNT(*) FROM countries");
        return (int) $stmt->fetchColumn();
    }

    /**
     * @param array $data
     * @return Country
     */
    private function hydrate(array $data): Country
    {
        return new Country(
            (int) $data['id'],
            $data['guid'],
            $data['name'],
            $data['alpha2Code'],
          $data['alpha3Code'],
            $data['dialCode'],
            (boolean) $data['isActive'],
            $data['createdAt'],
            $data['updatedAt']
        );
    }


}


