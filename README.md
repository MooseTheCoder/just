# Just

Just is a stupidly simple way to make an API. No bloat, no guff.

Want to add database stuff? Go ahead. Want auth? fine by me.

This is for defining endpoints, and sending responses to them.


### Use

- Download this repo.
- Add your endpoints into index.php
- Add controllers into ./Controller

##### Defining endpoints

```php
    $api->get('/my/new/endpoint', 'My\Controller\Name@MyFunctionName')
```

##### Supported methods
```php
    $allowedMethods = ['get', 'post', 'put', 'delete', 'patch'];
```

#### Handling user input

Just\Request::input() will return all input data, including dyanmic URL params.

#### Responding

Just\Response::json([DATA], CODE) will return JSON to the user with a defined response code.

```php
    Response::json(['success'=>'User created!']); // Defaults to 200
    Response::json(['error'=>'User not foun !'], 404);
```

#### Protected endpoints

Just\Guard exists. Add your logic to secure an endpoint in Just\Guard::protect();

Call it at the start of your endpoints function if it needs protected.

#### 404ing

Set a route not found message using

```php
    $api->setNotFound(['error'=>'Not found!']); // Defaults to ['error'=>'Route not found']
```

-----

#### Why would someone want this when Slim / Comet / -Insert Project Name here- exists?

Sometimes you just don't need guff. Larger projects are amazing, well maintained etc, but if I am replicating an onboard

#### Example use cases?

One great example is what I originally built this for. I work with a good number of hardware vendors, they all have their own APIs that we need access to for testing / development. Hardware ain't cheap... but making a fake API is.
