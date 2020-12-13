# simpleSAMLphp season module

## How To Apply

- download module

```bash
cd /path/to/simplesaml/modules/
git clone https://github.com/season-framework/simplesamlphp-module-season season
```

- change config file, `config/config.php`

```php
// ...
'theme.use' => 'season:season',
// ...
```

- change logo & favicon

```bash
cd /path/to/simplesaml/modules/season/www/res/
wget https://your.logo.url -O logo.png
wget https://your.icon.url -O icon.ico
```

## License

See the [LICENSE](https://github.com/season-framework/simplesamlphp-module-season/blob/master/LICENSE) file.