<?php

namespace AppBundle\EventListener;

use AppBundle\Entity\Message;
use AppBundle\Entity\Mosque;
use Vich\UploaderBundle\Event\Event;

class VichImageHandling
{

    const IMAGE_WIDTH = 1920;
    const IMAGE_HEIGHT = 1080;

    public function resizeImage(Event $event)
    {
        /**
         * @var $object Mosque
         */
        $object = $event->getObject();

        if ($object instanceof Mosque && $event->getMapping()->getFileNamePropertyName() === 'justificatory') {
            return;
        }

        if ($object instanceof Mosque || $object instanceof Message) {
            $destinationDir = $event->getMapping()->getUploadDestination();
            $fileName = $event->getMapping()->getFileName($object);
            $filePath = $destinationDir . '/' . $fileName;
            if (file_exists($filePath)) {
                $image = new \Imagick($filePath);
                $this->cropImage($image);
                $image->writeimage();
            }
        }

        if ($object instanceof Mosque && $object->getId() && $event->getMapping()->getFileNamePropertyName() === "image3") {
            $object->setStatus(Mosque::STATUS_SCREEN_PHOTO_ADDED);
        }
    }

    private function cropImage(\Imagick &$image){
        $w = $image->getImageWidth();
        $h = $image->getImageHeight();

        if ($h > self::IMAGE_HEIGHT || $w > self::IMAGE_WIDTH) {
            $image->cropImage(self::IMAGE_WIDTH, self::IMAGE_HEIGHT, ($w - self::IMAGE_WIDTH) / 2, ($h - self::IMAGE_HEIGHT) / 2);
        } else {
            $image->scaleimage(self::IMAGE_WIDTH, self::IMAGE_HEIGHT);
        }
    }
}
