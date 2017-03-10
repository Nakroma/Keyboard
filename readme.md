[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)

## Description
Keyboard is a lightweight, really basic board software which features an invite-only (per generated keys) system and random anonymous nicknames in each thread (think 4chan ID's).  
However, these things can easily be changed so it can be transformed into a traditional board.  
Keyboard is written in Laravel 5.4

## Installation

1. Fork/Clone/Download this project
2. Run `php artisan migrate`
3. Run `php artisan serve`
4. Register your admin account with the key `ADMIN_KEY`

## Configuration
You can find most of the config in `config/_custom.php`.  
You can change pagination limits, add new callsigns and change groups or permissions.  
A few notes about groups and permissions:

* The lowest group is always the one that stands for permanently banned (can't access the board at all).
* All groups under 0 may have restrictions (It is assumed that everything under 0 is not a full user).
* The highest group is always the one that gets assigned on use with your admin key.  

If you want to remove the invite only access: Remove everything regarding keys in the register view and controller, as well as in the profile view and profile controller.  
If you want to remove the anonymous callsigns: Simply change the `$callnames[$xyz->author]` to `$xyz->username` and remove the comments around ->username assignment in the thread controller.