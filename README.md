# ![Prophet Brand Strategy](https://avatars3.githubusercontent.com/u/13744340?v=3&s=25) ClickToTweet Reference
***
***




This document describes the usage of the **Prophet ClickToTweet Plugin** for WordPress

	AUTHOR:					Moritz Mädler
	VERSION:	 			10
	LAST DATE OF CHANGE:	2015-08-12
	

## Prerequisites

To install the plugin you need a user that has administrative rights as well as the possibility to upload files to your webserver


## General Usage

The plugin is designed to work as a short code you can place anywhere into a post or page body
To include the plugin in a post or page body use the following shortcode: 

```
[clicktotweet]tweetable text[/clicktotweet]
```

If you want to send other text to twitter then you have marked, use the `tweet` attribute in the shortcode: 

```
[clicktotweet tweet="tweet that is actually sent to twitter"]highlighted text[/clicktotweet]
```


## Attribute Reference

You can pass in a list of additional attributes to directly modify the behaviour of the the plugin. However, it is **strongly recommended** to use the global administrative settings under "Settings" > "Prophet ClickToTweet" for modifications instead 

### Overview of available attributes

Attribute Name | Allowed Values | Description 
:-------------- | :--------------- | :----------- 
`popup_width` | Numeric Value | Width of the tweet popup
`popup_height` | Numeric Value| Height of the tweet popup
`popup_url` | Valid URL | The URL that will display the twitter tweet form
`truncate` | 0, ***1*** | Defines if the tweet should be automatically truncated to 140 chars
`truncate_url_length` | ***-1***, Numeric Value | If truncate == 1, how many chars should be additionally substracted from the tweet to make enough room to fit the backlink url in
`truncate_ellipsis` | ******, any chars | What should be used as ellipsis in case the tweet gets truncated? No HTML, length is added to the sum of substracted chars if truncate == 1

Values written in ***bold-italic*** text are the defaults

### The `popup_url` attribute in detail

The `popup_url` represents the URL of the page that will actually send the tweet The default setting is `https://twittercom/intent/tweet?text=%s&url=%s`

The `popup_url` can contain up to two wildcard characters `%s` that will be replaced as follows:

```
1st Wildcard (%s) => Tweet Text
2nd Wildcard (%s) => Backlink URL to current post/page (uses Wordpress permalink)
```

### The `truncate_url_length` attribute in detail

In case you want to add a backlink url to every tweet that is sent, this parameter defines how many additional chars should be substracted from the original tweet text to make enough room within the 140 chars to fit the URL in You can basically choose betweet two options:

```
-1 		=> determine length of URL automatically
[0-9]+ 	=> deduct a fixed amount of chars
```

The latter option is useful in case you are using URL shortener services that return URLs that always have a fixed length or in case you are using external services (eg ShareThis) that shorten the URL after the ClickToTweet link has been clicked

## Styling

In case you want to modify the appearance of the ClickToTweet links, feel free to do so by overriding the ```pbsclicktotweet``` CSS class in your themes or custom stylesheet The relevant classes/selectors are the following:

```
pbsclicktotweet, pbsclicktotweet::after, pbsclicktotweet:hover, pbsclicktotweet:hover::after
```


## Known Issues

None so far In case you find a bug please open an issue here or send a pull request in case you have made a fix already :-)

## Plugin Requirements

The plugin depends on `jQuery`[^1], but is written in a way that it dynamically loads the libraries in case they are missing
It also uses the `Themify Icon set`[^2]  that is bundled in the plugin


***
***
[^1]: <http://jquerycom>
[^2]: <http://themifyme/themify-icons>