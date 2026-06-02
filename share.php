<?php
/**
 * PIXIS SOCIAL MEDIATOR
 * Este archivo sirve metadatos puros a los bots de WhatsApp/Facebook/etc.
 */

// Forzar que el servidor NUNCA cachee esta respuesta.
header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');

$slug = isset($_GET['producto']) ? $_GET['producto'] : '';
$bannerId = isset($_GET['banner']) ? $_GET['banner'] : '';
$categoriaId = isset($_GET['categoria']) ? $_GET['categoria'] : '';
$theProduct = null;
$theBanner = null;
$theCategory = null;

// Diccionario de SEO Personalizado para Categorías (Restaurado de versión de prueba)
$CATEGORIAS_SEO = [
    "cargadores" => [
        "titulo" => "Pixis Informática | Cargadores y Cables Especializados",
        "descripcion" => "⚡ CARGADORES / CABLES USB TIPO C Y V8 — Encontrá cargadores rápidos de pared, fuentes para portátiles y conectividad premium con stock inmediato en Santiago del Estero.",
        "imagen" => "https://mistyrose-ibex-626891.hostingersite.com/assets/meta/banner-cargadores.jpg"
    ],
    "almacenamiento" => [
        "titulo" => "Pixis Informática | Discos SSD y Almacenamiento de todo tipo para tu PC",
        "descripcion" => "💾 DISCOS SÓLIDOS Y ALMACENAMIENTO M.2 NVMe — Optimizá la velocidad de tu PC o Notebook. Unidades de alto rendimiento y almacenamiento externo.",
        "imagen" => "https://mistyrose-ibex-626891.hostingersite.com/assets/meta/banner-almacenamiento.jpg"
    ],
    "memorias ram" => [
        "titulo" => "Pixis Informática | Memorias RAM de Alto Rendimiento para tu PC",
        "descripcion" => "🚀 MEMORIAS RAM DDR4 Y DDR5 — Potenciá tu rendimiento multitarea. Módulos de alta velocidad ideales para Gaming, Diseño y Oficina.",
        "imagen" => "https://mistyrose-ibex-626891.hostingersite.com/assets/meta/banner-ram.jpg"
    ],
    "cables" => [
        "titulo" => "Pixis Informática | Adaptadores y Cables todo lo que necesitas para tu PC y Consola",
        "descripcion" => "🔌 ADAPTADORES & CABLES — Conectividad garantizada. Cables HDMI, DisplayPort, adaptadores de video y red para PC y Consolas.",
        "imagen" => ""
    ],
    "camara de seguridad" => [
        "titulo" => "Pixis Informática | Cámaras de Seguridad y Vigilancia",
        "descripcion" => "📷 CÁMARAS DE SEGURIDAD — Protegé lo que más importa. Equipos de vigilancia en alta definición, cámaras IP y kits completos para tu hogar o comercio.",
        "imagen" => ""
    ],
    "fuentes" => [
        "titulo" => "Pixis Informática | Fuentes de Alimentación para cuidar tu inversión",
        "descripcion" => "🔋 FUENTES DE ALIMENTACIÓN — Energía estable y segura para tu hardware. Fuentes certificadas 80 Plus, modulares y de alta gama.",
        "imagen" => ""
    ],
    "gabinetes" => [
        "titulo" => "Pixis Informática | Gabinetes Gamers y de Oficina",
        "descripcion" => "🖥️ GABINETES GAMER — Diseños con flujo de aire optimizado, vidrio templado y coolers RGB. Encontrá el chasis perfecto para tu setup.",
        "imagen" => ""
    ],
    "herramientas" => [
        "titulo" => "Pixis Informática | Herramientas de Precisión",
        "descripcion" => "🔧 HERRAMIENTAS Y MANTENIMIENTO — Destornilladores de precisión, pastas térmicas y herramientas esenciales para service técnico y ensamble de PC.",
        "imagen" => ""
    ],
    "monitores" => [
        "titulo" => "Pixis Informática | Monitores Gamer full hd y de Oficina",
        "descripcion" => "🖥️ MONITORES — Disfrutá de la mejor definición. Pantallas de alta tasa de refresco, paneles IPS, curvos y planos para Gaming o Trabajo.",
        "imagen" => ""
    ],
    "notebook" => [
        "titulo" => "Pixis Informática | PC gamers, Notebooks y mini pcs",
        "descripcion" => "💻 PORTÁTILES Y MINI PCs — Notebooks para estudio, trabajo y gaming de las mejores marcas. Rendimiento móvil garantizado.",
        "imagen" => ""
    ],
    "periféricos" => [
        "titulo" => "Pixis Informática | Periféricos y Accesorios todo para tu PC",
        "descripcion" => "🖱️ TECLADOS, MOUSES Y AURICULARES — Periféricos ergonómicos y mecánicos para elevar tu experiencia de juego y productividad en el día a día.",
        "imagen" => ""
    ],
    "placas madres" => [
        "titulo" => "Pixis Informática | PLACAS MADRES AMD AM4 y AM5 ",
        "descripcion" => "🔲 PLACAS MADRES — La base de tu potencia. Chipsets Intel y AMD de última generación, listos para ensamblar tu nueva computadora.",
        "imagen" => ""
    ],
    "placas de video" => [
        "titulo" => "Pixis Informática | MIRA LAS PLACAS DE VIDEO NVIDIA y AMD DISPONIBLES",
        "descripcion" => "🎮 TARJETAS GRÁFICAS — Rendimiento extremo en tus juegos y diseño. GPUs Nvidia GeForce RTX, AMD Radeon, listas con stock inmediato.",
        "imagen" => ""
    ],
    "procesadores" => [
        "titulo" => "Pixis Informática | Procesadores AMD RYZEN ",
        "descripcion" => "⚙️ PROCESADORES INTEL Y AMD — El cerebro de tu máquina. CPUs de alto rendimiento para Gaming, Edición y Ofimática.",
        "imagen" => ""
    ],
    "red" => [
        "titulo" => "Pixis Informática | Conectividad y Redes",
        "descripcion" => "📡 ROUTERS, PLACAS WI-FI Y SWITCHES — Mantené tu conexión al máximo. Soluciones de conectividad cableada e inalámbrica de alto alcance.",
        "imagen" => ""
    ],
    "refrigeracion" => [
        "titulo" => "Pixis Informática | Soluciones termicas confiables para tu PC",
        "descripcion" => "❄️ COOLERS Y REFRIGERACIÓN LÍQUIDA — Disipadores de calor eficientes. Mantené tus temperaturas bajo control y el máximo rendimiento de tu procesador.",
        "imagen" => ""
    ],
    "sillas y escritorios gamer" => [
        "titulo" => "Pixis Informática | TODO PARA TU SETUP GAMER: SILLAS Y ESCRITORIOS ",
        "descripcion" => "🪑 ERGONOMÍA GAMER — Sillas ultra cómodas y escritorios premium para pasar horas jugando o trabajando con la mejor postura.",
        "imagen" => ""
    ],
    "destacados" => [
        "titulo" => "Pixis Informática | MIRA LOS PRODUCTOS DESTACADOS EN PIXIS",
        "descripcion" => "🔥 LOS MÁS ELEGIDOS — Descubrí los productos más populares y recomendados de nuestra tienda con la mejor relación calidad-precio.",
        "imagen" => ""
    ],
    "nuevos" => [
        "titulo" => "Pixis Informática | MIRA LOS NUEVOS INGRESOS EN PIXIS",
        "descripcion" => "✨ RECIÉN LLEGADOS — Las últimas novedades en tecnología y hardware que acaban de ingresar a nuestro catálogo. ¡No te las pierdas!",
        "imagen" => ""
    ]
];

$baseUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]";

// --- DETECCIÓN DE PLATAFORMA ---
$userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';
// Optimizando para WhatsApp Privado (facebookexternalhit)
$isWhatsApp = (stripos($userAgent, 'WhatsApp') !== false || stripos($userAgent, 'Telegram') !== false || stripos($userAgent, 'facebookexternalhit') !== false);

if ($bannerId) {
    $redirectUrl = $baseUrl . "/index.html?banner=" . urlencode($bannerId);
} elseif ($categoriaId) {
    $redirectUrl = $baseUrl . "/index.html?categoria=" . urlencode($categoriaId);
} else {
    $redirectUrl = $baseUrl . "/index.html" . ($slug ? "?producto=" . urlencode($slug) : "");
}

function makeSlug($text) {
    $text = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $text);
    $text = strtolower($text);
    $text = preg_replace('/[^a-z0-9]+/', '-', $text);
    $text = trim($text, '-');
    return $text;
}

function makeImageUrl($imgPath, $baseUrl) {
    if (strpos($imgPath, 'http') === 0) return $imgPath;
    $imgPath = str_replace('\\', '/', $imgPath);
    $imgPath = ltrim(trim($imgPath), '/');
    $segments = explode('/', $imgPath);
    $encodedSegments = array_map(function($seg) { return rawurlencode($seg); }, $segments);
    $encodedPath = implode('/', $encodedSegments);
    return $baseUrl . '/' . $encodedPath;
}

