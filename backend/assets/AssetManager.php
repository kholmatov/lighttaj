<?php
namespace backend\assets;
use yii\web\AssetBundle;
//use kholmatov\imagick\Imagick;
use yii\imagine\Image;

use Yii;
define("URL_SEPARATOR", "/");

class AssetManager extends AssetBundle {

    const AssetsDirectoryRoot  = "assets";
    const S3AssetsRoot  = "lighttaj";
    const AssetsDirectoryDeals = "deals";
    const AssetsDirectoryUsers = "users";

    const AssetsDirectoryCategoryIcons = "category";

    const ImageMaxWidth        = 1024;
    const ImageMaxHeight       = 1024;
    const ImageFileExtension   = ".jpeg";

    const GroupingModulus      = 10000;

    const PermissionsDirectory = 0755;
    const PermissionsFile      = 0644;

    const ICON_PREFIX_BIG      = "big";
    const ICON_PREFIX_MEDIUM   = "med";
    const ICON_PREFIX_SMALL      = "small";

    const CategoryItemWidth_big    = 96;
    const CategoryItemHeight_big   = 96;

    const CategoryItemWidth_med    = 64;
    const CategoryItemHeight_med   = 64;

    const CategoryItemWidth_small    = 32;
    const CategoryItemHeight_small   = 32;
    static private $assetsDirectory;

    // ============================
    //            Init
    // ============================
    public function __construct() {}


    // ============================
    // S3
    // ============================

    private static $s3 = null;

    private static function getS3() {
        if (!self::$s3) {
            /*
            $sharedConfig = [
                'region'  => 'us-east-1',
                'version' => 'latest'
            ];
            $sdk = new Aws\Sdk($sharedConfig);
            self::$s3 = $sdk->createS3();
            */
            $awssdk = Yii::$app->awssdk->getAwsSdk();
            self::$s3 = $awssdk->createS3();
        }
        //putenv("AWS_ACCESS_KEY");
        //putenv("AWS_SECRET_ACCESS_KEY=lighttaj");
        return self::$s3;
    }

    static public function uploadToS3($src, $dst) {
        $client = self::getS3();
        $result = $client->putObject(array(
            'Bucket'       => 'lighttaj',
            'Key'          => $dst,
            'SourceFile'   => $src,
            'ContentType'  => 'image/jpeg',
            'ACL'          => 'public-read',
            'StorageClass' => 'REDUCED_REDUNDANCY',
            'Metadata'     => array(
                //'param1' => 'value 1',
                //'param2' => 'value 2'
            )
        ));

        return $result;
    }

    static public function deleteFromS3($dst) {
        $client = self::getS3();
        $result = $client->deleteObject(array(
            'Bucket'       => 'lighttaj',
            'Key'          => $dst,
            'StorageClass' => 'REDUCED_REDUNDANCY',
            'Metadata'     => array(
                //'param1' => 'value 1',
                //'param2' => 'value 2'
            )
        ));

        return $result;
    }

    // ============================
    //      Image Manipulation
    // ============================
    static public function fetchImageFilesForDeal($dealID, $imageList) {
        if (empty($imageList)) return [];
        $imageIds = explode(',', $imageList);

        $images = [];
        foreach ($imageIds as $imageId) {
            $images[] = self::URLForDealImageFile($dealID, self::fileNameForDealImageFile($dealID, $imageId));
            //$images[] = new Image($imageId, $dealID, $imageURL);
        }
        return $images;
    }

    static public function dealExists($dealID) {
        $dealDirectory = self::pathForDealDirectory($dealID);
        return self::itemAtPathExists($dealDirectory);
    }

