### What is Just?
---
Just is an extremely barebones REST API framework with zero dependencies used to build REST APIs.

Simple, lightweight. The way it should be.

### Why does it exist?
---
Projects like Slim / Comet / Leaf exist and are amazing and there is no reason not to use them.

I built Just to solve a personal problem. From time to time I find myself replicating API endpoints found on-board network switches, gateways and other devices that are not easy to get a hold of.

Just lets me replicate these with ease.

### Composer?
---
Nope. No package manager. Just download the repo, remove the .git folder and add your own.

---
###### Documentation Below
---

### Just
#### Api
###### `setNotFound($array)`

Set the not found message. 

```php
$api->setNotFound(['error'=>'Requested resource not found']);
```

Default value : 

```php
['error'=>'Route not found']
```
---

###### `run()`

Run the configuration and accept requests.

---

###### `get($endpoint, $handler)`

Define a new GET endpoint

```php
$api->get('/example/get', 'Controller\Example\ExampleController@getExample');
```
###### `post($endpoint, $handler)`

Define a new POST endpoint

```php
$api->post('/example/post', 'Controller\Example\ExampleController@postExample');
```
---
###### `put($endpoint, $handler)`

Define a new PUT endpoint

---

###### `delete($endpoint, $handler)`

Define a new DELETE endpoint

---

###### `patch($endpoint, $handler)`

Define a new PATCH endpoint

---
#### Environment
###### `get($var)`

Get environment variables defined in a .env file

```
#.env
NAME=Just
```

```php
\Just\Environment::get('NAME'); // Just
```
---
#### Guard
###### `protect()`

An empty function to your own code to protect your endpoints.

---
#### Request
###### `uri()`

Returns the current request URI without query params.

---
###### `method()`

Returns the current request method

---
###### `input($assoc=false)`

Returns all data input to the request from `php://input`, `$_GET`, `$_POST` and dynamic request variables.

If $assoc is true, data will be returned as an assoc array, not an object.

```php
$api->get('/example/user/{userId}/details', 'Controller\Example\ExampleController@getUserDetails')
```

`GET mywebapp.domain/example/user/70/details?filter=id,name&sensitive=false

```php
public function getUserDetails(){
	\Just\Request::input()->userId; // "70"
	\Just\Request::input()->filter; // "id,name"
	\Just\Request::input()->sensitive; // "false"
}
```

---
###### `requires()`

Ensure request includes all required parameters from all sources.

```php
public function myRequestHandler(){
	\Just\Request::requires([
		'username',
		'password'
	]);
}
```
---
#### Response
###### `json($data, $code=200)`

Return a JSON response with response code.

```php
\Just\Response::json([
	'message'=>'User Found!', 'data'=>$userData
]);
```
