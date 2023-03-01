<?php

declare(strict_types=1);

namespace MatanYadaev\EloquentSpatial;

use Illuminate\Contracts\Database\Query\Expression as ExpressionContract;
use Illuminate\Database\Connection;
use Illuminate\Support\Facades\DB;
use MatanYadaev\EloquentSpatial\Objects\Geometry;

function toExpressionString(
  ExpressionContract|Geometry|string $geometry,
  Connection $connection,
): string
{
  $grammar = $connection->getQueryGrammar();

  if ($geometry instanceof ExpressionContract) {
    $expression = $geometry;
  } elseif ($geometry instanceof Geometry) {
    $expression = $geometry->toSqlExpression($connection);
  } else {
    $expression = DB::raw($grammar->wrap($geometry));
  }

  return (string) $expression->getValue($grammar);
}
