namespace Tests\Unit\Domain\ValueObjects;

use App\Domain\ValueObjects\YouTubeUrl;
use App\Domain\Exceptions\InvalidYouTubeUrlException;
use PHPUnit\Framework\TestCase;

class YouTubeUrlTest extends TestCase
{
    public function test_pode_criar_com_url_valida()
    {
        $urls = [
            'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
            'https://youtu.be/dQw4w9WgXcQ',
            'https://youtube.com/embed/dQw4w9WgXcQ',
        ];

        foreach ($urls as $url) {
            $youtubeUrl = YouTubeUrl::fromString($url);
            $this->assertEquals('dQw4w9WgXcQ', $youtubeUrl->getId());
        }
    }

    public function test_falha_com_url_invalida()
    {
        $this->expectException(InvalidYouTubeUrlException::class);
        
        YouTubeUrl::fromString('https://invalid-url.com');
    }

    public function test_falha_com_url_vazia()
    {
        $this->expectException(InvalidYouTubeUrlException::class);
        
        YouTubeUrl::fromString('');
    }

    public function test_pode_gerar_urls_derivadas()
    {
        $youtubeUrl = YouTubeUrl::fromString('https://www.youtube.com/watch?v=dQw4w9WgXcQ');

        $this->assertEquals(
            'https://img.youtube.com/vi/dQw4w9WgXcQ/maxresdefault.jpg',
            $youtubeUrl->getThumbnailUrl()
        );

        $this->assertEquals(
            'https://www.youtube.com/embed/dQw4w9WgXcQ',
            $youtubeUrl->getEmbedUrl()
        );

        $this->assertEquals(
            'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
            $youtubeUrl->getWatchUrl()
        );
    }
}