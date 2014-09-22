# NOTE: This functionality is now a part of [Twitter Plugin](https://dukt.net/craft/twitter) since version 0.9.10

Still, there are differences, and you might like this one better.

# Craft Twig filter: expand links in tweets

Craft twig filter to expand urls, hashtags, user mentions in tweets. Works with [Twitter Plugin](https://dukt.net/craft/twitter)

## Installation

- Unzip file
- Place the 'tweetlinker' folder into your craft/plugins directory
- Install plugin in the Craft Control Panel under Settings > Plugins
- Install [Twitter Plugin](https://dukt.net/craft/twitter)

## Usage

- Fetch tweet with `craft.twitter.get`
- `{{tweet|tweetLink}}` is the tweet with urls etc. expanded
- `{{...|raw}}` so the link html is not escaped

### Example

```
{% set tweets = craft.twitter.get('statuses/user_timeline.json?screen_name=marionnewlevant&count=5') %}
{% for tweet in tweets %}
  {{tweet|tweetLink|raw}}
{% endfor %}
```

## Features

- Use as filter `tweet|tweetLink`
- Use as function `tweetLink(tweet)`
- filter/function argument `newWindow` (default false): include `target="_blank"` to open urls in new window?
- filter/function argument `title` (default false): include title attribute in url?
