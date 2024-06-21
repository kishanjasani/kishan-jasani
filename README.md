## Kishan Jasani Backend Task

A plugin that retrieves data from a remote API endpoint, and makes that data accessible from an API endpoint on the WordPress site. The data will be displayed via a custom block and on an admin WordPress page.

## Installation

Download the plugin and install it on your site from plugin page.

## Development

Clone the repo

``` bash
git clone git@github.com:kishanjasani/kishan-jasani.git

cd kishan-jasani

npm install

npm run start
```

If you add additional class to the plugin then don't forget to run
```bash
composer du
```

To create new block follow this steps:
- Go to blocks directory inside the plugin and run following command:

```bash
npx @wordpress/create-block@latest block-name --no-plugin

npm run start
```

To create a production build of assets:

```bash
npm run build
```

## WP CLI command

Use this command to flush transient cache.

```bash
wp kj flush
```
