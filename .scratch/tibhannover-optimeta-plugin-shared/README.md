**OPTIMETA OJS Plugin Shared Library**

Shared Library for the OPTIMETA OJS Plugins

This library will be used in the following OPTIMETA OJS Plugins repositories: 

https://github.com/TIBHannover/citationManager

https://github.com/TIBHannover/optimetaGeo

**Install / update**

Add the following to your project composer.json

```
  "repositories": [
    {
      "type": "vcs",
      "url": "https://github.com/tibhannover/optimeta-plugin-shared.git"
    }
  ],
```

```
"require": {
  "tibhannover/optimeta": "dev-main"
}
```
OR if you want to use a specific version
```  
"require": {
  "tibhannover/optimeta": "^v1.0.0"
}
```
Execute the following commands

to install
```
composer install
```
or to update
```
composer update
```

Autoloading is defined in the composer.json of this library. 
Execute the following to update the composer autoload:
```
composer dump-autoload
```
