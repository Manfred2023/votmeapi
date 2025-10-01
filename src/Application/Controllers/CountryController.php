<?php
/*
 *   VotMe Api
 *   Copyright (c) 2025 FredCode Sarl. All rights reserved.
 *
 *   Created by: Manfred MOUKATE
 *   Contact: hello@votme.cm +237 699 888 709
 *   First created: 01/10/2025, 19:02
 *   Last modified: 01/10/2025, 19:02
 *
 */
class CountryController
{
    private CountryService $countryService;

    public function __construct(CountryService $countryService)
    {
        $this->countryService = $countryService;
    }

    /**
     * @return void
     */
    public function index(): void
    {
        try {
            $page = (int) ($_GET['page'] ?? 1);
            $limit = (int) ($_GET['limit'] ?? 10);

            $result = $this->countryService->getAllCountries($page, $limit);

            JsonResponse::success($result, 'Countries retrieved successfully');

        } catch (Exception $e) {
            JsonResponse::error('Failed to retrieve countries', 500);
        }
    }


    public function show(string $guid): void
    {
        try {
            $country = $this->countryService->getCountryByGuid($guid);

            JsonResponse::success($country->toArray(), 'Country retrieved successfully');

        } catch (NotFoundException $e) {
            JsonResponse::error($e->getMessage(), 404);
        } catch (Exception $e) {
            JsonResponse::error('Failed to retrieve country', 500);
        }
    }

    public function store(): void
    {
        try {
            $data = json_decode(file_get_contents('php://input'), true);

            Validator::validate($data, [
                'name' => 'required|min:2',
                'code' => 'required|min:2|max:3',
                'continent' => 'required',
                'population' => 'required'
            ]);

            $country = $this->countryService->createCountry($data);

            JsonResponse::success($country->toArray(), 'Country created successfully', 201);

        } catch (ValidationException $e) {
            JsonResponse::error('Validation failed', 422, json_decode($e->getMessage(), true));
        } catch (Exception $e) {
            JsonResponse::error($e->getMessage(), 400);
        }
    }

    public function update(string $guid): void
    {
        try {
            $data = json_decode(file_get_contents('php://input'), true);

            $country = $this->countryService->updateCountry($guid, $data);

            JsonResponse::success($country->toArray(), 'Country updated successfully');

        } catch (NotFoundException $e) {
            JsonResponse::error($e->getMessage(), 404);
        } catch (Exception $e) {
            JsonResponse::error('Failed to update country', 400);
        }
    }

    public function destroy(string $guid): void
    {
        try {
            $this->countryService->deleteCountry($guid);

            JsonResponse::success(null, 'Country deleted successfully');

        } catch (NotFoundException $e) {
            JsonResponse::error($e->getMessage(), 404);
        } catch (Exception $e) {
            JsonResponse::error('Failed to delete country', 400);
        }
    }
}

