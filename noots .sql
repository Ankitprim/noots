-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 28, 2025 at 11:21 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `noots`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(3) NOT NULL,
  `name` varchar(200) NOT NULL,
  `email` varchar(200) NOT NULL,
  `password` varchar(200) NOT NULL,
  `image` varchar(999) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `name`, `email`, `password`, `image`, `created_at`) VALUES
(1, 'Ankit kushwaha', 'ankit@akt.com', '$2y$10$LRStcFEvP/StnGmYMSHdfOswS0QTQwwmFvdHk6KFdMsrtBCcOEQZq', 'https://ankitprim.github.io/portfolio/ankit_img.jpg', '2025-06-22 02:50:03');

-- --------------------------------------------------------

--
-- Table structure for table `article`
--

CREATE TABLE `article` (
  `id` int(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `views` int(255) NOT NULL,
  `sub_title` varchar(255) NOT NULL,
  `category` varchar(255) NOT NULL,
  `description` varchar(300) NOT NULL,
  `image` varchar(900) NOT NULL,
  `content` mediumtext NOT NULL,
  `author_id` int(255) NOT NULL,
  `tags` varchar(300) NOT NULL,
  `lable` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `article`
--

INSERT INTO `article` (`id`, `title`, `views`, `sub_title`, `category`, `description`, `image`, `content`, `author_id`, `tags`, `lable`, `created_at`) VALUES
(2, 'The Return of Live Music Festivals: What to Expect in 2025', 2, 'From Coachella to Tomorrowland, festival season is back and bigger than ever', 'Entertainment', 'After years of pandemic-era restrictions, 2025 marks a triumphant return for the global live music scene with sold-out festivals and surprise headliners.\r\n\r\n', 'https://baltimore.org/wp-content/uploads/2023/05/MRF2022_0807_194156-9715_TAH-scaled.jpg', '<p>Live music is experiencing a renaissance in 2025. Following years of uncertainty, music festivals are roaring back with record-breaking ticket sales and expanded lineups. Coachella, Glastonbury, and Tomorrowland have all reported sell-outs within hours, signaling that fans are eager to reconnect with the live experience.</p><p>What’s different this year? Festival organizers have embraced hybrid formats, offering both on-site and high-quality virtual access. Surprise appearances — like Beyoncé at Coachella and Daft Punk’s rumored reunion at Tomorrowland — are drawing buzz and boosting engagement on social media platforms.</p><p>Safety protocols remain in place, but the emphasis has shifted toward immersive fan experiences and sustainable practices, reflecting evolving audience values.</p>', 2, '#MusicFestivals #LiveMusic #Coachella2025 #Tomorrowland #ConcertNews', '', '2025-06-28 06:29:22'),
(3, 'Streaming Wars Heat Up: Netflix vs. Prime Video vs. Apple TV+', 3, 'Who\'s leading the pack in 2025, and what it means for subscribers', 'Entertainment', 'Streaming giants are battling for dominance, each rolling out exclusive titles, features, and subscription bundles.\r\n\r\n', 'https://th.bing.com/th/id/OIP.nzIJK_ovzYxLlF7JRa344gHaE-?w=198&h=180&c=7&r=0&o=7&pid=1.7&rm=3', '<p>The streaming landscape has never been more competitive. In 2025, Netflix, Prime Video, and Apple TV+ are vying for top position, each with unique strategies.</p><p>Netflix remains the content king, thanks to international hits like <i>Bloodline Tokyo</i> and a new partnership with Studio Ghibli. Amazon’s Prime Video has carved out a niche with live sports and award-winning sci-fi dramas, while Apple TV+ is betting big on prestige TV and original films with A-list talent.</p><p>New features like AI-curated playlists and interactive episodes are redefining how people watch. For subscribers, this means more choices — but also subscription fatigue. Many are turning to bundles or ad-supported tiers to manage costs.</p>', 2, '#StreamingWars #Netflix2025 #PrimeVideo #AppleTVPlus #BingeWatch', '', '2025-06-21 07:16:57'),
(4, 'AI in Hollywood: The Future of Filmmaking or a Threat to Creativity?', 2, 'Directors and writers weigh in on the growing role of artificial intelligence in movie production', 'Entertainment', 'As AI tools reshape scriptwriting, VFX, and casting, the film industry faces a crucial question: embrace or resist?', 'https://tse1.mm.bing.net/th/id/OIP.qj2xa5pJTbdCNNV_OdYHnwHaER?r=0&rs=1&pid=ImgDetMain&cb=idpwebpc2', '<p>Artificial Intelligence is no longer just a sci-fi trope — it\'s now a key player behind the scenes in Hollywood. From AI-assisted scriptwriting to deepfake technology enabling de-aged actors, the line between innovation and artistic integrity is blurring.</p><p>Some filmmakers, like James Cameron, see AI as a collaborative tool that streamlines editing and visualization. Others worry it could undermine creative jobs, particularly among writers and visual artists.</p><p>The 2025 Cannes Film Festival even featured its first AI-generated short film, sparking debate about awards eligibility and the definition of “authorship.” Unions and guilds are pushing for clearer regulations to protect human creators in this evolving landscape.</p><h3>&nbsp;</h3>', 2, '#AIinHollywood #FutureOfFilm #FilmmakingTech #CreativityVsAI #Cannes2025', '', '2025-06-28 06:30:21'),
(5, 'The Streaming Revolution: How On-Demand Entertainment is Redefining Pop Culture', 0, 'From Netflix to TikTok, digital platforms are transforming how we consume, create, and connect through entertainment.', 'Entertainment', 'This article explores the impact of streaming services and digital content platforms on the entertainment industry. It highlights the shift from traditional media to on-demand consumption, the rise of content creators, and how audiences are shaping trends in real-time.', 'https://th.bing.com/th/id/OIP.jRFzRHsLJwFWAndqgvhXrQHaE8?w=243&h=180&c=7&r=0&o=7&pid=1.7&rm=3', '<p>The entertainment industry has undergone a seismic shift in the past decade, thanks to the explosive growth of streaming platforms and digital content creation. What was once dominated by scheduled television broadcasts and big-screen movie releases is now ruled by on-demand services like Netflix, YouTube, Spotify, and social media apps such as TikTok and Instagram.</p><h3>The Fall of Traditional Media</h3><p>Cable television and print publications have seen significant declines as audiences gravitate toward more personalized and convenient viewing experiences. With platforms like Netflix, users can binge-watch entire seasons of their favorite shows, while Spotify offers endless playlists tailored to individual tastes. The traditional “prime time” no longer dictates when or how people consume entertainment.</p><h3>The Rise of the Content Creator</h3><p>Digital platforms have democratized fame. Today, anyone with a smartphone and internet connection can become a content creator. TikTok stars, YouTube vloggers, and Twitch streamers are now household names, often with larger audiences than mainstream celebrities. This shift has empowered individuals to build careers independently, attracting brand deals, sponsorships, and millions of followers.</p><h3>Audience as the New Influencer</h3><p>In the streaming era, the audience plays a vital role in determining what’s popular. Algorithms respond to likes, shares, and watch times, making user behavior a powerful force in deciding which songs trend or which series get renewed. Fandoms form in online communities, elevating niche shows into global phenomena and influencing major studio decisions.</p><h3>Challenges and Opportunities</h3><p>While the digital revolution offers creative freedom and global reach, it also introduces challenges like content oversaturation, mental health pressures for creators, and concerns over algorithm transparency. However, it also opens the door to more diverse voices and stories that might not have found space in traditional media.</p><h3>The Future of Entertainment</h3><p>Looking ahead, entertainment will likely become even more interactive and immersive. Technologies like virtual reality (VR), augmented reality (AR), and AI-driven content are poised to push the boundaries further. One thing is certain: the future of entertainment is in the hands of its audience—active, global, and always online.</p>', 2, 'TikTok, Netflix, pop culture trends, social media influence,', '', '2025-06-18 11:53:56'),
(6, 'The Future of Renewable Energy', 2, 'How Science is Powering the Green Revolution', 'Science', 'A deep dive into the technologies transforming renewable energy and how they are shaping a sustainable future.', 'https://avaada.com/wp-content/uploads/solar-and-wind.jpg', '<p>Renewable energy is no longer a fringe concept—it’s at the core of global efforts to combat climate change. Advances in solar, wind, hydro, and bioenergy technologies have made clean energy more accessible and affordable. Scientists are now exploring next-generation solutions like perovskite solar cells, floating wind farms, and hydrogen fuel derived from electrolysis.</p><p>&nbsp;</p><p>Battery storage and smart grid integration are also critical for managing supply and demand. The fusion of AI with energy systems allows for predictive maintenance, efficient energy distribution, and integration of decentralized sources.</p><p>However, challenges remain: storage efficiency, rare material dependencies, and infrastructure investments are key hurdles. Despite this, science continues to pave the way for a cleaner, greener planet.</p><p>&nbsp;</p><p><strong>Keywords:</strong> Renewable energy, solar power, wind energy, green technology, sustainable science, smart grid, hydrogen fuel</p>', 3, 'Renewable energy, solar power, wind energy, green technology, sustainable science, smart grid, hydrogen fuel', '', '2025-06-21 02:17:33'),
(7, 'CRISPR and the Future of Genetic Engineering', 2, 'Editing the Code of Life with Precision', 'Science', 'An exploration of CRISPR gene editing and its revolutionary impact on medicine, agriculture, and biology.', 'https://petrieflom.law.harvard.edu/wp-content/uploads/2019/09/CRISPR_illustration.jpg', '<p>CRISPR (Clustered Regularly Interspaced Short Palindromic Repeats) is one of the most powerful tools in modern science. It allows researchers to edit DNA with unprecedented precision. Originally discovered in bacterial defense systems, CRISPR has been adapted to modify genes in plants, animals, and humans.</p><p>&nbsp;</p><p>Applications range from curing genetic disorders such as sickle cell anemia to engineering pest-resistant crops. In agriculture, CRISPR enables precision breeding without introducing foreign DNA, making food more resilient and nutritious.</p><p>&nbsp;</p><p>However, ethical concerns persist, especially regarding human embryo editing and potential unintended mutations. Strict regulations and continued research are necessary to balance innovation with responsibility.</p>', 3, 'CRISPR, gene editing, biotechnology, genetics, genome engineering, bioethics, medical innovation', '', '2025-06-24 00:42:32'),
(8, 'Artificial Intelligence in Scientific Discovery', 2, 'From Lab Assistant to Research Partner', 'Science', 'How AI is accelerating breakthroughs across physics, chemistry, and biology.', 'https://news.yale.edu/sites/default/files/styles/full/public/ynews-risks-ai-research.jpg?itok=JlO4dGlz', '<p>Artificial Intelligence (AI) is transforming the scientific process. From predicting molecular behavior to automating lab experiments, AI helps researchers solve complex problems faster and more efficiently.</p><p>&nbsp;</p><p>In drug discovery, machine learning models can identify potential drug candidates by analyzing chemical structures and predicting interactions. In physics, AI assists in data-heavy experiments such as those at CERN, revealing patterns hidden in vast datasets.</p><p>&nbsp;</p><p>AI is also enhancing climate models, improving the accuracy of weather forecasts and predicting long-term environmental changes. Yet, the black-box nature of many AI systems raises concerns about transparency and reproducibility in scientific research.</p>', 3, 'Artificial intelligence, machine learning, scientific computing, drug discovery, AI in science, data analysis, automation', '', '2025-06-21 08:20:59'),
(9, 'The Quantum Computing Revolution', 2, 'Computing at the Edge of Physics', 'Science', 'Understanding the science behind quantum computing and its transformative potential.', 'https://scx2.b-cdn.net/gfx/news/hires/2022/symmetry-protected-maj.jpg', '<p>Quantum computing leverages the principles of quantum mechanics—superposition, entanglement, and interference—to process information in fundamentally new ways. Unlike classical bits, quantum bits (qubits) can exist in multiple states at once, enabling massive parallelism.</p><p>&nbsp;</p><p>Tech giants and research labs are racing to build stable, scalable quantum computers. Applications include simulating molecular interactions for new drugs, solving complex optimization problems, and advancing cryptography.</p><p>&nbsp;</p><p>Despite recent milestones like achieving quantum supremacy, practical quantum computing faces obstacles such as qubit stability (decoherence) and error correction. Researchers are developing new qubit types (e.g., topological qubits) and exploring hybrid models that combine quantum and classical systems.</p>', 3, 'Quantum computing, qubits, quantum mechanics, computational physics, quantum supremacy, quantum algorithms, future computing', '', '2025-06-21 02:14:52'),
(12, 'The Language of Color: What Red Means Around the World', 0, ' A Global Palette of Meaning', 'Culture', ' A journey through how different cultures interpret the color red — from celebration to caution.', 'https://cdn2.psychologytoday.com/assets/styles/manual_crop_1_91_1_1528x800/public/teaser_image/blog_entry/2025-01/shutterstock_501657283.jpg?itok=siuJvtxu', '<p>Red is a powerful hue. But what it means can depend deeply on where you\'re standing.</p><p>In China, red is the color of luck, celebration, and joy. It is worn at weddings, displayed at Lunar New Year, and believed to ward off evil spirits.</p><blockquote><p>“Red is not the color of blood in China — it is the color of life.” — <i>Zhang Yimou</i>, Chinese film director</p></blockquote><p>In South Africa, however, red is used in the country’s flag to symbolize the bloodshed of the struggle for independence. In Western cultures, red often signals danger or passion — a dichotomy between love and alarm.</p><p>Meanwhile, among the Maasai of Kenya and Tanzania, red is sacred. Warriors wear red <i>shúkà</i> robes, and it represents bravery, strength, and unity.</p><p>The symbolic spectrum of red is not just visual, but emotional and historical. Color speaks where language cannot.</p>', 4, 'colours ', '', '2025-06-18 11:54:14'),
(14, 'Sacred Spaces: The Role of Architecture in Cultural Identity', 1, 'More Than Stone and Steel', 'Culture', 'Walk into a Gothic cathedral, a Balinese temple, or a Navajo hogan, and you’ll sense something more than structure. Architecture can be spiritual, cultural, and deeply symbolic.', 'https://www.designdekko.com/uploads/content/055.jpg', '<p>The Hagia Sophia in Istanbul, once a church, then a mosque, now a museum, tells the story of shifting empires and faiths.</p><blockquote><p>“Architecture is a visual art, and the buildings speak for themselves.” — <i>Julia Morgan</i>, architect</p></blockquote><p>In Japan, Shinto shrines blend seamlessly with nature, reflecting the cultural belief in harmony with the environment. Contrast that with the towering minarets of Islamic architecture, reaching skyward, echoing the call to prayer and a vertical connection to the divine.</p><p>In indigenous communities, architecture is communal. The longhouse of the Iroquois Confederacy wasn’t just a home — it symbolized the political unity of the tribes.</p><p>Buildings are cultural memory cast in stone. They teach us who we were, who we are, and who we strive to be.</p>', 4, 'Architecture', '', '2025-06-21 02:27:33'),
(16, 'The Vibrant Cultural Heritage of Nagaland: A Tapestry of Tribes and Traditions', 1, 'Exploring the Indigenous Rituals, Artistry, and Festivals of Northeast India’s Enigmatic Highlands', 'Culture', 'Nagaland, nestled in India’s northeastern frontier, is a realm where ancient tribal legacies thrive. Home to 17 major Naga tribes, each with distinct dialects, customs, and artistic expressions, this state offers a living museum of human heritage. From warrior traditions to harvest festivals and int', 'https://www.holidify.com/images/bgImages/NAGALAND.jpg', '<p><strong>1. Tribal Diversity: The Heartbeat of Nagaland</strong><br>Nagaland’s cultural landscape is woven from its tribes—Ao, Angami, Konyak, Sumi, and more—each guarding unique traditions. The <strong>Konyak</strong> tribe, famed for their tattooed faces and historic headhunting past, now showcases these symbols as marks of valor. Tribal governance revolves around village councils (<i>Gaon Buras</i>), where elders resolve disputes through oral traditions, embodying communal harmony.</p><p><strong>2. Festivals: Rhythms of Earth and Spirit</strong><br>Festivals anchor Naga life, celebrating seasons, harvests, and ancestral spirits:</p><p><strong>Hornbill Festival</strong>: A December extravaganza uniting all tribes. Witness warrior dances, log drums, and fiery <i>Zutho</i> (rice beer).</p><p><strong>Sekrenyi</strong> (Angami): A purification ritual featuring sacred baths, feather-adorned attire, and communal feasts.</p><p><strong>Moatsu</strong> (Ao): Marking seed-sowing with songs, buffalo sacrifices, and bamboo mug exchanges.</p><p><strong>3. Craftsmanship: Weaving Legacy into Life</strong><br>Naga artisans transform nature into art:</p><p><strong>Textiles</strong>: Tribal shawls (<i>Rongsu</i> for Aos, <i>Tsüngkotepsü</i> for Aos) use bold geometric patterns dyed with indigo and wild herbs. Each design signifies social status or clan identity.</p><p><strong>Beadwork &amp; Pottery</strong>: Vibrant bead necklaces (<i>Rongkhim</i>) and hand-coiled pottery preserve prehistoric techniques.</p><p><strong>Wood &amp; Bamboo</strong>: Intricately carved totems guard villages, while bamboo mugs (<i>Tathu</i>) and baskets serve daily life.</p><p><strong>4. Oral Traditions: Echoes of Ancestral Wisdom</strong><br>With no historic written scripts, Nagaland’s history survives through <strong>folktales</strong>, war chants, and epic songs. Legends of origin—like the <i>Ao</i> tribe’s emergence from <i>Longterok</i> (six stones)—are sung during festivals, binding generations.</p><p><strong>5. Challenges &amp; Preservation</strong><br>Modernization threatens indigenous knowledge. Yet, initiatives like the <strong>Tribal Heritage Centre (Kohima)</strong> and digital archives of Naga dialects strive to safeguard traditions. Ecotourism, guided by tribes themselves, offers sustainable engagement.</p><p><strong>6. Culinary Heritage</strong><br>Smoked pork with <i>axone</i> (fermented soybean), fiery <i>bhut jolokia</i> chutneys, and bamboo-steamed fish reflect a cuisine rooted in forest bounty. Meals end with <i>Zutho</i>, shared as a communal bond.</p><p><br>&nbsp;</p>', 4, 'Nagaland, Cultural Heritage, Naga Tribes, Hornbill Festival, Indigenous Traditions, Tribal Crafts, Northeast India, Nagaland Tourism, Traditional Weaving, Naga Festivals', '', '2025-06-21 02:34:37');

-- --------------------------------------------------------

--
-- Table structure for table `article_views`
--

CREATE TABLE `article_views` (
  `id` int(11) NOT NULL,
  `article_id` int(11) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `view_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `article_views`
--

INSERT INTO `article_views` (`id`, `article_id`, `ip_address`, `view_date`) VALUES
(3, 1, '::1', '2025-06-20'),
(17, 1, '::1', '2025-06-21'),
(4, 2, '::1', '2025-06-20'),
(13, 2, '::1', '2025-06-21'),
(6, 3, '192.168.43.1', '2025-06-20'),
(5, 3, '::1', '2025-06-20'),
(18, 3, '::1', '2025-06-21'),
(9, 4, '::1', '2025-06-20'),
(11, 4, '::1', '2025-06-21'),
(8, 6, '::1', '2025-06-20'),
(12, 6, '::1', '2025-06-21'),
(1, 7, '::1', '2025-06-20'),
(15, 7, '::1', '2025-06-21'),
(20, 7, '::1', '2025-06-24'),
(7, 8, '::1', '2025-06-20'),
(19, 8, '::1', '2025-06-21'),
(2, 9, '::1', '2025-06-20'),
(10, 9, '::1', '2025-06-21'),
(14, 14, '::1', '2025-06-21'),
(16, 16, '::1', '2025-06-21');

-- --------------------------------------------------------

--
-- Table structure for table `author`
--

CREATE TABLE `author` (
  `id` int(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `username` varchar(200) NOT NULL,
  `password` varchar(200) NOT NULL,
  `bio` varchar(300) NOT NULL,
  `expertise` varchar(255) NOT NULL,
  `img` varchar(200) NOT NULL,
  `joined` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `testimonial` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `author`
--

INSERT INTO `author` (`id`, `email`, `username`, `password`, `bio`, `expertise`, `img`, `joined`, `testimonial`) VALUES
(2, 'knowra@akt.com', 'Knowra ', '$2y$10$l4X4CAPNn7yLw55Cf7aOf.W.Va3PMIxLiWgotKhHL32CnLJ24HWIO', '', '', 'https://image.cdn2.seaart.me/2025-06-17/d18iljde878c73c2hf5g-3/e349af9c0e32b1e3a6f2b5d0831c79d0_high.webp', '2025-06-20 07:02:43', 'Joining The  noots my writing career. Within six months, I tripled my readership and connected with editors from major publications'),
(3, 'ami@akt.com', 'Amelia', '$2y$10$ekmhR.xaTYcsOaMQuLyjNuWlDSGQ/VC93/3NxvGJfYdJNKfYUsJau', '', 'Science', 'https://image.cdn2.seaart.me/2023-12-04/24434789138357253/16288fcdbc2c56b259261e9c98732dedcee4413f_high.webp', '2025-06-18 11:48:35', 'The editorial support here is exceptional. My articles have never been stronger, and I\'ve developed a loyal following of readers who value my insights'),
(4, 'megu@akt.com', 'megu', '$2y$10$pyuEO6jDLso6Tnf.tGlMruwMYlttJi.7WabQx4wH7kb1zHTBaXauS', '', '', 'https://image.cdn2.seaart.me/2025-02-08/cujb6vde878c73d05fp0/44ababcdad1b8ddd92ba2884e3fca3ce934d852e_high.webp', '2025-06-20 03:43:01', 'Joining The  noots my writing career. Within six months, I tripled my readership and connected with editors from major publications'),
(6, 'ank@akt.com', 'Ankit kushwaha', '$2y$10$3Evqxnx0asDJnbMOkIKjZ.tUzDPapDoaklRrjufN71H/2usjbiYxO', 'i\'m ankit kushwaha', '', 'https://ankitprim.github.io/portfolio/ankit_img.jpg', '2025-06-28 06:51:59', '');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(255) NOT NULL,
  `Name` varchar(200) NOT NULL,
  `description` varchar(1111) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `Name`, `description`, `created_at`) VALUES
(1, 'Technology', '', '2025-06-21 10:30:35'),
(2, 'Science', '', '2025-06-21 10:30:46'),
(3, 'Health', '', '2025-06-21 10:31:05'),
(4, 'Entertainment', '', '2025-06-21 10:31:44'),
(5, 'Culture', '', '2025-06-21 10:32:05'),
(6, 'Education', '', '2025-06-21 10:32:44'),
(7, 'Sports', '', '2025-06-21 10:33:09');

-- --------------------------------------------------------

--
-- Table structure for table `disclaimer`
--

CREATE TABLE `disclaimer` (
  `id` int(11) NOT NULL,
  `content` mediumtext NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `disclaimer`
--

INSERT INTO `disclaimer` (`id`, `content`, `created_at`) VALUES
(1, 'The information provided on this website is for educational purposes only. All content, including text, graphics, images, and other materials, is intended to be used as a general guide for learning and informational purposes. It is not intended to substitute professional advice, diagnosis, or treatment of any kind.\r\n\r\nWhile we strive to ensure the accuracy and reliability of the information presented, we make no warranties or representations of any kind, express or implied, about the completeness, accuracy, reliability, suitability, or availability with respect to the website or the information, products, services, or related graphics contained on the website for any purpose.\r\n\r\nAny reliance you place on such information is therefore strictly at your own risk.\r\n\r\nIn no event will the website owner or contributors be liable for any loss or damage, including without limitation, indirect or consequential loss or damage, or any loss or damage whatsoever arising from loss of data or profits arising out of, or in connection with, the use of this website.\r\n\r\nBy using this website, you agree to this disclaimer and its terms.\r\n\r\n', '2025-06-22 04:10:30'),
(2, '\r\nयह वेबसाइट केवल शैक्षिक उद्देश्यों के लिए बनाई गई है। इस वेबसाइट पर उपलब्ध सभी जानकारी, जैसे कि लेख, ग्राफिक्स, चित्र और अन्य सामग्री, केवल सामान्य ज्ञान और शिक्षा के लिए प्रदान की गई है। यह किसी भी प्रकार की व्यावसायिक सलाह, निदान या उपचार का विकल्प नहीं है।\r\n\r\nहम यहां प्रस्तुत जानकारी की सटीकता और विश्वसनीयता बनाए रखने का पूरा प्रयास करते हैं, लेकिन हम वेबसाइट पर दी गई किसी भी जानकारी, उत्पाद, सेवाओं या ग्राफिक्स की पूर्णता, सटीकता, विश्वसनीयता या उपयुक्तता के बारे में कोई गारंटी नहीं देते।\r\n\r\nइस वेबसाइट पर दी गई जानकारी पर आपकी निर्भरता पूरी तरह से आपके अपने जोखिम पर है।\r\n\r\nइस वेबसाइट के उपयोग से होने वाले किसी भी प्रकार के नुकसान या क्षति के लिए, चाहे वह प्रत्यक्ष हो या अप्रत्यक्ष, हम उत्तरदायी नहीं होंगे।\r\n\r\nइस वेबसाइट का उपयोग करके, आप इस अस्वीकरण और इसकी शर्तों से सहमत होते हैं।\r\n', '2025-06-22 04:10:40');

-- --------------------------------------------------------

--
-- Table structure for table `featured`
--

CREATE TABLE `featured` (
  `id` int(1) NOT NULL,
  `article_id` int(200) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `featured`
--

INSERT INTO `featured` (`id`, `article_id`, `created_at`) VALUES
(1, 14, '2025-06-19 14:30:11');

-- --------------------------------------------------------

--
-- Table structure for table `top_story`
--

CREATE TABLE `top_story` (
  `id` int(9) NOT NULL,
  `article_id` int(200) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `top_story`
--

INSERT INTO `top_story` (`id`, `article_id`, `created_at`) VALUES
(1, 2, '2025-06-18 10:52:13'),
(2, 4, '2025-06-18 10:52:13'),
(3, 7, '2025-06-18 10:52:24'),
(4, 9, '2025-06-18 10:52:24');

-- --------------------------------------------------------

--
-- Table structure for table `tranding`
--

CREATE TABLE `tranding` (
  `id` int(9) NOT NULL,
  `article_id` int(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tranding`
--

INSERT INTO `tranding` (`id`, `article_id`) VALUES
(1, 2),
(2, 6),
(3, 4),
(4, 13),
(5, 7),
(6, 11);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `article`
--
ALTER TABLE `article`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `article_views`
--
ALTER TABLE `article_views`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_view` (`article_id`,`ip_address`,`view_date`);

--
-- Indexes for table `author`
--
ALTER TABLE `author`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `disclaimer`
--
ALTER TABLE `disclaimer`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `featured`
--
ALTER TABLE `featured`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `top_story`
--
ALTER TABLE `top_story`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tranding`
--
ALTER TABLE `tranding`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `article`
--
ALTER TABLE `article`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `article_views`
--
ALTER TABLE `article_views`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `author`
--
ALTER TABLE `author`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `disclaimer`
--
ALTER TABLE `disclaimer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `featured`
--
ALTER TABLE `featured`
  MODIFY `id` int(1) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `top_story`
--
ALTER TABLE `top_story`
  MODIFY `id` int(9) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tranding`
--
ALTER TABLE `tranding`
  MODIFY `id` int(9) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
