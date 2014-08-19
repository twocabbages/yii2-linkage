##[Live Demo](http://121.40.80.24/?r=linkage)

## Install
```
composer require cabbage/yii2-linkage: "dev-master"
```

## Applying Migrations
```
$php yii migrate/up --migrationPath=yourMigrationsDir/migrations
```

## To access the module, you need to add this to your application configuration:
```
'modules' => [
    'linkage' => [
        'class' => 'cabbage\linkage\Module',
    ],
]

```
## Visit:
```
/index.php?r=linkage/default/index
```