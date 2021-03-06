<?php

namespace App\Tests\Twig;

use PHPUnit\Framework\TestCase;
use App\Twig\AppExtension;

class SluggerTest extends TestCase
{
    /**
     * @param string $string
     * @param string $slug
     * @dataProvider getSlugs
     */
    public function testSlugify(string $string, string $slug)
    {
        $slugger = new AppExtension;

        $this->assertSame($slug, $slugger->slugify($string));
    }

    public function getSlugs()
    {
//        return [
//           ['Lorem Ipsum', 'lorem-ipsum'],
//           [' Lorem Ipsum', 'lorem-ipsum'],
//        ];

        // alternative
        yield ['Lorem Ipsum', 'lorem-ipsum'];
        yield [' Lorem Ipsum', 'lorem-ipsum'];
        yield [' LOrEm IpsUm', 'lorem-ipsum'];
        yield ['!Lorem Ipsum!', 'lorem-ipsum'];
        yield ['lorem-ipsum', 'lorem-ipsum'];
        yield ['Children\'s books', 'childrens-books'];
        yield ['Five star movies', 'five-star-movies'];
        yield ['Adults 60+', 'adults-60'];
    }
}
