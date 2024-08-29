# hmvc Setting module as a package

- ✅ Make sure you install nwidart/laravel-modules
- ✅ Install this package   
- ✅ Publish package folders
- ✅ And then You can deal with it as a hmvc module
    

### How to setup

```bash
#dont forget to install nwidart/laravel-modules 
composer require nwidart/laravel-modules

#install Setting_module package 
composer require magdasaif/settings_module:dev-dev

# publish module folders
php artisan settings:publish

#don't forget to do brlow commands
composer dump
php artisan optimize

# then start to browse module routes and access them
```