// 1. Cargar datos
if ($bannerId) {
    $siteData = @file_get_contents(__DIR__ . '/data/site.json');
    if ($siteData) {
        $site = json_decode($siteData, true);
        $siteBanners = $site['banners'] ?? [];
        if (isset($siteBanners[$bannerId])) { $theBanner = $siteBanners[$bannerId]; }
        if ($theBanner) {
            $allSlides = array_merge($site['carouselTop'] ?? [], $site['carouselBottom'] ?? []);
            foreach ($allSlides as $slide) { if (($slide['bannerId'] ?? '') === $bannerId) { $theBanner['img'] = $slide['imgPc'] ?? $slide['imgMobile'] ?? ''; break; } }
        }
    }
} elseif ($categoriaId) {
    $categoriesData = @file_get_contents(__DIR__ . '/data/categories.json');
    if ($categoriesData) {
        $categories = json_decode($categoriesData, true);
        if (is_array($categories)) { foreach ($categories as $cat) { if (isset($cat['id']) && strtolower($cat['id']) === strtolower($categoriaId)) { $theCategory = $cat; break; } } }
    }
    if ($theCategory) {
        if (!empty($theCategory['customIcon'])) { $theCategory['img'] = $theCategory['customIcon']; } else {
            $prodsData = @file_get_contents(__DIR__ . '/data/products.json');
            if ($prodsData) { $products = json_decode($prodsData, true);
                if (is_array($products)) { foreach ($products as $p) {
                    $assignedCats = [strtolower(trim($p['category'] ?? '')), strtolower(trim($p['category2'] ?? '')), strtolower(trim($p['category3'] ?? ''))];
                    if (in_array(strtolower($theCategory['id']), $assignedCats)) { $rawImg = $p['img'] ?? ''; $firstImg = trim(explode(',', $rawImg)[0]); if ($firstImg) { $theCategory['img'] = $firstImg; break; } }
                } }
            }
        }
    }
} elseif ($slug) {
    $prodsData = @file_get_contents(__DIR__ . '/data/products.json');
    if ($prodsData) {
        $products = json_decode($prodsData, true);
        if (is_array($products)) { foreach ($products as $p) { if (makeSlug($p['title'] ?? 'producto') === $slug) { $theProduct = $p; break; } } }
    }
}

// 2. Definir metadata final
if ($theProduct) {
    $title = $theProduct['title'] . " - Pixis Informatica | Precio especial: $" . number_format($theProduct['priceLocal'] ?? $theProduct['price'] ?? 0, 2, ',', '.');
    $rawDesc = trim($theProduct['desc'] ?? '');
    $description = mb_strlen($rawDesc) > 150 ? mb_substr($rawDesc, 0, 150) . '...' : ($rawDesc ?: 'Disponible en Pixis Informática');
    $directImg = makeImageUrl(trim(explode(',', $theProduct['img'] ?? '')[0]), $baseUrl);
    $image = $isWhatsApp ? $directImg : ($baseUrl . "/meta_image.php?url=" . urlencode($directImg));
} elseif ($theBanner) {
    $title = ($theBanner['t'] ?? 'Promoción') . " - Pixis Informatica | 🚀 Ofertas";
    $description = "Aprovechá las mejores ofertas en " . ($theBanner['t'] ?? '') . ".";
    $directImg = makeImageUrl($theBanner['img'] ?? 'img/logo_pixis.png', $baseUrl);
    $image = $isWhatsApp ? $directImg : ($baseUrl . "/meta_image.php?url=" . urlencode($directImg));
} elseif ($theCategory || isset($CATEGORIAS_SEO[strtolower($categoriaId)])) {
    $catLower = strtolower($categoriaId);
    $title = "Categoría " . ($theCategory['name'] ?? $categoriaId) . " - Pixis Informatica";
    $description = "Hardware de alto rendimiento en Pixis Informática.";
    $imgSource = $theCategory['img'] ?? ($theCategory['customIcon'] ?? 'img/logo_pixis.png');
    if (isset($CATEGORIAS_SEO[$catLower])) {
        $title = $CATEGORIAS_SEO[$catLower]['titulo'] ?: $title;
        $description = $CATEGORIAS_SEO[$catLower]['description'] ?: $description;
        if (empty($theCategory['customIcon'])) $imgSource = $CATEGORIAS_SEO[$catLower]['imagen'] ?: $imgSource;
    }
    $directImg = makeImageUrl($imgSource, $baseUrl);
    $image = $isWhatsApp ? $directImg : ($baseUrl . "/meta_image.php?url=" . urlencode($directImg));
} else {
    $title = "Pixis Informática | Especialistas en Computación";
    $description = "Tienda de computación online en Santiago del Estero.";
    $defaultImg = $baseUrl . "/img/logo_pixis.png";
    $image = $isWhatsApp ? $defaultImg : ($baseUrl . "/meta_image.php?url=" . urlencode($defaultImg));
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($title); ?></title>
    <meta name="description" content="<?php echo htmlspecialchars($description); ?>">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="Pixis Informática">
    <meta property="og:title" content="<?php echo htmlspecialchars($title); ?>">
    <meta property="og:description" content="<?php echo htmlspecialchars($description); ?>">
    <meta property="og:image" content="<?php echo htmlspecialchars($image); ?>">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:image:type" content="image/jpeg">
    <meta property="og:url" content="<?php echo htmlspecialchars($redirectUrl); ?>">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:image" content="<?php echo htmlspecialchars($image); ?>">
    <script>window.location.replace("<?php echo $redirectUrl; ?>");</script>
</head>
<body><p>Redirigiendo a Pixis Informática...</p></body>
</html>
