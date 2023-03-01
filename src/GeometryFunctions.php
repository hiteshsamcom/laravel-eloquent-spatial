<?php

declare(strict_types=1);

namespace MatanYadaev\EloquentSpatial;

use Illuminate\Contracts\Database\Query\Expression as ExpressionContract;
use Illuminate\Database\Connection;
use Illuminate\Support\Facades\DB;
use MatanYadaev\EloquentSpatial\Objects\Geometry;
use MatanYadaev\EloquentSpatial\Objects\MultiPoint;
use MatanYadaev\EloquentSpatial\Objects\Polygon;

class GeometryFunctions
{
  public function __construct(protected ?string $connection = null)
  {
    //
  }

  public static function make(?string $connection = null): self
  {
    return new self($connection);
  }

  protected function getConnection(): Connection
  {
    return DB::connection($this->connection);
  }

  public function convexHull(MultiPoint $multiPoint): Polygon
  {
    $convexHullWkb = $this->getConnection()
      ->query()
      ->selectRaw(
        sprintf(
          'ST_CONVEXHULL(%s) as result',
          $this->toExpressionString($multiPoint),
        )
      )->value('result');

    assert(is_string($convexHullWkb));

    return Polygon::fromWkb($convexHullWkb);
  }

  protected function toExpressionString(Geometry|string|ExpressionContract $geometry): string
  {
    return toExpressionString($geometry, $this->getConnection());
  }
}
