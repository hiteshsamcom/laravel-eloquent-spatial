<?php

declare(strict_types=1);

namespace MatanYadaev\EloquentSpatial\Objects;

use MatanYadaev\EloquentSpatial\Factory;
use RuntimeException;

class Point extends Geometry
{
  public float $latitude;

  public float $longitude;

  public function __construct(float $latitude, float $longitude, int $srid = 0)
  {
    $this->latitude = $latitude;
    $this->longitude = $longitude;
    $this->srid = $srid;

    $pointClass = Factory::getPointClass();
    if ($pointClass !== self::class) {
      // @TODO test this
      // @TODO find a better exception class
      // @TODO write for other objects
      throw new RuntimeException("Point class must be {$pointClass}.");
    }
  }

  public function toWkt(): string
  {
    $wktData = $this->getWktData();

    return "POINT({$wktData})";
  }

  public function getWktData(): string
  {
    return "{$this->longitude} {$this->latitude}";
  }

  /**
   * @return array{0: float, 1: float}
   */
  public function getCoordinates(): array
  {
    return [
      $this->longitude,
      $this->latitude,
    ];
  }
}
