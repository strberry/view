# 🍓 Extension: Input/Output
This is a simple template engine for [strawberry](https://github.com/elderguardian/strawberry), but you could easily use it in other frameworks.
## Installation
```
mkdir src/foundations && cd src/foundations
git clone https://github.com/strberry/view.git strawberry-view
```
### Creating your first view
#### **`src/views/greet.php`**
```
Hello, {{ $message }}
```
### Creating your first component
#### **`src/views/components/card.php`**
```
<div>
    <h3>{{ name }}</h3>
    <p>{{ $description }}</p>
</div>
```
#### Using the component inside a view
##### **`src/views/greet.php`**
```
Hello, {{ $message }}

{{ card, { "name": "John Doe", "description": "Hello World!" } }}
{{ card, { "name": " {{ $firstPerson }}", "description": "hey." } }}
```
###  Using your view in a controller
```php
class TestController extends ViewController
{
    public function __construct() {
        parent::__construct("hello");
    }

    function index() : string {
        return $this->respond([
            "message" => "world",
            "firstPerson" => "John Doe the second",
        ]);
    }
}
```