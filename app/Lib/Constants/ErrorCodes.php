<?php

namespace App\Lib\Constants;

class ErrorCodes
{
    const UNAUTHENTICATED = 1;
    const INVALID_USER_CREDENTIALS = 2;
    const USER_NOT_AUTHORIZED = 3;
    const DATE_PARAM_HAS_NOT_THE_REQUIRED_FORMAT = 4;

    // GARMENTS
    const GARMENT_NOT_FOUND = 20;
    const INVALID_GARMENT = 21;
    const FAILED_TO_CREATE_GARMENT = 22;
    const FAILED_TO_UPDATE_GARMENT = 23;
    const FAILED_TO_DELETE_GARMENT = 24;

    // USERS
    const USER_NOT_FOUND = 30;
    const INVALID_USER = 31;
    const FAILED_TO_CREATE_USER = 32;
    const FAILED_TO_UPDATE_USER = 33;
    const FAILED_TO_DELETE_USER = 34;

    // IMAGES
    const IMAGE_NOT_FOUND = 40;
    const INVALID_IMAGE = 41;
    const FAILED_TO_CREATE_IMAGE = 42;
    const FAILED_TO_UPDATE_IMAGE = 43;
    const FAILED_TO_DELETE_IMAGE = 44;
    const FAILED_TO_UPLOAD_IMAGE = 45;
    const FILE_IS_REQUIRED_FOR_CREATE_IMAGE = 46;
    const FILE_SIZE_NOT_ALLOWED = 47;

    // EXPENSES
    const EXPENSE_NOT_FOUND = 50;
    const INVALID_EXPENSE = 51;
    const FAILED_TO_CREATE_EXPENSE = 52;
    const FAILED_TO_UPDATE_EXPENSE = 53;
    const FAILED_TO_DELETE_EXPENSE = 54;

    // CAMERAS
    const CAMERA_NOT_FOUND = 60;
    const INVALID_CAMERA = 61;
    const FAILED_TO_CREATE_CAMERA = 62;
    const FAILED_TO_UPDATE_CAMERA = 63;
    const FAILED_TO_DELETE_CAMERA = 64;

    // SPENDING_CATEGORYS
    const SPENDING_CATEGORY_NOT_FOUND = 70;
    const INVALID_SPENDING_CATEGORY = 71;
    const FAILED_TO_CREATE_SPENDING_CATEGORY = 72;
    const FAILED_TO_UPDATE_SPENDING_CATEGORY = 73;
    const FAILED_TO_DELETE_SPENDING_CATEGORY = 74;

    // COMMENTS
    const COMMENT_NOT_FOUND = 80;
    const INVALID_COMMENT = 81;
    const FAILED_TO_CREATE_COMMENT = 82;
    const FAILED_TO_UPDATE_COMMENT = 83;
    const FAILED_TO_DELETE_COMMENT = 84;

    // PLACES
    const PLACE_NOT_FOUND = 90;
    const INVALID_PLACE = 91;
    const FAILED_TO_CREATE_PLACE = 92;
    const FAILED_TO_UPDATE_PLACE = 93;
    const FAILED_TO_DELETE_PLACE = 94;

    // PERMISSIONS
    const PERMISSION_NOT_FOUND = 100;
    const INVALID_PERMISSION = 101;
    const FAILED_TO_CREATE_PERMISSION = 102;
    const FAILED_TO_UPDATE_PERMISSION = 103;
    const FAILED_TO_DELETE_PERMISSION = 104;

    // DESTINATIONS
    const DESTINATION_NOT_FOUND = 110;
    const INVALID_DESTINATION = 111;
    const FAILED_TO_CREATE_DESTINATION = 112;
    const FAILED_TO_UPDATE_DESTINATION = 113;
    const FAILED_TO_DELETE_DESTINATION = 114;
}
