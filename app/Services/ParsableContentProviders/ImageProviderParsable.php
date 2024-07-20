<?php

declare(strict_types=1);

namespace App\Services\ParsableContentProviders;

use App\Contracts\Services\ParsableContentProvider;
use Illuminate\Support\Facades\Storage;

final readonly class ImageProviderParsable implements ParsableContentProvider
{
    /**
     * {@inheritDoc}
     */
    public function parse(string $content): string
    {
        return (string) preg_replace_callback(
            '/!\[(.*?)\]\((.*?)\)/',
            static function (array $match): string {
                $altText = preg_replace('/\.(jpg|jpeg|png|gif)$/i', '', $match[1]);

                $disk = Storage::disk('public');

                if (! $disk->exists($match[2])) {
                    return '';
                }

                $url = $disk->url($match[2]);

                return "<img class='object-cover mx-auto max-h-[52rem] w-full max-w-[26rem] rounded-lg' src=\"{$url}\" alt=\"{$altText}\">";
            },
            $content
        );
    }
}
