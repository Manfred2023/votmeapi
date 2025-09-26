<?php
/*
 *  BuildTrackApi
 *
 *  Created by Manfred MOUKATE on 1/7/25, 10:15 AM,
 *  Email moukatemanfred@gmail.com
 *  Copyright (c) 2025. All rights reserved.
 *  Last modified 1/7/25, 10:15 AM
 */

use Ramsey\Uuid\Uuid;
use RedBeanPHP\OODBBean;
require_once __DIR__ . '/../../../vendor/autoload.php';
class Country extends DBA
{
    protected const   TABLE = 'country';
    private ?int $id;
    private ?string $token;
    private string $namefr;
    private string $nameen;
    private string $alpha2;
    private string $alpha3;
    private string $dialcode;

    /**
     * @param int|null $id
     * @param string|null $token
     * @param string $namefr
     * @param string $nameen
     * @param string $alpha2
     * @param string $alpha3
     * @param string $dialcode
     */
    public function __construct(?int $id, ?string $token, string $namefr, string $nameen, string $alpha2, string $alpha3, string $dialcode)
    {
        $this->id = $id;
        $this->token = $token;
        $this->namefr = $namefr;
        $this->nameen = $nameen;
        $this->alpha2 = $alpha2;
        $this->alpha3 = $alpha3;
        $this->dialcode = $dialcode;
    }

    static public function _get(int $criteria, $value): ?Country
    {
        return match (true) {
            $criteria == Criteria::ID && (int)$value > 0 => self::_toObject(parent::_getOne(self::TABLE, [parent::ID => (int)$value])),
            $criteria == Criteria::TOKEN && !QString::_isNull($value) => self::_toObject(parent::_getOne(self::TABLE, [parent::TOKEN => trim($value)])),
            default => null,
        };
    }

    static protected function _toObject(?OODBBean $bean): ?Country
    {
        if (!$bean instanceof OODBBean)
            return null;

        if (!empty($vars = $bean->export())) {
            return new Country(
                $vars[self::ID],
                $vars[TOKEN],
                $vars[NAMEFR],
                $vars[NAMEEN],
                $vars[ALPHA2],
                $vars[ALPHA3],
                $vars[DIAL],
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
     * @param int $limit
     * @param int $offset
     * @return array|null
     */


    public function toArray(): array
    {
        return [
            TOKEN => $this->token,
            DIAL => $this->dialcode,
            ALPHA2 => $this->alpha2,
            ALPHA3 => $this->alpha3,
            NAMEFR => $this->namefr,
            NAMEEN => $this->nameen,
        ];
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(?string $token): void
    {
        $this->token = $token;
    }

    /**
     * @return Country|null
     */
    public function save(): ?Country
    {
        try {
            $this->validate();
            return self::_toBean($this);
        } catch (Exception $e) {
            Reply::_error($e->getMessage());
        }
        return null;
    }

    /**
     * @return void
     * @throws Exception
     */
    public function validate(): void
    {
        $errors = [];

        if (empty($this->namefr)) $errors[] = "namefr is required!";
        if (empty($this->nameen)) $errors[] = "nameen is required!";
        if (empty($this->dialcode)) $errors[] = "dialcode is required!";
        if (empty($this->alpha2)) $errors[] = "alpha2 is required!";
        if (empty($this->alpha3)) $errors[] = "alpha3 is required!";

        if (!empty($errors)) {
            throw new Exception(implode(" ", $errors));
        }
    }

    static protected function _toBean(Country $country): ?Country
    {

        $bean = ($update = (int)$country->getId() > 0)
            ? R::loadForUpdate(self::TABLE, $country->getId())
            : R::dispense(self::TABLE);

        if (!$bean instanceof OODBBean)
            return null;
        if (!$update) {
            $bean->{self::TOKEN} =  self::uuidToken();
        }
        $bean->{NAMEFR} = QString::_set($country->getNamefr());
        $bean->{NAMEEN} = QString::_set($country->getNameen());
        $bean->{DIAL} = QString::_set($country->getDialcode());
        $bean->{ALPHA2} = QString::_set($country->getAlpha2());
        $bean->{ALPHA3} = QString::_set($country->getAlpha3());


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
    static protected function uuidToken(): string
    {
        try {
            return Uuid::uuid4()->toString();
        } catch (\Exception $e) {
            throw new \RuntimeException("Erreur lors de la génération du token UUID : " . $e->getMessage());
        }
    }

    public function getNamefr(): string
    {
        return $this->namefr;
    }

    public function setNamefr(string $namefr): void
    {
        $this->namefr = $namefr;
    }

    public function getNameen(): string
    {
        return $this->nameen;
    }

    public function setNameen(string $nameen): void
    {
        $this->nameen = $nameen;
    }

    public function getDialcode(): string
    {
        return $this->dialcode;
    }

    public function setDialcode(string $dialcode): void
    {
        $this->dialcode = $dialcode;
    }

    public function getAlpha2(): string
    {
        return $this->alpha2;
    }

    public function setAlpha2(string $alpha2): void
    {
        $this->alpha2 = $alpha2;
    }

    public function getAlpha3(): string
    {
        return $this->alpha3;
    }

    public function setAlpha3(string $alpha3): void
    {
        $this->alpha3 = $alpha3;
    }


}