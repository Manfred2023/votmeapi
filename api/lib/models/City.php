<?php

use RedBeanPHP\OODBBean;

class City extends DBA
{
    protected const   TABLE = 'city';
    private ?int $id;
    private ?int $token;
    private string $city;
    private ?Country $country;

    /**
     * @param int|null $id
     * @param int|null $token
     * @param string $city
     * @param Country|null $country
     */
    public function __construct(?int $id, ?int $token, string $city, ?Country $country)
    {
        $this->id = $id;
        $this->token = $token;
        $this->city = $city;
        $this->country = $country;
    }

    static public function _get(int $criteria, $value): ?City
    {
        return match (true) {
            $criteria == Criteria::ID && (int)$value > 0 => self::_toObject(parent::_getOne(self::TABLE, [parent::ID => (int)$value])),
            $criteria == Criteria::TOKEN && !QString::_isNull($value) => self::_toObject(parent::_getOne(self::TABLE, [parent::TOKEN => trim($value)])),
            default => null,
        };
    }

    static protected function _toObject(?OODBBean $bean): ?City
    {
        if (!$bean instanceof OODBBean)
            return null;

        if (!empty($vars = $bean->export())) {
            return new City(
                $vars[self::ID],
                $vars[TOKEN],
                $vars[CITY],
                Country::_get(Criteria::ID, COUNTRY),
            );
        }
        return null;
    }

    static public function _list(): ?array
    {
        if (!empty($beans = parent::_getAll(self::TABLE, []))) {
            foreach ($beans as $bean)
                if (($item = self::_toObject($bean)) instanceof self)

                    $action[] = $item->toArray();

        }
        return $action ?? null;
    }

    public function toArray(): array
    {
        return [
            TOKEN => $this->token,
            CITY => $this->city,
        ];
    }

    public function getToken(): ?int
    {
        return $this->token;
    }

    public function setToken(?int $token): void
    {
        $this->token = $token;
    }

    public function save(): ?City
    {
        try {
            $this->validate();
            return self::_toBean($this);
        } catch (Exception $e) {
            error_log($e->getMessage());
            return null;
        }
    }

    /**
     * @return void
     * @throws Exception
     */
    public function validate(): void
    {
        $errors = [];

        if (empty($this->city)) $errors[] = "city is required!";
        if (empty($this->country)) $errors[] = "country is required!";

        if (!empty($errors)) {
            throw new Exception(implode(" ", $errors));
        }
    }

    static protected function _toBean(City $city): ?City
    {

        $bean = ($update = (int)$city->getId() > 0)
            ? R::loadForUpdate(self::TABLE, $city->getId())
            : R::dispense(self::TABLE);

        if (!$bean instanceof OODBBean)
            return null;
        if (!$update) {
            $bean->{self::TOKEN} = (int)self::shortToken(self::TABLE);
        }
        $bean->{CITY} = QString::_set($city->getCity());
        $bean->{COUNTRY} = QString::_set($city->getCountry()->getId());


        if (($lastCID = (int)R::store($bean)) > 0)
            return self::_toObject(R::load(self::TABLE, $lastCID));

        return null;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    static protected function shortToken(): int
    {
        $table = self::TABLE;

        $lastToken = R::getCell("SELECT MAX(token) FROM {$table}");

        $nextToken = ($lastToken) ? intval($lastToken) + 1 : 100001;

        return $nextToken;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function setCity(string $city): void
    {
        $this->city = $city;
    }

    public function getCountry(): ?Country
    {
        return $this->country;
    }

    public function setCountry(?Country $country): void
    {
        $this->country = $country;
    }

}