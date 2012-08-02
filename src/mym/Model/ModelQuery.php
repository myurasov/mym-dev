<?php

namespace mym\Model;

use mym\Helper\PDOHelper;
use mym\Config;

class ModelQuery
{
  protected $whereSql = '';
  protected $limitSql = '';
  protected $namespace = '';

  /**
   * @var PDO
   */
  protected $pdo;

  /**
   * Constructor
   *
   * @param string|null $modelsNamespace Namespace for models
   */
  public function __construct($namespace = '')
  {
    $this->namespace = $namespace;
    $this->pdo = PDOHelper::getPDO();
  }

  /**
   * Set WHERE clause
   *
   * @param string $whereSql
   * @param array|null $params
   * @return ModelBase
   */
  public function where($whereSql, $params = null)
  {
    $whereSql = PDOHelper::prepareSQL($whereSql, $params);
    $this->whereSql = $whereSql;
    return $this;
  }

  /**
   * Set LIMIT clause
   *
   * @param string $limitSql
   * @param array|null $params
   * @return ModelBase
  */
  public function limit($limitSql, $params = null)
  {
    $limitSql = PDOHelper::prepareSQL($limitSql, $params);
    $this->limitSql = $limitSql;
    return $this;
  }

  /**
   * Fetch models
   * 
   * @param string $className
   * @return array
   */
  public function fetch($className)
  {
    // Get model params
    $className = $this->namespace . '\\' . $className;
    $instance = new $className();
    $table = $instance->getTable();
    $data = $instance->get();

    // Create SQL query

    $sql = "SELECT %k FROM {$table}";

    if ($this->whereSql != '')
      $sql .= " WHERE " . $this->whereSql;

    if ($this->limitSql != '')
      $sql .= " LIMIT " . $this->limitSql;

    $sql = PDOHelper::prepareSQL($sql, $data);
    $result = array();
    $resultI = 0;

    // Fetch models

    if ($stmt = $this->pdo->query($sql))
    {
      while ($row = $stmt->fetch(\PDO::FETCH_ASSOC))
      {
        if ($resultI++ > 0)
          $instance = new $class_name();

        $instance->fill($row);
        $result[] = $instance;
      }
    }

    return $result;
  }

  public function getNamespace()
  {
    return $this->namespace;
  }

  public function setNamespace($namespace)
  {
    $this->namespace = $namespace;
  }
}