    static public function appendImageFileForDeal(File $imageFile, $dealID, $imageId, $imageIndex) {
        /* @var $status Status */

        /*
         * Determine path to the deal image directory
         * and create the it if it doesn't exist yet.
         */
        $imagePath     = self::pathForDealImageFile($dealID, $imageIndex, $imageId);
        $dealDirectory = self::pathForDealDirectory($dealID);
        self::ensureDirectoryExists($dealDirectory);

        /*
         * Use ImageMagic to read the image at
         * the temporary path, create a JPEG
         * thumbnail with lowered quality and
         * write it to the destination path.
         */
        $image = new Imagick($imageFile->path);

        $width  = $image->getImageWidth();
        $height = $image->getImageHeight();

        if ($width > $height) {
            $image->thumbnailImage(self::ImageMaxWidth, 0);
        } else {
            $image->thumbnailImage(0, self::ImageMaxHeight);
        }

        $image->setImageCompression(Imagick::COMPRESSION_JPEG);
        $image->setImageCompressionQuality(70);
        $image->stripImage();

        $success = $image->writeImage($imagePath);
        $imageFile = AssetManager::fileNameForDealImageFile($dealID, $imageId);

        $uploaded = self::uploadToS3($imagePath, self::s3KeyForDeal($dealID, $imageFile));

        $image->destroy();

        unlink($imagePath);

        if ($success) {
            return Status::success("Image written to disk successfully");
        } else {
            return Status::errorFilesystem("Failed to write image to disk");
        }
    }

    static public function deleteImageFileForDeal($dealID, $imageID, $needsRenumber = true) {

        /*
         * We need to pass $imageID into the method to avoid
         * generating a new unique file name. Index of 0 is
         * ignored in this function call because $imageID is
         * provided instead.
         */
        $imagePath = self::pathForDealImageFile($dealID, 0, $imageID);
        $success   = self::deleteItemAtPath($imagePath);
        if ($success) {

            /*
             * If renumbering flag is set, we'll enumerate
             * the directory in reverse order starting with
             * the last image, and assign a brand new file
             * name in proper order.
             *
             * We'll also get the index of the current imageID
             * in order to avoid unnecessary renumbering of
             * images that haven't changed.
             */
            if ($needsRenumber) {
                $index = self::indexForImageIdentifier($imageID);
                self::renumberAllImageFilesForDeal($dealID, $index);
            }
            return Status::success("Image file deleted successfully");

        } else {
            return Status::errorFilesystem("Failed to remove image file");
        }
    }

    static public function nextAvailableImageIndexForDeal($dealID) {
        $dealDirectory = self::pathForDealDirectory($dealID);
        $imageFiles    = self::contentsOfDirectory($dealDirectory);

        return count($imageFiles);
    }

    static private function renumberAllImageFilesForDeal($dealID, $startIndex = 0) {

        $dealDirectory = self::pathForDealDirectory($dealID);

        /*
         * Save current directory so we can
         * restore to current state after
         * we're finished
         */
        $dir = getcwd();
        chdir($dealDirectory);

        // TODO: Need to lock the directory for writing
        // - Create a lockable file (ex: .lock in directory and flock() it for each operation)
        // - Lock directory using Redis

        /*
         * Iterate over each file in directory
         * in reverse order and rename the file
         * according to the index. Stop when
         * start index is reached.
         */
        $files = self::contentsOfDirectory($dealDirectory, false); // false for descending order
        $index = count($files) - 1;
        foreach ($files as $oldFile) {
            /*
             * Stop renumbering if we've reached
             * the specified index. Its likely
             * that these files haven't been
             * changed and don't need renumbering.
             */
            if ($index < $startIndex) {
                break;
            }

            $newFile = self::createImageIdentifierForIndex($index) . self::extension($oldFile);

            rename($oldFile, $newFile);
            --$index;
        }

        /*
         * Return the pwd to the previous
         * active directory.
         */
        chdir($dir);
    }

    static public function deleteAllImageFilesForDeal($dealID) {

        $dealDirectory = self::pathForDealDirectory($dealID);
        $success       = self::deleteItemAtPath($dealDirectory);
        if ($success) {
            return Status::success("Deal directory deleted successfully");
        } else {
            return Status::errorFilesystem("Failed to remove deal directory");
        }
    }

    // ============================
    //      Image Manipulation: Category icons
    // ============================

    static public function getCategoryIconPath($categoryID, $size) {
        return self::pathForCategoryIconDirectory()
        . DIRECTORY_SEPARATOR
        . "{$categoryID}_{$size}.png";
    }

