<?php
/**
 * PIXIS SOCIAL MEDIATOR - VERSIÓN RAÍZ V14
 */

header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');

$slug = isset($_GET['producto']) ? $_GET['producto'] : '';
$bannerId = isset($_GET['banner']) ? $_GET['banner'] : '';
$categoriaId = isset($_GET['categoria']) ? $_GET['categoria'] : '';
$theProduct = null;
$theBanner = null;
$theCategory = null;

// Diccionario de SEO Personalizado
$CATEGORIAS_SEO = [
    "cargadores" => ["titulo" => "Cargadores y Cables | Pixis", "descripcion" => "Cargadores rápidos y cables premium."],
    "almacenamiento" => ["titulo" => "Almacenamiento SSD | Pixis", "descripcion" => "Discos sólidos y memorias."],
    "memorias ram" => ["titulo" => "Memorias RAM | Pixis", "descripcion" => "Potencia para tu setup."],
    "destacados" => ["titulo" => "Productos Destacados | Pixis", "descripcion" => "Lo mejor de nuestra tienda."],
    "nuevos" => ["titulo" => "Nuevos Ingresos | Pixis", "descripcion" => "Recién llegados a stock."]
];

$baseUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
$currentUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

// DETECCIÓN DE PLATAFORMA (WhatsApp Privado = facebookexternalhit)
$userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';
$isWhatsApp = (stripos($userAgent, 'WhatsApp') !== false || stripos($userAgent, 'Telegram') !== false || stripos($userAgent, 'facebookexternalhit') !== false);

// Redirección humana (esta sí puede llevar el index.html)
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
    return $baseUrl . '/' . $imgPath;
}

// 1. Cargar datos
$prodsData = @file_get_contents(__DIR__ . '/data/products.json');
if ($slug && $prodsData) {
    $products = json_decode($prodsData, true);
    if (is_array($products)) {
        foreach ($products as $p) {
            if (makeSlug($p['title'] ?? 'p') === $slug) { $theProduct = $p; break; }
        }
    }
}

// 2. Definir metadata
if ($theProduct) {
    $title = $theProduct['title'] . " - Pixis | $" . number_format($theProduct['priceLocal'] ?? $theProduct['price'] ?? 0, 2, ',', '.');
    $description = mb_substr(trim($theProduct['desc'] ?? ''), 0, 150) . '...';
    $directImg = makeImageUrl(trim(explode(',', $theProduct['img'] ?? '')[0]), $baseUrl);
    $image = $isWhatsApp ? $directImg : ($baseUrl . "/meta_image.php?url=" . urlencode($directImg));
} else {
    $title = "Pixis Informática | Especialistas en Computación";
    $description = "Hardware y accesorios gamer en Santiago del Estero.";
    $image = $baseUrl . "/img/logo_pixis.png";
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
    <meta property="og:url" content="<?php echo htmlspecialchars($currentUrl); ?>">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:image" content="<?php echo htmlspecialchars($image); ?>">
    <script>window.location.replace("<?php echo $redirectUrl; ?>");</script>
</head>
<body><p>Redirigiendo a Pixis...</p></body>
</html>
