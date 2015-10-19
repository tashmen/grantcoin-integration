<?php
/*
    Copyright 2015 Jon Norcross 

    This file is part of GrantCoin Integration.

    GrantCoin Integration is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    GrantCoin Integration is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with GrantCoin Integration.  If not, see <http://www.gnu.org/licenses/>.
*/
/**
 * Interface for database operations
 *
 * @author jnorcross
 */
interface iDatabase {
    public function lastInsertId();
    public function execute($statement, $parameters = null, $fetchData = true);
    public function rowCount($statement, $parameters = null);
    public function GetColumnSchema($table);
}
