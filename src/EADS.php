<?php

namespace EADS;

class EADS
{
    /**
    * Returns an array of EADS\Filters\Filter as defined in the $filterString.
    * @param  string $filterString EADS compliant filter query
    * @return array                Array of EADS\Filters\Filter
    * @throws  EADS\Filters\FilterException
    */
    public static function filters(string $filterString) : array
    {
        return (new Filters\Parser($filterString))->getFilters();
    }
}
