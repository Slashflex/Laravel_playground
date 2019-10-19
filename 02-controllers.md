### Controllers

Controllers are an alternative to Closures for defining the application logic
Generating a new Controller
```shell
php artisan make:controller ControllerName
```
Controllers are stored inside the ```app/Http/Controllers``` folder

#### Example
```php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function index()
    {
        return view('home');
    }
    
    public function contact()
    {
        return view('contact');
    }
}
```
Controllers do not have to extend the base ```App\Http\Controllers\Controller``` class, but it provides some convienience methods.
Route definition for the above controller (routes/web.php):
```php
Route::get('/', 'HomeController@home');
Route::get('/contact', 'HomeController@contact');
```
We don't have to specify the full controller namespace in ```web.php```. It's enough to specify everything after ```App\Http\Controllers```.

RouteServiceProvider is responsible for prepending the ```App\Http\Controllers``` namespace to controller names in routes.

### Single Action Controllers

For controllers that handle just a single action:
```php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

class HomeSingleController extends Controller
{
    public function __invoke()
    {
        return view('home');
    }
}
```


Route definition (routes/web.php)
```php
Route::get('home', 'HomeSingleController');
```
Generating a single action controller
```php
php artisan make:controller HomeSingleController --invokable
```
The ```_invoke``` is a magic PHP method that allows the Object to be called like a function, eg:
```php
$controller = new HomeSingleController();
$controller();
```

### Routing

Route for controller action
```php
$url = action('HomeController@home');
```
