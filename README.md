# Fetch

[![Software License][ico-license]](LICENSE.md)

Query `Eloquent` models easily. it is ideal for who uses Laravel as an API

## Install

Via Composer

``` bash
$ composer require rcbytes/fetch
```

On `config/app.php` 

Service Provider:
```php 
rcbytes\fetch\FetchServiceProvider::class
```
Facade:
```php 
'Fetch' => rcbytes\fetch\Fetch::class
```

## Usage

#### Controller
``` php
...

class UserController extends Controller

	public function index(Request $request)
	{
	   
	    // your criteria will overwrite criteria from Request->where  
	    $where = [ "account_id" => $request->user()->account_id ];
  		return Fetch::all(App\User::query(), $where);
	
	}
	
	public function show($id)
	{
	
	    // third parameter $key defaults to "id"
  		return Fetch::one(App\User::query(), $id);
	
	}

...
```

#### Model:

```php

class User implements AuthenticatableContract, CanResetPasswordContract
{
    ...
    public function friends()
    {
    
    	return $this->hasMany(\App\User::class);
    	
    }
    
    public function posts()
    {
    
    	return $this->hasMany(\App\Model\Post::class);
    	
    }
    ...
}
```
#### Request :

Get all `Users` with their `Posts` and `Friends` 
```
GET /user?with=["posts", "friends"]
```

Get `Users` with their `Posts` and `Friends` that `age` equals 30 
```
GET /user?with=["posts", "friends"]&where={age:30}
```

Get `Users` with their `Posts` and their `Comments` and `Friends` that have at least one `Friend` with `age` greater than 30 
```
GET /user?with=["posts.comments", "friends"]&where={"friends.age":[ ">", 30]}
```

Get `User` of `key = 1` with their `Posts`, `Friends` and `Friends of Friends`
```
GET /user/1?with=["posts", "friends.friends"]
```

#### Parameters:

- array `with`: It expects the `Models` to have the `relationship method`. Accepts `dot notation`, both `Models` come when used. eg.: `?with:["posts.comments"]`. `User::posts` and `Post::comments` methods must exist
- object|mixed `where`: It expects the `Models` to have the queried columns. eg.: `?where:{age:30}` or `?where:{age:[">",30]`
- integer `take`: takes this number of `Models`
- integer `paginate`: also known as `per page`
- integer `page`: page number

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
$ to do
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) and [CONDUCT](CONDUCT.md) for details.

## Security

If you discover any security related issues, please email lab.rafamello@gmail.com instead of using the issue tracker.

## Credits

- [Rafael Mello Campanari][link-author]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/rcbytes/fetch.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/rcbytes/fetch/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/rcbytes/fetch.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/rcbytes/fetch.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/rcbytes/fetch.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/rcbytes/fetch
[link-travis]: https://travis-ci.org/rcbytes/fetch
[link-scrutinizer]: https://scrutinizer-ci.com/g/rcbytes/fetch/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/rcbytes/fetch
[link-downloads]: https://packagist.org/packages/rcbytes/fetch
[link-author]: https://github.com/rcbytes
[link-contributors]: ../../contributors
