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

    /**
     * @param int|null $id
     * @param int|null $token
     * @param string $theme
     * @param string|null $logo
     * @param bool $free
     * @param DateTime $started
     * @param DateTime $ended
     * @param Structure $structure
     * @param User $promoter
     */
    public function
    __construct(?int $id, ?int $token, string $theme, ?string $logo, bool $free, DateTime $started, DateTime $ended, Structure $structure, User $promoter)
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
            $vars[GRATUIT],
            $vars[STARTED],
            $vars[ENDED],
            Structure::_get(Criteria::ID, (int)$vars[S]),
            $vars[MBLE],
            $vars[LANG],
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
            FRSTNM => QString::_get($this->firstname),
            LSTNM => QString::_get($this->lastname),
            GNDR => ($this->gender),
            MBLE => ($this->mobile),
            EMAIL => QString::_get($this->email),
            LCTN => QString::_get($this->location),
            LANG => QString::_get($this->lang),
            CITY => $this->city instanceof City ? $this->city->toArray() : null,
        ];
    }

    static protected function _toBean(?Competition $contact): ?Competition
    {
        if (!$contact instanceof Contact)
            return null;

        if (!$contact->getCity() instanceof City)
            return null;


        $bean = ($update = (int)$contact->getId() > 0)
            ? R::loadForUpdate(self::TABLE, $contact->getId())
            : R::dispense(self::TABLE);


        if (!$bean instanceof OODBBean)
            return null;

        if (!$update) {
            $bean->{self::TOKEN} = (int)self::shortToken(self::TABLE);
        }
        $bean->{FRSTNM} = QString::_set(QString::_latin($contact->getFirstname()));
        $bean->{LSTNM} = QString::_set(QString::_latin($contact->getLastname()));
        $bean->{MBLE} = (int)($contact->getMobile());
        $bean->{GNDR} = $contact->getGender();
        $bean->{CITY} = (int)$contact->getCity()->getId();
        $bean->{LCTN} = QString::_set(QString::_latin($contact->getLocation()));
        $bean->{EMAIL} = QString::_set($contact->getEmail());
        $bean->{LANG} = QString::_set($contact->getLang());


        if (($lastID = (int)R::store($bean)) > 0)
            return self::_toObject(R::load(self::TABLE, $lastID));

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

    public function getToken(): ?int
    {
        return $this->token;
    }

    public function setToken(?int $token): void
    {
        $this->token = $token;
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

    public function getPromoter(): User
    {
        return $this->promoter;
    }

    public function setPromoter(User $promoter): void
    {
        $this->promoter = $promoter;
    }

}