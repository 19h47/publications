# Publications

A __WordPress__ plugin to turn your Instagram's post into WordPress posts.

![Publications](assets/screenshot.png)

## Description

__Publications__ allow you to turn your Instagram's post into a custom post type Publication.

## Installation

### Dependencies

The plugin uses an easy-to-use PHP Class for accessing [Instagram's API](https://github.com/cosenary/Instagram-PHP-API).

```
composer require cosenary/instagram
```

### Config

Locate the `config-sample.json`, fill it with your personal informations and then saving it as `config.json`.

### Plugin

- Upload the folder `publications` to the `/wp-content/plugins/` directory
- Activate the plugin through the __Plugins__ menu in WordPress

## References

- [WordPress plugin boilerplate](https://github.com/DevinVinson/WordPress-Plugin-Boilerplate)
- [Instagram Developer](https://www.instagram.com/developer/)
- [Instagram Clients Management](https://www.instagram.com/developer/clients/manage/)
- [Insert attachment from url](https://gist.github.com/m1r0/f22d5237ee93bcccb0d9)

## To do

- [Creating a custom settings page](https://developer.wordpress.org/plugins/settings/custom-settings-page/)
- Retrieve more than 20 posts