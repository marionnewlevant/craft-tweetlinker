<?php

namespace Craft;

class TweetLinkerTwigExtension extends \Twig_Extension
{
  protected $env;

  public function getName()
  {
    return 'Tweet Linker';
  }

  public function getFilters()
  {
    return array('tweetLink' => new \Twig_Filter_Method($this, 'tweetLink'));
  }

  public function getFunctions()
  {
    return array('tweetLink' => new \Twig_Function_Method($this, 'tweetLink'));
  }

  public function initRuntime(\Twig_Environment $env)
  {
    $this->env = $env;
  }

  public function tweetLink($tweet, $newWindow = false, $title = false)
  {
    $expandedTweet = '';
    $originalTweet = $tweet['text'];
    $start = 0;
    $sortedEntities = $this->_sortEntities($tweet['entities']);
    foreach($sortedEntities as $entity)
    {
      $expandedTweet .= mb_substr($originalTweet, $start, $entity['indices'][0] - $start);
      switch ($entity['entityType'])
      {
        case 'hashtags':
          $expandedTweet .= $this->_hashtag($entity, '#', $newWindow, $title);
          break;
        case 'symbols':
          $expandedTweet .= $this->_hashtag($entity, '$', $newWindow, $title);
          break;
        case 'user_mentions':
          $expandedTweet .= $this->_userMention($entity, $newWindow, $title);
          break;
        case 'urls':
        case 'media':
          $expandedTweet .= $this->_url($entity, $newWindow, $title);
          break;
        default:
          $expandedTweet .= mb_substr($originalTweet, $entity['indices'][0], $entity['indices'][1]-$entity['indices'][0]);
          break;
      }
      $start = $entity['indices'][1];
    }
    $expandedTweet .= mb_substr($originalTweet, $start);
    return TemplateHelper::getRaw($expandedTweet);
  }

  /* raw tweet entities look like:
  // https://dev.twitter.com/docs/entities
  'entities' =>
      array (size=4)
        'hashtags' =>
          array (size=1)
            0 =>
              array (size=2)
                'text' => string 'testing' (length=7)
                'indices' =>
                  array (size=2)
                    0 => int 0
                    1 => int 8
        'symbols' =>
          array (size=0)
            empty
        'urls' =>
          array (size=0)
            empty
        'user_mentions' =>
          array (size=0)
            empty
    We return a merged array, where the type of entity has been added to each entry
    (['entityType'] is 'hashtags', 'symbols', 'urls', or 'user_mentions'), and we have sorted
    by ['indices'][0]
  */
  private function _sortEntities($entities)
  {
    $res = array();
    foreach($entities as $entityType => $entityGroup)
    {
      foreach($entityGroup as $single)
      {
        $single['entityType'] = $entityType;
        $res[] = $single;
      }
    }
    // now we have the entities all in one array, which we sort by ['indices'][0]
    usort($res, function($a, $b) { return($a['indices'][0] > $b['indices'][0]); });
    return $res;
  }

// https://dev.twitter.com/docs/entities
  private function _url($linkData, $newWindow, $title)
  {
    $target = $newWindow ? ' target="_blank"' : '';
    $title = $title ? ' title="'.$linkData['expanded_url'].'"' : '';
    return '<a href="'.$linkData['expanded_url'].'"'.$target.$title.'>'.$linkData['display_url'].'</a>';
  }

  private function _userMention($linkData, $newWindow, $title)
  {
    $target = $newWindow ? ' target="_blank"' : '';
    $title = $title ? ' title="'.$linkData['name'].'"' : '';
    return '<a href="http://twitter.com/'.$linkData['screen_name'].'"'.$target.$title.'>@<span>'.$linkData['screen_name'].'</span></a>';
  }

  private function _hashtag($linkData, $hash, $newWindow, $title)
  {
    $target = $newWindow ? ' target="_blank"' : '';
    $title = $title ? ' title="'.$hash.$linkData['text'].'"' : '';
    return '<a href="http://twitter.com/search/%23'.$linkData['text'].'"'.$target.$title.'>'.$hash.'<span>'.$linkData['text'].'</span></a>';
  }

}
