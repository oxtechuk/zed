<?php

namespace Database\Seeders;

trait LocalImagesTrait
{
    /** @var string[] */
    private array $carImagePool = [
        'car1.png',
        'g1.png',
        'g2.png',
        'g3.png',
        'g4.png',
        'eid.png',
    ];

    /** @var string[] */
    private array $offerImagePool = [
        'offer.png',
        'offer1.png',
        'all-cars-offer-page.png',
        'offerCard.png',
    ];

    private function localImage(string $file): string
    {
        return url('images/'.$file);
    }

    private function carThumbnail(int $index): string
    {
        return $this->localImage($this->carImagePool[$index % count($this->carImagePool)]);
    }

    /**
     * @return array<int, array{url: string, type: string}>
     */
    private function carGallery(int $index): array
    {
        $pool = $this->carImagePool;
        $count = count($pool);
        $base = $index % $count;

        return [
            ['url' => $this->localImage($pool[$base % $count]), 'type' => 'exterior'],
            ['url' => $this->localImage($pool[($base + 1) % $count]), 'type' => 'exterior'],
            ['url' => $this->localImage($pool[($base + 2) % $count]), 'type' => 'interior'],
        ];
    }

    private function brandLogo(): string
    {
        return $this->localImage('brand.svg');
    }

    private function offerImage(int $index): string
    {
        return $this->localImage($this->offerImagePool[$index % count($this->offerImagePool)]);
    }
}
