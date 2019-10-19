### Inside route definition file (routes/web.php)

Defining a route using closure
```php
Route::get('/', function () {
    return view('welcome');
});
```
Defining a route that only renders a Blade template
```php
Route::view('/home')
Route::view('/home', ['data' => 'value'])
```
Route with a required parameter
```php
Route::get('/page/{id}', function ($id) {
    return view('page', ['page' => $id]);
});
```
Route with an optional parameter
```php
Route::get('/hello/{name?}', function ($name = 'Guest') {
    return view('hello', ['name' => $name]);
});
```
Named route
```php
Route::view('/home')->name('home');
Route::get('/blog-post/{id}', function ($id) { return view('blog-post', ['id' => $id]); });
```
Generating url to the named route
```php
$url = route('home');
// Generates /home
$blogPostUrl = route('blog-post', ['id' => 1]);
// Generates /blog-post/1
```

### Inside Blade template

Defining a section
```php
@section('content')
	<h1>Header</h1>
@endsection
```
Rendering a section
```php
@yield('content')
```
Extending a layout
```php
@yield@extends('layout')
```
Function to render a view
```php
view('name', ['data' =>‚ 'value'])
```
Rendering data inside a Blade template
```php
{{ $data }}
```
By default data is escaped using ```htmlspecialchars```

Rendering unescaped data
```php
{!! $data !!}}
```
Including another view
```php
@include('view.name')
```
*Included view will inherit parent view data*

Passing additional data to included view
```php
@include('view.name', ['name' => 'John'])
```
Generating a URL inside view
```php
<a href="{{ route('home') }}">Home</a>
```





