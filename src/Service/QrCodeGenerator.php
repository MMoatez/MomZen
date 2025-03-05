<?php 
namespace App\Service;

use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\Label\Font\NotoSans;
use Endroid\QrCode\Writer\Result\ResultInterface;
use Endroid\QrCode\Writer\SvgWriter;
use App\Entity\User;

class QrCodeGenerator 
{
    public function createQrCode(User $user): ResultInterface
    {
        $info = sprintf(
            "ID  %s\nPrenom  %s\nNom  %s\nEmail  %s\nNum Tel  %s\nGenre  %s",
            $user->getId(),
            $user->getPrenom(),
            $user->getNom(),
            $user->getEmail(),
            $user->getNumtel(),
            $user->isGenre()
        );

        return Builder::create()
            ->writer(new SvgWriter())
            ->writerOptions([])
            ->data($info)
            ->encoding(new Encoding('UTF-8'))
            ->size(200)
            ->margin(10)
            ->labelText('Vous trouvez vos informations ici')
            ->labelFont(new NotoSans(20))
            ->validateResult(false)
            ->build();
    }
}
