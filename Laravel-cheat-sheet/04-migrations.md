### Migrations problems

The limit for keys is 767 bytes.

With the ```utf-8``` encoding, a single character is 3 bytes, so a key set on ```VARCHAR(255)``` length won't exceed 767 (3 * 255 = 765).

If you are using ```utf8mb4```, a single character is 4 bytes. Thus the maximum length for ```VARCHAR``` which has key on it (like ```UNIQUE```) is 191

When you encounter this :
```shell
Migration table created successfully.
Migrating: 2014_10_12_000000_create_users_table

Illuminate\Database\QueryException  : SQLSTATE[42000]: Syntax error or access violation: 1071 Specified key was too long; max key length is 767 bytes (SQL: alter table `users` add unique `users_email_unique`(`email`))
```
Change the email field length to 191.
___

### Running migrations

To run the most recents, not executed yet migrations 
```shell
php artisan migrate
```
To rollback the most recent migration
```shell
php artisan migrate:rollback
```
Setting the length of the field
```php
$table->sting('email', 191);
```
___

### Writing migrations

Creating a new migration
```shell
php artisan make:migration create_blogposts_table
```
to prefill the migration with stub code that creates a new table
```shell
php artisan make:migration create_blogposts_table --create=blogposts
```
To prefill the migration with stub code that modifies an existing table
```shell
php artisan make:migration add_date_to_blogposts_table --table=blogposts
```
___

### The migration file

Migration file contains a class with 2 methods, ```up()``` and ```down()```

The ```up()``` method creates new table, modifies the existing one. Generally, it specifies how your database schema should change from now on - this might include deleting fields or tables.

The ```down()``` method specifies how to revert changes made my migration.

```up()``` method is called when you run ```shell php artisan migrate```

```down()``` method is called when you run ```shell php artisan migrate:rollback```

Creating a new table
```php
Schema::create('blogposts', function (Blueprint $table) {
    $table->increments('id');
});
```
Renaming an existing table
```php
schema::rename('blogposts', 'posts');
```
Creating columns
```php
Schema::table('blogposts', function (Blueprint $table) {
    $table->string('title');
});
```
Available column types

Refer to this link https://laravel.com/docs/5.7/migrations#columns

Typical columns types
<table>
  <thead>
    <tr>
      <th>Command</th>
      <th>Description</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td><code>$table-&gt;increments('id');</code></td>
      <td>Auto-incrementing UNSIGNED INTEGER</td>
    </tr>
    <tr>
      <td><code>$table-&gt;string('title', 100);</code></td>
      <td>VARCHAR with optional length</td>
    </tr>
    <tr>
      <td><code>$table-&gt;timestamps();</code></td>
      <td>Nullable TIMESTAMP <code>created_at</code> and <code>updated_at</code> columns</td>
    </tr>
    <tr>
      <td><code>$table-&gt;text('content');</code></td>
      <td>TEXT</td>
    </tr>
  </tbody>
</table>

Column modifiers
```->default('value')``` - the default column value
```->nullable()``` - column can be NULL
```->unsigned()``` - integer is unsigned (no negative values)


