<?php

namespace Mpociot\FaceAuth;

use Exception;
use GuzzleHttp\Client;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Contracts\Auth\Authenticatable;

class FaceAuthUserProvider extends EloquentUserProvider implements UserProvider
{

    /**
     * Retrieve a user by the given credentials.
     *
     * @param  array  $credentials
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function retrieveByCredentials(array $credentials)
    {
        if (empty($credentials)) {
            return;
        }

        // First we will add each credential element to the query as a where clause.
        // Then we can execute the query and, if we found a user, return it in a
        // Eloquent User "model" that will be utilized by the Guard instances.
        $query = $this->createModel()->newQuery();

        foreach ($credentials as $key => $value) {
            if (! Str::contains($key, config('face_auth.credential_name'))) {
                $query->where($key, $value);
            }
        }

        return $query->first();
    }

    /**
     * Validate a user against the given credentials.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable $user
     * @param  array $credentials
     * @return bool
     * @throws Exception
     */
    public function validateCredentials(Authenticatable $user, array $credentials)
    {
        if (!$user instanceof FaceAuthenticatable) {
            throw new Exception('The user needs to implement the "FaceAuthenticatable" interface');
        }

        try {
            // Get face id of user photo
            $userFaceId = $this->getFaceId($user->getFaceAuthPhoto());
            $matchFaceId = $this->getFaceId((new ImageManager())->make($credentials[config('face_auth.credential_name')])->encode('png'));

            // Verify faces
            $response = app(Client::class)->post('https://westus.api.cognitive.microsoft.com/face/v1.0/verify',[
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Ocp-Apim-Subscription-Key' => config('face_auth.api_key'),
                ],
                'body' => json_encode([
                    'faceId1' => $userFaceId,
                    'faceId2' => $matchFaceId
                ])
            ]);
            $responseData = json_decode($response->getBody()->getContents());
            return $responseData->isIdentical;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * @param $image
     * @return mixed
     */
    protected function getFaceId($image) {
        $response = app(Client::class)->post('https://westus.api.cognitive.microsoft.com/face/v1.0/detect?returnFaceId=true',[
            'headers' => [
                'Content-Type' => 'application/octet-stream',
                'Ocp-Apim-Subscription-Key' => config('face_auth.api_key'),
            ],
            'body' => $image
        ]);
        return collect(json_decode($response->getBody()->getContents(),true))->pluck('faceId')->first();
    }
}