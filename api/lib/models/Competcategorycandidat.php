<?php

use RedBeanPHP\OODBBean;

class Competcategorycandidat extends DBA
{
    protected const   TABLE = 'competcategorycandidat';
    private ?int $id;
    private ?int $token;
    private ?Candidate $candidate;
    private ?Competcategory $competcategory;

    /**
     * @param int|null $id
     * @param int|null $token
     * @param Candidate|null $candidate
     * @param Competcategory|null $competcategory
     */
    public function __construct(?int $id, ?int $token, ?Candidate $candidate, ?Competcategory $competcategory)
    {
        $this->id = $id;
        $this->token = $token;
        $this->candidate = $candidate;
        $this->competcategory = $competcategory;
    }

    static public function _get(int $criteria, $value): ?Competcategorycandidat
    {
        return match (true) {
            $criteria == Criteria::ID && (int)$value > 0 => self::_toObject(parent::_getOne(self::TABLE, [parent::ID => (int)$value])),
            $criteria == Criteria::TOKEN && !QString::_isNull($value) => self::_toObject(parent::_getOne(self::TABLE, [parent::TOKEN => trim($value)])),
            default => null,
        };
    }

    static protected function _toObject(?OODBBean $bean): ?Competcategorycandidat
    {
        if (!$bean instanceof OODBBean)
            return null;

        if (!empty($vars = $bean->export())) {
            return new Competcategorycandidat(
                $vars[self::ID],
                $vars[TOKEN],
                Candidate::_get(Criteria::ID, $vars[CNDDT]),
                Competcategory::_get(Criteria::ID, $vars[CMPTCTGR]),
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
            CNDDT => $this->candidate instanceof Candidate ? $this->candidate->toArray() : null,
            CMPTCTGR => $this->competcategory instanceof Competcategory ? $this->competcategory->toArray() : null,
        ];
    }

    static public function getCandidateByCategory(Competcategory $competition): array
    {
        $beans = R::findAll(self::TABLE, 'competcategory = ?', [$competition->getId()]);
        foreach ($beans as $bean)
            if (($item = self::_toObject($bean)) instanceof self)
                $contacts[] = $item->toArray();
        return $contacts ?? [];
    }

    public function getToken(): ?int
    {
        return $this->token;
    }

    public function setToken(?int $token): void
    {
        $this->token = $token;
    }

    public function save(): ?Competcategorycandidat
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

        if (empty($this->candidate)) $errors[] = "candidate_is_required!";
        if (empty($this->competcategory)) $errors[] = "competcategory_is_required!";

        if (!empty($errors)) {
            throw new Exception(implode(" ", $errors));
        }
    }

    static protected function _toBean(Competcategorycandidat $competcategorycandidat): ?Competcategorycandidat
    {


        $bean = ($update = (int)$competcategorycandidat->getId() > 0)
            ? R::loadForUpdate(self::TABLE, $competcategorycandidat->getId())
            : R::dispense(self::TABLE);

        if (!$bean instanceof OODBBean)
            return null;
        if (!$update) {
            $bean->{self::TOKEN} = (int)self::shortToken(self::TABLE);
        }
        $bean->{CNDDT} = QString::_set($competcategorycandidat->getCandidate()->getId());
        $bean->{CMPTCTGR} = QString::_set($competcategorycandidat->getCompetcategory()->getId());


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

    public function getCandidate(): ?Candidate
    {
        return $this->candidate;
    }

    public function setCandidate(?Candidate $candidate): void
    {
        $this->candidate = $candidate;
    }

    public function getCompetcategory(): ?Competcategory
    {
        return $this->competcategory;
    }

    public function setCompetcategory(?Competcategory $competcategory): void
    {
        $this->competcategory = $competcategory;
    }

    public function delete(): bool
    {

        parent::_toTrash(self::TABLE, $this->id);

        return false;
    }


}