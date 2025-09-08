<?php

namespace App\Models\ValueObjects;


final class YouTubeUrl
{
    private const YOUTUBE_PATTERNS = [
        '/(?:youtube\.com\/watch\?v=|youtu\.be\/|youtube\.com\/embed\/)([a-zA-Z0-9_-]{11})/',
        '/youtube\.com\/watch\?.*v=([a-zA-Z0-9_-]{11})/',
        '/youtu\.be\/([a-zA-Z0-9_-]{11})/',
        '/youtube\.com\/embed\/([a-zA-Z0-9_-]{11})/',
    ];

    public function __construct(
        private readonly string $url,
        private readonly string $id
    ) {}

    public static function fromString(string $url): self
    {
        $url = trim($url);

        if (empty($url)) {
            // throw new Exception('URL do YouTube não pode estar vazia');
        }

        $id = self::extractVideoId($url);

        if (!$id) {
            // throw new Exception('URL do YouTube inválida: ' . $url);
        }

        return new self($url, $id);
    }

    public static function fromId(string $id): self
    {
        if (!self::isValidVideoId($id)) {
            // throw new Exception('ID do YouTube inválido: ' . $id);
        }

        $url = "https://www.youtube.com/watch?v={$id}";

        return new self($url, $id);
    }

    public function toString(): string
    {
        return $this->url;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getThumbnailUrl(string $quality = 'maxresdefault'): string
    {
        return "https://img.youtube.com/vi/{$this->id}/{$quality}.jpg";
    }

    public function getEmbedUrl(): string
    {
        return "https://www.youtube.com/embed/{$this->id}";
    }

    public function getWatchUrl(): string
    {
        return "https://www.youtube.com/watch?v={$this->id}";
    }

    public function equals(YouTubeUrl $other): bool
    {
        return $this->id === $other->id;
    }

    private static function extractVideoId(string $url): ?string
    {
        foreach (self::YOUTUBE_PATTERNS as $pattern) {
            if (preg_match($pattern, $url, $matches)) {
                return $matches[1];
            }
        }

        return null;
    }

    private static function isValidVideoId(string $id): bool
    {
        return preg_match('/^[a-zA-Z0-9_-]{11}$/', $id) === 1;
    }

    public function __toString(): string
    {
        return $this->toString();
    }
}
