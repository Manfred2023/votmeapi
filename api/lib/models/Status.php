<?php

use RedBeanPHP\OODBBean;

class Status extends DBA
{
    protected const   TABLE = 'status';
    private ?int $id;
    private ?int $token;
    private ?int $position;
    private string $label;
    private string $reference;
    private string $description;
    private Family $family;

    /**
     * @param int|null $id
     * @param int|null $token
     * @param int|null $position
     * @param string $label
     * @param string $reference
     * @param string $description
     * @param Family $family
     */
    public function __construct(?int $id, ?int $token, ?int $position, string $label, string $reference, string $description, Family $family)
    {
        $this->id = $id;
        $this->token = $token;
        $this->position = $position;
        $this->label = $label;
        $this->reference = $reference;
        $this->description = $description;
        $this->family = $family;
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

    static protected function _toObject(?OODBBean $bean): ?Status
    {
        if (!$bean instanceof OODBBean || $bean->isEmpty())
            return null;

        $vars = $bean->export();

        return new Status(
            $vars[self::ID],
            $vars[self::TOKEN],
            $vars[POSITION],
            $vars[LABEL],
            $vars[REFERENCE],
            $vars[DSCRPTN],
            Family::_get(Criteria::ID, $vars[FAMILY])

        );
    }

    static public function _get(int $criteria, $value): ?Status
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
            POSITION => $this->position,
            LABEL => QString::_get($this->label),
            REFERENCE => ($this->reference),
            DSCRPTN => ($this->description),
            FAMILY => $this->family instanceof Family ? $this->family->toArray() : null
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

    public function save(): Status
    {

        return self::_toBean($this);
    }

    static protected function _toBean(?Status $status): ?Status
    {
        if (!$status->getFamily() instanceof Family)
            return null;


        $bean = ($update = (int)$status->getId() > 0)
            ? R::loadForUpdate(self::TABLE, $status->getId())
            : R::dispense(self::TABLE);


        if (!$bean instanceof OODBBean)
            return null;

        if (!$update) {
            $bean->{self::TOKEN} = (int)self::shortToken(self::TABLE);
        }
        $bean->{POSITION} = (int)($status->getPosition());
        $bean->{LABEL} = QString::_set(QString::_latin($status->getLabel()));
        $bean->{REFERENCE} = ($status->getReference());
        $bean->{DSCRPTN} = $status->getDescription();
        $bean->{FAMILY} = (int)$status->getFamily()->getId();


        if (($lastID = (int)R::store($bean)) > 0)
            return self::_toObject(R::load(self::TABLE, $lastID));

        return null;
    }

    public function getFamily(): Family
    {
        return $this->family;
    }

    public function setFamily(Family $family): void
    {
        $this->family = $family;
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

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function setPosition(?int $position): void
    {
        $this->position = $position;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function setLabel(string $label): void
    {
        $this->label = $label;
    }

    public function getReference(): string
    {
        return $this->reference;
    }

    public function setReference(string $reference): void
    {
        $this->reference = $reference;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }


}