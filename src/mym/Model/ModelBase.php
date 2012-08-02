<?php

/**
 * Models base class
 * 
 * @copyright 2010 Misha Yurasov
 * @package mym
 */

namespace mym\Model;

use mym\Storage;
use mym\Helper\PDOHelper;
use mym\Exception\Exception;

abstract class ModelBase extends Storage
{
  /**
   * Datatabase table name
   * @var string
   */
  protected $table = '';

  /**
   * ID key name
   *
   * Persistent copy of object is stored
   * in the database under this key
   *
   * @var string
   */
  protected $idKey = 'id';

  // Changed data
  protected $changed = array();

  // Remember changes?
  protected $remember_changes = true;

  /**
   * Constructor
   * 
   * @param array $data
   */
  public function __construct($data = array())
  {
    if (!is_array($data))
    {
      $data = array($this->idKey => $data);
    }

    $this->set($data, null);
  }

  /**
   * Set field or multiple fields
   *
   * @param string|array $name
   * @param mixed $value
   * @return Storage
   */
  public function set($name, $value = null)
  {
    if ($this->remember_changes && !is_array($name))
    {
      $this->changed[$name] = $value;
    }

    return parent::set($name, $value);
  }

  /**
   * Save to database
   *
   * @param boolean $force_all
   * @return ModelBase
   */
  public function save($force_all = false)
  {
    // Save all data
    if ($force_all)
      $this->changed = $this->data;

    if (count($this->changed) > 0)
    {
      $pdo = PDOHelper::getPDO();

      if (is_null($this->data[$this->idKey]))
      {
        $sql = PDOHelper::prepareSQL(
          "INSERT INTO {$this->table} (%k) VALUES (%v)",
          $this->changed
        );

        // Insert data
        $pdo->exec($sql);

        // Set id
        $this->set($this->idKey, $pdo->lastInsertId($this->idKey));
      }
      else
      {
        $sql = PDOHelper::prepareSQL(
          "UPDATE {$this->table} SET %k=v WHERE {$this->idKey}=%s",
          $this->changed,
          $this->data[$this->idKey]
        );

        // Update data
        $pdo->exec($sql);
      }

      $this->changed = array();
    }

    return $this;
  }

  /**
   * Load data from database
   *
   * @param integer $id
   * @return ModelBase ModelBase or FALSE
   */
  public function load($id = null)
  {
    if (is_null($id))
      $id = $this->data[$this->idKey];

    if (is_null($id))
    {
      throw new Exception(get_called_class() . '::' . $this->idKey .
              ' is NULL', \mym\ERROR_MISC);
    }

    $pdo = PDOHelper::getPDO();
    
    $whereSql = 'WHERE ' . $this->idKey .
      '=' . $pdo->quote($id);

    $sql = PDOHelper::prepareSQL(
      "SELECT %k FROM {$this->table} {$whereSql}",
      $this->data
    );

    // Fetch data
    if (false === ($data = $pdo->query($sql)->fetch(\PDO::FETCH_ASSOC)))
      return false;

    // Reset changes
    $this->data = $data;
    $this->resetChanged();

    return $this;
  }

  /**
   * Fill instance with data, not remembering changes
   *
   * @param array|string $data
   * @param mixed $value
   */
  public function fill($data, $value = null)
  {
    $remember_changes = $this->remember_changes;
    $this->remember_changes = false;

    parent::set($data, $value);

    $this->remember_changes = $remember_changes;
    return $this;
  }

  /**
   * Reset list of changes fields
   */
  public function resetChanged()
  {
    $this->changed = array();
  }

  /**
   * Delete from database
   * Sets id field to null
   *
   * @param integer $id
   * @return ModelBase
   */
  public function delete($id = null)
  {
    if (!is_null($id))
      $this->data[$this->idKey] = $id;

    if (is_null($this->data[$this->idKey]))
      throw new Exception(get_called_class() . '::' . $this->idKey . ' is NULL', mym\ERROR_MISC);

    $pdo = PDOHelper::getPDO();

    $sql = PDOHelper::prepareSQL(
      "DELETE FROM {$this->table} WHERE {$this->idKey}=%s",
      $this->data[$this->idKey]
    );

    // Fetch data
    $pdo->query($sql);

      // Reset changes
    $this->resetChanged();

    return $this;
  }

  /**
   * Get table name
   *
   * @return string
   */
  public function getTable()
  {
    return $this->table;
  }

  /**
   * Get id key name
   * 
   * @return string
   */
  public function getIdKey()
  {
    return $this->idKey;
  }

  /**
   * Create instance from, database
   *
   * @param mixed $id
   * @return ModelBase ModelBase|false
   */
  public static function fromId($id)
  {
    $instance = new static();

    if (false === $instance->load($id))
      return false;

    return $instance;
  }

  /**
   * From WHERE query
   *
   * @param <type> $condition
   * @param <type> $sqlParams
   * @return ModelBase
   */
  public static function fromWhere($condition, $sqlParams = null)
  {
    $instance = new static();
    $pdo = PDOHelper::getPDO();
    $sql = PDOHelper::prepareSQL(
      "SELECT %k FROM {$instance->getTable()} WHERE ", $instance->data) .
        PDOHelper::prepareSQL($condition, $sqlParams);
    
    if (false === ($data = $pdo->query($sql)->fetch(\PDO::FETCH_ASSOC)))
      return false;

    $instance->fill($data);
    return $instance;
  }
}