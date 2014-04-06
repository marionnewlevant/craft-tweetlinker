<?php
/**
 * Expand links in tweets: plugin for Craft CMS
 *
 * @package   Tweet Linker
 * @author    Marion Newlevant
 * @copyright Copyright (c) 2014
 * @link      https://github.com/marionnewlevant/craft-tweetlinke
 * @license   MIT
 */
namespace Craft;

class TweetLinkerPlugin extends BasePlugin
{
    function getName()
    {
         return Craft::t('Tweet Linker');
    }

    function getVersion()
    {
        return '0.1';
    }

    function getDeveloper()
    {
        return 'Marion Newlevant';
    }

    function getDeveloperUrl()
    {
        return 'http://marion.newlevant.com';
    }

    function addTwigExtension()
    {
        Craft::import('plugins.tweetlinker.twigextensions.TweetLinkerTwigExtension');
        return new TweetLinkerTwigExtension();
    }
}
