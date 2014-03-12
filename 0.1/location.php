<?php
/**
 * The user location module.
 * Shows a user's proximity to other users, given their
 * current location.
 */

/**
 * Returns the last 10 location updates from a user.
 */
$app->get("/location/", function () {
   $res = new APIResponse(['user']);
   $geo = new UserLocation($res->userid);

   $res->addData(['history' => $geo->locationHistory()]);

   $res->respond();
});
$app->post("/location/", function () {
   $res = new APIResponse(['user']);
   $params = $res->params($_POST, ['latitude', 'longitude']);
   $geo = new UserLocation($res->userid);

   $geo->updateLocation($params['latitude'], $params['longitude']);
   $geo->save();

   $res->addData(['history' => $geo->locationHistory()]);

   $res->respond();
});

/**
 * Returns the 25 most nearby users.
 */
$app->get("/location/nearby/", function () {
   $res = new APIResponse(['user']);
   $params = $res->params($_GET, ['latitude', 'longitude']);
   $geo = new UserLocation($res->userid);

   $geo->updateLocation($params['latitude'], $params['longitude']);
   $res->addData(['nearby' => $geo->nearbyUsers()]);

   $res->respond();
});
