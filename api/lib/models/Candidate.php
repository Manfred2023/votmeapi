<?php

use RedBeanPHP\OODBBean;
use RedBeanPHP\RedException\SQL;

class Candidate extends DBA
{
    protected const   TABLE = 'candidate';
    private ?int $id;
    private ?int $token;
    private string $photo;
    private string $description;
    private ?string $slogan;
    private bool $confirmed;
    private Contact $contact;
    private Competition $competition;
    private User $createdby;

    /**
     * @param int|null $id
     * @param int|null $token
     * @param string $photo
     * @param string $description
     * @param string|null $slogan
     * @param bool $confirmed
     * @param Contact $contact
     * @param Competition $competition
     * @param User $createdby
     */
    public function __construct(?int $id, ?int $token, string $photo, string $description, ?string $slogan, bool $confirmed, Contact $contact, Competition $competition, User $createdby)
    {
        $this->id = $id;
        $this->token = $token;
        $this->photo = $photo;
        $this->description = $description;
        $this->slogan = $slogan;
        $this->confirmed = $confirmed;
        $this->contact = $contact;
        $this->competition = $competition;
        $this->createdby = $createdby;
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

    static protected function _toObject(?OODBBean $bean): ?Candidate
    {
        if (!$bean instanceof OODBBean || $bean->isEmpty())
            return null;

        $vars = $bean->export();

        return new Candidate(
            $vars[self::ID],
            $vars[self::TOKEN],
            $vars[PHOTO],
            $vars[DSCRPTN],
            $vars[SLOGAN],
            $vars[CONFIRMED],
            Contact::_get(Criteria::ID, (int)$vars[CNTCT]),
            Competition::_get(Criteria::ID, (int)$vars[COMPTTN]),
            User::_get(Criteria::ID, (int)$vars[CRTDBY]),

        );
    }

    static public function _get(int $criteria, $value): ?Candidate
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
            PHOTO => QString::_get($this->photo),
            DSCRPTN => QString::_get($this->description),
            SLOGAN => ($this->slogan),
            CONFIRMED => ($this->confirmed),
            CNTCT => $this->contact instanceof Contact ? $this->contact->toArray() : null,
            COMPTTN => $this->competition instanceof Competition ? $this->competition->toArray() : null,

        ];
    }

    static public function getCandidateByCompetition(Competition $competition): array
    {
        $beans = R::findAll(self::TABLE, 'competition = ?', [$competition->getId()]);
        foreach ($beans as $bean)
            if (($item = self::_toObject($bean)) instanceof self)
                $contacts[] = $item->toArray();
        return $contacts ?? [];
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

    public function save(): ?Candidate
    {
        return self::_toBean($this);
    }

    /**
     * @throws SQL
     */
    static protected function _toBean(?Candidate $candidate): ?Candidate
    {
        if (!$candidate->getContact() instanceof Contact)
            return null;

        if (!$candidate->getCreatedby() instanceof User)
            return null;

        if (!$candidate->getCompetition() instanceof Competition)
            return null;


        $bean = ($update = (int)$candidate->getId() > 0)
            ? R::loadForUpdate(self::TABLE, $candidate->getId())
            : R::dispense(self::TABLE);


        if (!$bean instanceof OODBBean)
            return null;

        if (!$update) {
            $bean->{self::TOKEN} = (int)self::shortToken(self::TABLE);
        }
        $bean->{PHOTO} = QString::_latin($candidate->getPhoto());
        $bean->{DSCRPTN} = QString::_latin($candidate->getDescription());
        $bean->{SLOGAN} = ($candidate->getSlogan());
        $bean->{CONFIRMED} = $candidate->isConfirmed();
        $bean->{CNTCT} = $candidate->getContact()->getId();
        $bean->{COMPTTN} = $candidate->getCompetition()->getId();
        $bean->{CRTDBY} = $candidate->getCreatedby()->getId();


        if (($lastID = (int)R::store($bean)) > 0)
            return self::_toObject(R::load(self::TABLE, $lastID));

        return null;
    }

    public function getContact(): Contact
    {
        return $this->contact;
    }

    public function setContact(Contact $contact): void
    {
        $this->contact = $contact;
    }

    public function getCreatedby(): User
    {
        return $this->createdby;
    }

    public function setCreatedby(User $createdby): void
    {
        $this->createdby = $createdby;
    }

    public function getCompetition(): Competition
    {
        return $this->competition;
    }

    public function setCompetition(Competition $competition): void
    {
        $this->competition = $competition;
    }

    static protected function shortToken(): int
    {
        // Nom de la table
        $table = self::TABLE;

        $lastToken = R::getCell("SELECT MAX(token) FROM {$table}");

        $nextToken = ($lastToken) ? intval($lastToken) + 1 : 100001;

        return $nextToken;
    }

    public function getPhoto(): string
    {
        return $this->photo;
    }

    public function setPhoto(string $photo): void
    {
        $this->photo = $photo;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getSlogan(): ?string
    {
        return $this->slogan;
    }

    public function setSlogan(?string $slogan): void
    {
        $this->slogan = $slogan;
    }

    public function isConfirmed(): bool
    {
        return $this->confirmed;
    }

    public function setConfirmed(bool $confirmed): void
    {
        $this->confirmed = $confirmed;
    }


}