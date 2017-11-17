<?php

namespace EADS\Filters;

class ParserTest extends \TestCase
{
    public function test_it_parses_two_queries()
    {
        $filter = "id==1234,user==42";

        $parser = new Parser($filter);

        $this->assertEquals($parser->getQueries(),[
            'id==1234',
            'user==42'
        ]);
    }

    public function test_it_parses_two_queries_with_escapable_values()
    {
        $filter = "user==42,fullName==Montoya\,Inigo";

        $parser = new Parser($filter);

        $this->assertEquals($parser->getQueries(),[
            'user==42',
            'fullName==Montoya,Inigo'
        ]);
    }

    public function test_it_parses_with_multiple_escapable_values()
    {
        $filter = "user==42,game==Army\\\\Navy,phrase=='I missed the early plane\; however\, I still made the meeting.'";

        $parser = new Parser($filter);

        $this->assertEquals($parser->getQueries(),[
            'user==42',
            "game==Army\\Navy",
            "phrase=='I missed the early plane; however, I still made the meeting.'"
        ]);
    }

    public function test_it_creates_filters()
    {
        $filter = "user==42,age>18";

        $parser = new Parser($filter);

        // Assert Count
        $this->assertEquals(2, count($parser->getFilters()));

        //Assert Type
        foreach($parser->getFilters() as $filter){
            $this->assertEquals(Filter::class, get_class($filter));
        }
    }
}
