<?php
/*
 *   VotMe Api
 *   Copyright (c) 2025 FredCode Sarl. All rights reserved.
 *
 *   Created by: Manfred MOUKATE
 *   Contact: hello@votme.cm +237 699 888 709
 *   First created: 01/10/2025, 17:58
 *   Last modified: 01/10/2025, 17:58
 *
 */
class CountryService
{
    private CountryRepositoryInterface $countryRepository;

    public function __construct(CountryRepositoryInterface $countryRepository)
    {
        $this->countryRepository = $countryRepository;
    }

    /**
     * @param array $data
     * @return Country
     */
    public function createCountry(array $data): Country
    {
        $country = new Country(
            null,
            null,
            $data['name'],
            $data['alpha2Code'],
            $data['alpha3Code'],
            $data['dialCode'],
            true,
            $data['createdAt'] ,
            $data['updatedAt']
        );

        return $this->countryRepository->save($country);
    }

    /**
     * @param int $guid
     * @return Country
     * @throws NotFoundException
     */
    public function getCountryByGuid(int $guid): Country
    {
        $country = $this->countryRepository->findByGuid($guid);

        if (!$country) {
            throw new NotFoundException("Country with ID $guid not found");
        }

        return $country;
    }

    /**
     * @param int $page
     * @param int $limit
     * @return array
     */
    public function getAllCountries(int $page = 1, int $limit = 10): array
    {
        $countries = $this->countryRepository->findAll($page, $limit);
        $total = $this->countryRepository->count();

        return [
            'countries' => array_map(fn($c) => $c->toArray(), $countries),
            'pagination' => [
                'current_page' => $page,
                'per_page' => $limit,
                'total' => $total,
                'total_pages' => ceil($total / $limit)
            ]
        ];
    }

    /**
     * @param int $guid
     * @return Country
     * @throws NotFoundException
     */
    public function updateCountry(int $guid ): Country
    {
        $country = $this->getCountryByGuid($guid);
        $this->countryRepository->update($country);

        return $country;
    }

    /**
     * @param int $guid
     * @return void
     * @throws NotFoundException
     */
    public function deleteCountry(int $guid): void
    {
        $country = $this->getCountryByGuid($guid);
        $this->countryRepository->delete($guid);
    }
}

