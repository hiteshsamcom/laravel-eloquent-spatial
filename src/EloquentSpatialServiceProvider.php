<?php

declare(strict_types=1);

namespace MatanYadaev\EloquentSpatial;

use Doctrine\DBAL\Types\Type;
use Illuminate\Database\Connection;
use Illuminate\Database\DatabaseServiceProvider;
use Illuminate\Support\Facades\DB;

class EloquentSpatialServiceProvider extends DatabaseServiceProvider
{
  public function boot(): void
  {
    $this->publishes([
      __DIR__.'/../config/eloquent-spatial.php' => config_path('eloquent-spatial.php'),
    ]);

    /** @var Connection $connection */
    $connection = DB::connection();

    if ($connection->isDoctrineAvailable()) {
      $this->registerDoctrineTypes($connection);
    }
  }

  public function register()
  {
    $this->mergeConfigFrom(
      __DIR__.'/../config/eloquent-spatial.php',
      'eloquent-spatial'
    );
  }

  protected function registerDoctrineTypes(Connection $connection): void
  {
    # @TODO assert this
    /** @var array<string, class-string<Type>> $doctrineTypes */
    $doctrineTypes = config()->get('eloquent-spatial.doctrine_types');

    foreach ($doctrineTypes as $type => $class) {
      DB::registerDoctrineType($class, $type, $type);
      $connection->registerDoctrineType($class, $type, $type);
    }
  }
}
