<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Blog;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class BlogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get admin and artist users
        $admin = User::where('role', 'admin')->first();
        $artist = User::where('role', 'artist')->where('is_approved', true)->first();

        if (!$admin) {
            $this->command->info('No admin user found. Skipping blog seeding.');
            return;
        }

        $blogs = [
            [
                'user_id' => $admin->id,
                'title' => 'Welcome to Panchi Gallery\'s New Blog',
                'excerpt' => 'We are excited to launch our new blog where we will share insights about Myanmar art, artist spotlights, and gallery updates.',
                'content' => '<p>Welcome to the official Panchi Gallery blog! We are thrilled to launch this new platform where we can share our passion for Myanmar art with our community.</p>
                <p>Through this blog, we will be featuring:</p>
                <ul>
                    <li>In-depth artist spotlights and interviews</li>
                    <li>Behind-the-scenes looks at our exhibitions</li>
                    <li>Educational content about Myanmar art history</li>
                    <li>Market trends and collecting tips</li>
                    <li>Gallery news and upcoming events</li>
                </ul>
                <p>Myanmar has a rich artistic heritage that spans centuries, from traditional Buddhist art to contemporary expressions. Our goal is to celebrate this heritage while supporting the next generation of Myanmar artists.</p>
                <p>Stay tuned for regular updates and join us on this journey of discovery and appreciation of Myanmar\'s vibrant art scene.</p>',
                'category' => 'Gallery Updates',
                'tags' => ['announcement', 'blog launch', 'Myanmar art', 'gallery news'],
                'is_published' => true,
                'is_featured' => true,
                'published_at' => '2024-01-15 10:00:00',
                'meta_title' => 'Welcome to Panchi Gallery\'s New Blog',
                'meta_description' => 'We are excited to launch our new blog where we will share insights about Myanmar art, artist spotlights, and gallery updates.',
                'meta_keywords' => 'Panchi Gallery, Myanmar art, blog launch, gallery news',
                'views_count' => 1250,
            ],
            [
                'user_id' => $admin->id,
                'title' => 'The Rise of Contemporary Myanmar Art',
                'excerpt' => 'Exploring how contemporary artists are redefining Myanmar\'s artistic landscape while honoring traditional techniques.',
                'content' => '<p>Myanmar\'s art scene has undergone a remarkable transformation over the past decade. Contemporary artists are increasingly gaining international recognition while maintaining deep connections to their cultural heritage.</p>
                <h3>Bridging Tradition and Modernity</h3>
                <p>Many of today\'s Myanmar artists are finding innovative ways to blend traditional techniques with contemporary themes. This fusion creates unique visual languages that speak to both local and global audiences.</p>
                <p>Artists are exploring themes such as:</p>
                <ul>
                    <li>Social and political change</li>
                    <li>Environmental concerns</li>
                    <li>Urbanization and modern life</li>
                    <li>Preservation of cultural identity</li>
                </ul>
                <h3>International Recognition</h3>
                <p>Myanmar artists are increasingly participating in international exhibitions, biennales, and art fairs. This exposure has helped elevate the profile of Myanmar art on the global stage.</p>
                <p>At Panchi Gallery, we are committed to supporting this movement by providing platforms for both established and emerging artists to showcase their work to wider audiences.</p>',
                'category' => 'Art News',
                'tags' => ['contemporary art', 'Myanmar artists', 'art trends', 'international art'],
                'is_published' => true,
                'is_featured' => true,
                'published_at' => '2024-02-20 14:30:00',
                'meta_title' => 'The Rise of Contemporary Myanmar Art',
                'meta_description' => 'Exploring how contemporary artists are redefining Myanmar\'s artistic landscape while honoring traditional techniques.',
                'meta_keywords' => 'contemporary Myanmar art, modern art, art trends, Myanmar artists',
                'views_count' => 890,
            ],
            [
                'user_id' => $artist ? $artist->id : $admin->id,
                'title' => 'Artist Spotlight: The Creative Process',
                'excerpt' => 'An intimate look into the daily practice and creative journey of a Myanmar artist.',
                'content' => '<p>Every artist has a unique creative process. In this spotlight, we explore the daily practice, inspirations, and challenges faced by Myanmar artists.</p>
                <h3>The Daily Routine</h3>
                <p>For many artists in Myanmar, the creative process begins early in the morning. The quiet hours before the city wakes provide ideal conditions for focused work. Whether painting in a home studio or working outdoors en plein air, consistency is key.</p>
                <h3>Finding Inspiration</h3>
                <p>Inspiration comes from many sources: the vibrant colors of a local market, the intricate patterns of traditional textiles, the serene beauty of pagodas at dawn, or the dynamic energy of street life.</p>
                <p>Many artists also draw inspiration from Myanmar\'s rich artistic traditions, studying ancient techniques and adapting them for contemporary expression.</p>
                <h3>Overcoming Challenges</h3>
                <p>Like artists everywhere, Myanmar artists face challenges: limited access to materials, balancing commercial work with personal projects, and finding opportunities for exhibition and sales.</p>
                <p>Despite these challenges, the passion for creating art drives artists forward, and the growing appreciation for Myanmar art provides hope for the future.</p>',
                'category' => 'Artist Spotlight',
                'tags' => ['artist interview', 'creative process', 'Myanmar artist', 'artist life'],
                'is_published' => true,
                'is_featured' => false,
                'published_at' => '2024-03-10 09:00:00',
                'meta_title' => 'Artist Spotlight: The Creative Process',
                'meta_description' => 'An intimate look into the daily practice and creative journey of a Myanmar artist.',
                'meta_keywords' => 'artist spotlight, creative process, Myanmar artist, artist interview',
                'views_count' => 654,
            ],
            [
                'user_id' => $admin->id,
                'title' => 'Collecting Myanmar Art: A Beginner\'s Guide',
                'excerpt' => 'Essential tips for new collectors interested in building a collection of Myanmar art.',
                'content' => '<p>Collecting art is a rewarding journey that combines personal passion with potential investment value. For those interested in Myanmar art, here are some essential tips to get started.</p>
                <h3>Start with Research</h3>
                <p>Before making your first purchase, spend time learning about Myanmar art history, prominent artists, and current market trends. Visit galleries, read books and articles, and attend exhibitions.</p>
                <h3>Buy What You Love</h3>
                <p>The most important rule of art collecting is to buy pieces that genuinely resonate with you. Your personal connection to the artwork will bring lasting enjoyment, regardless of its market value.</p>
                <h3>Consider Your Budget</h3>
                <p>Myanmar art offers options at various price points. Emerging artists often provide excellent value for collectors with limited budgets, while established artists\' works command higher prices.</p>
                <h3>Verify Authenticity</h3>
                <p>When purchasing, ensure you receive proper documentation including certificates of authenticity and provenance information. Reputable galleries like Panchi Gallery provide this documentation for all artworks.</p>
                <h3>Build Relationships</h3>
                <p>Develop relationships with galleries and artists. These connections can provide access to new works and insider knowledge about the art scene.</p>',
                'category' => 'Market Trends',
                'tags' => ['art collecting', 'beginner guide', 'art investment', 'Myanmar art market'],
                'is_published' => true,
                'is_featured' => false,
                'published_at' => '2024-04-05 11:00:00',
                'meta_title' => 'Collecting Myanmar Art: A Beginner\'s Guide',
                'meta_description' => 'Essential tips for new collectors interested in building a collection of Myanmar art.',
                'meta_keywords' => 'art collecting, beginner guide, art investment, Myanmar art market',
                'views_count' => 432,
            ],
            [
                'user_id' => $admin->id,
                'title' => 'Traditional Techniques in Modern Context',
                'excerpt' => 'How contemporary Myanmar artists are adapting traditional artistic techniques for modern expressions.',
                'content' => '<p>Myanmar\'s artistic traditions span centuries, encompassing diverse techniques from Buddhist painting to textile weaving. Today\'s artists are finding innovative ways to adapt these traditions for contemporary expression.</p>
                <h3>Traditional Painting Techniques</h3>
                <p>Traditional Myanmar painting often uses natural pigments derived from minerals and plants. Artists are rediscovering these materials, combining them with modern acrylics to create unique visual effects.</p>
                <h3>Textile Arts</h3>
                <p>Myanmar\'s rich textile traditions, including intricate weaving and embroidery patterns, inspire contemporary artists working in various media. Some incorporate actual textiles into their work, while others translate patterns into painting or sculpture.</p>
                <h3>Lacquerware Techniques</h3>
                <p>The centuries-old craft of lacquerware, known for its durability and beauty, influences artists working with mixed media and sculpture. The layering techniques of lacquerware find new expression in contemporary abstract works.</p>
                <h3>Preserving Heritage</h3>
                <p>By adapting traditional techniques, artists help preserve cultural heritage while keeping it relevant for new generations. This ensures that Myanmar\'s artistic traditions continue to evolve and thrive.</p>',
                'category' => 'Art Techniques',
                'tags' => ['traditional art', 'art techniques', 'cultural heritage', 'contemporary adaptation'],
                'is_published' => true,
                'is_featured' => false,
                'published_at' => '2024-05-01 16:00:00',
                'meta_title' => 'Traditional Techniques in Modern Context',
                'meta_description' => 'How contemporary Myanmar artists are adapting traditional artistic techniques for modern expressions.',
                'meta_keywords' => 'traditional art, art techniques, cultural heritage, Myanmar art',
                'views_count' => 321,
            ],
            [
                'user_id' => $admin->id,
                'title' => 'Upcoming Exhibition: Myanmar Contemporary Art Festival',
                'excerpt' => 'Get ready for our biggest exhibition of the year featuring works from over 30 Myanmar artists.',
                'content' => '<p>We are thrilled to announce the upcoming Myanmar Contemporary Art Festival, our most ambitious exhibition to date. This festival will showcase works from over 30 Myanmar artists, both established and emerging.</p>
                <h3>What to Expect</h3>
                <p>The festival will feature:</p>
                <ul>
                    <li>Over 100 artworks across various media</li>
                    <li>Live painting demonstrations</li>
                    <li>Artist talks and panel discussions</li>
                    <li>Workshops for aspiring artists</li>
                    <li>Special opening night reception</li>
                </ul>
                <h3>Dates and Location</h3>
                <p>The festival runs from June 15 to August 30, 2024, at the National Museum of Myanmar in Yangon. Opening night is June 15 at 6 PM.</p>
                <h3>Featured Artists</h3>
                <p>The exhibition includes works from some of Myanmar\'s most celebrated contemporary artists alongside exciting newcomers. This diversity ensures a rich and varied viewing experience.</p>
                <p>We invite art lovers, collectors, and anyone interested in Myanmar culture to join us for this celebration of artistic excellence.</p>',
                'category' => 'Exhibition Reviews',
                'tags' => ['exhibition', 'art festival', 'contemporary art', 'gallery event'],
                'is_published' => true,
                'is_featured' => true,
                'published_at' => '2024-05-20 10:00:00',
                'meta_title' => 'Upcoming Exhibition: Myanmar Contemporary Art Festival',
                'meta_description' => 'Get ready for our biggest exhibition of the year featuring works from over 30 Myanmar artists.',
                'meta_keywords' => 'exhibition, art festival, Myanmar art, gallery event',
                'views_count' => 567,
            ],
        ];

        foreach ($blogs as $blogData) {
            $slug = \Illuminate\Support\Str::slug($blogData['title']);
            Blog::firstOrCreate(['slug' => $slug], $blogData);
        }

        $this->command->info('Blog posts seeded successfully.');
    }
}
