<?php

namespace EADS\Filters;

class Parser
{
    protected $escapedChars = [
        '$$SEMICOLON$$' => '\;',
        '$$COMMA$$' => '\,',
        '$$BACKSLASH$$' => '\\'
    ];

    protected $filterQuery;

    protected $queries;

    protected $filters;

    public function __construct(string $filterQuery)
    {
        $this->filterQuery = $filterQuery;

        $this->queries = $this->parseFilterQueries($filterQuery);

        $this->filters = array_map(function($query){
            return new Filter($query);
        }, $this->queries);
    }

    /**
    * Get an array of individual query strings
    * @return array Query strings
    */
    public function getQueries() : array
    {
        return $this->queries;
    }

    /**
    * Get an array of individual query Filter
    * @return array Filters
    */
    public function getFilters() : array
    {
        return $this->filters;
    }

    protected function parseFilterQueries(string $filterQuery) : array
    {
        $parsed = array_map(function($query){
                return $this->placeholderToEscaped($query);
            },explode(',', $this->escapedToPlaceholder($filterQuery))
        );

        // Remove escapes
        return array_map(function($query){
            return $this->removeEscapes($query);
        }, $parsed);
    }

    protected function escapedToPlaceholder(string $filterQuery) : string
    {
        foreach($this->escapedChars as $placeholder => $char){
            $filterQuery = str_replace($char, $placeholder, $filterQuery);
        }

        return $filterQuery;
    }

    protected function placeholderToEscaped(string $filterQuery) : string
    {
        foreach($this->escapedChars as $placeholder => $char){
            $filterQuery = str_replace($placeholder, $char, $filterQuery);
        }

        return $filterQuery;
    }

    protected function removeEscapes(string $query) : string
    {
        // Remove escaped semicolons
        $query = str_replace('\;', ';', $query);

        // Remove escaped commas
        $query = str_replace('\,', ',', $query);

        // Remove escaped backslash
        $query = str_replace('\\\\', '\\', $query);

        return $query;
    }
}
