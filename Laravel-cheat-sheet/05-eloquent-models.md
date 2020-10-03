### Laravel Tinker

Tinker is a console program that allows you to play around with Laravel models and execute arbitraty PHP command directly from your command line

Starting the console
```shell php artisan tinker```

**Solving a problem with Tinker (for PHP 7.3 or newer) when it quits after every command**
Modify the php.ini by adding this option
```shell pcre.jit=0```

On Linux, to find where is ```php.ini``` is located, run this command in Terminal
```shell
php -i | grep 'php.ini'
``` 
To be able to edit this file you should do so as root (your password will be required to be able to write on this file)
```
sudo nano /etc/php/7.3/cli/php.ini 
```
___

### Eloquent Model

Generating a new model
```shell
php artisan make:model BlogPost
```
Generating a new model with migration
```shell
php artisan make:model BlogPost --migration
// or
php artisan make:model BlogPost -m
```
By convention, Laravel assumes the table name is "snake case" plural model name. For example :
<table>
  <thead>
    <tr>
      <th>Model name</th>
      <th>Table name</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td>BlogPost</td>
      <td><code>blog_posts</code></td>
    </tr>
    <tr>
      <td>Blogpost</td>
      <td><code>blogposts</code></td>
    </tr>
    <tr>
      <td>VeryLongTrain</td>
      <td><code>very_long_trains</code></td>
    </tr>
  </tbody>
</table>

To define a custom name, add (override) the protected ```$table``` model property
```php
class BlogPost extends Model
{
    protected $table = 'blogposts';
}
```
By default, all models are stored inside the ```App``` namespacce, eg. ```App\BlogPost```

**Acessing and modifying properties**
You can read and modify model properties (row columns) using properties
```php
$post = App\BlogPost::find(1);
$title = $post->title;
$content = $post->content;

$post->title = 'New title';
$post->content = 'New content';
// Always call save() to create the record and UPDATE the existing one
$post->save();
```
Property name corresponds to the columns names of the table
___

### Querying

Retrieving all models as *collection*
```php
$posts = App\BlogPost::all();
```
Retrieving single model by primary key (usually, by ```id``` property/column)
```php
// Fetch BlogPost with id 10
$post = App\BlogPost::find(10);
```
Fetching collection of models by ```id```
```php
$posts = App\BlogPost::find([1, 2, 3]);
```
Collections are iterable (eg. using ```foreach```)
```php
$posts = App\BlogPost::all();

foreach ($posts as $post) {
	echo $post->title;
}
```
Getting first element of the collection of models
```php
$posts = App\BlogPost::all();
$post = $posts->first();
```
Creating and saving (creating a database row) a new model
```php
$post = new App\BlogPost();
$post->title = 'Title';
$post->content = 'Content';
$post->save();
```


































