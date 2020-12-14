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

// title string
'theme.title' => 'season AUTH',
// favicon url
'theme.icon' => 'https://auth.season.co.kr/simplesaml/module.php/season/res/icon.ico',
// logo url
'theme.logo' => 'https://auth.season.co.kr/simplesaml/module.php/season/res/logo.png',
// footer string
'theme.footer' => 'Copyright &copy; 2020 <a href="https://www.season.co.kr">Season Inc.</a> All Rights Reserved.',
// ...
```

## License

See the [LICENSE](https://github.com/season-framework/simplesamlphp-module-season/blob/master/LICENSE) file.