    static public function saveCategoryIcon($categorypath,$categoryID) {
        $dst = self::AssetsDirectoryCategoryIcons;
        $big = $categoryID.'_big.png';
        $med = $categoryID.'_med.png';
        $small = $categoryID.'_small.png';
        if(!self::uploadToS3($categorypath.$big,$dst.'/'.$big))
            return "Error while saving category big image to s3";
        if(!self::uploadToS3($categorypath.$med,$dst.'/'.$med))
           return "Error while saving category med image to s3";
        if(!self::uploadToS3($categorypath.$small,$dst.'/'.$small))
            return "Error while saving category small image to s3";

        $deleted  = unlink($categorypath.$big);
        $deleted = $deleted && unlink($categorypath.$med);
        $deleted = $deleted && unlink($categorypath.$small);
        if (!$deleted) {
            //TODO log this as warning
        }

        return 1;

    }

    static public function deleteCategoryIcon($categoryID) {
        $deleted  = unlink(self::getCategoryIconPath($categoryID, self::ICON_PREFIX_MEDIUM));
        $deleted = $deleted && unlink(self::getCategoryIconPath($categoryID, self::ICON_PREFIX_BIG));
        $deleted = $deleted && unlink(self::getCategoryIconPath($categoryID, self::ICON_PREFIX_SMALL));

        if (!$deleted) {
            //TODO log this as warning
        }

        return Status::Success();
    }



    // ============================
    //      Image Manipulation: User photo
    // ============================
// ============================
    //      Image Manipulation: User photo
    // ============================


    static public function pathForUserPhoto($userID) {
        return self::pathForUserDirectory($userID)
        . DIRECTORY_SEPARATOR
        . "photo.jpg";
    }

    static public function saveUserImage(File $file, $userID) {
        if (!self::itemAtPathExists($file->path)) {
            return Status::errorFilesystem("Can't find uploaded image");
        }

        if ($file->mimeType !== File::MimePNG
            && $file->mimeType !== File::MimeJPEG) {
            return Status::errorInvalid("Please upload PNG or JPEG format");
        }

        $success = true;
        if (!is_dir(self::pathForUserDirectory($userID))) {
            $success = mkdir(self::pathForUserDirectory($userID), 0777, true);
        }

        if (!$success) {
            return Status::errorFilesystem("Can't create user directory");
        }

        $result = self::uploadToS3($file->path, self::s3KeyForUserPhoto($userID) . "orig");

        $image = new Imagick($file->path);

        $success = true;

        $tmpPath = self::pathForUserPhoto($userID);

        $success = $success && $image->resizeImage(Def::UserPhotoResizeToHeight, Def::UserPhotoResizeToHeight, Imagick::FILTER_POINT, 1);
        $success = $success && $image->setImageFormat("jpeg");
        $success = $success && $image->writeImage($tmpPath);

        $result = self::uploadToS3($tmpPath, self::s3KeyForUserPhoto($userID));

        return $success ? Status::Success() : Status::errorInternal("Error while saving user image");

    }



    // ============================
    //         Asset URLs
    // ============================
    static public function URLForDealImageFile($dealID, $imageFile) {
        // TODO remove me after migration to AWS!!
        //self::uploadToS3(self::pathForDealDirectory($dealID) . DIRECTORY_SEPARATOR . $imageFile, self::s3KeyForDeal($dealID, $imageFile));
        return self::URLForDealDirectory($dealID) . URL_SEPARATOR . $imageFile;
    }

    static public function URLForUserImageFile($userID) {
        // TODO remove me after migration to AWS!!
        //self::uploadToS3(self::pathForUserPhoto($userID), self::s3KeyForUserPhoto($userID));
        return self::URLForAssetsDirectory() . DIRECTORY_SEPARATOR . self::s3KeyForUserPhoto($userID);
    }

    static private function s3BucketForUser($userID) {
        return self::AssetsDirectoryUsers . DIRECTORY_SEPARATOR . self::indexForID($userID) . DIRECTORY_SEPARATOR . $userID;
    }

    static private function s3KeyForUserPhoto($userID) {
        return self::s3BucketForUser($userID) . "/photo.jpg";
    }

    static private function s3KeyForDeal($dealID, $imageFile) {
        return self::AssetsDirectoryDeals . URL_SEPARATOR . self::indexForID($dealID) . DIRECTORY_SEPARATOR . $dealID . URL_SEPARATOR . $imageFile;
    }

    static public function URLForDealDirectory($dealID) {
        return self::URLForAssetsDirectory() . URL_SEPARATOR . self::AssetsDirectoryDeals . URL_SEPARATOR . self::indexForID($dealID) . DIRECTORY_SEPARATOR . $dealID;
    }

