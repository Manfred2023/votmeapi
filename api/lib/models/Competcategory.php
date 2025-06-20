<?php

use RedBeanPHP\OODBBean;

class Competcategory extends DBA
{
    protected const   TABLE = 'competcategory';
    private ?int $id;
    private ?int $token;
    private ?Competition $competition;
    private ?Category $category;

    /**
     * @param int|null $id
     * @param int|null $token
     * @param Competition|null $competition
     * @param Category|null $category
     */
    public function __construct(?int $id, ?int $token, ?Competition $competition, ?Category $category)
    {
        $this->id = $id;
        $this->token = $token;
        $this->competition = $competition;
        $this->category = $category;
    }

    static public function _get(int $criteria, $value): ?Competcategory
    {
        return match (true) {
            $criteria == Criteria::ID && (int)$value > 0 => self::_toObject(parent::_getOne(self::TABLE, [parent::ID => (int)$value])),
            $criteria == Criteria::TOKEN && !QString::_isNull($value) => self::_toObject(parent::_getOne(self::TABLE, [parent::TOKEN => trim($value)])),
            default => null,
        };
    }

    static protected function _toObject(?OODBBean $bean): ?Competcategory
    {
        if (!$bean instanceof OODBBean)
            return null;

        if (!empty($vars = $bean->export())) {
            return new Competcategory(
                $vars[self::ID],
                $vars[TOKEN],
                Competition::_get(Criteria::ID, $vars[COMPTTN]),
                Category::_get(Criteria::ID, $vars[CTGRY]),
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
    static public function getCategoryByCompet(Competition $competition): ?array
    {
        if (!empty($beans = parent::_getAll(self::TABLE, []))) {
            foreach ($beans as $bean)
                if (($item = self::_toObject($bean)) instanceof self)
                    if($item->competition->getToken() == $competition->getToken())
                    $action[] = $item->toArray();

        }
        return $action ?? null;
    }

    public function toArray(): array
    {
        return [
            TOKEN => $this->token,
            COMPTTN => $this->competition instanceof Competition ? $this->competition->getTheme() : null,
            CTGRY => $this->category instanceof Category ? $this->category->getLabel() : null,
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

    public function save(): ?Competcategory
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

        if (empty($this->category)) $errors[] = "category is required!";
        if (empty($this->competition)) $errors[] = "competition is required!";

        if (!empty($errors)) {
            throw new Exception(implode(" ", $errors));
        }
    }

    static protected function _toBean(Competcategory $group): ?Competcategory
    {


        $bean = ($update = (int)$group->getId() > 0)
            ? R::loadForUpdate(self::TABLE, $group->getId())
            : R::dispense(self::TABLE);

        if (!$bean instanceof OODBBean)
            return null;
        if (!$update) {
            $bean->{self::TOKEN} = (int)self::shortToken(self::TABLE);
        }
        $bean->{COMPTTN} = QString::_set($group->getCompetition()->getId());
        $bean->{CTGRY} = QString::_set($group->getCategory()->getId());


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

    public function getCompetition(): ?Competition
    {
        return $this->competition;
    }

    public function setCompetition(?Competition $competition): void
    {
        $this->competition = $competition;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): void
    {
        $this->category = $category;
    }

    public function delete(): bool
    {

        parent::_toTrash(self::TABLE, $this->id);

        return false;
    }

}