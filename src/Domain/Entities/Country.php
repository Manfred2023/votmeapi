<?php
/*
 *   VotMe Api
 *   Copyright (c) 2025 FredCode Sarl. All rights reserved.
 *
 *   Created by: Manfred MOUKATE
 *   Contact: hello@votme.cm +237 699 888 709
 *   First created: 01/10/2025, 17:47
 *   Last modified: 01/10/2025, 17:47
 *
 */

class Country
{

    private ?int $id;
    private ?int $guid;
    private string $name;
    private string $alpha2Code;
    private string $alpha3Code;
    private string $dialCode;
    private bool $isActive;
    private string $createdAt;
    private ?string $updatedAt;

    /**
     * @param int|null $id
     * @param int|null $guid
     * @param string $name
     * @param string $alpha2Code
     * @param string $alpha3Code
     * @param string $dialCode
     * @param bool $isActive
     * @param string $createdAt
     * @param string|null $updatedAt
     */
    public function __construct(?int $id, ?int $guid, string $name, string $alpha2Code, string $alpha3Code, string $dialCode, bool $isActive = true, string $createdAt, ?string $updatedAt)
    {
        $this->id = $id;
        $this->guid = $guid;
        $this->name = $name;
        $this->alpha2Code = $alpha2Code;
        $this->alpha3Code = $alpha3Code;
        $this->dialCode = $dialCode;
        $this->isActive = $isActive;
        $this->createdAt = $createdAt ?? date('Y-m-d H:i:s');
        $this->updatedAt = $updatedAt;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getGuid(): ?int
    {
        return $this->guid;
    }

    public function setGuid(?int $guid): void
    {
        $this->guid = $guid;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getAlpha2Code(): string
    {
        return $this->alpha2Code;
    }

    public function setAlpha2Code(string $alpha2Code): void
    {
        $this->alpha2Code = $alpha2Code;
    }

    public function getAlpha3Code(): string
    {
        return $this->alpha3Code;
    }

    public function setAlpha3Code(string $alpha3Code): void
    {
        $this->alpha3Code = $alpha3Code;
    }

    public function getDialCode(): string
    {
        return $this->dialCode;
    }

    public function setDialCode(string $dialCode): void
    {
        $this->dialCode = $dialCode;
    }

    public function isActive(): bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): void
    {
        $this->isActive = $isActive;
    }

    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    public function setCreatedAt(string $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getUpdatedAt(): ?string
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?string $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }
    public function toArray(): array
    {
        return [
            'guid' => $this->guid,
            'name' => $this->name,
            'dialCode' => $this->dialCode,
            'alpha2Code' => $this->alpha2Code,
            'alpha3Code' => $this->alpha3Code,
            'isActive' => $this->isActive,
            'createdAt' => $this->createdAt,
            'updatedAt' => $this->updatedAt
        ];
    }


}