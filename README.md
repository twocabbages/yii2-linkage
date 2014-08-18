## Applying Migrations
```
$php yii migrate/up --migrationPath=yourMigrationsDir/migrations
```

## To access the module, you need to add this to your application configuration:
```
'modules' => [
    'linkage' => [
        'class' => 'cabbage\modules\linkage\Module',
    ],
]

```
## Visit:
```
/index.php?r=linkage/default/index
```