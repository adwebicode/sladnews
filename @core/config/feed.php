<?php

return [
    'feeds' => [
        'main' => [
            /*
             * Here you can specify which class and method will return
             * the items that should appear in the feed. For example:
             * 'App\Model@getAllFeedItems'
             *
             * You can also pass an argument to that method:
             * ['App\Model@getAllFeedItems', 'argument']
             */
            'items' => 'App\Blog@getFeedItems',

            /*
             * The feed will be available on this url.
             */
            'url' => getenv('RSS_FEED_URL'),
            'title' => getenv('RSS_FEED_TITLE'),
            'description' => getenv('RSS_FEED_DESCRIPTION'),
            'language' => getenv('RSS_FEED_LANGUAGE'),

            'view' => 'feed::atom',

            'type' => 'application/atom+xml',
        ],
    ],
];
