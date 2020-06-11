# UI for [R.P.A.C.]

## Installation

Install the RPAC-package if you dont have it. 

### Service Provider

Add the RpacuiServiceProvider to your `config/app.php` file.

```php
'providers' => [

    // ...
    
    /**
     * Third Party Service Providers...
     */
    Codewiser\Rpac\RpacServiceProvider::class,
    Codewiser\Rpacui\RpacuiServiceProvider::class,

],
```

### Publish Controllers And Views

Publish the package files to your app using terminal:

`php artisan vendor:publish --provider="Codewiser\Rpacui\RpacuiServiceProvider"`

### Settings

Set policies for User and Roles in RPAC (see RPAC-instructions).
Customizing controllers (in App\Http\Controllers\Rpac), if you need.

### Usage

Go to `your_site.com/rpac-ui` and be happy!