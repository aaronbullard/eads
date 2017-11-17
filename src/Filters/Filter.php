<?php

namespace EADS\Filters;

use TypeError;

class Filter
{
  /**
   * List of operators.  Order mattes
   * @var array
   */
  protected $operators = ['==', '!=', '=@', '!@', '>=<', '>=', '<=', '><', '>', '<'];

  protected $sqlTemplates = [
    '==' => "{field} = ?",
    '!=' => "{field} != ?",
    '=@' => "{field} LIKE ?",
    '!@' => "{field} NOT LIKE ?",
    '>=' => "{field} >= ?",
    '<=' => "{field} <= ?",
    '>=<' => "{field} BETWEEN ? AND ?",
    '><' => "{field} > ? AND {field} < ?",
    '>' => "{field} > ?",
    '<' => "{field} < ?"
  ];

  protected $query;

  protected $segments;

  protected $sql;

  public function __construct(string $query)
  {
    $this->query = $query;

    try{
      $this->segments = $this->handleSegments($query);

      $this->sql = $this->handleSQL($this->segments);
    }
    catch(TypeError $e){
      throw new FilterException("The query could no be parsed.");
    }
  }

  public function getQuery()
  {
    return $this->query;
  }

  public function getSegments() : array
  {
    return $this->segments;
  }

  public function getSQL() : string
  {
    return $this->sql;
  }

  public function getBindings()
  {
    $segments = $this->getSegments();

    $value = $segments['value'];

    // Handle =@ and !@ for a LIKE query
    if($segments['oper'] == '=@' || $segments['oper'] == '!@'){
      $value[0] = "'%" . $value[0] . "%'";
    }

    return $value;
  }

  protected function handleSegments(string $query) : array
  {
    // break into segments
    foreach($this->operators as $oper){
      // is string present?
      if(strpos($query, $oper)){
        // Split string into array
        $parts = explode($oper, $query);

        return [
          'field' => $parts[0],
          'oper' => $oper,
          'value' => $this->handleValue($parts[1])
        ];
      }
    }
  }

  protected function handleValue(string $value) : array
  {
    return strpos($value, ';') ? explode(';', $value) : [$value];
  }

  protected function handleSQL(array $segments) : string
  {
    $template = $this->sqlTemplates[$segments['oper']];

    return str_replace('{field}', $segments['field'], $template);
  }

}
