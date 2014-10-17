## Ratchet Chat Backend

In lovely PHP. This was built on PHP 5.4 behind Nginx and php-fpm.
It's built on the Slim framework for PHP. It was built in about a weekend, for
the most part. It's a little messy and it's certainly not optimized or well
commented, but it did the job.

### Okay, well, what is it?

Ratchet is a chat app (original, I know) that I developed for Android (and I
had planned to eventually create an iOS client as well). The backend is simple
PHP with few endpoints, though the app is relatively full-featured.

Highlights:

* Threads can have any number of participants.
* Threads can be renamed.
* Users can be invited to threads at any time.
* Users can leave threads at any time.
* Users in a thread will recieve push notifications of new posts
* Users can enable and disable notifications on a global and per-thread level.
* Photographs/media can be sent along with messages.
 * Messages can include text only, media only, or both (as long as the client supports it).
 * Images are uploaded to an abstracted filesystem.
  * Local in this implementation.
  * Support for S3, Azure, and other cloud filesystems (with a little work).
 * Images are asynchronously regenerated into various sizes via a queueworker for better delivery.
  * Original, Medium, Thumbnail sizes are available.
  * Generated images are progressive jpegs.
 * Each user has a "media manager" which stores references to files that the user has uploaded.
* Users can set a profile photo and bio and such.
* Users can share their current location.
 * Users can view the location of other users, sorted by distance.
 * Currently implemented in the app as a cascade, a la Grindr (shudder).

 API authentication is done via a HTTP header, and auth is checked at the time
 that the APIResponse object is instantiated.  See `Objects/User.php` for more.