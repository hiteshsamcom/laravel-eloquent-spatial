<?php

declare(strict_types=1);

use MatanYadaev\EloquentSpatial\Doctrine\GeometryCollectionType;
use MatanYadaev\EloquentSpatial\Doctrine\LineStringType;
use MatanYadaev\EloquentSpatial\Doctrine\MultiLineStringType;
use MatanYadaev\EloquentSpatial\Doctrine\MultiPointType;
use MatanYadaev\EloquentSpatial\Doctrine\MultiPolygonType;
use MatanYadaev\EloquentSpatial\Doctrine\PointType;
use MatanYadaev\EloquentSpatial\Doctrine\PolygonType;
use MatanYadaev\EloquentSpatial\Objects\GeometryCollection;
use MatanYadaev\EloquentSpatial\Objects\LineString;
use MatanYadaev\EloquentSpatial\Objects\MultiLineString;
use MatanYadaev\EloquentSpatial\Objects\MultiPoint;
use MatanYadaev\EloquentSpatial\Objects\MultiPolygon;
use MatanYadaev\EloquentSpatial\Objects\Point;
use MatanYadaev\EloquentSpatial\Objects\Polygon;

return [

  'geometries' => [
    'point' => Point::class,
    'line_string' => LineString::class,
    'multi_point' => MultiPoint::class,
    'polygon' => Polygon::class,
    'multi_line_string' => MultiLineString::class,
    'multi_polygon' => MultiPolygon::class,
    'geometry_collection' => GeometryCollection::class,
  ],

  'doctrine_types' => [
    'point' => PointType::class,
    'linestring' => LineStringType::class,
    'multipoint' => MultiPointType::class,
    'polygon' => PolygonType::class,
    'multilinestring' => MultiLineStringType::class,
    'multipolygon' => MultiPolygonType::class,
    'geometrycollection' => GeometryCollectionType::class,
    'geomcollection' => GeometryCollectionType::class,
  ],

];
