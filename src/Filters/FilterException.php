<?php

namespace EADS\Filters;

use InvalidArgumentException;

class FilterException extends InvalidArgumentException {

  const PARSE = "The query could no be parsed.";
}
