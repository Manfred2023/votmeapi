<?php
/*
 *   VotMe Api
 *   Copyright (c) 2025 FredCode Sarl. All rights reserved.
 *
 *   Created by: Manfred MOUKATE
 *   Contact: hello@votme.cm +237 699 888 709
 *   First created: 01/10/2025, 17:55
 *   Last modified: 01/10/2025, 17:55
 *
 */
interface CountryRepositoryInterface
{
    public function save(Country $country): Country;
    public function findByGuid(string $guid): ?Country;
    public function findAll(int $page = 1, int $limit = 10): array;
    public function findByAlpha3(string $alpha3): ?Country;
    public function update(Country $country): bool;
    public function delete(string $guid): bool;
    public function count(): int;
}
