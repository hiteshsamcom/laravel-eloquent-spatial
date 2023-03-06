<?php

declare(strict_types=1);

namespace MatanYadaev\EloquentSpatial;

use Geometry as geoPHPGeometry;
use GeometryCollection as geoPHPGeometryCollection;
use geoPHP;
use InvalidArgumentException;
use LineString as geoPHPLineString;
use MatanYadaev\EloquentSpatial\Objects\Geometry;
use MatanYadaev\EloquentSpatial\Objects\GeometryCollection;
use MatanYadaev\EloquentSpatial\Objects\LineString;
use MatanYadaev\EloquentSpatial\Objects\MultiLineString;
use MatanYadaev\EloquentSpatial\Objects\MultiPoint;
use MatanYadaev\EloquentSpatial\Objects\MultiPolygon;
use MatanYadaev\EloquentSpatial\Objects\Point;
use MatanYadaev\EloquentSpatial\Objects\Polygon;
use MultiLineString as geoPHPMultiLineString;
use MultiPoint as geoPHPMultiPoint;
use MultiPolygon as geoPHPMultiPolygon;
use Point as geoPHPPoint;
use Polygon as geoPHPPolygon;

class Factory
{
  public static function parse(string $value): Geometry
  {
    try {
      /** @var geoPHPGeometry|false $geoPHPGeometry */
      $geoPHPGeometry = geoPHP::load($value);
    } finally {
      if (! isset($geoPHPGeometry) || ! $geoPHPGeometry) {
        throw new InvalidArgumentException('Invalid spatial value');
      }
    }

    return self::createFromGeometry($geoPHPGeometry);
  }

  protected static function createFromGeometry(geoPHPGeometry $geometry): Geometry
  {
    $srid = is_int($geometry->getSRID()) ? $geometry->getSRID() : 0;

    if ($geometry instanceof geoPHPPoint) {
      if ($geometry->coords[0] === null || $geometry->coords[1] === null) {
        throw new InvalidArgumentException('Invalid spatial value');
      }

      $pointClass = self::getPointClass();

      return new $pointClass($geometry->coords[1], $geometry->coords[0], $srid);
    }

    /** @var geoPHPGeometryCollection $geometry */
    $components = collect($geometry->components)
      ->map(static function (geoPHPGeometry $geometryComponent): Geometry {
        return self::createFromGeometry($geometryComponent);
      });

    if ($geometry::class === geoPHPMultiPoint::class) {
      $multiPointClass = self::getMultiPointClass();

      return new $multiPointClass($components, $srid);
    }

    if ($geometry::class === geoPHPLineString::class) {
      $lineStringClass = self::getLineStringClass();

      return new $lineStringClass($components, $srid);
    }

    if ($geometry::class === geoPHPPolygon::class) {
      $polygonClass = self::getPolygonClass();

      return new $polygonClass($components, $srid);
    }

    if ($geometry::class === geoPHPMultiLineString::class) {
      $multiLineStringClass = self::getMultiLineStringClass();

      return new $multiLineStringClass($components, $srid);
    }

    if ($geometry::class === geoPHPMultiPolygon::class) {
      $multiPolygonClass = self::getMultiPolygonClass();

      return new $multiPolygonClass($components, $srid);
    }

    $geometryCollectionClass = self::getGeometryCollectionClass();

    return new $geometryCollectionClass($components, $srid);
  }

  /**
   * @return class-string<Point>
   *
   * @throws InvalidArgumentException
   */
  public static function getPointClass(): string
  {
    $pointClass = config()->get('eloquent-spatial.geometries.point');

    if (! class_exists($pointClass) or ! is_a($pointClass, Point::class, allow_string: true)) {
      // @TODO change exception type and message
      // @TODO add test (use dataset)
      // @TODO find a better place to store these methods
      throw new InvalidArgumentException('Invalid point class.');
    }

    return $pointClass;
  }

  /**
   * @return class-string<LineString>
   *
   * @throws InvalidArgumentException
   */
  public static function getLineStringClass(): string
  {
    $lineStringClass = config()->get('eloquent-spatial.geometries.line_string');

    if (! class_exists($lineStringClass) or ! is_a($lineStringClass, LineString::class, allow_string: true)) {
      throw new InvalidArgumentException('Invalid line string class.');
    }

    return $lineStringClass;
  }

  /**
   * @return class-string<Polygon>
   *
   * @throws InvalidArgumentException
   */
  public static function getPolygonClass(): string
  {
    $polygonClass = config()->get('eloquent-spatial.geometries.polygon');

    if (! class_exists($polygonClass) or ! is_a($polygonClass, Polygon::class, allow_string: true)) {
      throw new InvalidArgumentException('Invalid polygon class.');
    }

    return $polygonClass;
  }

  /**
   * @return class-string<MultiPoint>
   *
   * @throws InvalidArgumentException
   */
  public static function getMultiPointClass(): string
  {
    $multiPointClass = config()->get('eloquent-spatial.geometries.multi_point');

    if (! class_exists($multiPointClass) or ! is_a($multiPointClass, MultiPoint::class, allow_string: true)) {
      throw new InvalidArgumentException('Invalid multi point class.');
    }

    return $multiPointClass;
  }

  /**
   * @return class-string<MultiLineString>
   *
   * @throws InvalidArgumentException
   */
  public static function getMultiLineStringClass(): string
  {
    $multiLineStringClass = config()->get('eloquent-spatial.geometries.multi_line_string');

    if (! class_exists($multiLineStringClass) or ! is_a($multiLineStringClass, MultiLineString::class, allow_string: true)) {
      throw new InvalidArgumentException('Invalid multi line string class.');
    }

    return $multiLineStringClass;
  }

  /**
   * @return class-string<MultiPolygon>
   *
   * @throws InvalidArgumentException
   */
  public static function getMultiPolygonClass(): string
  {
    $multiPolygonClass = config()->get('eloquent-spatial.geometries.multi_polygon');

    if (! class_exists($multiPolygonClass) or ! is_a($multiPolygonClass, MultiPolygon::class, allow_string: true)) {
      throw new InvalidArgumentException('Invalid multi polygon class.');
    }

    return $multiPolygonClass;
  }

  /**
   * @return class-string<GeometryCollection>
   *
   * @throws InvalidArgumentException
   */
  public static function getGeometryCollectionClass(): string
  {
    $geometryCollectionClass = config()->get('eloquent-spatial.geometries.geometry_collection');

    if (! class_exists($geometryCollectionClass) or ! is_a($geometryCollectionClass, GeometryCollection::class, allow_string: true)) {
      throw new InvalidArgumentException('Invalid geometry collection class.');
    }

    return $geometryCollectionClass;
  }
}
