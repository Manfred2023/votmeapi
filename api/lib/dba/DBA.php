<?php
/*
 *  BuildTrackApi
 *
 *  Created by Manfred MOUKATE on 1/7/25, 10:15 AM,
 *  Email moukatemanfred@gmail.com
 *  Copyright (c) 2025. All rights reserved.
 *  Last modified 1/7/25, 10:15 AM
 */


use RedBeanPHP\OODBBean;

abstract class DBA
    {
        protected const ANDJOIN = ' AND ';
        protected const ORJOIN = ' OR ';
        protected const ID = 'id';
        protected const TOKEN = 'token';

        /**
         * @param string $table
         * @param array|null $columns
         * @param string|null $logic
         * @return OODBBean|null
         */
        static protected function _getOne(string $table, ?array $columns, ?string $logic = null): ?OODBBean
        {
            if (empty($columns))
                return null;

            $sql = [];
            foreach ($columns as $column => $value)
                $sql[] = (is_int($value) && (int)$value > 0) ? " `{$column}` = " . (int)$value : " `{$column}` LIKE '{$value}'";

            $logic = is_null($logic) ? static::ANDJOIN : (!in_array($logic, [static::ORJOIN, static::ANDJOIN]) ? static::ANDJOIN : $logic);

            return R::findOne($table, join($logic, $sql));
        }

        /**
         * @param string $table
         * @param array|null $columns
         * @param string|null $logic
         * @return OODBBean[]|null
         */
        static protected function _getAll(string $table, ?array $columns, ?string $logic = null): ?array
        {
            $sql = [];
            foreach ($columns as $dbColumn => $value)
                $sql[] = (is_int($value) && (int)$value > 0) ? " `{$dbColumn}` = " . (int)$value : " `{$dbColumn}` LIKE '{$value}'";

            $logic = is_null($logic) ? static::ANDJOIN : (!in_array($logic, [static::ORJOIN, static::ANDJOIN]) ? static::ANDJOIN : $logic);

            return R::findAll($table, join($logic, $sql));
        }

        /**
         * @param string $table
         * @param int $id
         * @return void
         */
        static protected function _toTrash(string $table, int $id): void
        {
            R::trash($table, $id);
        }

        /**
         * @return string
         */

    }