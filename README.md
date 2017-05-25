[![](https://scdn.rapidapi.com/RapidAPI_banner.png)](https://rapidapi.com/package/Stream/functions?utm_source=RapidAPIGitHub_StreamFunctions&utm_medium=button&utm_content=RapidAPI_GitHub)
# Stream Package
Create and customize a social newsfeed/activity newsfeed.
* Domain: [Stream](https://getstream.io)
* Credentials: apiKey, apiSecret

## How to get credentials: 
1. Get apiKey and apiSecret from [Stream](https://www.getstream.io) 
 
## Stream.createActivity
Create any activity on feed

| Field        | Type  | Description
|--------------|-------|----------
| apiKey       | credentials| User apiKey obtained from getstream.io
| apiSecret    | credentials| User apiSecret obtained from getstream.io
| feedOwnerType| String| Type of feed in which the activity will be created. In your app settings, you can add, remove or edit feed groups. Put there feed Name, not feed type. User has type flat, so you must enter 'user'
| feedOwnerId  | String| ID of feed in which the activity will be created
| activity     | File  | JSON file with activity parameters. There may be many fields required for your application. But there are 3 required fields: actor, verb, object. Actor - who (name), verb - what actor do (like, post, tweet etc), object - with what actor do verb. Fox example: actor - Mike, verb - like, object - picture:10 (if in your app exist picture object with id=10). In Object field you get type and ip of object. You may parse field to get type and id. You can also set object = 10, if you has only pictures to like.

## Stream.getActivities
Get activities from feed with offset and limit

| Field        | Type  | Description
|--------------|-------|----------
| apiKey       | credentials| User apiKey obtained from getstream.io
| apiSecret    | credentials| User apiSecret obtained from getstream.io
| feedOwnerType| String| Type of feed which activity will be get
| feedOwnerId  | String| Id of feed which activity will be get
| limit        | Number| The amount of activities requested from the APIs. Default: 25
| offset       | Number| The offset. Default: 0
| id_gte       | String| Filter the feed on ids greater than or equal to the given value
| id_gt        | String| Filter the feed on ids greater than the given value
| id_lte       | String| Filter the feed on ids smaller than or equal to the given value
| id_lt        | String| Filter the feed on ids smaller than the given value

## Stream.deleteActivity
Remove activity from feed

| Field        | Type  | Description
|--------------|-------|----------
| apiKey       | credentials| User apiKey obtained from getstream.io
| apiSecret    | credentials| User apiSecret obtained from getstream.io
| feedOwnerType| String| Type of feed which activity will be removed
| feedOwnerId  | String| Id of feed which activity will be removed
| activityId   | String| Activity ID, which will be removed

## Stream.followFeed
Follow feed

| Field         | Type  | Description
|---------------|-------|----------
| apiKey        | credentials| User apiKey obtained from getstream.io
| apiSecret     | credentials| User apiSecret obtained from getstream.io
| feedOwnerType | String| Feed Name
| feedOwnerId   | String| Feed ID
| feedTargetType| String| Target feed Name. For which owner will follow
| feedTargetId  | String| Target feed ID. For which owner will follow

## Stream.createMultipleFollows
Create multiple follows with one request

| Field    | Type  | Description
|----------|-------|----------
| apiKey   | credentials| User apiKey obtained from getstream.io
| apiSecret| credentials| User apiSecret obtained from getstream.io
| list     | Array  | Create many follows.

## Stream.getFeedFollower
Get feed follower

| Field        | Type  | Description
|--------------|-------|----------
| apiKey       | credentials| User apiKey obtained from getstream.io
| apiSecret    | credentials| User apiSecret obtained from getstream.io
| feedOwnerType| String| Feed Name
| feedOwnerId  | String| Feed ID

## Stream.getFeedFollowings
Get feed followings

| Field        | Type  | Description
|--------------|-------|----------
| apiKey       | credentials| User apiKey obtained from getstream.io
| apiSecret    | credentials| User apiSecret obtained from getstream.io
| feedOwnerType| String| Feed Name
| feedOwnerId  | String| Feed ID

## Stream.unFollowFeed
Unfollow feed

| Field         | Type  | Description
|---------------|-------|----------
| apiKey        | credentials| User apiKey obtained from getstream.io
| apiSecret     | credentials| User apiSecret obtained from getstream.io
| feedOwnerType | String| Feed Name
| feedOwnerId   | String| Feed ID
| feedTargetType| String| Target feed Name
| feedTargetId  | String| Target feed ID

## Stream.addActivityToFeeds
Add one activity to many feed

| Field        | Type  | Description
|--------------|-------|----------
| apiKey       | credentials| User apiKey obtained from getstream.io
| apiSecret    | credentials| User apiSecret obtained from getstream.io
| feedList     | Array | Comma-separated feeds. Example: ['user:1', 'notification:1']. 'user' - feed Name, '1' - feed ID
| activity     | File  | JSON file with activity parameters. There may be many fields required for your application. But there age 3 required fields: actor, verb, object. Actor - who (name), verb - what actor do (like, post, tweet etc), object

