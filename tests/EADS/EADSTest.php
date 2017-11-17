<?php

namespace EADS;

class EADSTest extends \TestCase
{
  public function test_it_returns_filters()
  {
    $filterString = "user==42,fullName==Montoya\,Inigo,description=@foobar";

    $filters = EADS::filters($filterString);

    $this->assertCount(3, $filters);

    foreach($filters as $filter){
      $this->assertInstanceOf(Filters\Filter::class, $filter);
    }
  }
}
