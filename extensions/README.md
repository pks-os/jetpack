# Jetpack Block Editor Extensions

This directory lists extensions for the Block Editor, also known as Gutenberg,
[that was introduced in WordPress 5.0](https://wordpress.org/news/2018/12/bebo/).

## How do I develop blocks in Jetpack?

- Use the [Jetpack Docker environment](https://github.com/Automattic/jetpack/tree/master/docker#readme).
- Modify files in extensions/ directory.
- To build blocks run `yarn build-extensions` [--watch].
- Jetpack wp-admin should be your primary way to develop and test blocks
- Then test them on WPCOM before deploying.

## How do I develop with WPCOM / Calypso?
You can build blocks from the Jetpack folder to your local sandbox folder and sync the whole sandbox like you always do:

```
yarn build-extensions
  --output-path /PATH_TO_YOUR_SANDBOX/wp-content/mu-plugins/jetpack/_inc/blocks/
  --watch
```

Alternatively, if you don’t need to touch PHP files, you can build blocks in the Jetpack folder without --output-path and use rsync to push files directly to your sandbox:


```
rsync -a --delete _inc/blocks/
wpdev@YOUR_WPCOM_SANDBOX.wordpress.com:/home/wpcom/public_html/wp-content/mu-plugins/jetpack/_inc/blocks/﻿
```

Calypso loads Gutenberg from simple sites’ wp-admin in an iframe.

## BETA BLOCKS
Explain beta blocks process here

## How do I deploy to wpcom?
TODO

## Can I use Jurassic Ninja to test blocks?
Yes! Just like any other changes in Jetpack, also blocks work in Jurassic Ninja.

Simply add branch name to the URL: jurassic.ninja/create/?jetpack-beta&branch=master or use other ninjastic features.

## Extension Type

We define different types of block editor extensions:

- Blocks are available in the editor itself.
- Plugins are available in the Jetpack sidebar that appears on the right side of the block editor.

## Extension Structure

Extensions loosely follow this structure:

```
.
└── block-or-plugin-name/
	├── block-or-plugin-name.php ← PHP file where the block and its assets are registered.
	├── editor.js                ← script loaded only in the editor
	├── editor.scss              ← styles loaded only in the editor
	├── view.js                  ← script loaded in the editor and theme
	└── view.scss                ← styles loaded in the editor and theme
```

If your block depends on another block, place them all in extensions folder:

```
.
├── block-name/
└── sub-blockname/
```

Notes: 


When these lists change, they need to be synced with systems so that Team City can watch for right files:

https://opengrok.a8c.com/source/xref/trunk/bin/jetpack/build-plugin-files.php#15
https://opengrok.a8c.com/source/xref/trunk/bin/jetpack/build-plugin-files.php#54

we should document that somewhere and add link to docs above those lines in `build-plugin-files.php`
