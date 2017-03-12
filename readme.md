[![Latest Version](https://img.shields.io/github/release/nakroma/keyboard.svg?style=flat-square)](https://github.com/nakroma/keyboard/releases)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)

## Description
Keyboard is a lightweight, really basic board software which features an invite-only (per generated keys) system and random anonymous nicknames in each thread (think 4chan ID's).  
However, these things can easily be changed so it can be transformed into a traditional board.  
Keyboard is written in Laravel 5.4

## Configuration
You can find most of the config in `config/_custom.php`.  
You can change pagination limits, add new callsigns and change groups or permissions.  
A few notes about groups and permissions:

* The lowest group is always the one that stands for permanently banned (can't access the board at all).
* All groups under 0 may have restrictions (It is assumed that everything under 0 is not a full user).
* The highest group is always the one that gets assigned on use with your admin key.  

If you want to remove the invite only access: Remove everything regarding keys in the register view and controller, as well as in the profile view and profile controller.  
If you want to remove the anonymous callsigns: Simply change the `$callnames[$xyz->author]` to `$xyz->username` and remove the comments around ->username assignment in the thread controller.

The entire board is styled with bootstrap, so it's pretty easy to style over it. The css is found in `public/css/`.

## Installation

1. Fork/Clone/Download this project
2. Move the directory where you want to store it
3. Rename `.env.example` to `.env` and change your settings
4. Run `php artisan key:generate`
5. Run `php artisan migrate`

**Development**

6. Run `php artisan serve`

**Production**

6. Copy the contents of `public/` where you want to serve the url
7. Change the two bootstrap paths in `index.php` to the correct path
8. Make `storage/` writable with `chmod -R o+w storage`
9. Get composer in your project root with `curl -s https://getcomposer.org/installer | php`
10. Run `php composer.phar install`
11. Run `php composer.phar dumpautoload -o`
12. Run `php artisan config:cache`
13. Run `php artisan route:cache`