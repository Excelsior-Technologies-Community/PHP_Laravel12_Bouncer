# PHP_Laravel12_Bouncer

# Step 1: Install Laravel 12 Create Project
```php
We create a fresh Laravel 12 project to implement Role & Permission Management using Bouncer Package.
```

Run Command
```php
composer create-project laravel/laravel:^12.0 PHP_Laravel12_Bouncer
```
Move to Project Folder
```php
cd PHP_Laravel12_Bouncer
```

Generate Application Key
```php
php artisan key:generate
```

# Step 2: Setup Database (.env File)
Open .env file and configure database:
```php
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3307
DB_DATABASE=laravel12_bouncer
DB_USERNAME=root
DB_PASSWORD=
```
Run Default Migration
```php
php artisan migrate
```

# Step 3: Install Laravel Bouncer Package
Bouncer is used for:
```php
           - Role Management
           - Permission Management
           - Ability Control
```
Install Package
 ```php
composer require silber/bouncer
```

# Step 4: Publish Bouncer Migration Files
```php
php artisan vendor:publish --tag="bouncer.migrations"
```
This will create:
```php
- roles table
- abilities table
- assigned_roles table
- permissions table
```

# Step 5: Run Bouncer Migration
```php
php artisan migrate
```
Now database is ready for role & permission system.

# Step 6: Add Bouncer Trait in User Model
Open File
```php
app/Models/User.php
```

Add Code
```php
<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Silber\Bouncer\Database\HasRolesAndAbilities;

class User extends Authenticatable
{
    use HasRolesAndAbilities;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];
}
```

# Step 7: Create Role & Ability Seeder
Create Seeder
```php
php artisan make:seeder RoleAbilitySeeder
```
Open File
```php
database/seeders/RoleAbilitySeeder.php
```
Add Code
```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Silber\Bouncer\BouncerFacade as Bouncer;
use App\Models\User;

class RoleAbilitySeeder extends Seeder
{
    public function run(): void
    {
        // Roles
        $admin = Bouncer::role()->firstOrCreate([
            'name' => 'admin',
            'title' => 'Administrator',
        ]);

        $user = Bouncer::role()->firstOrCreate([
            'name' => 'user',
            'title' => 'User',
        ]);

        // Abilities
        Bouncer::ability()->firstOrCreate([
            'name' => 'manage-users',
            'title' => 'Manage Users',
        ]);

        Bouncer::ability()->firstOrCreate([
            'name' => 'view-dashboard',
            'title' => 'View Dashboard',
        ]);

        // Assign abilities
        Bouncer::allow($admin)->to(['manage-users', 'view-dashboard']);
        Bouncer::allow($user)->to(['view-dashboard']);

        // Assign role to first user
        $firstUser = User::first();
        if ($firstUser) {
            Bouncer::assign('admin')->to($firstUser);
        }
    }
}
```
# Step 8: Protect Routes Using Permission
Open
```php
routes/web.php
```
Add Route
```php
<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Welcome Page
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| Dashboard (ONLY ONE)
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

/*
|--------------------------------------------------------------------------
| Profile Routes
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| Bouncer / Permission Example
|--------------------------------------------------------------------------
*/
Route::get('/users', function () {
    return 'User Management Page';
})->middleware('can:manage-users');

require __DIR__.'/auth.php';
```
# Step 9: Run Laravel Project
```php
php artisan serve
```
Open Browser
```php
http://127.0.0.1:8000/register
```
<img width="1351" height="674" alt="image" src="https://github.com/user-attachments/assets/3ec37114-8883-4470-b5bd-4577e4adb4cc" />

```php
http://127.0.0.1:8000/login
```
<img width="1339" height="678" alt="image" src="https://github.com/user-attachments/assets/6bd57e1b-f09a-4a8f-892d-34c6a75674a4" />

```php
http://127.0.0.1:8000/deshboerd
```
<img width="1321" height="667" alt="image" src="https://github.com/user-attachments/assets/915d0bcf-68ae-48bb-8f11-e2704bed9dc4" />

# Project Folder Structure
```php
PHP_Laravel12_Bouncer
├── app
│   ├── Models
│   │   └── User.php
│
├── database
│   ├── seeders
│   │   └── RoleAbilitySeeder.php
│
├── routes
│   └── web.php
│
├── .env
├── artisan
```

# Explanation
```php
- Role Based Access Control (RBAC)
- Ability Based Permission Checking
- Database Driven Authorization
- Secure Route Protection
- Enterprise Level Permission System
```





