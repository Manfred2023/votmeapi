<?php

use RedBeanPHP\OODBBean;

class Competition extends DBA
{
    protected const   TABLE = 'competition';
    private ?int $id;
    private ?int $token;
    private string $theme;
    private ?string $logo;
    private bool $free;
    private DateTime $started;
    private DateTime $ended;
    private Structure $structure;
    private User $promoter;
    private Status $status;

    /**
     * /**
     * @param int|null $id
     * @param int|null $token
     * @param string $theme
     * @param string|null $logo
     * @param bool $free
     * @param DateTime $started
     * @param DateTime $ended
     * @param Structure $structure
     * @param User $promoter
     * @param Status $status
     */
    public function
    __construct(?int $id, ?int $token, string $theme, ?string $logo, bool $free, DateTime $started, DateTime $ended, Structure $structure, User $promoter, Status $status)
    {
        $this->id = $id;
        $this->token = $token;
        $this->theme = $theme;
        $this->logo = $logo;
        $this->free = $free;
        $this->started = $started;
        $this->ended = $ended;
        $this->structure = $structure;
        $this->promoter = $promoter;
        $this->status = $status;
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

    /**
     * @param User $user
     * @return array|null
     */
    static public function competitionByPromoter(User $user): ?array
    {
        if (!empty($beans = parent::_getAll(self::TABLE, []))) {
            foreach ($beans as $bean)
                if (($item = self::_toObject($bean)) instanceof self)
                    if($item->promoter->getToken() == $user->getToken())
                    $contacts[] = $item->toArray();
        }
        return $contacts ?? null;
    }

    static protected function _toObject(?OODBBean $bean): ?Competition
    {
        if (!$bean instanceof OODBBean || $bean->isEmpty())
            return null;

        $vars = $bean->export();

        return new Competition(
            $vars[self::ID],
            $vars[self::TOKEN],
            $vars[THEME],
            $vars[LOGO],
            $vars[FREE],
            new DateTime($vars[STARTED]),
            new DateTime($vars[ENDED],),

            Structure::_get(Criteria::ID, (int)$vars[STRCTR]),
            User::_get(Criteria::ID, (int)$vars[PROMOTR]),
            Status::_get(Criteria::ID, (int)$vars[STATUS]),
        );
    }

    static public function _get(int $criteria, $value): ?Competition
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
            THEME => QString::_get($this->theme),
            LOGO => QString::_get($this->logo),
            FREE => ($this->free),
            STARTED => ($this->started),
            ENDED => $this->ended,
            STRCTR => $this->structure instanceof Structure ? $this->structure->toArray() : null,
            PROMOTR => $this->promoter instanceof User ? $this->promoter->toArray() : null,
            STATUS => $this->status instanceof Status ? $this->status->toArray() : null,
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

    public function save(): Competition
    {

        return self::_toBean($this);

    }

    static protected function _toBean(?Competition $competition): ?Competition
    {
        if (!$competition->getPromoter() instanceof User)
            return null;


        $bean = ($update = (int)$competition->getId() > 0)
            ? R::loadForUpdate(self::TABLE, $competition->getId())
            : R::dispense(self::TABLE);


        if (!$bean instanceof OODBBean)
            return null;

        if (!$update) {
            $bean->{self::TOKEN} = (int)self::shortToken(self::TABLE);
        }
        $bean->{THEME} = QString::_set(QString::_latin($competition->getTheme()));
        $bean->{LOGO} = $competition->getLogo();
        $bean->{FREE} = $competition->isFree();
        $bean->{STARTED} = $competition->getStarted();
        $bean->{ENDED} = $competition->getEnded();
        $bean->{STRCTR} = QString::_set(QString::_latin($competition->getStructure()->getId()));
        $bean->{PROMOTR} = QString::_set($competition->getPromoter()->getId());
        $bean->{STATUS} = QString::_set($competition->getStatus()->getId());


        if (($lastID = (int)R::store($bean)) > 0)
            return self::_toObject(R::load(self::TABLE, $lastID));

        return null;
    }

    public function getPromoter(): User
    {
        return $this->promoter;
    }

    public function setPromoter(User $promoter): void
    {
        $this->promoter = $promoter;
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

    public function getTheme(): string
    {
        return $this->theme;
    }

    public function setTheme(string $theme): void
    {
        $this->theme = $theme;
    }

    public function getLogo(): ?string
    {
        return $this->logo;
    }

    public function setLogo(?string $logo): void
    {
        $this->logo = $logo;
    }

    public function isFree(): bool
    {
        return $this->free;
    }

    public function setFree(bool $free): void
    {
        $this->free = $free;
    }

    public function getStarted(): DateTime
    {
        return $this->started;
    }

    public function setStarted(DateTime $started): void
    {
        $this->started = $started;
    }

    public function getEnded(): DateTime
    {
        return $this->ended;
    }

    public function setEnded(DateTime $ended): void
    {
        $this->ended = $ended;
    }

    public function getStructure(): Structure
    {
        return $this->structure;
    }

    public function setStructure(Structure $structure): void
    {
        $this->structure = $structure;
    }

    public function getStatus(): Status
    {
        return $this->status;
    }

    public function setStatus(Status $status): void
    {
        $this->status = $status;
    }

}