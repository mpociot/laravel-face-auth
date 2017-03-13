<?php

return [

	/*
	 * The API key for the MS cognitive face API.
	 */
	'api_key' => env('MICROSOFT_FACE_API_KEY'),

	/*
	 * The name of the form field / request data that contains
	 * the user image to use for credentials.
	 * Needs to be a base64 encoded string.
	 */
	'credential_name' => 'photo',

];