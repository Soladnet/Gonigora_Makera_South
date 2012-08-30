<?php

require_once 'Zend/Loader.php';
Zend_Loader::loadClass('Zend_Gdata_YouTube');
Zend_Loader::loadClass('Zend_Gdata_App_Exception');
Zend_Loader::loadClass('Zend_Gdata_ClientLogin');

$authenticationURL = 'https://www.google.com/accounts/ClientLogin';
$httpClient = Zend_Gdata_ClientLogin::getHttpClient(
                $username = 'soladnet@gmail.com', $password = 'soladnet2006romeo1', $service = 'youtube', $client = null, $source = 'Gossout', // a short string identifying your application
                $loginToken = null, $loginCaptcha = null, $authenticationURL);
$developerKey = 'AI39si6TWOa6PxiWOzBnGyosPYbIlcpr071r_YG37pnKWPPUeZUPGjgIO9Qk2yZ9_G8ycTtrYJSW0tWA-oKzKmz4FNX3Telx9g';
$applicationId = 'AIzaSyBCIhsy-VaK5kkrNquNhAlizqWPCpFOBDw';
$clientId = '62477224356.apps.googleusercontent.com';

$yt = new Zend_Gdata_YouTube($httpClient, $applicationId, $clientId, $developerKey);
try {
    $entry = $yt->getVideoEntry('1S6gCOHGWwU');
} catch (Zend_Gdata_App_HttpException $httpException) {
    print 'ERROR ' . $httpException->getMessage()
            . ' HTTP details<br /><textarea cols="100" rows="20">'
            . $httpException->getRawResponseBody()
            . '</textarea><br />'
            . '<a href="session_details.php">'
            . 'click here to view details of last request</a><br />';
    exit;
}
$videoTitle = htmlspecialchars($entry->getVideoTitle());
$videoUrl = htmlspecialchars(findFlashUrl($entry));
//$relatedVideoFeed = getRelatedVideos($entry->getVideoId(), $httpClient, $applicationId, $clientId, $developerKey);
$topRatedFeed = getTopRatedVideosByUser($entry->author[0]->name);
print <<<END
        <b>$videoTitle</b><br />
        <object width="200" height="200">
        <param name="movie" value="${videoUrl}&autoplay=1"></param>
        <param name="wmode" value="transparent"></param>
        <embed src="${videoUrl}&autoplay=1" type="application/x-shockwave-flash" wmode="transparent"
        width="200" height="200"></embed>
        </object>
END;

echo '<br />';
echoVideoMetadata($entry);
//echo '<br /><b>Related:</b><br />';
//echoThumbnails($relatedVideoFeed);
echo '<br /><b>Top rated videos by user:</b><br />';
echoThumbnails($topRatedFeed);

function findFlashUrl($entry) {
    foreach ($entry->mediaGroup->content as $content) {
        if ($content->type === 'application/x-shockwave-flash') {
            return $content->url;
        }
    }
    return null;
}

function echoThumbnails($feed) {
    foreach ($feed as $entry) {
        $videoId = $entry->getVideoId();
        $firstThumbnail = htmlspecialchars(
                $entry->mediaGroup->thumbnail[0]->url);
        echo '<img id="' . $videoId . '" class="thumbnail" src="'
        . $firstThumbnail . '" width="130" height="97" onclick="'
        . 'ytVideoApp.presentVideo(\'' . $videoId . '\', 1);" '
        . 'title="click to watch: ' .
        htmlspecialchars($entry->getVideoTitle()) . '" />';
    }
}

function getRelatedVideos($videoId, $httpClient, $applicationId, $clientId, $developerKey) {
    $youTubeService = new Zend_Gdata_YouTube($httpClient, $applicationId, $clientId, $developerKey);
    $ytQuery = $youTubeService->newVideoQuery();
    // show videos related to the specified video
    $ytQuery->setFeedType('related', $videoId);
    // order videos by rating
    $ytQuery->setOrderBy('rating');
    // retrieve a maximum of 5 videos
    $ytQuery->setMaxResults(5);
    // retrieve only embeddable videos
    $ytQuery->setFormat(5);
    return $youTubeService->getVideoFeed($ytQuery);
}

function getTopRatedVideosByUser($user) {
    $userVideosUrl = 'https://gdata.youtube.com/feeds/users/' .
            $user . '/uploads';
    $youTubeService = new Zend_Gdata_YouTube();
    $ytQuery = $youTubeService->newVideoQuery($userVideosUrl);
    // order by the rating of the videos
    $ytQuery->setOrderBy('rating');
    // retrieve a maximum of 5 videos
    $ytQuery->setMaxResults(5);
    // retrieve only embeddable videos
    $ytQuery->setFormat(5);
    return $youTubeService->getVideoFeed($ytQuery);
}

function echoVideoMetadata($entry) {
    $title = htmlspecialchars($entry->getVideoTitle());
    $description = htmlspecialchars($entry->getVideoDescription());
    $authorUsername = htmlspecialchars($entry->author[0]->name);
    $authorUrl = 'http://www.youtube.com/profile?user=' .
            $authorUsername;
    $tags = htmlspecialchars(implode(', ', $entry->getVideoTags()));
    $duration = htmlspecialchars($entry->getVideoDuration());
    $soladnetRating = htmlspecialchars($entry->getRecorded());
    $watchPage = htmlspecialchars($entry->getVideoWatchPageUrl());
    $viewCount = htmlspecialchars($entry->getVideoViewCount());
    $rating = 0;
    if (isset($entry->rating->average)) {
        $rating = $entry->rating->average;
    }
    $numRaters = 0;
    if (isset($entry->rating->numRaters)) {
        $numRaters = $entry->rating->numRaters;
    }
    $flashUrl = htmlspecialchars(findFlashUrl($entry));
    print <<<END
        <b>Title:</b> ${title}<br />
        <br>Soladnet Rating: </b>${soladnetRating}<br/>
        <b>Description:</b> ${description}<br />
        <b>Author:</b> <a href="${authorUrl}">${authorUsername}</a><br />
        <b>Tags:</b> ${tags}<br />
        <b>Duration:</b> ${duration} seconds<br />
        <b>View count:</b> ${viewCount}<br />
        <b>Rating:</b> ${rating} (${numRaters} ratings)<br />
        <b>Flash:</b> <a href="${flashUrl}">${flashUrl}</a><br />
        <b>Watch page:</b> <a href="${watchPage}">${watchPage}</a> <br />
END;
}

?>