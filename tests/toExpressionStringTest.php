<?php

use Illuminate\Support\Facades\DB;
use MatanYadaev\EloquentSpatial\AxisOrder;
use MatanYadaev\EloquentSpatial\Enums\Srid;
use MatanYadaev\EloquentSpatial\Objects\Point;
use function MatanYadaev\EloquentSpatial\toExpressionString;

it('toExpressionString can handle an Expression input', function (): void {
  $connection = DB::connection();

  $result = toExpressionString(DB::raw('POINT(longitude, latitude)'), $connection);

  expect($result)->toBe('POINT(longitude, latitude)');
});

it('toExpressionString can handle a Geometry input', function (): void {
  $connection = DB::connection();
  $point = new Point(0, 180, Srid::WGS84->value);

  $result = toExpressionString($point, $connection);

  $expected = "ST_GeomFromText('POINT(180 0)', 4326, 'axis-order=long-lat')";
  expect($result)->toBe($expected);
})->skip(fn () => ! (new AxisOrder)->supported(DB::connection()));

it('toExpressionString can handle a Geometry input - without axis-order', function (): void {
  $connection = DB::connection();
  $point = new Point(0, 180, Srid::WGS84->value);

  $result = toExpressionString($point, $connection);

  $expected = "ST_GeomFromText('POINT(180 0)', 4326)";
  expect($result)->toBe($expected);
})->skip(fn () => (new AxisOrder)->supported(DB::connection()));

it('toExpressionString can handle a string input', function (): void {
  $connection = DB::connection();

  $result = toExpressionString('test_places.point', $connection);

  expect($result)->toBe('`test_places`.`point`');
});
