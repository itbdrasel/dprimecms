# DprimeCMS


| **Laravel** | **DprimeCMS** |
|-------------|---------------------|
| 10.0        | 1.0.0                |

`sourcebit/dprimecms` 

## Install

To install through Composer, by run the following steps:


### composer.json

By default, the module classes are not loaded automatically. You can autoload your modules using `psr-4`. For example:

``` json
{

    "repositories": [
          {
              "type": "vcs",
              "url": "https://github.com/itbdrasel/dprimecms",
              "options": {
                  "symlink": true
              }
          }
      ],

    "require": {
		"sourcebit/dprimecms": "1.0.x-dev"
    }

}
```



Optionally, publish the package's configuration file by running:

``` bash
php artisan vendor:publish --tag=public --force
php artisan vendor:publish --tag=config --force
```

In the $aliases array add the following facades for this package.

``` bash
 'aliases' => Facade::defaultAliases()->merge([
        'Auth' => Sourcebit\Dprimecms\Facades\Auth::class,
        'Content' => Sourcebit\Dprimecms\Facades\Content::class,
    ])->toArray(),
```

**Tip: don't forget to run `composer dump-autoload` afterwards.**

## Documentation

