<?php
/*
 *  BuildTrackApi
 *
 *  Created by Manfred MOUKATE on 1/7/25, 10:15 AM,
 *  Email moukatemanfred@gmail.com
 *  Copyright (c) 2025. All rights reserved.
 *  Last modified 1/7/25, 10:15 AM
 */

/* use RedBeanPHP\RedException\SQL; */

use RedBeanPHP\OODBBean;

class Contact extends DBA
{

    protected const   TABLE = 'contact';
    private ?int $id;
    private ?int $token;
    private string $gender;
    private ?string $firstname;
    private string $lastname;
    private ?string $email;
    private ?string $location;
    private City $city;
    private string $mobile;
    private ?string $lang;

    /**
     * @param int|null $id
     * @param int|null $token
     * @param string $gender
     * @param string|null $firstname
     * @param string $lastname
     * @param string $email
     * @param string|null $location
     * @param City $city
     * @param string $mobile
     * @param string $lang
     */
    public function __construct(?int $id, ?int $token, string $gender, ?string $firstname, string $lastname, string $email, ?string $location, City $city, string $mobile, string $lang)
    {
        $this->id = $id;
        $this->token = $token;
        $this->gender = $gender;
        $this->firstname = $firstname;
        $this->lastname = $lastname;
        $this->email = $email;
        $this->location = $location;
        $this->city = $city;
        $this->mobile = $mobile;
        $this->lang = $lang;
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

    static protected function _toObject(?OODBBean $bean): ?Contact
    {
        if (!$bean instanceof OODBBean || $bean->isEmpty())
            return null;

        $vars = $bean->export();

        return new Contact(
            $vars[self::ID],
            $vars[self::TOKEN],
            $vars[GNDR],
            $vars[FRSTNM],
            $vars[LSTNM],
            $vars[EMAIL],
            $vars[LCTN],
            City::_get(Criteria::ID, (int)$vars[CITY]),
            $vars[MBLE],
            $vars[LANG],
        );
    }

    static public function _get(int $criteria, $value): ?Contact
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

    static public function checkNumber(string $mobile): ?bool
    {
        $bean = R::findOne(self::TABLE, 'mobile = ?', [$mobile]);

        if ($bean instanceof RedBeanPHP\OODBBean) {
            $contact = self::_toObject($bean);
            if ($contact instanceof self) {
                Reply::_error("This number is already in use");
            }
        }
        return false;
    }

    static public function checkEmail(string $email): ?bool
    {
        $bean = R::findOne(self::TABLE, 'email = ?', [$email]);

        if ($bean instanceof RedBeanPHP\OODBBean) {
            $contact = self::_toObject($bean);
            if ($contact instanceof self) {
                Reply::_error("This email is already in use");
            }
        }
        return false;
    }

    public function getToken(): ?int
    {
        return $this->token;
    }

    public function setToken(?int $token): void
    {
        $this->token = $token;
    }

    public function save(): ?Contact
    {
        if ($this->isOK())
            return self::_toBean($this);
        return null;
    }

    private function isOK(): bool
    {
        if (!$this->city instanceof City)
            throw new Exception("Enter city name ");
        if (QString::_isNull($this->lastname))
            throw new Exception("Lastname is required!");
        if (QString::_isNull($this->email))
            throw new Exception("Email's name is required!");
        if (QString::_isNull($this->mobile))
            throw new Exception("Mobile's name is required!");

        return true;
    }

    static protected function _toBean(?Contact $contact): ?Contact
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

    public function getCity(): City
    {
        return $this->city;
    }

    public function setCity(City $city): void
    {
        $this->city = $city;
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

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(?string $firstname): void
    {
        $this->firstname = $firstname;
    }

    public function getLastname(): string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): void
    {
        $this->lastname = $lastname;
    }

    public function getMobile(): string
    {
        return $this->mobile;
    }

    public function setMobile(string $mobile): void
    {
        $this->mobile = $mobile;
    }

    public function getGender(): string
    {
        return $this->gender;
    }

    public function setGender(string $gender): void
    {
        $this->gender = $gender;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(?string $location): void
    {
        $this->location = $location;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getLang(): string
    {
        return $this->lang;
    }

    public function setLang(string $lang): void
    {
        $this->lang = $lang;
    }

    public function delete(): bool
    {

        if ($this->isGood()) {
            parent::_toTrash(self::TABLE, $this->id);
            return true;
        }
        return false;
    }

    private function isGood(): bool
    {
        if (is_null(trim($this->token)))
            throw new Exception('Token is required!');

        return true;
    }


}