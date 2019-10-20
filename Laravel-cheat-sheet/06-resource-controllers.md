### Controllers

Import the Model class for easier usage
```php
use App\BlogPost
// Now it can be used like this
BlogPost::all();
// Instead of 
\App\BlogPost::all();
```
Group code by responsibility, separate controllers for 2 simple static pages (HomeController), another for displaying blog posts (PostController

**Resource Controllers**
Use (per) resource controllers
```php
Route::resource('posts', 'PostController');
```
instead of
```php
Route::get('/posts', 'PostController@index')->name('blog.index');
Route::get('/posts/{id}', 'PostController@show')->name('blog.show');
```
Generating a new resource controller with all the resource methods
```shell
php artisan make:controller PostController --resource
```
<table>
  <thead>
    <tr>
      <th>Verb</th>
      <th>URI</th>
      <th>Action</th>
      <th>Route Name</th>
    </tr>
    </thead>
    <tbody>
    <tr>
      <td>GET</td>
      <td>/posts</td>
      <td>index</td>
      <td>posts.index</td>
    </tr>
    <tr>
      <td>GET</td>
      <td>/posts/create</td>
      <td>create</td>
      <td>posts.create</td>
    </tr>
    <tr>
      <td>POST</td>
      <td>/posts</td>
      <td>store</td>
      <td>posts.store</td>
    </tr>
    <tr>
      <td>GET</td>
      <td>/posts/{photo}</td>
      <td>show</td>
      <td>posts.show</td>
    </tr>
    <tr>
      <td>GET</td>
      <td>/posts/{photo}/edit</td>
      <td>edit</td>
      <td>posts.edit</td>
    </tr>
    <tr>
      <td>PUT/PATCH</td>
      <td>/posts/{photo}</td>
      <td>update</td>
      <td>posts.update</td>
    </tr>
    <tr>
      <td>DELETE</td>
      <td>/posts/{photo}</td>
      <td>destroy</td>
      <td>posts.destroy</td>
    </tr>
  </tbody>
</table>

To enable only certain routes
```php
Route::resource('posts', 'PostController')->only(['index', 'show']);
```
To disable specific routes
```php
Route::resource('posts', 'PostController')->except(['create', 'store', 'edit', 'update', 'destroy]);
```
*Both examples above will result in the same routes -* ```posts.index``` *and* ```posts.show```

**Fetching a single model**

Model can be fetched using ```BlogPost::find($id)```

To display a 404 Not Found page when model cannot be found, use ```BlogPost::findOrFail($id```

**Route Model Binding**

Those 2 examples are equivalent
```php
PostController extends Controller {
	public function show($post) {
		return view('post.show', ['post' => BlogPost::findOrFail($id)]);
	}
}
```
Above, we manually try to fetch the BlogPost model. ```findOrFail``` will show a 404 page if model is not found.
```php
PostController extends Controller {
	public function show(BlogPost $post) {
		return view('post.show', ['post' => $post]);
	}
}
```
Above we use **Route Model Binding**. If the method parameter name matches the route segment, eg. route is ```/posts/{post}``` the variable name has to be ```$post```.

To customize the property by which the model is fetched, add the ```getRouteKeyName()``` method to the model.
```php
public function getRouteKeyName()
{
    return 'slug';
}
```
With the example above, Laravel would try to find a ```BlogPost``` model by ```slug``` property.




