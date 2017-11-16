<?php

namespace EADS\Filters;

class FilterTest extends \TestCase
{
  protected $segmentCases = [
    "id==1234" => [
      'field' => 'id',
      'oper' => '==',
      'value' => ['1234']
    ],
    "id!=1234" => [
      'field' => 'id',
      'oper' => '!=',
      'value' => ['1234']
    ],
    "id>=<400;600" => [
      'field' => 'id',
      'oper' => '>=<',
      'value' => ['400', '600']
    ],
    "id>=1234" => [
      'field' => 'id',
      'oper' => '>=',
      'value' => ['1234']
    ],
    "id<=1234" => [
      'field' => 'id',
      'oper' => '<=',
      'value' => ['1234']
    ],
    "id><400;600" => [
      'field' => 'id',
      'oper' => '><',
      'value' => ['400', '600']
    ],
    "id>1234" => [
      'field' => 'id',
      'oper' => '>',
      'value' => ['1234']
    ],
    "id<1234" => [
      'field' => 'id',
      'oper' => '<',
      'value' => ['1234']
    ]
  ];

  protected $sqlCases = [
    "id==1234" => [
      "sql" => "id = ?",
      "bindings" => ['1234']
    ],
    "id!=1234" => [
      "sql" => "id != ?",
      "bindings" => ['1234']
    ],
    "id>=<400;600" => [
      "sql" => "id BETWEEN ? AND ?",
      "bindings" => ['400', '600']
    ],
    "id>=1234" => [
      "sql" => "id >= ?",
      "bindings" => ['1234']
    ],
    "id<=1234" => [
      "sql" => "id <= ?",
      "bindings" => ['1234']
    ],
    "id><400;600" => [
      "sql" => "id > ? AND id < ?",
      "bindings" => ['400', '600']
    ],
    "id>1234" => [
      "sql" => "id > ?",
      "bindings" => ['1234']
    ],
    "id<1234" => [
      "sql" => "id < ?",
      "bindings" => ['1234']
    ]
  ];

  public function test_it_creates_segments()
  {
    foreach($this->segmentCases as $query => $segments){
      $filter = new Filter($query);
      $this->assertEquals($filter->getSegments(), $segments);
    }
  }

  public function test_it_creates_sql()
  {
    foreach($this->sqlCases as $query => $sql){
      $filter = new Filter($query);
      $this->assertEquals($filter->getSQL(), $sql['sql']);
      $this->assertEquals($filter->getBindings(), $sql['bindings']);
    }
  }

  public function test_it_throws_exception_for_malformed_queries()
  {
    $this->expectException(FilterException::class, FilterException::PARSE);

    new Filter("id=1234");
  }
}
