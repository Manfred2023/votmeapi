<?php

use RedBeanPHP\OODBBean;

class Structure extends DBA
{
    protected const   TABLE = 'structure';
    private ?int $id;
    private ?int $token;
    private string $name;
    private string $mobile;
    private City $city;
    private ?Contact $manager;

    /**
     * @param int|null $id
     * @param int|null $token
     * @param string $name
     * @param string $mobile
     * @param City $city
     * @param Contact|null $manager
     */
    public function __construct(?int $id, ?int $token, string $name, string $mobile, City $city, ?Contact $manager)
    {
        $this->id = $id;
        $this->token = $token;
        $this->name = $name;
        $this->mobile = $mobile;
        $this->city = $city;
        $this->manager = $manager;
    }

    static public function _list(): ?array
    {
        if (!empty($beans = parent::_getAll(self::TABLE, []))) {
            foreach ($beans as $bean)
                if (($item = self::_toObject($bean)) instanceof self)
                    $contacts[] = $item->toArray();
        }
        return $contacts ?? null;
    }

    static protected function _toObject(?OODBBean $bean): ?Structure
    {
        if (!$bean instanceof OODBBean || $bean->isEmpty())
            return null;

        $vars = $bean->export();

        return new Structure(
            $vars[self::ID],
            $vars[self::TOKEN],
            $vars[NAME],
            $vars[MBLE],
            City::_get(Criteria::ID, $vars[CITY]),
            Contact::_get(Criteria::ID, $vars[MANAGR]),
        );
    }

    static public function _get(int $criteria, $value): ?Structure
    {
        return match (true) {
            $criteria == Criteria::ID && (int)$value > 0 => self::_toObject(parent::_getOne(self::TABLE, [parent::ID => (int)$value])),
            $criteria == Criteria::TOKEN && !QString::_isNull($value) => self::_toObject(parent::_getOne(self::TABLE, [parent::TOKEN => trim($value)])),
            $criteria == Criteria::MOBILE && !QString::_isNull($value) => self::_toObject(parent::_getOne(self::TABLE, [Criteria::MOBILE => trim($value)])),
            default => null,
        };
    }

    public function toArray(): ?array
    {
        return [
            TOKEN => ($this->token),
            NAME => QString::_get($this->name),
            MBLE => ($this->mobile),
            MANAGR => $this->manager instanceof Contact ? $this->manager->toArray() : null,
            CITY => $this->city instanceof City ? $this->city->toArray() : null,
        ];
    }

    public function save(): ?Structure
    {
        if ($this->isOK())
            return self::_toBean($this);
        return null;
    }

    private function isOK(): bool
    {
        return true;
    }

    static protected function _toBean(?Structure $structure): ?Structure
    {
        if (!$structure->getManager() instanceof Contact)
            return null;

        if (!$structure->getCity() instanceof City)
            return null;


        $bean = ($update = (int)$structure->getId() > 0)
            ? R::loadForUpdate(self::TABLE, $structure->getId())
            : R::dispense(self::TABLE);


        if (!$bean instanceof OODBBean)
            return null;

        if (!$update) {
            $bean->{self::TOKEN} = (int)self::shortToken(self::TABLE);
        }
        $bean->{NAME} = QString::_set(QString::_latin($structure->getName()));
        $bean->{MBLE} = QString::_set(QString::_latin($structure->getMobile()));
        $bean->{CITY} = (int)$structure->getCity()->getId();
        $bean->{MANAGR} = QString::_set(QString::_latin($structure->getManager()->getId()));


        if (($lastID = (int)R::store($bean)) > 0)
            return self::_toObject(R::load(self::TABLE, $lastID));

        return null;
    }

    public function getManager(): ?Contact
    {
        return $this->manager;
    }

    public function setManager(?Contact $manager): void
    {
        $this->manager = $manager;
    }

    public function getCity(): City
    {
        return $this->city;
    }

    public function setCity(City $city): void
    {
        $this->city = $city;
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
        // Nom de la table
        $table = self::TABLE;

        $lastToken = R::getCell("SELECT MAX(token) FROM {$table}");

        $nextToken = ($lastToken) ? intval($lastToken) + 1 : 100001;

        return $nextToken;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getMobile(): string
    {
        return $this->mobile;
    }

    public function setMobile(string $mobile): void
    {
        $this->mobile = $mobile;
    }

    public function getToken(): ?int
    {
        return $this->token;
    }

    public function setToken(?int $token): void
    {
        $this->token = $token;
    }


}