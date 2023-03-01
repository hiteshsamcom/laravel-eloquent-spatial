<?php

use Illuminate\Foundation\Testing\DatabaseMigrations;
use MatanYadaev\EloquentSpatial\GeometryFunctions;
use MatanYadaev\EloquentSpatial\Objects\MultiPoint;
use MatanYadaev\EloquentSpatial\Objects\Polygon;

uses(DatabaseMigrations::class);

it('creates a convex hull from a MultiPoint', function (): void {
  $multiPoint = MultiPoint::fromJson('{"type":"MultiPoint","coordinates":[[-1,-1],[1,-1],[1,1],[-1,1],[-1,-1],[0,0]]}');

  $convexHullPolygon = GeometryFunctions::make()->convexHull($multiPoint);


  $actualPolygonCoordinates = $convexHullPolygon->getCoordinates();
  $expectedPolygonCoordinatesUnsorted = [
    [
      [-1, -1],
      [-1, 1],
      [1, -1],
      [1, 1],
      [-1, -1],
    ]
  ];
  expect($actualPolygonCoordinates)->toEqualCanonicalizing($expectedPolygonCoordinatesUnsorted);
});