    static public function URLForUserDirectory($userID) {
        return self::URLForAssetsDirectory() . URL_SEPARATOR . self::AssetsDirectoryUsers . URL_SEPARATOR . self::indexForID($userID) . DIRECTORY_SEPARATOR . $userID;
    }

    static public function URLForAssetsDirectory($secure = false) {
        //$scheme = ($secure) ? "https://" : "http://";
        //return $scheme . $_SERVER["HTTP_HOST"] . URL_SEPARATOR . self::AssetsDirectoryRoot;
        return "https://s3.amazonaws.com/lighttaj";
    }

    static public function URLForCategoryIconDirectory() {
        return self::URLForAssetsDirectory() . URL_SEPARATOR . self::AssetsDirectoryCategoryIcons;
    }

    // ============================
    //     Image Identifiers
    // ============================
    static public function createImageIdentifierForIndex($index) {
        return uniqid(($index !== null) ? $index . "-" : "");
    }

    static private function indexForImageIdentifier($imageID) {
        if (strlen($imageID) > 1) {
            return (int)substr($imageID, 0, 1);
        }
        return null;
    }

    static public function fileNameForDealImageFile($dealID, $imageID = null) {
        return $imageID . self::ImageFileExtension;
    }

    // ============================
    //         Filesystem
    // ============================
    static private function pathForDealImageFile($dealID, $imageIndex, $imageID = null) {
        $name = ($imageID !== null) ? $imageID : self::createImageIdentifierForIndex($imageIndex);
        return self::pathForDealDirectory($dealID) . DIRECTORY_SEPARATOR . $name . self::ImageFileExtension;
    }

    static private function pathForDealDirectory($dealID) {
        return self::pathForRoot() . DIRECTORY_SEPARATOR . self::AssetsDirectoryDeals . DIRECTORY_SEPARATOR . self::indexForID($dealID) . DIRECTORY_SEPARATOR . $dealID;
    }

    static public function pathForCategoryIconDirectory() {
        return self::pathForRoot() . DIRECTORY_SEPARATOR . self::AssetsDirectoryCategoryIcons;
    }

    static private function pathForUserDirectory($userID) {
        return self::pathForRoot() . DIRECTORY_SEPARATOR . self::AssetsDirectoryUsers . DIRECTORY_SEPARATOR . self::indexForID($userID) . DIRECTORY_SEPARATOR . $userID;
    }

    static private function pathForRoot() {
        if (!self::$assetsDirectory) {
            self::$assetsDirectory = $_SERVER["DOCUMENT_ROOT"] . DIRECTORY_SEPARATOR . self::AssetsDirectoryRoot;
        }
        return self::$assetsDirectory;
    }

    static private function indexForID($itemID) {
        return (int)((float)$itemID / (float)self::GroupingModulus);
    }

    static private function ensureDirectoryExists($path) {
        if (!self::itemAtPathExists($path)) {
            $success = mkdir($path, self::PermissionsDirectory, true);
            if ($success) {
                return Status::success("Directory created at path: " . $path);
            } else {
                return Status::errorFilesystem("Unable to create directory at path: " . $path);
            }
        } else {
            return Status::success("Directory already exists at path: " . $path);
        }
    }

    static private function itemAtPathExists($path) {
        return file_exists($path);
    }

    static private function deleteItemAtPath($path) {
        if (is_dir($path) === true) {

            $files = self::contentsOfDirectory($path);
            foreach ($files as $file) {
                unlink(realpath($path) . DIRECTORY_SEPARATOR . $file);
            }
            return rmdir($path);

        } else if (is_file($path) === true) {
            return unlink($path);
        }
    }

    static private function contentsOfDirectory($path, $ascending = true) {
        if (self::itemAtPathExists($path)) {
            $order = ($ascending) ? SCANDIR_SORT_ASCENDING : SCANDIR_SORT_DESCENDING;
            return array_diff(scandir($path, $order), [".", ".."]);
        }
        return [];
    }

    static private function base($filename) {
        return explode(".", $filename)[0];
    }

    static private function extension($filename) {
        $c = explode(".", $filename);
        return "." . $c[count($c) - 1];
    }

}