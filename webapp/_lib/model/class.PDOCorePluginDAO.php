<?php
/**
 *
 * ThinkUp/webapp/_lib/model/class.PDOCorePluginDAO.php
 *
 * Copyright (c) 2011 Gina Trapani
 *
 * LICENSE:
 *
 * This file is part of ThinkUp (http://thinkupapp.com).
 *
 * ThinkUp is free software: you can redistribute it and/or modify it under the terms of the GNU General Public
 * License as published by the Free Software Foundation, either version 2 of the License, or (at your option) any
 * later version.
 *
 * ThinkUp is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied
 * warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more
 * details.
 *
 * You should have received a copy of the GNU General Public License along with ThinkUp.  If not, see
 * <http://www.gnu.org/licenses/>.
 *
 * PDO Core/Plugin DAO
 *
 * Provides support methods for selecting plugin-specific fields in addition to core data fiels without rewritng SQL.
 *
 * @author Gina Trapani <ginatrapani[at]gmail[dot]com>
 * @license http://www.gnu.org/licenses/gpl.html
 * @copyright 2011 Gina Trapani
 */
abstract class PDOCorePluginDAO extends PDODAO{
    /**
     * @var str Object the DAO interacts with.
     */
    var $object_name;
    /**
     * @var str MySQL table name without its prefix.
     */
    var $table_name;
    /**
     * @var str Plugin-specific MySQL table name without its prefix.
     */
    var $meta_table_name = null;
    /**
     * Define the object name to return, the core table name, and the meta/plugin table name.
     * @param str $object_name
     * @param str $table_name
     * @param str $meta_table_name
     * @return PDOCorePluginDAO
     */
    public function __construct($object_name, $table_name, $meta_table_name=null) {
        $this->object_name = $object_name;
        $this->table_name = $table_name;
        $this->meta_table_name = $meta_table_name;
    }
    /**
     * Set the object name.
     * @param str $object_name
     */
    protected function setObjectName($object_name) {
        $this->object_name = $object_name;
    }
    /**
     * Set the meta table name.
     * @param str $meta_table_name
     */
    protected function setMetaTableName($meta_table_name) {
        $this->meta_table_name = $meta_table_name;
    }
    /**
     * Get string listing all the fields to select from both core and plugin table.
     * @TODO Use reflection on the object to iterate through the fields one by one instead of using .*.
     * @return str
     */
    protected function getFieldList() {
        $field_list = $this->getTableName().".* ";
        if (isset($this->meta_table_name)) {
            $field_list .= ", ".$this->getMetaTableName().".* ";
        }
        return $field_list;
    }
    /**
     * Get table name with dynamic prefix.
     * @return str
     */
    protected function getTableName() {
        return "#prefix#".$this->table_name;
    }
    /**
     * Get meta table name with dynamic prefix.
     * @return str
     */
    protected function getMetaTableName() {
        if (isset($this->meta_table_name)) {
            return "#prefix#".$this->meta_table_name;
        } else {
            return "";
        }
    }
    /**
     * Get the join definition on the meta plugin table.
     * @return str
     */
    protected function getMetaTableJoin() {
        $join = "";
        if (isset($this->meta_table_name)) {
            $join .= "LEFT JOIN ".$this->getMetaTableName()." on ".$this->getTableName(). ".id=".
            $this->getMetaTableName().".id ";
        }
        return $join;
    }
}