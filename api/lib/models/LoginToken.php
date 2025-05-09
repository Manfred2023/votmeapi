<?php

use RedBeanPHP\OODBBean;

class LoginToken extends DBA
{
    protected const   TABLE = 'logintoken';
    private ?int $id;
    private string $token;
    private User $user;
    private string $datetime;

    /**
     * @param int|null $id
     * @param string $token
     * @param User $user
     * @param string $datetime
     */
    public function __construct(?int $id, string $token, User $user, string $datetime)
    {
        $this->id = $id;
        $this->token = $token;
        $this->user = $user;
        $this->datetime = $datetime;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    /**
     * @param string $token
     * @param User $user
     * @param string $datetime
     */


    public function getToken(): string
    {
        return $this->token;
    }

    public function setToken(string $token): void
    {
        $this->token = $token;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    public function getDatetime(): string
    {
        return $this->datetime;
    }

    public function setDatetime(string $datetime): void
    {
        $this->datetime = $datetime;
    }
    public static function generateLoginCode(): string {
        return bin2hex(random_bytes(20));
    }
    static public function _get(int $criteria, $value): ?LoginToken
    {
        return match (true) {
            $criteria == Criteria::ID && (int)$value > 0 => self::_toObject(parent::_getOne(self::TABLE, [parent::ID => (int)$value])),
            $criteria == Criteria::TOKEN && !QString::_isNull($value) => self::_toObject(parent::_getOne(self::TABLE, [parent::TOKEN => trim($value)])),
            $criteria == Criteria::USER && !QString::_isNull($value) => self::_toObject(parent::_getOne(self::TABLE, ['userconnected' => trim($value)])),
            default => null,
        };
    }

    static protected function _toObject(?OODBBean $bean): ?LoginToken
    {
        if (!$bean instanceof OODBBean)
            return null;

        if (!empty($vars = $bean->export())) {
            return new LoginToken(
                $vars[ID],
                $vars[TOKEN],
                User::_get(Criteria::ID, $vars[USER1]),
                $vars[DTTM],
            );
        }
        return null;
    }
    public function save()
    {
        return self::_toBean($this);
    }
    static protected function _toBean(LoginToken $loginToken): ?LoginToken
    {

        $bean = ($update = (int)$loginToken->getId() > 0)
            ? R::loadForUpdate(self::TABLE, $loginToken->getId())
            : R::dispense(self::TABLE);

        if (!$bean instanceof OODBBean)
            return null;
      /*  if (!$update) {
            $bean->{TOKEN} = QString::_set($loginToken->getToken());
        }*/
        $bean->{TOKEN} = QString::_set($loginToken->getToken());
        $bean->{USER1} = QString::_set($loginToken->getUser()->getId());
        $bean->{DTTM} = QString::_set($loginToken->getDatetime());


        if (($lastCID = (int)R::store($bean)) > 0)
            return self::_toObject(R::load(self::TABLE, $lastCID));

        return null;
    }

}