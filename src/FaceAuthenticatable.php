<?php

namespace Mpociot\FaceAuth;

interface FaceAuthenticatable
{
    /**
     * Get the file / binary representation of the user
     * face authentication photo.
     *
     * @return string
     */
    public function getFaceAuthPhoto();
}
