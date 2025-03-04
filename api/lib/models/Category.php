<?php

use RedBeanPHP\OODBBean;

class Category extends DBA
{
    protected const   TABLE = 'category';
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

    static public function _get(int $criteria, $value): ?Category
    {
        return match (true) {
            $criteria == Criteria::ID && (int)$value > 0 => self::_toObject(parent::_getOne(self::TABLE, [parent::ID => (int)$value])),
            $criteria == Criteria::TOKEN && !QString::_isNull($value) => self::_toObject(parent::_getOne(self::TABLE, [parent::TOKEN => trim($value)])),
            default => null,
        };
    }

    static protected function _toObject(?OODBBean $bean): ?Category
    {
        if (!$bean instanceof OODBBean)
            return null;

        if (!empty($vars = $bean->export())) {
            return new Category(
                $vars[self::ID],
                $vars[TOKEN],
                $vars[LABEL],
                $vars[DSCRPTN],
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
            LABEL => $this->label,
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

    public function save(): ?Category
    {
        try {
            $this->validate();
            return self::_toBean($this);
        } catch (Exception $e) {
            error_log($e->getMessage());
            return null;
        }
    }

    public function validate(): void
    {
        $errors = [];

        if (empty($this->label)) $errors[] = "label is required!";

        if (!empty($errors)) {
            throw new Exception(implode(" ", $errors));
        }
    }

    static protected function _toBean(Category $category): ?Category
    {

        $bean = ($update = (int)$category->getId() > 0)
            ? R::loadForUpdate(self::TABLE, $category->getId())
            : R::dispense(self::TABLE);

        if (!$bean instanceof OODBBean)
            return null;
        if (!$update) {
            $bean->{self::TOKEN} = (int)self::shortToken(self::TABLE);
        }
        $bean->{LABEL} = QString::_set($category->getLabel());
        $bean->{DSCRPTN} = QString::_set($category->getDescription());


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


}