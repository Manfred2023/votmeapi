<?php

use RedBeanPHP\OODBBean;

class User extends DBA
{
    protected const   TABLE = 'user';
    private ?int $id;
    private ?int $token;
    private int $mobile;
    private string $code;
    private bool $blocked;
    private Contact $contact;
    private ?User $createdby;
    private Profile $profile;
    private ?string $lastlogin;

    /**
     * @param int|null $id
     * @param int|null $token
     * @param int $mobile
     * @param string $code
     * @param bool $blocked
     * @param Contact $contact
     * @param User|null $createdby
     * @param Profile $profile
     */
    public function __construct(?int $id, ?int $token, int $mobile, string $code, bool $blocked, Contact $contact, ?User $createdby, Profile $profile, ?string $lastlogin)
    {
        $this->id = $id;
        $this->token = $token;
        $this->mobile = $mobile;
        $this->code = $code;
        $this->blocked = $blocked;
        $this->contact = $contact;
        $this->createdby = $createdby;
        $this->profile = $profile;
        $this->lastlogin = $lastlogin;
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
    static public function getAllPromoter(): ?array
    {
        if (!empty($beans = parent::_getAll(self::TABLE, []))) {
            foreach ($beans as $bean)
                if (($item = self::_toObject($bean)) instanceof self)
                        if($item->isPromotor()){
                            $contacts[] = $item->toArray();
                        }


        }
        return $contacts ?? null;
    }
 /*   static public function getAllAssociate(self $userParam): ?array
    {
        if (!empty($beans = parent::_getAll(self::TABLE, []))) {
            foreach ($beans as $bean)
                if (($item = self::_toObject($bean)) instanceof self)
                    if($item->getCreatedby() != null){
                        if($item->getCreatedby()->getToken() == $userParam->getToken())
                            $contacts[] = $item->toArray();
                    }


        }
        return $contacts ?? null;
    }*/

    public static function getAllAssociate(self $userParam): ?array
    {
        $contacts = [];

        $beans = parent::_getAll(self::TABLE, []);
        if (!empty($beans)) {
            foreach ($beans as $bean) {
                $item = self::_toObject($bean);
                if ($item instanceof self) {
                    $createdBy = $item->getCreatedby();
                    if ($createdBy !== null && $createdBy->getToken() === $userParam->getToken()) {
                        $contacts[] = $item->toArray();
                    }
                }
            }
        }

        return !empty($contacts) ? $contacts : null;
    }

    static protected function _toObject(?OODBBean $bean): ?User
    {
        if (!$bean instanceof OODBBean)
            return null;

        if (!empty($vars = $bean->export()))
            return new User(
                $vars[self::ID],
                $vars[self::TOKEN],
                $vars[MBLE],
                $vars[CODE],
                $vars[BLCKD],
                Contact::_get(Criteria::ID, (int)$vars[CNTCT]),
                User::_get(Criteria::ID, (int)$vars[CRTDBY]),
                Profile::_get(Criteria::ID, (int)$vars[PRFLE]),
                $vars[LSTLGN]
            );
        return null;
    }

    static public function _get(int $criteria, $value): ?User
    {
        return match (true) {
            $criteria == Criteria::ID && (int)$value > 0 => self::_toObject(parent::_getOne(self::TABLE, [parent::ID => (int)$value])),
            $criteria == Criteria::TOKEN && !QString::_isNull($value) => self::_toObject(parent::_getOne(self::TABLE, [parent::TOKEN => trim($value)])),
            $criteria == Criteria::MOBILE && !QString::_isNull($value) => self::_toObject(parent::_getOne(self::TABLE, [MBLE => trim($value)])),
             default => null
        };
    }

    public function toArray(): array
    {
        return [
            self::TOKEN => $this->token,
            CODE => ($this->code),
            MBLE => ($this->mobile),
            CNTCT => ($this->contact instanceof Contact) ? $this->contact->toArray() : null,
            PRFLE => ($this->profile instanceof Profile) ? $this->profile->toArray() : null,
            BLCKD => $this->isBlocked(),
            LSTLGN => $this->lastlogin,
            CRTDBY => ($this->createdby instanceof User)
                ? $this->createdby->getContact()->getFirstname() . ' ' . $this->createdby->getContact()->getLastname()
                : null,

        ];
    }

    public function isBlocked(): bool
    {
        return $this->blocked;
    }

    public function setBlocked(bool $blocked): void
    {
        $this->blocked = $blocked;
    }

    public function getContact(): Contact
    {
        return $this->contact;
    }

    public function setContact(Contact $contact): void
    {
        $this->contact = $contact;
    }

    public function save(): ?User
    {
        if (!$this->ToHave())
            return null;
        return self::_toBean($this);

    }

    private function ToHave(): bool
    {
        if (QString::_isNull($this->code))
            throw new Exception("pin is not valid");
        return true;
    }

    /**
     * @throws \RedBeanPHP\RedException\SQL
     */
    static protected function _toBean(?User $user): ?User
    {
        if (!$user instanceof User)
            return null;

        if (!$user->getContact() instanceof Contact)
            return null;

        if (!$user->getProfile() instanceof Profile)
            return null;

        $bean = ($update = (int)$user->getId() > 0)
            ? R::loadForUpdate(self::TABLE, $user->getId())
            : R::dispense(self::TABLE);

        if (!$bean instanceof OODBBean)
            return null;

        if (!$update)
            $bean->{self::TOKEN} = self::shortToken();
        $bean->{MBLE} = $user->getMobile();
        $bean->{CODE} = $user->getCode();
        $bean->{BLCKD} = $user->isBlocked();
        $bean->{CNTCT} = (int)$user->getContact()->getId();
        $bean->{CRTDBY} = $user->getCreatedby()?->getId();
        $bean->{PRFLE} = $user->getProfile()->getId();
        $bean->{LSTLGN} = $user->getLastlogin();


        if (($lastID = (int)R::store($bean)) > 0)
            return self::_toObject(R::load(self::TABLE, $lastID));

        return null;
    }

    public function getProfile(): Profile
    {
        return $this->profile;
    }

    public function isPromotor():bool
    {
        if($this->getProfile()->getLabel() == "promoter"){
            return true;
        }
        return false;
    }
    public function isAssociate():bool
    {
        if($this->getProfile()->getLabel() == "associate"){
            return true;
        }
        return false;
    }

    public function setProfile(Profile $profile): void
    {
        $this->profile = $profile;
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

    public function getMobile(): int
    {
        return $this->mobile;
    }

    public function setMobile(int $mobile): void
    {
        $this->mobile = $mobile;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function setCode(string $code): void
    {
        $this->code = $code;
    }

    public function getCreatedby(): ?User
    {
        return $this->createdby;
    }

    public function setCreatedby(?User $createdby): void
    {
        $this->createdby = $createdby;
    }

    public function getLastlogin(): ?string
    {
        return $this->lastlogin;
    }

    public function setLastlogin(?string $lastlogin): void
    {
        $this->lastlogin = $lastlogin;
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