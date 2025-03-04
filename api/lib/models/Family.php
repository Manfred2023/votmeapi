<?php

use RedBeanPHP\OODBBean;

class Family extends DBA
{
    protected const   TABLE = 'family';
    private ?int $id;
    private ?int $token;
    private string $family;

    /**
     * @param int|null $id
     * @param int|null $token
     * @param string $family
     */
    public function __construct(?int $id, ?int $token, string $family)
    {
        $this->id = $id;
        $this->token = $token;
        $this->family = $family;
    }

    static public function _get(int $criteria, $value): ?Family
    {
        return match (true) {
            $criteria == Criteria::ID && (int)$value > 0 => self::_toObject(parent::_getOne(self::TABLE, [parent::ID => (int)$value])),
            $criteria == Criteria::TOKEN && !QString::_isNull($value) => self::_toObject(parent::_getOne(self::TABLE, [parent::TOKEN => trim($value)])),
            default => null,
        };
    }

    static protected function _toObject(?OODBBean $bean): ?Family
    {
        if (!$bean instanceof OODBBean)
            return null;

        if (!empty($vars = $bean->export())) {
            return new Family(
                $vars[self::ID],
                $vars[TOKEN],
                $vars[FAMILY],
            );
        }
        return null;
    }

    /**
     * @return array|null
     */

    static public function _list(): ?array
    {
        if (!empty($beans = parent::_getAll(self::TABLE, []))) {
            foreach ($beans as $bean)
                if (($item = self::_toObject($bean)) instanceof self)

                    $action[] = $item->toArray();

        }
        return $action ?? null;
    }

    /**
     * @return array
     */


    public function toArray(): array
    {
        return [
            TOKEN => $this->token,
            FAMILY => $this->family,
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

    public function save()
    {
        try {
            $this->validate();
            return self::_toBean($this);
        } catch (Exception $e) {
            Reply::_error($e->getMessage());
        }
    }

    /**
     * @return void
     * @throws Exception
     */
    public function validate(): void
    {
        $errors = [];

        if (empty($this->family)) $errors[] = "family is required!";

        if (!empty($errors)) {
            throw new Exception(implode(" ", $errors));
        }
    }

    static protected function _toBean(Family $family): ?Family
    {

        $bean = ($update = (int)$family->getId() > 0)
            ? R::loadForUpdate(self::TABLE, $family->getId())
            : R::dispense(self::TABLE);

        if (!$bean instanceof OODBBean)
            return null;
        if (!$update) {
            $bean->{self::TOKEN} = (int)self::shortToken(self::TABLE);
        }
        $bean->{FAMILY} = QString::_set($family->getFamily());


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

    public function getFamily(): string
    {
        return $this->family;
    }

    public function setFamily(string $family): void
    {
        $this->family = $family;
    }

}