<?php

use RedBeanPHP\OODBBean;

class Profile extends DBA
{
    protected const   TABLE = 'profile';
    private ?int $id;
    private ?int $token;
    private string $label;
    private ?string $description;

    /**
     * @param int|null $id
     * @param int|null $token
     * @param string $label
     * @param string|null $description
     */
    public function __construct(?int $id, ?int $token, string $label, ?string $description)
    {
        $this->id = $id;
        $this->token = $token;
        $this->label = $label;
        $this->description = $description;
    }

    static public function _list(): ?array
    {
        if (!empty($beans = parent::_getAll(self::TABLE, []))) {
            foreach ($beans as $bean)
                if (($item = self::_toObject($bean)) instanceof self)
                    if ($item->getLabel() != "root") {
                        $action[] = $item->toArray();
                    }
        }
        return $action ?? null;
    }

    public function toArray(): ?array
    {
        return [
            TOKEN => ($this->token),
            LABEL => $this->getLabel(),
            DSCRPTN => $this->getDescription(),
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

    public function save(): ?Profile
    {
        if ($this->isOK())
            return self::_toBean($this);
        return null;
    }

    private function isOK(): bool
    {
        if (QString::_isNull($this->label)) {
            throw new Exception("label is required!");
        }
        return true;
    }

    static protected function _toBean(Profile $profile): ?Profile
    {
        if (!$profile instanceof Profile)
            return null;

        $bean = ($update = (int)$profile->getId() > 0)
            ? R::loadForUpdate(self::TABLE, $profile->getId())
            : R::dispense(self::TABLE);

        if (!$bean instanceof OODBBean)
            return null;
        if (!$update) {
            $bean->{self::TOKEN} = (int)self::shortToken(self::TABLE);
        }
        $bean->{LABEL} = QString::_set($profile->getLabel());
        $bean->{DSCRPTN} = QString::_set($profile->getDescription());


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
        // Nom de la table
        $table = self::TABLE;

        $lastToken = R::getCell("SELECT MAX(token) FROM {$table}");

        $nextToken = ($lastToken) ? intval($lastToken) + 1 : 100001;

        return $nextToken;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function setLabel(string $label): void
    {
        $this->label = $label;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    static protected function _toObject(?OODBBean $bean): ?Profile
    {
        if (!$bean instanceof OODBBean)
            return null;

        if (!empty($vars = $bean->export())) {
            return new Profile(
                $vars[self::ID],
                $vars[self::TOKEN],
                QString::_get($vars[LABEL]),
                QString::_get($vars[DSCRPTN]),
            );
        }
        return null;
    }

    static public function _get(int $criteria, $value): ?Profile
    {
        return match (true) {
            $criteria == Criteria::ID && (int)$value > 0 => self::_toObject(parent::_getOne(self::TABLE, [parent::ID => (int)$value])),
            $criteria == Criteria::TOKEN && !QString::_isNull($value) => self::_toObject(parent::_getOne(self::TABLE, [parent::TOKEN => trim($value)])),
            default => null,
        };
    }


}